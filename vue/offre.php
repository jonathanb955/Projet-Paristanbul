<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Postuler — Supermarché Paristanbul</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-blue: #0d1b2a;
            --brand-blue-2: #1b263b;
            --brand-red: #a4161a;
            --dark: #0a0a0a;
            --light: #e5e5e5;
            --radius:16px;
            --gap:16px;
            --pad:16px;
            --input-h:48px;
        }

        /* --- ANIMATIONS KEYFRAMES --- */
        @keyframes fadeZoomIn {
            0% {opacity:0; transform:scale(.92) translateY(20px);}
            60% {opacity:.8; transform:scale(1.02) translateY(0);}
            100% {opacity:1; transform:scale(1);}
        }
        @keyframes subtleGlow {
            0%,100% {box-shadow:0 0 25px rgba(164,22,26,.25);}
            50% {box-shadow:0 0 40px rgba(164,22,26,.35);}
        }
        @keyframes pulseFocus {
            0% {box-shadow:0 0 0 0 rgba(164,22,26,.6);}
            70% {box-shadow:0 0 10px 6px rgba(164,22,26,.0);}
            100% {box-shadow:0 0 0 0 rgba(164,22,26,.0);}
        }

        html { font-size:16px; -webkit-text-size-adjust:100% }
        * { box-sizing:border-box; }
        body {
            font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
            margin:0;
            min-height:100vh;
            background:
                    radial-gradient(1200px 600px at -10% -10%, rgba(164,22,26,.15) 0%, transparent 60%),
                    radial-gradient(900px 450px at 110% 0%, rgba(13,27,42,.4) 0%, transparent 60%),
                    linear-gradient(135deg, var(--brand-blue) 0%, var(--brand-blue-2) 40%, var(--brand-red) 100%);
            background-attachment: fixed;
            color: var(--light);
        }
        .container {
            max-width:960px;
            margin:24px auto;
            padding:32px;
            background:rgba(0,0,0,0.65);
            border:1px solid rgba(255,255,255,0.08);
            border-radius:20px;
            backdrop-filter: blur(6px);
            animation: fadeZoomIn 1s ease forwards, subtleGlow 6s ease-in-out infinite;
        }
        header {
            display:flex; gap:16px; align-items:center; padding:8px 0 24px;
        }
        header img {
            height:60px; flex:0 0 auto; border-radius:12px;
            box-shadow:0 0 15px rgba(0,0,0,.7);
            transition:transform .6s ease;
        }
        header img:hover { transform:rotate(-3deg) scale(1.05); }
        h1 { margin:0; font-size:30px; line-height:1.2; color:#fff; }
        header div > div { color:#ccc; }

        .card {
            background:rgba(20,20,20,0.75);
            border:1px solid rgba(255,255,255,0.08);
            border-radius:var(--radius);
            padding:24px;
            box-shadow:0 4px 18px rgba(0,0,0,.4);
            animation: fadeZoomIn 1.2s ease;
        }
        .grid { display:grid; grid-template-columns:1fr; gap:var(--gap);}
        .grid-1 { display:grid; grid-template-columns:1fr; gap:var(--gap);}
        @media (min-width:640px){
            .grid{grid-template-columns:1fr 1fr}
            header{padding:24px 0}
        }

        label {
            font-weight:600;
            margin-bottom:6px;
            display:block;
            color:#eee;
        }
        input[type=text],input[type=date],input[type=email],input[type=tel],textarea,select {
            width:100%;
            padding:12px 14px;
            border:1px solid rgba(255,255,255,0.2);
            border-radius:12px;
            background:rgba(255,255,255,0.05);
            color:#fff;
            font-size:15px;
            line-height:1.2;
            min-height:var(--input-h);
            transition: border-color 0.3s, background 0.3s, transform 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            outline:none;
            border-color: var(--brand-red);
            background:rgba(255,255,255,0.12);
            animation: pulseFocus 1.2s ease-out;
            transform: scale(1.02);
        }
        textarea { min-height:120px; resize:vertical; }
        .hint { font-size:12px; color:#aaa; margin-top:4px }
        .inline { display:flex; gap:16px; align-items:center; flex-wrap:wrap; color:#ccc;}
        .hidden { display:none; }
        .required::after { content:" *"; color:var(--brand-red); font-weight:700; }
        .actions {
            display:flex; justify-content:space-between; align-items:center;
            margin-top:16px; gap:16px; flex-wrap:wrap;
        }
        .btn {
            background: var(--brand-red);
            border:none;
            color:#fff;
            font-weight:700;
            padding:12px 22px;
            border-radius:12px;
            cursor:pointer;
            min-height:48px;
            position:relative;
            overflow:hidden;
            transition:transform 0.25s ease;
        }
        .btn::before {
            content:"";
            position:absolute;
            top:-50%;
            left:-50%;
            width:200%;
            height:200%;
            background: radial-gradient(circle at center,
            rgba(255,255,255,.2) 0%, transparent 70%);
            transform:scale(0);
            transition:transform 0.5s ease;
        }
        .btn:hover::before { transform:scale(1); }
        .btn:hover { transform:translateY(-2px) scale(1.03); }
        .btn:active { transform:scale(0.97); }
        .footer-note { font-size:12px; color:#aaa; margin-top:8px }
        .error { color:var(--brand-red); font-size:14px; margin-top:8px }

        body{padding-bottom:env(safe-area-inset-bottom)}
        @media (max-width:639.98px){
            header{flex-direction:column;align-items:flex-start}
            header img{height:48px}
            .container{margin:16px auto;padding:20px}
            body{background-attachment: scroll;}
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <img src="../assets/img/petit-logo.jpg" alt="Logo"/>
        <div>
            <h1>Recrutement — Supermarché Paristanbul</h1>
            <div>Merci de remplir ce formulaire pour postuler.</div>
        </div>
    </header>

    <form class="card"
          action="https://formsubmit.co/aac791e09d354dacc64b91b94bba2646"
          method="POST"
          enctype="multipart/form-data">

        <div class="grid">
            <div>
                <label class="required" for="nom">Nom</label>
                <input id="nom" name="Nom" type="text" required />
            </div>
            <div>
                <label class="required" for="prenom">Prénom</label>
                <input id="prenom" name="Prénom" type="text" required />
            </div>
        </div>

        <div class="grid">
            <div>
                <label class="required" for="telephone">Numéro de téléphone</label>
                <input id="telephone" name="Téléphone" type="tel"
                       placeholder="+33 6 12 34 56 78"
                       pattern="^(\+33|0)[1-9](\d{2}){4}$" required />
                <div class="hint">Format FR : 06XXXXXXXX ou +33 6 XX XX XX XX.</div>
            </div>
            <div>
                <label class="required" for="email">Adresse e-mail</label>
                <input id="email" name="Email" type="email" placeholder="vous@exemple.com" required />
            </div>
        </div>

        <div class="grid">
            <div>
                <label class="required" for="date_naissance">Date de naissance</label>
                <input id="date_naissance" name="Date de naissance" type="date" required />
            </div>
            <div>
                <label class="required" for="langues">Langues parlées</label>
                <input id="langues" name="Langues" type="text" placeholder="Français, Turc, Anglais…" required />
            </div>
        </div>

        <div class="grid-1">
            <div>
                <label class="required" for="adresse">Adresse</label>
                <input id="adresse" name="Adresse" type="text" placeholder="N°, Rue, Ville, Code postal" required />
            </div>
        </div>

        <div class="grid">
            <div>
                <label class="required">Permis de conduire</label>
                <div class="inline" role="radiogroup">
                    <label><input type="radio" name="Permis" value="Oui" required> Oui</label>
                    <label><input type="radio" name="Permis" value="Non"> Non</label>
                </div>
            </div>
            <div id="vehicule-group" class="hidden">
                <label class="required">Véhicule</label>
                <div class="inline" role="radiogroup">
                    <label><input type="radio" name="Véhicule" value="Oui"> Oui</label>
                    <label><input type="radio" name="Véhicule" value="Non"> Non</label>
                </div>
            </div>
        </div>

        <div class="grid-1">
            <div>
                <label class="required" for="experiences">Expériences</label>
                <textarea id="experiences" name="Expériences" required></textarea>
            </div>
        </div>

        <div class="grid-1">
            <div>
                <label class="required" for="lettre_motivation">Lettre de motivation</label>
                <textarea id="lettre_motivation" name="Lettre de motivation" required></textarea>
            </div>
        </div>

        <div class="grid">
            <div>
                <label for="cv">CV (PDF, DOCX) — optionnel</label>
                <input id="cv" name="CV" type="file" accept=".pdf,.doc,.docx" />
            </div>
            <div>
                <label for="autre">Autre pièce jointe — optionnel</label>
                <input id="autre" name="Autre pièce jointe" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
            </div>
        </div>

        <div class="inline" style="margin-top:8px">
            <label><input type="checkbox" name="Consentement" required> J'accepte que mes données soient utilisées pour traiter ma candidature.</label>
        </div>

        <div class="actions">
            <div class="footer-note">Les champs marqués * sont obligatoires.</div>
            <button class="btn" type="submit">Envoyer ma candidature</button>
        </div>
    </form>
</div>

<script>
    const permisRadios = document.querySelectorAll('input[name="Permis"]');
    const vehiculeGroup = document.getElementById('vehicule-group');
    function syncVehiculeRequired() {
        const checked = document.querySelector('input[name="Permis"]:checked');
        if (checked && checked.value === 'Oui') {
            vehiculeGroup.classList.remove('hidden');
            document.querySelectorAll('input[name="Véhicule"]').forEach(v => v.required = true);
        } else {
            vehiculeGroup.classList.add('hidden');
            document.querySelectorAll('input[name="Véhicule"]').forEach(v => v.required = false);
        }
    }
    permisRadios.forEach(r => r.addEventListener('change', syncVehiculeRequired));
    syncVehiculeRequired();
</script>
</body>
</html>
