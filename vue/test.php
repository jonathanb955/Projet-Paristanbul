<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Footer Paristanbul</title>
    <link rel="stylesheet" href="footer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<footer class="footer">
    <div class="footer-container">

        <!-- Colonne 1 -->
        <div class="footer-column paristanbul-col">
            <h3>Paristanbul</h3>
            <p>
                Rejoignez-nous sur les réseaux et accédez à nos<br>
                offres et nouveautés en exclusivité.
            </p>
            <div class="social-icons">
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="tiktok"><i class="bi bi-tiktok"></i></a>
                <a href="#" class="youtube"><i class="bi bi-youtube"></i></a>
                <a href="#" class="paristanbul">
                    <img src="../assets/img/logo.app.pi.webp" alt="Logo Paristanbul">
                </a>
            </div>
        </div>

        <!-- Colonne 2 -->
        <div class="footer-column">
            <h3>L'enseigne Paristanbul</h3>
            <ul>
                <li><a href="#">Notre histoire</a></li>
                <li><a href="#">Trouver un magasin</a></li>
                <li><a href="#">Nous contacter</a></li>
            </ul>
        </div>

        <!-- Colonne 3 -->
        <div class="footer-column">
            <h3>Actualités</h3>
            <ul>
                <li><a href="#">Nos nouveautés</a></li>
                <li><a href="#">Nos promotions</a></li>
                <li><a href="#">Télécharger l'application</a></li>
            </ul>
        </div>

        <!-- Colonne 4 -->
        <div class="footer-column">
            <h3>Nous rejoindre</h3>
            <ul>
                <li><a href="#">Nos offres d'emploi</a></li>
                <li><a href="#">Télécharger l'application</a></li>
            </ul>
        </div>

        <!-- Colonne 5 -->
        <div class="footer-column newsletter-col">
            <h3>Newsletters</h3>
            <p>
                Abonnez-vous à notre newsletter pour recevoir nos dernières actualités.
            </p>
            <div class="newsletter-form">
                <input type="email" placeholder="Votre email" class="newsletter-field" />
                <button class="newsletter-submit">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

    </div>
</footer>

</body>
</html>

<style>
    .footer {
        background-color: #0c0b12;
        color: white;
        padding: 40px 20px;
        font-family: 'Segoe UI', sans-serif;
    }

    .footer-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        width: 100%;
        padding: 0 40px;
    }

    .footer-column {
        flex: 1 1 200px;
        min-width: 200px;
        margin-bottom: 30px;
    }
    .newsletter-form {
        display: flex;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 15px;
        width: 100%;          /* ✅ s’adapte au parent */
        max-width: 260px;     /* ✅ limite pour éviter le débordement */
    }
    .newsletter-col {
        margin-right: 0; /* ou -10px maximum si tu veux un petit décalage */
    }
    .newsletter-col {
        margin-left: 20px; /* ✅ décale la colonne vers la gauche */
        margin-right: 0;   /* ❌ évite un décalage extrême à droite */
    }


    .footer-column h3 {
        font-size: 1.2rem;
        margin-bottom: 15px;
        color: white;
    }

    .footer-column p {
        font-size: 0.95rem;
        color: #e2e2e2;
        line-height: 1.6;
    }

    .footer-column ul {
        list-style: none;
        padding: 0;
    }

    .footer-column ul li {
        margin-bottom: 10px;
    }

    .footer-column ul li a {
        color: #e2e2e2;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .footer-column ul li a:hover {
        text-decoration: underline;
    }

    /* Réseaux sociaux */
    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }

    .social-icons a {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        text-decoration: none;
        background-color: #444;
        overflow: hidden;
    }

    .social-icons i {
        font-size: 1.4rem;
    }

    /* Images dans les logos ronds */
    .social-icons img {
        width: 60%;
        height: 60%;
        object-fit: contain;
    }

    /* Couleurs spécifiques */
    .social-icons a.facebook { background-color: #1877F2; }
    .social-icons a.instagram {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 50%, #d6249f 75%, #285AEB 100%);
    }
    .social-icons a.tiktok { background-color: #000000; }
    .social-icons a.youtube { background-color: #FF0000; }
    .social-icons a.paristanbul { background-color: white; }

    /* Champ newsletter */
    .newsletter-form {
        display: flex;
        width: 100%;
        max-width: 300px;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 15px;
    }

    .newsletter-field {
        flex: 1;
        border: none;
        padding: 14px 16px;
        font-size: 1rem;
        outline: none;
        background-color: transparent;
        color: #333;
    }

    .newsletter-submit {
        background-color: #7366ff;
        border: none;
        padding: 0 20px;
        font-size: 1.2rem;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 0;
    }

    .newsletter-submit:hover {
        background-color: #5c53d7;
    }

    .newsletter-submit i {
        font-family: "bootstrap-icons";
        font-style: normal;
    }

    /* Décalages personnalisés */
    .paristanbul-col {
        margin-left: -20px;
    }

    .newsletter-col {
        margin-right: -20px;
    }
</style>