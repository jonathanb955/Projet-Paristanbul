<?php
session_start();
$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin />
    <!-- PageFlip -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css">

    <style>
        :root{
            /* Palette déjà utilisée sur la home */
            --black:#0a0c10; --blue:#0b3b8a; --red:#7b0f20;
            --text:#ffffff; --muted:#c9d4ea; --panel:#0f1320; --ring:#2c59ff55;
            --edge:#1b2235; --panel-2:#0e1422;
            --strip-gap: clamp(24px, 3vw, 48px);
            --strip-card: clamp(180px, 20vw, 300px);
            --strip-radius: 18px;
            --strip-border: 5px;
            --strip-speed: 22s;
            --pi-blue:#2E4C97; --pi-red:#D6452E;

            /* === Variables du FOND UNIQUE (récupérées de “Notre histoire”) === */
            --ink:#E6E9F2; --muted-2:#cfd5e6;
            --bg-1:#0B1326; --bg-2:#0A0F1F;
            --card:#141B2B; --chip:#1B2436;
            --border:rgba(255,255,255,.06);

            /* Dégradé global réutilisé */
            --page-bg:
                    radial-gradient(1000px 500px at 10% 10%, rgba(46,76,151,.25), transparent 60%),
                    radial-gradient(900px 600px at 90% 10%, rgba(214,69,46,.18), transparent 55%),
                    linear-gradient(180deg, var(--bg-1), var(--bg-2) 70%);
        }

        *{box-sizing:border-box}
        html,body{height:100%}
        /* IMPORTANT : on laisse transparaître le fond unique */
        html,body{ background:transparent !important; }

        body{
            margin:0; font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;
            color:var(--text); overflow-x:hidden; position:relative;
        }
        a{color:inherit;text-decoration:none}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}

        /* === Calque de FOND UNIQUE (comme sur “Notre histoire”) === */
        #page-bg{
            position:fixed; inset:0; z-index:-2; pointer-events:none;
            background:var(--page-bg);
        }

        /* Orbes décoratives du fond */
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

        /* Header */
        header{
            position: static;         /* <- plus de sticky */
            background: transparent !important;
            border-bottom: 1px solid #141826;
            /* z-index/top inutiles en statique */
        }


        .nav{display:grid; grid-template-columns: 1fr auto 1fr; align-items:center; gap:16px; height:66px;}
        .brand{display:flex; align-items:center; gap:12px; font-weight:800; letter-spacing:.3px}
        .brand-badge{width:34px;height:34px;border-radius:10px; background:linear-gradient(145deg,var(--blue),#0a204a); display:grid;place-items:center; box-shadow:0 8px 20px #0a1a38}
        .nav-links{justify-self:center; display:flex; gap:14px; align-items:center;}
        .auth{justify-self:end; display:flex; gap:10px;}
        .nav a.btn{padding:10px 16px; border-radius:12px; background:linear-gradient(145deg,#1a2237,#0f172a); border:1px solid #1e2740;}
        .nav a.btn:hover{ outline:2px solid var(--ring) }
        header .nav-links a.btn:not(.primary){
            position:relative; background:transparent; border:0; padding-inline:10px; padding-bottom:6px;
        }
        header .nav-links a.btn:not(.primary)::after{
            content:""; position:absolute; left:50%; bottom:-6px; width:0; height:2px;
            background:linear-gradient(90deg,var(--pi-blue),var(--pi-red)); transition:width .25s,left .25s;
        }
        header .nav-links a.btn:not(.primary):hover::after,
        header .nav-links a.btn:not(.primary).is-active::after{ width:100%; left:0; }
        @media (max-width: 768px){
            .nav{ grid-template-columns: 1fr auto; row-gap:10px; height:auto; }
            .nav-links{ justify-self:start; flex-wrap:wrap }
            .auth{ justify-self:end }
        }

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
        .video-card{position:relative; border-radius:18px; overflow:hidden; border:1px solid #1a2033; box-shadow:0 20px 60px #070a12; width:35vw; height:35vh; margin:0; padding:0;}
        .video-card video{display:block; width:100%; height:100%; object-fit:cover; object-position:center}

        /* Sections */
        section{padding:64px 0; background:transparent !important;}

        .section-hd{display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:20px}
        .section-hd h2{font-size:clamp(24px,3.3vw,40px); margin:0}
        .sub{color:var(--muted)}

        /* Reveal base */
        .reveal{opacity:0; transform:translateY(16px) scale(.98); filter:saturate(.9); transition:opacity .5s ease, transform .5s ease, filter .5s ease}
        .reveal.is-visible{opacity:1; transform:none; filter:none}

        /* Catalogue */
        #catalog .catalog-app{display:flex; flex-direction:column; gap:16px}
        #catalog .toolbar{position:static; background:linear-gradient(180deg,#0d1321cc 0%,#0d132199 100%); border:1px solid #182037; border-radius:14px; overflow:hidden; backdrop-filter:blur(8px);}
        #catalog .toolbar .row{ display:flex; gap:.6rem; align-items:center; flex-wrap:wrap; padding:.65rem 1rem }
        #catalog .brand{font-weight:800; letter-spacing:.2px; display:flex; gap:.6rem; align-items:center}
        #catalog .brand .dot{ width:.6rem; height:.6rem; border-radius:999px; background:#3aa0ff; box-shadow:0 0 0 4px #3aa0ff22; }
        #catalog .btn{ appearance:none; border:1px solid #1f2942; background:#131a2a; color:var(--text); border-radius:999px; padding:.55rem .9rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; font-weight:800; box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004; transition:transform .1s ease, border-color .2s ease, background .2s ease; }
        #catalog .btn:hover{ border-color:#2a3659; background:#0f1626 }
        #catalog .btn.icon{ padding:.55rem .7rem }
        #catalog .sep{ width:1px; height:28px; background:#223052; opacity:.6; margin:0 .25rem }
        #catalog .metric{ margin-left:auto; display:inline-flex; align-items:center; gap:.5rem; font-weight:800; color:#cfe0ff; background:#0e1423; border:1px solid #1f2942; padding:.45rem .75rem; border-radius:.75rem; box-shadow:0 1px 0 #ffffff0d inset; }
        #catalog .metric small{ color:#8fa0bf; font-weight:700 }
        #catalog .stage{ position:relative; border:1px solid var(--edge); background:transparent; border-radius:18px; box-shadow:0 24px 60px #0009, inset 0 1px 0 #ffffff12, inset 0 0 0 1px #0008; display:grid; place-items:center; overflow:hidden; }
        #catalog .stage::after{ content:""; position:absolute; inset:0; pointer-events:none; background:radial-gradient(1400px 700px at 50% -10%, transparent 0%, #00000022 60%, #00000055 100%) }
        #catalog .stage-inner{ position:relative; transform-origin:50% 50%; transition:transform .15s ease; overflow:hidden; border-radius:14px }
        #flipbook{ width:min(92vw,1040px); height:88vh }
        @media (max-width:768px){ #flipbook{ height:92dvh } #catalog .metric{ display:none } }

        /* Avantages (carousel) */
        #advantages .carousel{ position:relative; isolation:isolate; background:linear-gradient(180deg,#0f1525aa,#0d132199); border:1px solid var(--edge); border-radius:18px; padding:clamp(14px,2vw,18px); box-shadow:0 18px 40px rgba(0,0,0,.35); }
        #advantages .track-viewport{position:relative; overflow:hidden; border-radius:12px;}
        #advantages .track{display:flex; gap:16px; will-change:transform; transition:transform .45s cubic-bezier(.22,.84,.3,1); touch-action:pan-y;}
        #advantages .card{ min-width:clamp(260px,42vw,340px); flex:0 0 clamp(260px,42vw,340px); background:linear-gradient(180deg,#0e1422,#0b101b); border:1px solid var(--edge); border-radius:16px; overflow:hidden; padding:16px; transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
        #advantages .thumb{aspect-ratio:16/9; background:linear-gradient(135deg,#142036,#171d2b); border:1px solid #202a44; border-radius:12px; margin-bottom:12px; overflow:hidden;}
        #advantages .thumb img{width:110%; height:110%; object-fit:cover; transform:scale(1); transition:transform .35s ease;}
        #advantages .card:hover .thumb img{transform:scale(1.06)}
        #advantages .meta{display:flex; align-items:center; gap:10px; color:var(--muted); font-size:13px; margin-bottom:6px}
        #advantages .dot{width:8px; height:8px; border-radius:50%;}
        #advantages .adv-nav{ position:absolute; inset:0; pointer-events:none; z-index:2; }
        #advantages .adv-nav button{ pointer-events:auto; position:absolute; top:50%; transform:translateY(-50%); width:46px; height:46px; border-radius:14px; border:1px solid #223055; background:rgba(15,22,35,.86); color:#cfe0ff; display:grid; place-items:center; cursor:pointer; backdrop-filter:blur(6px); transition:background .2s ease, border-color .2s ease, box-shadow .2s ease; }
        #advantages .adv-nav .prev{left:10px} .adv-nav .next{right:10px}
        #advantages .adv-nav button:hover{box-shadow:0 6px 24px rgba(0,0,0,.35); background:rgba(20,28,48,.95)}

        /* Strip défilant */
        .strip-section{ padding:48px 0 24px; }
        .strip{ width:100%; padding:clamp(12px, 2.2vh, 24px) 0; overflow:hidden; position:relative; border-radius:18px; }
        .strip::before,.strip::after{ content:""; position:absolute; top:0; bottom:0; width:10vw; pointer-events:none; z-index:2; }
        .strip::before{ left:0; background:linear-gradient(90deg, transparent 0%, rgba(10,10,10,0.0) 100%); }
        .strip::after{ right:0; background:linear-gradient(270deg, transparent 0%, rgba(10,10,10,0.0) 100%); }
        .marquee-strip{ width: max(200%, 200vw); }
        .track-strip{ display:flex; align-items:center; gap:var(--strip-gap); width:max-content; animation: stripScroll var(--strip-speed) linear infinite; }
        .strip:hover .track-strip{ animation-play-state: paused; }
        @keyframes stripScroll{ from{transform:translateX(0)} to{transform:translateX(-50%)} }
        .card-strip{ width:var(--strip-card); aspect-ratio:1/1; border-radius: calc(var(--strip-radius) + var(--strip-border)); padding: var(--strip-border);
            background: linear-gradient(#0b0b0b, #0b0b0b) padding-box, conic-gradient(from 225deg at 20% 80%, rgba(255,255,255,.95), rgba(255,255,255,.15), rgba(255,255,255,.6), rgba(255,255,255,.2), rgba(255,255,255,.9)) border-box;
            border:2px solid transparent; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.04), 0 12px 40px rgba(0,0,0,0.55);
        }
        .card-strip .inner{ width:100%; height:100%; border-radius:var(--strip-radius); overflow:hidden; background:#0a0a0a; position:relative; display:block; }
        .card-strip img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:block; }

        /* STORES */
        #stores .nav-tabs{ display:flex; justify-content:center; gap:12px; margin-bottom:26px; flex-wrap:wrap; position:relative }
        #stores .nav-tab{ background:transparent; border:1px solid #1f2942; color:#e6edff; font-weight:800; padding:.75rem 1.1rem; cursor:pointer; border-radius:999px; transition:all .25s ease; display:inline-flex; align-items:center; gap:8px; }
        #stores .nav-tab:hover{ background:#121a30; }
        #stores .nav-tab.active{ background:#1c305c; color:#fff; box-shadow:0 0 10px rgba(28,48,92,.35) }
        .tabs-underline{position:absolute;bottom:-6px;height:3px;border-radius:3px;
            background:linear-gradient(90deg,#e11d48,#f59e0b);transition:transform .25s ease,width .25s ease}
        .badge-new{ padding:2px 8px; font-size:.75rem; border-radius:999px; font-weight:800; letter-spacing:.4px; color:#fff; text-transform:uppercase;
            background:linear-gradient(90deg,#e11d48,#f59e0b,#e11d48); background-size:200% 100%;
            animation:shimmer 2s linear infinite, floatY 2.6s ease-in-out infinite; box-shadow:0 0 0 1px #ffffff40 inset, 0 0 10px #e11d4880; }
        @keyframes shimmer { to{ background-position:200% 0; } }
        @keyframes floatY { 0%,100%{ transform:translateY(0) } 50%{ transform:translateY(-1.5px) } }
        #stores .content-area{ background:linear-gradient(180deg,#0f1525aa,#0d132199); border:1px solid #1d2742; border-radius:20px; padding:1.6rem; backdrop-filter:blur(15px); min-height:500px; display:grid; grid-template-columns:1fr 1fr; gap:1.6rem; align-items:stretch; }
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
        .leaflet-popup-content-wrapper{ background:#ffffff; color:#0b0f1a; border-radius:10px; box-shadow:0 12px 26px rgba(0,0,0,.25) }
        .leaflet-popup-tip{ background:#ffffff; }
        .leaflet-popup-content{ color:#0b0f1a; font-weight:600 }
        .leaflet-marker-icon div{animation:bounce 1.6s ease-in-out infinite;transform-origin:center bottom}
        @keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
        @media (max-width:1024px){
            #stores .nav-tabs{ justify-content:flex-start; overflow-x:auto; padding-bottom:.5rem }
        }
        @media (max-width:768px){
            #stores .content-area{ grid-template-columns:1fr; gap:1rem; padding:1.25rem }
            #stores .map-section, #stores .map-container{ min-height:320px }
            .strip-section{ padding:36px 0 8px; }
        }

        /* CONTACT */
        #contact { padding: 72px 0; }
        #contact .section-hd { flex-direction: column; align-items: center; gap: 6px; text-align: center; background:transparent !important; }
        .contact-grid{ display:grid; grid-template-columns:1fr 1fr; gap:28px; align-items:stretch; }
        .contact-panel{ background: linear-gradient(180deg,#121826,#0e1422); border: 1px solid #1e2740; border-radius: 22px; box-shadow: 0 18px 50px rgba(0,0,0,.35), inset 0 1px 0 #ffffff10; padding: 28px 24px; display:flex; flex-direction:column; gap:22px; }
        .contact-title{ margin:0; text-align:center; font-size: clamp(20px, 2.2vw, 24px); font-weight: 800; text-decoration: underline; text-underline-offset: 6px; text-decoration-thickness: 3px; }
        .form-row{ display:flex; flex-direction:column; gap:12px; }
        .form-control{ width:100%; padding:16px 18px; border-radius:14px; color:#fff; background:linear-gradient(145deg,#0f152b,#0c1223); border:1px solid #1e2740; outline:none; font: 600 16px/1.2 "Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial; }
        .form-control::placeholder{ color:#a9b6d3; font-weight:600; }
        .form-control:focus{ outline:2px solid var(--ring); }
        .form-textarea{ min-height:140px; resize:vertical; }
        .btn-send{ appearance:none; cursor:pointer; border:0; width:100%; padding:16px 18px; border-radius:14px; font-weight:800; font-size:16px; letter-spacing:.1px; color:#fff; background: linear-gradient(145deg, #d26043, #8b1f22); box-shadow: 0 12px 32px rgba(139,31,34,.35); transition: transform .08s ease, box-shadow .2s ease, filter .2s ease; }
        .btn-send:hover{ filter:brightness(1.05); box-shadow:0 18px 40px rgba(139,31,34,.45); }
        .btn-send:active{ transform: translateY(1px); }
        .info-table{ display:grid; grid-template-columns: 24px 130px 1fr; row-gap:14px; column-gap:12px; align-items:center; color:#e7ecf5; font-weight:700; margin-top:-6px; }
        .info-ico{ width:24px; height:24px; display:block; color:#cdd9ff; opacity:.95 }
        .info-label{ font-weight:800; line-height:1.1 }
        .info-value{ color:#c4d0ea; font-weight:600; line-height:1.2 }
        .newsletter{ display:flex; flex-direction:column; align-items:center; gap:10px; margin-top:6px; }
        .news-wrap{ display:flex; width:100%; max-width:520px; gap:10px; }
        .news-input{ flex:1; padding:14px 16px; border-radius:12px; border:1px solid #1e2740; background:linear-gradient(145deg,#0f152b,#0c1223); color:#fff; font-weight:600; }
        .news-input::placeholder{ color:#a9b6d3; }
        .news-btn{ display:grid; place-items:center; width:56px; border-radius:12px; border:1px solid #213055; background: linear-gradient(145deg, #122043, #0e1731); color:#cfe0ff; cursor:pointer; transition: transform .08s ease, background .2s ease, border-color .2s ease; }
        .news-btn:hover{ background:#0f1b3b; border-color:#2a3d73; }
        .news-btn:active{ transform: translateY(1px); }
        @media (max-width:980px){ .contact-grid{ grid-template-columns:1fr; } }

        /* Footer : TRANSPARENT pour laisser voir le fond unique */
        footer.pi-footer{ background:transparent !important; border-top: 1px solid #141a2b; }
        .pi-footer .wrap{ max-width:1100px; margin:0 auto; text-align:center; }
        .pi-footer .brand{ height:72px; width:auto; object-fit:contain; display:block; margin:0 auto 18px; }
        .pi-footer .headline{ display:flex; align-items:center; justify-content:center; gap:22px; margin:6px auto 18px; }
        .pi-footer .headline h2{ margin:0; font-weight:800; letter-spacing:.12em; color:var(--pi-red, #D6452E); font-size:24px; }
        .pi-footer .headline .line{ height:4px; width:260px; border-radius:2px; background:var(--pi-red, #D6452E); transform-origin:center; transform:scaleX(0); transition:transform .6s cubic-bezier(.22,.84,.3,1) }
        @media (max-width:720px){ .pi-footer .headline .line{ width:20vw } .pi-footer .headline h2{ font-size:20px } }
        .pi-footer .social{ list-style:none; display:flex; justify-content:center; align-items:center; gap:14px; padding:0; margin:14px 0 20px; }
        .pi-footer .social a{ width:42px; height:42px; display:grid; place-items:center; background:#101733; color:#cfe0ff; border-radius:50%; border:1px solid #1e2740; font-size:18px; transition:transform .2s ease, background .2s ease, border-color .2s ease, color .2s ease; }
        .pi-footer .social a:hover{ background: linear-gradient(145deg, var(--pi-blue,#2E4C97), var(--pi-red,#D6452E)); border-color:#2a3659; color:#fff; transform:translateY(-2px); }
        .pi-footer .footer-nav{ display:flex; flex-wrap:wrap; justify-content:center; gap:26px 30px; padding:12px 0 8px; margin:0 auto 12px; }
        .pi-footer .footer-nav a{ text-decoration:none; color:#e9f1ff; font-weight:800; font-size:14px; letter-spacing:.04em; text-transform:uppercase; transition:color .2s ease; }
        .pi-footer .footer-nav a:hover{ color:var(--pi-red,#D6452E) }
        .pi-footer .copyright{ margin:6px 0 0; font-size:12px; color:var(--muted); user-select:none; }

        /* Progress + cursor */
        .progress{position:fixed;top:0;left:0;height:3px;width:0; background:linear-gradient(90deg,#3aa0ff,#e11d48);z-index:1000;box-shadow:0 0 12px #3aa0ff88}
        .cursor-dot,.cursor-ring{position:fixed;pointer-events:none;z-index:10000;left:0;top:0;transform:translate(-50%,-50%)}
        .cursor-dot{width:6px;height:6px;border-radius:50%;background:#fff}
        .cursor-ring{width:36px;height:36px;border-radius:50%;border:1px solid #ffffff55;mix-blend-mode:exclusion;transition:width .15s ease,height .15s ease}
        .click-ripple{position:fixed;left:0;top:0;width:12px;height:12px;border-radius:999px;background:#ffffff40;pointer-events:none;z-index:9999;transform:translate(-50%,-50%)}

        /* Gradient animé "à deux pas" */
        .gradient-text{ background-image: linear-gradient(90deg, var(--blue), #b6152f, var(--red), #b6152f, var(--blue)); background-size:300% 100%; background-position:0% 50%; -webkit-background-clip:text; background-clip:text; color:transparent; }

        /* === AOS mini (stores, contact, footer) === */
        .aos{opacity:0; will-change:transform,opacity,clip-path}
        .aos.in{opacity:1}
        @keyframes aos-rise   {from{transform:translateY(22px);opacity:0} to{transform:none;opacity:1}}
        @keyframes aos-scale  {from{transform:scale(.96);opacity:0}        to{transform:scale(1);opacity:1}}
        @keyframes aos-sweep  {from{clip-path:inset(0 50% 0 50%);opacity:0} to{clip-path:inset(0 0 0 0);opacity:1}}
        @keyframes aos-pop    {0%{transform:scale(.6);opacity:0} 70%{transform:scale(1.06)} 100%{transform:scale(1);opacity:1}}
        [data-anim="rise"].in  {animation:aos-rise .65s cubic-bezier(.22,.84,.3,1) both;  animation-delay:var(--aos-delay,0ms)}
        [data-anim="scale"].in {animation:aos-scale .55s ease-out both;                animation-delay:var(--aos-delay,0ms)}
        [data-anim="sweep"].in {animation:aos-sweep .70s ease-out both;                animation-delay:var(--aos-delay,0ms)}
        [data-anim="pop"].in   {animation:aos-pop .45s cubic-bezier(.2,.9,.2,1.2) both;animation-delay:var(--aos-delay,0ms)}
        .pi-footer .headline.in .line{transform:scaleX(1)}
        @media (prefers-reduced-motion: reduce){
            .aos,[data-anim]{opacity:1!important; animation:none!important; transform:none!important; clip-path:none!important}
        }
        /* ====== HEADER SIMPLE (scopé) ====== */
        header.pi-simple{ background:transparent !important; }
        .pi-simple .topbar{
            display:grid; grid-template-columns:1fr auto 1fr;
            align-items:center; gap:16px; padding:14px 0;
        }
        .pi-simple .left-col{display:flex; align-items:flex-start}
        .pi-simple .social-group{display:flex; flex-direction:column; align-items:center; width:max-content}
        .pi-simple .social{display:flex; align-items:center; gap:16px; color:var(--muted,#c9d4ea)}
        .pi-simple .social a{font-size:18px; color:var(--muted,#c9d4ea)}
        .pi-simple .social a:hover{color:#fff}
        .pi-simple .join{font-size:13px; color:var(--muted,#c9d4ea); font-weight:800; margin-top:6px; text-align:center}

        .pi-simple .brand{display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px}
        .pi-simple .brand img{height:56px; width:auto; display:block}
        .pi-simple .tagline{display:flex; align-items:center; gap:12px; color:var(--muted,#c9d4ea); font-size:13px; line-height:1}
        .pi-simple .tagline .rule{width:64px; height:1px; background:var(--border,rgba(255,255,255,.06))}

        .pi-simple .right-col{display:flex; justify-content:flex-end; align-items:center; gap:10px; font-weight:800}
        .pi-simple .right-col i{color:#c9d4ea}
        .pi-simple .phone{font-size:14px; color:#e7ecf5}

        .pi-simple .divider{border:0; border-top:1px solid var(--edge,#141a26); margin:0}

        .pi-simple .navrow{padding:12px 0}
        .pi-simple .menu{display:flex; justify-content:center; gap:28px; list-style:none; margin:0; padding:0}
        .pi-simple .menu a{
            font-weight:800; font-size:14px; color:#c9d4ea; letter-spacing:.06em; text-transform:uppercase
        }
        .pi-simple .menu a:hover, .pi-simple .menu a.is-active{color:#ffffff}

        @media (max-width:720px){
            .pi-simple .topbar{grid-template-columns:1fr; row-gap:10px; text-align:center}
            .pi-simple .left-col{justify-content:center}
            .pi-simple .social-group{margin:0 auto}
            .pi-simple .brand img{height:48px}
            .pi-simple .tagline .rule{width:48px}
            .pi-simple .menu{flex-wrap:wrap; gap:18px}
        }
        /* === PATCH : bloc central (logo) plus imposant === */
        .pi-simple .topbar{
            /* colonne centrale plus large */
            grid-template-columns: 1fr minmax(460px, 1.7fr) 1fr;
            /* plus d'air vertical */
            padding-block: clamp(18px, 3.5vh, 40px);
        }

        .pi-simple .brand{
            gap: 10px;
            /* donne un peu de "masse" visuelle au bloc */
            padding-block: clamp(4px, 0.8vh, 10px);
        }

        .pi-simple .brand img{
            /* logo nettement plus grand mais responsive */
            height: clamp(65px, 10vw, 80px);
            width: auto;
        }

        .pi-simple .tagline{
            font-size: clamp(13px, 1.3vw, 16px);
            gap: 14px;
        }

        .pi-simple .tagline .rule{
            /* traits plus longs autour du "Since 1993" */
            width: clamp(65px, 10vw, 100px);
        }



        /* Mobile : on garde des proportions raisonnables */
        @media (max-width: 720px){
            .pi-simple .topbar{ padding-block: 14px; }
            .pi-simple .brand img{ height: 64px; }
            .pi-simple .tagline .rule{ width: 48px; }
        }

        /* === PATCH : téléphone + réseaux plus grands === */

        /* Téléphone (colonne droite) */
        .pi-simple .right-col{
            gap: clamp(10px, 1.4vw, 16px);
        }
        .pi-simple .right-col i{
            font-size: clamp(18px, 2.2vw, 26px);
        }
        .pi-simple .phone{
            font-size: clamp(16px, 1.6vw, 24px);
            font-weight: 800;
            letter-spacing: .2px;
        }

        /* Réseaux (colonne gauche) */
        .pi-simple .social{
            gap: clamp(14px, 1.8vw, 22px);
        }
        .pi-simple .social a{
            font-size: clamp(20px, 2.2vw, 26px);
        }

        /* Mobile : tailles confortables */
        @media (max-width:720px){
            .pi-simple .right-col i{ font-size: 22px; }
            .pi-simple .phone{ font-size: 18px; }
            .pi-simple .social a{ font-size: 20px; }
            .pi-simple .social{ gap: 14px; }
        }


        /* === TWEAK v2 : tailles plus petites (téléphone, réseaux, "Rejoignez nous") === */

        /* Colonne droite : téléphone */
        .pi-simple .right-col{ gap: clamp(8px, 1vw, 12px); }
        .pi-simple .right-col i{ font-size: clamp(16px, 1.4vw, 20px); }
        .pi-simple .phone{
            font-size: clamp(14px, 1.2vw, 18px);
            font-weight: 700;
            letter-spacing: 0;
        }

        /* Colonne gauche : réseaux */
        .pi-simple .social{ gap: clamp(10px, 1.2vw, 16px); }
        .pi-simple .social a{ font-size: clamp(16px, 1.6vw, 20px); }

        /* Texte "Rejoignez nous" (sous les icônes) */
        .pi-simple .join{
            font-size: clamp(11px, 1vw, 13px);
            line-height: 1;
            margin-top: 4px;
        }

        /* Mobile */
        @media (max-width:720px){
            .pi-simple .right-col i{ font-size: 18px; }
            .pi-simple .phone{ font-size: 16px; }
            .pi-simple .social a{ font-size: 18px; }
            .pi-simple .join{ font-size: 12px; }
        }
        /* === MICRO-BOOST : icônes + "Rejoignez nous" un poil plus grands === */
        .pi-simple .social a{
            font-size: clamp(17px, 1.7vw, 25px);
        }
        .pi-simple .join{
            font-size: clamp(12px, 1.05vw, 22px);
        }

        /* Mobile */
        @media (max-width:720px){
            .pi-simple .social a{ font-size: 19px; }
            .pi-simple .join{ font-size: 13px; }
        }

        /* === TWEAK : bloc central légèrement plus étroit === */
        .pi-simple .topbar{
            /* avant : minmax(460px, 1.7fr) */
            grid-template-columns: 1fr minmax(200px, 1fr) 1fr;
        }

        /* (optionnel) logo et ligne un chouïa plus petits */
        .pi-simple .brand img{
            /* avant : clamp(65px, 10vw, 80px) */
            height: clamp(60px, 9vw, 72px);
        }
        .pi-simple .tagline .rule{
            /* avant : clamp(65px, 10vw, 100px) */
            width: clamp(58px, 9vw, 92px);
        }

        /* === FONDS : bandeau défilant (tout en haut) === */
        .marquee{
            background: linear-gradient(180deg, rgba(15,21,37,.92), rgba(13,19,33,.86));
            border-top: 1px solid #1b2744;
            border-bottom: 1px solid #1b2744;
            backdrop-filter: blur(10px);
            box-shadow: inset 0 8px 24px rgba(0,0,0,.25);
        }
        .marquee .pill{
            background: linear-gradient(145deg,#121a34,#0f162a);
            border-color:#223055;
            color:#e7ecf5;
        }

        /* === FONDS : header (bloc logo + ligne de menu) === */
        .pi-simple .topbar,
        .pi-simple .navrow{
            background: linear-gradient(180deg, rgba(15,21,37,.90), rgba(12,18,34,.86));
            border: 1px solid #1d2742;
            border-radius: 18px;
            backdrop-filter: blur(10px);
            box-shadow: 0 12px 28px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06);
        }

        /* === FOND : footer === */
        body footer.pi-footer{
            background:
                    radial-gradient(900px 500px at 10% -10%, rgba(46,76,151,.12), transparent 60%),
                    radial-gradient(900px 500px at 90% -10%, rgba(214,69,46,.10), transparent 55%),
                    linear-gradient(180deg, #0f1525, #0c1223) !important; /* écrase l'ancien transparent !important */
            border-top: 1px solid #141a2b;
            box-shadow: inset 0 12px 40px rgba(0,0,0,.35);
        }
        /* ===== FULL-BLEED BACKGROUNDS (header + footer + bandeau) ===== */

        /* Bandeau qui défile (tout en haut) : déjà full width, on lui met un fond */
        .marquee{
            background: linear-gradient(180deg, rgba(15,21,37,.94), rgba(13,19,33,.88));
            border-top: 1px solid #1b2744;
            border-bottom: 1px solid #1b2744;
            backdrop-filter: blur(10px);
        }

        /* HEADER : retire l'ancien fond arrondi et crée un fond qui va bord à bord */
        .pi-simple .topbar,
        .pi-simple .navrow{
            position: relative;
            isolation: isolate;         /* crée un contexte pour placer le ::before derrière */
            background: transparent !important;
            border-radius: 0;           /* plus de coins arrondis */
        }

        /* Topbar : fond plein écran derrière la .container */
        .pi-simple .topbar::before{
            content:"";
            position:absolute;
            z-index:-1;
            top:0; bottom:0;
            left:50%; right:50%;
            margin-left:-50vw; margin-right:-50vw; /* déborde jusqu’aux bords de l’écran */
            background: linear-gradient(180deg, rgba(15,21,37,.90), rgba(12,18,34,.86));
            box-shadow: 0 12px 28px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06);
            border-bottom: 1px solid #1d2742;
        }

        /* Rangée du menu : même principe */
        .pi-simple .navrow::before{
            content:"";
            position:absolute;
            z-index:-1;
            top:0; bottom:0;
            left:50%; right:50%;
            margin-left:-50vw; margin-right:-50vw;
            background: linear-gradient(180deg, rgba(15,21,37,.90), rgba(12,18,34,.86));
            box-shadow: 0 12px 28px rgba(0,0,0,.30), inset 0 1px 0 rgba(255,255,255,.05);
            border-top: 1px solid #1d2742;
        }

        /* FOOTER : fond plein écran malgré la .container interne */
        footer.pi-footer{
            position: relative;
            isolation: isolate;
        }
        footer.pi-footer::before{
            content:"";
            position:absolute;
            z-index:-1;
            top:0; bottom:0;
            left:50%; right:50%;
            margin-left:-50vw; margin-right:-50vw;
            background:
                    radial-gradient(900px 500px at 10% -10%, rgba(46,76,151,.12), transparent 60%),
                    radial-gradient(900px 500px at 90% -10%, rgba(214,69,46,.10), transparent 55%),
                    linear-gradient(180deg, #0f1525, #0c1223);
            border-top: 1px solid #141a2b;
            box-shadow: inset 0 12px 40px rgba(0,0,0,.35);
        }

        .marquee__inner{
            animation: marquee 22s linear infinite;
        }

        /* ======= COMPACER LE BLOC DE CATALOGUE (sans toucher au flipbook) ======= */

        /* Moins d'air autour de la section */
        #catalog {
            padding: 28px 0;            /* avant 64px 0 */
        }
        #catalog .section-hd {
            margin-bottom: 8px;         /* avant 20px */
        }

        /* Réduire les espacements internes de l'app */
        #catalog .catalog-app {
            gap: 10px;                  /* avant 16px */
        }

        /* Toolbar plus compacte (même boutons, juste moins haut) */
        #catalog .toolbar .row{
            padding: .45rem .75rem;     /* avant .65rem 1rem */
            gap: .45rem;                /* avant .6rem */
        }
        #catalog .btn{
            padding: .45rem .7rem;      /* avant .55rem .9rem */
            border-radius: 999px;
            font-weight: 800;
            font-size: .92rem;          /* léger downscale */
        }
        #catalog .btn.icon{
            padding: .42rem .58rem;     /* icônes un poil plus serrées */
        }
        #catalog .metric{
            padding: .35rem .6rem;      /* plus compact */
            font-size: .9rem;
        }

        /* La zone “stage” conserve le flipbook tel quel,
           mais on réduit l’épaisseur visuelle du cadre */
        #catalog .stage{
            border-radius: 14px;        /* avant 18px */
            box-shadow: 0 18px 46px #0009, inset 0 1px 0 #ffffff12, inset 0 0 0 1px #0008;
        }
        #catalog .stage::after{
            /* halo un peu moins haut */
            background: radial-gradient(1200px 560px at 50% -10%, transparent 0%, #00000022 60%, #00000055 100%);
        }

        /* L’enveloppe du flipbook (ne change PAS sa taille),
           mais évite tout padding parasite autour */
        #stageBox{
            padding: 0 !important;
        }
        #stageInner{
            margin: 0 !important;
            /* on garde le scale/transform géré par ton JS */
        }

        /* ===== Fonds opaques pour le BLOC CATALOGUE ===== */


        /* 2) Enveloppe de l’app (optionnel mais clean visuellement) */
        #catalog .catalog-app{
            background: #0f1525;
            border: 1px solid #1d2742;
            border-radius: 18px;
            box-shadow: 0 16px 48px rgba(0,0,0,.35);
            padding: 12px; /* léger padding pour respirer */
        }

        /* 3) Toolbar : fini le semi-transparent/blur */
        #catalog .toolbar{
            background: #121a34 !important;
            border-color: #1f2942 !important;
            backdrop-filter: none !important;
        }

        /* 4) Stage (cadre du flipbook) : fond plein */
        #catalog .stage{
            background: #0e1423 !important;
        }

        /* 5) Empêcher toute “transparence résiduelle” autour du flipbook */
        #stageInner{ background: #0e1423 !important; }
        #flipbook  { background: #0e1423 !important; }  /* au cas où le composant ajoute du vide */

        /* ===== Toolbar Catalogue : rendre les icônes visibles ===== */
        #catalog .toolbar .btn{
            color:#eaf0ff !important;        /* force une couleur claire */
        }

        #catalog .toolbar .btn svg{
            width:18px;                      /* taille explicite */
            height:18px;
            display:block;                   /* évite le shrink */
        }

        #catalog .toolbar .btn svg path{
            fill:none !important;            /* assure un tracé */
            stroke:currentColor !important;  /* utilise la couleur du bouton */
            stroke-width:2;                  /* lisible sur fond sombre */
            stroke-linecap:round;
            stroke-linejoin:round;
        }

        /* boutons icônes : compacts, sans effet d’écrasement du texte */
        #catalog .toolbar .btn.icon{
            line-height:0;
            padding:.42rem .58rem; /* si tu veux garder compact */
        }
        /* ===== Stores tabs : fonds opaques derrière le texte ===== */
        #stores .nav-tab{
            background: linear-gradient(145deg, #111a31, #0e1528) !important; /* fond plein */
            border: 1px solid #223055 !important;
            color: #e7ecf5;
            box-shadow:
                    inset 0 1px 0 rgba(255,255,255,.06),
                    0 6px 16px rgba(0,0,0,.25);
            backdrop-filter: none;
        }

        #stores .nav-tab:hover{
            background: linear-gradient(145deg, #15203d, #101a33);
            border-color: #2a3659;
            color:#fff;
        }

        #stores .nav-tab.active{
            background: linear-gradient(145deg, #1c305c, #2a3d73) !important; /* état actif bien visible */
            border-color: #2a3d73 !important;
            color:#fff;
            box-shadow:
                    inset 0 1px 0 rgba(255,255,255,.07),
                    0 8px 22px rgba(0,0,0,.30);
        }

        /* Le petit badge "NEW" reste lisible sur fond plus sombre */
        #stores .nav-tab .badge-new{
            box-shadow: 0 0 0 1px rgba(255,255,255,.28) inset, 0 8px 18px rgba(225,29,72,.35);
        }

        /* (Optionnel) réduire un peu la transparence du fond derrière toute la rangée */
        #stores .nav-tabs{
            background: linear-gradient(180deg, rgba(12,18,34,.85), rgba(11,16,29,.82));
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid #1d2742;
        }

        /* ======= NOS MAGASINS : un bloc unique pour rassembler carte + infos ======= */

        /* 1) Le conteneur devient la "carte" principale */
        #stores .content-area{
            position: relative;
            grid-template-columns: 1.2fr .8fr;     /* 2 colonnes */
            gap: 0;                                 /* pas d'espace entre elles */
            padding: 16px;                          /* marge intérieure du bloc */
            border-radius: 22px;
            border: 1px solid #1d2742;
            background: linear-gradient(180deg,#111a2b,#0e1526);
            box-shadow: 0 18px 48px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05);
            overflow: hidden;                        /* coins communs */
        }

        /* 2) Séparateur vertical discret entre carte et fiche */
        #stores .content-area::after{
            content:"";
            position:absolute;
            top:16px; bottom:16px;
            left: calc(100% * 1.2 / (1.2 + .8));    /* à la jonction des 2 colonnes */
            width:1px;
            background:#233055;
            opacity:.9;
            pointer-events:none;
        }

        /* 3) Nettoyer les sous-blocs pour qu’ils fassent "corps" dans le grand bloc */
        #stores .map-section,
        #stores .map-container,
        #stores #map{
            border:0 !important;
            border-radius: 14px !important;          /* coins doux internes */
            background: transparent;
        }
        #stores .info-section{
            background: transparent;                 /* le fond vient du bloc parent */
            padding: 16px;                           /* un peu d'air à droite */
            gap: 14px;
        }
        #stores .store-image{
            border:0;
            border-radius: 12px;
        }

        /* 4) Boutons en bas : bien posés */
        #stores .actions{
            margin-top:auto;
            gap: 12px;
        }

        /* 5) Mobile : on passe en une colonne, le séparateur devient horizontal */
        @media (max-width: 768px){
            #stores .content-area{
                grid-template-columns: 1fr;
                padding: 12px;
            }
            #stores .content-area::after{
                display:none;
            }
            #stores .info-section{
                border-top:1px solid #233055;          /* séparateur horizontal */
                margin-top: 12px;
                padding-top: 16px;
            }
        }
        /* ===== NOS MAGASINS : carte + onglets dans un seul bloc style "Catalogue" ===== */

        /* 1) La rangée d’onglets = top du carton */
        #stores .nav-tabs{
            background: linear-gradient(180deg, #121a32, #0f172c) !important;
            border: 1px solid #1d2742 !important;
            border-bottom: none !important;              /* on laisse le bas ouvert pour s'emboîter */
            border-radius: 18px 18px 0 0 !important;     /* coins supérieurs */
            padding: 10px 12px !important;
            margin-bottom: 0 !important;                 /* colle au bloc dessous */
            box-shadow: 0 10px 28px rgba(0,0,0,.28);
        }

        /* 2) Le contenu (carte + fiche) = bas du carton */
        #stores .content-area{
            background: linear-gradient(180deg, #10182e, #0d1529) !important;
            border: 1px solid #1d2742 !important;
            border-top: none !important;                 /* pas de double bord avec la rangée d’onglets */
            border-radius: 0 0 18px 18px !important;     /* coins inférieurs */
            padding: 16px !important;                    /* marge interne “carton” */
            box-shadow: 0 18px 48px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05);
            gap: 0 !important;                           /* si tu veux garder la fusion carte/fiche */
            overflow: hidden;                            /* coins arrondis conservés */
        }

        /* 3) Optionnel : un filet entre la carte et la fiche (vertical en desktop) */
        #stores .content-area{ position: relative; }
        #stores .content-area::after{
            content:"";
            position:absolute;
            top:16px; bottom:16px;
            left: calc(100% * 1.2 / (1.2 + .8));        /* à la jonction des 2 colonnes */
            width:1px; background:#233055; opacity:.9; pointer-events:none;
        }
        @media (max-width:768px){
            #stores .content-area::after{ display:none; }
        }

        /* 4) Nettoyage des sous-blocs pour que tout fasse corps */
        #stores .map-section, #stores .map-container, #stores #map{
            border:0 !important; border-radius:12px !important; background:transparent !important;
        }
        #stores .info-section{
            background:transparent !important; padding:18px !important; gap:14px !important;
        }
        #stores .store-image{ border:0 !important; border-radius:12px !important; }

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

<!-- ===== FOND GLOBAL UNIQUE (identique à “Notre histoire”) ===== -->
<div id="page-bg" aria-hidden="true"></div>
<div class="pi-orbs" aria-hidden="true">
    <span class="orb blue a"></span>
    <span class="orb red  b"></span>
    <span class="orb blue c"></span>
    <span class="orb red  d"></span>
</div>

<!-- Progress + cursor -->
<div class="progress" id="progress"></div>
<div class="cursor-dot" id="cDot"></div>
<div class="cursor-ring" id="cRing"></div>

<!-- (Canvas de fond SUPPRIMÉ car on utilise désormais le fond unique)
<canvas id="bg-anim" aria-hidden="true"></canvas>
-->

<!-- Bandeau -->
<div class="marquee" aria-hidden="true">
    <div class="marquee__inner">
        <span class="pill"><span class="dot"></span> Promotions fraîches chaque semaine</span>
        <span class="pill"><span class="dot"></span> Qualité halal • Boucherie & primeur</span>
        <span class="pill"><span class="dot"></span> Épicerie du monde • Turquie, Maghreb & +</span>
        <span class="pill"><span class="dot"></span> Produits frais & de saison</span>
        <span class="pill"><span class="dot"></span> Promotions fraîches chaque semaine</span>
        <span class="pill"><span class="dot"></span> Qualité halal • Boucherie & primeur</span>
        <span class="pill"><span class="dot"></span> Épicerie du monde • Turquie, Maghreb & +</span>
        <span class="pill"><span class="dot"></span> Produits frais & de saison</span>
    </div>
</div>

<header class="pi-simple">
    <div class="container topbar">
        <!-- GAUCHE : réseaux + “Rejoignez nous” centré -->
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

        <!-- CENTRE : LOGO + Since -->
        <div class="brand">
            <!-- Mets le chemin de TON logo -->
            <a href="index.php" class="navbar-brand">
                <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
            </a>
            <div class="tagline">
                <span class="rule" aria-hidden="true"></span>
                <span>Since 1993</span>
                <span class="rule" aria-hidden="true"></span>
            </div>
        </div>

        <!-- DROITE : téléphone -->
        <div class="right-col">
            <i class="fa-solid fa-phone"></i>
            <a class="phone" href="tel:+33749826133">07 49 82 61 33</a>
        </div>
    </div>

    <hr class="divider">

    <!-- NAVIGATION CENTRÉE -->
    <div class="container navrow">
        <ul class="menu" aria-label="Navigation principale">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre Histoire</a></li>
            <li><a href="#catalog">Catalogue</a></li>
            <li><a href="nosMagasins.php">Nos Magasins</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="postuler.php">Postuler</a></li>
        </ul>
    </div>

    <hr class="divider">
</header>

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
                    <video id="promoVideo" preload="metadata" playsinline muted poster="../assets/img/bondy%20.gif">
                        <source src="../assets/img/bondy%20.mp4" type="video/mp4" />
                        Votre navigateur ne supporte pas la vidéo HTML5.
                    </video>
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
                <h2>Nos rayons</h2>
                <div class="sub">Défilement infini — mini-zoom au survol — clics inactifs</div>
            </div>

            <div class="carousel reveal">
                <div class="track-viewport">
                    <div class="track" id="adv-track">
                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://i.pinimg.com/1200x/a9/17/2b/a9172b533641bb9bc8edfccba5973d13.jpg" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#2f7bff"></span><span>Fraîcheur</span></div>
                            <h3>Surgelés</h3>
                            <p>Fraîcheur préservée, prêts en minutes : du congélo à l’assiette.</p>
                            <div class="tags"><span class="tag">Local</span><span class="tag">Saisonnier</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://i.pinimg.com/1200x/bb/b9/3c/bbb93c3d3c6142c00083d6bd40a8e591.jpg" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#b9143f"></span><span>Qualité</span></div>
                            <h3>Boucherie sélection</h3>
                            <p>Viandes حلال, tendreté garantie, découpe du jour et traçabilité.</p>
                            <div class="tags"><span class="tag">Label</span><span class="tag">Traçable</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://i.pinimg.com/1200x/e7/1a/25/e71a25d82e39cf9b377b904333c1ff92.jpg" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#19c37d"></span><span>Prix</span></div>
                            <h3>Produits frais</h3>
                            <p>Fruits croquants, légumes de saison, crèmerie du matin.</p>
                            <div class="tags"><span class="tag">Promo</span><span class="tag">Budget</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://i.pinimg.com/736x/2b/6f/4e/2b6f4e109d3fc84f1dff4cc887a2ec0a.jpg" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#f5a524"></span><span>Prix</span></div>
                            <h3>Produits secs</h3>
                            <p>Épicerie, pâtes, riz, conserves : essentiels à prix mini.</p>
                            <div class="tags"><span class="tag">Rapide</span><span class="tag">Local</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://i.pinimg.com/1200x/79/6c/34/796c34906c9111f02f41a319298a261b.jpg" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#b07cff"></span><span>Gourmand</span></div>
                            <h3>Boissons</h3>
                            <p>Eaux, jus, sodas et packs familiaux à prix doux.</p>
                            <div class="tags"><span class="tag">Fait-maison</span><span class="tag">Fraîcheur</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://i.pinimg.com/736x/7a/05/62/7a05628cfd2a0b571fefb161bf9443c6.jpg" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#b9143f"></span><span>Qualité</span></div>
                            <h3>Emballages</h3>
                            <p>Sacs, barquettes, films : conservez, transportez, protégez.</p>
                            <div class="tags"><span class="tag">Label</span><span class="tag">Traçable</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="https://i.pinimg.com/1200x/79/d6/21/79d6218ff0262f32b1b447e1d0389a4b.jpg" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#b9143f"></span><span>Qualité</span></div>
                            <h3>Hygiènes</h3>
                            <p>Lessive, soins, ménage : propre, frais, impeccable.</p>
                            <div class="tags"><span class="tag">Label</span><span class="tag">Traçable</span></div>
                        </article>

                    </div>

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

    <!-- STRIP défilant -->
    <section class="strip-section">
        <div class="container">
            <div class="strip" aria-label="Galerie défilante">
                <div class="marquee-strip">
                    <div class="track-strip" id="trackStrip">
                        <article class="card-strip"><span class="inner"><img src="../assets/img/DSC09743.JPG" alt="Rayon fruits"/></span></article>
                        <article class="card-strip"><span class="inner"><img src="../assets/img/DJI_0264.JPG" alt="Vue drone"/></span></article>
                        <article class="card-strip"><span class="inner"><img src="../assets/img/DSC09757.JPG" alt="Légumes"/></span></article>
                        <article class="card-strip"><span class="inner"><img src="../assets/img/DSC09764.JPG" alt="Rayon fruits"/></span></article>
                        <article class="card-strip"><span class="inner"><img src="../assets/img/DSC09680.JPG" alt="Clients"/></span></article>
                        <article class="card-strip"><span class="inner"><img src="../assets/img/DSC09686.JPG" alt="Légumes"/></span></article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAGASINS -->
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

                <div class="contact-panel">
                    <h3 class="contact-title">Service client</h3>

                    <div class="info-table">
                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.3 1.77.55 2.61a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.47-1.08a2 2 0 0 1 2.11-.45c.84.25 1.71.43 2.61.55A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <div class="info-label">Téléphone</div>
                        <div class="info-value">07 49 82 61 33 (appel gratuit)</div>

                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/>
                        </svg>
                        <div class="info-label">Email</div>
                        <div class="info-value">parisistambulnogent@gmail.com</div>

                        <svg class="info-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                        <div class="info-label">Horaires</div>
                        <div class="info-value">Lun–Ven : 9h00–18h00</div>
                    </div>

                    <div class="newsletter">
                        <h3 class="contact-title" style="text-decoration-thickness:2px">Newsletter</h3>
                        <div class="sub" style="text-align:center">Recevez nos promos & actus.</div>

                        <!-- Formulaire Brevo -->
                        <form id="newsletterForm"
                              class="news-wrap"
                              action="newsletter.php"
                              method="post"
                              onsubmit="return subscribeNewsletter(event,this)"
                              novalidate>
                            <input class="news-input" type="email" name="email" placeholder="Votre email" required>
                            <input type="text" name="_honey" style="display:none">
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

<footer class="pi-footer">
    <div class="wrap">
        <a href="index.php">
            <img class="brand" src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
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
            <a href="#catalog">Catalogue</a>
            <a href="quiSommesNous.html">À propos</a>
            <a href="postuler.php">Postuler</a>
            <a href="#contact">Contact</a>

        </nav>

        <p class="copyright">
            © <span id="year"></span> Paristanbul — Tous droits réservés.
            <br><br>
        </p>
    </div>
</footer>

<!-- JS externes -->
<script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin></script>
<script defer src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

<script>
    function toast(msg, ok=true){
        const t = document.createElement('div');
        t.style.cssText="position:fixed;right:16px;top:16px;z-index:9999;padding:10px 14px;border-radius:10px;font-weight:700;border:1px solid;box-shadow:0 10px 30px rgba(0,0,0,.25)";
        t.style.background = ok ? 'rgba(16,185,129,.95)' : 'rgba(220,38,38,.95)';
        t.style.color = '#fff';
        t.style.borderColor = ok ? 'rgba(16,185,129,.4)' : 'rgba(220,38,38,.4)';
        t.textContent = msg || (ok ? "Merci ! Veuillez confirmer l'email si demandé." : "Une erreur est survenue.");
        document.body.appendChild(t);
        setTimeout(()=>{ t.style.transition='opacity .35s, transform .35s'; t.style.opacity='0'; t.style.transform='translateY(-6px)'; setTimeout(()=>t.remove(),380); }, 2200);
    }

    async function subscribeNewsletter(e, form){
        e.preventDefault();
        try{
            const res  = await fetch(form.action, { method: 'POST', body: new FormData(form) });
            const json = await res.json().catch(()=>({ok:false,msg:'Réponse invalide'}));
            toast(json.msg || (json.ok ? "Inscription validée" : "Erreur"), !!json.ok);
            if (json.ok) form.reset();
        }catch{
            toast("Impossible de joindre le service.", false);
        }
        return false; // bloque la navigation quoi qu'il arrive
    }
</script>

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

    /* ===== Catalogue ===== */
    (function(){
        const TOTAL_PAGES = 5;
        const PATH = '/Projet-paristanbul/assets/pages';
        const FILENAME = i => String(i).padStart(2,'0') + '.jpg';
        const MOBILE_BREAKPOINT = 768;
        const MIN_W = 480, MAX_W = 1040;

        const pages = Array.from({length: TOTAL_PAGES}, (_,k) => `${PATH}/${FILENAME(k+1)}`);
        pages.forEach(src => { const i = new Image(); i.src = src; });

        let pageFlip, pageAspect = 0.707, pageW = 600, scale = 1, baseScale = 1;

        const stageInner= document.getElementById('stageInner');
        const flipEl    = document.getElementById('flipbook');
        const pageLabel = document.getElementById('pageLabel');

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

    /* ===== Avantages carousel ===== */
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
        function cloneNode(n){ return n.cloneNode(true); }

        function setupClones(){
            clearClones();
            const V=visibleCount();
            const head=originals.slice(-V).map(cloneNode); head.forEach(n=>{ n.dataset.clone='head'; track.insertBefore(n,track.firstChild);});
            const tail=originals.slice(0,V).map(cloneNode); tail.forEach(n=>{ n.dataset.clone='tail'; track.appendChild(n);});
            startIndex=V; index=startIndex; instantTranslate();
        }
        function translate(){ const x=-(index*(cardWidth()+GAP)); track.style.transform=`translateX(${x}px)`; }
        function instantTranslate(){ const t=track.style.transition; track.style.transition='none'; translate(); track.offsetHeight; track.style.transition=t||''; }
        function next(){ index++; translate(); }
        function prev(){ index--; translate(); }

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

        function startAuto(){ stopAuto(); autoplay=setInterval(()=>next(), 3500); }
        function stopAuto(){ if(autoplay) { clearInterval(autoplay); autoplay=null; } }
        root.addEventListener('mouseenter', stopAuto); root.addEventListener('mouseleave', startAuto);

        function init(){ originals=[...track.children].filter(el=>!el.dataset.clone); setupClones(); translate(); startAuto(); }
        init();
        window.addEventListener('resize', ()=>{ originals=[...track.querySelectorAll('.card')].filter(el=>!el.dataset.clone); setupClones(); });
    })();

    /* ===== Strip : pause onglet masqué ===== */
    (function(){ const track = document.getElementById('trackStrip'); document.addEventListener('visibilitychange',()=>{ track.style.animationPlayState = document.hidden ? 'paused' : 'running'; });})();

    /* ===== STORES (images locales + lazy) ===== */
    const storesData = {
        villiers1: { title:'Paristanbul VILLIERS-LE-BEL', image:'../assets/img/magasins/villiers1.jpg', address:'3 avenue des entrepreneurs, VILLIERS-LE-BEL', hours:'Lundi à Dimanche : 08:30-20:00', phone:'01 39 94 12 34', coordinates:[49.0010, 2.3894] },
        villiers2: { title:'Paristanbul VILLIERS-LE-BEL 2', image:'../assets/img/magasins/villiers2.jpg', address:'117 Avenue Pierre Semard, VILLIERS-LE-BEL', hours:'Lundi à Dimanche : 08:30-20:00', phone:'01 39 95 12 34', coordinates:[48.9985, 2.4148] },
        drancy:    { title:'Paristanbul DRANCY', image:'../assets/img/magasins/drancy.jpg', address:'83 avenue Marceau, DRANCY', hours:'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00', phone:'01 48 95 12 34', coordinates:[48.9242, 2.4456] },
        bondy:     { title:'Paristanbul BONDY', image:'../assets/img/magasins/bondy.jpg', address:'116 Av. Gallieni, BONDY', hours:'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00', phone:'01 48 47 12 34', coordinates:[48.9024, 2.4823] },
        villemomble:{ title:'Paristanbul VILLEMOMBLE', image:'../assets/img/magasins/villemomble.jpg', address:'68 ALLEE DU PLATEAU, VILLEMOMBLE', hours:'Lundi à Dimanche : 08:00-20:30', phone:'01 45 28 12 34', coordinates:[48.8844, 2.5103] },
        nogent:    { title:'Paristanbul NOGENT-SUR-OISE', image:'../assets/img/magasins/nogent.jpg', address:'171 Rue Jean Monnet, NOGENT-SUR-OISE', hours:'Lundi à Samedi : 09:30-20:00, Dimanche : 10:00-19:00', phone:'03 44 74 12 34', coordinates:[49.2765, 2.2011] },
        vertsaintdenis:{ title:'Paristanbul VERT-SAINT-DENIS', image:'../assets/img/magasins/vertsaintdenis.jpg', address:'La Fontaine Ronde, VERT-SAINT-DENIS', hours:'Lundi à Dimanche : 08:30-20:30', phone:'01 64 10 12 34', coordinates:[48.6478, 2.6223] }
    };
    let currentMap = null;

    function createStoreContent(storeKey){
        const s = storesData[storeKey];
        return `
  <div class="map-section"><div class="map-container"><div id="map"></div></div></div>
  <div class="info-section">
    <img src="${s.image}" alt="${s.title}" class="store-image" loading="lazy">
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
        setTimeout(()=> initMap(s.coordinates[0], s.coordinates[1], s.title, s.address), 120);
    }
    document.addEventListener('click', (e)=>{
        const btn = e.target.closest('#stores .nav-tab');
        if(!btn) return;
        selectStore(btn.getAttribute('data-store'));
    });
    document.addEventListener('DOMContentLoaded', ()=> setTimeout(()=> selectStore('villiers1'), 140));

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

    /* (Fond animé canvas supprimé : on laisse le fond unique en CSS) */

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





    /* UI extras: progress, smooth anchors, cursor, magnet, ripple, marquee speed */
    (() => {
        const progress = $('#progress');
        const onProg = () => {
            const h = document.documentElement;
            const sc = h.scrollTop, max = h.scrollHeight - h.clientHeight;
            progress.style.width = (max ? (sc/max)*100 : 0) + '%';
        };
        onProg(); addEventListener('scroll', onProg, {passive:true});

        document.addEventListener('click', (e) => {
            const a = e.target.closest('a[href^="#"]');
            if(!a) return;
            const id = a.getAttribute('href');
            const tgt = id && id !== '#' ? document.querySelector(id) : null;
            if(tgt){ e.preventDefault(); tgt.scrollIntoView({behavior:'smooth', block:'start'}); }
        });

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

        addEventListener('click', (e)=>{
            const r=document.createElement('span'); r.className='click-ripple';
            r.style.left=e.clientX+'px'; r.style.top=e.clientY+'px';
            document.body.appendChild(r);
            requestAnimationFrame(()=>{ r.style.transition='transform .5s ease, opacity .5s ease';
                r.style.transform='translate(-50%,-50%) scale(12)'; r.style.opacity='0'; });
            setTimeout(()=>r.remove(),520);
        }, {passive:true});


    })();

    /* === AOS mini: animations d'entrée pour stores, contact, footer === */
    (() => {
        const observer = new IntersectionObserver((entries)=>{
            entries.forEach(e=>{
                if(e.isIntersecting){
                    e.target.classList.add('in');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.15 });

        const add = (sel, anim, stagger=0) => {
            document.querySelectorAll(sel).forEach((el, i)=>{
                el.classList.add('aos');
                el.dataset.anim = anim;
                if (stagger) el.style.setProperty('--aos-delay', `${i*stagger}ms`);
                observer.observe(el);
            });
        };

        /* STORES */
        add('#stores .section-hd', 'rise');
        add('#stores .nav-tabs .nav-tab', 'pop', 60);
        add('#stores .content-area', 'scale');

        /* CONTACT */
        add('#contact .section-hd', 'rise');
        add('#contact .contact-panel', 'rise', 120);

        /* FOOTER */
        add('footer.pi-footer .brand', 'scale');
        add('footer.pi-footer .headline', 'sweep');
        add('footer.pi-footer .social li', 'pop', 50);
        add('footer.pi-footer .footer-nav a', 'rise', 30);
        add('footer.pi-footer .copyright', 'rise', 200);
    })();
</script>



</body>
</html>
