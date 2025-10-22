<?php
session_start();

function db_connect(): PDO {
    $tries = [
            ['h'=>'127.0.0.1','p'=>8889,'u'=>'root','pw'=>'root'],
            ['h'=>'127.0.0.1','p'=>3306,'u'=>'root','pw'=>'root'],
            ['h'=>'127.0.0.1','p'=>8889,'u'=>'root','pw'=>''],
            ['h'=>'127.0.0.1','p'=>3306,'u'=>'root','pw'=>''],
    ];
    foreach ($tries as $t) {
        try {
            return new PDO(
                    "mysql:host={$t['h']};port={$t['p']};dbname=bdd_paristanbul;charset=utf8mb4",
                    $t['u'],$t['pw'],
                    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]
            );
        } catch (Throwable $e) {}
    }
    throw new Exception("Connexion MySQL impossible (ports 8889/3306, mdp root/vide).");
}

$form_status = null; $form_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = db_connect();

        $prenom  = trim($_POST['prenom'] ?? '');
        $nom     = trim($_POST['nom'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $mdp     = $_POST['mdp'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        $role    = 'user';

        if ($prenom === '' || $nom === '' || $email === '' || $mdp === '') throw new Exception("Champs requis manquants.");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception("Email invalide.");
        if (strlen($mdp) < 8) throw new Exception("Mot de passe trop court (min 8).");
        if ($mdp !== $confirm) throw new Exception("Les mots de passe ne correspondent pas.");

        $pdo->exec("CREATE TABLE IF NOT EXISTS utilisateurs (
            id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
            nom TINYTEXT NOT NULL,
            prenom TINYTEXT NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            mdp VARCHAR(255) NOT NULL,
            role TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $check = $pdo->prepare("SELECT 1 FROM utilisateurs WHERE email=? LIMIT 1");
        $check->execute([$email]);
        if ($check->fetch()) throw new Exception("Cet email est déjà utilisé.");

        $hash = password_hash($mdp, PASSWORD_BCRYPT);
        $ins = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mdp, role) VALUES (:nom,:prenom,:email,:mdp,:role)");
        $ins->execute([':nom'=>$nom, ':prenom'=>$prenom, ':email'=>$email, ':mdp'=>$hash, ':role'=>$role]);

        header('Location: ' . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/index.php');
        exit;
    } catch (Exception $e) {
        $form_status = 'error';
        $form_msg = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription — Paristanbul</title>
    <meta name="color-scheme" content="dark light">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme:{ extend:{
                    colors:{ piBlue:'#2E4C97', piRed:'#D6452E', piNavy:'#0B1326', piInk:'#E6E9F2' },
                    boxShadow:{ glow:'0 12px 32px rgba(0,0,0,.45)' }, borderRadius:{ xl:'16px' }
                }}}
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>html,body{height:100%} body{font-family:Inter,ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial}</style>
</head>
<body class="min-h-full bg-[radial-gradient(1000px_500px_at_10%_10%,rgba(46,76,151,.25),transparent_60%),radial-gradient(900px_600px_at_90%_10%,rgba(214,69,46,.18),transparent_55%),linear-gradient(180deg,#0B1326,#0A0F1F_70%)] text-piInk">
<main class="relative z-10 flex min-h-full items-center justify-center p-6">
    <div class="w-full max-w-2xl">
        <?php if ($form_status): ?>
            <div class="mb-4 rounded-xl border px-4 py-3 <?php echo $form_status==='success'?'border-emerald-500/30 bg-emerald-500/10 text-emerald-300':'border-rose-500/30 bg-rose-500/10 text-rose-300'; ?>">
                <?php echo htmlspecialchars($form_msg); ?>
            </div>
        <?php endif; ?>

        <div class="rounded-xl border border-white/10 bg-white/5 shadow-glow backdrop-blur-xl p-8 sm:p-10">
            <h1 class="mb-2 text-2xl font-bold">Créer un compte</h1>
            <form class="grid gap-5" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" novalidate>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-200">Prénom</label>
                        <input name="prenom" type="text" required class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white" value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>"/>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-200">Nom</label>
                        <input name="nom" type="text" required class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>"/>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm text-slate-200">Adresse e-mail</label>
                    <input name="email" type="email" required class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"/>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-200">Mot de passe</label>
                        <input id="password" name="mdp" type="password" minlength="8" required class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white"/>
                        <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/10"><div id="meter" class="h-full w-1/6 rounded-full bg-rose-500 transition-all"></div></div>
                        <p id="pass-hint" class="mt-2 text-xs text-slate-400">Force du mot de passe</p>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-200">Confirmer le mot de passe</label>
                        <input id="confirm" name="confirm" type="password" minlength="8" required class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-white"/>
                        <p id="confirm-help" class="mt-2 hidden text-xs text-rose-400">Les mots de passe ne correspondent pas.</p>
                    </div>
                </div>
                <label class="flex items-start gap-3 text-sm text-slate-200">
                    <input type="checkbox" class="mt-1 h-4 w-4 rounded border-white/20 bg-white/10" required>
                    J'accepte les <a href="#" class="ml-1 text-piBlue hover:underline">Conditions générales</a>.
                </label>
                <button type="submit" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-b from-piRed to-[#b53a27] px-4 py-3 font-semibold text-white">Créer mon compte</button>
            </form>
            <div class="mt-6 text-center text-sm text-slate-300">
                Déjà de la famille ?
                <a href="pageConnexion.php" class="font-semibold text-piBlue hover:underline">
                    Connectez-vous
                </a>
            </div>
        </div>
    </div>
</main>

<script>
    const pass=document.getElementById('password');const confirmPwd=document.getElementById('confirm');const meter=document.getElementById('meter');const passHint=document.getElementById('pass-hint');
    function st(p){let s=0;if(p.length>=8)s++;if(/[A-Z]/.test(p))s++;if(/[a-z]/.test(p))s++;if(/[0-9]/.test(p))s++;if(/[^\w]/.test(p))s++;return s;}
    function render(p){const s=st(p);meter.style.width=`${s*20}%`;meter.className='h-full rounded-full transition-all '+(s<=2?'bg-rose-500':s==3?'bg-amber-400':'bg-emerald-500');passHint.textContent=s<=2?'Faible':s==3?'Moyen':'Fort';}
    pass?.addEventListener('input',e=>render(e.target.value));
    const confirmHelp=document.getElementById('confirm-help');
    function validate(){if(confirmPwd.value&&pass.value!==confirmPwd.value){confirmHelp.classList.remove('hidden');}else{confirmHelp.classList.add('hidden');}}
    confirmPwd?.addEventListener('input',validate);pass?.addEventListener('input',validate);
</script>
</body>
</html>