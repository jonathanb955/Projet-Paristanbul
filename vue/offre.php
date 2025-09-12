<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <!-- Empêche le zoom auto iOS quand un input < 16px ; adapte l’échelle -->
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Postuler — Supermarché Paristanbul (Melun)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            /* Couleurs Paristanbul */
            --brand-blue:#1f4aa9;
            --brand-blue-2:#2a64d4;
            --brand-red:#e53935;

            /* Palette */
            --green:#2d9b47;
            --dark:#222;
            --light:#f6f7f9;
            --error:#c1121f;

            /* Échelle mobile */
            --radius:16px;
            --gap:16px;
            --pad:16px;
            --input-h:48px;
        }

        /* Base mobile friendly */
        html{font-size:16px;-webkit-text-size-adjust:100%}
        *{box-sizing:border-box}
        body{
            font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
            color:#222; margin:0; min-height:100vh;

            /* Dégradé + halos */
            background:
                    radial-gradient(1200px 600px at -10% -10%, rgba(255,255,255,.10) 0%, rgba(255,255,255,0) 60%),
                    radial-gradient(900px 450px at 110% 0%, rgba(255,255,255,.10) 0%, rgba(255,255,255,0) 60%),
                    linear-gradient(135deg, var(--brand-blue) 0%, var(--brand-blue-2) 45%, var(--brand-red) 100%);
            /* iOS a du mal avec fixed sur les fonds : on forcera scroll en mobile */
            background-attachment: fixed;
        }

        .container{
            max-width:960px; margin:24px auto; padding:24px;
            background:rgba(255,255,255,.92);
            border:1px solid #e5e7eb; border-radius:20px;
            box-shadow:0 10px 30px rgba(31,74,169,.15);
        }

        header{display:flex;gap:16px;align-items:center;padding:8px 0 16px}
        header img{height:56px;flex:0 0 auto}
        h1{margin:0;font-size:28px;line-height:1.2}
        .badge{display:inline-block;padding:4px 10px;border-radius:999px;background:#eaf0fb;color:#0b3a8a;font-weight:600;font-size:12px}

        .card{
            background:#fff;border:1px solid #e5e7eb;border-radius:var(--radius);
            padding:24px;box-shadow:0 4px 18px rgba(0,0,0,.06)
        }

        /* Grilles : sur mobile => 1 colonne ; ≥640px => 2 colonnes */
        .grid{display:grid;grid-template-columns:1fr;gap:var(--gap)}
        .grid-1{display:grid;grid-template-columns:1fr;gap:var(--gap)}

        @media (min-width:640px){
            .grid{grid-template-columns:1fr 1fr}
            .container{padding:32px}
            header{padding:24px 0}
        }

        label{font-weight:600;margin-bottom:6px;display:block}
        input[type=text],input[type=date],input[type=email],input[type=tel],textarea,select{
            width:100%;
            padding:12px 14px;
            border:1px solid #d1d5db;border-radius:12px;background:#fff;font-size:15px;
            line-height:1.2; min-height:var(--input-h);
        }
        /* Empêche le zoom iOS lors du focus si police < 16px */
        input, select, textarea { font-size:16px; }

        textarea{min-height:120px;resize:vertical}
        .hint{font-size:12px;color:#666;margin-top:4px}
        .inline{display:flex;gap:16px;align-items:center;flex-wrap:wrap}
        .inline label{font-weight:500}
        .hidden{display:none}
        .required::after{content:" *";color:var(--error);font-weight:700}
        .actions{display:flex;justify-content:space-between;align-items:center;margin-top:16px;gap:16px;flex-wrap:wrap}

        /* Bouton tactile */
        .btn{
            background:var(--brand-blue); border:none; color:#fff; font-weight:700;
            padding:12px 18px;border-radius:12px;cursor:pointer; min-height:48px;
            touch-action: manipulation; -webkit-tap-highlight-color: transparent;
        }
        .btn:hover{filter:brightness(.95)}
        .btn:active{transform:translateY(1px)}

        /* Focus accessible */
        :focus-visible{
            outline:2px solid transparent;
            box-shadow:0 0 0 3px rgba(42,100,212,.35), 0 0 0 1px var(--brand-blue-2) inset;
            border-radius:12px;
        }

        .footer-note{font-size:12px;color:#6b7280;margin-top:8px}
        .error{color:var(--error);font-size:14px;margin-top:8px}

        /* Sécurité “safe-area” iPhone (encoches) */
        .container{padding-left:calc(24px + env(safe-area-inset-left)); padding-right:calc(24px + env(safe-area-inset-right));}
        body{padding-bottom:env(safe-area-inset-bottom)}

        /* Mobile : header en colonne + logo réduit + fond non-fixed pour fluidité */
        @media (max-width:639.98px){
            header{flex-direction:column;align-items:flex-start}
            header img{height:48px}
            .container{margin:16px auto;padding:16px}
            body{background-attachment: scroll;}
        }

        /* Respecte l’accessibilité mouvement réduit */
        @media (prefers-reduced-motion: reduce){
            .btn:active{transform:none}
        }
    </style>
</head>

<header>
    <img src="https://play-lh.googleusercontent.com/4-hTf32960CWp7N_cBSNN7UnH3UNHMzgye3wGzXqSp69-iAc7-88jwc1jPlkeqDktLE=w240-h480" alt="Logo" />
    <div>
        <h1>Recrutement — Supermarché Paristanbul <span class="badge">Bondy - Drancy - Villiers-le-Bel - Villemomble - Nogent-sur-Oise - Melun - Vert-Saint-Denis</span></h1>
        <div>Merci de remplir ce formulaire pour postuler.</div>
    </div>
</header>
<body>
<form class="card" action="../src/traitement/gestionOffres.php" method="POST" enctype="multipart/form-data" id=<?php echo $_GET['id']; ?> novalidate>
    <input type="hidden" name="ref_offre" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">

    <div class="grid">
        <div>
            <label class="required" for="nom">Nom</label>
            <input id="nom" name="nom" type="text" autocomplete="family-name" autocapitalize="words" required />
        </div>
        <div>
            <label class="required" for="prenom">Prénom</label>
            <input id="prenom" name="prenom" type="text" autocomplete="given-name" autocapitalize="words" required />
        </div>
    </div>

    <div class="grid">
        <div>
            <label class="required" for="telephone">Numéro de téléphone</label>
            <input id="telephone" name="telephone" type="tel"
                   placeholder="+33 6 12 34 56 78"
                   inputmode="tel"
                   autocomplete="tel"
                   pattern="^(\+33|0)[1-9](\d{2}){4}$"
                   required />
            <div class="hint">Format FR accepté : 06XXXXXXXX ou +33 6 XX XX XX XX.</div>
        </div>
        <div>
            <label class="required" for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" placeholder="vous@exemple.com" autocomplete="email" required />
        </div>
    </div>

    <div class="grid">
        <div>
            <label class="required" for="date_naissance">Date de naissance</label>
            <input id="date_naissance" name="date_naissance" type="date" autocomplete="bday" required />
        </div>
        <div>
            <label class="required" for="langues">Langues parlées</label>
            <input id="langues" name="langues" type="text" placeholder="Français, Turc, Anglais…" autocomplete="off" required />
        </div>
    </div>

    <div class="grid-1">
        <div>
            <label class="required" for="adresse">Adresse</label>
            <input id="adresse" name="adresse" type="text" placeholder="N°, Rue, Ville, Code postal"
                   autocomplete="street-address" required />
        </div>
    </div>

    <div class="grid">
        <div>
            <label class="required">Permis de conduire</label>
            <div class="inline" role="radiogroup" aria-label="Permis de conduire">
                <label><input type="radio" name="permis" value="oui" required> Oui</label>
                <label><input type="radio" name="permis" value="non"> Non</label>
            </div>
        </div>
        <div id="vehicule-group" class="hidden">
            <label class="required">Véhicule</label>
            <div class="inline" role="radiogroup" aria-label="Véhicule">
                <label><input type="radio" name="vehicule" value="oui"> Oui</label>
                <label><input type="radio" name="vehicule" value="non"> Non</label>
            </div>
        </div>
    </div>

    <div class="grid-1">
        <div>
            <label class="required" for="experiences">Expériences (poste, durée, missions)</label>
            <textarea id="experiences" name="experiences" required placeholder="Décrivez rapidement vos expériences pertinentes." spellcheck="false"></textarea>
        </div>
    </div>

    <div class="grid-1">
        <div>
            <label class="required" for="lettre_motivation">Lettre de motivation</label>
            <textarea id="lettre_motivation" name="lettre_motivation" required placeholder="Expliquez votre motivation, vos disponibilités, etc." spellcheck="true"></textarea>
        </div>
    </div>

    <div class="grid">
        <div>
            <label for="cv">CV (PDF, DOCX) — optionnel</label>
            <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" />
        </div>
        <div>
            <label for="autre">Autre pièce jointe — optionnel</label>
            <input id="autre" name="autre" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
        </div>
    </div>

    <!-- Anti-spam (honeypot) -->
    <div class="hidden" aria-hidden="true">
        <label>Votre site web</label>
        <input type="text" name="website" autocomplete="off" tabindex="-1" />
    </div>

    <div class="inline" style="margin-top:8px">
        <label><input type="checkbox" name="consent" required> J'accepte que mes données soient utilisées pour traiter ma candidature.</label>
    </div>

    <div class="actions">
        <div class="footer-note">Les champs marqués * sont obligatoires.</div>
        <button class="btn" type="submit">Envoyer ma candidature</button>
        <button class="btn" onclick="window.location.href='index.php';">
            Retour à l'accueil
        </button>
    </div>

    <div id="form-error" class="error" role="alert" aria-live="polite" style="display:none;"></div>

    <?php if(isset($_GET['err'])): ?>
        <div class="error">Erreur : <?php echo htmlspecialchars($_GET['err']); ?></div>
    <?php endif; ?>
</form>
</body>
</html>
