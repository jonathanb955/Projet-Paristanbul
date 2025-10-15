<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pied de page – Rejoignez-nous</title>

    <!-- Police -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root{
            --bg:#f3eeee;          /* fond très clair rosé/gris */
            --ink:#222;            /* texte principal */
            --muted:#a8a3a3;       /* texte copyright */
            --accent:#cf5a59;      /* rouge rosé des lignes/titres */
            --chip:#333;           /* pastilles icônes */
        }

        *{box-sizing:border-box}
        html,body{margin:0}
        body{font-family:Montserrat,system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial,sans-serif;background:#fff;color:var(--ink)}

        footer.cta-footer{
            background:var(--bg);
            padding:48px 20px 26px;
        }
        footer .wrap{
            max-width:1100px;
            margin:0 auto;
            text-align:center;
        }

        /* Logo */
        .brand{
            height:72px;           /* ajuste selon ton logo */
            width:auto;
            object-fit:contain;
            margin:0 auto 22px;
            display:block;
        }

        /* Titre avec lignes */
        .headline{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:22px;
            margin:10px auto 18px;
        }
        .headline h2{
            margin:0;
            font-weight:700;
            letter-spacing:.12em;
            color:var(--accent);
            font-size:24px;
        }
        .headline .line{
            height:4px;
            width:260px;           /* longueur des traits */
            background:var(--accent);
            border-radius:2px;
        }
        @media (max-width:720px){
            .headline .line{width:20vw}
            .headline h2{font-size:20px}
        }

        /* Réseaux sociaux */
        .social{
            list-style:none;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:14px;
            padding:0;
            margin:14px 0 20px;
        }
        .social a{
            width:42px;height:42px;
            display:grid;place-items:center;
            background:var(--chip);
            color:#fff;
            border-radius:50%;
            text-decoration:none;
            font-size:18px;
            transition:transform .2s ease, background .2s ease;
        }
        .social a:hover{background:var(--accent); transform:translateY(-2px)}

        /* Menu bas de page */
        .footer-nav{
            display:flex;
            flex-wrap:wrap;
            justify-content:center;
            gap:26px 30px;
            padding:12px 0 8px;
            margin:0 auto 12px;
        }
        .footer-nav a{
            text-decoration:none;
            color:var(--ink);
            font-weight:700;
            font-size:14px;
            letter-spacing:.04em;
            text-transform:uppercase;
            transition:color .2s ease;
        }
        .footer-nav a:hover{color:var(--accent)}

        /* Copyright */
        .copyright{
            margin:6px 0 0;
            font-size:12px;
            color:var(--muted);
            user-select:none;
        }
    </style>
</head>
<body>

<footer class="cta-footer">
    <div class="wrap">
        <!-- Remplacer le src par ton fichier -->
        <img src="logo.png" alt="Logo" class="brand">

        <div class="headline">
            <span class="line" aria-hidden="true"></span>
            <h2>REJOIGNEZ-NOUS</h2>
            <span class="line" aria-hidden="true"></span>
        </div>

        <ul class="social" aria-label="Réseaux sociaux">
            <li><a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
            <li><a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a></li>
            <li><a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a></li>
            <li><a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
        </ul>

        <nav class="footer-nav" aria-label="Navigation pied de page">
            <a href="#">Accueil</a>
            <a href="#">Quilles</a>
            <a href="#">Actualités</a>
            <a href="#">Calendrier</a>
            <a href="#">Galerie</a>
            <a href="#">À propos</a>
            <a href="#">Contact</a>
            <a href="#">Boutique</a>
            <a href="#">Mentions légales</a>
            <a href="#">CGV</a>
        </nav>

        <p class="copyright">
            Copyright © 2018 - 2019 CTS Quilles au Maillet. Tous droits réservés.
        </p>
    </div>
</footer>

</body>
</html>
<?php
