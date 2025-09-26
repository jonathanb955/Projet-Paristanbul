<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Paristanbul — Supermarché</title>
    <meta name="description" content="Paristanbul : vos courses, nos magasins, notre catalogue interactif et nos meilleures offres." />

    <!-- Police élégante -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin />

    <!-- PageFlip -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css">

    <style>
        :root{
            --black:#0a0c10; --blue:#0b3b8a; --red:#7b0f20;
            --text:#ffffff; --muted:#c9d4ea; --panel:#0f1320; --ring:#2c59ff55;
            --edge:#1b2235; --panel-2:#0e1422;

            /* Strip défilant */
            --strip-gap: clamp(24px, 3vw, 48px);
            --strip-card: clamp(180px, 20vw, 300px);
            --strip-radius: 18px;
            --strip-border: 5px;
            --strip-speed: 22s;
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0; font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;
            color:var(--text); background:var(--black); overflow-x:hidden; position:relative;
        }
        a{color:inherit;text-decoration:none}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}

        /* Header */
        header{position:sticky; top:0; z-index:50; background:transparent; border-bottom:1px solid #141826}
        .nav{display:flex; align-items:center; justify-content:space-between; gap:16px; height:66px}
        .brand{display:flex; align-items:center; gap:12px; font-weight:800; letter-spacing:.3px}
        .brand-badge{width:34px;height:34px;border-radius:10px; background:linear-gradient(145deg,var(--blue),#0a204a); display:grid;place-items:center; box-shadow:0 8px 20px #0a1a38}
        .nav a.btn{padding:10px 16px;border-radius:12px; background:linear-gradient(145deg,#1a2237,#0f172a); border:1px solid #1e2740}
        .nav a.btn:hover{outline:2px solid var(--ring)}
        .nav-links{display:flex; gap:14px; align-items:center}

        /* Marquee */
        .marquee{position:relative; overflow:hidden; border-top:1px solid #151a2a; border-bottom:1px solid #151a2a; background:transparent;}
        .marquee__inner{display:flex; gap:40px; padding:10px 0; white-space:nowrap; animation:marquee 22s linear infinite}
        .pill{display:inline-flex; align-items:center; gap:8px; padding:6px 12px; border-radius:999px; background:linear-gradient(145deg,#101733,#111621); border:1px solid #1a2340; font-size:.92rem}
        .pill .dot{width:8px;height:8px;border-radius:50%;background:conic-gradient(from 90deg,var(--red),var(--blue))}
        @keyframes marquee{from{transform:translateX(0)} to{transform:translateX(-50%)}}

        /* Hero */
        #hero{position:relative; padding:64px 0 40px;}
        .hero-wrap{display:grid; grid-template-columns:1.1fr .9fr; gap:40px; align-items:center}
        .eyebrow{font-size:.9rem; color:var(--muted); letter-spacing:.2em; text-transform:uppercase}
        h1{font-size:clamp(32px,4.6vw,58px); line-height:1.04; margin:.3em 0;}
        .lead{font-size:1.1rem; color:#e3eaff}
        .cta-row{display:flex; gap:12px; margin-top:18px}
        .btn{display:inline-flex; align-items:center; gap:10px; padding:12px 16px; border-radius:14px; border:1px solid #1f2842; background:linear-gradient(145deg,#151c32,#0f1424); font-weight:700}
        .btn.primary{background:linear-gradient(145deg,#102453,var(--blue)); border-color:#0f2b6a}

        .video-card{position:relative; border-radius:18px; overflow:hidden; border:1px solid #1a2033; box-shadow:0 20px 60px #070a12}
        .video-card video{display:block; width:100%; height:auto}
        .video-overlay{position:absolute; inset:0; display:grid; place-items:center; background:linear-gradient(180deg,#00000055,#00000000); pointer-events:none}
        .play-hint{pointer-events:auto; display:inline-flex; align-items:center; gap:10px; background:#00000080; padding:10px 14px; border-radius:12px; border:1px solid #ffffff20}

        /* Sections */
        section{padding:64px 0}
        .section-hd{display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:20px}
        .section-hd h2{font-size:clamp(24px,3.3vw,40px); margin:0}
        .sub{color:var(--muted)}

        /* Reveal anim */
        .reveal{opacity:0; transform:translateY(16px) scale(.98); filter:saturate(.9); transition:opacity .5s ease, transform .5s ease, filter .5s ease}
        .reveal.is-visible{opacity:1; transform:none; filter:none}

        /* ===== CATALOGUE ===== */
        #catalog .catalog-app{display:flex; flex-direction:column; gap:16px}
        #catalog .toolbar{
            position:static;
            background:linear-gradient(180deg,#0d1321cc 0%,#0d132199 100%);
            border:1px solid #182037; border-radius:14px; overflow:hidden;
            backdrop-filter:blur(8px);
        }
        #catalog .toolbar .row{ display:flex; gap:.6rem; align-items:center; flex-wrap:wrap; padding:.65rem 1rem }
        #catalog .brand{font-weight:800; letter-spacing:.2px; display:flex; gap:.6rem; align-items:center}
        #catalog .brand .dot{ width:.6rem; height:.6rem; border-radius:999px; background:#3aa0ff; box-shadow:0 0 0 4px #3aa0ff22; }
        #catalog .btn{
            appearance:none; border:1px solid #1f2942; background:#131a2a; color:var(--text);
            border-radius:999px; padding:.55rem .9rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; font-weight:800;
            box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004; transition:transform .1s ease, border-color .2s ease, background .2s ease;
        }
        #catalog .btn:hover{ border-color:#2a3659; background:#0f1626 }
        #catalog .btn.icon{ padding:.55rem .7rem }
        #catalog .sep{ width:1px; height:28px; background:#223052; opacity:.6; margin:0 .25rem }
        #catalog .metric{ margin-left:auto; display:inline-flex; align-items:center; gap:.5rem; font-weight:800; color:#cfe0ff; background:#0e1423; border:1px solid #1f2942; padding:.45rem .75rem; border-radius:.75rem; box-shadow:0 1px 0 #ffffff0d inset; }
        #catalog .metric small{ color:#8fa0bf; font-weight:700 }

        #catalog .stage{
            position:relative; border:1px solid var(--edge); background:var(--panel); border-radius:18px;
            box-shadow:0 24px 60px #0009, inset 0 1px 0 #ffffff12, inset 0 0 0 1px #0008;
            display:grid; place-items:center; overflow:hidden;
        }
        #catalog .stage::after{ content:""; position:absolute; inset:0; pointer-events:none; background:radial-gradient(1400px 700px at 50% -10%, transparent 0%, #00000022 60%, #00000055 100%) }
        #catalog .stage-inner{ position:relative; transform-origin:50% 50%; transition:transform .15s ease; overflow:hidden; border-radius:14px }

        /* Taille d'origine */
        #flipbook{ width:min(92vw,1040px); height:88vh }
        #catalog .icon svg{ width:18px; height:18px; display:block }
        @media (max-width:768px){ #flipbook{ height:92dvh } #catalog .metric{ display:none } }

        /* ======= Nos avantages ======= */
        #advantages .carousel{
            position:relative; isolation:isolate;
            background:linear-gradient(180deg,var(--panel),var(--panel-2));
            border:1px solid var(--edge); border-radius:18px;
            padding:clamp(14px,2vw,18px);
            box-shadow:0 18px 40px rgba(0,0,0,.35);
        }
        #advantages .track-viewport{position:relative; overflow:hidden; border-radius:12px;}
        #advantages .track{display:flex; gap:16px; will-change:transform; transition:transform .45s cubic-bezier(.22,.84,.3,1); touch-action:pan-y;}
        #advantages .card{
            min-width:clamp(260px,42vw,340px); flex:0 0 clamp(260px,42vw,340px);
            background:linear-gradient(180deg,#0e1422,#0b101b); border:1px solid var(--edge); border-radius:16px; overflow:hidden; padding:16px;
            transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        #advantages .thumb{aspect-ratio:16/9; background:linear-gradient(135deg,#142036,#171d2b); border:1px solid #202a44; border-radius:12px; margin-bottom:12px; overflow:hidden;}
        #advantages .thumb img{width:110%; height:110%; object-fit:cover; transform:scale(1); transition:transform .35s ease;}
        #advantages .card:hover .thumb img{transform:scale(1.06)}
        #advantages .meta{display:flex; align-items:center; gap:10px; color:var(--muted); font-size:13px; margin-bottom:6px}
        #advantages .dot{width:8px; height:8px; border-radius:50%; background:#2f7bff}
        #advantages .card h3{margin:6px 0 6px; font-size:clamp(16px,2.2vw,19px)}
        #advantages .tags{display:flex; gap:8px; flex-wrap:wrap; margin-top:10px}
        #advantages .tag{font-size:12px; padding:4px 8px; border-radius:999px; border:1px solid #243055; color:#e9f1ff; opacity:.95}

        /* Flèches statiques */
        #advantages .adv-nav{ position:absolute; inset:0; pointer-events:none; z-index:2; }
        #advantages .adv-nav button{
            pointer-events:auto; position:absolute; top:50%; transform:translateY(-50%);
            width:46px; height:46px; border-radius:14px;
            border:1px solid #223055; background:rgba(15,22,35,.86); color:#cfe0ff;
            display:grid; place-items:center; cursor:pointer; backdrop-filter:blur(6px);
            transition:background .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        #advantages .adv-nav .prev{left:10px}
        #advantages .adv-nav .next{right:10px}
        #advantages .adv-nav button:hover{box-shadow:0 6px 24px rgba(0,0,0,.35); background:rgba(20,28,48,.95)}
        #advantages .adv-nav svg{width:22px; height:22px}

        /* ======= STRIP défilant (au-dessus des magasins) ======= */
        .strip-section{ padding:48px 0 24px; }
        .strip{
            width:100%; padding: clamp(12px, 2.2vh, 24px) 0; overflow:hidden; position:relative;
            border-radius:18px;
        }
        .strip::before,.strip::after{
            content:""; position:absolute; top:0; bottom:0; width:10vw; pointer-events:none; z-index:2;
        }
        .strip::before{ left:0; background:linear-gradient(90deg, var(--black) 0%, rgba(10,10,10,0.0) 100%); }
        .strip::after{ right:0; background:linear-gradient(270deg, var(--black) 0%, rgba(10,10,10,0.0) 100%); }
        .marquee-strip{ width: max(200%, 200vw); }
        .track-strip{ display:flex; align-items:center; gap:var(--strip-gap); width:max-content; animation: stripScroll var(--strip-speed) linear infinite; }
        .strip:hover .track-strip{ animation-play-state: paused; }
        @keyframes stripScroll{ from{transform:translateX(0)} to{transform:translateX(-50%)} }

        .card-strip{
            width:var(--strip-card); aspect-ratio:1/1;
            border-radius: calc(var(--strip-radius) + var(--strip-border));
            padding: var(--strip-border);
            background:
                    linear-gradient(#0b0b0b, #0b0b0b) padding-box,
                    conic-gradient(from 225deg at 20% 80%, rgba(255,255,255,.95),
                    rgba(255,255,255,.15), rgba(255,255,255,.6), rgba(255,255,255,.2),
                    rgba(255,255,255,.9)) border-box;
            border:2px solid transparent;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.04), 0 12px 40px rgba(0,0,0,0.55);
        }
        .card-strip .inner{ width:100%; height:100%; border-radius:var(--strip-radius); overflow:hidden; background:#0a0a0a; position:relative; display:block; }
        .card-strip img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:block; }
        .card-strip .inner::after{ content:""; position:absolute; inset:0; pointer-events:none; border-radius:inherit; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.035); }

        /* ======= NOS MAGASINS (onglets + carte) ======= */
        #stores .nav-tabs{
            display:flex; justify-content:center; gap:12px; margin-bottom:26px; flex-wrap:wrap;
        }
        #stores .nav-tab{
            background:transparent; border:1px solid #1f2942; color:#e6edff;
            font-weight:800; padding:.75rem 1.1rem; cursor:pointer; border-radius:999px;
            transition:all .25s ease; display:inline-flex; align-items:center; gap:8px;
        }
        #stores .nav-tab:hover{ background:#121a30; }
        #stores .nav-tab.active{ background:#1c305c; color:#fff; box-shadow:0 0 10px rgba(28,48,92,.35) }

        /* Badge "New" animé */
        .badge-new{
            padding:2px 8px; font-size:.75rem; border-radius:999px; font-weight:800; letter-spacing:.4px;
            color:#fff; text-transform:uppercase;
            background:linear-gradient(90deg,#e11d48,#f59e0b,#e11d48); background-size:200% 100%;
            animation:shimmer 2s linear infinite, floatY 2.6s ease-in-out infinite;
            box-shadow:0 0 0 1px #ffffff40 inset, 0 0 10px #e11d4880;
        }
        @keyframes shimmer { to{ background-position:200% 0; } }
        @keyframes floatY { 0%,100%{ transform:translateY(0) } 50%{ transform:translateY(-1.5px) } }

        #stores .content-area{
            background:linear-gradient(180deg,#0f1525,#0d1321); border:1px solid #1d2742; border-radius:20px;
            padding:1.6rem; backdrop-filter:blur(15px); min-height:500px;
            display:grid; grid-template-columns:1fr 1fr; gap:1.6rem; align-items:stretch;
        }
        #stores .map-section{ border-radius:18px; overflow:hidden; position:relative; min-height:420px }
        #stores .map-container{ width:100%; height:100%; min-height:420px }
        #stores #map{ width:100%; height:100%; border-radius:18px }

        #stores .info-section{ display:flex; flex-direction:column; gap:1rem }
        #stores .store-image{ width:100%; height:200px; border-radius:15px; object-fit:cover; border:2px solid #233055 }
        #stores .store-title{ font-size:1.6rem; font-weight:800; margin:.2rem 0; background:linear-gradient(45deg,#8b1a1a,#1c305c); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
        #stores .info-item{ display:flex; align-items:center; gap:.8rem; padding:.7rem .8rem; background:#0e1528; border:1px solid #233055; border-radius:12px; }
        #stores .icon{ width:20px; height:20px; fill:#A32929 }

        #stores .actions{ display:flex; gap:1rem; margin-top:auto }
        #stores .btn{ flex:1; justify-content:center; border-radius:25px }
        #stores .btn-primary{ background:linear-gradient(45deg,#A32929,#8B1A1A); color:#fff }
        #stores .btn-secondary{ background:#1c305c; border:1px solid #233055; color:#fff }

        /* Leaflet popups - clair */
        .leaflet-popup-content-wrapper{ background:#ffffff; color:#0b0f1a; border-radius:10px; box-shadow:0 12px 26px rgba(0,0,0,.25); }
        .leaflet-popup-tip{ background:#ffffff; }
        .leaflet-popup-content{ color:#0b0f1a; font-weight:600 }

        @media (max-width:1024px){
            #stores .nav-tabs{ justify-content:flex-start; overflow-x:auto; padding-bottom:.5rem }
        }
        @media (max-width:768px){
            #stores .content-area{ grid-template-columns:1fr; gap:1rem; padding:1.25rem }
            #stores .map-section, #stores .map-container{ min-height:320px }
            .strip-section{ padding:36px 0 8px; }
        }

        /* ===== CONTACT ===== */
        #contact { padding: 72px 0; }
        #contact .section-hd { flex-direction: column; align-items: center; gap: 6px; text-align: center; }
        .contact-grid{ display:grid; grid-template-columns:1fr 1fr; gap:28px; align-items:stretch; }
        .contact-panel{
            background: linear-gradient(180deg,#121826,#0e1422);
            border: 1px solid #1e2740; border-radius: 22px;
            box-shadow: 0 18px 50px rgba(0,0,0,.35), inset 0 1px 0 #ffffff10;
            padding: 28px 24px; display:flex; flex-direction:column; gap:22px;
        }
        .contact-title{
            margin:0; text-align:center; font-size: clamp(20px, 2.2vw, 24px); font-weight: 800;
            text-decoration: underline; text-underline-offset: 6px; text-decoration-thickness: 3px;
        }
        .form-row{ display:flex; flex-direction:column; gap:12px; }
        .form-control{
            width:100%; padding:16px 18px; border-radius:14px; color:#fff;
            background:linear-gradient(145deg,#0f152b,#0c1223); border:1px solid #1e2740; outline:none;
            font: 600 16px/1.2 "Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;
        }
        .form-control::placeholder{ color:#a9b6d3; font-weight:600; }
        .form-control:focus{ outline:2px solid var(--ring); }
        .form-textarea{ min-height:140px; resize:vertical; }
        .btn-send{
            appearance:none; cursor:pointer; border:0; width:100%; padding:16px 18px; border-radius:14px;
            font-weight:800; font-size:16px; letter-spacing:.1px; color:#fff;
            background: linear-gradient(145deg, #d26043, #8b1f22);
            box-shadow: 0 12px 32px rgba(139,31,34,.35);
            transition: transform .08s ease, box-shadow .2s ease, filter .2s ease;
        }
        .btn-send:hover{ filter:brightness(1.05); box-shadow:0 18px 40px rgba(139,31,34,.45); }
        .btn-send:active{ transform: translateY(1px); }

        /* === Alignement parfait des infos (icône / libellé / valeur) === */
        .info-table{
            display:grid;
            grid-template-columns: 24px 130px 1fr; /* valeur centrale fixe => toutes les valeurs alignées */
            row-gap:14px; column-gap:12px; align-items:center;
            color:#e7ecf5; font-weight:700;
            margin-top:-6px;
        }
        .info-ico{ width:24px; height:24px; display:block; color:#cdd9ff; opacity:.95 }
        .info-label{ font-weight:800; line-height:1.1 }
        .info-value{ color:#c4d0ea; font-weight:600; line-height:1.2 }

        .newsletter{ display:flex; flex-direction:column; align-items:center; gap:10px; margin-top:6px; }
        .news-wrap{ display:flex; width:100%; max-width:520px; gap:10px; }
        .news-input{
            flex:1; padding:14px 16px; border-radius:12px; border:1px solid #1e2740;
            background:linear-gradient(145deg,#0f152b,#0c1223); color:#fff; font-weight:600;
        }
        .news-input::placeholder{ color:#a9b6d3; }
        .news-btn{
            display:grid; place-items:center; width:56px; border-radius:12px; border:1px solid #213055;
            background: linear-gradient(145deg, #122043, #0e1731); color:#cfe0ff; cursor:pointer;
            transition: transform .08s ease, background .2s ease, border-color .2s ease;
        }
        .news-btn:hover{ background:#0f1b3b; border-color:#2a3d73; }
        .news-btn:active{ transform: translateY(1px); }
        @media (max-width:980px){ .contact-grid{ grid-template-columns:1fr; } }

        /* Footer */
        footer{border-top:1px solid #141a2b; background:transparent;}
        .foot{display:grid; grid-template-columns:1fr auto; gap:16px; padding:24px 0; color:#b7c2d9}

        /* Gradient animé "à deux pas" */
        .gradient-text{
            background-image: linear-gradient(90deg, var(--blue), #b6152f, var(--red), #b6152f, var(--blue));
            background-size:300% 100%; background-position:0% 50%;
            -webkit-background-clip:text; background-clip:text; color:transparent;
        }

        /* Canvas fond */
        #bg-anim{position:fixed; inset:0; z-index:-1; pointer-events:none; background:var(--black);}

        /* === Extra animations (global) === */
        .progress{position:fixed;top:0;left:0;height:3px;width:0;
            background:linear-gradient(90deg,#3aa0ff,#e11d48);z-index:1000;box-shadow:0 0 12px #3aa0ff88}
        .cursor-dot,.cursor-ring{position:fixed;pointer-events:none;z-index:10000;left:0;top:0;transform:translate(-50%,-50%)}
        .cursor-dot{width:6px;height:6px;border-radius:50%;background:#fff}
        .cursor-ring{width:36px;height:36px;border-radius:50%;border:1px solid #ffffff55;mix-blend-mode:exclusion;transition:width .15s ease,height .15s ease}
        .click-ripple{position:fixed;left:0;top:0;width:12px;height:12px;border-radius:999px;background:#ffffff40;pointer-events:none;z-index:9999;transform:translate(-50%,-50%)}

        #stores .nav-tabs{position:relative}
        .tabs-underline{position:absolute;bottom:-6px;height:3px;border-radius:3px;
            background:linear-gradient(90deg,#e11d48,#f59e0b);transition:transform .25s ease,width .25s ease}

        /* Leaflet marker gentle bounce */
        .leaflet-marker-icon div{animation:bounce 1.6s ease-in-out infinite;transform-origin:center bottom}
        @keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}

        /* Petite entrée verticale des lettres (utilisée pour titrage si besoin)
        .h2-animate .char{display:inline-block;transform:translateY(20px);opacity:0}
        @keyframes rise{to{transform:none;opacity:1}} */

    </style>
</head>
<body>
<!-- Progress bar + custom cursor -->
<div class="progress" id="progress"></div>
<div class="cursor-dot" id="cDot"></div>
<div class="cursor-ring" id="cRing"></div>


<!-- Canvas du fond animé -->
<canvas id="bg-anim" aria-hidden="true"></canvas>

<!-- Bandeau -->
<div class="marquee" aria-hidden="true">
    <div class="marquee__inner">
        <span class="pill"><span class="dot"></span> Promotions fraîches chaque semaine</span>
        <span class="pill"><span class="dot"></span> Qualité halal • Boucherie & primeur</span>
        <span class="pill"><span class="dot"></span> Épicerie du monde • Turquie, Maghreb & +</span>
        <span class="pill"><span class="dot"></span> Livraison & Drive selon magasins</span>
        <span class="pill"><span class="dot"></span> Promotions fraîches chaque semaine</span>
        <span class="pill"><span class="dot"></span> Qualité halal • Boucherie & primeur</span>
        <span class="pill"><span class="dot"></span> Épicerie du monde • Turquie, Maghreb & +</span>
        <span class="pill"><span class="dot"></span> Livraison & Drive selon magasins</span>
    </div>
</div>

<header>
    <div class="container nav">
        <div class="brand">
            <div class="brand-badge"><span>PI</span></div>
            <div>
                <div style="font-weight:800; line-height:1">Paristanbul</div>
                <div style="font-size:.78rem; color:#9fb0c7; line-height:1">Supermarché</div>
            </div>
        </div>
        <nav class="nav-links">
            <a href="quiSommesNous.html" class="btn magnet">Notre Histoire</a>
            <a href="postuler.php" class="btn magnet">Postuler</a>
            <a href="#contact" class="btn primary magnet">Nous contacter</a>
        </nav>
    </div>
</header>

<main>
    <!-- HERO -->
    <section id="hero" class="container">
        <div class="hero-wrap">
            <div class="reveal">
                <div class="eyebrow">Bienvenue chez Paristanbul</div>
                <h1>Vos saveurs favorites, <span id="aDeuxPas" class="gradient-text">à deux pas</span> de chez vous.</h1>
                <p class="lead">Boucherie halal, primeur, épicerie, produits turcs et du monde. Découvrez notre nouveau catalogue interactif et trouvez le magasin le plus proche.</p>
                <div class="cta-row">
                    <a href="#catalog" class="btn primary magnet">Voir le catalogue</a>
                    <a href="#stores" class="btn magnet">Voir nos magasins</a>
                </div>
            </div>

            <div class="reveal" data-parallax data-speed="0.06">
                <div class="video-card tilt">
                    <video id="promoVideo" preload="metadata" playsinline muted poster="https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1200&auto=format&fit=crop">
                        <source src="assets/promo.mp4" type="video/mp4" />
                        Votre navigateur ne supporte pas la vidéo HTML5.
                    </video>
                    <div class="video-overlay">
                        <button id="playBtn" class="play-hint" aria-label="Lire la vidéo">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            Lire la vidéo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CATALOGUE -->
    <section id="catalog">
        <div class="container">
            <div class="section-hd reveal">
                <h2>Catalogue interactif</h2>
                <div class="sub">Couverture seule centrée → puis double-page</div>
            </div>

            <div class="catalog-app">
                <div class="toolbar reveal">
                    <div class="row">
                        <div class="brand"><span class="dot"></span> Catalogue</div>

                        <button id="prevBtn" class="btn icon magnet" title="Page précédente" aria-label="Page précédente">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button id="nextBtn" class="btn icon magnet" title="Page suivante" aria-label="Page suivante">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
                        </button>

                        <span class="sep" aria-hidden="true"></span>

                        <button id="zoomOut" class="btn icon magnet" title="Zoom -" aria-label="Zoom -">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/></svg>
                        </button>
                        <button id="zoomIn" class="btn icon magnet" title="Zoom +" aria-label="Zoom +">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <button id="fitBtn" class="btn magnet" title="Ajuster" aria-label="Ajuster">Ajuster</button>

                        <div class="metric"><small>Page</small> <span id="pageLabel">1 / 5</span></div>
                    </div>
                </div>

                <div class="stage reveal" id="stageBox">
                    <div id="stageInner" class="stage-inner">
                        <div id="flipbook" aria-label="Catalogue interactif"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AVANTAGES -->
    <section id="advantages">
        <div class="container">
            <div class="section-hd reveal">
                <h2>Nos avantages</h2>
                <div class="sub">Défilement infini — mini-zoom au survol — clics inactifs</div>
            </div>

            <div class="carousel reveal">
                <div class="track-viewport">
                    <div class="track" id="adv-track">
                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://picsum.photos/seed/fruits/1280/720" alt=""></div>
                            <div class="meta"><span class="dot"></span><span>Fraîcheur</span></div>
                            <h3>Fruits & légumes croquants</h3>
                            <p>Des arrivages quotidiens directement des producteurs pour une qualité au top.</p>
                            <div class="tags"><span class="tag">Local</span><span class="tag">Saisonnier</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://picsum.photos/seed/butcher/1280/720" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#b9143f"></span><span>Qualité</span></div>
                            <h3>Boucherie sélection</h3>
                            <p>Contrôles stricts, origine vérifiée, découpe fraîche chaque jour.</p>
                            <div class="tags"><span class="tag">Label</span><span class="tag">Traçable</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://picsum.photos/seed/grocery/1280/720" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#19c37d"></span><span>Prix</span></div>
                            <h3>Indispensables à petit prix</h3>
                            <p>Des tarifs justes et stables toute l’année sur les essentiels du quotidien.</p>
                            <div class="tags"><span class="tag">Promo</span><span class="tag">Budget</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://picsum.photos/seed/delivery/1280/720" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#f5a524"></span><span>Service</span></div>
                            <h3>Près de vous</h3>
                            <p>Retrait rapide, créneaux souples, équipe réactive pour vous servir.</p>
                            <div class="tags"><span class="tag">Rapide</span><span class="tag">Local</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://picsum.photos/seed/traiteur/1280/720" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#b07cff"></span><span>Gourmand</span></div>
                            <h3>Traiteur maison</h3>
                            <p>Recettes authentiques, ingrédients frais, prêt à déguster.</p>
                            <div class="tags"><span class="tag">Fait-maison</span><span class="tag">Fraîcheur</span></div>
                        </article>
                    </div>

                    <!-- Flèches centrées et statiques -->
                    <div class="adv-nav">
                        <button class="prev" id="adv-prev" aria-label="Précédent">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M15 5 8 12l7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <button class="next" id="adv-next" aria-label="Suivant">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STRIP défilant (au-dessus des magasins) -->
    <section class="strip-section">
        <div class="container">
            <div class="strip" aria-label="Galerie défilante">
                <div class="marquee-strip">
                    <div class="track-strip" id="trackStrip">
                        <!-- Série A -->
                        <article class="card-strip"><span class="inner"><img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=1600&auto=format&fit=crop" alt="Rayon fruits"/></span></article>
                        <article class="card-strip"><span class="inner"><!-- vide --></span></article>
                        <article class="card-strip"><span class="inner"><img src="https://images.unsplash.com/photo-1515165562835-c3b8c2e1f9af?q=80&w=1600&auto=format&fit=crop" alt="Clients"/></span></article>
                        <article class="card-strip"><span class="inner"><!-- vide --></span></article>
                        <article class="card-strip"><span class="inner"><img src="https://images.unsplash.com/photo-1506806732259-39c2d0268443?q=80&w=1600&auto=format&fit=crop" alt="Légumes"/></span></article>
                        <article class="card-strip"><span class="inner"><!-- vide --></span></article>
                        <!-- Série B (dupliquée) -->
                        <article class="card-strip"><span class="inner"><img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=1600&auto=format&fit=crop" alt="Rayon fruits"/></span></article>
                        <article class="card-strip"><span class="inner"><!-- vide --></span></article>
                        <article class="card-strip"><span class="inner"><img src="https://images.unsplash.com/photo-1515165562835-c3b8c2e1f9af?q=80&w=1600&auto=format&fit=crop" alt="Clients"/></span></article>
                        <article class="card-strip"><span class="inner"><!-- vide --></span></article>
                        <article class="card-strip"><span class="inner"><img src="https://images.unsplash.com/photo-1506806732259-39c2d0268443?q=80&w=1600&auto=format&fit=crop" alt="Légumes"/></span></article>
                        <article class="card-strip"><span class="inner"><!-- vide --></span></article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAGASINS (onglets + carte) -->
    <section id="stores">
        <div class="container">
            <div class="section-hd reveal">
                <h2>Nos magasins</h2>
                <div class="sub">Choisissez une ville pour voir la carte, l'adresse et les horaires</div>
            </div>

            <div class="nav-tabs">
                <button class="nav-tab active" data-store="villiers1">Villiers-le-Bel</button>
                <button class="nav-tab" data-store="villiers2">Villiers-le-Bel 2</button>
                <button class="nav-tab" data-store="drancy">Drancy</button>
                <button class="nav-tab" data-store="bondy">Bondy</button>
                <button class="nav-tab" data-store="villemomble">Villemomble</button>
                <button class="nav-tab" data-store="nogent">Nogent-sur-Oise</button>
                <button class="nav-tab" data-store="vertsaintdenis">Vert-Saint-Denis <span class="badge-new">New</span></button>
            </div>

            <div class="content-area" id="contentArea">
                <div class="sub" style="grid-column:1/-1; text-align:center;">Chargement…</div>
            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact">
        <div class="container">
            <div class="section-hd">
                <h2>Contactez-nous</h2>
                <div class="sub">Une question, une suggestion ?</div>
            </div>

            <div class="contact-grid">
                <!-- Formulaire -->
                <div class="contact-panel">
                    <h3 class="contact-title">Envoyez-nous un message</h3>

                    <form id="contactForm" class="form-row"
                          action="https://formsubmit.co/parisistambulnogent@gmail.com"
                          method="post" accept-charset="UTF-8">
                        <input class="form-control" type="text" name="name" placeholder="Nom complet" required>
                        <input class="form-control" type="email" name="email" placeholder="Email" required>
                        <select class="form-control" name="sujet" required>
                            <option value="">Sélectionnez un sujet</option>
                            <option>Informations générales</option>
                            <option>Commande</option>
                            <option>Problème technique</option>
                        </select>
                        <textarea class="form-control form-textarea" name="message" placeholder="Votre message..." required></textarea>

                        <input type="hidden" name="_next" value="">
                        <input type="hidden" name="_subject" value="Nouveau message — Site Paristanbul">
                        <input type="hidden" name="_template" value="table">
                        <input type="hidden" name="_captcha" value="false">
                        <input type="text" name="_honey" style="display:none">

                        <button class="btn-send" type="submit">Envoyer le message</button>
                    </form>
                </div>

                <!-- Infos + newsletter -->
                <div class="contact-panel">
                    <h3 class="contact-title">Service client</h3>

                    <!-- Grille d'alignement parfait -->
                    <div class="info-table">
                        <!-- TÉLÉPHONE -->
                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.3 1.77.55 2.61a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.47-1.08a2 2 0 0 1 2.11-.45c.84.25 1.71.43 2.61.55A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <div class="info-label">Téléphone</div>
                        <div class="info-value">07 49 82 61 33 (appel gratuit)</div>

                        <!-- EMAIL -->
                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/>
                        </svg>
                        <div class="info-label">Email</div>
                        <div class="info-value">parisistambulnogent@gmail.com</div>

                        <!-- HORAIRES -->
                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                        <div class="info-label">Horaires</div>
                        <div class="info-value">Lun–Ven : 9h00–18h00</div>
                    </div>

                    <div class="newsletter">
                        <h3 class="contact-title" style="text-decoration-thickness:2px">Newsletter</h3>
                        <div class="sub" style="text-align:center">Recevez nos promos & actus.</div>
                        <form id="newsletterForm" class="news-wrap" action="#" method="post">
                            <input class="news-input" type="email" name="newsletter" placeholder="Votre email" required>
                            <button class="news-btn" type="submit" aria-label="S’inscrire">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container foot">
        <div>
            <div style="font-weight:800">Paristanbul</div>
            <div>Service client : <a href="mailto:contact@paristanbul.com">contact@paristanbul.com</a></div>
            <div style="font-size:.92rem; color:#9fb0c7">Suivez-nous : Instagram • Facebook • TikTok</div>
        </div>
        <div>© <span id="year"></span> Paristanbul – Tous droits réservés</div>
    </div>
</footer>

<!-- JS externes -->
<script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin></script>
<script defer src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

<script>
    /* Helpers */
    const $ = (s,el=document)=>el.querySelector(s);
    const $$ = (s,el=document)=>[...el.querySelectorAll(s)];

    /* Vidéo */
    const video=$('#promoVideo'), playBtn=$('#playBtn');
    if(playBtn&&video){ playBtn.addEventListener('click',()=>{ playBtn.style.display='none'; video.muted=false; const p=video.play(); if(p&&p.catch)p.catch(()=>{}); }); }

    /* Reveal on scroll */
    const io=new IntersectionObserver((ents)=>{ ents.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('is-visible'); io.unobserve(e.target);} }); },{threshold:.15});
    $$('.reveal').forEach(n=>io.observe(n));

    /* Parallax léger */
    const parallaxNodes=$$('[data-parallax]');
    const onScrollParallax=()=>{ const y=window.scrollY||document.documentElement.scrollTop; parallaxNodes.forEach(n=>{ const sp=parseFloat(n.dataset.speed||'0.05'); n.style.transform=`translateY(${y*sp}px)`; });};
    onScrollParallax(); addEventListener('scroll', onScrollParallax, {passive:true});

    /* ===== Catalogue : couverture seule centrée ===== */
    (function(){
        const TOTAL_PAGES = 5;
        const PATH = '/Projet-paristanbul/assets/pages'; // <-- adapte si besoin
        const FILENAME = i => String(i).padStart(2,'0') + '.jpg';
        const MOBILE_BREAKPOINT = 768;
        const MIN_W = 480, MAX_W = 1040;

        const pages = Array.from({length: TOTAL_PAGES}, (_,k) => `${PATH}/${FILENAME(k+1)}`);
        pages.forEach(src => { const i = new Image(); i.src = src; });

        let pageFlip, pageAspect = 0.707, pageW = 600, scale = 1, baseScale = 1;

        const stageInner= document.getElementById('stageInner');
        const flipEl    = document.getElementById('flipbook');
        const pageLabel = document.getElementById('pageLabel');

        function applyScale(){ stageInner.style.transform = `scale(${scale})`; }
        function updateMetric(){ if(pageFlip){ const i = pageFlip.getCurrentPageIndex(); pageLabel.textContent = `${i+1} / ${TOTAL_PAGES}`; } }

        async function detectAspect(){
            return new Promise(resolve=>{
                const probe = new Image();
                probe.onload = () => { if (probe.naturalWidth && probe.naturalHeight) pageAspect = probe.naturalWidth / probe.naturalHeight; resolve(); };
                probe.onerror = () => resolve();
                probe.src = pages[0];
            });
        }

        function computeSize(){
            const usePortrait = window.innerWidth < MOBILE_BREAKPOINT;
            const height = Math.floor(window.innerHeight * 0.88);
            let width = Math.round(height * pageAspect);
            width = Math.min(MAX_W, Math.max(MIN_W, width));
            return { width, height, usePortrait };
        }

        function coverMaskAndCenter(){
            const idx = pageFlip.getCurrentPageIndex();
            const isDouble = !pageFlip.getSettings().usePortrait;

            flipEl.style.clipPath = 'none';
            flipEl.style.webkitClipPath = 'none';
            stageInner.style.transform = `scale(${scale})`;

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
                width, height, size:'fixed',
                showCover:true,
                usePortrait,
                autoSize:true, maxShadowOpacity:0.5, mobileScrollSupport:true,
                startPage:startIndex
            });

            pageFlip.loadFromImages(pages);
            pageFlip.on('flip', coverMaskAndCenter);

            scale = baseScale;
            coverMaskAndCenter();
        }

        $('#nextBtn').onclick = ()=> pageFlip?.flipNext();
        $('#prevBtn').onclick = ()=> pageFlip?.flipPrev();
        $('#zoomIn').onclick  = ()=>{ scale = Math.min(2.0, scale + 0.1); coverMaskAndCenter(); };
        $('#zoomOut').onclick = ()=>{ scale = Math.max(0.6, scale - 0.1); coverMaskAndCenter(); };
        $('#fitBtn').onclick  = ()=>{ scale = baseScale; coverMaskAndCenter(); };

        let rt;
        window.addEventListener('resize', ()=>{ clearTimeout(rt); rt = setTimeout(()=>{ const current = pageFlip ? pageFlip.getCurrentPageIndex() : 0; initFlip(current); }, 150); });

        if(document.readyState!=='loading') initFlip(0); else window.addEventListener('load', ()=> initFlip(0));
    })();

    /* ===== Nos avantages : loop + flèches ===== */
    (function(){
        const root=document.getElementById('advantages'); if(!root) return;
        const vp=root.querySelector('.track-viewport');
        const track=document.getElementById('adv-track');
        const prevBtn=document.getElementById('adv-prev');
        const nextBtn=document.getElementById('adv-next');
        const GAP=16;

        let originals=[...track.children], index=0, startIndex=0, autoplay=null;

        function cardWidth(){ return originals[0].getBoundingClientRect().width; }
        function visibleCount(){ const w=vp.getBoundingClientRect().width; return Math.max(1, Math.floor((w+GAP)/(cardWidth()+GAP))); }
        function clearClones(){ [...track.children].forEach(n=>{ if(n.dataset.clone) n.remove();});}
        function cloneNode(n){ const c=n.cloneNode(true); wireTilt(c); return c; }

        function setupClones(){
            clearClones();
            const V=visibleCount();
            const head=originals.slice(-V).map(cloneNode); head.forEach(n=>{ n.dataset.clone='head'; track.insertBefore(n,track.firstChild);});
            const tail=originals.slice(0,V).map(cloneNode); tail.forEach(n=>{ n.dataset.clone='tail'; track.appendChild(n);});
            startIndex=V; index=startIndex; instantTranslate();
        }
        function translate(){ const x=-(index*(cardWidth()+GAP)); track.style.transform=`translateX(${x}px)`; }
        function instantTranslate(){ const t=track.style.transition; track.style.transition='none'; translate(); track.offsetHeight; track.style.transition=t||''; }
        function next(){ index++; translate(); ripple(nextBtn); }
        function prev(){ index--; translate(); ripple(prevBtn); }

        track.addEventListener('transitionend',()=>{
            const V=startIndex, total=originals.length, tailStart=V+total;
            if(index>=tailStart){ index-=total; instantTranslate(); }
            else if(index<V){ index+=total; instantTranslate(); }
        });

        prevBtn.addEventListener('click', (e)=>{ e.stopPropagation(); prev(); });
        nextBtn.addEventListener('click', (e)=>{ e.stopPropagation(); next(); });

        // Drag / Swipe
        let dragging=false, startX=0, base=0;
        function onDown(e){ dragging=true; startX=(e.touches?e.touches[0].clientX:e.clientX); const m=track.style.transform.match(/-?\d+(\.\d+)?/); base=m?parseFloat(m[0]):-(index*(cardWidth()+GAP)); track.style.transition='none'; }
        function onMove(e){ if(!dragging) return; const cur=(e.touches?e.touches[0].clientX:e.clientX); const dx=cur-startX; track.style.transform=`translateX(${base+dx}px)`; }
        function onUp(){ if(!dragging) return; dragging=false; track.style.transition=''; const m=track.style.transform.match(/-?\d+(\.\d+)?/); const pos=m?parseFloat(m[0]):0; const w=cardWidth()+GAP; index=Math.round(-pos/w); translate(); }
        vp.addEventListener('mousedown', onDown); window.addEventListener('mousemove', onMove); window.addEventListener('mouseup', onUp);
        vp.addEventListener('touchstart', onDown, {passive:true}); vp.addEventListener('touchmove', onMove, {passive:true}); vp.addEventListener('touchend', onUp);

        // Autoplay
        function startAuto(){ stopAuto(); autoplay=setInterval(()=>next(), 3500); }
        function stopAuto(){ if(autoplay) { clearInterval(autoplay); autoplay=null; } }
        root.addEventListener('mouseenter', stopAuto); root.addEventListener('mouseleave', startAuto);

        // Ripple anim
        function ripple(btn){
            const r=document.createElement('span');
            r.style.cssText=`position:absolute; left:50%; top:50%; width:10px;height:10px;border-radius:999px;background:#cfe0ff33; transform:translate(-50%,-50%); pointer-events:none;`;
            btn.appendChild(r);
            requestAnimationFrame(()=>{ r.style.transition='transform .4s ease, opacity .4s ease'; r.style.transform='translate(-50%,-50%) scale(8)'; r.style.opacity='0'; });
            setTimeout(()=>r.remove(), 450);
        }

        // Tilt 3D
        function wireTilt(el){ if(!el.classList.contains('tilt')) return;
            const r=10; let raf=null;
            const move=(x,y,w,h)=>{ const rx=((y-h/2)/(h/2))*-r, ry=((x-w/2)/(w/2))*r; el.style.transform=`rotateX(${rx}deg) rotateY(${ry}deg)`; };
            el.addEventListener('mousemove',(e)=>{ const rc=el.getBoundingClientRect(); const x=e.clientX-rc.left, y=e.clientY-rc.top; if(raf) cancelAnimationFrame(raf); raf=requestAnimationFrame(()=>move(x,y,rc.width,rc.height)); });
            el.addEventListener('mouseleave',()=>{ el.style.transform='rotateX(0) rotateY(0)'; });
        }
        $$('.tilt', track).forEach(wireTilt);

        function init(){ originals=[...track.children].filter(el=>!el.dataset.clone); setupClones(); translate(); startAuto(); }
        init();
        window.addEventListener('resize', ()=>{ originals=[...track.querySelectorAll('.card')].filter(el=>!el.dataset.clone); setupClones(); });
    })();

    /* ===== Strip : pause onglet masqué ===== */
    (function(){ const track = document.getElementById('trackStrip'); document.addEventListener('visibilitychange',()=>{ track.style.animationPlayState = document.hidden ? 'paused' : 'running'; });})();

    /* ===== STORES : tabs + Leaflet ===== */
    const storesData = {
        villiers1: { title:'Paristanbul VILLIERS-LE-BEL', image:'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=200&fit=crop&crop=center', address:'3 avenue des entrepreneurs, VILLIERS-LE-BEL', hours:'Lundi à Dimanche : 08:30-20:00', phone:'01 39 94 12 34', coordinates:[49.0010, 2.3894] },
        villiers2: { title:'Paristanbul VILLIERS-LE-BEL 2', image:'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=200&fit=crop&crop=center', address:'117 Avenue Pierre Semard, VILLIERS-LE-BEL', hours:'Lundi à Dimanche : 08:30-20:00', phone:'01 39 95 12 34', coordinates:[49.0015, 2.3900] },
        drancy:    { title:'Paristanbul DRANCY', image:'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&h=200&fit=crop&crop=center', address:'83 avenue Marceau, DRANCY', hours:'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00', phone:'01 48 95 12 34', coordinates:[48.9242, 2.4456] },
        bondy:     { title:'Paristanbul BONDY', image:'https://images.unsplash.com/photo-1574719602651-bce1b0cb84a3?w=400&h=200&fit=crop&crop=center', address:'116 Av. Gallieni, BONDY', hours:'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00', phone:'01 48 47 12 34', coordinates:[48.9024, 2.4823] },
        villemomble:{ title:'Paristanbul VILLEMOMBLE', image:'https://images.unsplash.com/photo-1534723328310-e82dad3ee43f?w=400&h=200&fit=crop&crop=center', address:'68 ALLEE DU PLATEAU, VILLEMOMBLE', hours:'Lundi à Dimanche : 08:00-20:30', phone:'01 45 28 12 34', coordinates:[48.8844, 2.5103] },
        nogent:    { title:'Paristanbul NOGENT-SUR-OISE', image:'https://images.unsplash.com/photo-1604719312566-9d6eed8dd866?w=400&h=200&fit=crop&crop=center', address:'171 Rue Jean Monnet, NOGENT-SUR-OISE', hours:'Lundi à Samedi : 09:30-20:00, Dimanche : 10:00-19:00', phone:'03 44 74 12 34', coordinates:[49.2765, 2.2011] },
        vertsaintdenis:{ title:'Paristanbul VERT-SAINT-DENIS', image:'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=200&fit=crop&crop=center', address:'La Fontaine Ronde, VERT-SAINT-DENIS', hours:'Lundi à Dimanche : 08:30-20:30', phone:'01 64 10 12 34', coordinates:[48.6478, 2.6223] }
    };
    let currentMap = null;

    function createStoreContent(storeKey){
        const s = storesData[storeKey];
        return `
    <div class="map-section"><div class="map-container"><div id="map"></div></div></div>
    <div class="info-section">
      <img src="${s.image}" alt="${s.title}" class="store-image">
      <div class="store-info">
        <h2 class="store-title">${s.title}</h2>
        <div class="info-item">
          <svg class="icon" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 5.5 12 5.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
          <span>${s.address}</span>
        </div>
        <div class="info-item">
          <svg class="icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
          <span>${s.hours}</span>
        </div>
        <div class="info-item">
          <svg class="icon" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
          <span>${s.phone}</span>
        </div>
      </div>
      <div class="actions">
        <a href="#" class="btn btn-primary" onclick="openDirections('${s.address}')" rel="noopener">Itinéraire</a>
        <a href="tel:${s.phone.replace(/\s/g,'')}" class="btn btn-secondary">Appeler</a>
      </div>
    </div>`;
    }

    function initMap(lat, lng, title, address){
        if(currentMap){ currentMap.remove(); }
        // ---- CARTE CLAIRE (fond blanc) ----
        currentMap = L.map('map',{ zoomControl:true, scrollWheelZoom:true }).setView([lat,lng],15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
            { attribution:'© OpenStreetMap • © CARTO', subdomains:'abcd', maxZoom:19 }).addTo(currentMap);

        const customIcon = L.divIcon({
            html:'<div style="background:#A32929;width:20px;height:20px;border-radius:50%;border:3px solid white;box-shadow:0 2px 10px rgba(0,0,0,.3);"></div>',
            iconSize:[26,26], iconAnchor:[13,13]
        });
        L.marker([lat,lng],{icon:customIcon}).addTo(currentMap)
            .bindPopup(`<strong>${title}</strong><br>${address}`).openPopup();
        setTimeout(()=>currentMap.invalidateSize(), 100);
    }

    function openDirections(address){
        const encoded = encodeURIComponent(address);
        window.open(`https://www.google.com/maps/dir/?api=1&destination=${encoded}`, '_blank');
    }

    function selectStore(key){
        $$('#stores .nav-tab').forEach(t=>t.classList.remove('active'));
        $(`#stores .nav-tab[data-store="${key}"]`).classList.add('active');
        const area = document.getElementById('contentArea');
        area.innerHTML = createStoreContent(key);
        const s = storesData[key];
        setTimeout(()=> initMap(s.coordinates[0], s.coordinates[1], s.title, s.address), 100);
    }
    document.addEventListener('click', (e)=>{
        const btn = e.target.closest('#stores .nav-tab');
        if(!btn) return;
        selectStore(btn.getAttribute('data-store'));
    });
    document.addEventListener('DOMContentLoaded', ()=> setTimeout(()=> selectStore('villiers1'), 120));

    /* Footer year */
    document.getElementById('year').textContent = new Date().getFullYear();

    /* Gradient animé “à deux pas” */
    (function(){
        const el=document.getElementById('aDeuxPas'); if(!el) return;
        let pos=0, raf=null, last=0; const speed=30, span=300;
        function tick(ts){ if(!last) last=ts; const dt=(ts-last)/1000; last=ts; pos=(pos+dt*speed)%span; el.style.backgroundPosition=`${pos}% 50%`; raf=requestAnimationFrame(tick); }
        const ob=new IntersectionObserver(es=>{ es.forEach(e=>{ if(e.isIntersecting){ if(!raf) raf=requestAnimationFrame(tick);} else { if(raf) cancelAnimationFrame(raf); raf=null; last=0; } });},{threshold:.1});
        ob.observe(el);
    })();

    /* Fond animé canvas */
    (function(){
        const canvas=document.getElementById('bg-anim'); if(!canvas) return;
        const ctx=canvas.getContext('2d',{alpha:true});
        let W=0,H=0,DPR=Math.min(devicePixelRatio||1,1.5); const blobs=[]; const BLUE='rgba(11,59,138,', RED='rgba(123,15,32,';
        function resize(){ W=Math.floor(innerWidth*DPR); H=Math.floor(innerHeight*DPR); canvas.width=W; canvas.height=H; canvas.style.width=innerWidth+'px'; canvas.style.height=innerHeight+'px'; }
        function makeBlob(color,baseR){ return { x:Math.random()*W,y:Math.random()*H, vx:(Math.random()*0.2+0.05)*(Math.random()<.5?-1:1), vy:(Math.random()*0.2+0.05)*(Math.random()<.5?-1:1), r:baseR*(0.8+Math.random()*0.4), r2:baseR*(1.2+Math.random()*0.6), phase:Math.random()*Math.PI*2, omega:0.4+Math.random()*0.5, color }; }
        function setup(){ resize(); blobs.length=0; const base=Math.min(W,H)*0.20; blobs.push(makeBlob(BLUE,base), makeBlob(BLUE,base*.85), makeBlob(RED,base), makeBlob(RED,base*.9), makeBlob(BLUE,base*.7), makeBlob(RED,base*.7)); }
        let raf=null,last=0,paused=false;
        function step(ts){ if(paused){raf=null;return;} if(!last) last=ts; const dt=Math.min((ts-last)/16.666,3); last=ts;
            ctx.clearRect(0,0,W,H); ctx.globalCompositeOperation='lighter';
            for(const b of blobs){
                b.phase+=b.omega*0.005*dt; const pr=.5+Math.sin(b.phase)*.5; const R=b.r+(b.r2-b.r)*pr;
                b.x+=b.vx*dt; b.y+=b.vy*dt;
                if(b.x<-R) b.x=W+R; else if(b.x>W+R) b.x=-R; if(b.y<-R) b.y=H+R; else if(b.y>H+R) b.y=-R;
                const g=ctx.createRadialGradient(b.x,b.y,0,b.x,b.y,R);
                g.addColorStop(0.0,b.color+'0.28)'); g.addColorStop(0.5,b.color+'0.16)'); g.addColorStop(1.0,b.color+'0.00)');
                ctx.fillStyle=g; ctx.beginPath(); ctx.arc(b.x,b.y,R,0,Math.PI*2); ctx.fill();
            }
            ctx.globalCompositeOperation='source-over'; raf=requestAnimationFrame(step);
        }
        document.addEventListener('visibilitychange',()=>{ if(document.hidden){paused=true;} else {paused=false; last=0; if(!raf) raf=requestAnimationFrame(step);} });
        addEventListener('resize', resize);
        setup(); raf=requestAnimationFrame(step);
    })();

    /* Contact: fixer _next vers cette page + #contact */
    (function(){
        const form = document.getElementById('contactForm');
        if(form){
            const nextField = form.querySelector('input[name="_next"]');
            if(nextField){
                const url = new URL(window.location.href);
                url.searchParams.set('sent','1'); url.hash = 'contact';
                nextField.value = url.toString();
            }
        }
    })();
    /* Newsletter (démo) */
    (function(){
        const f = document.getElementById('newsletterForm');
        if(!f) return;
        f.addEventListener('submit', function(e){
            e.preventDefault();
            const email = f.newsletter.value.trim();
            if(!email) return;
            alert('Merci ! Vous serez informé(e) de nos prochaines promos.');
            f.reset();
        });
    })();
</script>
<script>
    (() => {
        const $ = (s,el=document)=>el.querySelector(s);
        const $$ = (s,el=document)=>[...el.querySelectorAll(s)];

        /* 1) Barre de progression scroll */
        const progress = $('#progress');
        const onProg = () => {
            const h = document.documentElement;
            const sc = h.scrollTop, max = h.scrollHeight - h.clientHeight;
            progress.style.width = (max ? (sc/max)*100 : 0) + '%';
        };
        onProg(); addEventListener('scroll', onProg, {passive:true});

        /* 2) Smooth scroll pour les ancres internes */
        document.addEventListener('click', (e) => {
            const a = e.target.closest('a[href^="#"]');
            if(!a) return;
            const id = a.getAttribute('href');
            const tgt = id && id !== '#' ? document.querySelector(id) : null;
            if(tgt){ e.preventDefault(); tgt.scrollIntoView({behavior:'smooth', block:'start'}); }
        });

        /* 3) Curseur personnalisé + Magnet sur .magnet */
        const cDot = $('#cDot'), cRing = $('#cRing');
        let mx=innerWidth/2, my=innerHeight/2, rx=mx, ry=my;
        addEventListener('mousemove', (e)=>{ mx=e.clientX; my=e.clientY; cDot.style.left=mx+'px'; cDot.style.top=my+'px'; });
        (function loop(){ rx += (mx-rx)*0.15; ry += (my-ry)*0.15; cRing.style.left=rx+'px'; cRing.style.top=ry+'px'; requestAnimationFrame(loop); })();

        $$('.magnet').forEach(btn=>{
            let r=null;
            btn.addEventListener('mouseenter', ()=>{ r=btn.getBoundingClientRect(); cRing.style.width='48px'; cRing.style.height='48px'; });
            btn.addEventListener('mousemove', (e)=>{
                if(!r) r=btn.getBoundingClientRect();
                const x = e.clientX - (r.left + r.width/2);
                const y = e.clientY - (r.top  + r.height/2);
                btn.style.transform = `translate(${x*0.15}px, ${y*0.20}px)`;
            });
            btn.addEventListener('mouseleave', ()=>{ btn.style.transform='translate(0,0)'; cRing.style.width='36px'; cRing.style.height='36px'; });
        });

        /* 4) Ripple au clic (global) */
        addEventListener('click', (e)=>{
            const r=document.createElement('span'); r.className='click-ripple';
            r.style.left=e.clientX+'px'; r.style.top=e.clientY+'px';
            document.body.appendChild(r);
            requestAnimationFrame(()=>{ r.style.transition='transform .5s ease, opacity .5s ease';
                r.style.transform='translate(-50%,-50%) scale(12)'; r.style.opacity='0'; });
            setTimeout(()=>r.remove(),520);
        }, {passive:true});

        /* 5) Shimmer toggle sur la marquee (haut) */
        const mqInner = $('.marquee__inner');
        if(mqInner){
            mqInner.addEventListener('mouseenter', ()=> mqInner.style.animationDuration = '12s');
            mqInner.addEventListener('mouseleave', ()=> mqInner.style.animationDuration = '22s');
        }

        /* 6) Scramble sur chaque titre de section quand visible */
        const letters='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        const scrambleIn = (el)=>{
            const orig = el.dataset.orig || el.textContent;
            el.dataset.orig = orig;
            let p = 0;
            const timer = setInterval(()=>{
                p++;
                el.textContent = orig.split('').map((ch,i)=> i<p ? ch : letters[Math.random()*letters.length|0]).join('');
                if(p >= orig.length){ clearInterval(timer); el.textContent = orig; }
            }, 30);
        };
        const ob2 = new IntersectionObserver(es=>{
            es.forEach(e=>{ if(e.isIntersecting){ scrambleIn(e.target); ob2.unobserve(e.target);} });
        }, {threshold:.6});
        $$('.section-hd h2').forEach(h=>ob2.observe(h));

        /* 7) “Nos magasins” : soulignement glissant sous l’onglet actif */
        const tabs = $('#stores .nav-tabs');
        if(tabs){
            const ul = document.createElement('div'); ul.className='tabs-underline'; tabs.appendChild(ul);
            const move = ()=>{
                const active = tabs.querySelector('.nav-tab.active') || tabs.querySelector('.nav-tab');
                if(!active) return;
                const a = active.getBoundingClientRect(); const t = tabs.getBoundingClientRect();
                ul.style.width = a.width + 'px';
                ul.style.transform = `translateX(${a.left - t.left}px)`;
            };
            move(); addEventListener('resize', move);
            document.addEventListener('click', (e)=>{ if(e.target.closest('#stores .nav-tab')) setTimeout(move,10); });
        }

        /* 8) Confettis quand on valide la newsletter */
        const fx = document.createElement('canvas');
        fx.id='fx'; fx.style.cssText='position:fixed;inset:0;pointer-events:none;z-index:9998'

</body>
</html>
