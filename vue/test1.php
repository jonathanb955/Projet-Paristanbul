<?php
session_start();
$flash      = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

$isLoggedIn = !empty($_SESSION['user_id']);
$username   = $_SESSION['user_name'] ?? 'Client';
$isAdmin    = (!empty($_SESSION['user_id']) && (($_SESSION['user_role'] ?? '') === 'admin'));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Paristanbul — Supermarché</title>
    <meta name="description" content="Paristanbul : vos courses, nos magasins, notre catalogue interactif et nos meilleures offres." />

    <!-- Fonts + Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    <!-- Leaflet map + PageFlip (utiles plus bas dans la page, je les laisse ici si tu veux les garder) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css">

    <style>
        :root{
            --black:#0a0c10; --blue:#0b3b8a; --red:#7b0f20;
            --pi-blue:#2E4C97; --pi-red:#D6452E;

            --text:#ffffff;
            --muted:#c9d4ea;
            --muted-2:#cfd5e6;

            --bg-1:#0B1326;
            --bg-2:#0A0F1F;

            --panel:#0f1320;
            --panel-soft:#121a34;
            --panel-alt:#111418;

            --edge:#1b2235;
            --ring:#2c59ff55;

            --borderSoft:rgba(255,255,255,.07);
            --borderHard:rgba(255,255,255,.14);

            --page-bg:
                    radial-gradient(1000px 500px at 10% 10%, rgba(46,76,151,.25), transparent 60%),
                    radial-gradient(900px 600px at 90% 10%, rgba(214,69,46,.18), transparent 55%),
                    linear-gradient(180deg, var(--bg-1), var(--bg-2) 70%);
        }

        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0;
            font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;
            color:var(--text);
            background:transparent;
            overflow-x:hidden;
        }
        a{color:inherit;text-decoration:none}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}

        /* ========= FOND GLOBAL ========= */
        #page-bg{
            position:fixed;
            inset:0;
            z-index:-4;
            pointer-events:none;
            background:var(--page-bg);
        }
        .pi-orbs{
            position:fixed;
            inset:0;
            z-index:-3;
            pointer-events:none;
            overflow:hidden;
        }
        .pi-orbs .orb{
            position:absolute;
            width:48vmax;
            height:48vmax;
            border-radius:9999px;
            filter:blur(80px);
            opacity:.75;
            mix-blend-mode:screen;
        }
        .pi-orbs .blue{ background:rgba(46,76,151,.18) }
        .pi-orbs .red { background:rgba(226,27,60,.16) }

        .pi-orbs .a{ top:-10vmax; left:-6vmax;  animation:orbA 36s linear infinite }
        .pi-orbs .b{ top:-8vmax;  right:-10vmax; animation:orbB 42s linear infinite }
        .pi-orbs .c{ bottom:-12vmax; left:15vw;  animation:orbC 40s linear infinite; width:42vmax;height:42vmax }
        .pi-orbs .d{ bottom:-14vmax; right:10vw; animation:orbD 46s linear infinite; width:50vmax;height:50vmax }

        @keyframes orbA{50%{transform:translate3d(4vw,2vh,0) scale(1.05)}}
        @keyframes orbB{50%{transform:translate3d(-3vw,3vh,0) scale(1.03)}}
        @keyframes orbC{50%{transform:translate3d(2vw,-2vh,0) scale(1.06)}}
        @keyframes orbD{50%{transform:translate3d(-2vw,-3vh,0) scale(1.04)}}

        /* glossy pulse halo dans les orbes */
        .pi-orbs .orb::after{
            content:"";
            position:absolute;
            inset:0;
            border-radius:inherit;
            background:radial-gradient(circle at 30% 30%,rgba(255,255,255,.4)0%,rgba(255,255,255,0)60%);
            mix-blend-mode:screen;
            opacity:.2;
            filter:blur(40px);
            animation:orbPulse 8s ease-in-out infinite;
        }
        @keyframes orbPulse{
            0%,100% { transform:scale(1); opacity:.2; }
            50%     { transform:scale(1.05); opacity:.35; }
        }

        /* ====== BANDEAU défilant haut de page ====== */
        .marquee{
            position:relative;
            overflow:hidden;
            border-top:1px solid #151a2a;
            border-bottom:1px solid #151a2a;
            background: linear-gradient(180deg,#0f1525,#0c1223) !important;
        }
        .marquee__track{
            display:flex;
            width:max-content;
            will-change:transform;
            animation:marquee-roll 28s linear infinite;
        }
        .marquee:hover .marquee__track{ animation-play-state:paused; }
        .marquee__group{
            display:flex;
            gap:40px;
            padding:10px 0;
        }
        @keyframes marquee-roll{
            from{ transform:translateX(0)}
            to  { transform:translateX(-50%)}
        }
        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 12px;
            border-radius:999px;
            background:linear-gradient(145deg,#121a34,#0f162a);
            border:1px solid #1b2744;
            font-size:.92rem;
            color:#fff;
            box-shadow:0 16px 30px rgba(0,0,0,.8);
        }
        .pill .dot{
            width:8px;
            height:8px;
            border-radius:50%;
            background:conic-gradient(from 90deg,var(--red),var(--blue));
        }

        /* ========= HEADER .pi-simple (même base que postuler.php) ========= */
        header.pi-simple{
            position: relative;
            isolation: isolate;
            background:transparent !important;
            z-index: 0;
        }
        header.pi-simple::before{
            content:"";
            position:absolute;
            inset:0;
            z-index:-1;
            left:50%; right:50%;
            margin-left:-50vw; margin-right:-50vw;
            background: linear-gradient(180deg, #0f1525 0%, #0c1223 100%) !important;
            border-bottom:1px solid #141a2b;
            box-shadow: inset 0 -12px 40px rgba(0,0,0,.35);
        }

        .pi-simple .topbar{
            display:grid;
            grid-template-columns:1fr minmax(200px, 1fr) 1fr;
            align-items:center;
            gap:16px;
            padding-block: clamp(18px, 3.5vh, 40px);
        }

        /* Colonne gauche (réseaux) */
        .pi-simple .left-col{display:flex}
        .pi-simple .social-group{
            display:flex;
            flex-direction:column;
            align-items:center;
            width:max-content
        }
        .pi-simple .social{
            display:flex;
            align-items:center;
            gap:16px;
            color:var(--muted)
        }
        .pi-simple .social a{
            font-size:18px;
            color:var(--muted)
        }
        .pi-simple .social a:hover{color:#fff}
        .pi-simple .join{
            font-size:13px;
            color:var(--muted);
            font-weight:800;
            margin-top:6px;
            text-align:center
        }

        /* Colonne centre (logo + since 1993) */
        .pi-simple .brand{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            gap:10px
        }
        .pi-simple .brand img{
            height: clamp(60px, 9vw, 72px)
        }
        .pi-simple .tagline{
            display:flex;
            align-items:center;
            gap:14px;
            color:var(--muted);
            font-size: clamp(13px, 1.3vw, 16px);
            line-height:1
        }
        .pi-simple .tagline .rule{
            width: clamp(58px, 9vw, 92px);
            height:1px;
            background:rgba(255,255,255,.06)
        }

        /* Colonne droite : téléphone + login btn façon "magnet" */
        .pi-simple .right-col{
            display:flex;
            flex-direction:column;
            align-items:flex-end;
            gap:12px;
            font-weight:800;
        }
        .pi-simple .right-col i{color:#c9d4ea}
        .pi-simple .right-col .phone-line,
        .pi-simple .right-col .phone-row{
            display:flex;
            align-items:center;
            gap:10px;
        }
        .pi-simple .phone{
            font-size: clamp(14px, 1.2vw, 18px);
            color:#e7ecf5
        }

        /* bouton login/déco */
        .btn-login.magnet{
            position:relative;
            border-radius:16px;
            display:inline-flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            gap:8px;
            padding:14px 18px;
            min-width:130px;
            text-align:center;
            font-weight:800;
            font-size:12.5px;
            line-height:1.05;
            letter-spacing:.06em;
            text-transform:uppercase;
            color:#eaf0ff;

            background:
                    linear-gradient(180deg, #1c2b59, #0f1833) padding-box,
                    linear-gradient(135deg, #3a58ff, #e5473a) border-box;
            border:1px solid transparent;

            box-shadow:
                    0 12px 26px rgba(0,0,0,.35),
                    inset 0 1px 0 rgba(255,255,255,.06),
                    0 0 40px rgba(46,76,151,.4);

            transition:.18s;
        }
        .btn-login.magnet i{
            font-size:18px;
            line-height:1;
            width:40px;
            height:40px;
            border-radius:999px;
            display:grid;
            place-items:center;
            background: radial-gradient(120% 120% at 30% 20%, #2a3f86 0%, #182650 45%, #0f1833 100%);
            box-shadow:
                    inset 0 1px 0 rgba(255,255,255,.08),
                    0 6px 18px rgba(58,88,255,.25);
        }
        .btn-login.magnet:hover{
            transform:translateY(-1px) scale(1.03) rotate(-.4deg);
            box-shadow:
                    0 22px 44px -12px rgba(0,0,0,.9),
                    0 0 60px rgba(46,76,151,.6),
                    inset 0 1px 0 rgba(255,255,255,.06);
            filter:brightness(1.04);
        }
        .btn-login.magnet:active{
            transform:translateY(0) scale(.995);
            filter:brightness(.98);
        }

        /* Lignes du header */
        .pi-simple .divider{
            border:0;
            border-top:1px solid #141a26;
            margin:0
        }
        .pi-simple .navrow{padding:12px 0; position: relative;}
        .pi-simple .menu{
            display:flex;
            justify-content:center;
            gap:28px;
            list-style:none;
            margin:0;
            padding:0
        }
        .pi-simple .menu a{
            font-weight:800;
            font-size:14px;
            color:#c9d4ea;
            letter-spacing:.06em;
            text-transform:uppercase;
        }
        .pi-simple .menu a:hover,
        .pi-simple .menu a.is-active{color:#ffffff}

        @media (max-width:720px){
            .pi-simple .topbar{ grid-template-columns:1fr; text-align:center }
            .pi-simple .left-col{justify-content:center}
            .pi-simple .right-col{ align-items:center; }
            .pi-simple .menu{flex-wrap:wrap; gap:18px}
        }

        /* ========= SECTIONS GLOBALES ========= */
        main{ position:relative; z-index:1; }
        section{padding:64px 0; background:transparent !important;}
        .section-hd{display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:20px}
        .section-hd h2{
            font-size:clamp(24px,3.3vw,40px);
            margin:0;
            font-weight:800;
            background:linear-gradient(90deg,#fff,#9cc3ff 50%,#fff 100%);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            text-shadow:0 18px 40px rgba(0,0,0,.8);
            animation:titleGlow 6s ease-in-out infinite;
        }
        @keyframes titleGlow{
            0%,100% { filter:drop-shadow(0 0 12px rgba(46,76,151,.4)); }
            50%     { filter:drop-shadow(0 0 20px rgba(214,69,46,.4)); }
        }
        .sub{color:var(--muted); font-size:.95rem; font-weight:500; }

        /* mini util */
        .badge-soft{
            background: rgba(214,69,46,.12);
            color:#ffd7dc;
            border:1px solid rgba(214,69,46,.25);
            font-weight:700;
            padding:.35rem .6rem;
            border-radius:999px;
            font-size:.8rem;
        }

        /* Boutons génériques */
        .btn.magnet{
            position:relative;
            border-radius:14px;
            box-shadow:
                    0 16px 30px -10px rgba(0,0,0,.9),
                    0 0 40px rgba(46,76,151,.4);
            transition:.18s;
            font-weight:800;
            font-size:14px;
            letter-spacing:.05em;
            text-transform:uppercase;
            padding:12px 16px;
            line-height:1;
            display:inline-flex;
            align-items:center;
            gap:8px;
            border:1px solid transparent;
            color:#fff;
        }
        .btn.magnet:hover{
            transform:translateY(-1px) scale(1.03) rotate(-.4deg);
            box-shadow:
                    0 22px 44px -12px rgba(0,0,0,.9),
                    0 0 60px rgba(46,76,151,.6);
        }

        .btn-red{
            background:#A32929;
            border:1px solid #A32929;
            color:#fff;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:.04em;
            border-radius:10px;
            box-shadow:
                    0 16px 30px -10px rgba(0,0,0,.9),
                    0 0 30px rgba(214,69,46,.4);
            transition:.18s;
        }
        .btn-red:hover{
            background:#8B1A1A;
            border-color:#8B1A1A;
            box-shadow:
                    0 20px 40px -10px rgba(0,0,0,.9),
                    0 0 40px rgba(214,69,46,.6);
            transform:translateY(-1px) scale(1.02);
        }

        .btn-ghost{
            background:transparent;
            border:1px solid rgba(255,255,255,.14);
            color:#fff;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:.04em;
            border-radius:10px;
        }
        .btn-ghost:hover{
            border-color:#2a3d73;
            background:#0f1b3b;
        }

        /* ========= HERO ========= */
        .hero{
            padding:64px 0 32px;
        }
        .hero-wrap{
            display:grid;
            grid-template-columns:1fr .9fr;
            gap:40px;
            align-items:center;
        }
        @media(max-width:980px){
            .hero-wrap{grid-template-columns:1fr; gap:26px}
        }

        .eyebrow{
            font-size:.8rem;
            color:var(--muted);
            letter-spacing:.2em;
            font-weight:800;
            text-transform:uppercase;
        }
        .hero-title{
            font-size:clamp(32px,4vw,44px);
            line-height:1.05;
            font-weight:800;
            margin:.3em 0;
            text-shadow:0 12px 30px rgba(0,0,0,.8);
        }

        /* texte dégradé animé réutilisable */
        .gradient-text{
            display:inline-block;
            background-image:linear-gradient(90deg, var(--pi-red), var(--pi-blue), var(--pi-red));
            background-size:200% 100%;
            background-repeat:no-repeat;
            -webkit-background-clip:text;
            background-clip:text;
            -webkit-text-fill-color:transparent;
            color:transparent;
            animation:ink-move 8s ease-in-out infinite;
            will-change:background-position;
        }
        @keyframes ink-move{
            0%,100%{ background-position:0% 50%; }
            50%    { background-position:100% 50%; }
        }

        .hero-lead{
            font-size:1.05rem;
            color:#e3eaff;
            max-width:46ch;
        }
        @media (max-width:600px){
            .hero-lead{font-size:1rem;max-width:38ch}
        }

        .job-pills{
            display:flex;
            flex-wrap:wrap;
            gap:8px 10px;
            margin:16px 0 24px;
            padding:0;
            list-style:none;
            font-size:.8rem;
            font-weight:600;
            color:#fff;
            position:relative;
        }
        .job-pill{
            position:relative;
            background:radial-gradient(circle at 0% 0%,rgba(46,76,151,.35)0%,rgba(16,23,51,.2)60%);
            border:1px solid rgba(46,76,151,.5);
            border-radius:10px;
            padding:6px 10px;
            line-height:1;
            box-shadow:
                    0 10px 24px rgba(0,0,0,.8),
                    inset 0 1px 0 rgba(255,255,255,.07);
            overflow:hidden;
        }
        .job-pill::after{
            content:"";
            position:absolute;
            left:-40%;
            top:0;
            width:40%;
            height:100%;
            background:linear-gradient(90deg,rgba(255,255,255,.4) 0%,rgba(255,255,255,0) 70%);
            transform:skewX(-20deg) translateX(-120%);
            filter:blur(2px);
            animation:shimmer 4s infinite;
        }
        @keyframes shimmer{
            0%   { transform:skewX(-20deg) translateX(-120%); opacity:0; }
            20%  { opacity:1; }
            40%  { transform:skewX(-20deg) translateX(250%); opacity:0; }
            100% { opacity:0; }
        }

        .cta-row{
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            margin-top:18px;
        }

        /* carte vidéo hero (mêmes arrondis/glow que dans postuler) */
        .hero-media{
            border-radius:16px;
            overflow:hidden;
            box-shadow:
                    0 20px 50px rgba(0,0,0,.35),
                    inset 0 1px 0 rgba(255,255,255,.06);
            border:1px solid #1e2740;
            background:#0b1020;
            max-width:480px;
            margin-inline:auto;
            aspect-ratio:16/9;
            position:relative;
        }
        .hero-media iframe{
            position:absolute;
            inset:0;
            width:100%;
            height:100%;
            border:0;
            display:block;
        }

        /* util petits textes */
        .small-muted{ color:#9aa4b2; font-size:.8rem; font-weight:500; }

        /* ========= (le reste des sections: catalogue, carrousels, map, contact, footer sera dans PARTIE 2 & 3) ========= */
    </style>
</head>
<body>

<?php if (!empty($flash)): ?>
    <div id="toast" style="position:fixed;right:16px;top:16px;z-index:9999; padding:10px 14px;border-radius:10px; background:rgba(16,185,129,.95);color:#fff;font-weight:700; border:1px solid rgba(16,185,129,.4);box-shadow:0 10px 30px rgba(0,0,0,.25)">
        <?= htmlspecialchars($flash) ?>
    </div>
    <script>
        setTimeout(()=>{
            const t=document.getElementById('toast');
            if(!t) return;
            t.style.transition='opacity .35s ease, transform .35s ease';
            t.style.opacity='0';
            t.style.transform='translateY(-6px)';
            setTimeout(()=>t.remove(),380);
        },2200);
    </script>
<?php endif; ?>

<!-- FOND GLOBAL -->
<div id="page-bg" aria-hidden="true"></div>
<div class="pi-orbs" aria-hidden="true">
    <span class="orb blue a"></span>
    <span class="orb red  b"></span>
    <span class="orb blue c"></span>
    <span class="orb red  d"></span>
</div>

<!-- BANDEAU défilant -->
<div class="marquee" aria-hidden="true">
    <div class="marquee__track">
        <div class="marquee__group">
            <span class="pill"><span class="dot"></span> Promotions fraîches chaque semaine</span>
            <span class="pill"><span class="dot"></span> Boucherie halal &amp; primeur</span>
            <span class="pill"><span class="dot"></span> Épicerie Turquie / Maghreb / Monde</span>
            <span class="pill"><span class="dot"></span> Produits frais &amp; de saison</span>
            <span class="pill"><span class="dot"></span> Promotions fraîches chaque semaine</span>
        </div>
        <div class="marquee__group" aria-hidden="true">
            <span class="pill"><span class="dot"></span> Promotions fraîches chaque semaine</span>
            <span class="pill"><span class="dot"></span> Boucherie halal &amp; primeur</span>
            <span class="pill"><span class="dot"></span> Épicerie Turquie / Maghreb / Monde</span>
            <span class="pill"><span class="dot"></span> Produits frais &amp; de saison</span>
            <span class="pill"><span class="dot"></span> Promotions fraîches chaque semaine</span>
        </div>
    </div>
</div>

<!-- HEADER -->
<header class="pi-simple">
    <div class="container topbar">
        <!-- Col gauche : réseaux -->
        <div class="left-col">
            <div class="social-group">
                <nav class="social" aria-label="Réseaux sociaux">
                    <a href="https://www.facebook.com/supermarcheparistanbul/?locale=fr_FR" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/paristanbul_supermarche/?hl=fr" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@supermarche_paristanbul" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.youtube.com/channel/UCsjy3bdpFzBwM7MF923gKvA" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </nav>
                <div class="join">Rejoignez nous</div>
            </div>
        </div>

        <!-- Col centre : logo -->
        <div class="brand">
            <a href="index.php" class="navbar-brand">
                <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
            </a>
            <div class="tagline">
                <span class="rule" aria-hidden="true"></span>
                <span>Since 1993</span>
                <span class="rule" aria-hidden="true"></span>
            </div>
        </div>

        <!-- Col droite : connexion + tel -->
        <div class="right-col">
            <?php if ($isLoggedIn): ?>
                <a class="btn-login magnet" href="../deconnexion.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Se déconnecter</span>
                </a>
            <?php else: ?>
                <a class="btn-login magnet" href="pageConnexion.php">
                    <i class="fa-regular fa-user"></i>
                    <span>Se connecter</span>
                </a>
            <?php endif; ?>

            <div class="phone-line">
                <i class="fa-solid fa-phone"></i>
                <a class="phone" href="tel:+33749826133">07 49 82 61 33</a>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- Menu nav -->
    <div class="container navrow">
        <ul class="menu" aria-label="Navigation principale">
            <?php if ($isAdmin): ?>
                <li><a href="pageAdmin.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="index.php" class="is-active">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre Histoire</a></li>
            <li><a href="#catalog">Catalogue</a></li>
            <li><a href="nosMagasins.php">Nos Magasins</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="postuler.php">Postuler</a></li>
        </ul>
    </div>

    <hr class="divider">
</header>

<main>
    <!-- HERO -->
    <section class="hero">
        <div class="container hero-wrap">
            <!-- Col texte -->
            <div>
                <div class="eyebrow">Bienvenue chez Paristanbul</div>

                <h1 class="hero-title">
                    Vos saveurs favorites,
                    <span class="gradient-text">à deux pas</span>
                    de chez vous.
                </h1>

                <p class="hero-lead">
                    Boucherie halal, primeur, épicerie turque et du monde.
                    Découvrez notre nouveau <strong>catalogue interactif</strong> et trouvez
                    le magasin le plus proche.
                </p>

                <ul class="job-pills">
                    <li class="job-pill">Boucherie halal</li>
                    <li class="job-pill">Fruits &amp; légumes frais</li>
                    <li class="job-pill">Épicerie Turquie / Maghreb</li>
                </ul>

                <div class="cta-row">
                    <a href="#catalog"
                       class="btn magnet"
                       style="background:linear-gradient(145deg,#1c305c,#101a33);border:1px solid #2a3d73;">
                        Voir le catalogue
                    </a>

                    <a href="#stores" class="btn magnet"
                       style="background:linear-gradient(145deg,#8B1A1A,#A32929);border:1px solid #A32929;">
                        Voir nos magasins
                    </a>
                </div>
            </div>

            <!-- Col média (vidéo) -->
            <div class="hero-media">
                <iframe
                        src="https://www.youtube-nocookie.com/embed/-AeizsAsJHA?controls=1&playsinline=1&modestbranding=1&rel=0&showinfo=0&autoplay=1&mute=1&loop=1&playlist=-AeizsAsJHA"
                        title="Paristanbul Promo"
                        allow="autoplay; fullscreen; picture-in-picture; encrypted-media"
                        referrerpolicy="strict-origin-when-cross-origin">
                </iframe>
            </div>
        </div>
    </section>
    <!-- FIN HERO -->
    <!-- CATALOGUE -->
    <section id="catalog" style="padding-top:32px;">
        <div class="container">
            <div class="section-hd">
                <div>
                    <h2>Catalogue interactif</h2>
                    <div class="sub">Tournez les pages, zoomez, explorez.</div>
                </div>
            </div>

            <div class="catalog-app" style="
                display:flex;
                flex-direction:column;
                gap:10px;
                background:linear-gradient(180deg,#0f1525 0%, #0c1223 100%);
                border:1px solid rgba(255,255,255,.07);
                border-radius:18px;
                box-shadow:
                    0 28px 60px -20px rgba(0,0,0,.8),
                    0 0 80px rgba(46,76,151,.2);
                padding:12px;
            ">
                <!-- Toolbar -->
                <div class="toolbar" style="
                    background:#121a34;
                    border:1px solid #1f2942;
                    border-radius:14px;
                    overflow:hidden;
                    box-shadow:inset 0 1px 0 rgba(255,255,255,.06);
                ">
                    <div class="row" style="
                        display:flex;
                        flex-wrap:wrap;
                        align-items:center;
                        gap:.45rem;
                        padding:.45rem .75rem;
                        color:#fff;
                        font-weight:800;
                        font-size:.8rem;
                        letter-spacing:.05em;
                        text-transform:uppercase;
                    ">
                        <div class="brand" style="
                            display:flex;
                            align-items:center;
                            gap:.6rem;
                            font-weight:800;
                            letter-spacing:.2px;
                            color:#eaf0ff;
                        ">
                            <span class="dot" style="
                                width:.6rem;
                                height:.6rem;
                                border-radius:999px;
                                background:#3aa0ff;
                                box-shadow:0 0 0 4px #3aa0ff22;
                                flex-shrink:0;
                            "></span>
                            <span>Catalogue</span>
                        </div>

                        <!-- nav pages -->
                        <button id="prevBtn" class="btn magnet" style="
                            width:40px;
                            height:40px;
                            padding:0;
                            border-radius:12px;
                            display:grid;
                            place-items:center;
                            background:#131a2a;
                            border:1px solid #1f2942;
                            color:#eaf0ff;
                            font-weight:800;
                            box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004;
                        " aria-label="Page précédente">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 style="width:20px;height:20px;display:block;pointer-events:none;">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                        </button>

                        <button id="nextBtn" class="btn magnet" style="
                            width:40px;
                            height:40px;
                            padding:0;
                            border-radius:12px;
                            display:grid;
                            place-items:center;
                            background:#131a2a;
                            border:1px solid #1f2942;
                            color:#eaf0ff;
                            font-weight:800;
                            box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004;
                        " aria-label="Page suivante">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 style="width:20px;height:20px;display:block;pointer-events:none;">
                                <path d="M9 6l6 6-6 6"/>
                            </svg>
                        </button>

                        <span class="sep" style="
                            width:1px;
                            height:28px;
                            background:#223052;
                            opacity:.6;
                            margin:0 .25rem;
                            flex-shrink:0;
                        " aria-hidden="true"></span>

                        <!-- zoom -->
                        <button id="zoomOut" class="btn magnet" style="
                            width:40px;
                            height:40px;
                            padding:0;
                            border-radius:12px;
                            display:grid;
                            place-items:center;
                            background:#131a2a;
                            border:1px solid #1f2942;
                            color:#eaf0ff;
                            font-weight:800;
                            box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004;
                        " aria-label="Zoom -">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 style="width:20px;height:20px;display:block;pointer-events:none;">
                                <path d="M5 12h14"/>
                            </svg>
                        </button>

                        <button id="zoomIn" class="btn magnet" style="
                            width:40px;
                            height:40px;
                            padding:0;
                            border-radius:12px;
                            display:grid;
                            place-items:center;
                            background:#131a2a;
                            border:1px solid #1f2942;
                            color:#eaf0ff;
                            font-weight:800;
                            box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004;
                        " aria-label="Zoom +">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 style="width:20px;height:20px;display:block;pointer-events:none;">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                        </button>

                        <button id="fitBtn" class="btn magnet" style="
                            background:#131a2a;
                            border:1px solid #1f2942;
                            border-radius:12px;
                            padding:.45rem .7rem;
                            line-height:1;
                            color:#fff;
                            font-weight:800;
                            box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004;
                            text-transform:uppercase;
                            font-size:.7rem;
                            letter-spacing:.08em;
                        ">
                            Ajuster
                        </button>

                        <!-- page counter -->
                        <div class="metric" style="
                            margin-left:auto;
                            display:inline-flex;
                            align-items:center;
                            gap:.5rem;
                            font-weight:800;
                            color:#cfe0ff;
                            background:#0e1423;
                            border:1px solid #1f2942;
                            padding:.35rem .6rem;
                            border-radius:.75rem;
                            box-shadow:0 1px 0 #ffffff0d inset;
                            line-height:1.2;
                        ">
                            <small style="font-size:.7rem;opacity:.8;">Page</small>
                            <span id="pageLabel">1 / 5</span>
                        </div>
                    </div>
                </div>

                <!-- Zone flipbook -->
                <div class="stage" id="stageBox" style="
                    position:relative;
                    border:1px solid var(--edge,#1b2235);
                    background:#0e1423 !important;
                    border-radius:14px;
                    box-shadow:
                        0 18px 46px rgba(0,0,0,.8),
                        inset 0 1px 0 rgba(255,255,255,.07),
                        0 0 80px rgba(46,76,151,.2);
                    display:grid;
                    place-items:center;
                    overflow:hidden;
                ">
                    <div id="stageInner" class="stage-inner" style="background:#0e1423 !important;">
                        <div id="flipbook" aria-label="Catalogue interactif" style="
                            width:min(92vw,1040px);
                            height:88vh;
                            background:#0e1423 !important;
                        "></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SLIDER "NOS RAYONS" (pile de 3 cartes qui tournent) -->
    <section id="rayons" class="section" style="padding-top:32px; padding-bottom:32px;">
        <div class="container pi-sites" style="
            display:grid;
            grid-template-columns:1.05fr 1fr;
            align-items:center;
            gap: clamp(24px, 5vw, 80px);
            min-height: 62vh;
            position:relative;
        ">
            <!-- Texte / contrôles -->
            <div class="pi-sites__left" style="position:relative;z-index:5;">
                <p class="eyebrow" style="margin:0 0 10px 0;">Un rayon, une histoire</p>

                <h2 class="hero-title" id="piSitesTitle" style="
                    font-size:clamp(28px,6vw,52px);
                    line-height:1.05;
                    font-weight:800;
                    margin:0 0 24px 0;
                    color:#e6ecf5;
                    text-shadow:0 12px 30px rgba(0,0,0,.8);
                ">
                    Boucherie sélection
                </h2>

                <div class="pi-sites__nav" aria-label="Contrôles du slider" style="display:flex;gap:14px;">
                    <button class="btn magnet" id="piSitesPrev" aria-label="Précédent" style="
                        width:44px;
                        height:44px;
                        border-radius:14px;
                        cursor:pointer;
                        background:#111729;
                        border:1px solid rgba(255,255,255,.14);
                        display:grid;
                        place-items:center;
                        color:#e6edff;
                        font-weight:800;
                        box-shadow:0 16px 30px rgba(0,0,0,.8),inset 0 1px 0 rgba(255,255,255,.06);
                    ">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn magnet" id="piSitesNext" aria-label="Suivant" style="
                        width:44px;
                        height:44px;
                        border-radius:14px;
                        cursor:pointer;
                        background:#111729;
                        border:1px solid rgba(255,255,255,.14);
                        display:grid;
                        place-items:center;
                        color:#e6edff;
                        font-weight:800;
                        box-shadow:0 16px 30px rgba(0,0,0,.8),inset 0 1px 0 rgba(255,255,255,.06);
                    ">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Stack visuelle -->
            <div class="pi-sites__stack" id="piSitesStack" aria-live="polite" style="
                position:relative;
                min-height: clamp(360px, 58vh, 640px);
                isolation:isolate;
            ">
                <!-- 3 cartes superposées -->
                <figure class="pi-sites__card pi-role-left" style="
                    position:absolute;
                    inset:auto 0 0 auto;
                    width:min(52vw,760px);
                    height:80%;
                    border-radius:12px;
                    overflow:hidden;
                    background:#1a2032;
                    border:1px solid rgba(255,255,255,.06);
                    box-shadow:0 22px 60px rgba(0,0,0,.28);
                    transform:translate(-12vw,6vh) scale(.66);
                    z-index:1;
                    opacity:.9;
                    transition: transform 560ms cubic-bezier(.22,.8,.24,1), opacity 560ms ease;
                ">
                    <img src="../assets/img/DSC09757.JPG" alt="Produit frais" style="width:100%;height:100%;object-fit:cover;display:block;">
                </figure>

                <figure class="pi-sites__card pi-role-center" style="
                    position:absolute;
                    inset:auto 0 0 auto;
                    width:min(52vw,760px);
                    height:80%;
                    border-radius:12px;
                    overflow:hidden;
                    background:#1a2032;
                    border:1px solid rgba(255,255,255,.06);
                    box-shadow:0 22px 60px rgba(0,0,0,.28);
                    transform:translate(0,0) scale(1);
                    z-index:3;
                    opacity:1;
                    transition: transform 560ms cubic-bezier(.22,.8,.24,1), opacity 560ms ease;
                ">
                    <img src="../assets/img/DSC09743.JPG" alt="Boucherie sélection" style="width:100%;height:100%;object-fit:cover;display:block;">
                </figure>

                <figure class="pi-sites__card pi-role-right" style="
                    position:absolute;
                    inset:auto 0 0 auto;
                    width:min(52vw,760px);
                    height:80%;
                    border-radius:12px;
                    overflow:hidden;
                    background:#1a2032;
                    border:1px solid rgba(255,255,255,.06);
                    box-shadow:0 22px 60px rgba(0,0,0,.28);
                    transform:translate(14vw,-3vh) scale(.62);
                    z-index:1;
                    opacity:.9;
                    transition: transform 560ms cubic-bezier(.22,.8,.24,1), opacity 560ms ease;
                ">
                    <img src="../assets/img/DJI_0264.JPG" alt="Boissons" style="width:100%;height:100%;object-fit:cover;display:block;">
                </figure>

                <!-- Légende overlay -->
                <figcaption class="pi-legend" style="
                    position:absolute;
                    left:22px;
                    bottom:22px;
                    z-index:6;
                    display:flex;
                    align-items:center;
                    gap:12px;
                    color:#fff;
                    font-size: clamp(18px, 2.2vw, 32px);
                    font-weight:800;
                    text-shadow:0 2px 10px rgba(0,0,0,.35),0 6px 30px rgba(0,0,0,.45);
                    pointer-events:none;
                ">
                    <span id="piSitesCity">Paristanbul</span>
                </figcaption>
            </div>
        </div>
    </section>

    <!-- CARROUSEL MAGASINS "quelques-uns de nos magasins" -->
    <section id="advantages" class="section" style="padding-top:16px;padding-bottom:16px;">
        <div class="container">
            <div class="section-hd" style="row-gap:12px;">
                <div style="display:flex; flex-direction:column; gap:6px; min-width:0;">
                    <h2>Quelques-uns de nos magasins</h2>
                    <div class="sub">Des équipes passionnées à votre service</div>
                </div>

                <a href="nosMagasins.php"
                   class="btn magnet"
                   style="
                        background:linear-gradient(145deg,#1c305c,#101a33);
                        border:1px solid #2a3d73;
                        color:#fff;
                        font-weight:800;
                        font-size:14px;
                        letter-spacing:.05em;
                        text-transform:uppercase;
                        border-radius:14px;
                        padding:10px 14px;
                        line-height:1;
                        display:inline-flex;
                        align-items:center;
                        gap:8px;
                   ">
                    <span>Tous nos magasins</span>
                    <i class="bi bi-arrow-right-short" style="font-size:18px;line-height:1;"></i>
                </a>
            </div>

            <div class="carousel" style="
                position:relative;
                isolation:isolate;
                background:linear-gradient(180deg,#0f1525aa,#0d132199);
                border:1px solid rgba(255,255,255,.07);
                border-radius:18px;
                padding:clamp(14px,2vw,18px);
                box-shadow:
                    0 32px 70px -20px rgba(0,0,0,.9),
                    0 0 120px rgba(46,76,151,.28);
            ">
                <div class="track-viewport" style="position:relative; overflow:hidden; border-radius:12px;">
                    <div class="track" id="adv-track" style="
                        display:flex;
                        gap:16px;
                        will-change:transform;
                        transition:transform .45s cubic-bezier(.22,.84,.3,1);
                        touch-action:pan-y;
                    ">

                        <!-- Carte magasin -->
                        <article class="card tilt" tabindex="0" style="
                            min-width:clamp(260px,42vw,340px);
                            flex:0 0 clamp(260px,42vw,340px);
                            background:linear-gradient(180deg,#0e1422,#0b101b);
                            border:1px solid rgba(255,255,255,.07);
                            border-radius:16px;
                            overflow:hidden;
                            padding:16px;
                            box-shadow:
                                0 24px 60px -20px rgba(0,0,0,.8),
                                0 0 80px rgba(214,69,46,.22);
                        ">
                            <div class="thumb" style="
                                aspect-ratio:16/9;
                                background:linear-gradient(135deg,#142036,#171d2b);
                                border:1px solid #202a44;
                                border-radius:12px;
                                margin-bottom:12px;
                                overflow:hidden;
                            ">
                                <img src="/Projet-Paristanbul/assets/img/magasins/villiers1.jpg"
                                     alt="Paristanbul Villiers-le-Bel"
                                     style="width:110%;height:110%;object-fit:cover;display:block;">
                            </div>

                            <div class="meta" style="display:flex;align-items:center;gap:10px;color:#c9d4ea;font-size:13px;margin-bottom:6px;">
                                <span class="dot" style="width:8px;height:8px;border-radius:50%;background:#2f7bff;"></span>
                                <span>Val-d'Oise (95)</span>
                            </div>

                            <h3 style="margin:0;font-weight:700;color:#fff;font-size:1rem;">Villiers-le-Bel</h3>
                            <p style="margin:.25rem 0 .5rem 0;font-size:.85rem;color:#c9d4ea;">3 avenue des entrepreneurs</p>

                            <div class="tags" style="display:flex;flex-wrap:wrap;gap:8px;font-size:.7rem;font-weight:600;color:#fff;">
                                <span class="tag" style="background:#1c305c;border:1px solid #2a3d73;border-radius:999px;padding:4px 8px;line-height:1;">08:30–20:00</span>
                                <span class="tag" style="background:#8B1A1A;border:1px solid #A32929;border-radius:999px;padding:4px 8px;line-height:1;">7j/7</span>
                            </div>
                        </article>

                        <article class="card tilt" tabindex="0" style="
                            min-width:clamp(260px,42vw,340px);
                            flex:0 0 clamp(260px,42vw,340px);
                            background:linear-gradient(180deg,#0e1422,#0b101b);
                            border:1px solid rgba(255,255,255,.07);
                            border-radius:16px;
                            overflow:hidden;
                            padding:16px;
                            box-shadow:
                                0 24px 60px -20px rgba(0,0,0,.8),
                                0 0 80px rgba(214,69,46,.22);
                        ">
                            <div class="thumb" style="
                                aspect-ratio:16/9;
                                background:linear-gradient(135deg,#142036,#171d2b);
                                border:1px solid #202a44;
                                border-radius:12px;
                                margin-bottom:12px;
                                overflow:hidden;
                            ">
                                <img src="/Projet-Paristanbul/assets/img/magasins/drancy.jpg"
                                     alt="Paristanbul Drancy"
                                     style="width:110%;height:110%;object-fit:cover;display:block;">
                            </div>

                            <div class="meta" style="display:flex;align-items:center;gap:10px;color:#c9d4ea;font-size:13px;margin-bottom:6px;">
                                <span class="dot" style="width:8px;height:8px;border-radius:50%;background:#19c37d;"></span>
                                <span>Seine-Saint-Denis (93)</span>
                            </div>

                            <h3 style="margin:0;font-weight:700;color:#fff;font-size:1rem;">Drancy</h3>
                            <p style="margin:.25rem 0 .5rem 0;font-size:.85rem;color:#c9d4ea;">83 avenue Marceau</p>

                            <div class="tags" style="display:flex;flex-wrap:wrap;gap:8px;font-size:.7rem;font-weight:600;color:#fff;">
                                <span class="tag" style="background:#1c305c;border:1px solid #2a3d73;border-radius:999px;padding:4px 8px;line-height:1;">09:00–21:00</span>
                                <span class="tag" style="background:#8B1A1A;border:1px solid #A32929;border-radius:999px;padding:4px 8px;line-height:1;">Dimanche 19h</span>
                            </div>
                        </article>

                        <article class="card tilt" tabindex="0" style="
                            min-width:clamp(260px,42vw,340px);
                            flex:0 0 clamp(260px,42vw,340px);
                            background:linear-gradient(180deg,#0e1422,#0b101b);
                            border:1px solid rgba(255,255,255,.07);
                            border-radius:16px;
                            overflow:hidden;
                            padding:16px;
                            box-shadow:
                                0 24px 60px -20px rgba(0,0,0,.8),
                                0 0 80px rgba(214,69,46,.22);
                        ">
                            <div class="thumb" style="
                                aspect-ratio:16/9;
                                background:linear-gradient(135deg,#142036,#171d2b);
                                border:1px solid #202a44;
                                border-radius:12px;
                                margin-bottom:12px;
                                overflow:hidden;
                            ">
                                <img src="/Projet-Paristanbul/assets/img/magasins/bondy.jpg"
                                     alt="Paristanbul Bondy"
                                     style="width:110%;height:110%;object-fit:cover;display:block;">
                            </div>

                            <div class="meta" style="display:flex;align-items:center;gap:10px;color:#c9d4ea;font-size:13px;margin-bottom:6px;">
                                <span class="dot" style="width:8px;height:8px;border-radius:50%;background:#f5a524;"></span>
                                <span>Seine-Saint-Denis (93)</span>
                            </div>

                            <h3 style="margin:0;font-weight:700;color:#fff;font-size:1rem;">Bondy</h3>
                            <p style="margin:.25rem 0 .5rem 0;font-size:.85rem;color:#c9d4ea;">116 Avenue Gallieni</p>

                            <div class="tags" style="display:flex;flex-wrap:wrap;gap:8px;font-size:.7rem;font-weight:600;color:#fff;">
                                <span class="tag" style="background:#1c305c;border:1px solid #2a3d73;border-radius:999px;padding:4px 8px;line-height:1;">09:00–21:00</span>
                                <span class="tag" style="background:#8B1A1A;border:1px solid #A32929;border-radius:999px;padding:4px 8px;line-height:1;">Dimanche 19h</span>
                            </div>
                        </article>

                        <article class="card tilt" tabindex="0" style="
                            min-width:clamp(260px,42vw,340px);
                            flex:0 0 clamp(260px,42vw,340px);
                            background:linear-gradient(180deg,#0e1422,#0b101b);
                            border:1px solid rgba(255,255,255,.07);
                            border-radius:16px;
                            overflow:hidden;
                            padding:16px;
                            box-shadow:
                                0 24px 60px -20px rgba(0,0,0,.8),
                                0 0 80px rgba(214,69,46,.22);
                        ">
                            <div class="thumb" style="
                                aspect-ratio:16/9;
                                background:linear-gradient(135deg,#142036,#171d2b);
                                border:1px solid #202a44;
                                border-radius:12px;
                                margin-bottom:12px;
                                overflow:hidden;
                            ">
                                <img src="/Projet-Paristanbul/assets/img/magasins/villemomble.jpg"
                                     alt="Paristanbul Villemomble"
                                     style="width:110%;height:110%;object-fit:cover;display:block;">
                            </div>

                            <div class="meta" style="display:flex;align-items:center;gap:10px;color:#c9d4ea;font-size:13px;margin-bottom:6px;">
                                <span class="dot" style="width:8px;height:8px;border-radius:50%;background:#b07cff;"></span>
                                <span>Seine-Saint-Denis (93)</span>
                            </div>

                            <h3 style="margin:0;font-weight:700;color:#fff;font-size:1rem;">Villemomble</h3>
                            <p style="margin:.25rem 0 .5rem 0;font-size:.85rem;color:#c9d4ea;">68 Allée du Plateau</p>

                            <div class="tags" style="display:flex;flex-wrap:wrap;gap:8px;font-size:.7rem;font-weight:600;color:#fff;">
                                <span class="tag" style="background:#1c305c;border:1px solid #2a3d73;border-radius:999px;padding:4px 8px;line-height:1;">08:00–20:30</span>
                                <span class="tag" style="background:#8B1A1A;border:1px solid #A32929;border-radius:999px;padding:4px 8px;line-height:1;">7j/7</span>
                            </div>
                        </article>

                        <article class="card tilt" tabindex="0" style="
                            min-width:clamp(260px,42vw,340px);
                            flex:0 0 clamp(260px,42vw,340px);
                            background:linear-gradient(180deg,#0e1422,#0b101b);
                            border:1px solid rgba(255,255,255,.07);
                            border-radius:16px;
                            overflow:hidden;
                            padding:16px;
                            box-shadow:
                                0 24px 60px -20px rgba(0,0,0,.8),
                                0 0 80px rgba(214,69,46,.22);
                        ">
                            <div class="thumb" style="
                                aspect-ratio:16/9;
                                background:linear-gradient(135deg,#142036,#171d2b);
                                border:1px solid #202a44;
                                border-radius:12px;
                                margin-bottom:12px;
                                overflow:hidden;
                            ">
                                <img src="/Projet-Paristanbul/assets/img/magasins/nogent.jpg"
                                     alt="Paristanbul Nogent-sur-Oise"
                                     style="width:110%;height:110%;object-fit:cover;display:block;">
                            </div>

                            <div class="meta" style="display:flex;align-items:center;gap:10px;color:#c9d4ea;font-size:13px;margin-bottom:6px;">
                                <span class="dot" style="width:8px;height:8px;border-radius:50%;background:#b9143f;"></span>
                                <span>Oise (60)</span>
                            </div>

                            <h3 style="margin:0;font-weight:700;color:#fff;font-size:1rem;">Nogent-sur-Oise</h3>
                            <p style="margin:.25rem 0 .5rem 0;font-size:.85rem;color:#c9d4ea;">171 Rue Jean Monnet</p>

                            <div class="tags" style="display:flex;flex-wrap:wrap;gap:8px;font-size:.7rem;font-weight:600;color:#fff;">
                                <span class="tag" style="background:#1c305c;border:1px solid #2a3d73;border-radius:999px;padding:4px 8px;line-height:1;">09:30–20:00</span>
                                <span class="tag" style="background:#8B1A1A;border:1px solid #A32929;border-radius:999px;padding:4px 8px;line-height:1;">Dimanche 19h</span>
                            </div>
                        </article>

                    </div>

                    <!-- nav flèches -->
                    <div class="adv-nav" style="
                        position:absolute;
                        inset:0;
                        pointer-events:none;
                        z-index:2;
                    ">
                        <button class="prev" id="adv-prev" aria-label="Précédent" style="
                            pointer-events:auto;
                            position:absolute;
                            top:50%;
                            left:10px;
                            transform:translateY(-50%);
                            width:46px;height:46px;
                            border-radius:14px;
                            border:1px solid #223055;
                            background:rgba(15,22,35,.86);
                            color:#cfe0ff;
                            display:grid;
                            place-items:center;
                            cursor:pointer;
                            backdrop-filter:blur(6px);
                            box-shadow:0 6px 24px rgba(0,0,0,.35);
                        ">
                            <svg viewBox="0 0 24 24" fill="none" style="width:20px;height:20px;">
                                <path d="M15 5 8 12l7 7"
                                      stroke="currentColor"
                                      stroke-width="2"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <button class="next" id="adv-next" aria-label="Suivant" style="
                            pointer-events:auto;
                            position:absolute;
                            top:50%;
                            right:10px;
                            transform:translateY(-50%);
                            width:46px;height:46px;
                            border-radius:14px;
                            border:1px solid #223055;
                            background:rgba(15,22,35,.86);
                            color:#cfe0ff;
                            display:grid;
                            place-items:center;
                            cursor:pointer;
                            backdrop-filter:blur(6px);
                            box-shadow:0 6px 24px rgba(0,0,0,.35);
                        ">
                            <svg viewBox="0 0 24 24" fill="none" style="width:20px;height:20px;">
                                <path d="M9 5l7 7-7 7"
                                      stroke="currentColor"
                                      stroke-width="2"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- NOS MAGASINS + MAP INTERACTIVE -->
    <section id="stores" class="section" style="padding-top:16px;">
        <div class="container">
            <div class="section-hd">
                <div>
                    <h2>Nos magasins</h2>
                    <div class="sub">Choisissez un point de vente, voyez l’itinéraire</div>
                </div>
            </div>

            <!-- Onglets / contenu -->
            <div style="
                background:linear-gradient(180deg,#10182e,#0d1529);
                border:1px solid rgba(255,255,255,.07);
                border-radius:18px;
                box-shadow:
                    0 32px 70px -20px rgba(0,0,0,.9),
                    0 0 120px rgba(46,76,151,.28),
                    inset 0 1px 0 rgba(255,255,255,.06);
                overflow:hidden;
            ">

                <!-- barre d’onglets -->
                <div class="nav-tabs" style="
                    display:flex;
                    justify-content:center;
                    flex-wrap:wrap;
                    gap:12px;
                    padding:12px;
                    background:linear-gradient(180deg,#121a32,#0f172c);
                    border-bottom:1px solid rgba(255,255,255,.07);
                ">
                    <button class="nav-tab active" data-store="villiers1" style="
                        background:linear-gradient(145deg,#1c305c,#2a3d73);
                        border:1px solid #2a3d73;
                        color:#fff;
                        font-weight:800;
                        padding:.75rem 1.1rem;
                        border-radius:999px;
                        font-size:.8rem;
                        letter-spacing:.05em;
                        text-transform:uppercase;
                        box-shadow:inset 0 1px 0 rgba(255,255,255,.07),0 8px 22px rgba(0,0,0,.30);
                    ">Villiers-le-Bel</button>

                    <button class="nav-tab" data-store="drancy" style="
                        background:linear-gradient(145deg,#111a31,#0e1528);
                        border:1px solid #223055;
                        color:#e7ecf5;
                        font-weight:800;
                        padding:.75rem 1.1rem;
                        border-radius:999px;
                        font-size:.8rem;
                        letter-spacing:.05em;
                        text-transform:uppercase;
                    ">Drancy</button>

                    <button class="nav-tab" data-store="bondy" style="
                        background:linear-gradient(145deg,#111a31,#0e1528);
                        border:1px solid #223055;
                        color:#e7ecf5;
                        font-weight:800;
                        padding:.75rem 1.1rem;
                        border-radius:999px;
                        font-size:.8rem;
                        letter-spacing:.05em;
                        text-transform:uppercase;
                    ">Bondy</button>

                    <button class="nav-tab" data-store="villemomble" style="
                        background:linear-gradient(145deg,#111a31,#0e1528);
                        border:1px solid #223055;
                        color:#e7ecf5;
                        font-weight:800;
                        padding:.75rem 1.1rem;
                        border-radius:999px;
                        font-size:.8rem;
                        letter-spacing:.05em;
                        text-transform:uppercase;
                    ">Villemomble</button>

                    <button class="nav-tab" data-store="nogent" style="
                        background:linear-gradient(145deg,#111a31,#0e1528);
                        border:1px solid #223055;
                        color:#e7ecf5;
                        font-weight:800;
                        padding:.75rem 1.1rem;
                        border-radius:999px;
                        font-size:.8rem;
                        letter-spacing:.05em;
                        text-transform:uppercase;
                    ">Nogent-sur-Oise</button>

                    <button class="nav-tab" data-store="vertsaintdenis" style="
                        background:linear-gradient(145deg,#111a31,#0e1528);
                        border:1px solid #223055;
                        color:#e7ecf5;
                        font-weight:800;
                        padding:.75rem 1.1rem;
                        border-radius:999px;
                        font-size:.8rem;
                        letter-spacing:.05em;
                        text-transform:uppercase;
                    ">Vert-Saint-Denis</button>
                </div>

                <!-- zone info + map -->
                <div id="contentArea" style="
                    display:grid;
                    grid-template-columns:1.2fr .8fr;
                    min-height:500px;
                    position:relative;
                    overflow:hidden;
                ">
                    <!-- JS va injecter ici la map + infos du magasin sélectionné -->
                </div>
            </div>
        </div>
    </section>
    <!-- CONTACT -->
    <section id="contact" class="section" style="padding-top:32px;">
        <div class="container">
            <div class="section-hd" style="flex-direction:column;align-items:center;text-align:center;gap:6px;">
                <h2>Contactez-nous</h2>
                <div class="sub">Une question, une suggestion ?</div>
            </div>

            <div class="contact-grid" style="
                display:grid;
                grid-template-columns:1fr 1fr;
                gap:28px;
                align-items:stretch;
            ">
                <!-- Formulaire contact -->
                <div class="contact-panel" style="
                    background:linear-gradient(180deg,#121826,#0e1422);
                    border:1px solid rgba(255,255,255,.07);
                    border-radius:22px;
                    box-shadow:
                        0 28px 60px -20px rgba(0,0,0,.8),
                        0 0 80px rgba(46,76,151,.2),
                        inset 0 1px 0 rgba(255,255,255,.07);
                    padding:28px 24px;
                    display:flex;
                    flex-direction:column;
                    gap:22px;
                ">
                    <h3 class="contact-title" style="
                        margin:0;
                        text-align:center;
                        font-size:clamp(20px,2.2vw,24px);
                        font-weight:800;
                        color:#fff;
                        text-decoration:underline;
                        text-underline-offset:6px;
                        text-decoration-thickness:3px;
                        text-decoration-color:#A32929;
                    ">
                        Envoyez-nous un message
                    </h3>

                    <form id="contactForm" class="form-row"
                          action="https://formsubmit.co/parisistambulnogent@gmail.com"
                          method="post"
                          accept-charset="UTF-8"
                          style="display:flex;flex-direction:column;gap:12px;">
                        <input class="form-control" type="text" name="name" placeholder="Nom complet" required style="
                            width:100%;
                            padding:16px 18px;
                            border-radius:14px;
                            color:#fff;
                            background:linear-gradient(145deg,#0f152b,#0c1223);
                            border:1px solid #1e2740;
                            font:600 16px/1.2 'Plus Jakarta Sans',system-ui,Segoe UI,Roboto,Arial;
                            outline:none;
                        ">
                        <input class="form-control" type="email" name="email" placeholder="Email" required style="
                            width:100%;
                            padding:16px 18px;
                            border-radius:14px;
                            color:#fff;
                            background:linear-gradient(145deg,#0f152b,#0c1223);
                            border:1px solid #1e2740;
                            font:600 16px/1.2 'Plus Jakarta Sans',system-ui,Segoe UI,Roboto,Arial;
                            outline:none;
                        ">
                        <select class="form-control" name="sujet" required style="
                            width:100%;
                            padding:16px 18px;
                            border-radius:14px;
                            color:#fff;
                            background:linear-gradient(145deg,#0f152b,#0c1223);
                            border:1px solid #1e2740;
                            font:600 16px/1.2 'Plus Jakarta Sans',system-ui,Segoe UI,Roboto,Arial;
                            outline:none;
                        ">
                            <option value="">Sélectionnez un sujet</option>
                            <option>Informations générales</option>
                            <option>Commande</option>
                            <option>Problème technique</option>
                        </select>
                        <textarea class="form-control form-textarea" name="message" placeholder="Votre message..." required style="
                            width:100%;
                            padding:16px 18px;
                            border-radius:14px;
                            color:#fff;
                            min-height:140px;
                            resize:vertical;
                            background:linear-gradient(145deg,#0f152b,#0c1223);
                            border:1px solid #1e2740;
                            font:600 16px/1.2 'Plus Jakarta Sans',system-ui,Segoe UI,Roboto,Arial;
                            outline:none;
                        "></textarea>

                        <!-- champs cachés formsubmit -->
                        <input type="hidden" name="_next" value="">
                        <input type="hidden" name="_subject" value="Nouveau message — Site Paristanbul">
                        <input type="hidden" name="_template" value="table">
                        <input type="hidden" name="_captcha" value="false">
                        <input type="text" name="_honey" style="display:none">

                        <button class="btn-send magnet" type="submit" style="
                            appearance:none;
                            cursor:pointer;
                            border:0;
                            width:100%;
                            padding:16px 18px;
                            border-radius:14px;
                            font-weight:800;
                            font-size:16px;
                            letter-spacing:.1px;
                            color:#fff;
                            background:linear-gradient(145deg,#d26043,#8b1f22);
                            box-shadow:0 20px 40px -10px rgba(139,31,34,.6),
                                       0 0 60px rgba(214,69,46,.4);
                            transition:transform .08s ease,
                                       box-shadow .2s ease,
                                       filter .2s ease;
                            text-transform:uppercase;
                            letter-spacing:.08em;
                        ">
                            Envoyer le message
                        </button>
                    </form>
                </div>

                <!-- Bloc infos / newsletter -->
                <div class="contact-panel" style="
                    background:linear-gradient(180deg,#121826,#0e1422);
                    border:1px solid rgba(255,255,255,.07);
                    border-radius:22px;
                    box-shadow:
                        0 28px 60px -20px rgba(0,0,0,.8),
                        0 0 80px rgba(46,76,151,.2),
                        inset 0 1px 0 rgba(255,255,255,.07);
                    padding:28px 24px;
                    display:flex;
                    flex-direction:column;
                    gap:22px;
                ">
                    <h3 class="contact-title" style="
                        margin:0;
                        text-align:center;
                        font-size:clamp(20px,2.2vw,24px);
                        font-weight:800;
                        color:#fff;
                        text-decoration:underline;
                        text-underline-offset:6px;
                        text-decoration-thickness:3px;
                        text-decoration-color:#1c305c;
                    ">
                        Service client
                    </h3>

                    <!-- infos contact -->
                    <div class="info-table" style="
                        display:grid;
                        grid-template-columns:24px 130px 1fr;
                        row-gap:14px;
                        column-gap:12px;
                        align-items:center;
                        color:#e7ecf5;
                        font-weight:700;
                        margin-top:-6px;
                        font-size:.9rem;
                        line-height:1.3;
                    ">
                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:24px;height:24px;color:#cdd9ff;opacity:.95;">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0  0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.3 1.77.55 2.61a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.47-1.08a2 2 0 0 1 2.11-.45c.84.25 1.71.43 2.61.55A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <div class="info-label" style="font-weight:800;line-height:1.1;">Téléphone</div>
                        <div class="info-value" style="color:#c4d0ea;font-weight:600;line-height:1.2;">+33 7 49 82 61 33<br><span style="opacity:.7;font-size:.8em;">(appel gratuit)</span></div>

                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:24px;height:24px;color:#cdd9ff;opacity:.95;">
                            <path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/>
                        </svg>
                        <div class="info-label" style="font-weight:800;line-height:1.1;">Email</div>
                        <div class="info-value" style="color:#c4d0ea;font-weight:600;line-height:1.2;">contact@paristanbul.com</div>

                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:24px;height:24px;color:#cdd9ff;opacity:.95;">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                        <div class="info-label" style="font-weight:800;line-height:1.1;">Horaires</div>
                        <div class="info-value" style="color:#c4d0ea;font-weight:600;line-height:1.2;">Lun–Ven : 9h00–18h00</div>
                    </div>

                    <!-- newsletter -->
                    <div class="newsletter" style="display:flex;flex-direction:column;align-items:center;gap:10px;margin-top:6px;">
                        <h3 class="contact-title" style="
                            margin:0;
                            text-align:center;
                            font-size:clamp(18px,2vw,20px);
                            font-weight:800;
                            color:#fff;
                            text-decoration:underline;
                            text-decoration-thickness:2px;
                            text-underline-offset:6px;
                            text-decoration-color:#A32929;
                        ">
                            Newsletter
                        </h3>
                        <div class="sub" style="text-align:center;color:#c4d0ea;font-size:.9rem;font-weight:500;">
                            Recevez nos promos & actus.
                        </div>

                        <form id="newsletterForm"
                              class="news-wrap"
                              action="newsletter.php"
                              method="post"
                              onsubmit="return subscribeNewsletter(event,this)"
                              novalidate
                              style="
                                display:flex;
                                width:100%;
                                max-width:520px;
                                gap:10px;
                              ">
                            <input class="news-input" type="email" name="email" placeholder="Votre email" required style="
                                flex:1;
                                padding:14px 16px;
                                border-radius:12px;
                                border:1px solid #1e2740;
                                background:linear-gradient(145deg,#0f152b,#0c1223);
                                color:#fff;
                                font-weight:600;
                                font-size:15px;
                                outline:none;
                            ">
                            <input type="text" name="_honey" style="display:none">
                            <button class="news-btn magnet" type="submit" aria-label="S’inscrire" style="
                                display:grid;
                                place-items:center;
                                width:56px;
                                border-radius:12px;
                                border:1px solid #213055;
                                background:linear-gradient(145deg,#122043,#0e1731);
                                color:#cfe0ff;
                                cursor:pointer;
                                box-shadow:0 16px 30px rgba(0,0,0,.8),
                                           0 0 40px rgba(46,76,151,.4);
                            ">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5 12h14M13 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>
            </div><!-- /.contact-grid -->
        </div>
    </section>
</main>

<!-- FOOTER -->
<footer class="pi-footer" style="position:relative;isolation:isolate;">
    <div class="wrap" style="
        max-width:1100px;
        margin:0 auto;
        text-align:center;
        padding:24px 20px 10px;
        position:relative;
        z-index:2;
    ">
        <a href="index.php" style="display:inline-block;">
            <img class="brand" src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" style="
                height:72px;
                width:auto;
                object-fit:contain;
                display:block;
                margin:0 auto 18px;
            ">
        </a>

        <div class="headline" style="
            display:flex;
            align-items:center;
            justify-content:center;
            gap:22px;
            margin:6px auto 18px;
            flex-wrap:wrap;
        ">
            <span class="line" aria-hidden="true" style="
                height:4px;
                width:260px;
                max-width:35vw;
                border-radius:2px;
                background:#D6452E;
            "></span>

            <h2 style="
                margin:0;
                font-weight:800;
                letter-spacing:.12em;
                color:#D6452E;
                font-size:24px;
            ">
                REJOIGNEZ-NOUS
            </h2>

            <span class="line" aria-hidden="true" style="
                height:4px;
                width:260px;
                max-width:35vw;
                border-radius:2px;
                background:#D6452E;
            "></span>
        </div>

        <ul class="social" aria-label="Réseaux sociaux" style="
            list-style:none;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:14px;
            padding:0;
            margin:14px 0 20px;
        ">
            <li><a href="https://www.facebook.com/supermarcheparistanbul/?locale=fr_FR" aria-label="Facebook" style="
                width:42px;
                height:42px;
                display:grid;
                place-items:center;
                background:#101733;
                color:#cfe0ff;
                border-radius:50%;
                border:1px solid #1e2740;
                font-size:18px;
                box-shadow:0 16px 30px rgba(0,0,0,.8),0 0 40px rgba(46,76,151,.4);
            "><i class="fa-brands fa-facebook-f"></i></a></li>

            <li><a href="https://www.instagram.com/paristanbul_supermarche/?hl=fr" aria-label="Instagram" style="
                width:42px;
                height:42px;
                display:grid;
                place-items:center;
                background:#101733;
                color:#cfe0ff;
                border-radius:50%;
                border:1px solid #1e2740;
                font-size:18px;
                box-shadow:0 16px 30px rgba(0,0,0,.8),0 0 40px rgba(214,69,46,.4);
            "><i class="fa-brands fa-instagram"></i></a></li>

            <li><a href="https://www.tiktok.com/@supermarche_paristanbul" aria-label="TikTok" style="
                width:42px;
                height:42px;
                display:grid;
                place-items:center;
                background:#101733;
                color:#cfe0ff;
                border-radius:50%;
                border:1px solid #1e2740;
                font-size:18px;
                box-shadow:0 16px 30px rgba(0,0,0,.8),0 0 40px rgba(46,76,151,.4);
            "><i class="fa-brands fa-tiktok"></i></a></li>

            <li><a href="https://www.youtube.com/channel/UCsjy3bdpFzBwM7MF923gKvA" aria-label="YouTube" style="
                width:42px;
                height:42px;
                display:grid;
                place-items:center;
                background:#101733;
                color:#cfe0ff;
                border-radius:50%;
                border:1px solid #1e2740;
                font-size:18px;
                box-shadow:0 16px 30px rgba(0,0,0,.8),0 0 40px rgba(214,69,46,.4);
            "><i class="fa-brands fa-youtube"></i></a></li>
        </ul>

        <nav class="footer-nav" aria-label="Navigation pied de page" style="
            display:flex;
            flex-wrap:wrap;
            justify-content:center;
            gap:26px 30px;
            padding:12px 0 8px;
            margin:0 auto 12px;
        ">
            <a href="index.php" style="text-decoration:none;color:#e9f1ff;font-weight:800;font-size:14px;letter-spacing:.04em;text-transform:uppercase;">Accueil</a>
            <a href="nosMagasins.php" style="text-decoration:none;color:#e9f1ff;font-weight:800;font-size:14px;letter-spacing:.04em;text-transform:uppercase;">Nos magasins</a>
            <a href="#catalog" style="text-decoration:none;color:#e9f1ff;font-weight:800;font-size:14px;letter-spacing:.04em;text-transform:uppercase;">Catalogue</a>
            <a href="quiSommesNous.html" style="text-decoration:none;color:#e9f1ff;font-weight:800;font-size:14px;letter-spacing:.04em;text-transform:uppercase;">À propos</a>
            <a href="postuler.php" style="text-decoration:none;color:#e9f1ff;font-weight:800;font-size:14px;letter-spacing:.04em;text-transform:uppercase;">Postuler</a>
            <a href="#contact" style="text-decoration:none;color:#e9f1ff;font-weight:800;font-size:14px;letter-spacing:.04em;text-transform:uppercase;">Contact</a>
        </nav>

        <p class="copyright" style="
            margin:6px 0 0;
            font-size:12px;
            color:#9aa4b2;
            user-select:none;
        ">
            © <span id="year"></span> Paristanbul — Tous droits réservés.
            <br><br>
        </p>
    </div>

    <!-- fond footer pleine largeur -->
    <div aria-hidden="true" style="
        content:'';
        position:absolute;
        inset:0;
        left:50%;
        right:50%;
        margin-left:-50vw;
        margin-right:-50vw;
        background:
            radial-gradient(900px 500px at 10% -10%, rgba(46,76,151,.12) 0 60%, #0f1525 60%),
            radial-gradient(900px 500px at 90% -10%, rgba(214,69,46,.10) 0 55%, #0f1525 55%),
            linear-gradient(180deg,#0f1525,#0c1223);
        border-top:1px solid #141a2b;
        box-shadow:inset 0 12px 40px rgba(0,0,0,.35);
        z-index:1;
    "></div>
</footer>

<!-- SCRIPTS EXTERNES -->
<script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin></script>
<script defer src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.js"></script>

<script>
    // petit util toast
    function toast(msg, ok=true){
        const t = document.createElement('div');
        t.style.cssText = `
        position:fixed;
        right:16px;
        top:16px;
        z-index:9999;
        padding:10px 14px;
        border-radius:10px;
        font-weight:700;
        border:1px solid;
        box-shadow:0 10px 30px rgba(0,0,0,.25);
        color:#fff;
    `;
        t.style.background = ok ? 'rgba(16,185,129,.95)' : 'rgba(220,38,38,.95)';
        t.style.borderColor = ok ? 'rgba(16,185,129,.4)' : 'rgba(220,38,38,.4)';
        t.textContent = msg || (ok ? "Merci ! Veuillez confirmer l'email si demandé." : "Une erreur est survenue.");
        document.body.appendChild(t);
        setTimeout(()=>{
            t.style.transition='opacity .35s, transform .35s';
            t.style.opacity='0';
            t.style.transform='translateY(-6px)';
            setTimeout(()=>t.remove(),380);
        },2200);
    }

    // newsletter ajax
    async function subscribeNewsletter(e, form){
        e.preventDefault();
        try {
            const res  = await fetch(form.action, { method:'POST', body:new FormData(form) });
            const json = await res.json().catch(()=>({ok:false,msg:'Réponse invalide'}));
            toast(json.msg || (json.ok ? "Inscription validée" : "Erreur"), !!json.ok);
            if (json.ok) form.reset();
        } catch {
            toast("Impossible de joindre le service.", false);
        }
        return false;
    }

    // util $ / $$
    const $  = (sel,el=document)=>el.querySelector(sel);
    const $$ = (sel,el=document)=>[...el.querySelectorAll(sel)];

    // reveal au scroll
    const io = new IntersectionObserver((entries)=>{
        entries.forEach(e=>{
            if(e.isIntersecting){
                e.target.classList.add('is-visible');
                io.unobserve(e.target);
            }
        });
    },{threshold:.15});

    $$('.section-hd, .catalog-app, .pi-sites, #advantages .carousel, #stores, #contact .contact-panel, footer .wrap').forEach(n=>{
        n.style.opacity='0';
        n.style.transform='translateY(16px) scale(.98)';
        n.style.transition='opacity .5s ease, transform .5s ease';
        io.observe(n);
    });
    function revealNow(el){
        el.style.opacity='1';
        el.style.transform='none';
    }
    io.takeRecords?.().forEach(e=>{ if(e.isIntersecting) revealNow(e.target); });

    // parallax léger pour les médias avec data-parallax
    const parallaxNodes = $$('[data-parallax]');
    function onScrollParallax(){
        const y = window.scrollY||document.documentElement.scrollTop;
        parallaxNodes.forEach(n=>{
            const sp = parseFloat(n.dataset.speed||'0.05');
            n.style.transform = `translateY(${y*sp}px)`;
        });
    }
    onScrollParallax();
    addEventListener('scroll', onScrollParallax, {passive:true});

    /* =======================
       CATALOGUE PageFlip init
       ======================= */
    (function(){
        const PATH = '/Projet-Paristanbul/assets/pages';
        const FILENAME = i => String(i).padStart(2,'0') + '.jpg';

        // ordre pages (tu ajustes ce tableau)
        const PAGES_ORDER = [1,3,4,5,6,7];

        const BUST = `?v=${Date.now()}`;
        const pages = PAGES_ORDER.map(n => `${PATH}/${FILENAME(n)}${BUST}`);
        const TOTAL_PAGES = pages.length;

        // pre-load images
        pages.forEach(src => { const img = new Image(); img.src = src; });

        const flipEl     = document.getElementById('flipbook');
        const stageInner = document.getElementById('stageInner');
        const pageLabel  = document.getElementById('pageLabel');

        let pageFlip;
        let pageAspect = 0.707;
        let pageW = 600;
        let scale = 1;
        let baseScale = 1;

        async function detectAspect(){
            return new Promise(resolve=>{
                const probe = new Image();
                probe.onload = () => {
                    if (probe.naturalWidth && probe.naturalHeight) {
                        pageAspect = probe.naturalWidth / probe.naturalHeight;
                    }
                    resolve();
                };
                probe.onerror = () => resolve();
                probe.src = pages[0];
            });
        }

        function computeSize(){
            const MOBILE_BREAKPOINT = 768;
            const MIN_W = 480;
            const MAX_W = 1040;
            const usePortrait = window.innerWidth < MOBILE_BREAKPOINT;
            const height = Math.floor(window.innerHeight * 0.88);
            let width = Math.round(height * pageAspect);
            width = Math.min(MAX_W, Math.max(MIN_W, width));
            return { width, height, usePortrait };
        }

        function updateMetric(){
            if(!pageFlip) return;
            const i = pageFlip.getCurrentPageIndex();
            pageLabel.textContent = `${i+1} / ${TOTAL_PAGES}`;
        }

        function coverMaskAndCenter(){
            const idx = pageFlip.getCurrentPageIndex();
            const isDouble = !pageFlip.getSettings().usePortrait;

            flipEl.style.clipPath = 'none';
            flipEl.style.webkitClipPath = 'none';
            stageInner.style.transform = `scale(${scale})`;

            // première / dernière page en mode double → centrage / "demi-page"
            if (isDouble && idx === 0) {
                flipEl.style.clipPath = 'inset(0 0 0 50%)';
                flipEl.style.webkitClipPath = 'inset(0 0 0 50%)';
                stageInner.style.transform = `translateX(${-pageW/2}px) scale(${scale})`;
            } else if (isDouble && (TOTAL_PAGES % 2 === 0) && idx === TOTAL_PAGES - 1) {
                flipEl.style.clipPath = 'inset(0 50% 0 0)';
                flipEl.style.webkitClipPath = 'inset(0 50% 0 0)';
                stageInner.style.transform = `translateX(${pageW/2}px) scale(${scale})`;
            }

            updateMetric();
        }

        async function initFlip(startIndex=0){
            await detectAspect();
            const { width, height, usePortrait } = computeSize();
            pageW = width;

            if(pageFlip){ pageFlip.destroy(); }

            pageFlip = new St.PageFlip(flipEl, {
                width,
                height,
                size:'fixed',
                showCover:true,
                usePortrait,
                autoSize:true,
                maxShadowOpacity:0.5,
                mobileScrollSupport:true,
                startPage:startIndex
            });

            pageFlip.loadFromImages(pages);
            pageFlip.on('flip', coverMaskAndCenter);

            scale = baseScale;
            coverMaskAndCenter();
        }

        // boutons catalogue
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const zoomIn  = document.getElementById('zoomIn');
        const zoomOut = document.getElementById('zoomOut');
        const fitBtn  = document.getElementById('fitBtn');

        prevBtn?.addEventListener('click', ()=> pageFlip?.flipPrev());
        nextBtn?.addEventListener('click', ()=> pageFlip?.flipNext());
        zoomIn?.addEventListener('click', ()=>{
            scale = Math.min(2.0, scale + 0.1);
            coverMaskAndCenter();
        });
        zoomOut?.addEventListener('click', ()=>{
            scale = Math.max(0.6, scale - 0.1);
            coverMaskAndCenter();
        });
        fitBtn?.addEventListener('click', ()=>{
            scale = baseScale;
            coverMaskAndCenter();
        });

        // resize -> on recrée le flipbook en gardant la page courante
        let resizeTimer;
        window.addEventListener('resize', ()=>{
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(()=>{
                const current = pageFlip ? pageFlip.getCurrentPageIndex() : 0;
                initFlip(current);
            },150);
        });

        // on démarre
        if(document.readyState!=='loading'){
            initFlip(0);
        } else {
            window.addEventListener('load', ()=>initFlip(0));
        }
    })();

    /* ===========================
       CARROUSEL MAGASINS (track)
       =========================== */
    (function(){
        const vp    = document.querySelector('#advantages .track-viewport');
        const track = document.getElementById('adv-track');
        const prev  = document.getElementById('adv-prev');
        const next  = document.getElementById('adv-next');
        if(!vp || !track) return;

        const GAP = 16;
        let index = 0;
        let startIndex = 0;
        let originals = [...track.children];
        let autoplay = null;

        function cardW(){ return originals[0].getBoundingClientRect().width; }
        function visibleCount(){
            const w = vp.getBoundingClientRect().width;
            return Math.max(1, Math.floor((w+GAP)/(cardW()+GAP)));
        }

        function clearClones(){
            [...track.children].forEach(n=>{
                if(n.dataset && n.dataset.clone) n.remove();
            });
        }

        function cloneNode(n){
            const c = n.cloneNode(true);
            c.dataset.clone = '1';
            return c;
        }

        function instantTranslate(){
            const t = track.style.transition;
            track.style.transition='none';
            translate();
            track.offsetHeight; // force reflow
            track.style.transition=t||'transform .45s cubic-bezier(.22,.84,.3,1)';
        }

        function setupClones(){
            clearClones();
            originals = [...track.children].filter(el=>!el.dataset.clone);
            const V = visibleCount();
            const head = originals.slice(-V).map(cloneNode);
            head.forEach(n=> track.insertBefore(n, track.firstChild));
            const tail = originals.slice(0,V).map(cloneNode);
            tail.forEach(n=> track.appendChild(n));
            startIndex = V;
            index = startIndex;
            instantTranslate();
        }

        function translate(){
            const x = -(index * (cardW()+GAP));
            track.style.transform = `translateX(${x}px)`;
        }

        function goNext(){
            index++;
            translate();
        }
        function goPrev(){
            index--;
            translate();
        }

        track.addEventListener('transitionend', ()=>{
            const V = startIndex;
            const total = originals.length;
            const tailStart = V + total;
            if(index >= tailStart){
                index -= total;
                instantTranslate();
            } else if(index < V){
                index += total;
                instantTranslate();
            }
        });

        prev?.addEventListener('click', e=>{ e.stopPropagation(); goPrev(); });
        next?.addEventListener('click', e=>{ e.stopPropagation(); goNext(); });

        // drag / swipe
        let dragging=false, downX=0, base=0;
        function onDown(e){
            dragging=true;
            downX=(e.touches?e.touches[0].clientX:e.clientX);
            const m=track.style.transform.match(/-?\d+(\.\d+)?/);
            base=m?parseFloat(m[0]):-(index*(cardW()+GAP));
            track.style.transition='none';
        }
        function onMove(e){
            if(!dragging) return;
            const cur=(e.touches?e.touches[0].clientX:e.clientX);
            const dx=cur-downX;
            track.style.transform=`translateX(${base+dx}px)`;
        }
        function onUp(){
            if(!dragging) return;
            dragging=false;
            track.style.transition='';
            const m=track.style.transform.match(/-?\d+(\.\d+)?/);
            const pos=m?parseFloat(m[0]):0;
            const w=cardW()+GAP;
            index=Math.round(-pos/w);
            translate();
        }

        vp.addEventListener('mousedown', onDown);
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', onUp);
        vp.addEventListener('touchstart', onDown,{passive:true});
        vp.addEventListener('touchmove', onMove,{passive:true});
        vp.addEventListener('touchend', onUp);

        // autoplay
        function startAuto(){
            stopAuto();
            autoplay=setInterval(goNext,4000);
        }
        function stopAuto(){
            if(autoplay){clearInterval(autoplay);autoplay=null;}
        }
        startAuto();
        vp.addEventListener('mouseenter', stopAuto);
        vp.addEventListener('mouseleave', startAuto);
        document.addEventListener('visibilitychange', ()=>{ if(document.hidden) stopAuto(); else startAuto(); });

        // init clones + resize watch
        setupClones();
        window.addEventListener('resize', setupClones);
    })();

    /* =====================
       MAGASINS + LEAFLET MAP
       ===================== */
    const storesData = {
        villiers1: {
            title: 'Paristanbul VILLIERS-LE-BEL',
            image: '/Projet-Paristanbul/assets/img/magasins/villiers1.jpg',
            address: '3 avenue des entrepreneurs, VILLIERS-LE-BEL',
            hours: 'Lundi à Dimanche : 08:30-20:00',
            phone: '+33 7 49 82 61 33',
            coordinates: [49.0010, 2.3894]
        },
        drancy: {
            title: 'Paristanbul DRANCY',
            image: '/Projet-Paristanbul/assets/img/magasins/drancy.jpg',
            address: '83 avenue Marceau, DRANCY',
            hours: 'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00',
            phone: '+33 7 49 82 61 33',
            coordinates: [48.9242, 2.4456]
        },
        bondy: {
            title: 'Paristanbul BONDY',
            image: '/Projet-Paristanbul/assets/img/magasins/bondy.jpg',
            address: '116 Av. Gallieni, BONDY',
            hours: 'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00',
            phone: '+33 7 49 82 61 33',
            coordinates: [48.9024, 2.4823]
        },
        villemomble: {
            title: 'Paristanbul VILLEMOMBLE',
            image: '/Projet-Paristanbul/assets/img/magasins/villemomble.jpg',
            address: '68 Allée du Plateau, VILLEMOMBLE',
            hours: 'Lundi à Dimanche : 08:00-20:30',
            phone: '+33 7 49 82 61 33',
            coordinates: [48.8844, 2.5103]
        },
        nogent: {
            title: 'Paristanbul NOGENT-SUR-OISE',
            image: '/Projet-Paristanbul/assets/img/magasins/nogent.jpg',
            address: '171 Rue Jean Monnet, NOGENT-SUR-OISE',
            hours: 'Lundi à Samedi : 09:30-20:00, Dimanche : 10:00-19:00',
            phone: '+33 7 49 82 61 33',
            coordinates: [49.2765, 2.2011]
        },
        vertsaintdenis: {
            title: 'Paristanbul VERT-SAINT-DENIS',
            image: '/Projet-Paristanbul/assets/img/magasins/vertsaintdenis.jpg',
            address: 'La Fontaine Ronde, VERT-SAINT-DENIS',
            hours: 'Lundi à Dimanche : 08:30-20:30',
            phone: '+33 7 49 82 61 33',
            coordinates: [48.6478, 2.6223]
        }
    };

    let currentMap = null;

    function openDirections(address){
        const encoded = encodeURIComponent(address);
        window.open('https://www.google.com/maps/dir/?api=1&destination='+encoded, '_blank');
    }

    function initMap(lat, lng, title, address){
        if(typeof L === 'undefined'){ return; } // sécurité si Leaflet pas encore chargé

        if(currentMap){ currentMap.remove(); }

        currentMap = L.map('map',{ zoomControl:true, scrollWheelZoom:true }).setView([lat,lng],15);

        L.tileLayer(
            'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
            {
                attribution:'© OpenStreetMap • © CARTO',
                subdomains:'abcd',
                maxZoom:19
            }
        ).addTo(currentMap);

        const customIcon = L.divIcon({
            html:'<div style="background:#A32929;width:20px;height:20px;border-radius:50%;border:3px solid white;box-shadow:0 2px 10px rgba(0,0,0,.3);"></div>',
            iconSize:[26,26],
            iconAnchor:[13,13]
        });

        L.marker([lat,lng],{icon:customIcon})
            .addTo(currentMap)
            .bindPopup(`<strong>${title}</strong><br>${address}`)
            .openPopup();

        setTimeout(()=> currentMap.invalidateSize(), 150);
    }

    function createStoreContent(key){
        const s = storesData[key];
        return `
        <div style="
            position:relative;
            padding:16px;
            border-right:1px solid rgba(255,255,255,.07);
            background:radial-gradient(circle at 0% 0%,rgba(46,76,151,.18)0%,transparent 60%);
        ">
            <div id="map" style="
                width:100%;
                height:100%;
                min-height:420px;
                border-radius:12px;
                overflow:hidden;
                border:1px solid rgba(255,255,255,.07);
                box-shadow:0 24px 60px rgba(0,0,0,.8);
            "></div>
        </div>

        <div style="
            display:flex;
            flex-direction:column;
            gap:14px;
            padding:16px 16px 20px;
            background:transparent;
            color:#fff;
        ">
            <img src="${s.image}" alt="${s.title}" style="
                width:100%;
                height:200px;
                object-fit:cover;
                border-radius:12px;
                border:1px solid rgba(255,255,255,.07);
                box-shadow:0 24px 60px rgba(0,0,0,.8);
            " loading="lazy">

            <div style="display:flex;flex-direction:column;gap:8px;">
                <h3 style="
                    margin:0;
                    font-size:1.2rem;
                    font-weight:800;
                    background:linear-gradient(45deg,#8B1A1A,#1c305c);
                    -webkit-background-clip:text;
                    background-clip:text;
                    -webkit-text-fill-color:transparent;
                    line-height:1.2;
                ">
                    ${s.title}
                </h3>

                <div style="
                    display:flex;
                    align-items:flex-start;
                    gap:.8rem;
                    font-size:.9rem;
                    background:#0e1528;
                    border:1px solid #233055;
                    border-radius:12px;
                    padding:.7rem .8rem;
                    color:#d1d9ff;
                    font-weight:600;
                    line-height:1.4;
                ">
                    <svg style="width:20px;height:20px;flex-shrink:0;fill:#A32929" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 5.5 12 5.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                    </svg>
                    <span>${s.address}</span>
                </div>

                <div style="
                    display:flex;
                    align-items:flex-start;
                    gap:.8rem;
                    font-size:.9rem;
                    background:#0e1528;
                    border:1px solid #233055;
                    border-radius:12px;
                    padding:.7rem .8rem;
                    color:#d1d9ff;
                    font-weight:600;
                    line-height:1.4;
                ">
                    <svg style="width:20px;height:20px;flex-shrink:0;fill:#A32929" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                    <span>${s.hours}</span>
                </div>

                <div style="
                    display:flex;
                    align-items:flex-start;
                    gap:.8rem;
                    font-size:.9rem;
                    background:#0e1528;
                    border:1px solid #233055;
                    border-radius:12px;
                    padding:.7rem .8rem;
                    color:#d1d9ff;
                    font-weight:600;
                    line-height:1.4;
                ">
                    <svg style="width:20px;height:20px;flex-shrink:0;fill:#A32929" viewBox="0 0 24 24">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    <span>${s.phone}</span>
                </div>
            </div>

            <div class="actions" style="display:flex;gap:1rem;margin-top:auto;flex-wrap:wrap;">
                <a class="btn magnet" style="
                    flex:1;
                    justify-content:center;
                    background:linear-gradient(45deg,#A32929,#8B1A1A);
                    border:1px solid #A32929;
                    box-shadow:0 20px 40px -10px rgba(139,31,34,.6),
                               0 0 60px rgba(214,69,46,.4);
                    border-radius:14px;
                    padding:12px 16px;
                    font-size:.8rem;
                    font-weight:800;
                    text-transform:uppercase;
                    letter-spacing:.05em;
                    color:#fff;
                    text-align:center;
                    line-height:1;
                " href="#" onclick="openDirections('${s.address}');return false;" rel="noopener">
                    Itinéraire
                </a>

                <a class="btn magnet" style="
                    flex:1;
                    justify-content:center;
                    background:linear-gradient(145deg,#1c305c,#2a3d73);
                    border:1px solid #2a3d73;
                    border-radius:14px;
                    padding:12px 16px;
                    font-size:.8rem;
                    font-weight:800;
                    text-transform:uppercase;
                    letter-spacing:.05em;
                    color:#fff;
                    text-align:center;
                    line-height:1;
                    box-shadow:0 16px 30px rgba(0,0,0,.8),0 0 40px rgba(46,76,151,.4);
                " href="tel:${s.phone.replace(/\s/g,'')}">
                    Appeler
                </a>
            </div>
        </div>
    `;
    }

    function selectStore(key){
        // état actif onglets
        document.querySelectorAll('#stores .nav-tab').forEach(btn=>{
            btn.classList.remove('active');
            // style actif / pas actif en JS:
            btn.style.background = "linear-gradient(145deg,#111a31,#0e1528)";
            btn.style.borderColor= "#223055";
            btn.style.color      = "#e7ecf5";
            btn.style.boxShadow  = "";
        });

        const activeBtn = document.querySelector(`#stores .nav-tab[data-store="${key}"]`);
        if (activeBtn){
            activeBtn.classList.add('active');
            activeBtn.style.background = "linear-gradient(145deg,#1c305c,#2a3d73)";
            activeBtn.style.borderColor= "#2a3d73";
            activeBtn.style.color      = "#fff";
            activeBtn.style.boxShadow  = "inset 0 1px 0 rgba(255,255,255,.07),0 8px 22px rgba(0,0,0,.30)";
        }

        // inject layout
        const area = document.getElementById('contentArea');
        area.style.background = "linear-gradient(180deg,#10182e 0%, #0d1529 100%)";
        area.style.borderTop  = "0";
        area.style.color      = "#fff";
        area.innerHTML = createStoreContent(key);

        // init carte leaflet après injection
        const s = storesData[key];
        setTimeout(()=> initMap(s.coordinates[0], s.coordinates[1], s.title, s.address), 80);
    }

    // click onglets magasins
    document.addEventListener('click', e=>{
        const btn = e.target.closest('#stores .nav-tab');
        if(!btn) return;
        const storeKey = btn.getAttribute('data-store');
        selectStore(storeKey);
    });

    // année footer
    document.getElementById('year').textContent = new Date().getFullYear();

    // init magasin défaut
    window.addEventListener('load', ()=>{
        selectStore('villiers1');
    });
</script>

</body>
</html>