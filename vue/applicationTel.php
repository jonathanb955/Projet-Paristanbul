<?php
session_start();
$isLoggedIn = !empty($_SESSION['user_id']);
$username   = $_SESSION['user_name'] ?? 'Client';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Téléchargez l'application Paristanbul — Supermarché</title>
    <meta name="description" content="Téléchargez l'application mobile Paristanbul pour accéder à nos offres exclusives, catalogues numériques et bien plus encore !" />

    <!-- Fonts + Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

    <style>
        :root{
            --black:#0a0c10; --blue:#0b3b8a; --red:#7b0f20;
            --text:#ffffff; --muted:#c9d4ea; --panel:#0f1320; --ring:#2c59ff55;
            --edge:#1b2235; --panel-2:#0e1422;
            --pi-blue:#2E4C97; --pi-red:#D6452E;
            --ink:#E6E9F2; --muted-2:#cfd5e6;
            --bg-1:#0B1326; --bg-2:#0A0F1F;
            --card:#141B2B; --chip:#1B2436;
            --border:rgba(255,255,255,.06);
            --page-bg:
                    radial-gradient(1000px 500px at 10% 10%, rgba(46,76,151,.25), transparent 60%),
                    radial-gradient(900px 600px at 90% 10%, rgba(214,69,46,.18), transparent 55%),
                    linear-gradient(180deg, var(--bg-1), var(--bg-2) 70%);
        }
        *{box-sizing:border-box; margin:0; padding:0}
        html,body{height:100%; background:var(--bg-1); color:var(--text); font-family:'Plus Jakarta Sans', sans-serif; line-height:1.5;}
        a{color:inherit; text-decoration:none;}
        .container{width:100%; max-width:1200px; margin:0 auto; padding:0 20px;}

        /* ========== HEADER « pi-simple » ========== */
        /* Bandeau promo (marquee) */
        .marquee{position:relative; overflow:hidden; border-top:1px solid #1b2744; border-bottom:1px solid #1b2744; background: linear-gradient(180deg, rgba(15,21,37,.94), rgba(13,19,33,.88)); backdrop-filter: blur(10px);}
        .marquee__inner{display:flex; gap:40px; padding:10px 0; white-space:nowrap; animation:marquee 22s linear infinite}
        .pill{display:inline-flex; align-items:center; gap:8px; padding:6px 12px; border-radius:999px; background:linear-gradient(145deg,#121a34,#0f162a); border:1px solid #223055; font-size:.92rem}
        .pill .dot{width:8px;height:8px;border-radius:50%;background:conic-gradient(from 90deg,var(--red),var(--blue))}
        @keyframes marquee{from{transform:translateX(0)} to{transform:translateX(-50%)}}

        /* Bloc header */
        header{
            position: static;
            background:
                    radial-gradient(600px 300px at 10% 0%, rgba(46,76,151,.18), transparent 60%),
                    radial-gradient(600px 300px at 90% 0%, rgba(214,69,46,.14), transparent 55%),
                    linear-gradient(180deg, #0f1525ee, #0c1223ee);
            border-bottom: 1px solid #141826;
            backdrop-filter: blur(8px);
        }
        header.pi-simple .topbar{
            display:grid; grid-template-columns: 1fr minmax(200px, 1fr) 1fr;
            align-items:center; gap:16px; padding-block: clamp(18px, 3.5vh, 40px);
        }
        .left-col{display:flex; align-items:flex-start}
        .social-group{display:flex; flex-direction:column; align-items:center; width:max-content}
        .social{display:flex; align-items:center; gap:16px; color:var(--muted)}
        .social a{font-size:18px; color:var(--muted)}
        .social a:hover{color:#fff}
        .join{font-size:13px; color:var(--muted); font-weight:800; margin-top:6px; text-align:center}

        .brand{display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px}
        .brand img{height: clamp(60px, 9vw, 72px)}
        .tagline{display:flex; align-items:center; gap:14px; color:var(--muted); font-size: clamp(13px, 1.3vw, 16px); line-height:1}
        .tagline .rule{width: clamp(58px, 9vw, 92px); height:1px; background:rgba(255,255,255,.06)}

        .right-col{display:flex; flex-direction:column; align-items:flex-end; gap:10px; font-weight:800}
        .right-col .phone-row{ display:flex; align-items:center; gap:10px; }
        .right-col i{color:#c9d4ea}
        .phone{font-size: clamp(14px, 1.2vw, 18px); color:#e7ecf5}

        .divider{border:0; border-top:1px solid #141a26; margin:0}
        .navrow{padding:12px 0; position: relative;}
        .menu{display:flex; justify-content:center; gap:28px; list-style:none; margin:0; padding:0}
        .menu a{ font-weight:800; font-size:14px; color:#c9d4ea; letter-spacing:.06em; text-transform:uppercase; }
        .menu a:hover, .menu a.is-active{color:#ffffff}

        /* Bouton connexion (non utilisé ici, laissé pour cohérence du styleguide) */
        .btn-login{
            --ring: rgba(44,89,255,.28);
            --bg1:#0f1833; --bg2:#1c2b59;
            --bd1:#3a58ff; --bd2:#e5473a;
            display:inline-flex; flex-direction:column; align-items:center; justify-content:center; gap:8px;
            padding:14px 18px; min-width:130px; text-align:center; border-radius:16px;
            background: linear-gradient(180deg, var(--bg2), var(--bg1)) padding-box,
            linear-gradient(135deg, var(--bd1), var(--bd2)) border-box;
            border:1px solid transparent; color:#eaf0ff; font-weight:800; letter-spacing:.02em; text-transform:uppercase;
            box-shadow:0 12px 26px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06);
            transition: transform .14s cubic-bezier(.2,.9,.2,1.2), box-shadow .22s ease, filter .22s ease, background .22s ease;
        }
        .btn-login i{
            font-size:18px; line-height:1; width:40px; height:40px; border-radius:999px; display:grid; place-items:center;
            background: radial-gradient(120% 120% at 30% 20%, #2a3f86 0%, #182650 45%, #0f1833 100%);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 6px 18px rgba(58,88,255,.25);
        }
        .btn-login:hover{ transform:translateY(-1px); box-shadow: 0 16px 34px rgba(0,0,0,.45), 0 0 0 3px var(--ring); filter:brightness(1.04);}
        .btn-login:active{ transform:translateY(0) scale(.995); filter:brightness(.98); }

        @media (max-width:720px){
            header.pi-simple .topbar{grid-template-columns:1fr; row-gap:10px; text-align:center}
            .left-col{justify-content:center}
            .menu{flex-wrap:wrap; gap:18px}
            .right-col{ align-items:center; }
            .btn-login{ padding:12px 14px; min-width:118px; border-radius:14px; }
            .btn-login i{ width:36px; height:36px; font-size:17px; }
        }

        /* ========== CONTENU DE LA PAGE (hero, features, cta…) ========== */
        /* Hero Section */
        .app-hero{padding:120px 0 100px; background:var(--page-bg); position:relative; overflow:hidden;}
        .hero-content{display:flex; align-items:center; gap:60px; position:relative; z-index:2;}
        .hero-text{flex:1; max-width:600px;}
        .hero-text h1{font-size:clamp(2.5rem, 5vw, 4rem); font-weight:800; line-height:1.1; margin-bottom:24px; background:linear-gradient(45deg, #fff, #c9d4ea); -webkit-background-clip:text; -webkit-text-fill-color:transparent;}
        .hero-text p{font-size:1.2rem; color:var(--muted); margin-bottom:32px; max-width:500px;}
        .app-badges{display:flex; gap:16px; margin-top:10px;}
        .app-badge{height:50px; transition:transform 0.2s;}
        .app-badge:hover{transform:translateY(-3px);}
        .hero-image{flex:1; position:relative;}
        .phone-mockup{width:100%; max-width:300px; margin:0 auto; position:relative; animation:float 6s ease-in-out infinite;}
        .phone-mockup img{width:100%; height:auto; display:block;}
        .floating-element{position:absolute; background:var(--pi-red); width:100px; height:100px; border-radius:50%; filter:blur(60px); opacity:0.3;}
        .floating-1{top:10%; left:10%; width:200px; height:200px; background:var(--pi-blue);}
        .floating-2{bottom:10%; right:10%; width:150px; height:150px;}

        /* Petit texte “Cliquez ici pour télécharger” */
        .download-note{
            font-size:.95rem;
            color:var(--muted);
            margin:8px 0 6px;
            display:flex; align-items:center; gap:8px;
        }
        .download-note i{ font-size:1rem; opacity:.9; }
        @media (max-width: 992px){
            .download-note{ justify-content:center; }
        }

        /* Features Section */
        .features{background:var(--bg-2); padding:100px 0; position:relative;}
        .section-title{text-align:center; margin-bottom:60px;}
        .section-title h2{font-size:2.5rem; font-weight:800; margin-bottom:16px; background:linear-gradient(45deg, var(--pi-red), var(--pi-blue)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; display:inline-block;}
        .section-title p{color:var(--muted); max-width:600px; margin:0 auto;}
        .features-grid{display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:30px;}
        .feature-card{background:var(--card); border:1px solid var(--border); border-radius:16px; padding:32px; transition:transform 0.3s, box-shadow 0.3s;}
        .feature-card:hover{transform:translateY(-5px); box-shadow:0 15px 30px rgba(0,0,0,0.2);}
        .feature-icon{width:60px; height:60px; background:linear-gradient(135deg, var(--pi-red), var(--pi-blue)); border-radius:16px; display:flex; align-items:center; justify-content:center; margin-bottom:24px;}
        .feature-icon i{font-size:28px; color:white;}
        .feature-card h3{font-size:1.4rem; margin-bottom:16px; color:var(--text);}
        .feature-card p{color:var(--muted); margin-bottom:0;}

        /* CTA Section */
        .cta-section{background:linear-gradient(135deg, var(--pi-blue), var(--pi-red)); padding:80px 0; text-align:center; position:relative; overflow:hidden;}
        .cta-content{position:relative; z-index:2; max-width:800px; margin:0 auto; padding:0 20px;}
        .cta-content h2{font-size:2.5rem; font-weight:800; margin-bottom:24px; color:white;}
        .cta-content p{font-size:1.2rem; color:rgba(255,255,255,0.9); margin-bottom:32px; max-width:600px; margin-left:auto; margin-right:auto;}
        .cta-buttons{display:flex; justify-content:center; gap:20px; flex-wrap:wrap;}
        .btn{display:inline-flex; align-items:center; justify-content:center; padding:12px 28px; border-radius:12px; font-weight:600; font-size:1rem; transition:all 0.3s; border:none; cursor:pointer;}
        .btn-primary{background:white; color:var(--pi-red);}
        .btn-primary:hover{transform:translateY(-3px); box-shadow:0 10px 20px rgba(0,0,0,0.2);}
        .btn-outline{background:transparent; border:2px solid white; color:white;}
        .btn-outline:hover{background:rgba(255,255,255,0.1); transform:translateY(-3px);}
        .cta-bg{position:absolute; width:100%; height:100%; top:0; left:0; opacity:0.1;}
        .cta-bg div{position:absolute; border-radius:50%; background:white;}

        /* Animations */
        @keyframes float{
            0%, 100%{transform:translateY(0);}
            50%{transform:translateY(-20px);}
        }

        /* Responsive */
        @media (max-width: 992px){
            .hero-content{flex-direction:column-reverse; text-align:center; gap:40px;}
            .hero-text{max-width:100%;}
            .hero-text p{margin-left:auto; margin-right:auto;}
            .app-badges{justify-content:center;}
        }
        @media (max-width: 768px){
            .app-hero{padding:100px 0 80px;}
            .features{padding:80px 0;}
            .features-grid{grid-template-columns:1fr;}
            .cta-content h2{font-size:2rem;}
        }
        @media (max-width: 576px){
            .app-badges{flex-direction:column; align-items:center;}
            .btn{padding:10px 20px; font-size:0.9rem;}
        }

        /* ===== Footer avancé (pi-footer) ===== */
        footer.pi-footer{ position: relative; isolation: isolate; }
        footer.pi-footer::before{
            content:""; position:absolute; z-index:-1; top:0; bottom:0; left:50%; right:50%;
            margin-left:-50vw; margin-right:-50vw;
            background:
                    radial-gradient(900px 500px at 10% -10%, rgba(46,76,151,.12), transparent 60%),
                    radial-gradient(900px 500px at 90% -10%, rgba(214,69,46,.10), transparent 55%),
                    linear-gradient(180deg, #0f1525, #0c1223);
            border-top: 1px solid #141a2b;
            box-shadow: inset 0 12px 40px rgba(0,0,0,.35);
        }
        .pi-footer .wrap{ max-width:1100px; margin:0 auto; text-align:center; padding:24px 20px 10px; }
        .pi-footer .brand{ height:72px; width:auto; object-fit:contain; display:block; margin:0 auto 18px; }
        .pi-footer .headline{ display:flex; align-items:center; justify-content:center; gap:22px; margin:6px auto 18px; }
        .pi-footer .headline h2{ margin:0; font-weight:800; letter-spacing:.12em; color:var(--pi-red, #D6452E); font-size:24px; }
        .pi-footer .headline .line{ height:4px; width:260px; border-radius:2px; background:var(--pi-red, #D6452E); transform-origin:center; }
        @media (max-width:720px){ .pi-footer .headline .line{ width:20vw } .pi-footer .headline h2{ font-size:20px } }
        .pi-footer .social{ list-style:none; display:flex; justify-content:center; align-items:center; gap:14px; padding:0; margin:14px 0 20px; }
        .pi-footer .social a{ width:42px; height:42px; display:grid; place-items:center; background:#101733; color:#cfe0ff; border-radius:50%; border:1px solid #1e2740; font-size:18px; transition:transform .2s ease, background .2s ease, border-color .2s ease, color .2s ease; }
        .pi-footer .social a:hover{ background: linear-gradient(145deg, var(--pi-blue,#2E4C97), var(--pi-red,#D6452E)); border-color:#2a3659; color:#fff; transform:translateY(-2px); }
        .pi-footer .footer-nav{ display:flex; flex-wrap:wrap; justify-content:center; gap:26px 30px; padding:12px 0 8px; margin:0 auto 12px; }
        .pi-footer .footer-nav a{ text-decoration:none; color:#e9f1ff; font-weight:800; font-size:14px; letter-spacing:.04em; text-transform:uppercase; transition:color .2s ease; }
        .pi-footer .footer-nav a:hover{ color:var(--pi-red,#D6452E) }
        .pi-footer .copyright{ margin:6px 0 0; font-size:12px; color:var(--muted); user-select:none; }

        /* ===== Fond global (identique à la home) ===== */
        html,body{ background:transparent !important; } /* laisse voir le fond fixe derrière */
        #page-bg{ position:fixed; inset:0; z-index:-2; pointer-events:none; background:var(--page-bg); }
        .pi-orbs{ position:fixed; inset:0; z-index:-1; pointer-events:none; overflow:hidden; }
        .pi-orbs .orb{
            position:absolute; width:48vmax; height:48vmax; border-radius:9999px;
            filter:blur(80px); opacity:.75; mix-blend-mode:screen; will-change:transform;
        }
        .pi-orbs .blue{ background:rgba(46,76,151,.18); }
        .pi-orbs .red { background:rgba(226,27,60,.16);  }
        .pi-orbs .a{ top:-10vmax; left:-6vmax;  animation:orbA 36s linear infinite; }
        .pi-orbs .b{ top:-8vmax;  right:-10vmax; animation:orbB 42s linear infinite; }
        .pi-orbs .c{ bottom:-12vmax; left:15vw;  animation:orbC 40s linear infinite; width:42vmax;height:42vmax;}
        .pi-orbs .d{ bottom:-14vmax; right:10vw; animation:orbD 46s linear infinite; width:50vmax;height:50vmax;}
        @keyframes orbA{ 50%{ transform:translate3d(4vw,2vh,0) scale(1.05);} }
        @keyframes orbB{ 50%{ transform:translate3d(-3vw,3vh,0) scale(1.03);} }
        @keyframes orbC{ 50%{ transform:translate3d(2vw,-2vh,0) scale(1.06);} }
        @keyframes orbD{ 50%{ transform:translate3d(-2vw,-3vh,0) scale(1.04);} }
        @media (prefers-reduced-motion:reduce){ .pi-orbs .orb{ animation:none; opacity:.55; } }
    </style>
</head>
<body>
<!-- Fond global -->
<div id="page-bg" aria-hidden="true"></div>
<div class="pi-orbs" aria-hidden="true">
    <span class="orb blue a"></span>
    <span class="orb red  b"></span>
    <span class="orb blue c"></span>
    <span class="orb red  d"></span>
</div>



<!-- ====== Header « pi-simple » intégré ====== -->
<header class="pi-simple">
    <div class="container topbar">
        <!-- Gauche -->
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

        <!-- Centre -->
        <div class="brand">
            <a href="index.php" class="navbar-brand">
                <img src="/assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
            </a>
            <div class="tagline">
                <span class="rule" aria-hidden="true"></span>
                <span>Since 1993</span>
                <span class="rule" aria-hidden="true"></span>
            </div>
        </div>

        <!-- Droite -->
        <div class="right-col">
            <div class="phone-row">
                <i class="fa-solid fa-phone"></i>
                <a class="phone" href="tel:+33749826133">07 49 82 61 33</a>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- Nav -->
    <div class="container navrow">
        <ul class="menu" aria-label="Navigation principale">
            <?php if (!empty($_SESSION['user_id']) && (($_SESSION['user_role'] ?? '') === 'admin')): ?>
                <li><a href="pageAdmin.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre Histoire</a></li>
            <li><a href="index.php#catalog">Catalogue</a></li>
            <li><a href="nosMagasins.php">Nos Magasins</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <li><a href="postuler.php">Postuler</a></li>
            <li><a href="applicationTel.php" class="is-active">Application</a></li>
        </ul>
    </div>

    <hr class="divider">
</header>

<!-- ====== Hero Section ====== -->
<section class="app-hero">
    <div class="container">
        <div class="hero-content" style="display: flex; align-items: center; gap: 40px;">
            <!-- Logo à gauche -->
            <div class="hero-image" style="flex: 0 0 30%;">
                <img src="/assets/img/petit-logo.jpg" alt="Logo Paristanbul" style="width: 100%; max-width: 280px; height: auto; border-radius: 24px; box-shadow: 0 15px 30px -10px rgba(0,0,0,0.2);">
            </div>

            <!-- Texte à droite -->
            <div class="hero-text" style="flex: 0 1 60%;">
                <style>
                    @keyframes gradientMove {
                        0% { background-position: 0% 50%; }
                        50% { background-position: 100% 50%; }
                        100% { background-position: 0% 50%; }
                    }
                    .gradient-text {
                        background: linear-gradient(90deg, #2E4C97, #D6452E, #2E4C97);
                        background-size: 200% auto;
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                        display: inline-block;
                        animation: gradientMove 5s ease-in-out infinite;
                    }
                </style>

                <div style="margin-bottom: 10px;">
                    <h1 style="margin: 0; font-size: 2.2em; font-weight: 700; display: flex; align-items: center; gap: 8px; color: #333; line-height: 1;">
                        <span>L'application</span>
                        <span class="gradient-text" style="font-size: 1.1em;">Paristanbul</span>
                    </h1>
                    <p style="margin: 0; color: white; font-size: 2.2em; font-weight: 300; line-height: 1;">dans votre poche</p>
                </div>

                <em style="margin: 0 0 20px 0; color: #555; line-height: 1.5; color: white">
                    Accédez à nos offres exclusives, consultez nos catalogues numériques,
                    gérez vos listes de courses et profitez de réductions personnalisées
                    où que vous soyez.
                </em>

                <!-- Petit texte d’aide au téléchargement -->
                <p class="download-note">
                    <i class="fa-solid fa-circle-down" aria-hidden="true"></i>
                    Cliquez pour télécharger l’application
                </p>

                <div class="app-badges" style="display: flex; gap: 15px; align-items: center;">
                    <a href="https://play.google.com/store/apps/details?id=com.akead.paristanbul&hl=fr"
                       target="_blank"
                       rel="noopener noreferrer"
                       style="display: inline-flex; align-items: center; height: 65px; margin-top: -4px;">
                        <img src="https://play.google.com/intl/en_us/badges/static/images/badges/fr_badge_web_generic.png"
                             alt="Disponible sur Google Play"
                             style="height: 100%; width: auto;">
                    </a>
                    <a href="https://apps.apple.com/fr/app/paristanbul-plus/id6743162682"
                       target="_blank"
                       rel="noopener noreferrer"
                       style="display: inline-block; height: 50px; margin-top: -2px;">
                        <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                             alt="Télécharger sur l'App Store"
                             height="50" style="height: 100%; width: auto;">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="floating-element floating-1"></div>
    <div class="floating-element floating-2"></div>
</section>

<!-- ====== Features Section ====== -->
<section class="features">
    <div class="container">
        <div class="section-title">
            <h2>Découvrez nos fonctionnalités</h2>
            <p>Tout ce dont vous avez besoin pour une expérience de shopping optimale</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>Promotions exclusives</h3>
                <p>Bénéficiez d'offres spéciales réservées aux utilisateurs de l'application et économisez sur vos courses.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-barcode"></i>
                </div>
                <h3>Scan &amp; Achetez</h3>
                <p>Scannez les codes-barres en magasin pour voir les prix, les promotions et les informations nutritionnelles.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-list"></i>
                </div>
                <h3>Listes de courses</h3>
                <p>Créez et partagez facilement vos listes de courses avec votre famille et vos amis.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Localisation en magasin</h3>
                <p>Trouvez facilement les produits dans nos rayons avec notre plan interactif des magasins.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3>Alertes personnalisées</h3>
                <p>Soyez informé en temps réel des promotions sur vos produits préférés.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3>Paiement mobile</h3>
                <p>Payez rapidement et facilement directement depuis votre smartphone.</p>
            </div>
        </div>
    </div>
</section>

<!-- ====== Comment ça marche ====== -->
<section class="how-it-works">
    <div class="container">
        <h2>Comment ça marche ?</h2>
        <p class="subtitle">Installation en 3 minutes.</p>

        <div class="steps-container">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Téléchargez</h3>
                <p>Ouvrez l'App Store ou Google Play et installez "Paristanbul".</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Créez un compte</h3>
                <p>Renseignez votre e-mail et validez en un instant.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Activez la fidélité</h3>
                <p>Votre carte apparaît automatiquement dans l'app.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h3>Profitez</h3>
                <p>Scannez en caisse, cumulez des points, recevez des récompenses.</p>
            </div>
        </div>
    </div>
</section>

<style>
    /* Styles de la section Comment ça marche */
    .how-it-works { padding: 80px 0; background: #f8f9fa; text-align: center; }
    .how-it-works h2 { font-size: 2.5rem; color: #2E4C97; margin-bottom: 15px; }
    .subtitle { font-size: 1.2rem; color: #6c757d; margin-bottom: 50px; }
    .steps-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
    .step-card { background: white; border-radius: 12px; padding: 30px 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .step-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
    .step-number { width: 50px; height: 50px; background: linear-gradient(135deg, #2E4C97, #D6452E); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin: 0 auto 20px; }
    .step-card h3 { color: #2E4C97; margin-bottom: 15px; font-size: 1.3rem; }
    .step-card p { color: #6c757d; line-height: 1.6; }
    @media (max-width: 768px) {
        .steps-container { grid-template-columns: 1fr; max-width: 500px; }
        .step-card { text-align: center; }
    }
    /* ====== Animations ====== */
    .animate {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }

    .feature-card {
        transition: opacity 0.6s ease-out, transform 0.6s ease-out, box-shadow 0.3s ease !important;
    }

    .feature-card:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.25) !important;
    }

    .section-title h2, .section-title p {
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .cta-content {
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }
</style>

<!-- ====== CTA Section ====== -->
<section class="cta-section">
    <div class="cta-content">
        <h2>Prêt à faire vos courses plus intelligemment ?</h2>
        <p>Téléchargez dès maintenant l'application Paristanbul et profitez d'une expérience de shopping améliorée.</p>
        <div class="cta-buttons">
            <a href="https://apps.apple.com/fr/app/paristanbul-plus/id6743162682" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-apple" style="margin-right:8px;"></i> App Store
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.akead.paristanbul&hl=fr" class="btn btn-outline" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-google-play" style="margin-right:8px;"></i> Google Play
            </a>
        </div>
    </div>
    <div class="cta-bg">
        <div style="width:300px; height:300px; top:-100px; right:-50px;"></div>
        <div style="width:200px; height:200px; bottom:-50px; left:-50px;"></div>
    </div>
</section>

<!-- ====== Footer ====== -->
<footer class="pi-footer">
    <div class="wrap">
        <a href="index.php">
            <img class="brand" src="/assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
        </a>

        <div class="headline">
            <span class="line" aria-hidden="true"></span>
            <h2>REJOIGNEZ-NOUS</h2>
            <span class="line" aria-hidden="true"></span>
        </div>

        <ul class="social" aria-label="Réseaux sociaux">
            <li><a href="https://www.facebook.com/supermarcheparistanbul/?locale=fr_FR" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
            <li><a href="https://www.instagram.com/paristanbul_supermarche/?hl=fr" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="https://www.tiktok.com/@supermarche_paristanbul" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a></li>
            <li><a href="https://www.youtube.com/channel/UCsjy3bdpFzBwM7MF923gKvA" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a></li>
        </ul>

        <nav class="footer-nav" aria-label="Navigation pied de page">
            <a href="index.php">Accueil</a>
            <a href="nosMagasins.php">Nos magasins</a>
            <a href="index.php#catalog">Catalogue</a>
            <a href="quiSommesNous.html">À propos</a>
            <a href="postuler.php">Postuler</a>
            <a href="index.php#contact">Contact</a>
        </nav>

        <p class="copyright">
            © <?php echo date('Y'); ?> Paristanbul — Tous droits réservés.
            <br><br>
        </p>
    </div>
</footer>

<script>
    // Animation au défilement pour les features + titre
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card').forEach((card) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            observer.observe(card);
        });

        const sectionTitles = document.querySelectorAll('.section-title');
        sectionTitles.forEach(title => {
            title.style.opacity = '0';
            title.style.transform = 'translateY(20px)';
            title.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(title);
            setTimeout(() => {
                if (title.isConnected) {
                    title.style.opacity = '1';
                    title.style.transform = 'translateY(0)';
                }
            }, 300);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration des animations
        const animationConfig = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        // Fonction d'animation
        const animateOnScroll = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                    // Ne plus observer l'élément après l'animation
                    observer.unobserve(entry.target);
                }
            });
        };

        // Création de l'observateur
        const observer = new IntersectionObserver(animateOnScroll, animationConfig);

        // Cibler les éléments à animer
        const elementsToAnimate = document.querySelectorAll('.hero-text, .hero-image, .feature-card, .section-title, .cta-content');

        // Ajouter la classe d'animation initiale et observer chaque élément
        elementsToAnimate.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            observer.observe(element);
        });

        // Gestion spécifique des cartes de fonctionnalités avec un délai progressif
        const featureCards = document.querySelectorAll('.feature-card');
        featureCards.forEach((card, index) => {
            card.style.transitionDelay = `${index * 0.15}s`;
        });

        // Gestion de l'animation du bouton d'appel à l'action
        const ctaButton = document.querySelector('.cta-content .btn');
        if (ctaButton) {
            ctaButton.style.transform = 'scale(0.95)';
            ctaButton.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';

            ctaButton.addEventListener('mouseenter', () => {
                ctaButton.style.transform = 'scale(1.05)';
                ctaButton.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2)';
            });

            ctaButton.addEventListener('mouseleave', () => {
                ctaButton.style.transform = 'scale(1)';
                ctaButton.style.boxShadow = 'none';
            });
        }
    });
</script>
</body>
</html>