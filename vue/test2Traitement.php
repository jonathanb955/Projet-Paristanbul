<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Catégories</title>

    <!-- Police optionnelle -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg:#e9e9eb; --text:#1f2937; --kicker:#3b5488;
            --circle:220px;    /* taille des ronds (desktop) */
            --gap:42px;
        }
        *{box-sizing:border-box}
        body{margin:0;font-family:Inter,system-ui,Arial;background:var(--bg);color:var(--text)}
        .section{max-width:1200px;margin:80px auto;padding:0 24px;text-align:center}
        .kicker{letter-spacing:.35em;color:var(--kicker);font-weight:600;margin-bottom:10px}
        h1{font-size:52px;line-height:1.1;margin:0 0 40px;font-weight:800}
        .grid{display:grid;grid-template-columns:repeat(5,1fr);gap:var(--gap);align-items:start;justify-items:center}
        .card{display:flex;flex-direction:column;align-items:center;gap:14px}
        .thumb{width:var(--circle);height:var(--circle);border-radius:50%;overflow:hidden;
            border:6px solid #fff;box-shadow:0 10px 24px rgba(0,0,0,.12)}
        .thumb img{width:100%;height:100%;object-fit:cover}
        .label{font-weight:700;letter-spacing:.05em;font-size:26px}
        .cta{display:inline-block;margin:36px auto 0;padding:14px 28px;border-radius:999px;
            background:#56a694;color:#fff;font-weight:600;text-decoration:none;
            box-shadow:0 8px 18px rgba(0,0,0,.15)}
        .cta:hover{filter:brightness(1.05)}

        @media (max-width:1200px){:root{--circle:200px}}
        @media (max-width:992px){.grid{grid-template-columns:repeat(3,1fr)}:root{--circle:180px}}
        @media (max-width:680px){.grid{grid-template-columns:repeat(2,1fr)}:root{--circle:150px} h1{font-size:34px}}
    </style>
</head>
<body>
<section class="section">
    <div class="kicker">BLINI TONËN</div>
    <h1>Catégorie De Catégorie</h1>

    <div class="grid">
        <figure class="card">
            <div class="thumb"><img src="img/cat1.png" alt="FRUIT"></div>
            <figcaption class="label">FRUIT</figcaption>
        </figure>

        <figure class="card">
            <div class="thumb"><img src="img/cat2.png" alt="PÉRIMÈTRE"></div>
            <figcaption class="label">PÉRIMÈTRE</figcaption>
        </figure>

        <figure class="card">
            <div class="thumb"><img src="img/cat3.png" alt="MISH"></div>
            <figcaption class="label">MISH</figcaption>
        </figure>

        <figure class="card">
            <div class="thumb"><img src="img/cat4.png" alt="QUMËSHTI"></div>
            <figcaption class="label">QUMËSHTI</figcaption>
        </figure>

        <figure class="card">
            <div class="thumb"><img src="img/cat5.png" alt="USHQIME"></div>
            <figcaption class="label">USHQIME</figcaption>
        </figure>
    </div>

    <a href="#" class="cta">Blini Tani</a>
</section>
</body>
</html>
