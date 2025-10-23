<?php
session_start();
$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);
$isLoggedIn = !empty($_SESSION['user_id']);
$username   = $_SESSION['user_name'] ?? 'Client';
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
            --black:#0a0c10; --blue:#0b3b8a; --red:#7b0f20;
            --text:#ffffff; --muted:#c9d4ea; --panel:#0f1320; --ring:#2c59ff55;
            --edge:#1b2235; --panel-2:#0e1422;
            --strip-gap: clamp(24px, 3vw, 48px);
            --strip-card: clamp(180px, 20vw, 300px);
            --strip-radius: 18px;
            --strip-border: 5px;
            --strip-speed: 22s;
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
        *{box-sizing:border-box}
        html,body{height:100%}
        html,body{ background:transparent !important; }
        body{
            margin:0; font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;
            color:var(--text); overflow-x:hidden; position:relative;
        }
        a{color:inherit;text-decoration:none}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}

        /* Fond global */
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

        /* ===== Header AVEC fond ===== */
        header{
            position: static;
            background:
                    radial-gradient(600px 300px at 10% 0%, rgba(46,76,151,.18), transparent 60%),
                    radial-gradient(600px 300px at 90% 0%, rgba(214,69,46,.14), transparent 55%),
                    linear-gradient(180deg, #0f1525ee, #0c1223ee);
            border-bottom: 1px solid #141826;
            backdrop-filter: blur(8px);
        }

        /* Bandeau top */
        .marquee{position:relative; overflow:hidden; border-top:1px solid #1b2744; border-bottom:1px solid #1b2744; background: linear-gradient(180deg, rgba(15,21,37,.94), rgba(13,19,33,.88)); backdrop-filter: blur(10px);}
        .marquee__inner{display:flex; gap:40px; padding:10px 0; white-space:nowrap; animation:marquee 22s linear infinite}
        .pill{display:inline-flex; align-items:center; gap:8px; padding:6px 12px; border-radius:999px; background:linear-gradient(145deg,#121a34,#0f162a); border:1px solid #223055; font-size:.92rem}
        .pill .dot{width:8px;height:8px;border-radius:50%;background:conic-gradient(from 90deg,var(--red),var(--blue))}
        @keyframes marquee{from{transform:translateX(0)} to{transform:translateX(-50%)}}

        /* Header simple */
        header.pi-simple{ }
        .pi-simple .topbar{
            display:grid; grid-template-columns: 1fr minmax(200px, 1fr) 1fr;
            align-items:center; gap:16px; padding-block: clamp(18px, 3.5vh, 40px);
        }
        .pi-simple .left-col{display:flex; align-items:flex-start}
        .pi-simple .social-group{display:flex; flex-direction:column; align-items:center; width:max-content}
        .pi-simple .social{display:flex; align-items:center; gap:16px; color:var(--muted)}
        .pi-simple .social a{font-size:18px; color:var(--muted)}
        .pi-simple .social a:hover{color:#fff}
        .pi-simple .join{font-size:13px; color:var(--muted); font-weight:800; margin-top:6px; text-align:center}

        .pi-simple .brand{display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px}
        .pi-simple .brand img{height: clamp(60px, 9vw, 72px)}
        .pi-simple .tagline{display:flex; align-items:center; gap:14px; color:var(--muted); font-size: clamp(13px, 1.3vw, 16px); line-height:1}
        .pi-simple .tagline .rule{width: clamp(58px, 9vw, 92px); height:1px; background:rgba(255,255,255,.06)}

        /* Colonne droite : téléphone + bouton login */
        .pi-simple .right-col{
            display:flex; flex-direction:column; align-items:flex-end; gap:10px; font-weight:800
        }
        .pi-simple .right-col .phone-row{ display:flex; align-items:center; gap:10px; }
        .pi-simple .right-col i{color:#c9d4ea}
        .pi-simple .phone{font-size: clamp(14px, 1.2vw, 18px); color:#e7ecf5}

        .pi-simple .divider{border:0; border-top:1px solid #141a26; margin:0}
        .pi-simple .navrow{padding:12px 0; position: relative;}
        .pi-simple .menu{display:flex; justify-content:center; gap:28px; list-style:none; margin:0; padding:0}
        .pi-simple .menu a{ font-weight:800; font-size:14px; color:#c9d4ea; letter-spacing:.06em; text-transform:uppercase; }
        .pi-simple .menu a:hover, .pi-simple .menu a.is-active{color:#ffffff}

        @media (max-width:720px){
            .pi-simple .topbar{grid-template-columns:1fr; row-gap:10px; text-align:center}
            .pi-simple .left-col{justify-content:center}
            .pi-simple .menu{flex-wrap:wrap; gap:18px}
            .pi-simple .right-col{ align-items:center; }
        }

        /* ===== BOUTON LOGIN v2 ===== */
        .pi-simple .right-col .btn-login{
            --ring: rgba(44,89,255,.28);
            --bg1:#0f1833; --bg2:#1c2b59;
            --bd1:#3a58ff; --bd2:#e5473a;

            display:inline-flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            gap:8px;
            padding:14px 18px;
            min-width:130px;
            text-align:center;
            border-radius:16px;

            background:
                    linear-gradient(180deg, var(--bg2), var(--bg1)) padding-box,
                    linear-gradient(135deg, var(--bd1), var(--bd2)) border-box;
            border:1px solid transparent;
            color:#eaf0ff;
            font-weight:800;
            letter-spacing:.02em;
            text-transform:uppercase;

            box-shadow:
                    0 12px 26px rgba(0,0,0,.35),
                    inset 0 1px 0 rgba(255,255,255,.06);

            transition:
                    transform .14s cubic-bezier(.2,.9,.2,1.2),
                    box-shadow .22s ease,
                    filter .22s ease,
                    background .22s ease;
        }
        .pi-simple .right-col .btn-login i{
            font-size:18px;
            line-height:1;
            width:40px; height:40px;
            border-radius:999px;
            display:grid; place-items:center;
            background: radial-gradient(120% 120% at 30% 20%, #2a3f86 0%, #182650 45%, #0f1833 100%);
            box-shadow:
                    inset 0 1px 0 rgba(255,255,255,.08),
                    0 6px 18px rgba(58,88,255,.25);
        }
        .pi-simple .right-col .btn-login span{
            display:block;
            line-height:1.05;
            font-size:12.5px;
            letter-spacing:.06em;
            opacity:.95;
        }
        .pi-simple .right-col .btn-login:hover{
            transform:translateY(-1px);
            box-shadow: 0 16px 34px rgba(0,0,0,.45), 0 0 0 3px var(--ring);
            filter:brightness(1.04);
        }
        .pi-simple .right-col .btn-login:active{
            transform:translateY(0) scale(.995);
            filter:brightness(.98);
        }
        .pi-simple .right-col .btn-login:focus-visible{
            outline:none;
            box-shadow: 0 0 0 3px rgba(58,88,255,.35), 0 10px 24px rgba(0,0,0,.35);
        }
        @media (max-width:720px){
            .pi-simple .right-col .btn-login{ padding:12px 14px; min-width:118px; border-radius:14px; }
            .pi-simple .right-col .btn-login i{ width:36px; height:36px; font-size:17px; }
        }

        /* ===== HERO ===== */
        #hero{position:relative; padding:64px 0 40px;}
        .hero-wrap{display:grid; grid-template-columns:1.1fr .9fr; gap:40px; align-items:center}
        .eyebrow{font-size:.9rem; color:var(--muted); letter-spacing:.2em; text-transform:uppercase}
        h1{font-size:clamp(32px,4.6vw,58px); line-height:1.04; margin:.3em 0;}
        .lead{font-size:1.1rem; color:#e3eaff}
        .cta-row{display:flex; gap:12px; margin-top:18px}
        .btn{display:inline-flex; align-items:center; gap:10px; padding:12px 16px; border-radius:14px; border:1px solid #1f2842; background:linear-gradient(145deg,#151c32,#0f1424); font-weight:700}
        .btn.primary{background:linear-gradient(145deg,#102453,var(--blue)); border-color:#0f2b6a}

        /* === HERO - vidéo dimensions stables (sans overlay) === */
        .video-card{
            --vid-w: 720px;
            --ratio: 16/9;
            width: min(100%, var(--vid-w));
            aspect-ratio: var(--ratio);
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: #0b1020;
            box-shadow: 0 20px 50px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06);
            border: 1px solid #1e2740;
            margin-inline: auto;
        }
        .yt-wrap, .yt-wrap iframe{ width:100%; height:100%; display:block; border:0; }

        @media (max-width:980px){ .hero-wrap{grid-template-columns:1fr; gap:26px} .video-card{ border-radius:14px } }

        /* Sections, Catalogue, Avantages, Strip, Stores, Contact, Footer */
        section{padding:64px 0; background:transparent !important;}
        .section-hd{display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:20px}
        .section-hd h2{font-size:clamp(24px,3.3vw,40px); margin:0}
        .sub{color:var(--muted)}
        .reveal{opacity:0; transform:translateY(16px) scale(.98); filter:saturate(.9); transition:opacity .5s ease, transform .5s ease, filter .5s ease}
        .reveal.is-visible{opacity:1; transform:none; filter:none}

        /* Catalogue */
        #catalog { padding: 28px 0; }
        #catalog .section-hd { margin-bottom: 8px; }
        #catalog .catalog-app{ display:flex; flex-direction:column; gap:10px; background:#0f1525; border:1px solid #1d2742; border-radius:18px; box-shadow:0 16px 48px rgba(0,0,0,.35); padding:12px;}
        #catalog .toolbar{ background:#121a34 !important; border-color:#1f2942 !important; backdrop-filter:none !important; border-radius:14px; overflow:hidden;}
        #catalog .toolbar .row{ display:flex; gap:.45rem; align-items:center; flex-wrap:wrap; padding:.45rem .75rem }
        #catalog .brand{font-weight:800; letter-spacing:.2px; display:flex; gap:.6rem; align-items:center}
        #catalog .brand .dot{ width:.6rem; height:.6rem; border-radius:999px; background:#3aa0ff; box-shadow:0 0 0 4px #3aa0ff22; }
        #catalog .btn{ appearance:none; border:1px solid #1f2942; background:#131a2a; color:var(--text); border-radius:999px; padding:.45rem .7rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; font-weight:800; box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004; transition:transform .1s ease, border-color .2s ease, background .2s ease; }
        #catalog .btn:hover{ border-color:#2a3659; background:#0f1626 }
        #catalog .btn.icon{ padding:.42rem .58rem }
        #catalog .sep{ width:1px; height:28px; background:#223052; opacity:.6; margin:0 .25rem }
        #catalog .metric{ margin-left:auto; display:inline-flex; align-items:center; gap:.5rem; font-weight:800; color:#cfe0ff; background:#0e1423; border:1px solid #1f2942; padding:.35rem .6rem; border-radius:.75rem; box-shadow:0 1px 0 #ffffff0d inset; }
        #catalog .stage{ position:relative; border:1px solid var(--edge); background:#0e1423 !important; border-radius:14px; box-shadow:0 18px 46px #0009, inset 0 1px 0 #ffffff12, inset 0 0 0 1px #0008; display:grid; place-items:center; overflow:hidden; }
        #catalog .stage::after{ content:""; position:absolute; inset:0; pointer-events:none; background:radial-gradient(1200px 560px at 50% -10%, transparent 0%, #00000022 60%, #00000055 100%) }
        #stageInner{ background:#0e1423 !important; margin:0 !important; }
        #flipbook{ width:min(92vw,1040px); height:88vh; background:#0e1423 !important; }
        @media (max-width:768px){ #flipbook{ height:92dvh } #catalog .metric{ display:none } }

        /* Avantages carousel */
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

        /* Strip */
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

        /* Stores (onglets + bloc fusion) */
        #stores .nav-tabs{ display:flex; justify-content:center; gap:12px; margin-bottom:0; flex-wrap:wrap; position:relative;
            background: linear-gradient(180deg, #121a32, #0f172c) !important; border: 1px solid #1d2742 !important;
            border-bottom:none !important; border-radius:18px 18px 0 0 !important; padding:10px 12px !important; box-shadow: 0 10px 28px rgba(0,0,0,.28); }
        #stores .nav-tab{ background: linear-gradient(145deg, #111a31, #0e1528) !important; border: 1px solid #223055 !important; color:#e7ecf5;
            font-weight:800; padding:.75rem 1.1rem; cursor:pointer; border-radius:999px; transition:all .25s ease; display:inline-flex; align-items:center; gap:8px; }
        #stores .nav-tab:hover{ background: linear-gradient(145deg, #15203d, #101a33); border-color: #2a3659; color:#fff; }
        #stores .nav-tab.active{ background: linear-gradient(145deg, #1c305c, #2a3d73) !important; border-color:#2a3d73 !important; color:#fff; box-shadow: inset 0 1px 0 rgba(255,255,255,.07), 0 8px 22px rgba(0,0,0,.30); }
        #stores .content-area{ background: linear-gradient(180deg, #10182e, #0d1529) !important; border: 1px solid #1d2742 !important; border-top:none !important;
            border-radius:0 0 18px 18px !important; padding:16px !important; box-shadow: 0 18px 48px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05);
            min-height:500px; display:grid; grid-template-columns:1.2fr .8fr; gap:0 !important; align-items:stretch; position:relative; overflow:hidden; }
        #stores .content-area::after{ content:""; position:absolute; top:16px; bottom:16px; left: calc(100% * 1.2 / (1.2 + .8)); width:1px; background:#233055; opacity:.9; pointer-events:none; }
        #stores .map-section, #stores .map-container, #stores #map{ border:0 !important; border-radius:12px !important; background:transparent !important; }
        #stores .map-container{ width:100%; height:100%; min-height:420px }
        #stores .info-section{ background:transparent !important; padding:18px !important; gap:14px !important; display:flex; flex-direction:column; }
        #stores .store-image{ width:100%; height:200px; border-radius:12px !important; object-fit:cover; border:0 !important; }
        #stores .store-title{ font-size:1.6rem; font-weight:800; margin:.2rem 0; background:linear-gradient(45deg,#8b1a1a,#1c305c); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
        #stores .info-item{ display:flex; align-items:center; gap:.8rem; padding:.7rem .8rem; background:#0e1528; border:1px solid #233055; border-radius:12px; }
        #stores .icon{ width:20px; height:20px; fill:#A32929 }
        #stores .actions{ display:flex; gap:1rem; margin-top:auto }
        #stores .btn{ flex:1; justify-content:center; border-radius:25px }
        #stores .btn-primary{ background:linear-gradient(45deg,#A32929,#8B1A1A); color:#fff }
        #stores .btn-secondary{ background:#1c305c; border:1px solid #233055; color:#fff }
        @media (max-width:768px){
            #stores .content-area{ grid-template-columns:1fr; padding:12px !important; }
            #stores .content-area::after{ display:none; }
            #stores .info-section{ border-top:1px solid #233055; margin-top: 12px; padding-top: 16px; }
            #stores .map-container{ min-height:320px }
        }

        /* Contact */
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

        /* Footer */
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
        .pi-footer .headline .line{ height:4px; width:260px; border-radius:2px; background:var(--pi-red, #D6452E); transform-origin:center; transform:scaleX(0); transition:transform .6s cubic-bezier(.22,.84,.3,1) }
        @media (max-width:720px){ .pi-footer .headline .line{ width:20vw } .pi-footer .headline h2{ font-size:20px } }
        .pi-footer .social{ list-style:none; display:flex; justify-content:center; align-items:center; gap:14px; padding:0; margin:14px 0 20px; }
        .pi-footer .social a{ width:42px; height:42px; display:grid; place-items:center; background:#101733; color:#cfe0ff; border-radius:50%; border:1px solid #1e2740; font-size:18px; transition:transform .2s ease, background .2s ease, border-color .2s ease, color .2s ease; }
        .pi-footer .social a:hover{ background: linear-gradient(145deg, var(--pi-blue,#2E4C97), var(--pi-red,#D6452E)); border-color:#2a3659; color:#fff; transform:translateY(-2px); }
        .pi-footer .footer-nav{ display:flex; flex-wrap:wrap; justify-content:center; gap:26px 30px; padding:12px 0 8px; margin:0 auto 12px; }
        .pi-footer .footer-nav a{ text-decoration:none; color:#e9f1ff; font-weight:800; font-size:14px; letter-spacing:.04em; text-transform:uppercase; transition:color .2s ease; }
        .pi-footer .footer-nav a:hover{ color:var(--pi-red,#D6452E) }
        .pi-footer .copyright{ margin:6px 0 0; font-size:12px; color:var(--muted); user-select:none; }

        /* AOS mini */
        .aos{opacity:0; will-change:transform,opacity,clip-path}
        .aos.in{opacity:1}
        @keyframes aos-rise   {from{transform:translateY(22px);opacity:0} to{transform:none;opacity:1}}
        @keyframes aos-scale  {from{transform:scale(.96);opacity:0} to{transform:scale(1);opacity:1}}
        @keyframes aos-sweep  {from{clip-path:inset(0 50% 0 50%);opacity:0} to{clip-path:inset(0 0 0 0);opacity:1}}
        @keyframes aos-pop    {0%{transform:scale(.6);opacity:0} 70%{transform:scale(1.06)} 100%{transform:scale(1);opacity:1}}
        [data-anim="rise"].in  {animation:aos-rise .65s cubic-bezier(.22,.84,.3,1) both;  animation-delay:var(--aos-delay,0ms)}
        [data-anim="scale"].in {animation:aos-scale .55s ease-out both;                animation-delay:var(--aos-delay,0ms)}
        [data-anim="sweep"].in {animation:aos-sweep .70s ease-out both;                animation-delay:var(--aos-delay,0ms)}
        [data-anim="pop"].in   {animation:aos-pop .45s cubic-bezier(.2,.9,.2,1.2) both;animation-delay:var(--aos-delay,0ms)}
        /* Donne une vraie taille au canvas Leaflet */
        #stores .map-container { position: relative; }
        #stores #map { width: 100%; height: 100%; min-height: 420px; }
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

<!-- Fond -->
<div id="page-bg" aria-hidden="true"></div>
<div class="pi-orbs" aria-hidden="true">
    <span class="orb blue a"></span>
    <span class="orb red  b"></span>
    <span class="orb blue c"></span>
    <span class="orb red  d"></span>
</div>

<div class="progress" id="progress"></div>

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
                <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
            </a>
            <div class="tagline">
                <span class="rule" aria-hidden="true"></span>
                <span>Since 1993</span>
                <span class="rule" aria-hidden="true"></span>
            </div>
        </div>

        <!-- Droite : téléphone + bouton login -->

        <div class="right-col">

            <?php if (!empty($_SESSION['user_id'])): ?>
                <!-- Connecté : bouton Déconnexion -->
                <a class="btn-login magnet" href="../deconnexion.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Se déconnecter</span>
                </a>
            <?php else: ?>
                <!-- Non connecté : bouton Connexion -->
                <a class="btn-login magnet" href="pageConnexion.php">
                    <i class="fa-regular fa-user"></i>
                    <span>Se connecter</span>
                </a>
            <?php endif; ?>
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
            <li><a href="index.php" class="is-active">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre Histoire</a></li>
            <li><a href="#catalog">Catalogue</a></li>
            <li><a href="#stores">Nos Magasins</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="postuler.php">Postuler</a></li>
        </ul>
    </div>

    <hr class="divider">
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

            <!-- Vidéo : AUTOPLAY + LOOP (muted pour compatibilité navigateur) -->
            <div class="video-card reveal" data-parallax data-speed="0.06">
                <div class="yt-wrap">
                    <iframe
                            id="ytFrame"
                            src="https://www.youtube-nocookie.com/embed/-AeizsAsJHA?controls=1&playsinline=1&modestbranding=1&rel=0&showinfo=0&autoplay=1&mute=1&loop=1&playlist=-AeizsAsJHA"
                            title="Paristanbul Promo"
                            allow="autoplay; fullscreen; picture-in-picture; encrypted-media"
                            referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- CATALOGUE -->
    <section id="catalog">
        <div class="container">
            <div class="section-hd reveal">
                <h2>Catalogue interactif</h2>
                <div class="sub"></div>
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
                <div class="sub"></div>
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
                            <div class="thumb"><img src="../assets/img/DSC09743.JPG" alt=""></div>
                            <div class="meta"><span class="dot" style="background:#b9143f"></span><span>Qualité</span></div>
                            <h3>Boucherie sélection</h3>
                            <p>Viandes حلال, tendreté garantie, découpe du jour et traçabilité.</p>
                            <div class="tags"><span class="tag">Label</span><span class="tag">Traçable</span></div>
                        </article>

                        <article class="card tilt" tabindex="0">
                            <div class="thumb"><img src="../assets/img/DSC09757.JPG" alt=""></div>
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
                            <div class="thumb"><img src="../assets/img/DJI_0264.JPG" alt=""></div>
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
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0  0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.3 1.77.55 2.61a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.47-1.08a2 2 0 0 1 2.11-.45c.84.25 1.71.43 2.61.55A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <div class="info-label">Téléphone</div>
                        <div class="info-value">+33 7 49 82 61 33 (appel gratuit)</div>
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
            <a href="index.php#stores">Nos magasins</a>
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
        return false;
    }
</script>

<script>
    const $ = (s,el=document)=>el.querySelector(s);
    const $$ = (s,el=document)=>[...el.querySelectorAll(s)];

    // Reveal on scroll
    const io=new IntersectionObserver((ents)=>{ ents.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('is-visible'); io.unobserve(e.target);} }); },{threshold:.15});
    $$('.reveal').forEach(n=>io.observe(n));

    // Parallax léger
    const parallaxNodes=$$('[data-parallax]');
    const onScrollParallax=()=>{ const y=window.scrollY||document.documentElement.scrollTop; parallaxNodes.forEach(n=>{ const sp=parseFloat(n.dataset.speed||'0.05'); n.style.transform=`translateY(${y*sp}px)`; });};
    onScrollParallax(); addEventListener('scroll', onScrollParallax, {passive:true});

    /* ====== Catalogue (PageFlip) ====== */
    (function(){
        const PATH = '/Projet-Paristanbul/assets/pages';
        const FILENAME = i => String(i).padStart(2,'0') + '.jpg';
        const BUST = `?v=${Date.now()}`;
        const MOBILE_BREAKPOINT = 768;
        const MIN_W = 480, MAX_W = 1040;


// ordre personnalisé : on retire 2
        const PAGES_ORDER = [1, 3, 4, 5, 6, 7];
        const pages = PAGES_ORDER.map(n => `${PATH}/${FILENAME(n)}${BUST}`);

// TOTAL_PAGES dérivé automatiquement
        const TOTAL_PAGES = pages.length;
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
        window.addEventListener('resize', ()=>{
            clearTimeout(rt);
            rt = setTimeout(()=>{ const current = pageFlip ? pageFlip.getCurrentPageIndex() : 0; initFlip(current); }, 150);
        });

        if(document.readyState!=='loading') initFlip(0); else window.addEventListener('load', ()=> initFlip(0));
    })();

    /* ====== Avantages carousel ====== */
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

    /* Strip pause onglet masqué + duplication */
    (function () {
        const track = document.getElementById('trackStrip');
        document.addEventListener('visibilitychange',()=>{ if(track) track.style.animationPlayState = document.hidden ? 'paused' : 'running'; });
        if (!track) return;
        const clones = [...track.children].map(n => { const c = n.cloneNode(true); c.setAttribute('aria-hidden', 'true'); return c; });
        clones.forEach(c => track.appendChild(c));
        track.style.willChange = 'transform';
        track.style.backfaceVisibility = 'hidden';
    })();

    /* Stores data + map */
    const storesData = {
        villiers1: {
            title: 'Paristanbul VILLIERS-LE-BEL',
            image: '/Projet-Paristanbul/assets/img/magasins/villiers1.jpg',
            address: '3 avenue des entrepreneurs, VILLIERS-LE-BEL',
            hours: 'Lundi à Dimanche : 08:30-20:00',
            phone: '+33 7 49 82 61 33',
            coordinates: [49.0010, 2.3894]
        },
        villiers2: {
            title: 'Paristanbul VILLIERS-LE-BEL 2',
            image: '/Projet-Paristanbul/assets/img/magasins/villiers2.jpg',
            address: '117 Avenue Pierre Semard, VILLIERS-LE-BEL',
            hours: 'Lundi à Dimanche : 08:30-20:00',
            phone: '+33 7 49 82 61 33',
            coordinates: [48.9985, 2.4148]
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
            address: '68 ALLEE DU PLATEAU, VILLEMOMBLE',
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
        document.querySelectorAll('#stores .nav-tab').forEach(t=>t.classList.remove('active'));
        document.querySelector(`#stores .nav-tab[data-store="${key}"]`).classList.add('active');
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


    /* AOS mini */
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

        add('#stores .section-hd', 'rise');
        add('#stores .nav-tabs .nav-tab', 'pop', 60);
        add('#stores .content-area', 'scale');

        add('#contact .section-hd', 'rise');
        add('#contact .contact-panel', 'rise', 120);

        add('footer.pi-footer .brand', 'scale');
        add('footer.pi-footer .headline', 'sweep');
        add('footer.pi-footer .social li', 'pop', 50);
        add('footer.pi-footer .footer-nav a', 'rise', 30);
        add('footer.pi-footer .copyright', 'rise', 200);
    })();
</script>

</body>
</html>
