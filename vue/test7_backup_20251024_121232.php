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

        /* Header */
        .app-header{background:rgba(15,22,35,.95); backdrop-filter:blur(12px); position:fixed; width:100%; top:0; left:0; z-index:100; border-bottom:1px solid var(--border);}
        .header-wrap{display:flex; justify-content:space-between; align-items:center; height:70px; padding:0 24px;}
        .logo{height:40px;}
        .nav-links{display:flex; gap:28px;}
        .nav-links a{font-weight:500; color:var(--muted); transition:color 0.2s;}
        .nav-links a:hover{color:var(--text);}
        .nav-links a.active{color:var(--pi-red); font-weight:600;}

        /* Hero Section */
        .app-hero{padding:180px 0 100px; background:var(--page-bg); position:relative; overflow:hidden;}
        .hero-content{display:flex; align-items:center; gap:60px; position:relative; z-index:2;}
        .hero-text{flex:1; max-width:600px;}
        .hero-text h1{font-size:clamp(2.5rem, 5vw, 4rem); font-weight:800; line-height:1.1; margin-bottom:24px; background:linear-gradient(45deg, #fff, #c9d4ea); -webkit-background-clip:text; -webkit-text-fill-color:transparent;}
        .hero-text p{font-size:1.2rem; color:var(--muted); margin-bottom:32px; max-width:500px;}
        .app-badges{display:flex; gap:16px; margin-top:32px;}
        .app-badge{height:50px; transition:transform 0.2s;}
        .app-badge:hover{transform:translateY(-3px);}
        .hero-image{flex:1; position:relative;}
        .phone-mockup{width:100%; max-width:300px; margin:0 auto; position:relative; animation:float 6s ease-in-out infinite;}
        .phone-mockup img{width:100%; height:auto; display:block;}
        .floating-element{position:absolute; background:var(--pi-red); width:100px; height:100px; border-radius:50%; filter:blur(60px); opacity:0.3;}
        .floating-1{top:10%; left:10%; width:200px; height:200px; background:var(--pi-blue);}
        .floating-2{bottom:10%; right:10%; width:150px; height:150px;}

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
            .nav-links{gap:16px;}
            .app-hero{padding:140px 0 80px;}
            .features{padding:80px 0;}
            .features-grid{grid-template-columns:1fr;}
            .cta-content h2{font-size:2rem;}
        }

        @media (max-width: 576px){
            .header-wrap{padding:0 16px;}
            .nav-links{gap:12px; font-size:0.9rem;}
            .app-badges{flex-direction:column; align-items:center;}
            .btn{padding:10px 20px; font-size:0.9rem;}
        }
    </style>
</head>
<body>
<!-- Header -->
<header class="app-header">
    <div class="header-wrap">
        <a href="index.php" class="logo">
            <img src="/assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" height="40">
        </a>
        <nav class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="catalogue.php">Catalogue</a>
            <a href="magasins.php">Nos Magasins</a>
            <a href="contact.php">Contact</a>
            <a href="applicationTel.php" class="active">Application</a>
            <?php if($isLoggedIn): ?>
                <a href="monCompte.php">Mon Compte</a>
                <a href="deconnexion.php">Déconnexion</a>
            <?php else: ?>
                <a href="connexion.php">Connexion</a>
                <a href="inscription.php" style="color:var(--pi-red);">Inscription</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Hero Section -->
<section class="app-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
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
                        position: relative;
                        line-height: 1.2;
                        padding-bottom: 0.2em;
                        animation: gradientMove 5s ease-in-out infinite;
                    }
                </style>
                <h1><span class="gradient-text">L'application Paristanbul</span> dans votre poche</h1>
                <p>Accédez à nos offres exclusives, consultez nos catalogues numériques, gérez vos listes de courses et profitez de réductions personnalisées où que vous soyez.</p>
                <div class="app-badges">
                    <a href="https://play.google.com/store/apps/details?id=com.akead.paristanbul&hl=fr" class="app-badge" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; height: 65px; margin-top: -7px;">
                        <img src="https://play.google.com/intl/en_us/badges/static/images/badges/fr_badge_web_generic.png" alt="Disponible sur Google Play" style="height: 100%; width: auto;">
                    </a>
                    <a href="https://apps.apple.com/fr/app/paristanbul-plus/id6743162682" class="app-badge" target="_blank" rel="noopener noreferrer">
                        <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="Télécharger sur l'App Store" height="50">
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <div class="phone-mockup">
                    <img src="https://i.ibb.co/3zQY1kC/iphone-mockup.png" alt="Application mobile Paristanbul">
                </div>
            </div>
        </div>
    </div>
    <div class="floating-element floating-1"></div>
    <div class="floating-element floating-2"></div>
</section>

<!-- Features Section -->
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

<!-- Comment ça marche -->
<section class="how-it-works">
    <div class="container">
        <h2>Comment ça marche ?</h2>
        <p class="subtitle">Installation en 3 minutes.</p>

        <div class="steps-container">
            <!-- Étape 1 -->
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Téléchargez</h3>
                <p>Ouvrez l'App Store ou Google Play et installez "Paristanbul".</p>
            </div>

            <!-- Étape 2 -->
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Créez un compte</h3>
                <p>Renseignez votre e-mail et validez en un instant.</p>
            </div>

            <!-- Étape 3 -->
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Activez la fidélité</h3>
                <p>Votre carte apparaît automatiquement dans l'app.</p>
            </div>

            <!-- Étape 4 -->
            <div class="step-card">
                <div class="step-number">4</div>
                <h3>Profitez</h3>
                <p>Scannez en caisse, cumulez des points, recevez des récompenses.</p>
            </div>
        </div>
    </div>
</section>

<style>
    /* Styles pour la section Comment ça marche */
    .how-it-works {
        padding: 80px 0;
        background: #f8f9fa;
        text-align: center;
    }

    .how-it-works h2 {
        font-size: 2.5rem;
        color: #2E4C97;
        margin-bottom: 15px;
    }

    .subtitle {
        font-size: 1.2rem;
        color: #6c757d;
        margin-bottom: 50px;
    }

    .steps-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .step-card {
        background: white;
        border-radius: 12px;
        padding: 30px 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }

    .step-number {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #2E4C97, #D6452E);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 auto 20px;
    }

    .step-card h3 {
        color: #2E4C97;
        margin-bottom: 15px;
        font-size: 1.3rem;
    }

    .step-card p {
        color: #6c757d;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .steps-container {
            grid-template-columns: 1fr;
            max-width: 500px;
        }

        .step-card {
            text-align: center;
        }
    }
</style>

<!-- CTA Section -->
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

<!-- Footer -->
<footer style="background:var(--bg-2); padding:60px 0 30px; border-top:1px solid var(--border);">
    <div class="container" style="text-align:center;">
        <img src="/assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" height="50" style="margin-bottom:20px;">
        <p style="color:var(--muted); margin-bottom:30px; max-width:600px; margin-left:auto; margin-right:auto;">
            Paristanbul - Votre supermarché de proximité préféré. Retrouvez-nous sur les réseaux sociaux.
        </p>
        <div style="display:flex; justify-content:center; gap:20px; margin-bottom:30px;">
            <a href="#" style="color:var(--muted); font-size:24px; transition:color 0.2s;"><i class="fab fa-facebook"></i></a>
            <a href="#" style="color:var(--muted); font-size:24px; transition:color 0.2s;"><i class="fab fa-instagram"></i></a>
            <a href="#" style="color:var(--muted); font-size:24px; transition:color 0.2s;"><i class="fab fa-twitter"></i></a>
            <a href="#" style="color:var(--muted); font-size:24px; transition:color 0.2s;"><i class="fab fa-youtube"></i></a>
        </div>
        <div style="border-top:1px solid var(--border); padding-top:20px; color:var(--muted); font-size:0.9rem;">
            &copy; <?php echo date('Y'); ?> Paristanbul. Tous droits réservés.
        </div>
    </div>
</footer>

<script>
    // Animation au défilement
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes au défilement
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Appliquer l'animation aux cartes de fonctionnalités
        document.querySelectorAll('.feature-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
            observer.observe(card);
        });

        // Animation du titre de section
        const sectionTitles = document.querySelectorAll('.section-title');
        sectionTitles.forEach(title => {
            title.style.opacity = '0';
            title.style.transform = 'translateY(20px)';
            title.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(title);

            // Déclenchement de l'animation après un court délai
            setTimeout(() => {
                if (title.isConnected) {
                    title.style.opacity = '1';
                    title.style.transform = 'translateY(0)';
                }
            }, 300);
        });
    });
</script>
</body>
</html>
