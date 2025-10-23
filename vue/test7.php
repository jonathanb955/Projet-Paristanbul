<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bandeau défilant — Paristanbul</title>

    <style>
        :root{
            --pi-blue:#2E4C97;
            --pi-red:#D6452E;
            --strip-h: 46px;             /* hauteur du bandeau */
            --strip-speed: 70;           /* vitesse en px/s (modifie la vitesse) */
            --strip-bg-top:#0f1525;      /* même fond que le header */
            --strip-bg-bottom:#0c1223;
        }

        /* ====== BANDEAU DÉFILANT EN HAUT ====== */
        .pi-strip{
            position: sticky; top: 0; z-index: 999;
            /* fond UNIFORME (pas de ronds) — identique au header */
            background: linear-gradient(180deg, var(--strip-bg-top) 0%, var(--strip-bg-bottom) 100%);
            border-bottom:1px solid #151a2a;
            user-select:none;
        }

        .pi-strip__mask{
            height: var(--strip-h);
            overflow: hidden;
            position: relative;
        }

        /* La piste qui défile (on en aura 2, l’original + le clone) */
        .pi-strip__track{
            position: absolute; inset-block:0; left:0;
            display: flex; align-items: center; gap: 28px;
            white-space: nowrap;
            padding-inline: 18px;
            will-change: transform;
            animation: pi-marquee linear infinite;
            /* durée calculée en JS pour une vitesse constante */
        }
        .pi-strip__track--clone{ /* la deuxième piste enchaîne la première */
            /* même styles, la position sera recalculée en JS */
        }

        /* Pastilles */
        .pill{
            display: inline-flex; align-items: center; gap: 10px;
            padding: 6px 12px; border-radius: 999px;
            font: 600 14px/1.2 system-ui, "Plus Jakarta Sans", Segoe UI, Roboto, Arial;
            color: #e6edff;
            background: linear-gradient(145deg,#121a34,#0f162a);
            border:1px solid #1b2744;
        }
        .pill .dot{
            width:8px; height:8px; border-radius:999px;
            background: conic-gradient(from 90deg, var(--pi-red), var(--pi-blue));
            box-shadow:0 0 8px rgba(255,255,255,.15);
        }

        /* Pause au survol */
        .pi-strip:hover .pi-strip__track{
            animation-play-state: paused;
        }

        /* Accessibilité : si réduit les animations, on stoppe la course */
        @media (prefers-reduced-motion: reduce){
            .pi-strip__track{ animation: none !important; }
        }

        /* Animation (la durée est injectée en JS selon la largeur du contenu) */
        @keyframes pi-marquee{
            from{ transform: translateX(0) }
            to{   transform: translateX(-50%) }
        }

        /* Optionnel : assombrir léger le bord inférieur pour “rappeler” le header */
        .pi-strip{ box-shadow: inset 0 -8px 24px rgba(0,0,0,.25); }
    </style>
</head>
<body>

<!-- ======= STRIP (TOP) ======= -->
<div class="pi-strip" role="region" aria-label="Infos postes défilantes">
    <div class="pi-strip__mask">
        <ul class="pi-strip__track" aria-hidden="true">
            <li class="pill"><span class="dot"></span> Préparateur·rice de commande</li>
            <li class="pill"><span class="dot"></span> Manutentionnaire</li>
            <li class="pill"><span class="dot"></span> Logistique</li>
            <li class="pill"><span class="dot"></span> Caissier·e</li>
            <li class="pill"><span class="dot"></span> Employé·e polyvalent·e</li>
            <li class="pill"><span class="dot"></span> Réception / Stock</li>
            <li class="pill"><span class="dot"></span> Responsable Rayon</li>
            <li class="pill"><span class="dot"></span> Préparateur·rice de commande</li>
            <li class="pill"><span class="dot"></span> Manutentionnaire</li>
            <li class="pill"><span class="dot"></span> Logistique</li>
            <li class="pill"><span class="dot"></span> Caissier·e</li>
            <li class="pill"><span class="dot"></span> Employé·e polyvalent·e</li>
        </ul>
        <!-- piste clonée pour une boucle parfaitement continue -->
        <ul class="pi-strip__track pi-strip__track--clone" aria-hidden="true"></ul>
    </div>
</div>

<!-- Démo: du contenu en dessous pour voir le sticky -->
<div style="height:160vh;background:#0a0f1f;color:#cfe0ff;font:16px/1.6 system-ui,Segoe UI,Roboto,Arial;padding:20px">
    <p>Fais défiler pour voir le bandeau rester en haut.</p>
</div>

<script>
    // ===== Marquee “propré” : vitesse constante, boucle parfaite, pause hover =====
    (function(){
        const SPEED = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--strip-speed')) || 70; // px/s
        const track = document.querySelector('.pi-strip__track:not(.pi-strip__track--clone)');
        const clone = document.querySelector('.pi-strip__track--clone');

        if(!track || !clone) return;

        // 1) Dupliquer le contenu pour supprimer tout “trou” en fin de piste
        clone.innerHTML = track.innerHTML;

        // 2) Mesurer la largeur réelle de la piste (après duplication)
        function totalWidth(el){
            const rect = el.getBoundingClientRect();
            // Largeur réelle du contenu (pas seulement la fenêtre)
            return el.scrollWidth || rect.width;
        }

        function setup(){
            // Positionner la piste clonée immédiatement après la première
            const w = totalWidth(track);
            clone.style.left = w + 'px';

            // 3) Calculer une durée d’animation = 50% (une longueur de piste) à vitesse constante
            // L’animation va de 0 à -50% (puisque 2 pistes identiques)
            // Donc distance = w (pixels). Durée = distance / vitesse.
            const duration = w / SPEED; // en s
            track.style.animationDuration = duration + 's';
            clone.style.animationDuration = duration + 's';
        }

        // 4) (Ré)initialiser au chargement + au redimensionnement
        let resizeTimer;
        const init = ()=>{ setup(); };
        window.addEventListener('load', init);
        window.addEventListener('resize', ()=>{
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(init, 150);
        });
    })();
</script>
</body>
</html>
