<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Paristanbul — Télécharger l’application</title>
    <meta name="description" content="Téléchargez l’app Paristanbul : promos en avant-première, carte de fidélité, magasins à deux pas. Disponible sur iOS et Android."/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

    <style>
        :root{
            --pi-blue:#2E4C97; --pi-red:#D6452E;
            --text:#fff; --muted:#c9d4ea; --edge:rgba(255,255,255,.08);
            --bg-1:#0B1326; --bg-2:#0A0F1F;
            --card:#141B2B; --chip:#1B2436; --ring:#2c59ff55;
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0; font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;
            color:var(--text); background:
                radial-gradient(1000px 500px at 10% 0%, rgba(46,76,151,.22), transparent 60%),
                radial-gradient(1000px 600px at 90% 0%, rgba(214,69,46,.16), transparent 55%),
                linear-gradient(180deg, var(--bg-1), var(--bg-2) 70%);
        }
        a{color:inherit;text-decoration:none}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}

        /* Header minimal */
        header{
            position:sticky; top:0; z-index:10;
            background:linear-gradient(180deg, #0f1525ee, #0c1223ee);
            border-bottom:1px solid #141a2b; backdrop-filter:blur(8px);
        }
        .topbar{
            display:flex; align-items:center; justify-content:space-between;
            gap:16px; padding:16px 0;
        }
        .brand{display:flex; align-items:center; gap:12px; font-weight:900; letter-spacing:.02em}
        .brand img{height:38px; width:auto; display:block}
        .nav{display:flex; gap:18px; font-weight:800; color:#dbe6ff}
        .nav a{opacity:.9} .nav a:hover{opacity:1}

        /* Badges stores */
        .store-badges{display:flex; gap:10px; flex-wrap:wrap}
        .store{
            display:inline-flex; align-items:center; gap:10px;
            padding:10px 14px; border-radius:12px; border:1px solid #223055;
            background:linear-gradient(145deg,#101831,#0c1224);
            font-weight:800; color:#eaf0ff;
            transition:transform .08s ease, filter .18s ease, border-color .18s ease;
        }
        .store i{font-size:20px}
        .store small{display:block; font-weight:700; line-height:1; opacity:.8}
        .store span{display:block; font-size:15px; line-height:1.1}
        .store:hover{filter:brightness(1.06); border-color:#2a3d73; transform:translateY(-1px)}

        /* HERO */
        #hero{padding:46px 0 26px}
        .hero-grid{display:grid; grid-template-columns:1.05fr .95fr; gap:36px; align-items:center}
        @media (max-width:980px){ .hero-grid{grid-template-columns:1fr; gap:26px} }
        .eyebrow{font-size:.9rem; color:var(--muted); letter-spacing:.2em; text-transform:uppercase}
        .hero-title{
            margin:.35rem 0 .7rem; font-weight:900; line-height:1.06; letter-spacing:-.02em;
            font-size: clamp(34px, 5.2vw, 86px);
        }
        .hero-title .line, .hero-title .kicker{display:block; white-space:nowrap}
        @media (max-width:980px){ .hero-title .line,.hero-title .kicker{white-space:normal} }
        .gradient-text{
            background-image:linear-gradient(90deg, var(--pi-red), var(--pi-blue), var(--pi-red));
            background-size:200% 100%;
            -webkit-background-clip:text; background-clip:text;
            -webkit-text-fill-color:transparent; color:transparent;
            animation:ink-move 8s ease-in-out infinite;
        }
        @keyframes ink-move{0%,100%{background-position:0% 50%} 50%{background-position:100% 50%}}

        .hero-lead{font-size:1.1rem; color:#e3eaff; margin:0 0 14px}

        .hero-card{
            position:relative; border:1px solid var(--edge); border-radius:18px;
            background:linear-gradient(180deg,#0f1525,#0b101d); padding:16px;
            box-shadow:0 18px 50px rgba(0,0,0,.35), inset 0 1px 0 #ffffff10;
        }
        .qr-wrap{display:grid; grid-template-columns:140px 1fr; gap:14px; align-items:center}
        .qr{
            width:140px; height:140px; border-radius:12px; overflow:hidden;
            background:#0c1224; display:grid; place-items:center; border:1px solid #223055;
        }
        .qr img, .qr canvas{width:100%; height:100%; object-fit:cover; display:block}
        .hint{color:#cfe0ff; font-weight:700}

        /* Features */
        section{padding:56px 0}
        .section-hd{display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:18px}
        .section-hd h2{margin:0; font-size:clamp(24px,3.3vw,40px)}
        .sub{color:var(--muted)}
        .features{display:grid; grid-template-columns:repeat(4,1fr); gap:14px}
        @media (max-width:980px){ .features{grid-template-columns:repeat(2,1fr)} }
        @media (max-width:560px){ .features{grid-template-columns:1fr} }
        .feat{
            background:linear-gradient(180deg,#11192d,#0c1222); border:1px solid #1e2740;
            border-radius:16px; padding:16px; transition:transform .15s ease, box-shadow .15s ease, border-color .15s;
        }
        .feat:hover{ transform:translateY(-2px); border-color:#2a3659; box-shadow:0 14px 38px rgba(0,0,0,.32) }
        .ico{width:48px;height:48px;border-radius:12px;display:grid;place-items:center;background:#1B2436;margin-bottom:8px}
        .ico i{font-size:22px;color:#eaf0ff}
        .feat h3{margin:.25rem 0 .25rem; font-size:1.05rem; font-weight:900}
        .feat p{margin:0; color:#c9d4ea}

        /* Steps */
        .steps{display:grid; grid-template-columns:repeat(4,1fr); gap:14px}
        @media (max-width:980px){ .steps{grid-template-columns:repeat(2,1fr)} }
        @media (max-width:560px){ .steps{grid-template-columns:1fr} }
        .step{background:linear-gradient(180deg,#0f1525,#0c1222); border:1px solid #1e2740; border-radius:16px; padding:16px}
        .badge{display:inline-grid; place-items:center; width:34px; height:34px; border-radius:10px; background:#1B2436; border:1px solid #213055; font-weight:900; margin-bottom:10px}

        /* Screens */
        .screens{
            display:grid; grid-template-columns:1fr 1fr; gap:14px;
        }
        @media (max-width:980px){ .screens{grid-template-columns:1fr} }
        .phone{
            border-radius:28px; padding:14px; background:linear-gradient(180deg,#0e1423,#0b101c);
            border:1px solid #1e2740; box-shadow:0 18px 48px rgba(0,0,0,.35); aspect-ratio:9/19; display:grid; place-items:center;
        }
        .phone img{width:100%; height:100%; object-fit:cover; border-radius:20px}

        /* FAQ (details) */
        .faq{max-width:900px; margin:0 auto}
        details{
            background:linear-gradient(180deg,#0f1525,#0c1222); border:1px solid #1e2740; border-radius:14px; padding:14px 16px;
        }
        details+details{margin-top:10px}
        summary{
            display:flex; align-items:center; justify-content:space-between; gap:12px; cursor:pointer; font-weight:900;
            list-style:none; color:#eaf0ff;
        }
        summary::-webkit-details-marker{display:none}
        details[open]{border-color:#2a3659}

        /* Footer */
        footer{
            padding:28px 0 20px; border-top:1px solid #141a2b;
            background:linear-gradient(180deg,#0f1525,#0c1223);
        }
        .social{list-style:none; display:flex; gap:12px; justify-content:center; padding:0; margin:12px 0 0}
        .social a{ width:40px;height:40px; display:grid; place-items:center; border-radius:50%; background:#101733; border:1px solid #1e2740; color:#cfe0ff }
        .social a:hover{ background:linear-gradient(145deg,var(--pi-blue),var(--pi-red)); color:#fff }
        .copyright{ text-align:center; color:#c9d4ea; font-size:12px; margin-top:10px }

        /* Small helpers */
        .btn{
            display:inline-flex; align-items:center; gap:10px; padding:12px 16px; border-radius:14px;
            border:1px solid #1f2842; background:linear-gradient(145deg,#151c32,#0f1424); font-weight:800
        }
        .grid-gap-14{display:grid; gap:14px}
    </style>
</head>
<body>

<header>
    <div class="container topbar">
        <div class="brand">
            <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
            <span>Paristanbul</span>
        </div>
        <nav class="nav">
            <a href="index.php">Accueil</a>
            <a href="nosMagasins.php">Magasins</a>
            <a href="index.php#catalog">Catalogue</a>
            <a href="postuler.php">Postuler</a>
        </nav>
    </div>
</header>

<main>
    <!-- HERO -->
    <section id="hero" class="container">
        <div class="hero-grid">
            <div>
                <div class="eyebrow">L’app Paristanbul</div>
                <h1 class="hero-title">
                    <span class="line">Vos promos, votre fidélité,</span>
                    <span class="kicker">à <span class="gradient-text">deux pas</span> de chez vous.</span>
                </h1>
                <p class="hero-lead">Recevez les offres en avant-première, cumulez des points, trouvez le magasin le plus proche et gardez vos tickets de caisse au même endroit.</p>

                <div class="grid-gap-14">
                    <div class="store-badges">
                        <!-- Remplacez les href par vos liens stores -->
                        <a class="store" href="https://apps.apple.com/app/id000000" target="_blank" rel="noopener">
                            <i class="fa-brands fa-apple"></i>
                            <div><small>Télécharger sur</small><span>App Store</span></div>
                        </a>
                        <a class="store" href="https://play.google.com/store/apps/details?id=com.paristanbul" target="_blank" rel="noopener">
                            <i class="fa-brands fa-google-play"></i>
                            <div><small>Disponible sur</small><span>Google Play</span></div>
                        </a>
                    </div>

                    <div class="hero-card">
                        <div class="qr-wrap">
                            <!-- QR code (changez data-app-url) -->
                            <div class="qr">
                                <img id="qrImg" alt="QR code de l’app" loading="lazy">
                            </div>
                            <div>
                                <div class="hint">Scannez pour télécharger l’app.</div>
                                <small style="color:#9fb2dc">iOS 13+ • Android 8.0+. Les liens s’ouvrent dans le store compatible avec votre appareil.</small>
                                <div style="margin-top:10px" class="store-badges">
                                    <a class="store" href="#faq"><i class="bi bi-question-circle"></i><span>Aide & FAQ</span></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Visuel téléphone -->
            <div class="screens">
                <div class="phone">
                    <img src="../assets/app/screen-1.jpg" alt="Capture de l’app Paristanbul">
                </div>
                <div class="phone">
                    <img src="../assets/app/screen-2.jpg" alt="Capture de l’app Paristanbul">
                </div>
            </div>
        </div>
    </section>

    <!-- AVANTAGES -->
    <section class="container" id="avantages">
        <div class="section-hd">
            <h2>Pourquoi télécharger l’app ?</h2>
            <div class="sub">Tout pour vos courses, au bon prix.</div>
        </div>
        <div class="features">
            <article class="feat">
                <div class="ico"><i class="bi bi-stars"></i></div>
                <h3>Promos en avant-première</h3>
                <p>Recevez les offres avant tout le monde et ne ratez plus les bons plans.</p>
            </article>
            <article class="feat">
                <div class="ico"><i class="bi bi-credit-card-2-front"></i></div>
                <h3>Fidélité intégrée</h3>
                <p>Carte dématérialisée, points cumulés, avantages personnalisés.</p>
            </article>
            <article class="feat">
                <div class="ico"><i class="bi bi-geo-alt"></i></div>
                <h3>Magasins près de vous</h3>
                <p>Horaires, itinéraire, affluence : trouvez l’adresse idéale en 1 clic.</p>
            </article>
            <article class="feat">
                <div class="ico"><i class="bi bi-receipt"></i></div>
                <h3>Tickets & garanties</h3>
                <p>Conservez vos tickets au même endroit et simplifiez vos retours.</p>
            </article>
        </div>
    </section>

    <!-- COMMENT ÇA MARCHE -->
    <section class="container" id="how">
        <div class="section-hd">
            <h2>Comment ça marche ?</h2>
            <div class="sub">Installation en 3 minutes.</div>
        </div>
        <div class="steps">
            <div class="step">
                <div class="badge">1</div>
                <h3>Téléchargez</h3>
                <p>Ouvrez l’App Store ou Google Play, puis installez “Paristanbul”.</p>
            </div>
            <div class="step">
                <div class="badge">2</div>
                <h3>Créez votre compte</h3>
                <p>Renseignez votre e-mail et validez en un instant.</p>
            </div>
            <div class="step">
                <div class="badge">3</div>
                <h3>Activez la fidélité</h3>
                <p>Votre carte apparaît automatiquement dans l’app.</p>
            </div>
            <div class="step">
                <div class="badge">4</div>
                <h3>Profitez</h3>
                <p>Scannez en caisse, cumulez des points, recevez des récompenses.</p>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="container" id="faq">
        <div class="section-hd">
            <h2>Questions fréquentes</h2>
        </div>

        <div class="faq">
            <details>
                <summary>
                    L’app est-elle gratuite ?
                    <i class="bi bi-chevron-down"></i>
                </summary>
                <div class="sub" style="margin-top:8px">Oui, le téléchargement et l’utilisation sont 100% gratuits.</div>
            </details>

            <details>
                <summary>
                    Sur quels appareils fonctionne l’app ?
                    <i class="bi bi-chevron-down"></i>
                </summary>
                <div class="sub" style="margin-top:8px">iPhone (iOS 13+) et Android (Android 8.0+). Nous améliorons régulièrement la compatibilité.</div>
            </details>

            <details>
                <summary>
                    Comment récupérer ma carte de fidélité existante ?
                    <i class="bi bi-chevron-down"></i>
                </summary>
                <div class="sub" style="margin-top:8px">Créez votre compte avec le même numéro/é-mail qu’en magasin. Votre carte se rattache automatiquement.</div>
            </details>

            <details>
                <summary>
                    J’ai un souci de connexion.
                    <i class="bi bi-chevron-down"></i>
                </summary>
                <div class="sub" style="margin-top:8px">Utilisez “Mot de passe oublié ?”. Si besoin, contactez le support : <a href="mailto:parisistambulnogent@gmail.com">parisistambulnogent@gmail.com</a>.</div>
            </details>
        </div>
    </section>
</main>

<footer>
    <div class="container" style="text-align:center">
        <div class="store-badges" style="justify-content:center; margin-bottom:10px">
            <a class="store" href="https://apps.apple.com/app/id000000" target="_blank" rel="noopener">
                <i class="fa-brands fa-apple"></i>
                <div><small>Télécharger sur</small><span>App Store</span></div>
            </a>
            <a class="store" href="https://play.google.com/store/apps/details?id=com.paristanbul" target="_blank" rel="noopener">
                <i class="fa-brands fa-google-play"></i>
                <div><small>Disponible sur</small><span>Google Play</span></div>
            </a>
        </div>

        <ul class="social" aria-label="Réseaux sociaux">
            <li><a href="https://www.facebook.com/supermarcheparistanbul/?locale=fr_FR" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
            <li><a href="https://www.instagram.com/paristanbul_supermarche/?hl=fr" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="https://www.tiktok.com/@supermarche_paristanbul" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a></li>
            <li><a href="https://www.youtube.com/channel/UCsjy3bdpFzBwM7MF923gKvA" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a></li>
        </ul>

        <p class="copyright">© <span id="year"></span> Paristanbul — Tous droits réservés.</p>
    </div>
</footer>

<script>
    // Année footer
    document.getElementById('year').textContent = new Date().getFullYear();

    // Génération QR (utilise un service public d'image QR)
    // Remplacez appUrl par l’URL de votre app / page de redirection smart.
    const appUrl = 'https://paristanbul.fr/app'; // <-- À personnaliser
    const qrImg = document.getElementById('qrImg');
    const size = 280; // génère une image nette même en retina
    qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=${size}x${size}&data=${encodeURIComponent(appUrl)}`;

    // Défilement doux si besoin
    document.querySelectorAll('a[href^="#"]').forEach(a=>{
        a.addEventListener('click', e=>{
            const id = a.getAttribute('href').slice(1);
            const el = document.getElementById(id);
            if(el){ e.preventDefault(); el.scrollIntoView({behavior:'smooth', block:'start'}); }
        });
    });
</script>
</body>
</html>
