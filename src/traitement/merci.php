<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Merci — Paristanbul</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://play-lh.googleusercontent.com/4-hTf32960CWp7N_cBSNN7UnH3UNHMzgye3wGzXqSp69-iAc7-88jwc1jPlkeqDktLE=w240-h480" />
    <style>
        :root{
            --brand-blue:#1f4aa9;
            --brand-blue-2:#2a64d4;
            --brand-red:#e53935;
        }

        *{box-sizing:border-box}

        /* Fond Paristanbul en dégradé + halos */
        body{
            margin:0;
            font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
            color:#111;
            min-height:100vh;
            background:
                    radial-gradient(1200px 600px at -10% -10%, rgba(255,255,255,.10) 0%, rgba(255,255,255,0) 60%),
                    radial-gradient(900px 450px at 110% 0%, rgba(255,255,255,.10) 0%, rgba(255,255,255,0) 60%),
                    linear-gradient(135deg, var(--brand-blue) 0%, var(--brand-blue-2) 45%, var(--brand-red) 100%);
            background-attachment: fixed;
        }

        .wrap{
            min-height:100vh;
            display:grid;
            place-items:center;
            padding:24px;
        }

        .card{
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:20px;
            padding:32px 28px;
            max-width:640px;
            width:min(100%, 720px);
            text-align:center;
            box-shadow:0 10px 30px rgba(31,74,169,.15);
        }

        .logo{
            height:56px;
            border-radius:12px;
            background:#fff;
            padding:6px;
            margin-bottom:10px;
            box-shadow:0 2px 10px rgba(0,0,0,.06);
        }

        h1{
            margin:8px 0 6px;
            color:var(--brand-blue);
            display:inline-block;
            padding-bottom:4px;
            border-bottom:6px solid var(--brand-red);
            font-size:28px;
        }

        p{margin:10px 0 0; line-height:1.6}

        a.button{
            display:inline-block;
            margin-top:16px;
            background:var(--brand-blue);
            color:#fff;
            padding:12px 18px;
            border-radius:12px;
            text-decoration:none;
            font-weight:700;
        }
        a.button:hover{filter:brightness(.95)}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <img class="logo" src="https://play-lh.googleusercontent.com/4-hTf32960CWp7N_cBSNN7UnH3UNHMzgye3wGzXqSp69-iAc7-88jwc1jPlkeqDktLE=w240-h480" alt="Paristanbul">
        <br>
        <h1>Merci pour votre candidature !</h1>
        <br>
        <p>Votre dossier a bien été transmis à notre équipe RH de Melun. Nous vous contacterons si votre profil correspond.</p>
        <a class="button" href="../../vue/index.php">Page d'accueil</a>
    </div>
</div>
</body>
</html>