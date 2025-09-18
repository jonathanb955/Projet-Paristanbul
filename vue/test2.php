<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Paristanbul — Notre Histoire</title>
    <meta name="theme-color" content="#0b1326" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- AOS (scroll animations) -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root{
            --bg-1:#0A0F1F;     /* noir bleuté profond */
            --bg-2:#0B1326;     /* noir dominant */
            --ink:#E6E9F2;      /* texte principal */
            --muted:#ffffff;    /* texte secondaire */
            --pi-blue:#2E4C97;  /* bleu Paristanbul */
            --pi-red:#B4322B;   /* rouge Paristanbul */
            --card:#121826;     /* cartes */
            --chip:#192239;     /* pastilles/puces */
            --line:#ffffff;     /* lignes */
        }

        html,body{height:100%}
        body{
            font-family:"Inter",system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
            color:var(--ink);
            background:
                    radial-gradient(900px 500px at 10% 5%, rgba(46,76,151,.25), transparent 60%),
                    radial-gradient(800px 600px at 90% 10%, rgba(180,50,43,.18), transparent 55%),
                    linear-gradient(180deg,var(--bg-2),var(--bg-1) 70%);
            overflow-x:hidden;
        }

        /* ===== Top progress bar ===== */
        .progress{
            position:fixed; inset:0 0 auto 0; height:3px;
            background:linear-gradient(90deg,var(--pi-blue),var(--pi-red));
            transform-origin:0 50%; transform:scaleX(0); z-index:1000;
        }

        /* ===== Navbar ===== */
        header .navbar{
            display:flex; align-items:center; justify-content:space-between;
            gap:1rem; padding:.8rem 1rem;
            background:linear-gradient(180deg,rgba(11,19,38,.95),rgba(11,19
