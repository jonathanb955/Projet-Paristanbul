<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Paristanbul — Notre histoire</title>

    <!-- Police + Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Icônes Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

    <!-- Perf CDN -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <style>
        /* ===== Variables thème ===== */
        :root{
            --pi-blue:#2E4C97; --pi-red:#D6452E;
            --ink:#E6E9F2; --muted:#cfd5e6;
            --bg-1:#0B1326; --bg-2:#0A0F1F;
            --card:#141B2B; --chip:#1B2436;
            --border:rgba(255,255,255,.06);
            --ease:cubic-bezier(.22,.61,.36,1);

            /* couleurs nav/footer (texte) */
            --text:#ffffff;
        }

        /* ===== Reset ===== */
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0;
            font-family:"Plus Jakarta Sans", system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
            color:var(--ink);
            overflow-x:hidden;
        }
        img{max-width:100%;height:auto;display:block}
        a{color:inherit;text-decoration:none}

        /* ===== Fond global UNIQUE (fixe derrière la page) ===== */
        :root{
            --page-bg:
                    radial-gradient(1000px 500px at 10% 10%, rgba(46,76,151,.25), transparent 60%),
                    radial-gradient(900px 600px at 90% 10%, rgba(214,69,46,.18), transparent 55%),
                    linear-gradient(180deg, var(--bg-1), var(--bg-2) 70%);
        }
        html,body{ background:transparent !important; }
        #page-bg{
            position:fixed; inset:0; z-index:-2; pointer-events:none;
            background:var(--page-bg);
        }

        /* ===== Scroll progress ===== */
        #scrollProgress{
            position:fixed; inset:0 auto auto 0; height:3px; width:0;
            background:linear-gradient(90deg, var(--pi-red), var(--pi-blue));
            z-index:99999; box-shadow:0 0 12px rgba(214,69,46,.45);
            transition:width .2s linear;
        }

        /* ===== Header ===== */
        header.page-head{position:sticky; top:0; z-index:50; background:transparent; border-bottom:1px solid #141826;}
        .container{width:min(1100px,92vw);margin-inline:auto}
        .nav{display:grid; grid-template-columns: 1fr auto 1fr; align-items:center; gap:16px; height:66px;}
        .brand{display:flex; align-items:center; gap:12px; font-weight:800; letter-spacing:.3px}
        .nav-links{justify-self:center; display:flex; gap:14px; align-items:center;}
        .auth{justify-self:end; display:flex; gap:10px;}
        .nav a.btn{padding:10px 16px; border-radius:12px; background:linear-gradient(145deg,#1a2237,#0f172a); border:1px solid #1e2740;}
        .nav a.btn:hover{ outline:2px solid #2c59ff55 }
        header .nav-links a.btn:not(.primary){
            position:relative; background:transparent; border:0; padding-inline:10px; padding-bottom:6px;
        }
        header .nav-links a.btn:not(.primary)::after{
            content:""; position:absolute; left:50%; bottom:-6px; width:0; height:2px;
            background:linear-gradient(90deg,var(--pi-blue),var(--pi-red)); transition:width .25s,left .25s;
        }
        header .nav-links a.btn:not(.primary):hover::after,
        header .nav-links a.btn:not(.primary).is-active::after{ width:100%; left:0; }
        .btn.primary{background:linear-gradient(145deg,#102453,#0b3b8a); border-color:#0f2b6a}
        @media (max-width: 768px){
            .nav{ grid-template-columns: 1fr auto; row-gap:10px; height:auto; }
            .nav-links{ justify-self:start; flex-wrap:wrap }
            .auth{ justify-self:end }
        }

        /* ===== Hero / contenu ===== */
        .eyebrow{letter-spacing:.18em;text-transform:uppercase;color:var(--muted);font-weight:800;font-size:.85rem}
        .btn{display:inline-flex;align-items:center;gap:.5rem;font-weight:800;border-radius:12px;padding:.7rem 1.1rem;cursor:pointer;border:1px solid transparent}
        .btn-blue{background:var(--pi-blue);color:#fff}.btn-blue:hover{background:#25427f}
        .btn-red{background:var(--pi-red);color:#fff}.btn-red:hover{background:#b53a27}
        .magnet{will-change:transform;transition:transform .12s ease}

        .hero{position:relative;isolation:isolate;padding:clamp(48px,6vw,72px) 0;overflow:hidden; background:transparent!important;}
        .hero::before,.hero::after{content:"";position:absolute;filter:blur(10px);pointer-events:none}
        .hero::before{width:42vw;height:42vw;background:radial-gradient(closest-side,rgba(46,76,151,.28),transparent);top:-10vw;left:-12vw;animation:float1 14s ease-in-out infinite alternate}
        .hero::after{width:36vw;height:36vw;background:radial-gradient(closest-side,rgba(214,69,46,.22),transparent);bottom:-8vw;right:-10vw;animation:float2 18s ease-in-out infinite alternate}
        @keyframes float1{from{transform:translate(0,0)}to{transform:translate(5vw,3vw)}}
        @keyframes float2{from{transform:translate(0,0)}to{transform:translate(-4vw,-3vw)}}

        .story-grid{display:grid;grid-template-columns:minmax(320px,1fr) minmax(320px,1fr);gap:28px;align-items:center}
        @media(max-width:720px){.story-grid{grid-template-columns:1fr}}

        .media{aspect-ratio:16/9;border-radius:18px;overflow:hidden;position:relative;background:linear-gradient(180deg,rgba(255,255,255,.06),rgba(255,255,255,.03));border:1px solid var(--border);box-shadow:0 10px 30px rgba(0,0,0,.35);transition:transform .6s cubic-bezier(.2,.8,.2,1),box-shadow .6s;will-change:transform}
        .media:hover{transform:scale(1.02) rotateZ(.1deg);box-shadow:0 18px 40px rgba(0,0,0,.45)}
        .media img{width:100%;height:100%;object-fit:cover}

        .chips{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem}
        .chip{display:inline-flex;align-items:center;gap:.5rem;padding:.35rem .75rem;border-radius:999px;background:rgba(255,255,255,.08);color:var(--muted);border:1px solid rgba(255,255,255,.09);font-weight:700;font-size:.88rem}

        .timeline{position:relative;margin:8px 0 0}
        .timeline::before{content:"";position:absolute;left:clamp(18px,4vw,28px);top:0;bottom:0;width:2px;background:linear-gradient(var(--pi-blue),var(--pi-red));opacity:.35}
        .tl-item{position:relative;padding-left:clamp(3.25rem,7vw,5rem);margin-bottom:18px}
        .tl-dot{position:absolute;left:clamp(10px,3.5vw,20px);top:.6rem;width:14px;height:14px;border-radius:50%;background:linear-gradient(135deg,var(--pi-blue),var(--pi-red));box-shadow:0 0 0 6px rgba(255,255,255,.05)}
        .tl-card{background:transparent;border:1px solid var(--border);border-radius:16px;padding:14px 16px;transform-style:preserve-3d;transition:transform .12s,box-shadow .2s}
        .tl-card::before{content:"";position:absolute;inset:-1px;z-index:-1;border-radius:inherit;opacity:0;background:linear-gradient(135deg,rgba(46,76,151,.35),rgba(214,69,46,.35));transition:opacity .35s}
        .tl-card:hover::before{opacity:1}
        .year{font-weight:900;letter-spacing:.02em}
        .tilt{transition:transform .12s ease, box-shadow .2s ease; transform:perspective(900px) rotateX(var(--rx,0)) rotateY(var(--ry,0))}

        .stats{display:grid;grid-template-columns:repeat(6,minmax(140px,1fr));gap:14px}
        @media(max-width:1080px){.stats{grid-template-columns:repeat(3,1fr)}}
        @media(max-width:560px){.stats{grid-template-columns:repeat(2,1fr)}}
        .stat{background:transparent;border:1px solid var(--border);border-radius:16px;padding:16px;text-align:center;transform-style:preserve-3d}
        .stat i{font-size:1.6rem;opacity:.9}
        .stat .num{font-size:2rem;font-weight:900;line-height:1;margin:.45rem 0}
        .stat .lbl{color:var(--muted);font-weight:700;font-size:.9rem}

        .values{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
        @media(max-width:960px){.values{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:520px){.values{grid-template-columns:1fr}}
        .value{background:linear-gradient(180deg,rgba(255,255,255,.05),rgba(255,255,255,.02));border:1px solid var(--border);border-radius:16px;padding:16px;transform-style:preserve-3d;transition:transform .12s,box-shadow .2s}
        .value .ico{width:48px;height:48px;border-radius:50%;background:var(--chip);display:grid;place-items:center;margin-bottom:8px}
        .value h6{margin:.25rem 0 .35rem;font-size:1.05rem;font-weight:900}
        .value p{margin:0;color:var(--muted)}

        .cta{position:relative;overflow:hidden;border:1px solid var(--border);border-radius:18px;background:linear-gradient(180deg,rgba(46,76,151,.15),rgba(46,76,151,.05));padding:22px clamp(18px,4vw,28px);display:grid;grid-template-columns:1fr auto;gap:16px;align-items:center}
        @media(max-width:720px){.cta{grid-template-columns:1fr}}

        /* ===== Footer (transparent, sur le fond unique) ===== */
        footer.pi-footer{ background:transparent !important; border-top: 1px solid #141a2b; }
        .pi-footer .wrap{ max-width:1100px; margin:0 auto; text-align:center; padding:0 18px; }
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
        .pi-footer .copyright{ margin:6px 0 26px; font-size:12px; color:var(--muted); user-select:none; }

        /* ===== Orbes + hairlines ===== */
        .pi-orbs{ position:fixed; inset:0; z-index:-1; pointer-events:none; overflow:hidden; }
        .pi-orbs .orb{
            position:absolute; width:48vmax; height:48vmax; border-radius:9999px;
            filter:blur(80px); opacity:.75; mix-blend-mode:screen; will-change:transform;
        }
        .pi-orbs .blue{ background:rgba(46,76,151,.18); }
        .pi-orbs .red { background:rgba(226,27,60,.16);  }
        .pi-orbs .a{ top:-10vmax; left:-6vmax;  animation:orbA 36s linear infinite; }
        .pi-orbs .b{ top:-8vmax; right:-10vmax; animation:orbB 42s linear infinite; }
        .pi-orbs .c{ bottom:-12vmax; left:15vw; animation:orbC 40s linear infinite; width:42vmax;height:42vmax;}
        .pi-orbs .d{ bottom:-14vmax; right:10vw; animation:orbD 46s linear infinite; width:50vmax;height:50vmax;}
        @keyframes orbA{ 50%{ transform:translate3d(4vw,2vh,0) scale(1.05);} }
        @keyframes orbB{ 50%{ transform:translate3d(-3vw,3vh,0) scale(1.03);} }
        @keyframes orbC{ 50%{ transform:translate3d(2vw,-2vh,0) scale(1.06);} }
        @keyframes orbD{ 50%{ transform:translate3d(-2vw,-3vh,0) scale(1.04);} }
        .tl-card, .stat, .value, .cta{
            background:transparent !important;
            border:1px solid rgba(255,255,255,.12) !important;
            box-shadow:none !important; border-radius:16px;
        }
        .tl-card:hover, .value:hover, .stat:hover, .cta:hover{
            border-color:rgba(255,255,255,.20) !important;
        }
        .timeline::before{ opacity:.5 !important; }
        @media (prefers-reduced-motion:reduce){ .pi-orbs .orb{ animation:none; opacity:.55; } }

        /* ===== Reveal / Ripple / ToTop ===== */
        @media(prefers-reduced-motion:reduce){.reveal,.media,.btn,.tilt{transition:none!important;animation:none!important}}
        .reveal{opacity:0;transform:translateY(18px) scale(.98);filter:blur(2px);transition:opacity .6s var(--ease),transform .6s var(--ease),filter .6s var(--ease)}
        .reveal.reveal--in{opacity:1;transform:none;filter:none}
        .r{position:absolute;border-radius:50%;background:currentColor;opacity:.15;transform:scale(0);pointer-events:none;animation:ripple .6s ease-out forwards}
        @keyframes ripple{to{opacity:0;transform:scale(3)}}
        #toTop{position:fixed;right:18px;bottom:18px;z-index:999;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;background:var(--pi-blue);color:#fff;border:0;box-shadow:0 10px 24px rgba(0,0,0,.25);opacity:0;pointer-events:none;transform:translateY(10px);transition:opacity .3s,transform .3s}
        #toTop.show{opacity:1;pointer-events:auto;transform:none}

        /* Assure qu'aucune section ne repeigne un fond solide */
        header, .hero, section, footer.pi-footer { background:transparent !important; }
    </style>

    <!-- Mini AOS (pour le footer) -->
    <style>
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
    </style>
</head>

<body>
<!-- Fond global unique -->
<div id="page-bg" aria-hidden="true"></div>

<div id="scrollProgress" aria-hidden="true"></div>

<!-- Header -->
<header class="page-head">
    <div class="container nav">
        <div class="brand">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" style="height:48px">
            </a>
        </div>

        <nav class="nav-links">
            <a href="index.php" class="btn magnet">Accueil</a>
            <a href="quiSommesNous.html" class="btn magnet is-active">Notre Histoire</a>
            <a href="postuler.php" class="btn magnet">Postuler</a>
            <a href="pageConnexion.php#contact" class="btn magnet">Nous contacter</a>
            <a href="nosMagasins.php" class="btn magnet">Nos Magasins</a>
        </nav>

        <div class="auth">
            <a href="pageInscription.php" class="btn">Inscription</a>
            <a href="pageConnexion.php" class="btn primary">Connexion</a>
        </div>
    </div>
</header>

<!-- Orbes décor -->
<div class="pi-orbs" aria-hidden="true">
    <span class="orb blue a"></span>
    <span class="orb red  b"></span>
    <span class="orb blue c"></span>
    <span class="orb red  d"></span>
</div>

<!-- HERO / ADN -->
<header class="hero">
    <div class="container story-grid">
        <div class="media reveal" style="--reveal-delay:40ms" id="heroMedia">
            <img src="../assets/img/maxresdefault.jpg" alt="Paristanbul — archives familiales" loading="lazy">
        </div>

        <div class="reveal" style="--reveal-delay:80ms" id="heroText">
            <span class="eyebrow">Notre ADN</span>
            <h1 style="margin:.35rem 0 .6rem;line-height:1.06;letter-spacing:-.02em;font-weight:900">
                <span class="pi-word-anim glow">Une histoire de famille</span>, de courage et de goût.
            </h1>
            <p style="color:var(--muted)">
                Arrivé jeune en Europe, <strong>Metin</strong> apprend vite le métier, de la plonge au service. À Paris,
                sa rencontre avec <strong>Gevriye</strong> change tout : ils repartent de zéro, misent sur la
                <em>qualité fraîche</em> et la confiance du quartier.
            </p>
            <p style="color:var(--muted)">
                Après un premier commerce de fruits &amp; légumes, la marque <strong>Paristanbul</strong> voit le jour en
                <strong>2012</strong> — un pont gourmand entre les cultures. En <strong>2025</strong>, avec leurs fils,
                la famille pilote une chaîne moderne de supermarchés de proximité.
            </p>
            <div class="chips">
                <span class="chip"><i class="bi bi-bag"></i> Fraîcheur & prix justes</span>
                <span class="chip"><i class="bi bi-truck"></i> Circuits courts</span>
                <span class="chip"><i class="bi bi-emoji-smile"></i> Hospitalité</span>
            </div>
            <div style="margin-top:14px">
                <a href="nosMagasins.php" class="btn btn-blue magnet"><i class="bi bi-geo-alt"></i> Voir nos adresses</a>
            </div>
        </div>
    </div>
</header>

<!-- TIMELINE -->
<section class="section" style="padding:26px 0 14px">
    <div class="container">
        <h2 class="reveal" style="margin:0 0 10px">Les jalons qui nous ont façonnés</h2>

        <div class="timeline">
            <div class="tl-item reveal" style="--reveal-delay:0ms">
                <div class="tl-dot"></div>
                <div class="tl-card tilt">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                        <span class="year">2006</span>
                        <span class="chip"><i class="bi bi-basket2"></i> Premier commerce</span>
                    </div>
                    <p style="color:var(--muted);margin:.6rem 0 0">
                        Ouverture du premier magasin de fruits &amp; légumes à Paris. Produits ultra-frais au meilleur prix, servis avec le sourire.
                    </p>
                </div>
            </div>

            <div class="tl-item reveal" style="--reveal-delay:60ms">
                <div class="tl-dot"></div>
                <div class="tl-card tilt">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                        <span class="year">2012</span>
                        <span class="chip"><i class="bi bi-stars"></i> Naissance de Paristanbul</span>
                    </div>
                    <p style="color:var(--muted);margin:.6rem 0 0">
                        L’identité est lancée : énergie d’Istanbul, exigence parisienne. Déploiement boucherie halal, crèmerie, épiceries du monde.
                    </p>
                </div>
            </div>

            <div class="tl-item reveal" style="--reveal-delay:120ms">
                <div class="tl-dot"></div>
                <div class="tl-card tilt">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                        <span class="year">2018</span>
                        <span class="chip"><i class="bi bi-box-seam"></i> Premier entrepôt</span>
                    </div>
                    <p style="color:var(--muted);margin:.6rem 0 0">
                        Structuration logistique : plateforme de préparation, achats centralisés et partenariats avec producteurs locaux.
                    </p>
                </div>
            </div>

            <div class="tl-item reveal" style="--reveal-delay:180ms">
                <div class="tl-dot"></div>
                <div class="tl-card tilt">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                        <span class="year">2020</span>
                        <span class="chip"><i class="bi bi-phone"></i> E-commerce de proximité</span>
                    </div>
                    <p style="color:var(--muted);margin:.6rem 0 0">
                        Click &amp; collect et livraison urbaine : le marché du quartier, depuis votre canapé.
                    </p>
                </div>
            </div>

            <div class="tl-item reveal" style="--reveal-delay:240ms">
                <div class="tl-dot"></div>
                <div class="tl-card tilt">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                        <span class="year">2025</span>
                        <span class="chip"><i class="bi bi-building"></i> 10 magasins</span>
                    </div>
                    <p style="color:var(--muted);margin:.6rem 0 .8rem">
                        Une chaîne familiale florissante, ancrée dans la proximité et la qualité. De nouveaux quartiers nous attendent.
                    </p>
                    <a href="postuler.php" class="btn btn-red magnet"><i class="bi bi-briefcase"></i> Nous rejoindre</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="section" style="padding:12px 0 8px">
    <div class="container">
        <div class="stats">
            <div class="stat reveal tilt" style="--reveal-delay:0ms">
                <i class="bi bi-bar-chart"></i>
                <div class="num counter" data-target="75">0</div>
                <div class="lbl">M€ de CA (2021)</div>
            </div>
            <div class="stat reveal tilt" style="--reveal-delay:40ms">
                <i class="bi bi-shop"></i>
                <div class="num counter" data-target="10">0</div>
                <div class="lbl">Magasins</div>
            </div>
            <div class="stat reveal tilt" style="--reveal-delay:80ms">
                <i class="bi bi-people-fill"></i>
                <div class="num counter" data-target="310">0</div>
                <div class="lbl">Salariés</div>
            </div>
            <div class="stat reveal tilt" style="--reveal-delay:120ms">
                <i class="bi bi-box-seam"></i>
                <div class="num counter" data-target="12000">0</div>
                <div class="lbl">Références</div>
            </div>
            <div class="stat reveal tilt" style="--reveal-delay:160ms">
                <i class="bi bi-truck"></i>
                <div class="num counter" data-target="3">0</div>
                <div class="lbl">Entrepôts</div>
            </div>
            <div class="stat reveal tilt" style="--reveal-delay:200ms">
                <i class="bi bi-hourglass-split"></i>
                <div class="num counter" data-target="1">0</div>
                <div class="lbl">En ouverture</div>
            </div>
        </div>
    </div>
</section>

<!-- VALEURS -->
<section class="section" style="padding:22px 0 10px">
    <div class="container">
        <h2 class="reveal" style="margin:0 0 10px">Nos valeurs, sans compromis</h2>
        <div class="values">
            <article class="value reveal tilt" style="--reveal-delay:0ms">
                <div class="ico"><i class="bi bi-people"></i></div>
                <h6>Proximité</h6>
                <p>Des équipes du quartier, des magasins à taille humaine, une relation directe.</p>
            </article>
            <article class="value reveal tilt" style="--reveal-delay:60ms">
                <div class="ico"><i class="bi bi-shield-check"></i></div>
                <h6>Qualité</h6>
                <p>Sélection rigoureuse, fraîcheur quotidienne, boucherie halal certifiée.</p>
            </article>
            <article class="value reveal tilt" style="--reveal-delay:120ms">
                <div class="ico"><i class="bi bi-currency-euro"></i></div>
                <h6>Prix justes</h6>
                <p>Des partenariats durables pour garantir le bon prix, toute l’année.</p>
            </article>
            <article class="value reveal tilt" style="--reveal-delay:180ms">
                <div class="ico"><i class="bi bi-globe2"></i></div>
                <h6>Responsabilité</h6>
                <p>Moins de gaspillage, plus de local, des emballages mieux pensés.</p>
            </article>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section" style="padding:24px 0 40px">
    <div class="container">
        <div class="cta reveal" style="--reveal-delay:40ms">
            <div>
                <h3 style="margin:.2rem 0 .2rem">Envie de nous rejoindre ?</h3>
                <p style="margin:0;color:var(--muted)">Grandir avec Paristanbul, c’est apprendre vite, bien — et ensemble.</p>
            </div>
            <div>
                <a href="postuler.php" class="btn btn-red magnet"><i class="bi bi-briefcase"></i> Voir les offres</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
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
            <a href="pageConnexion.php#contact">Contact</a>
        </nav>

        <p class="copyright">
            © <span id="year"></span> Paristanbul — Tous droits réservés.
        </p>
    </div>
</footer>

<!-- Bouton haut -->
<button id="toTop" aria-label="Remonter"><i class="bi bi-arrow-up"></i></button>

<!-- ===== JS ===== -->
<script>
    /* Scroll progress */
    (function(){
        const bar=document.getElementById('scrollProgress');
        const onScroll=()=>{ const h=document.documentElement; const s=h.scrollTop; const d=h.scrollHeight-h.clientHeight; bar.style.width=(d?(s/d)*100:0)+'%'; };
        document.addEventListener('scroll', onScroll, {passive:true}); onScroll();
    })();

    /* Nav active */
    (function(){
        document.querySelectorAll('header.page-head .nav-links a').forEach(a=>{
            const clean = (u)=>u.split('#')[0].replace(/\/+$/,'');
            if (clean(a.href) === clean(location.href)) a.classList.add('is-active');
        });
    })();

    /* ToTop */
    (function(){
        const toTop=document.getElementById('toTop');
        const onS=()=>{ const y=window.scrollY||0; toTop.classList.toggle('show', y>500); };
        onS(); window.addEventListener('scroll', onS, {passive:true});
        toTop.addEventListener('click', ()=>window.scrollTo({top:0,behavior:'smooth'}));
    })();

    /* Ripple sur .btn */
    document.querySelectorAll('.btn').forEach(btn=>{
        btn.addEventListener('pointerdown', e=>{
            const r=document.createElement('span'); r.className='r';
            const rect=btn.getBoundingClientRect(); const d=Math.max(rect.width,rect.height);
            r.style.width=r.style.height=d+'px';
            r.style.left=(e.clientX-rect.left-d/2)+'px';
            r.style.top=(e.clientY-rect.top-d/2)+'px';
            btn.appendChild(r); r.addEventListener('animationend',()=>r.remove());
        });
    });

    /* Magnetic buttons */
    (function(){
        const prefersReduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isTouch=('ontouchstart'in window)||navigator.maxTouchPoints>0;
        if(prefersReduced||isTouch) return;
        document.querySelectorAll('.magnet').forEach(el=>{
            const strength=10; let tX=0,tY=0,cX=0,cY=0,RAF=null;
            const lerp=(a,b,t)=>a+(b-a)*t;
            const animate=()=>{ cX=lerp(cX,tX,.18); cY=lerp(cY,tY,.18); el.style.transform=`translate(${cX}px,${cY}px)`; RAF=requestAnimationFrame(animate); };
            el.addEventListener('mousemove', e=>{
                const r=el.getBoundingClientRect(); const x=(e.clientX-r.left)/r.width-.5; const y=(e.clientY-r.top)/r.height-.5;
                tX=x*strength; tY=y*strength; if(!RAF) animate();
            });
            el.addEventListener('mouseleave',()=>{ tX=0; tY=0; cancelAnimationFrame(RAF); RAF=null; el.style.transform=''; });
        });
    })();

    /* Reveal simple */
    (function(){
        const els=[...document.querySelectorAll('.reveal,.tilt')]; if(!els.length) return;
        if(!('IntersectionObserver' in window)){els.forEach(el=>el.classList.add('reveal--in'));return;}
        const io=new IntersectionObserver(ents=>{
            ents.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('reveal--in'); io.unobserve(e.target);} });
        },{threshold:.12, rootMargin:'0px 0px -10% 0px'});
        els.forEach((el,i)=>{ el.style.setProperty('--reveal-delay',(i%6)*60+'ms'); io.observe(el); });
    })();

    /* Tilt 3D */
    (function(){
        const prefersReduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if(prefersReduced) return;
        const MAX=10;
        document.querySelectorAll('.tilt, .tl-card, .value, .stat').forEach(card=>{
            const onMove=(e)=>{
                const r=card.getBoundingClientRect();
                const px=(e.clientX-r.left)/r.width, py=(e.clientY-r.top)/r.height;
                const rx=(py-.5)*-MAX, ry=(px-.5)*MAX;
                card.style.setProperty('--rx', rx.toFixed(2)+'deg');
                card.style.setProperty('--ry', ry.toFixed(2)+'deg');
            };
            card.addEventListener('mousemove', onMove);
            card.addEventListener('mouseleave', ()=>{ card.style.removeProperty('--rx'); card.style.removeProperty('--ry'); });
        });
    })();

    /* Compteurs */
    (function(){
        const counters=document.querySelectorAll('.counter');
        if(!('IntersectionObserver' in window)||!counters.length) return;
        const io=new IntersectionObserver(entries=>{
            entries.forEach(e=>{
                if(!e.isIntersecting) return;
                const el=e.target; const target=+el.dataset.target||0;
                const step=Math.max(1,Math.floor(target/80));
                const tick=()=>{ const n=+el.textContent.replace(/\D/g,'')||0;
                    if(n<target){ el.textContent=String(n+step); requestAnimationFrame(tick); }
                    else { el.textContent=target.toLocaleString('fr-FR'); }
                };
                tick(); io.unobserve(el);
            });
        },{threshold:.4});
        counters.forEach(c=>io.observe(c));
    })();

    /* Footer year */
    document.getElementById('year').textContent = new Date().getFullYear();

    /* Mini AOS pour le footer */
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

        add('footer.pi-footer .brand', 'scale');
        add('footer.pi-footer .headline', 'sweep');
        add('footer.pi-footer .social li', 'pop', 50);
        add('footer.pi-footer .footer-nav a', 'rise', 30);
        add('footer.pi-footer .copyright', 'rise', 200);
    })();
</script>
</body>
</html>
