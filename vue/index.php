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

    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://unpkg.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Preload critical CSS -->
    <link rel="preload" href="../assets/css/index.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="../assets/css/index.css"></noscript>
    
    <!-- Load non-critical CSS asynchronously -->
    <!-- Chargement optimisé des polices avec display=swap -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" media="print" onload="this.media='all'" />
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    </noscript>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css" media="print" onload="this.media='all'" />
    
    <!-- Load CSS for print -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" media="print" onload="this.media='all'" />
    
    <!-- Critical CSS inlined -->
    <style>
        /* Critical CSS for above-the-fold content */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.6; }
        .container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 15px; }
        img { max-width: 100%; height: auto; display: block; }
        
        /* Animation de base pour les éléments au défilement */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .animate-on-scroll.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Optimisation des performances pour les animations */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>


</head>
<body>

<?php if (!empty($flash)): ?>
    <div id="toast" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" style="position:fixed;right:16px;top:16px;z-index:9999; padding:10px 14px;border-radius:10px; background:rgba(16,185,129,.95);color:#fff;font-weight:700; border:1px solid rgba(16,185,129,.4);box-shadow:0 10px 30px rgba(0,0,0,.25)">
        <?= htmlspecialchars($flash) ?>
    </div>
    <script>
    // Chargement différé du script principal
    window.addEventListener('load', function() {
        var script = document.createElement('script');
        script.src = '/Projet-Paristanbul/assets/js/index.js';
        script.defer = true;
        document.body.appendChild(script);
    });
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
        <div class="brand" style="display: flex; justify-content: center; width: 100%;">
            <a href="index.php" class="navbar-brand" style="display: block; max-width: 100%;">
                <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" style="height: auto; width: auto; max-height: 72px; max-width: 100%; object-fit: contain;">
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

                    <a href="nosMagasins.php" class="btn magnet"
                       style="background:linear-gradient(145deg,#8B1A1A,#A32929);border:1px solid #A32929;">
                        Voir nos magasins
                    </a>
                </div>
            </div>

            <!-- Col média (vidéo) -->
            <div class="hero-media">
                <iframe
                        src="https://www.youtube-nocookie.com/embed/2IEctnY7Qas?controls=1&playsinline=1&modestbranding=1&rel=0&showinfo=0&autoplay=1&mute=1&loop=1&playlist=2IEctnY7Qas"
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
    <section class="section" id="appPromo" style="padding-top:32px; padding-bottom:32px;">
        <div class="container apppromo-wrap">
            <!-- Col texte -->
            <div class="apppromo-left animate-on-scroll from-left">
                <div class="eyebrow">Ne ratez plus une promo</div>

                <h2 class="apppromo-title">
                    Dites bonjour à
                    <span class="gradient-text">toutes nos promos</span>
                    en direct 📲
                </h2>

                <p class="apppromo-lead">
                    Notre application Paristanbul vous donne l’accès immédiat aux
                    <strong>offres du moment</strong>, aux <strong>réductions exclusives</strong>
                    et au <strong>catalogue à jour</strong> — avant même l’affichage en magasin.
                </p>

                <ul class="apppromo-bullets">
                    <li>
                        <i class="bi bi-fire"></i>
                        <span>Promos fraîchement ajoutées chaque semaine</span>
                    </li>
                    <li>
                        <i class="bi bi-bell"></i>
                        <span>Alertes sur vos produits favoris</span>
                    </li>
                    <li>
                        <i class="bi bi-geo-alt"></i>
                        <span>Magasin le plus proche & horaires</span>
                    </li>
                </ul>

                <div class="apppromo-cta-row">
                    <button id="openAppModal" class="ctaDownloadApp">
                        <i class="fa-solid fa-download" aria-hidden="true"></i>
                        <span>TÉLÉCHARGER L’APPLI</span>
                    </button>

                    <div class="apppromo-small-text">
                        Gratuit • iOS &amp; Android
                    </div>
                </div>
            </div>

            <!-- Col visuel téléphone -->
            <!-- Col visuel téléphone -->
            <div class="apppromo-right animate-on-scroll from-right" data-parallax data-speed="0.015">
                <div class="apppromo-phoneCard">
                    <div class="apppromo-phoneMock">
                        <img src="../assets/img/app_paristanbul_mockup.jpeg"
                             alt="Application Paristanbul — interface de fidélité"
                             style="width:100%;height:100%;object-fit:cover;display:block;border-radius:20px;">
                    </div>

                    <div class="apppromo-badge">
                        <div class="apppromo-badge-num">+100</div>
                        <div class="apppromo-badge-label">
                            promos <br>chaque semaine
                        </div>
                    </div>
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
                <!-- (legacy appModal removed: using new centered popup instead) -->
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

<!-- SCRIPTS EXTERNES (chargement différé) -->
<script>
// Détection des fonctionnalités
const supportsIntersectionObserver = 'IntersectionObserver' in window;
const supportsLoading = 'loading' in HTMLImageElement.prototype;

// Variable globale pour PageFlip
window.St = { PageFlip: null };

// Fonction pour charger les scripts de manière asynchrone
function loadScript(src, async = true, defer = true) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.async = async;
        script.defer = defer;
        script.onload = resolve;
        script.onerror = reject;
        document.body.appendChild(script);
    });
}

// Chargement des scripts non critiques après le chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé, démarrage de l\'initialisation...');
    
    // Vérifier si le catalogue est présent dans la page
    const catalogSection = document.getElementById('catalog');
    if (!catalogSection) {
        console.warn('La section du catalogue est introuvable dans le DOM');
        return;
    }
    
    // Suppression de l'ancien conteneur d'erreur s'il existe
    const existingErrorContainer = catalogSection.querySelector('.catalog-error');
    if (existingErrorContainer) {
        existingErrorContainer.remove();
    }
    
    // Charger PageFlip
    console.log('Chargement de PageFlip...');
    loadScript('https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js', true, false)
        .then(() => {
            console.log('PageFlip chargé avec succès');
            
            // Mettre à jour la référence globale
            window.St = window.St || {};
            window.St.PageFlip = window.St.PageFlip || window.PageFlip;
            
            if (!window.St || !window.St.PageFlip) {
                throw new Error('PageFlip n\'a pas été correctement chargé');
            }
            
            // Vérifier que la fonction d'initialisation est disponible
            if (typeof initFlip !== 'function') {
                throw new Error('La fonction initFlip n\'est pas définie');
            }
            
            // Démarrer l'initialisation avec un léger délai
            console.log('Démarrage de l\'initialisation du catalogue...');
            setTimeout(() => initFlip(0), 300);
        })
        .catch(error => {
            console.error('Erreur lors du chargement de PageFlip:', error);
            
            // En cas d'erreur, on ne fait rien pour éviter d'afficher un message d'erreur
            console.error('Erreur lors du chargement de PageFlip:', error);
        });

    // Charger Leaflet en parallèle
    loadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', true, true);
});

// Optimisation des images - lazy loading
if ('loading' in HTMLImageElement.prototype) {
    const images = document.querySelectorAll('img[loading="lazy"]');
    images.forEach(img => {
        if (img.dataset.src) {
            img.src = img.dataset.src;
        }
        if (img.dataset.srcset) {
            img.srcset = img.dataset.srcset;
        }
    });
}

// Optimisation des iframes - lazy loading
document.addEventListener('DOMContentLoaded', function() {
    const iframes = document.querySelectorAll('iframe[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const iframeObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const iframe = entry.target;
                    if (iframe.dataset.src) {
                        iframe.src = iframe.dataset.src;
                        iframeObserver.unobserve(iframe);
                    }
                }
            });
        });

        iframes.forEach(iframe => iframeObserver.observe(iframe));
    } else {
        // Fallback pour les navigateurs qui ne supportent pas IntersectionObserver
        iframes.forEach(iframe => {
            if (iframe.dataset.src) {
                iframe.src = iframe.dataset.src;
            }
        });
    }
});

// Gestion des animations au défilement
function handleIntersection(entries, observer) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
            // Ne pas supprimer l'observation pour éviter les clignotements
            // lors du défilement vers le haut
        } else {
            // Ne pas retirer la classe 'active' pour maintenir l'état visible
            // entry.target.classList.remove('active');
        }
    });
}

// Configuration de l'IntersectionObserver avec un rootMargin plus grand
// pour déclencher l'animation plus tôt
const observer = new IntersectionObserver(handleIntersection, {
    root: null,
    rootMargin: '0px 0px -20% 0px', // Détecte les éléments 20% avant qu'ils n'entrent dans la vue
    threshold: 0.01 // Déclenche dès que 1% de l'élément est visible
});

// Observer tous les éléments avec la classe animate-on-scroll
document.querySelectorAll('.animate-on-scroll').forEach(el => {
    observer.observe(el);
});

// Optimisation des requêtes de police
const loadFonts = () => {
    if ('fonts' in document) {
        Promise.all([
            document.fonts.load('1em Plus Jakarta Sans'),
            document.fonts.load('700 1em Plus Jakarta Sans'),
            document.fonts.load('800 1em Plus Jakarta Sans')
        ]).then(() => {
            document.documentElement.classList.add('fonts-loaded');
        });
    }
};

// Démarrer le chargement des polices après le chargement critique
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadFonts);
} else {
    loadFonts();
}
</script>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.js"></script>
<script src="/Projet-Paristanbul/assets/js/index.js" defer></script>

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

    // pas d'animation scroll -> tout direct visible
    $$('.section-hd, .catalog-app, .pi-sites, #advantages .carousel, #stores, #contact .contact-panel, footer .wrap').forEach(n=>{
        n.style.opacity='1';
        n.style.transform='none';
    });
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
        // Chemin absolu vers les images du catalogue
        const PATH = window.location.origin + '/Projet-Paristanbul/assets/pages';
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
            try {
                console.log('Initialisation du catalogue...');
                console.log('Chemin des images:', pages);
                
                await detectAspect();
                const { width, height, usePortrait } = computeSize();
                pageW = width;

                if(pageFlip) { 
                    pageFlip.destroy(); 
                }

                // Vérifier que St.PageFlip est disponible
                if (!window.St || !window.St.PageFlip) {
                    console.error('PageFlip n\'est pas correctement chargé');
                    throw new Error('La bibliothèque PageFlip n\'est pas disponible');
                }

                // Créer une nouvelle instance de PageFlip
                pageFlip = new window.St.PageFlip(flipEl, {
                    width,
                    height,
                    size: 'fixed',
                    showCover: true,
                    usePortrait,
                    autoSize: true,
                    maxShadowOpacity: 0.5,
                    mobileScrollSupport: true,
                    startPage: startIndex
                });

                // Charger les images
                pageFlip.loadFromImages(pages);
                
                // Configurer les événements
                pageFlip.on('flip', coverMaskAndCenter);
                
                // Mettre à jour l'échelle et la position
                scale = baseScale;
                coverMaskAndCenter();
                
                console.log('Catalogue initialisé avec succès');
                
                // Cacher le message d'erreur si tout s'est bien passé
                const errorContainer = document.querySelector('.catalog-error');
                if (errorContainer) {
                    errorContainer.style.display = 'none';
                }
                
                // Initialiser les boutons de contrôle
                const zoomIn = document.getElementById('zoomIn');
                const zoomOut = document.getElementById('zoomOut');
                const fitBtn = document.getElementById('fitBtn');
                
                // Ajouter les écouteurs d'événements
                if (prevBtn) prevBtn.addEventListener('click', () => pageFlip?.flipPrev());
                if (nextBtn) nextBtn.addEventListener('click', () => pageFlip?.flipNext());
                if (zoomIn) zoomIn.addEventListener('click', () => {
                    scale = Math.min(2.0, scale + 0.1);
                    coverMaskAndCenter();
                });
                if (zoomOut) zoomOut.addEventListener('click', () => {
                    scale = Math.max(0.5, scale - 0.1);
                    coverMaskAndCenter();
                });
                if (fitBtn) fitBtn.addEventListener('click', () => {
                    scale = baseScale;
                    coverMaskAndCenter();
                });
                
            } catch (error) {
                console.error('Erreur lors de l\'initialisation du catalogue:', error);
                
                // Afficher le message d'erreur uniquement si le catalogue n'est pas déjà affiché
                const flipbook = document.getElementById('flipbook');
                if (!flipbook || flipbook.children.length === 0) {
                    const errorContainer = document.querySelector('.catalog-error');
                    if (errorContainer) {
                        errorContainer.textContent = 'Erreur lors du chargement du catalogue. ' + (error.message || 'Erreur inconnue');
                        errorContainer.style.display = 'block';
                    }
                }
            }
        }
        
        // Gestion du redimensionnement
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const current = pageFlip ? pageFlip.getCurrentPageIndex() : 0;
                initFlip(current);
            }, 150);
        });
        });

        // L'initialisation est maintenant gérée par le gestionnaire d'événements DOMContentLoaded
        // pour s'assurer que PageFlip est chargé avant d'initialiser
        }
    })();

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
<script>
    (function(){
        // ====== SLIDES "RAYONS" ======
        const slides = [
            {
                title: "Boucherie sélection",
                city:  "Halal & fraîche du jour",
                img:   "../assets/img/DSC09743.JPG"
            },
            {
                title: "Fruits & Légumes frais",
                city:  "Primeur de saison",
                img:   "../assets/img/DSC09757.JPG"
            },
            {
                title: "Boissons & rafraîchissements",
                city:  "Jus, soft & plus",
                img:   "../assets/img/DJI_0264.JPG"
            },
            {
                title: "Hygiène & Entretien",
                city:  "Pour la maison",
                img:   "../assets/img/hygiene.jpg"
            },
            {
                title: "Surgelé",
                city:  "Ultra-frais à -18°C",
                img:   "../assets/img/surgele.jpg"
            },
            {
                title: "Emballage & Gros volumes",
                city:  "Cartons, packs, stock",
                img:   "../assets/img/emballage.jpg"
            },
            {
                title: "Produits secs",
                city:  "Épicerie & pâtes",
                img:   "../assets/img/produits-secs.jpg"
            }
        ];

        const stack   = document.getElementById('piSitesStack');
        const titleEl = document.getElementById('piSitesTitle');
        const cityEl  = document.getElementById('piSitesCity');
        const prevBtn = document.getElementById('piSitesPrev');
        const nextBtn = document.getElementById('piSitesNext');

        function getCards(){
            return {
                leftFigure:   stack.querySelector('.pi-role-left'),
                centerFigure: stack.querySelector('.pi-role-center'),
                rightFigure:  stack.querySelector('.pi-role-right')
            };
        }
        function getImgs(){
            return {
                left:   stack.querySelector('.pi-role-left img'),
                center: stack.querySelector('.pi-role-center img'),
                right:  stack.querySelector('.pi-role-right img')
            };
        }

        const N   = slides.length;
        let idx   = 0;
        let busy  = false;
        const T   = 560;
        const mod = (n,m)=>((n%m)+m)%m;

        function renderCurrentTriplet(){
            const {left, center, right} = getImgs();

            const prevSlide = slides[ mod(idx-1, N) ];
            const currSlide = slides[ idx ];
            const nextSlide = slides[ mod(idx+1, N) ];

            if (left)   { left.src   = prevSlide.img;  left.alt   = prevSlide.title; }
            if (center) { center.src = currSlide.img;  center.alt = currSlide.title; }
            if (right)  { right.src  = nextSlide.img;  right.alt  = nextSlide.title; }

            titleEl.textContent = currSlide.title;
            cityEl.textContent  = currSlide.city;

            titleEl.style.animation = 'none';
            void titleEl.offsetWidth;
            titleEl.style.animation = 'piTitleIn .60s cubic-bezier(.22,.61,.36,1)';
        }

        function goNext(){
            if (busy) return;
            busy = true;
            stack.classList.add('pi-shift-next');
            setTimeout(()=>{
                idx = mod(idx+1, N);
                stack.classList.remove('pi-shift-next');

                const {leftFigure, centerFigure, rightFigure} = getCards();
                leftFigure.classList.replace('pi-role-left','pi-role-right');
                centerFigure.classList.replace('pi-role-center','pi-role-left');
                rightFigure.classList.replace('pi-role-right','pi-role-center');

                renderCurrentTriplet();
                busy = false;
            }, T);
        }

        function goPrev(){
            if (busy) return;
            busy = true;
            stack.classList.add('pi-shift-prev');
            setTimeout(()=>{
                idx = mod(idx-1, N);
                stack.classList.remove('pi-shift-prev');

                const {leftFigure, centerFigure, rightFigure} = getCards();
                rightFigure.classList.replace('pi-role-right','pi-role-left');
                centerFigure.classList.replace('pi-role-center','pi-role-right');
                leftFigure.classList.replace('pi-role-left','pi-role-center');

                renderCurrentTriplet();
                busy = false;
            }, T);
        }

        nextBtn.addEventListener('click', goNext);
        prevBtn.addEventListener('click', goPrev);

        (function autoplay(){
            const area   = document.querySelector('.pi-sites');
            const DELAY  = 4800;
            let timer    = null;

            function start(){
                stop();
                timer = setTimeout(function tick(){
                    if (!busy) goNext();
                    start();
                }, DELAY);
            }
            function stop(){
                if (timer){ clearTimeout(timer); timer = null; }
            }

            start();
            area?.addEventListener('mouseenter', stop);
            area?.addEventListener('mouseleave', start);
            area?.addEventListener('focusin',    stop);
            area?.addEventListener('focusout',   start);
            area?.addEventListener('touchstart', stop,  {passive:true});
            area?.addEventListener('touchend',   start, {passive:true});
            nextBtn.addEventListener('click', start);
            prevBtn.addEventListener('click', start);
            document.addEventListener('visibilitychange', ()=>{
                if (document.hidden) stop();
                else start();
            });
        })();

        document.addEventListener('keydown', e=>{
            if(e.key === 'ArrowRight') goNext();
            if(e.key === 'ArrowLeft')  goPrev();
        });

        renderCurrentTriplet();
    })();
</script>
<!-- ... ton footer / "Contactez-nous" / etc ... -->



<!-- ====== POPUP APP DOWNLOAD ====== -->
<!-- ====== POPUP APP DOWNLOAD ====== -->
<div class="appPromo-overlay is-hidden" id="appPromo-overlay"></div>

<div class="appPromo-card is-hidden" id="appPromo-card" role="dialog" aria-labelledby="appPromo-title" aria-modal="true">
    <button class="appPromo-close" id="appPromo-close" aria-label="Fermer la fenêtre">
        <span aria-hidden="true">&times;</span>
    </button>

    <div class="appPromo-headline">
        <div class="appPromo-eyebrow">PARISTANBUL APP</div>
        <h2 class="appPromo-title" id="appPromo-title">Téléchargez l’application</h2>
        <p class="appPromo-desc">
            Courses, promos en direct, carte de fidélité digitale. Gratuit sur iOS et Android.
        </p>
    </div>

    <div class="appPromo-badges">
        <a class="store-badge"
           href="https://apps.apple.com/fr/app/paristanbul-plus/id6743162682"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Ouvrir Paristanbul sur l’App Store (iOS)">
            <img src="../assets/img/badge-appstore.png" alt="Disponible sur l’App Store">
        </a>

        <a class="store-badge"
           href="https://play.google.com/store/apps/details?id=com.akead.paristanbul&hl=fr"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Ouvrir Paristanbul sur Google Play (Android)">
            <img src="../assets/img/badge-googleplay.png" alt="Disponible sur Google Play">
        </a>
    </div>

    <div class="appPromo-footnote">Bientôt disponible officiellement.</div>
</div>

<script>
    (function () {
        const card     = document.getElementById('appPromo-card');
        const overlay  = document.getElementById('appPromo-overlay');
        const closeBtn = document.getElementById('appPromo-close');
        const trigger  = document.getElementById('openAppModal'); // ton bouton "TÉLÉCHARGER L’APPLI"

        function openPopup() {
            card.classList.remove('is-hidden');
            overlay.classList.remove('is-hidden');
            card.classList.add('is-visible');
        }


        function closePopup() {
            card.classList.remove('is-visible');
            card.classList.add('is-hidden');
            overlay.classList.add('is-hidden');
        }

        if (trigger) {
            trigger.addEventListener('click', function(e){
                e.preventDefault();
                openPopup();
            });
        }

        closeBtn.addEventListener('click', closePopup);
        overlay.addEventListener('click', closePopup);
    })();
</script>
<!-- ====== /POPUP APP DOWNLOAD ====== -->

</body>
</html>