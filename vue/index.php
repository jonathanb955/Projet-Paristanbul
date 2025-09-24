<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Paristanbul — Accueil</title>

    <!-- Icônes Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

    <!-- CSS EXTERNE (facultatif) -->
    <link rel="stylesheet" href="../assets/css/index.css"/>

    <style>
        /* ========== RESET & BASE ========== */
        *{margin:0;padding:0;box-sizing:border-box}
        html,body{height:100%}
        body{font-family:'Segoe UI',system-ui,-apple-system,Roboto,Arial,sans-serif;line-height:1.6}

        /* ===== Scroll progress (même que postuler.php) ===== */
        #scrollProgress{
            position:fixed; inset:0 auto auto 0; height:3px; width:0;
            background:linear-gradient(90deg, var(--pi-red), var(--pi-blue));
            z-index:99999; box-shadow:0 0 12px rgba(214,69,46,.45);
            transition:width .2s linear;
        }

        /* ========== THÈME SOMBRE PARISTANBUL (identique postuler.php) ========== */
        :root{
            --pi-blue:#2E4C97;       /* bleu logo */
            --pi-red:#D6452E;        /* rouge logo */
            --ink:#E6E9F2;           /* texte clair */
            --muted:#cfd5e6;         /* texte secondaire */
            --bg-1:#0B1326;          /* fond sombre */
            --bg-2:#0A0F1F;          /* fond sombre 2 */
            --card:#141B2B;          /* cartes */
            --chip:#1B2436;          /* pictos / accents */
            --border:rgba(255,255,255,.08);
            --ring:rgba(46,76,151,.35);
            --ease:cubic-bezier(.22,.61,.36,1);
        }
        body{
            color:var(--ink) !important;
            background:
                    radial-gradient(1200px 700px at 15% 10%, rgba(46,76,151,.22), transparent 60%),
                    radial-gradient(1000px 700px at 85% 15%, rgba(214,69,46,.16), transparent 55%),
                    linear-gradient(180deg, var(--bg-1) 0%, var(--bg-2) 100%) !important;
        }
        a{color:inherit;text-decoration:none}
        img{max-width:100%;height:auto;display:block}

        /* ========== NAVBAR (mêmes comportements) ========== */
        nav.navbar{
            position:sticky; top:0; z-index:1000;
            display:flex; align-items:center; justify-content:space-between;
            gap:24px; padding:12px 24px;
            background:rgba(11,19,38,.98);
            border-bottom:1px solid var(--border);
            transition: background-color .35s ease, box-shadow .35s ease !important;
        }
        nav.navbar.nav--scrolled{
            background:rgba(10,15,31,.92) !important;
            box-shadow:0 10px 24px rgba(0,0,0,.28);
            backdrop-filter:saturate(120%) blur(6px);
        }
        .logo img{height:46px; width:auto}
        .nav-links{list-style:none;display:flex;gap:1.25rem;align-items:center}
        .nav-links a{position:relative;color:var(--muted);font-weight:600;letter-spacing:.2px}
        .nav-links a:hover,.nav-links a.active{color:#fff}
        .nav-links a::after{
            content:""; position:absolute; left:50%; bottom:-6px; width:0; height:2px;
            background:linear-gradient(90deg,var(--pi-blue),var(--pi-red));
            transition:width .25s ease,left .25s ease;
        }
        .nav-links a:hover::after,.nav-links a.active::after{width:100%; left:0}
        .nav-buttons a{color:#fff; font-weight:600}

        /* ========== HERO (mêmes halos/parallaxe) ========== */
        .hero{
            position:relative; overflow:hidden;
            padding:64px 20px 40px; isolation:isolate;
        }
        .hero .wrap{
            max-width:1200px; margin:0 auto; display:grid; gap:28px;
            grid-template-columns:1.1fr .9fr; align-items:center;
        }
        @media (max-width: 980px){ .hero .wrap{grid-template-columns:1fr} }

        .hero h1{
            font-size: clamp(28px, 4vw, 48px);
            font-weight:800; line-height:1.08;
            background:linear-gradient(90deg,#fff,#9cc3ff);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            margin-bottom:12px;
        }
        .hero p{color:var(--muted); font-size:1.08rem; margin-bottom:20px}

        .btn-outline, .btn-brand{
            display:inline-block; padding:.85rem 1.25rem; border-radius:12px;
            font-weight:700; letter-spacing:.2px; cursor:pointer; position:relative; overflow:hidden; isolation:isolate;
            transition: transform .15s ease, box-shadow .25s ease, background-color .25s ease, color .25s ease;
        }
        .btn-outline{ border:1px solid rgba(255,255,255,.65); color:#fff; background:transparent }
        .btn-brand{ background:var(--pi-red); color:#fff; border:1px solid transparent }
        .btn-outline:hover{ box-shadow:0 10px 24px rgba(230,233,242,.25); transform:translateY(-2px) }
        .btn-brand:hover{ box-shadow:0 10px 24px rgba(214,69,46,.35); transform:translateY(-2px) }

        /* ripple */
        .r{position:absolute;border-radius:50%;background:currentColor;opacity:.15;transform:scale(0);pointer-events:none;animation:ripple .6s ease-out forwards}
        @keyframes ripple{to{opacity:0;transform:scale(3)}}

        /* hero halos (équivalents s1/s2/s3) */
        .hero::before,.hero::after{content:"";position:absolute;pointer-events:none;filter:blur(14px);opacity:.22}
        .hero::before{
            width:42vw;height:42vw; border-radius:50%;
            background:radial-gradient(closest-side, rgba(46,76,151,.35), transparent 60%);
            top:-10vw; left:-10vw; animation:float1 14s ease-in-out infinite alternate;
        }
        .hero::after{
            width:36vw;height:36vw; border-radius:50%;
            background:radial-gradient(closest-side, rgba(214,69,46,.28), transparent 60%);
            bottom:-8vw; right:-8vw; animation:float2 18s ease-in-out infinite alternate;
        }
        @keyframes float1{from{transform:translate(0,0)} to{transform:translate(5vw,3vw)}}
        @keyframes float2{from{transform:translate(0,0)} to{transform:translate(-4vw,-3vw)}}

        .video-container{
            border-radius:20px; overflow:hidden; will-change:transform;
            transition: transform .6s cubic-bezier(.2,.8,.2,1), box-shadow .6s;
        }
        .video-container:hover{ transform:scale(1.02) rotateZ(.1deg); box-shadow:0 18px 40px rgba(0,0,0,.35) }

        /* ========== SECTIONS & CARTES SOMBRES ========== */
        .section{padding:64px 20px}
        .container{max-width:1200px; margin:0 auto}
        .section-title{font-size:clamp(22px,3vw,34px);font-weight:800;letter-spacing:.3px;margin-bottom:.5rem}
        .section-subtitle{color:var(--muted);margin-bottom:1.75rem}

        .grid{display:flex; flex-wrap:wrap; gap:24px; justify-content:center}
        .card{
            position:relative; width:300px; padding:22px; border-radius:16px; background:var(--card);
            border:1px solid var(--border); color:var(--ink);
            transition: transform .12s ease, box-shadow .25s ease, border-color .25s ease; will-change:transform;
            transform-style:preserve-3d;
        }
        .card:hover{border-color:rgba(255,255,255,.14); box-shadow:0 18px 50px -20px rgba(0,0,0,.6), 0 8px 24px -12px var(--ring)}
        .card::before{
            content:""; position:absolute; inset:-1px; z-index:-1; border-radius:inherit; opacity:0;
            background:linear-gradient(135deg, rgba(46,76,151,.35), rgba(214,69,46,.35));
            transition:opacity .35s ease;
        }
        .card:hover::before{opacity:1}
        .icon{width:54px;height:54px;display:grid;place-items:center;border-radius:14px;background:linear-gradient(180deg, rgba(46,76,151,.18), rgba(46,76,151,.06));border:1px solid var(--border);}

        .badge{display:inline-block;padding:.25rem .6rem;border-radius:999px;background:rgba(46,76,151,.12);border:1px solid rgba(46,76,151,.28);color:#cfe0ff;font-weight:700}

        /* PRODUITS / PROMOS (compteur + shimmer) */
        .produits .card, .promos .card{width:280px}
        .produit-image{height:160px; display:grid; place-items:center; background:#0F1524; border-radius:12px; overflow:hidden; position:relative}
        .produit-image.shimmer::before{
            content:""; position:absolute; inset:0;
            background:linear-gradient(90deg, transparent, rgba(255,255,255,.12), transparent);
            transform:translateX(-100%); animation:shimmer 1.5s infinite;
        }
        @keyframes shimmer{to{transform:translateX(100%)}}
        .prix{color:var(--pi-red);font-weight:800}
        .promo-header{
            position:absolute; top:0; left:0; border-top-left-radius:16px; border-bottom-right-radius:16px;
            background:var(--pi-blue); color:#fff; padding:.45rem .75rem; font-weight:800; font-size:.85rem;
        }
        .promo-prix,.date{color:var(--pi-blue);font-weight:800}
        .barre{text-decoration:line-through; color:#9aa6c1}

        /* MAGASINS */
        .itineraire-btn{ display:inline-block; width:100%; text-align:center; margin-top:.75rem;
            border-radius:10px; padding:.7rem 1rem; background:var(--pi-red); color:#fff; font-weight:800;
            transition: transform .15s ease, box-shadow .25s ease;
        }
        .itineraire-btn:hover{ transform:translateY(-2px); box-shadow:0 10px 24px rgba(214,69,46,.35)}

        /* CONTACT + MODAL “Merci” (identique postuler) */
        .contact .wrap{display:flex;flex-wrap:wrap;gap:24px;justify-content:center}
        .contact .form, .contact .side{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:22px}
        .contact .form{flex:1 1 420px; max-width:520px}
        .contact .side{flex:1 1 320px; max-width:420px}
        .contact input,.contact select,.contact textarea{
            width:100%; background:#0F1524; color:#fff; border:1px solid #2a3654; border-radius:10px; padding:.7rem; margin-top:.4rem;
            transition:border-color .2s var(--ease), box-shadow .2s var(--ease);
        }
        .contact input::placeholder,.contact textarea::placeholder{color:#9eb0d3}
        .contact input:focus,.contact select:focus,.contact textarea:focus{outline:none; box-shadow:0 0 0 4px rgba(46,76,151,.25); border-color:rgba(46,76,151,.6)}
        .contact .form button{width:100%; margin-top:1rem}

        .pi-modal{position:fixed;inset:0;display:grid;place-items:center;opacity:0;pointer-events:none;transition:opacity .25s ease;z-index:9999}
        .pi-modal--open{opacity:1;pointer-events:auto}
        .pi-modal__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45);backdrop-filter:saturate(120%) blur(2px)}
        .pi-modal__dialog{position:relative;width:min(560px,92vw);border-radius:20px;padding:28px 24px;background:#fff;box-shadow:0 20px 60px rgba(0,0,0,.25);transform:translateY(8px) scale(.96);opacity:.9}
        .pi-modal--open .pi-modal__dialog{animation:piZoom .42s cubic-bezier(.2,.8,.2,1) forwards}
        @keyframes piZoom{to{transform:translateY(0) scale(1);opacity:1}}
        .pi-modal__badge{width:82px;height:82px;border-radius:50%;display:grid;place-items:center;margin:-70px auto 12px;background:linear-gradient(135deg,var(--pi-blue) 0%,#5e7ad0 100%);color:#fff;font-size:40px;position:relative;box-shadow:0 10px 24px rgba(46,76,151,.35)}
        .pi-modal__title{ text-align:center; margin:6px 0; font-size:1.35rem; font-weight:800; color:#003366}
        .pi-modal__text{ text-align:center; color:#2b2b2b; margin:0 8px 18px; line-height:1.45}
        .pi-modal__actions{display:flex; justify-content:center; gap:12px}
        .pi-btn{border:0;border-radius:12px;padding:10px 16px;font-weight:700;cursor:pointer;transition:transform .08s ease,box-shadow .18s ease}
        .pi-btn--primary{background:#003366;color:#fff;box-shadow:0 6px 16px rgba(0,51,102,.35)}

        /* FOOTER sombre */
        .footer{background:#0A0F1F;color:#fff;padding:48px 20px}
        .footer .footer-container{max-width:1200px;margin:0 auto;display:flex;flex-wrap:wrap;gap:24px;justify-content:space-between}
        .footer .footer-column{flex:1 1 220px;min-width:220px}
        .footer h3{margin-bottom:10px}
        .footer p,.footer a{color:var(--muted)}
        .footer a:hover{color:#fff}
        .newsletter-form{display:flex;background:#151e31;border-radius:8px;overflow:hidden;margin-top:10px;max-width:300px}
        .newsletter-field{flex:1;border:0;background:transparent;color:#fff;padding:.7rem 1rem}
        .newsletter-submit{border:0;background:var(--pi-blue);color:#fff;padding:0 16px;font-size:1.2rem;cursor:pointer}

        /* ===== Reveal / Tilt / Magnetic / Utilities (identiques logique postuler) ===== */
        @media (prefers-reduced-motion: reduce){
            .reveal,.card,.video-container,.btn-outline,.btn-brand,nav.navbar{transition:none !important; animation:none !important}
        }
        .reveal{opacity:0;transform:translateY(18px) scale(.98);filter:blur(2px);transition:opacity .6s var(--ease),transform .6s var(--ease),filter .6s var(--ease);transition-delay:var(--reveal-delay,0ms)}
        .reveal.reveal--in{opacity:1;transform:none;filter:none}
        .tilt{transition:transform .12s ease, box-shadow .2s ease; transform:perspective(900px) rotateX(var(--rx,0)) rotateY(var(--ry,0))}
        .flip-in{animation:flipIn .7s ease both; transform-origin:50% 60%}
        @keyframes flipIn{0%{transform:rotateX(90deg);opacity:0}100%{transform:none;opacity:1}}
        #toTop{
            position:fixed; right:18px; bottom:18px; z-index:999; width:44px; height:44px; border-radius:50%;
            background:var(--pi-blue); color:#fff; display:grid; place-items:center; box-shadow:0 10px 24px rgba(0,0,0,.25);
            opacity:0; pointer-events:none; transform:translateY(10px); transition:opacity .3s, transform .3s;
        }
        #toTop.show{opacity:1; pointer-events:auto; transform:none}

        /* Responsive quick */
        @media (max-width: 860px){
            .nav-links{display:none}
        }
    </style>
</head>
<body>
<div id="scrollProgress" aria-hidden="true"></div>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
    <div class="logo">
        <a href="index.php" class="d-flex align-items-center">
            <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
        </a>
    </div>
    <ul class="nav-links">
        <li><a href="#" class="active">Accueil</a></li>
        <li><a href="nosMagasins.php">Nos magasins</a></li>
        <li><a href="quiSommesNous.html">Notre histoire</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="postuler.php">Postuler</a></li>
    </ul>
    <div class="nav-buttons">
        <a href="pageConnexion.php" style="text-decoration:none;display:flex;flex-direction:column;align-items:center;">
            <i class="bi bi-person" style="font-size:28px;"></i>
            <span style="font-weight:600;color:#fff;font-size:.95rem">Me connecter / M'inscrire</span>
        </a>
    </div>
</nav>

<!-- ===== HERO (anim & design postuler) ===== -->
<header class="hero">
    <div class="wrap">
        <div class="hero-text reveal" style="--reveal-delay:0ms">
            <span class="badge mb-2">Paristanbul • Proximité & Qualité</span>
            <h1>Des produits frais et de qualité près de chez vous</h1>
            <p>Découvrez notre large sélection de produits frais, locaux et à prix compétitifs.</p>
            <div class="hero-buttons" style="display:flex;gap:.6rem;flex-wrap:wrap">
                <a href="#populaires" class="btn-outline magnet">Nos produits populaires</a>
                <a href="nosMagasins.php" class="btn-brand magnet">Trouver un magasin</a>
            </div>
        </div>
        <div class="hero-image reveal" style="--reveal-delay:90ms">
            <div class="video-container">
                <iframe width="100%" height="360"
                        src="https://www.youtube.com/embed/WgkwUxDKi_0?autoplay=1&mute=1&loop=1&playlist=WgkwUxDKi_0&playsinline=1"
                        title="Présentation Paristanbul" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
            </div>
        </div>
    </div>
</header>

<!-- ===== RAYONS ===== -->
<section class="section">
    <div class="container">
        <h2 class="section-title reveal" data-reveal="fade">Nos rayons</h2>
        <p class="section-subtitle reveal" style="--reveal-delay:60ms">Découvrez la diversité de nos produits frais et de qualité.</p>
        <div class="reveal" style="--reveal-delay:120ms; margin-bottom:16px">
            <a href="catalogue.php" class="btn-outline magnet">Découvrir nos rayons</a>
        </div>

        <div class="grid">
            <!-- Exemple de carte rayon -->
            <article class="card tilt reveal" style="--reveal-delay:0ms">
                <div class="icon mb-2"><i class="bi bi-cup-straw"></i></div>
                <h3 class="fw-bold" style="margin-bottom:6px">Boissons</h3>
                <p class="text" style="color:var(--muted);margin-bottom:10px">Jus, sodas, eaux et boissons chaudes pour tous les goûts.</p>
                <a href="#" class="btn-outline magnet" style="width:max-content">Découvrir →</a>
            </article>

            <article class="card tilt reveal" style="--reveal-delay:60ms">
                <div class="icon mb-2"><i class="bi bi-basket"></i></div>
                <h3 class="fw-bold" style="margin-bottom:6px">Produits frais</h3>
                <p style="color:var(--muted);margin-bottom:10px">Fruits, légumes, crèmerie — fraîcheur garantie.</p>
                <a href="#" class="btn-outline magnet" style="width:max-content">Découvrir →</a>
            </article>

            <article class="card tilt reveal" style="--reveal-delay:120ms">
                <div class="icon mb-2"><i class="bi bi-box-seam"></i></div>
                <h3 class="fw-bold" style="margin-bottom:6px">Produits secs</h3>
                <p style="color:var(--muted);margin-bottom:10px">Pâtes, riz, conserves — l’essentiel du placard.</p>
                <a href="#" class="btn-outline magnet" style="width:max-content">Découvrir →</a>
            </article>

            <article class="card tilt reveal" style="--reveal-delay:180ms">
                <div class="icon mb-2"><i class="bi bi-snow"></i></div>
                <h3 class="fw-bold" style="margin-bottom:6px">Surgelés</h3>
                <p style="color:var(--muted);margin-bottom:10px">Rapides, savoureux, toujours disponibles.</p>
                <a href="#" class="btn-outline magnet" style="width:max-content">Découvrir →</a>
            </article>
        </div>
    </div>
</section>

<!-- ===== PRODUITS POPULAIRES ===== -->
<section id="populaires" class="section produits">
    <div class="container">
        <h2 class="section-title reveal">Nos produits populaires</h2>
        <p class="section-subtitle reveal" style="--reveal-delay:60ms">Les produits préférés de nos clients.</p>

        <div class="grid">
            <article class="card tilt reveal" style="--reveal-delay:0ms">
                <div class="produit-image"><img src="placeholder.png" alt="Pommes Gala"/></div>
                <h3 class="fw-bold" style="margin:.75rem 0 .35rem">Pommes Gala</h3>
                <p style="color:var(--muted);margin-bottom:.6rem">Croquantes et sucrées, idéales en collation.</p>
                <div style="display:flex;gap:.5rem;align-items:baseline">
                    <span class="prix">2,49 €</span><span style="color:#9aa6c1">/ kg</span>
                </div>
            </article>

            <article class="card tilt reveal" style="--reveal-delay:60ms">
                <div class="produit-image"><img src="placeholder.png" alt="Pain de campagne"/></div>
                <h3 class="fw-bold" style="margin:.75rem 0 .35rem">Pain de campagne</h3>
                <p style="color:var(--muted);margin-bottom:.6rem">Mie dense & savoureuse.</p>
                <div><span class="prix">3,20 €</span> <span style="color:#9aa6c1">/ 500g</span></div>
            </article>

            <article class="card tilt reveal" style="--reveal-delay:120ms">
                <div class="produit-image"><img src="placeholder.png" alt="Fromage de chèvre"/></div>
                <h3 class="fw-bold" style="margin:.75rem 0 .35rem">Fromage de chèvre</h3>
                <p style="color:var(--muted);margin-bottom:.6rem">Frais et crémeux.</p>
                <div><span class="prix">4,50 €</span> <span style="color:#9aa6c1">/ 200g</span></div>
            </article>

            <article class="card tilt reveal" style="--reveal-delay:180ms">
                <div class="produit-image"><img src="placeholder.png" alt="Yaourt nature"/></div>
                <h3 class="fw-bold" style="margin:.75rem 0 .35rem">Yaourt nature</h3>
                <p style="color:var(--muted);margin-bottom:.6rem">Au lait entier, ultra onctueux.</p>
                <div><span class="prix">2,75 €</span> <span style="color:#9aa6c1">/ 4x125g</span></div>
            </article>
        </div>
    </div>
</section>

<!-- ===== PROMOTIONS ===== -->
<section class="section promos">
    <div class="container">
        <h2 class="section-title reveal">Nos promotions de la semaine</h2>
        <p class="section-subtitle reveal" style="--reveal-delay:60ms">Profitez de nos offres spéciales.</p>


        <div class="grid">
            <article class="card tilt reveal" style="--reveal-delay:0ms">
                <div class="promo-header">30%</div>
                <h3 class="fw-bold" style="margin-top:1.8rem">Fruits de saison</h3>
                <p style="color:var(--muted)">Réduction sur tous les fruits de saison.</p>
                <p><span class="barre">4,99 €</span> <span class="promo-prix">3,49 €</span> / kg</p>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span class="date">Jusqu'à dimanche</span>
                    <a href="#" class="btn-outline magnet">Voir plus</a>
                </div>
            </article>

            <article class="card tilt reveal" style="--reveal-delay:60ms">
                <div class="promo-header">2 + 1 GRATUIT</div>
                <h3 class="fw-bold" style="margin-top:1.8rem">Yaourts Bio</h3>
                <p style="color:var(--muted)">Le 3ème est offert (le moins cher).</p>
                <p><span class="barre">3,75 €</span> <span class="promo-prix">3,75 €</span> / 6x125g</p>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span class="date">Cette semaine</span>
                    <a href="#" class="btn-outline magnet">Voir plus</a>
                </div>
            </article>

            <article class="card tilt reveal" style="--reveal-delay:120ms">
                <div class="promo-header">-50% sur le 2ème</div>
                <h3 class="fw-bold" style="margin-top:1.8rem">Filet de saumon</h3>
                <p style="color:var(--muted)">Moitié prix sur le 2ème filet.</p>
                <p><span class="barre">12,90 €</span> <span class="promo-prix">9,68 €</span> / 250g</p>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span class="date">Jusqu'à mercredi</span>
                    <a href="#" class="btn-outline magnet">Voir plus</a>
                </div>
            </article>
        </div>

        <div class="reveal" style="--reveal-delay:180ms; margin-top:18px">
            <a href="#" class="btn-brand magnet">Voir toutes les promotions</a>
        </div>
    </div>
</section>

<?php

$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8','root','',[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$req = $pdo->prepare("SELECT *  FROM magasins ORDER BY id_magasin DESC LIMIT 3;");
$req->execute();
$lignesMagasin = $req->fetchAll(PDO::FETCH_ASSOC);


?>
<!-- ===== MAGASINS ===== -->
<section class="section">
    <div class="container">
        <h2 class="section-title reveal">Quelques magasins</h2>
        <p class="section-subtitle reveal" style="--reveal-delay:60ms">Trouvez le Paristanbul le plus proche.</p>

        <div class="grid">
            <?php foreach ($lignesMagasin as $magasin) :?>

                <article class="card tilt reveal" style="--reveal-delay:0ms">
                <h3 class="fw-bold mb-1">Paristanbul <?= htmlspecialchars($magasin['ville_magasin']) ?></h3>
                <p style="color:var(--muted)"><i class="bi bi-geo-alt-fill"></i><?= htmlspecialchars($magasin['rue'].", ". htmlspecialchars($magasin['cp'])) ?> </p>
                <p style="color:var(--muted)"><i class="bi bi-clock-fill"></i> Lun-Dim: 8h30–20h</p>
                <p style="color:var(--muted)"><i class="bi bi-telephone-fill"></i> +33 7 49 82 61 33</p>
                <a href="#" class="itineraire-btn magnet"
                   data-address="117 Avenue Pierre Semard, 95400 Villiers-le-Bel"
                   data-lat="49.0094" data-lon="2.3911">Itinéraire</a>
            </article>
            <?php endforeach; ?>


        </div>
    </div>
</section>

<!-- ===== CONTACT ===== -->
<section class="section contact" id="contact">
    <div class="container">
        <h2 class="section-title reveal">Contactez-nous</h2>
        <p class="section-subtitle reveal" style="--reveal-delay:60ms">Une question, une suggestion ?</p>

        <div class="wrap">
            <!-- Form -->
            <div class="form reveal" style="--reveal-delay:0ms">
                <h3 class="fw-bold">Envoyez-nous un message</h3>
                <form id="contactForm" action="javascript:void(0)" method="post" enctype="multipart/form-data" data-endpoint="contactSubmit.php">
                    <label>Nom complet</label>
                    <input type="text" name="nom" placeholder="Votre nom" required>
                    <label>Email</label>
                    <input type="email" name="email" placeholder="votre@email.com" required>
                    <label>Sujet</label>
                    <select name="sujet" required>
                        <option value="">Sélectionnez un sujet</option>
                        <option>Informations générales</option>
                        <option>Commande</option>
                        <option>Problème technique</option>
                    </select>
                    <label>Message</label>
                    <textarea name="message" rows="5" placeholder="Votre message..." required></textarea>
                    <button type="submit" class="btn-brand magnet">Envoyer le message</button>
                </form>
            </div>
            <!-- Infos -->
            <div class="side reveal" style="--reveal-delay:90ms">
                <h3 class="fw-bold">Service client</h3>
                <p><i class="bi bi-telephone-fill"></i> <strong>Téléphone</strong><br>07 49 82 61 33 (appel gratuit)</p>
                <p><i class="bi bi-envelope-fill"></i> <strong>Email</strong><br>parisistambulnogent@gmail.com</p>
                <p><i class="bi bi-clock-fill"></i> <strong>Horaires</strong><br>Lun–Ven : 9h00–18h00</p>

                <h3 class="fw-bold" style="margin-top:14px">Newsletter</h3>
                <p style="color:var(--muted)">Recevez nos promos & actus.</p>
                <div class="newsletter-form">
                    <input type="email" placeholder="Votre email" class="newsletter-field">
                    <button class="newsletter-submit"><i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-column paristanbul-col">
            <h3>Paristanbul</h3>
            <p>Rejoignez-nous sur les réseaux et accédez à nos offres et nouveautés en exclusivité.</p>
            <div class="social-icons" style="display:flex;gap:10px;flex-wrap:wrap">
                <a href="https://www.facebook.com/supermarcheparistanbul/" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/paristanbul_supermarche/?hl=fr" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://www.tiktok.com/@supermarche_paristanbul" class="tiktok"><i class="bi bi-tiktok"></i></a>
                <a href="https://www.youtube.com/channel/UCsjy3bdpFzBwM7MF923gKvA" class="youtube"><i class="bi bi-youtube"></i></a>
                <a href="#" class="paristanbul"><img src="../assets/img/logo.app.pi.webp" alt="Paristanbul" style="width:28px;height:28px;object-fit:contain;filter:drop-shadow(0 0 0)"></a>
            </div>
        </div>

        <div class="footer-column">
            <h3>L'enseigne Paristanbul</h3>
            <ul>
                <li><a href="quiSommesNous.html">Notre histoire</a></li>
                <li><a href="nosMagasins.php">Trouver un magasin</a></li>
                <li><a href="#contact">Nous contacter</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Actualités</h3>
            <ul>
                <li><a href="#">Nos nouveautés</a></li>
                <li><a href="#">Nos promotions</a></li>
                <li><a href="#" id="app-store-link">Télécharger l'application</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Nous rejoindre</h3>
            <ul>
                <li><a href="postuler.php">Nos offres d'emploi</a></li>
                <li><a href="#" id="app-store-link-2">Télécharger l'application</a></li>
            </ul>
        </div>

        <div class="footer-column newsletter-col">
            <h3>Newsletters</h3>
            <p>Abonnez-vous pour recevoir les dernières actus.</p>
            <div class="newsletter-form">
                <input type="email" placeholder="Votre email" class="newsletter-field"/>
                <button class="newsletter-submit"><i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
    </div>
</footer>

<!-- ===== MODAL “MERCI” ===== -->
<div id="piThanks" class="pi-modal" aria-hidden="true" role="dialog" aria-labelledby="piThanksTitle">
    <div class="pi-modal__backdrop" data-pi-close></div>
    <div class="pi-modal__dialog" role="document" tabindex="-1">
        <button class="pi-modal__close" aria-label="Fermer" data-pi-close style="position:absolute;top:10px;right:10px;width:40px;height:40px;border-radius:50%;border:0;background:#f3f5f8;cursor:pointer;font-size:22px">&times;</button>
        <div class="pi-modal__badge"><i class="bi bi-check2-circle"></i></div>
        <h3 id="piThanksTitle" class="pi-modal__title">Merci pour votre confiance&nbsp;!</h3>
        <p class="pi-modal__text">Votre message a bien été envoyé. Notre équipe vous répondra rapidement.</p>
        <div class="pi-modal__actions">
            <button class="pi-btn pi-btn--primary" data-pi-close>OK</button>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS (identiques logique à postuler.php) ===== -->
<script>
    /* Scroll progress */
    (function(){
        const bar=document.getElementById('scrollProgress');
        const onScroll=()=>{ const h=document.documentElement; const s=h.scrollTop; const d=h.scrollHeight-h.clientHeight; bar.style.width=(d?(s/d)*100:0)+'%'; };
        document.addEventListener('scroll', onScroll, {passive:true}); onScroll();
    })();

    /* Navbar shadow on scroll */
    (function(){
        const nav=document.querySelector('nav.navbar');
        const onS=()=>{ if(!nav) return; nav.classList.toggle('nav--scrolled', (window.scrollY||0)>8);}
        document.addEventListener('scroll', onS, {passive:true}); onS();
    })();

    /* Intersection reveal */
    (function(){
        const prefersReduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const els=[...document.querySelectorAll('.reveal, .card, .btn-outline, .btn-brand')];
        if(prefersReduced || !('IntersectionObserver' in window)){ els.forEach(el=>el.classList.add('reveal--in')); return; }
        const io=new IntersectionObserver((entries)=>{
            entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('reveal--in'); io.unobserve(e.target);} });
        },{threshold:.12, rootMargin:'0px 0px -10% 0px'});
        els.forEach((el,i)=>{ el.style.setProperty('--reveal-delay', (i%6)*60+'ms'); io.observe(el);});
    })();

    /* Ripple */
    document.querySelectorAll('.btn-outline, .btn-brand, .itineraire-btn').forEach(btn=>{
        btn.addEventListener('pointerdown',(e)=>{
            const r=document.createElement('span'); r.className='r';
            const rect=btn.getBoundingClientRect(); const d=Math.max(rect.width,rect.height);
            r.style.width=r.style.height=d+'px'; r.style.left=(e.clientX-rect.left-d/2)+'px'; r.style.top=(e.clientY-rect.top-d/2)+'px';
            btn.appendChild(r); r.addEventListener('animationend',()=>r.remove());
        });
    });

    /* Magnetic buttons (subtil) */
    (function(){
        const prefersReduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isTouch=('ontouchstart'in window)||navigator.maxTouchPoints>0;
        if(prefersReduced||isTouch) return;
        document.querySelectorAll('.magnet').forEach(el=>{
            const strength=10; let tX=0,tY=0,cX=0,cY=0,RAF=null;
            const lerp=(a,b,t)=>a+(b-a)*t;
            const animate=()=>{ cX=lerp(cX,tX,.18); cY=lerp(cY,tY,.18); el.style.transform=`translate(${cX}px,${cY}px)`; RAF=requestAnimationFrame(animate); };
            el.addEventListener('mousemove',e=>{
                const r=el.getBoundingClientRect(); const x=(e.clientX-r.left)/r.width-.5; const y=(e.clientY-r.top)/r.height-.5;
                tX=x*strength; tY=y*strength; if(!RAF) animate();
            });
            el.addEventListener('mouseleave',()=>{ tX=0; tY=0; cancelAnimationFrame(RAF); RAF=null; el.style.transform=''; });
        });
    })();

    /* Hero parallax (léger) */
    (function(){
        const prefersReduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isTouch=('ontouchstart'in window)||navigator.maxTouchPoints>0;
        if(prefersReduced||isTouch) return;
        const hero=document.querySelector('.hero');
        const text=document.querySelector('.hero .hero-text');
        const vid=document.querySelector('.hero .video-container');
        let tx=0,ty=0,cx=0,cy=0; const lerp=(a,b,t)=>a+(b-a)*t;
        hero?.addEventListener('mousemove',(e)=>{
            const r=hero.getBoundingClientRect(); tx=((e.clientX-r.left)/r.width-.5); ty=((e.clientY-r.top)/r.height-.5);
        });
        (function raf(){
            cx=lerp(cx,tx,.08); cy=lerp(cy,ty,.08);
            if(text) text.style.transform=`translate3d(${cx*8}px,${cy*8}px,0)`;
            if(vid)  vid.style.transform =`translate3d(${cx*-10}px,${cy*-10}px,0)`;
            requestAnimationFrame(raf);
        })();
    })();

    /* Tilt 3D pour .card */
    (function(){
        const prefersReduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if(prefersReduced) return;
        const MAX=10;
        document.querySelectorAll('.tilt, .card').forEach(card=>{
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

    /* Bouton remonter en haut */
    (function(){
        const btn=document.createElement('button'); btn.id='toTop'; btn.setAttribute('aria-label','Remonter en haut'); btn.innerHTML='<i class="bi bi-arrow-up"></i>'; document.body.appendChild(btn);
        const update=()=>btn.classList.toggle('show',(window.scrollY||0)>500);
        window.addEventListener('scroll', update, {passive:true}); update();
        btn.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
    })();

    /* Loader (logo + anneaux) */
    (function(){
        const L=document.createElement('div');
        L.id='piLoader';
        L.innerHTML=`<div class="loader__wrap"><div class="loader__ring"></div><div class="loader__ring"></div><img class="loader__logo" src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul"></div>`;
        const css=`
    #piLoader{position:fixed;inset:0;z-index:9999;display:grid;place-items:center;background:
      radial-gradient(1200px 700px at 15% 10%, rgba(46,76,151,.2), transparent 60%),
      radial-gradient(1000px 700px at 85% 15%, rgba(214,69,46,.16), transparent 55%), #0A0F1F; transition:opacity .4s ease,visibility .4s ease}
    #piLoader.hide{opacity:0;visibility:hidden}
    .loader__wrap{position:relative;width:160px;height:160px;display:grid;place-items:center}
    .loader__ring{position:absolute;inset:0;border-radius:50%;border:3px solid rgba(255,255,255,.12);border-top-color: var(--pi-blue);animation:spin 1.1s linear infinite}
    .loader__ring:nth-child(1){inset:12px;animation-duration:1.6s;border-top-color:var(--pi-red)}
    @keyframes spin{to{transform:rotate(360deg)}}
    .loader__logo{width:120px;filter:drop-shadow(0 8px 20px rgba(0,0,0,.35))}
  `;
        const s=document.createElement('style'); s.textContent=css; document.head.appendChild(s);
        document.body.appendChild(L);
        const minShow=new Promise(r=>setTimeout(r,500));
        Promise.all([minShow,new Promise(r=>(window.onload=r))]).then(()=>{requestAnimationFrame(()=>L.classList.add('hide')); setTimeout(()=>L.remove(),800);});
    })();

    /* Shimmer produits + compteurs prix */
    (function(){
        document.querySelectorAll('.produit-image').forEach(box=>{
            const img=box.querySelector('img'); if(!img) return;
            const done=()=>box.classList.remove('shimmer'); box.classList.add('shimmer');
            if(img.complete) done(); else img.addEventListener('load',done,{once:true});
        });
        const fmtFR=n=>new Intl.NumberFormat('fr-FR',{minimumFractionDigits:(n%1?2:0),maximumFractionDigits:2}).format(n);
        const parsePrice=txt=>{ const raw=(txt||'').replace(/\s/g,'').replace(',', '.').match(/-?\d+(\.\d+)?/); return raw?parseFloat(raw[0]):0; };
        const animateNumber=(el,to,d=900)=>{ const from=0; const start=performance.now(); el.classList.add('counting');
            const step=(t)=>{ const p=Math.min(1,(t-start)/d); const eased=1-Math.pow(1-p,3); const val=from+(to-from)*eased; el.textContent=fmtFR(val); if(p<1) requestAnimationFrame(step); else { el.textContent=fmtFR(to); el.classList.remove('counting'); el.classList.add('flip-in'); el.addEventListener('animationend',()=>el.classList.remove('flip-in'),{once:true}); } };
            requestAnimationFrame(step);
        };
        if('IntersectionObserver' in window){
            const io=new IntersectionObserver((entries)=>{
                entries.forEach(e=>{
                    if(!e.isIntersecting) return;
                    const el=e.target; const n=parsePrice(el.textContent);
                    if(!el.dataset.animated){ el.dataset.animated='1'; animateNumber(el,n); }
                    io.unobserve(el);
                });
            },{threshold:.6});
            document.querySelectorAll('.prix, .promo-prix').forEach(el=>io.observe(el));
        }
    })();

    /* Itinéraires Google Maps */
    (function(){
        document.querySelectorAll('.itineraire-btn').forEach(btn=>{
            btn.addEventListener('click',(e)=>{
                e.preventDefault();
                const lat=btn.dataset.lat, lon=btn.dataset.lon, addr=btn.dataset.address;
                let dest='';
                if(lat && lon) dest=`${lat},${lon}`; else if(addr) dest=encodeURIComponent(addr); else return;
                const base=`https://www.google.com/maps/dir/?api=1&destination=${dest}`;
                if('geolocation' in navigator){
                    navigator.geolocation.getCurrentPosition(
                        pos=>{ const origin=`${pos.coords.latitude},${pos.coords.longitude}`; window.open(`${base}&origin=${origin}`,'_blank','noopener'); },
                        ()=>window.open(base,'_blank','noopener'),
                        { enableHighAccuracy:true, timeout:6000, maximumAge:0}
                    );
                }else{ window.open(base,'_blank','noopener'); }
            });
        });
    })();

    /* Modal 'Merci' + envoi AJAX contact */
    (function(){
        const modal=document.getElementById('piThanks');
        const open=()=>{ modal.classList.add('pi-modal--open'); document.body.classList.add('pi-modal-open'); modal.querySelector('.pi-modal__dialog')?.focus(); };
        const close=()=>{ modal.classList.remove('pi-modal--open'); document.body.classList.remove('pi-modal-open'); };
        modal?.querySelectorAll('[data-pi-close]').forEach(el=>el.addEventListener('click',close));
        modal?.addEventListener('click',e=>{ if(e.target.classList.contains('pi-modal__backdrop')) close(); });
        document.addEventListener('keydown',e=>{ if(e.key==='Escape') close(); });

        const form=document.getElementById('contactForm');
        if(!form) return;
        const submitBtn=form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async (e)=>{
            e.preventDefault(); if(!form.reportValidity()) return;
            const oldBtn=submitBtn.innerHTML;
            submitBtn.disabled=true; submitBtn.innerHTML='Envoi en cours <span class="pi-spinner" aria-hidden="true" style="display:inline-block;width:18px;height:18px;border-radius:50%;border:3px solid rgba(255,255,255,.5);border-top-color:#fff;vertical-align:-3px;animation:spin .7s linear infinite;margin-left:8px"></span>';
            try{
                const endpoint=form.dataset.endpoint||form.getAttribute('action')||location.href;
                const res=await fetch(endpoint,{method:'POST', body:new FormData(form), headers:{'X-Requested-With':'fetch'}});
                const isJson=res.headers.get('content-type')?.includes('application/json');
                const data=isJson?await res.json():{success:res.ok};
                if(!res.ok || !data.success) throw new Error(data?.error||'Une erreur est survenue. Merci de réessayer.');
                form.reset(); open();
            }catch(err){ alert(err.message||'Erreur réseau. Merci de réessayer.'); }
            finally{ submitBtn.disabled=false; submitBtn.innerHTML=oldBtn; }
        });
    })();

    /* Lien store (détection) */
    document.addEventListener('DOMContentLoaded', ()=>{
        const go=(el)=>{
            el?.addEventListener('click', (e)=>{
                e.preventDefault();
                const ua=navigator.userAgent||navigator.vendor||window.opera;
                if(/android/i.test(ua)) window.location.href="https://play.google.com/store/apps/details?id=com.tonapp.nom";
                else if(/iPad|iPhone|iPod/.test(ua) && !window.MSStream) window.location.href="https://apps.apple.com/fr/app/ton-app/idXXXXXXXXX";
                else window.location.href="https://paristanbul.fr/telecharger";
            });
        };
        go(document.getElementById('app-store-link'));
        go(document.getElementById('app-store-link-2'));
    });
</script>
</body>
</html>
