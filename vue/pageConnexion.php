<?php
var_dump($_POST);
$dsn = 'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8';
$bdd = new PDO($dsn, 'root', '');

// Vérification que le formulaire a été soumis
if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
    $login = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Requête admin
    $sqlAdmin = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = ? AND role = 'admin'");
    $sqlAdmin->execute([$login]);
    $lignesAdmin = $sqlAdmin->fetch();

    // Requête utilisateur
    $sqlUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = ? AND role = 'utilisateur'");
    $sqlUser->execute([$login]);
    $ligneUser = $sqlUser->fetch();

    if ($ligneUser && password_verify($mdp, $ligneUser['mdp'])) {
        header('Location: index.php');
        exit();
    } elseif ($lignesAdmin && password_verify($mdp, $lignesAdmin['mdp'])) {
        header('Location: pageAdmin.php');
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion — Paristanbul</title>
    <meta name="color-scheme" content="dark light">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        piBlue: '#2E4C97',   // bleu logo
                        piRed:  '#D6452E',   // rouge logo
                        piNavy: '#0B1326',   // fond sombre premium
                        piInk:  '#E6E9F2'    // texte clair
                    },
                    boxShadow: {
                        glow: '0 12px 32px rgba(0,0,0,.45)'
                    },
                    borderRadius: {
                        xl: '16px'
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html, body { height: 100%; }
        body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial; }
    </style>
</head>
<body class="min-h-full bg-[radial-gradient(1000px_500px_at_10%_10%,rgba(46,76,151,.25),transparent_60%),radial-gradient(900px_600px_at_90%_10%,rgba(214,69,46,.18),transparent_55%),linear-gradient(180deg,#0B1326,#0A0F1F_70%)] text-piInk">
<!-- halo décoratif -->
<div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
    <div class="absolute left-0 top-0 h-[22rem] w-[22rem] -translate-x-1/3 -translate-y-1/3 rounded-full bg-piBlue/25 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 h-[26rem] w-[26rem] translate-x-1/4 translate-y-1/4 rounded-full bg-piRed/20 blur-3xl"></div>
</div>

<main class="relative z-10 flex min-h-full items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="mb-6 flex items-center justify-center gap-3">
            <img src="/assets/img/paristanbul_logo.png" alt="Paristanbul" class="h-10 w-auto drop-shadow" onerror="this.style.display='none'">
            <span class="sr-only">Paristanbul</span>
        </div>

        <div class="rounded-xl border border-white/10 bg-white/5 shadow-glow backdrop-blur-xl">
            <div class="p-8">
                <h1 class="mb-1 text-2xl font-bold">Connexion</h1>
                <p class="mb-6 text-sm text-slate-300">Connectez‑vous pour accéder à votre espace.</p>

                <form id="login-form" class="grid gap-4" autocomplete="on" action="pageConnexion.php" method="post">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-200">Adresse e-mail</label>
                        <input id="email" name="email" type="email" required placeholder="vous@paristanbul.fr"
                               class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white placeholder-slate-400 outline-none focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="mdp" class="block text-sm font-medium text-slate-200">Mot de passe</label>
                        </div>
                        <div class="relative">
                            <input id="mdp" name="mdp" type="password" minlength="8" required placeholder="••••••••"
                                   class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 pr-12 text-white placeholder-slate-400 outline-none focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                            <button type="button" id="togglePass" aria-label="Afficher le mot de passe"
                                    class="absolute inset-y-0 right-0 mr-2 inline-flex items-center rounded-lg p-2 text-slate-300 hover:bg-white/10">
                                👁
                            </button>
                        </div>
                    </div>

                    <button id="submitBtn" type="submit"
                            class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-b from-piRed to-[#b53a27] px-4 py-3 font-semibold text-white shadow-lg shadow-[#b53a27]/30 transition hover:from-[#b53a27] hover:to-[#9f2f20] focus:outline-none focus:ring-2 focus:ring-piRed/40 disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg id="spinner" class="hidden h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" opacity="0.25"/><path d="M22 12a10 10 0 0 1-10 10"/></svg>
                        <span>Se connecter</span>
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">© Paristanbul — Depuis 1993</p>
    </div>
</main>

<script>
    // Toggle password visibility
    const pass = document.getElementById('mdp');
    document.getElementById('togglePass').addEventListener('click', () => {
        pass.type = pass.type === 'password' ? 'text' : 'password';
    });

    const form = document.getElementById('login-form');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('spinner');

    form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        spinner.classList.remove('hidden');
    });
</script>

</body>
</html>
