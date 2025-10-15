<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Header — Paristanbul</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>

    <style>
        :root{ --text:#111; --muted:#8f8f8f; --line:#d9d9d9; --bg:#fff; }
        *{box-sizing:border-box}
        html,body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,system-ui,Segoe UI,Roboto,Arial}
        a{color:inherit;text-decoration:none}
        .container{width:min(1100px,92vw);margin-inline:auto}

        /* ----- TOPBAR ----- */
        .topbar{
            display:grid; grid-template-columns:1fr auto 1fr;
            align-items:center; gap:16px; padding:14px 0;
        }

        /* GAUCHE : bloc compact (icônes + texte centré sous icônes) */
        .left-col{display:flex; align-items:flex-start}
        .social-group{display:flex; flex-direction:column; align-items:center; width:max-content}
        .social{display:flex; align-items:center; gap:16px; color:#7a7a7a}
        .social a{font-size:18px; color:#9a9a9a}
        .social a:hover{color:#555}
        .join{font-size:13px; color:#6a6a6a; font-weight:700; margin-top:6px; text-align:center}

        /* CENTRE : logo + Since encadré */
        .brand{display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px}
        .brand img{height:56px; width:auto; display:block}
        .tagline{display:flex; align-items:center; gap:12px; color:var(--muted); font-size:13px; line-height:1}
        .tagline .rule{width:64px; height:1px; background:var(--line)}

        /* DROITE : téléphone */
        .right-col{display:flex; justify-content:flex-end; align-items:center; gap:10px; font-weight:700}
        .right-col i{color:#5a5a5a}
        .phone{font-size:14px; color:#333}

        .divider{border:0; border-top:1px solid var(--line); margin:0}

        /* NAV centré */
        .navrow{padding:12px 0}
        .menu{display:flex; justify-content:center; gap:28px; list-style:none; margin:0; padding:0}
        .menu a{font-weight:600; font-size:14px; color:#5a5a5a; letter-spacing:.06em; text-transform:uppercase}
        .menu a:hover{color:#222}

        /* Mobile */
        @media (max-width:720px){
            .topbar{grid-template-columns:1fr; row-gap:10px; text-align:center}
            .left-col{justify-content:center}
            .social-group{margin:0 auto}
            .brand img{height:48px}
            .tagline .rule{width:48px}
            .menu{flex-wrap:wrap; gap:18px}
        }
    </style>
</head>
<body>

<header>
    <!-- TOP BAR -->
    <div class="container topbar">
        <!-- GAUCHE : réseaux + “Rejoignez nous” centré sous les icônes -->
        <div class="left-col">
            <div class="social-group">
                <nav class="social" aria-label="Réseaux sociaux">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="X / Twitter"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </nav>
                <div class="join">Rejoignez nous</div>
            </div>
        </div>

        <!-- CENTRE : LOGO + Since -->
        <div class="brand">
            <!-- Remplace par ton logo -->
            <img src="path/to/paristanbul-logo.png" alt="Paristanbul">
            <div class="tagline">
                <span class="rule" aria-hidden="true"></span>
                <span>Since July 2024</span>
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
            <li><a href="#">Science</a></li>
            <li><a href="#">Politics</a></li>
            <li><a href="#">Economics</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </div>

    <hr class="divider">
</header>

</body>
</html>
