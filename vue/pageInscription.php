<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription — Paristanbul</title>
    <meta name="color-scheme" content="dark light">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        piBlue: '#2E4C97',
                        piRed:  '#D6452E',
                        piNavy: '#0B1326',
                        piInk:  '#E6E9F2'
                    },
                    boxShadow: {
                        glow: '0 12px 32px rgba(0,0,0,.45)'
                    },
                    borderRadius: { xl: '16px' }
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
<div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
    <div class="absolute left-0 top-0 h-[22rem] w-[22rem] -translate-x-1/3 -translate-y-1/3 rounded-full bg-piBlue/25 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 h-[26rem] w-[26rem] translate-x-1/4 translate-y-1/4 rounded-full bg-piRed/20 blur-3xl"></div>
</div>

<main class="relative z-10 flex min-h-full items-center justify-center p-6">
    <div class="w-full max-w-2xl">
        <div class="mb-6 flex items-center justify-center gap-3">
            <img src="/assets/img/paristanbul_logo.png" alt="Paristanbul" class="h-10 w-auto drop-shadow" onerror="this.style.display='none'">
        </div>

        <div class="rounded-xl border border-white/10 bg-white/5 shadow-glow backdrop-blur-xl">
            <div class="p-8 sm:p-10">
                <a href="../vue/pageConnexion.php" class="mb-6 inline-block text-sm text-slate-300 hover:text-white">← Déjà inscrit ? <span class="text-piRed">Se connecter</span></a>
                <h1 class="mb-2 text-2xl font-bold">Créer un compte</h1>
                <p class="mb-8 text-sm text-slate-300">Rejoignez Paristanbul en quelques secondes.</p>

                <form id="register-form" class="grid gap-5" novalidate>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="first" class="mb-2 block text-sm text-slate-200">Prénom</label>
                            <input id="first" name="first" type="text" required placeholder="Ex. Lina"
                                   class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                        </div>
                        <div>
                            <label for="last" class="mb-2 block text-sm text-slate-200">Nom</label>
                            <input id="last" name="last" type="text" required placeholder="Ex. Martin"
                                   class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                        </div>
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm text-slate-200">Adresse e‑mail</label>
                        <input id="email" name="email" type="email" required placeholder="vous@paristanbul.fr"
                               class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                        <p id="email-help" class="mt-2 hidden text-xs text-rose-400">Adresse invalide.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-sm text-slate-200">Mot de passe</label>
                            <input id="password" name="password" type="password" minlength="8" required placeholder="Au moins 8 caractères"
                                   class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                            <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/10">
                                <div id="meter" class="h-full w-1/6 rounded-full bg-rose-500 transition-all"></div>
                            </div>
                            <p id="pass-hint" class="mt-2 text-xs text-slate-400">Force du mot de passe</p>
                        </div>

                        <div>
                            <label for="confirm" class="mb-2 block text-sm text-slate-200">Confirmer le mot de passe</label>
                            <input id="confirm" name="confirm" type="password" minlength="8" required placeholder="Répétez le mot de passe"
                                   class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-piBlue focus:ring-2 focus:ring-piBlue/30" />
                            <p id="confirm-help" class="mt-2 hidden text-xs text-rose-400">Les mots de passe ne correspondent pas.</p>
                        </div>
                    </div>

                    <label class="flex items-start gap-3 text-sm text-slate-200">
                        <input id="terms" type="checkbox" class="mt-1 h-4 w-4 rounded border-white/20 bg-white/10" required>
                        J'accepte les <a href="#" class="ml-1 text-piBlue hover:underline">Conditions générales</a>.
                    </label>

                    <button type="submit" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-b from-piRed to-[#b53a27] px-4 py-3 font-semibold text-white shadow-lg shadow-[#b53a27]/30 transition hover:from-[#b53a27] hover:to-[#9f2f20] focus:outline-none focus:ring-2 focus:ring-piRed/40">
                        Créer mon compte
                    </button>

                    <p id="form-msg" class="hidden text-sm font-semibold text-rose-400">Veuillez corriger les erreurs.</p>
                    <p id="form-ok" class="hidden text-sm font-semibold text-emerald-400">Compte créé ! Redirection…</p>
                </form>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">© Paristanbul — Depuis 1993</p>
    </div>
</main>

<script>
    const pass = document.getElementById('password');
    const confirm = document.getElementById('confirm');
    const meter = document.getElementById('meter');
    const passHint = document.getElementById('pass-hint');

    function strength(p){
        let s = 0; if(p.length >= 8) s++; if(/[A-Z]/.test(p)) s++; if(/[a-z]/.test(p)) s++; if(/[0-9]/.test(p)) s++; if(/[^\w]/.test(p)) s++;
        return s;
    }
    function renderStrength(p){
        const s = strength(p);
        meter.style.width = `${s*20}%`;
        meter.className = 'h-full rounded-full transition-all ' + (s<=2 ? 'bg-rose-500' : s==3 ? 'bg-amber-400' : 'bg-emerald-500');
        passHint.textContent = s<=2 ? 'Faible' : s==3 ? 'Moyen' : 'Fort';
    }
    pass.addEventListener('input', e => renderStrength(e.target.value));

    const confirmHelp = document.getElementById('confirm-help');
    function validateConfirm(){
        if(confirm.value && pass.value !== confirm.value){ confirmHelp.classList.remove('hidden'); }
        else { confirmHelp.classList.add('hidden'); }
    }
    confirm.addEventListener('input', validateConfirm);
    pass.addEventListener('input', validateConfirm);

    const form = document.getElementById('register-form');
    form.addEventListener('submit', (e)=>{
        e.preventDefault();
        if(pass.value !== confirm.value){ confirmHelp.classList.remove('hidden'); return; }
        document.getElementById('form-ok').classList.remove('hidden');
        setTimeout(()=>{ window.location.href = 'login.html'; }, 1200);
    });
</script>
</body>
</html>
