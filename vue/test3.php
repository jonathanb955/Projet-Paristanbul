<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Carousel — effet identique à la vidéo</title>

    <style>
        :root{
            --bg:#0a0a0a;
            --gap: clamp(28px, 4vw, 56px);
            --card-size: clamp(220px, 22vw, 360px);
            --radius: 22px;
            --border: 6px;          /* épaisseur du “cadre” dégradé */
            --speed: 22s;           /* vitesse du défilement */
        }

        /* PAGE */
        * { box-sizing:border-box }
        html,body{ height:100% }
        body{
            margin:0;
            background:var(--bg);
            color:#fff;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Inter, "Helvetica Neue", Arial, sans-serif;
            display:grid;
            place-items:center;
            overflow:hidden;
        }

        /* STRIP (bande) */
        .strip{
            width:100%;
            padding: clamp(16px, 3vh, 28px) 0;
            overflow:hidden;
            position:relative;
        }

        /* légère atténuation des extrémités (comme sur certaines captures) */
        .strip::before,
        .strip::after{
            content:"";
            position:absolute; top:0; bottom:0; width:10vw; pointer-events:none;
            z-index:2;
        }
        .strip::before{
            left:0;
            background:linear-gradient(90deg, var(--bg) 0%, rgba(10,10,10,0.0) 100%);
        }
        .strip::after{
            right:0;
            background:linear-gradient(270deg, var(--bg) 0%, rgba(10,10,10,0.0) 100%);
        }

        /* MARQUEE */
        .marquee{
            width: max(200%, 200vw);               /* espace pour l’animation continue */
        }
        .track{
            display:flex;
            align-items:center;
            gap: var(--gap);
            width:max-content;
            animation: scroll var(--speed) linear infinite;
        }
        /* pause au survol si besoin */
        .strip:hover .track{ animation-play-state: paused; }

        @keyframes scroll{
            from{ transform:translateX(0) }
            to  { transform:translateX(-50%) }    /* -50% car on duplique le lot de cartes */
        }

        /* CARTE AVEC BORDURE DÉGRADÉE ARRONDIE */
        .card{
            width: var(--card-size);
            aspect-ratio: 1 / 1;
            border-radius: calc(var(--radius) + var(--border));
            padding: var(--border);
            background:
                /* “fond” interne (noir) */
                    linear-gradient(#0b0b0b, #0b0b0b) padding-box,
                        /* bordure dégradée façon liseré blanc “brillant” */
                    conic-gradient(from 225deg at 20% 80%,
                    rgba(255,255,255,0.95),
                    rgba(255,255,255,0.15),
                    rgba(255,255,255,0.6),
                    rgba(255,255,255,0.2),
                    rgba(255,255,255,0.9)
                    ) border-box;
            border: 2px solid transparent;
            box-shadow:
                    inset 0 0 0 1px rgba(255,255,255,0.04),
                    0 12px 40px rgba(0,0,0,0.55);
        }

        .card__inner{
            width:100%;
            height:100%;
            border-radius: var(--radius);
            overflow:hidden;
            background:#0a0a0a; /* carte “vide” = noir */
            display:block;
            position:relative;
        }

        .card__img{
            position:absolute; inset:0;
            width:100%; height:100%;
            object-fit:cover;
            display:block;
        }

        /* subtilité : légère douceur sur les coins (comme la vidéo) */
        .card__inner::after{
            content:"";
            position:absolute; inset:0;
            pointer-events:none;
            border-radius: inherit;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.035);
        }

        /* accessibilité */
        @media (prefers-reduced-motion: reduce){
            .track{ animation: none }
        }
    </style>
</head>
<body>

<!-- BANDE défilante -->
<section class="strip" aria-label="Galerie défilante">
    <div class="marquee">
        <div class="track" id="track">

            <!-- ========= SÉRIE A ========= -->
            <article class="card"><span class="card__inner"><img class="card__img" src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=1600&auto=format&fit=crop" alt="Rayon de fruits et légumes"/></span></article>
            <article class="card"><span class="card__inner"><!-- volontairement vide (carte noire) --></span></article>
            <article class="card"><span class="card__inner"><img class="card__img" src="https://images.unsplash.com/photo-1515165562835-c3b8c2e1f9af?q=80&w=1600&auto=format&fit=crop" alt="Groupe d'amis devant une fresque"/></span></article>
            <article class="card"><span class="card__inner"><!-- vide --></span></article>
            <article class="card"><span class="card__inner"><img class="card__img" src="https://images.unsplash.com/photo-1506806732259-39c2d0268443?q=80&w=1600&auto=format&fit=crop" alt="Rayon légumes"/></span></article>
            <article class="card"><span class="card__inner"><!-- vide --></span></article>

            <!-- ========= SÉRIE B (DUPLIQUÉE) ========= -->
            <article class="card"><span class="card__inner"><img class="card__img" src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=1600&auto=format&fit=crop" alt="Rayon de fruits et légumes"/></span></article>
            <article class="card"><span class="card__inner"><!-- vide --></span></article>
            <article class="card"><span class="card__inner"><img class="card__img" src="https://images.unsplash.com/photo-1515165562835-c3b8c2e1f9af?q=80&w=1600&auto=format&fit=crop" alt="Groupe d'amis devant une fresque"/></span></article>
            <article class="card"><span class="card__inner"><!-- vide --></span></article>
            <article class="card"><span class="card__inner"><img class="card__img" src="https://images.unsplash.com/photo-1506806732259-39c2d0268443?q=80&w=1600&auto=format&fit=crop" alt="Rayon légumes"/></span></article>
            <article class="card"><span class="card__inner"><!-- vide --></span></article>

        </div>
    </div>
</section>

<script>
    /* --------- JS optionnel (réglages rapides) ---------
       - Change la vitesse avec data-speed sur <body data-speed="20s">
       - Pause si l’onglet est masqué (économie CPU)
    ---------------------------------------------------- */
    (function () {
        const root   = document.documentElement;
        const track  = document.getElementById('track');
        const speed  = document.body.dataset.speed;

        if (speed) {
            root.style.setProperty('--speed', speed);
        }

        document.addEventListener('visibilitychange', () => {
            track.style.animationPlayState = (document.hidden) ? 'paused' : 'running';
        }, {passive:true});
    })();
</script>
</body>
</html>
