<?php
/**************************************
 * POSTULER — Paristanbul (header/footer identiques à la Home)
 **************************************/

// ---------- CONFIG UPLOAD ----------
$MAX_FILE_SIZE_MB = 5; // limite 5 Mo
$ALLOWED_EXT = ['pdf','doc','docx'];
$ALLOWED_MIME = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

// ---------- UTIL: Connexion BDD multi-essais ----------
function db_connect(): PDO {
    $attempts = [
            ['host'=>'127.0.0.1', 'port'=>8889, 'user'=>'root', 'pass'=>'root'],  // MAMP
            ['host'=>'127.0.0.1', 'port'=>3306, 'user'=>'root', 'pass'=>''],      // MySQL
    ];
    $last = null;
    foreach ($attempts as $a) {
        try {
            $pdo = new PDO(
                    "mysql:host={$a['host']};port={$a['port']};dbname=bdd_paristanbul;charset=utf8mb4",
                    $a['user'],
                    $a['pass'],
                    [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false,
                    ]
            );
            return $pdo;
        } catch (Throwable $e) { $last = $e; }
    }
    throw new Exception("Impossible de se connecter à MySQL. Dernière erreur: " . ($last? $last->getMessage(): 'n/a'));
}

// ---------- FEEDBACK UI ----------
$form_status = null; // 'success' | 'error'
$form_message = '';
$uploaded_cv_public = null;

// ---------- HANDLE POST ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $bdd = new PDO('mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        // Champs
        $nom              = trim($_POST['nom'] ?? '');
        $prenom           = trim($_POST['prenom'] ?? '');
        $telephone        = trim($_POST['telephone'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $date_naissance   = !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null;
        $langues          = trim($_POST['langues'] ?? '');
        $adresse          = trim($_POST['adresse'] ?? '');
        $permis           = trim($_POST['permis'] ?? '');
        $experiences      = trim($_POST['experiences'] ?? '');
        $lettre_motivation= trim($_POST['lettre_motivation'] ?? '');
        $ref_offre        = !empty($_POST['ref_offre']) ? (int)$_POST['ref_offre'] : (!empty($_GET['id']) ? (int)$_GET['id'] : null);
        $date_candidature = date("Y-m-d H:i:s");

        if ($nom === '' || $prenom === '' || $email === '') throw new Exception("Merci de renseigner au minimum prénom, nom et email.");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))      throw new Exception("L'email n'est pas valide.");

        // Upload CV
        $upload_dir = __DIR__ . "/telechargement/candidatures/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $nettoyer_nom    = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($nom));
        $nettoyer_prenom = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($prenom));

        $lien_cv = null;
        if (!empty($_FILES['cv']['name'])) {
            $cvTmp     = $_FILES['cv']['tmp_name'];
            $cvName    = $_FILES['cv']['name'];
            $cvSize    = (int)$_FILES['cv']['size'];
            $cvType    = mime_content_type($cvTmp) ?: '';
            $extension = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));

            if (!in_array($extension, $ALLOWED_EXT, true)) throw new Exception("Extension de fichier non autorisée (pdf, doc, docx).");
            if (!in_array($cvType, $ALLOWED_MIME, true))   throw new Exception("Type de fichier non autorisé.");
            if ($cvSize > $MAX_FILE_SIZE_MB * 1024 * 1024) throw new Exception("Fichier trop volumineux (max {$MAX_FILE_SIZE_MB} Mo).");

            $hash  = substr(md5(uniqid('', true)), 0, 10);
            $fname = "cv_{$nettoyer_nom}_{$nettoyer_prenom}_{$hash}.{$extension}";
            $dest  = $upload_dir . $fname;

            if (!move_uploaded_file($cvTmp, $dest)) throw new Exception("Échec lors de l'upload du CV.");

            $lien_cv = "telechargement/candidatures/" . $fname;
            $uploaded_cv_public = $lien_cv;
        } else {
            throw new Exception("Le CV est obligatoire (PDF, DOC, DOCX).");
        }

        // Insert
        $sql = $bdd->prepare("
            INSERT INTO candidatures
            (nom, prenom, email, date_naissance, langues, adresse, telephone, permis,
             experiences, lettre_motivation, ref_offre, date_candidature, statut, lien_cv)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $ok = $sql->execute([
                $nom, $prenom, $email, $date_naissance, $langues, $adresse, $telephone, $permis,
                $experiences, $lettre_motivation, $ref_offre, $date_candidature, "Nouveau", $lien_cv
        ]);
        if (!$ok) throw new Exception("Une erreur est survenue lors de l'enregistrement.");

        $form_status = 'success';
        $form_message = "Candidature envoyée avec succès. Nous revenons vers vous sous 15 jours.";
    } catch (Exception $e) {
        $form_status = 'error';
        $form_message = $e->getMessage();
    }
}

// Pré-remplir ref_offre
$ref_offre_from_get = isset($_GET['id']) ? (int)$_GET['id'] : null;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Paristanbul — Recrutement</title>
    <meta name="description" content="Rejoignez Paristanbul : offres d'emploi et candidature spontanée." />

    <!-- Fonts + Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap (pour ta grille/form) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root{
            /* Palette globale (identique index) */
            --black:#0a0c10; --blue:#0b3b8a; --red:#7b0f20;
            --text:#ffffff; --muted:#c9d4ea; --panel:#0f1320; --ring:#2c59ff55;
            --pi-blue:#2E4C97; --pi-red:#D6452E;
            --bg-1:#0B1326; --bg-2:#0A0F1F;
            --page-bg:
                    radial-gradient(1000px 500px at 10% 10%, rgba(46,76,151,.25), transparent 60%),
                    radial-gradient(900px 600px at 90% 10%, rgba(214,69,46,.18), transparent 55%),
                    linear-gradient(180deg, var(--bg-1), var(--bg-2) 70%);
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0; font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;
            color:var(--text); background:transparent;
        }
        a{color:inherit;text-decoration:none}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}

        /* === FOND UNIQUE (comme index) === */
        #page-bg{ position:fixed; inset:0; z-index:-2; pointer-events:none; background:var(--page-bg); }
        .pi-orbs{ position:fixed; inset:0; z-index:-1; pointer-events:none; overflow:hidden; }
        .pi-orbs .orb{ position:absolute; width:48vmax; height:48vmax; border-radius:9999px; filter:blur(80px); opacity:.75; mix-blend-mode:screen; }
        .pi-orbs .blue{ background:rgba(46,76,151,.18) } .pi-orbs .red{ background:rgba(226,27,60,.16) }
        .pi-orbs .a{ top:-10vmax; left:-6vmax;  animation:orbA 36s linear infinite }
        .pi-orbs .b{ top:-8vmax;  right:-10vmax; animation:orbB 42s linear infinite }
        .pi-orbs .c{ bottom:-12vmax; left:15vw;  animation:orbC 40s linear infinite; width:42vmax;height:42vmax }
        .pi-orbs .d{ bottom:-14vmax; right:10vw; animation:orbD 46s linear infinite; width:50vmax;height:50vmax }
        @keyframes orbA{50%{transform:translate3d(4vw,2vh,0) scale(1.05)}}
        @keyframes orbB{50%{transform:translate3d(-3vw,3vh,0) scale(1.03)}}
        @keyframes orbC{50%{transform:translate3d(2vw,-2vh,0) scale(1.06)}}
        @keyframes orbD{50%{transform:translate3d(-2vw,-3vh,0) scale(1.04)}}

        /* ====== HEADER SIMPLE (.pi-simple) ====== */
        .marquee{position:relative; overflow:hidden; border-top:1px solid #151a2a; border-bottom:1px solid #151a2a;
            background: linear-gradient(180deg, rgba(15,21,37,.94), rgba(13,19,33,.88));}
        .marquee__inner{display:flex; gap:40px; padding:10px 0; white-space:nowrap; animation:marquee 22s linear infinite}
        .pill{display:inline-flex; align-items:center; gap:8px; padding:6px 12px; border-radius:999px;
            background:linear-gradient(145deg,#121a34,#0f162a); border:1px solid #1b2744; font-size:.92rem}
        .pill .dot{width:8px;height:8px;border-radius:50%;background:conic-gradient(from 90deg,var(--red),var(--blue))}
        @keyframes marquee{from{transform:translateX(0)} to{transform:translateX(-50%)}}

        header.pi-simple{ background:transparent !important; }
        .pi-simple .topbar{ display:grid; grid-template-columns:1fr minmax(200px, 1fr) 1fr; align-items:center; gap:16px; padding-block: clamp(18px, 3.5vh, 40px); }
        .pi-simple .left-col{display:flex}
        .pi-simple .social-group{display:flex; flex-direction:column; align-items:center; width:max-content}
        .pi-simple .social{display:flex; align-items:center; gap:16px; color:var(--muted)}
        .pi-simple .social a{font-size:18px; color:var(--muted)}
        .pi-simple .social a:hover{color:#fff}
        .pi-simple .join{font-size:13px; color:var(--muted); font-weight:800; margin-top:6px; text-align:center}
        .pi-simple .brand{display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px}
        .pi-simple .brand img{height: clamp(60px, 9vw, 72px)}
        .pi-simple .tagline{display:flex; align-items:center; gap:14px; color:var(--muted); font-size: clamp(13px, 1.3vw, 16px); line-height:1}
        .pi-simple .tagline .rule{width: clamp(58px, 9vw, 92px); height:1px; background:rgba(255,255,255,.06)}
        .pi-simple .right-col{display:flex; justify-content:flex-end; align-items:center; gap:10px; font-weight:800}
        .pi-simple .right-col i{color:#c9d4ea}
        .pi-simple .phone{font-size: clamp(14px, 1.2vw, 18px); color:#e7ecf5}
        .pi-simple .divider{border:0; border-top:1px solid #141a26; margin:0}
        .pi-simple .navrow{padding:12px 0; position: relative;}
        .pi-simple .menu{display:flex; justify-content:center; gap:28px; list-style:none; margin:0; padding:0}
        .pi-simple .menu a{ font-weight:800; font-size:14px; color:#c9d4ea; letter-spacing:.06em; text-transform:uppercase; }
        .pi-simple .menu a:hover, .pi-simple .menu a.is-active{color:#ffffff}

        /* Bouton login (nav uniquement) */
        .pi-simple .menu .btn-login{
            display:inline-flex; align-items:center; gap:8px;
            padding:10px 14px; border-radius:12px; border:1px solid #223055;
            background:linear-gradient(145deg,#122043,#0e1731); color:#e7ecf5; font-weight:800; text-transform:uppercase;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.06), 0 8px 18px rgba(0,0,0,.28);
            transition:transform .08s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
        }
        .pi-simple .menu .btn-login:hover{ background:linear-gradient(145deg,#1a2b57,#102244); border-color:#2a3d73; box-shadow:0 12px 26px rgba(0,0,0,.35); }
        .pi-simple .menu .btn-login:active{ transform:translateY(1px); }

        @media (max-width:720px){
            .pi-simple .topbar{ grid-template-columns:1fr; text-align:center }
            .pi-simple .left-col{justify-content:center}
            .pi-simple .menu{flex-wrap:wrap; gap:18px}
            .pi-simple .menu .btn-login span{ display:none; }
            .pi-simple .menu .btn-login{ padding:10px; }
        }

        /* FOOTER (identique index) */
        footer.pi-footer{ position:relative; isolation:isolate; }
        footer.pi-footer::before{
            content:""; position:absolute; z-index:-1; top:0; bottom:0; left:50%; right:50%;
            margin-left:-50vw; margin-right:-50vw;
            background:
                    radial-gradient(900px 500px at 10% -10%, rgba(46,76,151,.12), transparent 60%),
                    radial-gradient(900px 500px at 90% -10%, rgba(214,69,46,.10), transparent 55%),
                    linear-gradient(180deg, #0f1525, #0c1223);
            border-top:1px solid #141a2b; box-shadow: inset 0 12px 40px rgba(0,0,0,.35);
        }
        .pi-footer .wrap{ max-width:1100px; margin:0 auto; text-align:center; padding:24px 20px 10px; }
        .pi-footer .brand{ height:72px; width:auto; object-fit:contain; display:block; margin:0 auto 18px; }
        .pi-footer .headline{ display:flex; align-items:center; justify-content:center; gap:22px; margin:6px auto 18px; }
        .pi-footer .headline h2{ margin:0; font-weight:800; letter-spacing:.12em; color:var(--pi-red); font-size:24px; }
        .pi-footer .headline .line{ height:4px; width:260px; border-radius:2px; background:var(--pi-red); transform-origin:center; }
        .pi-footer .social{ list-style:none; display:flex; justify-content:center; gap:14px; padding:0; margin:14px 0 20px; }
        .pi-footer .social a{ width:42px; height:42px; display:grid; place-items:center; background:#101733; color:#cfe0ff; border-radius:50%; border:1px solid #1e2740; font-size:18px; transition:.2s; }
        .pi-footer .social a:hover{ background: linear-gradient(145deg, var(--pi-blue), var(--pi-red)); border-color:#2a3659; color:#fff; transform:translateY(-2px); }
        .pi-footer .footer-nav{ display:flex; flex-wrap:wrap; justify-content:center; gap:26px 30px; padding:12px 0 8px; margin:0 auto 12px; }
        .pi-footer .footer-nav a{ text-decoration:none; color:#e9f1ff; font-weight:800; font-size:14px; letter-spacing:.04em; text-transform:uppercase; }
        .pi-footer .footer-nav a:hover{ color:var(--pi-red) }
        .pi-footer .copyright{ margin:6px 0 0; font-size:12px; color:var(--muted); user-select:none; }

        /* --- Styles spécifiques page Recrutement (condensés) --- */
        .hero{ padding:64px 0 24px; }
        .badge-soft{ background: rgba(214,69,46,.12); color:#ffd7dc; border:1px solid rgba(214,69,46,.25); font-weight:700; padding:.35rem .6rem; border-radius:999px; }
        .pi-word-anim{ --c1:#e21b3c; --c2:#2E4C97; background-image:linear-gradient(90deg,var(--c1),var(--c2),var(--c1)); background-size:200% 100%; -webkit-background-clip:text; background-clip:text; color:transparent; animation:piWord 6s ease-in-out infinite alternate; }
        @keyframes piWord{ 0%{background-position:0% 50%} 100%{background-position:100% 50%} }
        .section{padding:72px 0}
        .section-title{ font-weight:800; letter-spacing:.3px; margin-bottom:10px; background:linear-gradient(90deg,#fff,#9cc3ff); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .section-sub{color:#9aa4b2}
        .card-dark{ background: linear-gradient(180deg, #111418, #141922); border:1px solid rgba(255,255,255,.08); border-radius:18px; transition:.25s; }
        .card-dark:hover{ transform: translateY(-4px); box-shadow: 0 18px 50px -20px rgba(0,0,0,.6); }
        .faq .faq-item{ border:1px solid rgba(255,255,255,.10); border-radius:14px; background:#0e1218; }
        .faq .faq-q{width:100%; background:transparent; border:0; color:#fff; text-align:left; padding:16px 18px; font-weight:800; display:flex; align-items:center; justify-content:space-between}
        .faq .faq-a{ height:0; overflow:hidden; padding:0 18px; color:#cfe0ffbb; transition: height .28s ease, padding .28s ease }
        .faq .faq-item.active .faq-a{ padding:0 18px 18px 18px }
        .cta{ background: linear-gradient(90deg, #8b1a1a, #a32929); border-top:1px solid rgba(255,255,255,.08); }
        .btn-red{ background:#A32929; border:1px solid #A32929; color:#fff; font-weight:800; }
        .btn-red:hover{ background:#8B1A1A; border-color:#8B1A1A; }
        .btn-ghost{ background:transparent; border:1px solid rgba(255,255,255,.14); color:#fff; font-weight:800; }
        .btn-ghost:hover{ border-color:#2a3d73; background:#0f1b3b; }
        .small-muted{ color:#9aa4b2 }
        /* Bandeau */
        .marquee{
            position:relative; overflow:hidden;
            border-top:1px solid #151a2a; border-bottom:1px solid #151a2a;
            background:linear-gradient(180deg, rgba(15,21,37,.94), rgba(13,19,33,.88));
        }
        .marquee__track{
            display:flex; width:max-content; /* s’ajuste à son contenu */
            will-change:transform;
            animation:marquee-roll 28s linear infinite;
        }
        .marquee:hover .marquee__track{ animation-play-state:paused; } /* pause au survol */

        .marquee__group{ display:flex; gap:40px; padding:10px 0; }
        .pill{
            display:inline-flex; align-items:center; gap:8px; padding:6px 12px;
            border-radius:999px; background:linear-gradient(145deg,#121a34,#0f162a);
            border:1px solid #1b2744; font-size:.92rem; color:#cfe0ff;
            white-space:nowrap;
        }
        .pill .dot{ width:8px; height:8px; border-radius:50%;
            background:conic-gradient(from 90deg,#D6452E,#2E4C97);
        }

        /* Défilement sans coupure :
           On translate de 50% car on a deux groupes identiques à la suite. */
        @keyframes marquee-roll{
            from{ transform:translateX(0) }
            to  { transform:translateX(-50%) }
        }

        /* Accessibilité */
        @media (prefers-reduced-motion:reduce){
            .marquee__track{ animation:none }
        }
    </style>
</head>
<body>

<!-- FOND GLOBAL -->
<div id="page-bg" aria-hidden="true"></div>
<div class="pi-orbs" aria-hidden="true">
    <span class="orb blue a"></span>
    <span class="orb red  b"></span>
    <span class="orb blue c"></span>
    <span class="orb red  d"></span>
</div>
    <!-- BANDEAU défilant continu -->
    <div class="marquee" aria-hidden="true">
        <div class="marquee__track">
            <div class="marquee__group">
                <span class="pill"><span class="dot"></span>Préparateur de commande</span>
                <span class="pill"><span class="dot"></span> Manutentionnaire</span>
                <span class="pill"><span class="dot"></span> Logistique</span>
                <span class="pill"><span class="dot"></span> Caissier</span>
                <span class="pill"><span class="dot"></span> Manutentionnaire</span>
                <span class="pill"><span class="dot"></span> Préparateur de commande</span>
                <span class="pill"><span class="dot"></span> Caissier</span>
                <span class="pill"><span class="dot"></span> Logistique</span>
            </div>

            <!-- DUPLICAT exact pour boucle sans coupure -->
            <div class="marquee__group" aria-hidden="true">
                <span class="pill"><span class="dot"></span> Préparateur de commande</span>
                <span class="pill"><span class="dot"></span> Manutentionnaire</span>
                <span class="pill"><span class="dot"></span> Logistique</span>
                <span class="pill"><span class="dot"></span> Caissier</span>
                <span class="pill"><span class="dot"></span> Manutentionnaire</span>
                <span class="pill"><span class="dot"></span> Préparateur de commande</span>
                <span class="pill"><span class="dot"></span> Caissier</span>
                <span class="pill"><span class="dot"></span> Logistique</span>
            </div>
        </div>
    </div>


<!-- HEADER .pi-simple (home) -->
<header class="pi-simple">
    <div class="container topbar">
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

        <div class="right-col">
            <i class="fa-solid fa-phone"></i>
            <a class="phone" href="tel:+33749826133">07 49 82 61 33</a>
        </div>
    </div>

    <hr class="divider">

    <div class="container navrow">
        <ul class="menu" aria-label="Navigation principale">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre Histoire</a></li>
            <li><a href="index.php#stores">Nos Magasins</a></li>
            <li><a href="index.php#catalog">Catalogue</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <li><a href="postuler.php" class="is-active">Postuler</a></li>
            <!-- Bouton login dans la nav -->
            <li><a class="btn-login" href="pageConnexion.php"><i class="fa-regular fa-user"></i><span> Se connecter</span></a></li>
        </ul>
    </div>

    <hr class="divider">
</header>

<!-- ===== MAIN : Recrutement ===== -->
<main>
    <!-- HERO -->
    <section class="hero">
        <div class="container text-center py-5">
            <span class="badge badge-soft mb-3">Carrières Paristanbul</span>
            <h1 class="display-5 fw-bold mb-3">Rejoignez la <span class="pi-word-anim">famille</span></h1>
            <p class="lead" style="color:#e3eaff">Un commerce plus <strong>responsable</strong>, plus <strong>innovant</strong> et surtout plus <strong>humain</strong>.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">
                <a href="#offres" class="btn btn-red btn-lg px-4">Voir nos offres</a>
                <a href="#candidature" class="btn btn-ghost btn-lg px-4">Candidature spontanée</a>
            </div>
        </div>
    </section>

    <!-- FEEDBACK FORM -->
    <?php if ($form_status): ?>
        <div class="container mt-4">
            <div class="alert <?php echo $form_status==='success' ? 'alert-success' : 'alert-danger'; ?> rounded-3" role="alert">
                <?php echo htmlspecialchars($form_message); ?>
                <?php if ($uploaded_cv_public && $form_status==='success'): ?>
                    <br><small class="text-muted">CV enregistré : <a class="link-light" href="<?php echo htmlspecialchars($uploaded_cv_public); ?>" target="_blank" rel="noopener">ouvrir</a></small>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- POURQUOI NOUS REJOINDRE -->
    <section class="section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Pourquoi nous rejoindre ?</h2>
                <p class="section-sub">Nous investissons sur le long terme dans les parcours, les compétences et le bien-être de nos équipes.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4"><div class="card-dark p-4 h-100">
                        <div class="mb-3" style="width:54px;height:54px;display:grid;place-items:center;border-radius:14px;background:rgba(214,69,46,.14);border:1px solid rgba(255,255,255,.08)"><i class="fa-solid fa-chart-line"></i></div>
                        <h5 class="fw-bold mb-2">Évolution professionnelle</h5><p class="text-white-50 mb-0">Promotion interne, parcours personnalisés et formations continues.</p>
                    </div></div>
                <div class="col-md-4"><div class="card-dark p-4 h-100">
                        <div class="mb-3" style="width:54px;height:54px;display:grid;place-items:center;border-radius:14px;background:rgba(214,69,46,.14);border:1px solid rgba(255,255,255,.08)"><i class="fa-regular fa-clock"></i></div>
                        <h5 class="fw-bold mb-2">Équilibre vie pro/perso</h5><p class="text-white-50 mb-0">Organisation flexible lorsque les postes s’y prêtent.</p>
                    </div></div>
                <div class="col-md-4"><div class="card-dark p-4 h-100">
                        <div class="mb-3" style="width:54px;height:54px;display:grid;place-items:center;border-radius:14px;background:rgba(214,69,46,.14);border:1px solid rgba(255,255,255,.08)"><i class="fa-solid fa-hand-holding-heart"></i></div>
                        <h5 class="fw-bold mb-2">Engagement & impact</h5><p class="text-white-50 mb-0">Des initiatives locales et responsables pour un commerce utile.</p>
                    </div></div>
            </div>
        </div>
    </section>

    <!-- AVANTAGES & FORMATION -->
    <section class="section pt-0">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6"><div class="card-dark p-4 h-100">
                        <h4 class="fw-bold mb-3">Nos avantages</h4>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">Remise collaborateurs 15%</li>
                            <li class="mb-2">Participation & intéressement</li>
                            <li class="mb-2">Mutuelle avantageuse</li>
                            <li class="mb-2">Tickets restaurant</li>
                            <li class="mb-2">Comité d’entreprise actif</li>
                        </ul>
                    </div></div>
                <div class="col-md-6"><div class="card-dark p-4 h-100">
                        <h4 class="fw-bold mb-3">Formation & développement</h4>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">Catalogue +200 formations</li>
                            <li class="mb-2">Mentorat & certification</li>
                            <li class="mb-2">Mobilité interne encouragée</li>
                            <li class="mb-2">Accompagnement personnalisé</li>
                        </ul>
                    </div></div>
            </div>
        </div>
    </section>

    <!-- OFFRES -->
    <section id="offres" class="section pt-0">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="section-title">Nos offres d’emploi</h2>
                <p class="section-sub">Découvrez nos opportunités actuelles.</p>
            </div>

            <div class="row g-4">
                <?php
                try {
                    $pdo = db_connect();
                    $req = $pdo->prepare("SELECT * FROM offres_emplois ORDER BY id_offre DESC;");
                    $req->execute();

                    if ($req->rowCount() > 0) {
                        while ($o = $req->fetch()) {
                            $secteur_activite = htmlspecialchars($o['secteur_activite'] ?? '');
                            $titre_poste      = htmlspecialchars($o['titre_poste'] ?? '');
                            $ville            = htmlspecialchars($o['ville'] ?? '');
                            $departement      = htmlspecialchars($o['departement'] ?? '');
                            $type_contrat     = htmlspecialchars($o['type_contrat'] ?? '');
                            $detail_poste     = htmlspecialchars($o['detail_poste'] ?? '');
                            $id_offre         = (int)($o['id_offre'] ?? 0);

                            echo '<div class="col-md-4">';
                            echo '  <div class="card-dark h-100">';
                            echo '    <div class="p-3" style="border-bottom:1px solid rgba(255,255,255,.08)">';
                            echo '      <span class="badge-soft me-2">'.$secteur_activite.'</span>';
                            echo '      <small class="text-white-50">'.$ville.' ('.$departement.') · '.$type_contrat.'</small>';
                            echo '    </div>';
                            echo '    <div class="p-4">';
                            echo '      <h5 class="fw-bold mb-2">'.$titre_poste.'</h5>';
                            echo '      <p class="small text-white-50">'.$detail_poste.'</p>';
                            echo '      <a href="offre.php?id='.$id_offre.'#candidature" class="btn btn-red w-100">Postuler</a>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="col-12"><div class="card-dark p-4 text-center">Aucune offre actuellement.</div></div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="col-12"><div class="alert alert-danger rounded-3">Erreur de chargement des offres.</div></div>';
                }
                ?>
            </div>

            <div class="text-center mt-4">
                <a href="#candidature" class="btn btn-ghost px-4">Candidature spontanée</a>
            </div>
        </div>
    </section>

    <!-- CANDIDATURE SPONTANÉE -->
    <section id="candidature" class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="row g-0 rounded-3 overflow-hidden">
                        <!-- Infos RH -->
                        <div class="col-md-4 p-4" style="background: linear-gradient(180deg, #1a0d10, #140d0f); border:1px solid rgba(255,255,255,.08)">
                            <span class="badge-soft mb-3 d-inline-block">Candidature spontanée</span>
                            <h4 class="fw-bold">Parlez-nous de vous</h4>
                            <p class="small text-white-50">Vous ne trouvez pas d’offre qui vous correspond ? Envoyez-nous votre profil.</p>
                            <hr class="my-3" style="border-color:rgba(255,255,255,.1)">
                            <p class="mb-1 fw-semibold">Nos engagements</p>
                            <ul class="small text-white-50 ps-3">
                                <li>Réponse sous 15 jours</li>
                                <li>Conservation encadrée de votre CV</li>
                                <li>Processus équitable et inclusif</li>
                            </ul>
                            <hr class="my-3" style="border-color:rgba(255,255,255,.1)">
                            <p class="small mb-1">Besoin d’aide ?</p>
                            <a href="mailto:recrutement@paristanbul.fr" class="link-light small">recrutement@paristanbul.fr</a>
                        </div>

                        <!-- Form -->
                        <div class="col-md-8 p-4 card-dark" style="border-left:0">
                            <form action="" method="post" enctype="multipart/form-data" novalidate>
                                <input type="hidden" name="ref_offre" value="<?php echo htmlspecialchars($ref_offre_from_get ?? ''); ?>">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="prenom" class="form-label">Prénom *</label>
                                        <input type="text" id="prenom" name="prenom" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nom" class="form-label">Nom *</label>
                                        <input type="text" id="nom" name="nom" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <input type="tel" id="telephone" name="telephone" class="form-control" placeholder="+33 6 12 34 56 78" pattern="[0-9\s+]{8,20}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_naissance" class="form-label">Date de naissance</label>
                                        <input type="date" id="date_naissance" name="date_naissance" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="langues" class="form-label">Langues</label>
                                        <input type="text" id="langues" name="langues" class="form-control" placeholder="Français (C1), Anglais (B2)…">
                                    </div>
                                    <div class="col-12">
                                        <label for="adresse" class="form-label">Adresse</label>
                                        <input type="text" id="adresse" name="adresse" class="form-control" placeholder="N° et rue, Ville, Code postal">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="permis" class="form-label">Permis</label>
                                        <input type="text" id="permis" name="permis" class="form-control" placeholder="B, AM, etc.">
                                    </div>
                                    <div class="col-12">
                                        <label for="experiences" class="form-label">Expériences</label>
                                        <textarea id="experiences" name="experiences" rows="3" class="form-control" placeholder="Rôles, missions, compétences clés…"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="lettre_motivation" class="form-label">Lettre de motivation</label>
                                        <textarea id="lettre_motivation" name="lettre_motivation" rows="4" class="form-control" placeholder="Présentez-vous et expliquez votre motivation…"></textarea>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="cv" class="form-label">CV (PDF/DOC/DOCX, max 5 Mo) *</label>
                                        <input type="file" id="cv" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-red w-100">Envoyer ma candidature</button>
                                    </div>
                                    <div class="col-12 text-end">
                                        <div class="form-check d-inline-flex align-items-center gap-2">
                                            <input class="form-check-input" type="checkbox" id="rgpdCheck" required>
                                            <label class="form-check-label small-muted" for="rgpdCheck">
                                                J’accepte le traitement de mes données selon la politique de confidentialité *
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="section pt-0">
        <div class="container faq">
            <div class="row g-3">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-3">Questions fréquentes</h3>

                    <div class="faq-item mb-2">
                        <button class="faq-q">Comment se déroule le processus de recrutement ? <i class="fa-solid fa-chevron-down"></i></button>
                        <div class="faq-a"><ol class="mb-0 py-3"><li>Étude de votre candidature</li><li>Échange téléphonique</li><li>Entretien avec le manager</li><li>Tests éventuels</li><li>Proposition & intégration</li></ol></div>
                    </div>

                    <div class="faq-item mb-2">
                        <button class="faq-q">Proposez-vous des stages et de l’alternance ? <i class="fa-solid fa-chevron-down"></i></button>
                        <div class="faq-a"><p class="mb-0 py-3">Oui, régulièrement selon les besoins des magasins et du siège.</p></div>
                    </div>

                    <div class="faq-item mb-2">
                        <button class="faq-q">Quelles sont les perspectives d’évolution ? <i class="fa-solid fa-chevron-down"></i></button>
                        <div class="faq-a"><p class="mb-0 py-3">Promotion interne, mobilité et plan de développement personnalisé.</p></div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-q">Quels avantages sociaux proposez-vous ? <i class="fa-solid fa-chevron-down"></i></button>
                        <div class="faq-a"><p class="mb-0 py-3">Tickets restaurant, mutuelle, réductions collaborateurs, CE…</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</main>

<!-- FOOTER (home) -->
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
            <a href="index.php#catalog">Catalogue</a>
            <a href="quiSommesNous.html">À propos</a>
            <a href="postuler.php">Postuler</a>
            <a href="index.php#contact">Contact</a>
        </nav>

        <p class="copyright">© <span id="year"></span> Paristanbul — Tous droits réservés.</p>
    </div>
</footer>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // FAQ accordéon
    document.querySelectorAll('.faq .faq-q').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            const item = btn.closest('.faq-item');
            const answer = item.querySelector('.faq-a');
            const open = item.classList.contains('active');

            document.querySelectorAll('.faq .faq-item.active').forEach(i=>{
                if(i!==item){
                    const a=i.querySelector('.faq-a');
                    a.style.height = a.scrollHeight + 'px';
                    requestAnimationFrame(()=>{ a.style.height='0px'; });
                    i.classList.remove('active');
                }
            });

            if(!open){
                item.classList.add('active');
                answer.style.height = answer.scrollHeight + 'px';
                answer.addEventListener('transitionend', function te(){ answer.style.height='auto'; answer.removeEventListener('transitionend', te); });
            }else{
                answer.style.height = answer.scrollHeight + 'px';
                requestAnimationFrame(()=>{ answer.style.height='0px'; });
                item.classList.remove('active');
            }
        });
    });

    // Footer year
    document.getElementById('year').textContent = new Date().getFullYear();
</script>
</body>
</html>