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
    <title>Paristanbul — Nos magasins</title>
    <meta name="description" content="Trouvez le magasin Paristanbul le plus proche : adresses, horaires, itinéraire, appel direct, et services disponibles." />

    <!-- Fonts + Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin />
    <!-- PageFlip css pas nécessaire ici mais on laisse pour homogénéité visuelle globale -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css">

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
            --sites-t:560ms; /* durée transition slider magasins */
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        html,body{ background:transparent !important; }
        body{ margin:0; font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial; color:var(--text); overflow-x:hidden; position:relative;}
        a{color:inherit;text-decoration:none}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}

        /* Fonds */
        #page-bg{ position:fixed; inset:0; z-index:-2; pointer-events:none; background:var(--page-bg); }
        .pi-orbs{ position:fixed; inset:0; z-index:-1; pointer-events:none; overflow:hidden; }
        .pi-orbs .orb{ position:absolute; width:48vmax; height:48vmax; border-radius:9999px; filter:blur(80px); opacity:.75; mix-blend-mode:screen; will-change:transform;}
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
            position: static;
            background:
                    radial-gradient(600px 300px at 10% 0%, rgba(46,76,151,.18), transparent 60%),
                    radial-gradient(600px 300px at 90% 0%, rgba(214,69,46,.14), transparent 55%),
                    linear-gradient(180deg, #0f1525ee, #0c1223ee);
            border-bottom: 1px solid #141826;
            backdrop-filter: blur(8px);
        }
        .pi-simple .topbar{display:grid; grid-template-columns: 1fr minmax(200px, 1fr) 1fr; align-items:center; gap:16px; padding-block: clamp(18px, 3.5vh, 40px);}
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
        .pi-simple .right-col{ display:flex; flex-direction:column; align-items:flex-end; gap:10px; font-weight:800 }
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

        .btn-login{
            --ring: rgba(44,89,255,.28);
            --bg1:#0f1833; --bg2:#1c2b59; --bd1:#3a58ff; --bd2:#e5473a;
            display:inline-flex; flex-direction:column; align-items:center; justify-content:center; gap:8px;
            padding:14px 18px; min-width:130px; text-align:center; border-radius:16px;
            background: linear-gradient(180deg, var(--bg2), var(--bg1)) padding-box, linear-gradient(135deg, var(--bd1), var(--bd2)) border-box;
            border:1px solid transparent; color:#eaf0ff; font-weight:800; letter-spacing:.02em; text-transform:uppercase;
            box-shadow: 0 12px 26px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06);
            transition: transform .14s cubic-bezier(.2,.9,.2,1.2), box-shadow .22s ease, filter .22s ease, background .22s ease;
        }
        .btn-login i{ font-size:18px; line-height:1; width:40px; height:40px; border-radius:999px; display:grid; place-items:center;
            background: radial-gradient(120% 120% at 30% 20%, #2a3f86 0%, #182650 45%, #0f1833 100%);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 6px 18px rgba(58,88,255,.25); }
        .btn-login:hover{ transform:translateY(-1px); box-shadow: 0 16px 34px rgba(0,0,0,.45), 0 0 0 3px var(--ring); filter:brightness(1.04); }
        .btn-login:active{ transform:translateY(0) scale(.995); filter:brightness(.98); }

        /* Section générique page */
        main section{padding:48px 0}
        .section-hd{display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:16px}
        .section-hd h1,.section-hd h2{margin:0}
        .sub{color:var(--muted)}

        /* ====== VIDEO CARD (intro) ====== */
        .video-wrap{ display:flex; justify-content:center; padding:28px 0 12px; }
        .video-card{
            position:relative;
            width:100%;
            max-width:820px;
            border-radius:18px;
            background:linear-gradient(180deg,#0e1424,#0b111f);
            border:1px solid #1c2743;
            overflow:hidden;
            box-shadow:0 14px 36px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05);
            transform:translateY(8px);
            opacity:0;
            transition:transform .6s cubic-bezier(.2,.8,.2,1), opacity .6s ease, box-shadow .3s ease;
        }
        .video-card.reveal.is-in{ transform:translateY(0); opacity:1; }
        .video-card:hover{ box-shadow:0 18px 48px rgba(0,0,0,.42), 0 0 0 3px rgba(46,89,255,.12); }
        .video-card::before{
            content:""; position:absolute; inset:0;
            border-radius:inherit; padding:1px;
            background:conic-gradient(from 0deg, #2E4C97, #D6452E, #2E4C97);
            -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite:xor; mask-composite:exclude;
            animation:spin 10s linear infinite;
            opacity:.3;
        }
        @keyframes spin{ to{ transform:rotate(1turn); } }
        .video-glow{
            position:absolute; inset:-20%;
            background:radial-gradient(60% 60% at 20% 80%, rgba(46,76,151,.25), transparent 60%),
            radial-gradient(60% 60% at 80% 20%, rgba(214,69,46,.18), transparent 60%);
            filter:blur(24px); pointer-events:none; opacity:.7;
        }
        .video-frame{ position:relative; aspect-ratio:16/9; background:#0b1323; overflow:hidden; }
        .video-frame iframe{
            position:absolute; inset:0; width:100%; height:100%; border:0; display:block;
        }
        .video-caption{
            display:flex; align-items:center; gap:10px;
            padding:12px 14px;
            border-top:1px solid rgba(255,255,255,.05);
            color:#cfe0ff; font-weight:800; letter-spacing:.02em;
        }
        .video-caption i{ color:#ff4b4b; }
        @media (prefers-reduced-motion:no-preference){
            .video-card.reveal{ transform:translateY(14px); opacity:0; }
            .video-card.reveal.is-in{ transform:translateY(0); opacity:1; }
        }

        /* ====== SLIDER "Nos magasins" (pile 3 cartes animées) ====== */
        .pi-sites{
            display:grid;
            grid-template-columns: 1.05fr 1fr;
            align-items:center;
            gap: clamp(24px, 5vw, 80px);
            min-height: 62vh;
            position:relative;
        }
        .pi-sites__left{ position:relative; z-index:5; }
        .pi-sites__stack{ position:relative; z-index:1; min-height: clamp(360px, 58vh, 640px); isolation:isolate; }

        .pi-sites__left .eyebrow{
            letter-spacing:.15em;
            text-transform:uppercase;
            color: var(--muted);
            font-weight:800;
            font-size: clamp(12px, .95vw, 14px);
            margin:0 0 10px 0;
        }
        .pi-sites__left .title{
            font-size: clamp(28px, 6vw, 76px);
            line-height:.95;
            color:#e6ecf5;
            margin:0 0 24px 0;
            font-weight:800;
            -webkit-text-stroke: 1.4px rgba(0,0,0,.75);
            paint-order: stroke fill;
            text-shadow:none!important;
            -webkit-font-smoothing: antialiased;
            text-rendering: geometricPrecision;
            will-change: opacity, transform, filter, letter-spacing;
        }
        @supports not (-webkit-text-stroke: 1px black){
            .pi-sites__left .title{
                text-shadow:
                        -1px 0 0 rgba(0,0,0,.85),
                        1px 0 0 rgba(0,0,0,.85),
                        0 -1px 0 rgba(0,0,0,.85),
                        0  1px 0 rgba(0,0,0,.85);
            }
        }
        @media (max-width:720px){
            .pi-sites__left .title{ -webkit-text-stroke: 1.1px rgba(0,0,0,.75); }
        }
        .title-appear{ animation: piTitleIn .60s cubic-bezier(.22,.61,.36,1); }
        @keyframes piTitleIn{
            0%{ opacity:0; transform:translateY(12px) scale(.98); filter:blur(2px); letter-spacing:.02em; }
            60%{ opacity:1; transform:translateY(0) scale(1); filter:none; }
            100%{ opacity:1; transform:none; letter-spacing:0; }
        }
        @media(prefers-reduced-motion:reduce){ .title-appear{ animation:none } }

        .pi-sites__nav{ display:flex; gap:14px; margin-top: clamp(16px, 3vh, 40px); }
        .pi-sites__nav .btn{
            width:44px; height:44px; border-radius:999px; cursor:pointer;
            background:#111729; border:1px solid rgba(255,255,255,.14);
            display:grid; place-items:center; color:#e6edff;
            transition: transform .12s ease, border-color .2s ease, background .2s ease;
        }
        .pi-sites__nav .btn:hover{ border-color:#2a3d73; background:#162038; }
        .pi-sites__nav .btn:active{ transform: translateY(1px); }

        .pi-sites__card{
            position:absolute; inset:auto 0 0 auto;
            width: min(52vw, 760px); height: 80%;
            border-radius: 12px; overflow:hidden; background:#1a2032;
            border:1px solid rgba(255,255,255,.06);
            box-shadow: 0 22px 60px rgba(0,0,0,.28);
            transform-origin: center center;
            transition: transform var(--sites-t) cubic-bezier(.22,.8,.24,1), opacity var(--sites-t) ease;
            z-index:0;
        }
        .pi-sites__card img{ width:100%; height:100%; object-fit:cover; display:block; }

        /* positions */
        .pi-role-left  { transform: translate(-12vw, 6vh) scale(.66); z-index:1; opacity:.9; }
        .pi-role-center{ transform: translate(0, 0)        scale(1);    z-index:3; opacity:1; }
        .pi-role-right { transform: translate(14vw,-3vh)   scale(.62);  z-index:1; opacity:.9; }

        /* états en mouvement */
        .pi-shift-next .pi-role-left{   transform: translate(-26vw, 12vh) scale(.56); opacity:0; }
        .pi-shift-next .pi-role-center{ transform: translate(-12vw, 6vh)  scale(.66); }
        .pi-shift-next .pi-role-right{  transform: translate(0,0)         scale(1); }

        .pi-shift-prev .pi-role-right{  transform: translate(24vw, -12vh) scale(.54); opacity:0; }
        .pi-shift-prev .pi-role-center{ transform: translate(14vw, -3vh)  scale(.62); }
        .pi-shift-prev .pi-role-left{   transform: translate(0,0)         scale(1); }

        /* z-index pendant mouvement : la carte entrante passe au-dessus */
        .pi-shift-next .pi-role-right{  z-index:5; }
        .pi-shift-next .pi-role-center{ z-index:2; }
        .pi-shift-prev .pi-role-left{   z-index:5; }
        .pi-shift-prev .pi-role-center{ z-index:2; }

        /* clics seulement sur la centrale */
        .pi-role-left, .pi-role-right{ pointer-events:none; }
        .pi-role-center{ pointer-events:auto; }

        /* Légende */
        .pi-legend{
            position:absolute; left:22px; bottom:22px; z-index:6;
            display:flex; align-items:center; gap:12px;
            color:#fff; font-size: clamp(18px, 2.2vw, 40px); font-weight:800;
            text-shadow: 0 2px 10px rgba(0,0,0,.35), 0 6px 30px rgba(0,0,0,.45);
            pointer-events:none;
        }
        .pi-legend svg{ display:none !important; }

        @media (max-width:980px){
            .pi-sites{ grid-template-columns:1fr; }
            .pi-sites__stack{ order:-1; min-height: 54vh; }
            .pi-sites__card{ width: 88vw; }
            .pi-role-left{  transform: translate(-14vw, 6vh) scale(.7); }
            .pi-role-right{ transform: translate(14vw, -4vh) scale(.68); }
        }

        /* ====== Bandeau ville défilante ====== */
        .marquee{
            position:relative; overflow:hidden;
            border-top:1px solid #1b2744; border-bottom:1px solid #1b2744;
            background:linear-gradient(180deg, rgba(15,21,37,.94), rgba(13,19,33,.88));
            --gap: 40px;
            --speed: 22s;
        }
        .marquee__inner{
            display:flex; gap:var(--gap); padding:10px 0;
            white-space:nowrap; width:max-content;
            animation:marquee var(--speed) linear infinite;
        }
        @keyframes marquee{
            from{ transform:translateX(0) }
            to  { transform:translateX(-50%) }
        }
        .pill{display:inline-flex; align-items:center; gap:8px; padding:6px 12px;
            border-radius:999px; background:linear-gradient(145deg,#121a34,#0f162a);
            border:1px solid #223055; font-size:.92rem}
        .pill .dot{width:8px;height:8px;border-radius:50%;background:conic-gradient(from 90deg,var(--red),var(--blue))}
        .marquee:hover .marquee__inner{ animation-play-state:paused; }
        @media (prefers-reduced-motion:reduce){
            .marquee__inner{ animation:none }
        }

        /* ====== Section magasins principale ====== */
        .controls{
            display:flex; gap:10px; flex-wrap:wrap; align-items:center;
            background: linear-gradient(180deg,#121a32,#0f172c); border:1px solid #1d2742; border-radius:16px; padding:12px; box-shadow:0 10px 28px rgba(0,0,0,.28);
        }
        .chip{ background:#101733; border:1px solid #1e2740; color:#cfe0ff; border-radius:12px; padding:10px 12px; display:inline-flex; align-items:center; gap:8px; font-weight:700; }
        .chip input[type="checkbox"]{accent-color:#A32929; width:16px; height:16px}
        .field{ display:flex; align-items:center; gap:8px; background:#0f162a; border:1px solid #223055; border-radius:12px; padding:10px 12px; }
        .field input,.field select{ background:transparent; border:0; outline:0; color:#eaf0ff; font:600 14px/1 "Plus Jakarta Sans",system-ui; min-width:160px }
        .btn{ display:inline-flex; align-items:center; gap:8px; border:1px solid #223055; background:#131a2a; color:#fff; border-radius:12px; padding:10px 12px; font-weight:800; cursor:pointer; }
        .btn.primary{ background:linear-gradient(145deg,#102453,var(--blue)); border-color:#0f2b6a }
        .btn.red{ background:linear-gradient(145deg,#d26043,#8b1f22); border-color:#8b1f22 }

        .stats{
            display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:12px;
        }
        .stat{
            background: linear-gradient(180deg,#121826,#0e1422);
            border: 1px solid #1e2740; border-radius: 16px; padding:14px 16px;
            display:flex; align-items:center; justify-content:space-between; gap:12px;
        }
        .stat .big{ font-size: clamp(20px,3vw,28px); font-weight:800 }

        .layout{
            margin-top:16px;
            background: linear-gradient(180deg, #10182e, #0d1529);
            border:1px solid #1d2742; border-radius:18px; overflow:hidden;
            box-shadow:0 18px 48px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05);
            display:grid; grid-template-columns:1.2fr .8fr;
            height:420px;
        }
        .map-pane{ position:relative; }
        #map{ position:absolute; inset:0; }
        .list-pane{
            height:100%;
            overflow:auto;
        }

        @media (max-width:980px){
            .layout{ grid-template-columns:1fr; height:320px; }
            .map-pane{ height:100%; }
        }

        .card-store{
            background:linear-gradient(180deg,#0e1422,#0b101b);
            border:1px solid var(--edge); border-radius:14px; padding:12px; display:grid; grid-template-columns:96px 1fr; gap:12px;
            transition:transform .12s ease, border-color .2s; cursor:pointer;
        }
        .card-store:hover{ transform:translateY(-1px); border-color:#2a3659; }
        .store-thumb{ width:96px; height:96px; border-radius:10px; object-fit:cover; }
        .store-title{ margin:0 0 6px; font-size:18px; font-weight:800; }
        .badge{
            display:inline-flex; align-items:center; gap:6px; padding:4px 8px; border-radius:999px; font-weight:800; font-size:12px; border:1px solid #233055;
            background:#0f162a; color:#cfe0ff;
        }
        .badge.open{ background:#15321f; border-color:#2a5f3a; color:#b6f2c9 }
        .badge.closed{ background:#321919; border-color:#5f2a2a; color:#f2b6b6 }
        .features{ display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
        .tag{ background:#101733; border:1px solid #1e2740; color:#cfe0ff; border-radius:999px; padding:4px 8px; font-size:12px; font-weight:700 }
        .row{ display:flex; align-items:center; gap:10px; color:#c9d4ea; }
        .row i{ color:#c9d4ea }
        .actions{ display:flex; gap:8px; margin-top:10px; }
        .distance{ color:#cfe0ff; font-weight:800; }

        @media (max-width:980px){
            .card-store{ grid-template-columns:96px 1fr; }
        }

        /* Footer */
        footer.pi-footer{ position: relative; isolation: isolate; margin-top:28px;}
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
        .pi-footer .copyright{ margin:6px 0 0; font-size:12px; color:#fff; user-select:none; }

        /* Espaces haut/bas section magasins */
        #stores {
            padding-top: clamp(32px, 4vh, 48px);
            padding-bottom: clamp(40px, 6vh, 72px);
        }
        main{
            margin-bottom: clamp(20px, 4vh, 40px);
        }
        footer.pi-footer{
            margin-top: clamp(24px, 5vh, 56px);
        }
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

<!-- Fonds -->
<div id="page-bg" aria-hidden="true"></div>
<div class="pi-orbs" aria-hidden="true">
    <span class="orb blue a"></span>
    <span class="orb red  b"></span>
    <span class="orb blue c"></span>
    <span class="orb red  d"></span>
</div>

<!-- Bandeau villes -->
<div class="marquee" id="cityMarquee">
    <div class="marquee__inner">
        <span class="pill"><span class="dot"></span> Villemomble</span>
        <span class="pill"><span class="dot"></span> Bondy</span>
        <span class="pill"><span class="dot"></span> Villiers-Le-Bel</span>
        <span class="pill"><span class="dot"></span> Nogent-Sur-Oise</span>
        <span class="pill"><span class="dot"></span> Vert-Saint-Denis</span>
        <span class="pill"><span class="dot"></span> Drancy</span>
    </div>
    <div class="marquee__inner" aria-hidden="true"></div>
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

        <!-- Droite -->
        <div class="right-col">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a class="btn-login magnet" href="../deconnexion.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Se déconnecter</span>
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
            <li><a href="index.php">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre Histoire</a></li>
            <li><a href="index.php#catalog">Catalogue</a></li>
            <li><a href="nosMagasins.php" class="is-active">Nos Magasins</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <li><a href="postuler.php">Postuler</a></li>
        </ul>
    </div>

    <hr class="divider">
</header>

<main>

    <!-- SLIDER Magasins (repris de l'index) -->
    <section class="section" id="sites-slider" style="padding:10px 0 10px">
        <div class="container pi-sites">
            <!-- Texte + flèches -->
            <div class="pi-sites__left">
                <p class="eyebrow">NOS MAGASINS PARTOUT EN FRANCE</p>
                <h2 class="title" id="piSitesTitle">Villiers-le-Bel</h2>

                <div class="pi-sites__nav" aria-label="Contrôles du slider">
                    <button class="btn" id="piSitesPrev" aria-label="Précédent">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="btn" id="piSitesNext" aria-label="Suivant">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Pile des cartes -->
            <div class="pi-sites__stack" id="piSitesStack" aria-live="polite">
                <figure class="pi-sites__card pi-role-left"><img alt=""></figure>
                <figure class="pi-sites__card pi-role-center"><img alt=""></figure>
                <figure class="pi-sites__card pi-role-right"><img alt=""></figure>

                <figcaption class="pi-legend">
                    <span id="piSitesCity">Tourcoing, Fr</span>
                </figcaption>
            </div>
        </div>
    </section>



    <!-- Section magasins / carte -->
    <section class="container" id="stores">
        <div class="section-hd">
            <div>
                <h1>Nos magasins</h1>
                <div class="sub">Recherchez, filtrez par services, localisez-vous pour trouver le plus proche.</div>
            </div>
            <div class="sub" id="userHint">Non localisé</div>
        </div>

        <!-- (Optionnel : filtres / recherche / tri -> si tu veux les remettre plus tard) -->
        <!--
        <div class="controls">
            ...
        </div>
        -->

        <!-- Stats -->
        <div class="stats" aria-live="polite">
            <div class="stat"><span>Magasins</span><span class="big" id="storesCount">—</span></div>
            <div class="stat"><span>Départements servis</span><span class="big" id="deptCount">—</span></div>
            <div class="stat"><span>Ouverts maintenant</span><span class="big" id="openCount">—</span></div>
        </div>

        <!-- Carte + Liste -->
        <div class="layout">
            <div class="map-pane">
                <div id="map" aria-label="Carte des magasins"></div>
            </div>
            <div class="list-pane" id="listPane" aria-live="polite">
                <!-- cartes magasins rendues en JS -->
            </div>
        </div>
    </section>
    <!-- Vidéo intro -->
    <div class="video-wrap container">
        <div class="video-card reveal">
            <div class="video-glow" aria-hidden="true"></div>
            <div class="video-frame">
                <iframe
                        src="https://www.youtube.com/embed/WgkwUxDKi_0?autoplay=1&mute=1&playsinline=1&rel=0&modestbranding=1&controls=0&loop=1&playlist=WgkwUxDKi_0"
                        title="Paristanbul — vidéo"
                        loading="eager"
                        allow="autoplay; accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen
                ></iframe>
            </div>
            <div class="video-caption">
                <i class="fa-brands fa-youtube"></i>
                <span>Découvrez Paristanbul en 60 secondes</span>
            </div>
        </div>
    </div>
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
            <a href="index.php#catalog">Catalogue</a>
            <a href="quiSommesNous.html">À propos</a>
            <a href="postuler.php">Postuler</a>
            <a href="#contact">Contact</a>
        </nav>

        <p class="copyright">
            © <span id="year"></span> Paristanbul — Tous droits réservés.
        </p>
    </div>
</footer>

<!-- JS externes -->
<script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin></script>

<script>
    // utilitaires
    const $ = (s,el=document)=>el.querySelector(s);
    const $$ = (s,el=document)=>[...el.querySelectorAll(s)];
    document.getElementById('year').textContent = new Date().getFullYear();

    // Données magasins
    const stores = [
        {
            key:'villiers1',
            title:'Paristanbul VILLIERS-LE-BEL',
            image:'/Projet-Paristanbul/assets/img/magasins/villiers1.jpg',
            address:'3 avenue des entrepreneurs, 95400 Villiers-le-Bel',
            dept:'95',
            phone:'+33 7 49 82 61 33',
            coords:[49.0010, 2.3894],
            services:['boucherie','primeur','epicerie','parking','halal','traiteur'],
            hours:{
                mon:'08:30-20:00', tue:'08:30-20:00', wed:'08:30-20:00', thu:'08:30-20:00',
                fri:'08:30-20:00', sat:'08:30-20:00', sun:'08:30-20:00'
            }
        },
        {
            key:'villiers2',
            title:'Paristanbul VILLIERS-LE-BEL 2',
            image:'/Projet-Paristanbul/assets/img/magasins/villiers2.jpg',
            address:'117 Avenue Pierre Semard, 95400 Villiers-le-Bel',
            dept:'95',
            phone:'+33 7 49 82 61 33',
            coords:[48.9985, 2.4148],
            services:['boucherie','primeur','epicerie','halal'],
            hours:{
                mon:'08:30-20:00', tue:'08:30-20:00', wed:'08:30-20:00', thu:'08:30-20:00',
                fri:'08:30-20:00', sat:'08:30-20:00', sun:'08:30-20:00'
            }
        },
        {
            key:'drancy',
            title:'Paristanbul DRANCY',
            image:'/Projet-Paristanbul/assets/img/magasins/drancy.jpg',
            address:'83 avenue Marceau, 93700 Drancy',
            dept:'93',
            phone:'+33 7 49 82 61 33',
            coords:[48.9242, 2.4456],
            services:['epicerie','primeur','boucherie','halal','parking'],
            hours:{
                mon:'09:00-21:00', tue:'09:00-21:00', wed:'09:00-21:00', thu:'09:00-21:00',
                fri:'09:00-21:00', sat:'09:00-21:00', sun:'09:00-19:00'
            }
        },
        {
            key:'bondy',
            title:'Paristanbul BONDY',
            image:'/Projet-Paristanbul/assets/img/magasins/bondy.jpg',
            address:'116 Av. Gallieni, 93140 Bondy',
            dept:'93',
            phone:'+33 7 49 82 61 33',
            coords:[48.9024, 2.4823],
            services:['epicerie','primeur','halal','parking'],
            hours:{
                mon:'09:00-21:00', tue:'09:00-21:00', wed:'09:00-21:00', thu:'09:00-21:00',
                fri:'09:00-21:00', sat:'09:00-21:00', sun:'09:00-19:00'
            }
        },
        {
            key:'villemomble',
            title:'Paristanbul VILLEMOMBLE',
            image:'/Projet-Paristanbul/assets/img/magasins/villemomble.jpg',
            address:'68 Allée du Plateau, 93250 Villemomble',
            dept:'93',
            phone:'+33 7 49 82 61 33',
            coords:[48.8844, 2.5103],
            services:['epicerie','primeur','boucherie','halal'],
            hours:{
                mon:'08:00-20:30', tue:'08:00-20:30', wed:'08:00-20:30', thu:'08:00-20:30',
                fri:'08:00-20:30', sat:'08:00-20:30', sun:'08:00-20:30'
            }
        },
        {
            key:'nogent',
            title:'Paristanbul NOGENT-SUR-OISE',
            image:'/Projet-Paristanbul/assets/img/magasins/nogent.jpg',
            address:'171 Rue Jean Monnet, 60180 Nogent-sur-Oise',
            dept:'60',
            phone:'+33 7 49 82 61 33',
            coords:[49.2765, 2.2011],
            services:['epicerie','primeur','parking','halal'],
            hours:{
                mon:'09:30-20:00', tue:'09:30-20:00', wed:'09:30-20:00', thu:'09:30-20:00',
                fri:'09:30-20:00', sat:'09:30-20:00', sun:'10:00-19:00'
            }
        },
        {
            key:'vertsaintdenis',
            title:'Paristanbul VERT-SAINT-DENIS',
            image:'/Projet-Paristanbul/assets/img/magasins/vertsaintdenis.jpg',
            address:'La Fontaine Ronde, 77240 Vert-Saint-Denis',
            dept:'77',
            phone:'+33 7 49 82 61 33',
            coords:[48.6478, 2.6223],
            services:['epicerie','primeur','boucherie','halal','parking','traiteur'],
            hours:{
                mon:'08:30-20:30', tue:'08:30-20:30', wed:'08:30-20:30', thu:'08:30-20:30',
                fri:'08:30-20:30', sat:'08:30-20:30', sun:'08:30-20:30'
            }
        }
    ];

    // Etat utilisateur
    let userPos = null;

    const weekdayKey = ['sun','mon','tue','wed','thu','fri','sat'];

    function parseTime(str){
        const [h,m] = str.split(':').map(Number);
        return h*60 + (m||0);
    }
    function isOpenNow(store, when=new Date()){
        const day = weekdayKey[when.getDay()];
        const spec = store.hours[day];
        if(!spec) return false;
        const [a,b] = String(spec).split('-');
        if(!(a && b)) return false;
        const now = when.getHours()*60 + when.getMinutes();
        const start = parseTime(a), end = parseTime(b);
        if(end >= start) return now >= start && now <= end;
        return (now >= start) || (now <= end);
    }
    function haversine(a,b){
        const R=6371e3, toRad=x=>x*Math.PI/180;
        const [lat1,lon1]=a,[lat2,lon2]=b;
        const dLat=toRad(lat2-lat1), dLon=toRad(lon2-lon1);
        const s1=Math.sin(dLat/2), s2=Math.sin(dLon/2);
        const aa = s1*s1 + Math.cos(toRad(lat1))*Math.cos(toRad(lat2))*s2*s2;
        const c = 2*Math.atan2(Math.sqrt(aa), Math.sqrt(1-aa));
        return R*c;
    }
    function fmtDistance(m){
        if(m == null || isNaN(m)) return '';
        if(m < 950) return `${Math.round(m/10)*10} m`;
        return `${(m/1000).toFixed(1).replace('.',',')} km`;
    }
    function openDirections(address){
        const encoded = encodeURIComponent(address);
        window.open(`https://www.google.com/maps/dir/?api=1&destination=${encoded}`, '_blank');
    }

    // Leaflet map
    let map, markers = {};
    function initMap(){
        map = L.map('map',{ zoomControl:true, scrollWheelZoom:true }).setView([48.8566,2.3522], 10);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
            { attribution:'© OpenStreetMap • © CARTO', subdomains:'abcd', maxZoom:19 }).addTo(map);

        const icon = (color='#A32929') => L.divIcon({
            html:`<div style="background:${color};width:20px;height:20px;border-radius:50%;border:3px solid white;box-shadow:0 2px 10px rgba(0,0,0,.3);"></div>`,
            iconSize:[26,26], iconAnchor:[13,13]
        });

        stores.forEach(s => {
            const m = L.marker(s.coords, { icon: icon() })
                .addTo(map)
                .bindPopup(
                    `<strong>${s.title}</strong><br>${s.address}<br><small>${s.phone}</small>`,
                    {
                        autoPan: true,
                        keepInView: true,
                        autoPanPaddingTopLeft: [20, 20],
                        autoPanPaddingBottomRight: [20, 110],
                    }
                );

            m.on('click', () => scrollToCard(s.key));
            markers[s.key] = m;
        });
        setTimeout(()=>map.invalidateSize(), 150);
    }

    function focusStore(key){
        const s = stores.find(x=>x.key===key);
        if(!s) return;
        const ZOOM_ON_FOCUS = 13;
        map.setView(s.coords, ZOOM_ON_FOCUS, {animate:true});
        markers[key]?.openPopup();
        setTimeout(()=> {
            map.panBy([0, -60], {animate:true});
        }, 200);
    }

    function renderList(list){
        const pane = document.getElementById('listPane');
        pane.innerHTML = '';
        if(!list.length){
            pane.innerHTML = `<div class="sub" style="padding:12px">Aucun magasin ne correspond aux filtres.</div>`;
            return;
        }
        const now = new Date();
        list.forEach(s=>{
            const open = isOpenNow(s, now);
            const distLabel = s.distance != null ? `<span class="distance"><i class="fa-solid fa-location-dot"></i> ${fmtDistance(s.distance)}</span>` : '';
            const card = document.createElement('article');
            card.className = 'card-store';
            card.dataset.key = s.key;
            card.innerHTML = `
                <img class="store-thumb" src="${s.image}" alt="${s.title}" loading="lazy">
                <div>
                  <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:space-between">
                    <h3 class="store-title">${s.title}</h3>
                    <span class="badge ${open?'open':'closed'}"><i class="fa-solid fa-circle"></i> ${open?'Ouvert':'Fermé'}</span>
                  </div>
                  <div class="row"><i class="fa-solid fa-location-dot"></i> <span>${s.address}</span> ${distLabel}</div>
                  <div class="row"><i class="fa-solid fa-clock"></i> <span>Horaires du jour : ${humanTodayHours(s)}</span></div>
                  <div class="row"><i class="fa-solid fa-phone"></i> <a href="tel:${s.phone.replace(/\s/g,'')}">${s.phone}</a></div>
                  <div class="features">
                    ${s.services.map(t=>`<span class="tag">${cap(t)}</span>`).join('')}
                  </div>
                  <div class="actions">
                    <a class="btn" href="tel:${s.phone.replace(/\s/g,'')}"><i class="fa-solid fa-phone"></i> Appeler</a>
                    <button class="btn primary" onclick="openDirections('${s.address.replace(/'/g,"\\'")}')"><i class="fa-solid fa-route"></i> Itinéraire</button>
                    <button class="btn" onclick="focusStore('${s.key}')"><i class="fa-solid fa-map-location-dot"></i> Voir sur la carte</button>
                  </div>
                </div>`;
            card.addEventListener('click', (e)=>{
                const isButton = e.target.closest('.actions .btn');
                if(isButton) return;
                focusStore(s.key);
            });
            pane.appendChild(card);
        });
    }

    function scrollToCard(key){
        const el = document.querySelector(`.card-store[data-key="${key}"]`);
        if(!el) return;
        el.scrollIntoView({behavior:'smooth', block:'center'});
        el.style.outline='2px solid #2a3d73'; el.style.outlineOffset='2px';
        setTimeout(()=>{ el.style.outline=''; }, 900);
    }

    function cap(t){ return t.charAt(0).toUpperCase()+t.slice(1); }
    function humanTodayHours(s){
        const d = weekdayKey[new Date().getDay()];
        const val = s.hours[d];
        if(!val) return '—';
        return val.replace('-', ' — ');
    }

    function applyFilters(){
        let list = stores.map(s => ({ ...s }));

        if (userPos) {
            list.forEach(s => s.distance = haversine(userPos, s.coords));
            list.sort((a,b) => (a.distance || Infinity) - (b.distance || Infinity));
        } else {
            list.sort((a,b) => a.title.localeCompare(b.title));
        }

        updateStats(list);
        renderList(list);
        fitMapTo(list);
    }

    function updateStats(list){
        $('#storesCount').textContent = String(list.length);
        const depts = new Set(list.map(s=>s.dept));
        $('#deptCount').textContent = String(depts.size);
        const now = new Date();
        const opened = list.filter(s=>isOpenNow(s,now)).length;
        $('#openCount').textContent = String(opened);
    }

    function fitMapTo(list){
        if(!list.length || !map) return;
        const group = L.featureGroup(list.map(s=>markers[s.key]).filter(Boolean));
        try{ map.fitBounds(group.getBounds().pad(0.2)); }catch(e){}
    }

    function locateUser(){
        if(!navigator.geolocation){
            $('#userHint').textContent = 'Géolocalisation indisponible';
            return;
        }
        $('#userHint').textContent = 'Localisation en cours…';
        navigator.geolocation.getCurrentPosition(pos=>{
            userPos = [pos.coords.latitude, pos.coords.longitude];
            $('#userHint').textContent = 'Localisé';
            const you = L.circleMarker(userPos,{radius:7, color:'#2a3d73', weight:3, fillColor:'#2a3d73', fillOpacity:.4}).addTo(map).bindPopup('Vous êtes ici');
            map.setView(userPos, 12, {animate:true});
            applyFilters();
        }, err=>{
            $('#userHint').textContent = 'Localisation refusée';
        }, {enableHighAccuracy:true, timeout:8000, maximumAge:120000});
    }

    function goNearest(){
        const withDist = stores.map(s=>({s, d: userPos ? haversine(userPos, s.coords) : Infinity}));
        withDist.sort((a,b)=>a.d-b.d);
        const best = withDist[0];
        if(!best || !isFinite(best.d)){
            alert('Active d’abord la géolocalisation.');
            return;
        }
        focusStore(best.s.key);
        scrollToCard(best.s.key);
    }

    // init page magasins
    window.addEventListener('DOMContentLoaded', ()=>{
        initMap();
        applyFilters();

        // slider vidéo apparition
        const el = document.querySelector('.video-card.reveal');
        if ('IntersectionObserver' in window && el){
            const io = new IntersectionObserver(([e])=>{
                if(e.isIntersecting){ el.classList.add('is-in'); io.disconnect(); }
            }, {threshold:.25});
            io.observe(el);
        } else { el?.classList.add('is-in'); }

        // bandeau marquee duplic / vitesse
        (function(){
            const marquee = document.getElementById('cityMarquee');
            if(!marquee) return;
            const lanes = marquee.querySelectorAll('.marquee__inner');
            if(lanes.length < 2) return;

            // duplique piste
            lanes[1].innerHTML = lanes[0].innerHTML;

            const baseSpeedPxPerSec = 120;
            function tune(){
                const w = lanes[0].scrollWidth;
                marquee.style.setProperty('--speed', (w/baseSpeedPxPerSec).toFixed(2)+'s');
                marquee.style.height = lanes[0].offsetHeight + 'px';
            }
            tune();
            let t; window.addEventListener('resize', ()=>{ clearTimeout(t); t=setTimeout(tune,150); });
        })();

        // Géoloc auto si tu veux (sinon commente)
        // locateUser();
    });

    /* ===== Slider "Nos magasins" (pile 3 cartes) ===== */
    (function(){
        const stack   = document.getElementById('piSitesStack');
        if(!stack) return;

        const titleEl = document.getElementById('piSitesTitle');
        const cityEl  = document.getElementById('piSitesCity');
        const prevBtn = document.getElementById('piSitesPrev');
        const nextBtn = document.getElementById('piSitesNext');

        // mêmes visuels que sur index (adapte chemins si besoin)
        const slides = [
            { title:"Villiers-le-Bel",      city:"", img:"../assets/img/stores/villiers-le-bel.jpg" },
            { title:"Villiers-le-Bel",      city:"", img:"../assets/img/stores/villiers-le-bel-2.jpg" },
            { title:"Drancy",               city:"", img:"../assets/img/stores/drancy.jpg" },
            { title:"Bondy",                city:"", img:"../assets/img/stores/bondy.jpg" },
            { title:"Villemomble",          city:"", img:"../assets/img/stores/villemomble.jpg" },
            { title:"Nogent-sur-Oise",      city:"", img:"", img:"../assets/img/stores/nogent-sur-oise.jpg" },
            { title:"Vert-Saint-Denis",     city:"", img:"../assets/img/stores/vert-saint-denis.jpg" }
        ];

        const N = slides.length;
        let idx = 0;
        let busy = false;
        const T = 560; // doit matcher --sites-t

        const mod = (n,m)=>((n%m)+m)%m;

        function imgsByRole(){
            return {
                left:   stack.querySelector('.pi-role-left img'),
                center: stack.querySelector('.pi-role-center img'),
                right:  stack.querySelector('.pi-role-right img')
            };
        }

        function render(){
            const {left, center, right} = imgsByRole();
            const prev = slides[mod(idx-1, N)];
            const curr = slides[idx];
            const next = slides[mod(idx+1, N)];

            if(left){   left.src   = prev.img;  left.alt   = prev.title; }
            if(center){ center.src = curr.img;  center.alt = curr.title; }
            if(right){  right.src  = next.img;  right.alt  = next.title; }

            titleEl.textContent = curr.title;
            cityEl.textContent  = curr.city;

            titleEl.classList.remove('title-appear');
            void titleEl.offsetWidth;
            titleEl.classList.add('title-appear');
        }

        // init affichage
        render();

        function rotateNext(){
            if(busy) return; busy = true;
            stack.classList.add('pi-shift-next');

            setTimeout(()=>{
                idx = mod(idx+1, N);
                stack.classList.remove('pi-shift-next');

                const left  = stack.querySelector('.pi-role-left');
                const cent  = stack.querySelector('.pi-role-center');
                const right = stack.querySelector('.pi-role-right');
                left.classList.replace('pi-role-left',  'pi-role-right');
                cent.classList.replace('pi-role-center','pi-role-left');
                right.classList.replace('pi-role-right','pi-role-center');

                render();
                busy = false;
            }, T);
        }

        function rotatePrev(){
            if(busy) return; busy = true;
            stack.classList.add('pi-shift-prev');

            setTimeout(()=>{
                idx = mod(idx-1, N);
                stack.classList.remove('pi-shift-prev');

                const left  = stack.querySelector('.pi-role-left');
                const cent  = stack.querySelector('.pi-role-center');
                const right = stack.querySelector('.pi-role-right');
                right.classList.replace('pi-role-right','pi-role-left');
                cent.classList.replace('pi-role-center','pi-role-right');
                left.classList.replace('pi-role-left','pi-role-center');

                render();
                busy = false;
            }, T);
        }

        nextBtn.addEventListener('click', rotateNext);
        prevBtn.addEventListener('click', rotatePrev);
        document.addEventListener('keydown', e=>{
            if(e.key==='ArrowRight') rotateNext();
            if(e.key==='ArrowLeft')  rotatePrev();
        });

        /* autoplay */
        (function(){
            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReduced) return;

            const AREA  = document.querySelector('.pi-sites');
            const DELAY = 4800;
            let timer = null;

            const schedule = (d = DELAY) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    if (!busy) rotateNext();
                    schedule();
                }, d);
            };

            const stop  = () => { clearTimeout(timer); timer = null; };
            const reset = () => { stop(); schedule(); };

            schedule();
            AREA?.addEventListener('mouseenter', stop);
            AREA?.addEventListener('mouseleave', reset);
            AREA?.addEventListener('focusin', stop);
            AREA?.addEventListener('focusout', reset);
            AREA?.addEventListener('touchstart', stop,  { passive: true });
            AREA?.addEventListener('touchend',   reset, { passive: true });

            nextBtn.addEventListener('click', reset);
            prevBtn.addEventListener('click', reset);
            document.addEventListener('keydown', e => {
                if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') reset();
            });

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) stop(); else reset();
            });
        })();
    })();
</script>

</body>
</html>