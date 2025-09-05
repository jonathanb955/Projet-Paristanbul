<?php

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

                <form id="login-form" class="grid gap-4" autocomplete="on" action="test2Traitement.php" method="post">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-200">Adresse e‑mail</label>
                        <input id="email" name="email" type="email" required placeholder="vous@paristanbul.fr"
                               class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white placeholder-slate-400 outline-none focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                        <p id="email-error" class="mt-2 hidden text-xs text-rose-400">Adresse e‑mail invalide.</p>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="mdp" class="block text-sm font-medium text-slate-200">Mot de passe</label>
                            <a href="#" class="text-sm font-medium text-piBlue hover:underline">Mot de passe oublié ?</a>
                        </div>
                        <div class="relative">
                            <input id="mdp" name="mdp" type="password" minlength="8" required placeholder="••••••••"
                                   class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 pr-12 text-white placeholder-slate-400 outline-none focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                            <button type="button" id="togglePass" aria-label="Afficher le mot de passe"
                                    class="absolute inset-y-0 right-0 mr-2 inline-flex items-center rounded-lg p-2 text-slate-300 hover:bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <p id="pass-error" class="mt-2 hidden text-xs text-rose-400">Au moins 8 caractères.</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-slate-200"><input type="checkbox" class="h-4 w-4 rounded border-white/20 bg-white/10"/> Se souvenir de moi</label>
                        <a href="../vue/pageInscription.php" class="text-sm text-slate-300 hover:text-white">Pas de compte ? <span class="font-semibold text-piRed">Créer un compte</span></a>
                    </div>

                    <button id="submitBtn" type="submit" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-b from-piRed to-[#b53a27] px-4 py-3 font-semibold text-white shadow-lg shadow-[#b53a27]/30 transition hover:from-[#b53a27] hover:to-[#9f2f20] focus:outline-none focus:ring-2 focus:ring-piRed/40 disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg id="spinner" class="hidden h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" opacity="0.25"/><path d="M22 12a10 10 0 0 1-10 10"/></svg>
                        <span>Se connecter</span>
                    </button>

                    <div class="relative py-2 text-center">
                        <span class="bg-transparent px-3 text-xs text-slate-300">ou</span>
                        <div class="absolute inset-x-0 top-1/2 -z-10 h-px -translate-y-1/2 bg-white/10"></div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/10 px-4 py-3 font-medium text-white hover:bg-white/15">Continuer avec Google</button>
                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/10 px-4 py-3 font-medium text-white hover:bg-white/15">Continuer avec Apple</button>
                    </div>

                    <p id="login-msg" class="hidden text-sm font-semibold text-rose-400">Identifiants incorrects.</p>
                </form>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">© Paristanbul — Depuis 1993</p>
    </div>
</main>
<!--
<script>
    // Toggle password visibility
    const pass = document.getElementById('password');
    document.getElementById('togglePass').addEventListener('click', () => {
        pass.type = pass.type === 'password' ? 'text' : 'password';
    });

    const email = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    const passError = document.getElementById('pass-error');
    const form = document.getElementById('login-form');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('spinner');

    function validate(){
        let ok = true;
        emailError.classList.add('hidden');
        passError.classList.add('hidden');
        if(!email.validity.valid){ emailError.classList.remove('hidden'); ok = false; }
        if(pass.value.length < 8){ passError.classList.remove('hidden'); ok = false; }
        return ok;
    }

    email.addEventListener('input', validate);
    pass.addEventListener('input', validate);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if(!validate()) return;
        submitBtn.disabled = true; spinner.classList.remove('hidden');
        try{
            // TODO: fetch('/api/login', { method:'POST', body: new FormData(form) })
            await new Promise(r => setTimeout(r, 600));
            window.location.href = '/dashboard';
        }catch(err){
            document.getElementById('login-msg').classList.remove('hidden');
        }finally{
            submitBtn.disabled = false; spinner.classList.add('hidden');
        }
    });
</script>
-->
</body>
</html>
