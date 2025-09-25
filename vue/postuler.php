<?php
/**************************************
 * POSTULER — Paristanbul (dark theme)
 * Unique file: UI + PHP handler
 **************************************/

// ---------- CONFIG UPLOAD ----------
$MAX_FILE_SIZE_MB = 5; // limite 5 Mo
$ALLOWED_EXT = ['pdf','doc','docx'];
$ALLOWED_MIME = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

// ---------- FEEDBACK UI ----------
$form_status = null; // 'success' | 'error'
$form_message = '';
$uploaded_cv_public = null;

// ---------- HANDLE POST ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connexion BDD
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

        // Validations simples
        if ($nom === '' || $prenom === '' || $email === '') {
            throw new Exception("Merci de renseigner au minimum prénom, nom et email.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'email n'est pas valide.");
        }

        // Upload CV
        $upload_dir = __DIR__ . "/telechargement/candidatures/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $nettoyer_nom    = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($nom));
        $nettoyer_prenom = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($prenom));

        $lien_cv = null;
        if (!empty($_FILES['cv']['name'])) {
            $cvTmp     = $_FILES['cv']['tmp_name'];
            $cvName    = $_FILES['cv']['name'];
            $cvSize    = (int)$_FILES['cv']['size'];
            $cvType    = mime_content_type($cvTmp) ?: '';
            $extension = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));

            if (!in_array($extension, $ALLOWED_EXT, true)) {
                throw new Exception("Extension de fichier non autorisée (pdf, doc, docx).");
            }
            if (!in_array($cvType, $ALLOWED_MIME, true)) {
                throw new Exception("Type de fichier non autorisé.");
            }
            if ($cvSize > $MAX_FILE_SIZE_MB * 1024 * 1024) {
                throw new Exception("Fichier trop volumineux (max {$MAX_FILE_SIZE_MB} Mo).");
            }

            $hash  = substr(md5(uniqid('', true)), 0, 10);
            $fname = "cv_{$nettoyer_nom}_{$nettoyer_prenom}_{$hash}.{$extension}";
            $dest  = $upload_dir . $fname;

            if (!move_uploaded_file($cvTmp, $dest)) {
                throw new Exception("Échec lors de l'upload du CV.");
            }
            // Chemin public
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

        if (!$ok) {
            throw new Exception("Une erreur est survenue lors de l'enregistrement.");
        }

        $form_status = 'success';
        $form_message = "Candidature envoyée avec succès. Nous revenons vers vous sous 15 jours.";
    } catch (Exception $e) {
        $form_status = 'error';
        $form_message = $e->getMessage();
    }
}

// Pour pré-remplir ref_offre si on arrive avec ?id=...
$ref_offre_from_get = isset($_GET['id']) ? (int)$_GET['id'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul — Recrutement</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Design sombre moderne + Animations -->
    <style>
        :root{
            --black:#0A0A0A;
            --black-2:#0D0F13;
            --navy:#0B1B34;
            --navy-2:#132946;
            --red:#A91D2B; /* rouge foncé */
            --red-2:#8D1824;
            --text:#EAF0F7;
            --muted:#9AA4B2;
            --card:#111418;
            --card-2:#141922;
            --border:rgba(255,255,255,.08);
            --ring:rgba(169,29,43,.35);
            --grad-hero: radial-gradient(1200px 600px at 15% -10%, rgba(169,29,43,.18), transparent 60%),
            radial-gradient(900px 500px at 110% -20%, rgba(11,27,52,.55), transparent 60%),
            linear-gradient(180deg, #0A0A0A 0%, #0B0F18 100%);
            --ease:cubic-bezier(.22,.61,.36,1);
        }

        *{box-sizing:border-box}
        html,body{background:var(--black); color:var(--text); scroll-behavior:smooth}
        a{color:inherit;text-decoration:none}
        img{max-width:100%;height:auto}

        /* Top scroll progress */
        #scrollProgress{
            position:fixed; inset:0 auto auto 0; height:3px; width:0;
            background:linear-gradient(90deg, var(--red-2), var(--red));
            z-index:99999; box-shadow:0 0 12px rgba(169,29,43,.45);
            transition:width .2s linear;
        }

        /* Navbar */
        .navbar-dark{
            background: linear-gradient(180deg, rgba(0,0,0,.85), rgba(0,0,0,.65));
            border-bottom:1px solid var(--border);
            backdrop-filter: blur(4px);
        }
        .navbar-dark .nav-link{ color:var(--muted); font-weight:500; letter-spacing:.2px; }
        .navbar-dark .nav-link.active,
        .navbar-dark .nav-link:hover{color:var(--text)}
        .navbar-brand img{height:46px}

        /* === Nav centrée (centre absolu, desktop) === */
        @media (min-width: 992px){ /* >= lg */
            .navbar .container{
                position: relative;
                display: flex;
                align-items: center;
            }
            .navbar-collapse{
                display: flex !important;
                align-items: center;
                flex: 1 1 auto;          /* occupe l'espace central */
            }
            /* Le bloc des liens au vrai centre du container */
            .navbar-nav{
                position: absolute;
                left: 50%; top: 50%;
                transform: translate(-50%, -50%);
                margin: 0 !important;
                gap: 1.25rem;
            }
            /* Boutons poussés à droite */
            .navbar .actions{
                margin-left: auto;
            }
        }
        /* Mobile/tablette : tout centré dans le menu déroulant */
        @media (max-width: 991.98px){
            #nav .navbar-nav{ align-items: center; }
            .navbar .actions{ justify-content: center; margin-top: .5rem; }
        }

        /* Buttons */
        .btn-red{
            --bs-btn-bg:var(--red);
            --bs-btn-border-color:var(--red);
            --bs-btn-hover-bg:var(--red-2);
            --bs-btn-hover-border-color:var(--red-2);
            --bs-btn-color:#fff;
            box-shadow:0 0 0 0 rgba(169,29,43,0);
            transition: box-shadow .25s ease, transform .2s ease;
            position:relative; overflow:hidden;
        }
        .btn-red:hover{ transform: translateY(-1px); box-shadow:0 6px 24px -8px var(--ring); }
        /* Ripple */
        .btn-red .ripple{
            position:absolute; border-radius:50%; transform:scale(0); opacity:.6;
            background:#fff; mix-blend-mode:overlay; pointer-events:none;
            animation:ripple .6s ease-out forwards;
        }
        @keyframes ripple{ to{ transform:scale(16); opacity:0;} }

        .btn-ghost{
            background:transparent; border:1px solid var(--border); color:#fff; position:relative; overflow:hidden;
            transition: border-color .25s var(--ease), background .25s var(--ease), transform .2s ease;
        }
        .btn-ghost:hover{border-color:rgba(255,255,255,.25); background:rgba(255,255,255,.04); transform:translateY(-1px)}

        /* Magnetic buttons (visual) */
        .magnet{ will-change: transform; transition: transform .12s ease }

        /* Hero + Parallax layers */
        .hero{ background: var(--grad-hero); position:relative; overflow:hidden; }
        .hero .shape{
            position:absolute; border-radius:50%;
            filter: blur(40px); opacity:.18; pointer-events:none; will-change: transform;
        }
        .shape.s1{width:320px;height:320px;background:#1b66ff; top:-80px;left:-80px; }
        .shape.s2{width:420px;height:420px;background:#b31b2b; top:-120px;right:-120px; }
        .shape.s3{width:260px;height:260px;background:#19c37d; bottom:-100px; left:25%; opacity:.12; filter:blur(48px); }

        .hero-parallax{ will-change: transform; }

        /* Sections */
        .section{padding:72px 0}
        .section-title{
            font-weight:800; letter-spacing:.3px; margin-bottom:16px;
            background:linear-gradient(90deg,#fff,#9cc3ff); -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }
        .section-sub{color:var(--muted)}

        /* Cards (tilt-ready) */
        .card-dark{
            position:relative;
            background: linear-gradient(180deg, var(--card) 0%, var(--card-2) 100%);
            border:1px solid var(--border);
            border-radius:18px;
            transition: transform .18s var(--ease), box-shadow .25s var(--ease), border-color .25s var(--ease);
            will-change: transform;
        }
        .card-dark:hover{
            transform: translateY(-4px);
            box-shadow: 0 18px 50px -20px rgba(0,0,0,.6), 0 8px 24px -12px var(--ring);
            border-color: rgba(255,255,255,.12);
        }

        /* Tilt 3D */
        .tilt{
            transform-style: preserve-3d;
        }
        .tilt::after{
            content:"";
            position:absolute; inset:-1px; border-radius:inherit; pointer-events:none;
            background: radial-gradient(240px 200px at var(--mx,50%) var(--my,50%), rgba(255,255,255,.08), transparent 60%);
            opacity:0; transition:opacity .2s var(--ease);
        }
        .tilt.hovered::after{ opacity:1 }

        /* Badge pill */
        .badge-soft{
            background: rgba(169,29,43,.12); color:#ffd7dc;
            border:1px solid rgba(169,29,43,.25);
            font-weight:600;
        }

        /* Icon bubble */
        .icon-bubble{
            width:54px;height:54px; display:grid; place-items:center;
            border-radius:14px;
            background:linear-gradient(180deg, rgba(169,29,43,.16), rgba(169,29,43,.05));
            border:1px solid var(--border);
            transition: transform .25s var(--ease);
        }
        .card-dark:hover .icon-bubble{ transform: translateY(-2px) }

        /* Offers grid */
        .offer .head{
            background: linear-gradient(180deg, #10131a, #0d1117);
            border-bottom:1px solid var(--border);
        }

        /* Form */
        .form-control, .form-select{
            background:#0e131a; border:1px solid var(--border); color:var(--text);
            transition: border-color .2s var(--ease), box-shadow .2s var(--ease), transform .15s var(--ease);
        }
        .form-control:focus, .form-select:focus{
            border-color:rgba(169,29,43,.6); box-shadow:0 0 0 .25rem rgba(169,29,43,.15); color:#fff;
            transform: translateY(-1px);
        }
        .is-valid-quick{
            box-shadow:0 0 0 .2rem rgba(60,200,120,.15)!important; border-color:rgba(60,200,120,.45)!important;
        }

        .small-muted{color:var(--muted); font-size:.9rem}

        /* FAQ */
        .faq .faq-item{
            border:1px solid var(--border); border-radius:14px; background:#0e1218; overflow:hidden;
            transition: border-color .25s var(--ease), box-shadow .25s var(--ease);
        }
        .faq .faq-item.active{ border-color: rgba(255,255,255,.16); box-shadow:0 10px 26px -18px rgba(0,0,0,.5) }
        .faq .faq-q{width:100%; background:transparent; border:0; color:#fff; text-align:left; padding:16px 18px; font-weight:700; display:flex; align-items:center; justify-content:space-between}
        .faq .faq-q .chev{ transition: transform .25s var(--ease); }
        .faq .faq-item.active .faq-q .chev{ transform: rotate(180deg) }
        .faq .faq-a{ height:0; overflow:hidden; padding:0 18px; color:var(--muted); transition: height .3s var(--ease), padding .3s var(--ease) }
        .faq .faq-item.active .faq-a{ padding:0 18px 18px 18px }

        /* CTA bottom */
        .cta{ background: linear-gradient(90deg, var(--red-2), var(--red)); border-top:1px solid var(--border); }

        /* Utilities */
        .rounded-2xl{border-radius:20px}
        .shadow-ring{box-shadow: 0 8px 26px -18px var(--ring)}
        hr.div{border-color:var(--border); opacity:1}

        /* Scroll reveal */
        .reveal{opacity:0; transform: translateY(24px); transition: opacity .6s var(--ease), transform .6s var(--ease); will-change: opacity, transform}
        .reveal[data-reveal="fade"]{ transform:none }
        .reveal.show{ opacity:1; transform:none }
        .reveal.delay-1{ transition-delay:.08s }
        .reveal.delay-2{ transition-delay:.16s }
        .reveal.delay-3{ transition-delay:.24s }
        .reveal.delay-4{ transition-delay:.32s }

        /* Slide in for alerts */
        .slide-in-top{ animation:slideInTop .55s var(--ease) both }
        @keyframes slideInTop{ from{ transform: translateY(-10px); opacity:0 } to{ transform:none; opacity:1 } }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce){
            .shape.s1,.shape.s2,.shape.s3{ will-change:auto }
            .reveal,.btn-red,.btn-ghost,.card-dark,.icon-bubble,.tilt{ transition:none }
            .faq .faq-a{ transition:none }

        }
        /* Force le fond sombre en TOUTES circonstances (focus, click, anim, etc.) */
        .form-control,
        .form-select{
            background:#0e131a !important;
            color:#eaf0f7 !important;
            border-color:var(--border);
            /* on évite toute transition du background pour supprimer le flash */
            transition: border-color .2s var(--ease), box-shadow .2s var(--ease), transform .15s var(--ease) !important;
            /* hack infaillible: peindre l'intérieur en sombre */
            box-shadow: inset 0 0 0 1000px #0e131a !important;
            appearance:none; -webkit-appearance:none;
            background-image:none !important;
        }

        .form-control:focus,
        .form-select:focus{
            background:#0e131a !important;
            color:#fff !important;
            border-color: rgba(169,29,43,.6) !important;
            /* on garde l'anneau rouge mais sans flash blanc */
            box-shadow: inset 0 0 0 1000px #0e131a, 0 0 0 .25rem rgba(169,29,43,.15) !important;
            outline: none !important;
            transform: translateY(-1px);
        }

        /* Chrome/Edge : when autofill tries to colorize */
        input:-webkit-autofill,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        select:-webkit-autofill{
            -webkit-text-fill-color:#fff !important;
            box-shadow: inset 0 0 0 1000px #0e131a !important;
            transition: background-color 9999s ease-out 0s !important;
        }

        /* iOS/Android tap highlight */
        * { -webkit-tap-highlight-color: transparent; }

        /* Optionnel : dites explicitement “dark” aux contrôles natifs (date, etc.) */
        form { color-scheme: dark; }

    </style>
</head>
<body>

<div id="scrollProgress" aria-hidden="true"></div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top reveal" data-reveal="fade">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Basculer la navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="nav" class="collapse navbar-collapse">
            <!-- UL sans ms-auto/me-3 pour permettre le centrage absolu -->
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#magasins">Nos magasins</a></li>
                <li class="nav-item"><a class="nav-link" href="quiSommesNous.html">Notre histoire</a></li>
            </ul>
            <!-- Ajout de la classe actions -->
            <div class="actions d-flex gap-2">
                <a href="pageInscription.php" class="btn btn-ghost btn-sm px-3 magnet">Inscription</a>
                <a href="pageConnexion.php" class="btn btn-red btn-sm px-3 magnet">Connexion</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO -->
<header class="hero py-5">
    <div class="shape s1"></div>
    <div class="shape s2"></div>
    <div class="shape s3"></div>
    <div class="container text-center py-4 reveal hero-parallax" data-reveal="fade">
        <span class="badge badge-soft mb-3">Carrières Paristanbul</span>
        <h1 class="display-5 fw-extrabold mb-3">Rejoignez l’aventure</h1>
        <p class="lead text-white-50 mb-4">Un commerce plus <strong>responsable</strong>, plus <strong>innovant</strong> et surtout plus <strong>humain</strong>.</p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="#offres" class="btn btn-red btn-lg px-4 magnet">Voir nos offres</a>
            <a href="#candidature" class="btn btn-ghost btn-lg px-4 magnet">Candidature spontanée</a>
        </div>
    </div>
</header>

<!-- FEEDBACK FORM -->
<?php if ($form_status): ?>
    <div class="container mt-4">
        <div class="alert <?php echo $form_status==='success' ? 'alert-success' : 'alert-danger'; ?> rounded-2xl shadow-ring slide-in-top" role="alert">
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
        <div class="text-center mb-5 reveal" data-reveal="fade">
            <h2 class="section-title">Pourquoi nous rejoindre ?</h2>
            <p class="section-sub">Nous investissons sur le long terme dans les parcours, les compétences et le bien-être de nos équipes.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-dark p-4 h-100 reveal tilt">
                    <div class="icon-bubble mb-3"><i class="bi bi-bar-chart-fill"></i></div>
                    <h5 class="fw-bold mb-2">Évolution professionnelle</h5>
                    <p class="text-white-50 mb-0">Promotion interne, parcours personnalisés et formations continues pour grandir avec nous.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-dark p-4 h-100 reveal delay-1 tilt">
                    <div class="icon-bubble mb-3"><i class="bi bi-clock"></i></div>
                    <h5 class="fw-bold mb-2">Équilibre vie pro/perso</h5>
                    <p class="text-white-50 mb-0">Organisation flexible lorsque les postes s’y prêtent, avantages concrets au quotidien.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-dark p-4 h-100 reveal delay-2 tilt">
                    <div class="icon-bubble mb-3"><i class="bi bi-bag-heart-fill"></i></div>
                    <h5 class="fw-bold mb-2">Engagement & impact</h5>
                    <p class="text-white-50 mb-0">Des initiatives locales et responsables pour un commerce utile et durable.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AVANTAGES & FORMATION -->
<section class="section pt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card-dark p-4 h-100 reveal tilt">
                    <h4 class="fw-bold mb-3">Nos avantages</h4>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Remise collaborateurs 15%</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Participation & intéressement</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Mutuelle avantageuse</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Tickets restaurant</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Comité d’entreprise actif</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-dark p-4 h-100 reveal delay-1 tilt">
                    <h4 class="fw-bold mb-3">Formation & développement</h4>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Catalogue +200 formations</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Mentorat & certification</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Mobilité interne encouragée</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-danger"></i>Accompagnement personnalisé</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TÉMOIGNAGES -->
<section class="section">
    <div class="container">
        <div class="text-center mb-4 reveal" data-reveal="fade">
            <h2 class="section-title">Ils travaillent chez Paristanbul</h2>
            <p class="section-sub">Des parcours différents, la même envie de bien faire.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-dark h-100 reveal tilt">
                    <div class="head p-4 text-center">
                        <i class="bi bi-person-circle fs-1 text-danger"></i>
                    </div>
                    <div class="p-4">
                        <h6 class="fw-bold mb-1">Sophie Martin</h6>
                        <p class="small-muted mb-3">Directrice de magasin</p>
                        <p class="small text-white-50 mb-0">“Entrée comme caissière il y a 12 ans, j’ai pu évoluer sur plusieurs postes. Aujourd’hui je dirige une équipe de 50 personnes.”</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-dark h-100 reveal delay-1 tilt">
                    <div class="head p-4 text-center">
                        <i class="bi bi-person-circle fs-1 text-danger"></i>
                    </div>
                    <div class="p-4">
                        <h6 class="fw-bold mb-1">Thomas Dubois</h6>
                        <p class="small-muted mb-3">Resp. Développement durable</p>
                        <p class="small text-white-50 mb-0">“Mes idées sont écoutées et mises en œuvre. Nous avons déjà réduit nos déchets de 30%.”</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-dark h-100 reveal delay-2 tilt">
                    <div class="head p-4 text-center">
                        <i class="bi bi-person-circle fs-1 text-danger"></i>
                    </div>
                    <div class="p-4">
                        <h6 class="fw-bold mb-1">Karim Benali</h6>
                        <p class="small-muted mb-3">Chef boucher</p>
                        <p class="small text-white-50 mb-0">“Formé régulièrement, j’accompagne désormais les apprentis et travaille avec nos partenaires locaux.”</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OFFRES -->
<section id="offres" class="section pt-0">
    <div class="container">
        <div class="text-center mb-4 reveal" data-reveal="fade">
            <h2 class="section-title">Nos offres d’emploi</h2>
            <p class="section-sub">Découvrez nos opportunités actuelles.</p>
        </div>

        <div class="row g-4">
            <?php
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8','root','',[
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);

                $req = $pdo->prepare("SELECT * FROM offres_emplois ORDER BY id_offre DESC;");
                $req->execute();

                if ($req->rowCount() > 0) {
                    $i = 0;
                    while ($o = $req->fetch()) {
                        $secteur_activite = htmlspecialchars($o['secteur_activite'] ?? '');
                        $titre_poste      = htmlspecialchars($o['titre_poste'] ?? '');
                        $ville            = htmlspecialchars($o['ville'] ?? '');
                        $departement      = htmlspecialchars($o['departement'] ?? '');
                        $type_contrat     = htmlspecialchars($o['type_contrat'] ?? '');
                        $detail_poste     = htmlspecialchars($o['detail_poste'] ?? '');
                        $id_offre         = (int)($o['id_offre'] ?? 0);

                        $delayClass = 'delay-' . min(2, $i % 3);
                        echo '<div class="col-md-4">';
                        echo '  <div class="card-dark offer h-100 reveal '.$delayClass.' tilt">';
                        echo '    <div class="head p-3">';
                        echo '      <span class="badge badge-soft me-2">'.$secteur_activite.'</span>';
                        echo '      <small class="text-white-50">'.$ville.' ('.$departement.') · '.$type_contrat.'</small>';
                        echo '    </div>';
                        echo '    <div class="p-4">';
                        echo '      <h5 class="fw-bold mb-2">'.$titre_poste.'</h5>';
                        echo '      <p class="small text-white-50">'.$detail_poste.'</p>';
                        echo '      <a href="postuler.php?id='.$id_offre.'#candidature" class="btn btn-red w-100 magnet">Postuler</a>';
                        echo '    </div>';
                        echo '  </div>';
                        echo '</div>';
                        $i++;
                    }
                } else {
                    echo '<div class="col-12"><div class="card-dark p-4 text-center reveal" data-reveal="fade">Aucune offre actuellement.</div></div>';
                }
            } catch (Exception $e) {
                echo '<div class="col-12"><div class="alert alert-danger rounded-2xl reveal" data-reveal="fade">Erreur de chargement des offres.</div></div>';
            }
            ?>
        </div>

        <div class="text-center mt-4 reveal" data-reveal="fade">
            <a href="#candidature" class="btn btn-ghost px-4 magnet">Candidature spontanée</a>
        </div>
    </div>
</section>

<!-- CANDIDATURE SPONTANÉE -->
<section id="candidature" class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="row g-0 rounded-2xl overflow-hidden reveal">
                    <!-- Infos RH -->
                    <div class="col-md-4 p-4" style="background: linear-gradient(180deg, #1a0d10, #140d0f); border:1px solid var(--border)">
                        <span class="badge badge-soft mb-3">Candidature spontanée</span>
                        <h4 class="fw-bold">Parlez-nous de vous</h4>
                        <p class="small text-white-50">Vous ne trouvez pas d’offre qui vous correspond ? Envoyez-nous votre profil.</p>
                        <hr class="div my-3">
                        <p class="mb-1 fw-semibold">Nos engagements</p>
                        <ul class="small text-white-50 ps-3">
                            <li>Réponse sous 15 jours</li>
                            <li>Conservation encadrée de votre CV</li>
                            <li>Processus équitable et inclusif</li>
                        </ul>
                        <hr class="div my-3">
                        <p class="small mb-1">Besoin d’aide ?</p>
                        <a href="mailto:recrutement@paristanbul.fr" class="link-light small">recrutement@paristanbul.fr</a>
                    </div>

                    <!-- Form -->
                    <div class="col-md-8 p-4 card-dark tilt" style="border-left:0">
                        <form action="https://formsubmit.co/paristanbul.recrutement@gmail.com" method="post" enctype="multipart/form-data" novalidate class="reveal">
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
                                    <button type="submit" class="btn btn-red w-100 magnet">Envoyer ma candidature</button>
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
                <h3 class="fw-bold mb-3 reveal" data-reveal="fade">Questions fréquentes</h3>

                <div class="faq-item mb-2 reveal tilt">
                    <button class="faq-q">Comment se déroule le processus de recrutement ? <i class="bi bi-chevron-down chev"></i></button>
                    <div class="faq-a">
                        <ol class="mb-0 py-3">
                            <li>Étude de votre candidature</li>
                            <li>Échange téléphonique</li>
                            <li>Entretien avec le manager</li>
                            <li>Éventuels tests ou mises en situation</li>
                            <li>Proposition et intégration</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item mb-2 reveal tilt">
                    <button class="faq-q">Proposez-vous des stages et de l’alternance ? <i class="bi bi-chevron-down chev"></i></button>
                    <div class="faq-a"><p class="mb-0 py-3">Oui, régulièrement selon les besoins des magasins et du siège.</p></div>
                </div>

                <div class="faq-item mb-2 reveal tilt">
                    <button class="faq-q">Quelles sont les perspectives d’évolution ? <i class="bi bi-chevron-down chev"></i></button>
                    <div class="faq-a"><p class="mb-0 py-3">Promotion interne, mobilité et plan de développement personnalisé.</p></div>
                </div>

                <div class="faq-item reveal tilt">
                    <button class="faq-q">Quels avantages sociaux proposez-vous ? <i class="bi bi-chevron-down chev"></i></button>
                    <div class="faq-a"><p class="mb-0 py-3">Tickets restaurant, mutuelle, réductions collaborateurs, CE…</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA BAS DE PAGE -->
<section class="cta py-5 text-white text-center reveal" data-reveal="fade">
    <div class="container">
        <h4 class="fw-bold mb-2">Prêt·e à nous rejoindre ?</h4>
        <p class="mb-4">Déposez votre candidature maintenant.</p>
        <a href="#candidature" class="btn btn-light text-dark fw-semibold rounded-pill px-4 magnet">Candidater</a>
    </div>
</section>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* ===== Scroll progress bar ===== */
    (function(){
        const bar=document.getElementById('scrollProgress');
        const onScroll=()=> {
            const h=document.documentElement;
            const s=h.scrollTop;
            const d=h.scrollHeight - h.clientHeight;
            const p = d ? (s/d)*100 : 0;
            bar.style.width = p + '%';
        };
        document.addEventListener('scroll', onScroll, {passive:true});
        onScroll();
    })();

    /* ===== Intersection Observer: reveal ===== */
    (function(){
        const els=[...document.querySelectorAll('.reveal')];
        if(!('IntersectionObserver' in window)){ els.forEach(el=>el.classList.add('show')); return; }
        const io=new IntersectionObserver((entries)=>{
            entries.forEach(e=>{
                if(e.isIntersecting){
                    e.target.classList.add('show');
                    io.unobserve(e.target);
                }
            });
        },{threshold:0.12});
        els.forEach(el=>io.observe(el));
    })();

    /* ===== Button ripple ===== */
    document.querySelectorAll('.btn-red, .btn-ghost').forEach(btn=>{
        btn.addEventListener('click', function(e){
            const rect=this.getBoundingClientRect();
            const span=document.createElement('span');
            const size=Math.max(rect.width, rect.height);
            span.className='ripple';
            span.style.width=span.style.height=size+'px';
            span.style.left=(e.clientX-rect.left - size/2)+'px';
            span.style.top=(e.clientY-rect.top - size/2)+'px';
            this.appendChild(span);
            setTimeout(()=>span.remove(), 650);
        });
    });

    /* ===== FAQ smooth height ===== */
    document.querySelectorAll('.faq .faq-item .faq-q').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            const item = btn.closest('.faq-item');
            const answer = item.querySelector('.faq-a');
            const open = item.classList.contains('active');

            // close others
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

    /* ===== Quick valid feedback on inputs ===== */
    document.querySelectorAll('input[required], textarea[required]').forEach(el=>{
        el.addEventListener('input', ()=>{
            if(el.checkValidity()) el.classList.add('is-valid-quick');
            else el.classList.remove('is-valid-quick');
        });
    });

    /* ===== Parallax hero (mousemove + scroll, léger) ===== */
    (function(){
        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        if(prefersReduced || isTouch) return;

        const hero = document.querySelector('.hero');
        const content = document.querySelector('.hero-parallax');
        const s1 = document.querySelector('.shape.s1');
        const s2 = document.querySelector('.shape.s2');
        const s3 = document.querySelector('.shape.s3');

        let targetX=0, targetY=0, curX=0, curY=0;

        function lerp(a,b,t){ return a+(b-a)*t; }

        hero.addEventListener('mousemove', (e)=>{
            const r = hero.getBoundingClientRect();
            const mx = (e.clientX - r.left) / r.width - .5;
            const my = (e.clientY - r.top) / r.height - .5;
            targetX = mx; targetY = my;
        });

        function raf(){
            curX = lerp(curX, targetX, 0.08);
            curY = lerp(curY, targetY, 0.08);

            const rot = `translate3d(${curX*8}px, ${curY*8}px, 0)`;
            if(content) content.style.transform = rot;

            if(s1) s1.style.transform = `translate3d(${curX*-40}px, ${curY*-30}px,0)`;
            if(s2) s2.style.transform = `translate3d(${curX*50}px, ${curY*36}px,0)`;
            if(s3) s3.style.transform = `translate3d(${curX*-30}px, ${curY*28}px,0)`;

            requestAnimationFrame(raf);
        }
        raf();

        // Parallax light on scroll
        const onScroll=()=>{
            const y = window.scrollY || document.documentElement.scrollTop;
            const t = Math.max(0, 1 - y/600);
            if(content) content.style.opacity = t.toFixed(3);
        };
        document.addEventListener('scroll', onScroll, {passive:true});
    })();

    /* ===== Tilt 3D cards ===== */
    (function(){
        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        if(prefersReduced) return;

        const MAX = 10; // degrés max
        const cards = document.querySelectorAll('.tilt');

        cards.forEach(card=>{
            let entered=false;
            function onMove(e){
                const r = card.getBoundingClientRect();
                const px = (e.clientX - r.left)/r.width;
                const py = (e.clientY - r.top)/r.height;
                const rx = (py - 0.5) * -MAX;
                const ry = (px - 0.5) *  MAX;
                card.style.transform = `perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg) translateY(-2px)`;
                card.style.setProperty('--mx', (px*100).toFixed(2) + '%');
                card.style.setProperty('--my', (py*100).toFixed(2) + '%');
            }
            function onEnter(){ entered=true; card.classList.add('hovered'); }
            function onLeave(){ entered=false; card.classList.remove('hovered'); card.style.transform=''; }

            const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
            if(!isTouch){
                card.addEventListener('mousemove', onMove);
                card.addEventListener('mouseenter', onEnter);
                card.addEventListener('mouseleave', onLeave);
            }
        });
    })();

    /* ===== Magnetic buttons (subtil) ===== */
    (function(){
        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        if(prefersReduced || isTouch) return;

        const magnets = document.querySelectorAll('.magnet');
        magnets.forEach(el=>{
            const strength = 10; // px
            let tX=0, tY=0, cX=0, cY=0, rafId=null;

            function lerp(a,b,t){ return a+(b-a)*t; }
            function animate(){
                cX = lerp(cX, tX, 0.18);
                cY = lerp(cY, tY, 0.18);
                el.style.transform = `translate(${cX}px, ${cY}px)`;
                rafId = requestAnimationFrame(animate);
            }

            el.addEventListener('mousemove', e=>{
                const r = el.getBoundingClientRect();
                const x = (e.clientX - r.left)/r.width - .5;
                const y = (e.clientY - r.top)/r.height - .5;
                tX = x * strength; tY = y * strength;
                if(!rafId) animate();
            });
            el.addEventListener('mouseleave', ()=>{
                tX=0; tY=0;
                cancelAnimationFrame(rafId); rafId=null;
                el.style.transform = '';
            });
        });
    })();
</script>
</body>
</html>
