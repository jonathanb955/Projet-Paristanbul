<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sites de conception — Slider</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --page-bg:#f6f3f3;
            --ink:#5a5a5a;
            --muted:#8d8d8d;
            --btn-border:#cfcfd0;
            --btn-hover:#1a1a1a;
            --card-radius: 6px;
            --t: 560ms;         /* durée des transitions */
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0; background:var(--page-bg);
            font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;
            color:#222;
        }

        .wrap{
            max-width: 1500px;
            margin: 0 auto;
            padding: clamp(20px, 4vw, 56px);
        }

        .hero{
            display:grid;
            grid-template-columns: 1.05fr 1fr;
            align-items:center;
            gap: clamp(24px, 5vw, 80px);
            min-height: 78vh;
        }

        /* ------ Colonne texte ------ */
        .eyebrow{
            letter-spacing:.15em;
            text-transform:uppercase;
            color: var(--muted);
            font-weight:700;
            font-size: clamp(12px, .95vw, 14px);
            margin: 0 0 14px 0;
        }
        .title{
            font-size: clamp(42px, 7vw, 110px);
            line-height: .95;
            color: var(--ink);
            margin: 0 0 28px 0;
            font-weight:800;
        }
        .nav{
            display:flex; gap:14px;
            margin-top: clamp(22px, 4vh, 46px);
        }
        .nav button{
            appearance:none; cursor:pointer;
            width:44px; height:44px; border-radius:999px;
            background:#fff; border:1px solid var(--btn-border);
            display:grid; place-items:center;
            transition: border-color .2s ease, color .2s ease, transform .12s ease;
            color:#333; font-weight:800;
        }
        .nav button:hover{ border-color:#999; color:var(--btn-hover) }
        .nav button:active{ transform: translateY(1px) }
        .nav svg{ width:18px; height:18px; }

        /* ------ Colonne visuelle (pile de cartes) ------ */
        .stack{
            position:relative;
            min-height: clamp(360px, 58vh, 640px);
        }
        .card{
            position:absolute; inset:auto 0 0 auto; /* on posera via transform */
            width: min(52vw, 760px);
            height: 80%;
            border-radius: var(--card-radius);
            overflow:hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.22);
            background:#ddd;
            transform-origin: center center;
            transition: transform var(--t) cubic-bezier(.22,.8,.24,1), opacity var(--t) ease;
        }
        .card img{
            width:100%; height:100%; object-fit:cover; display:block;
        }

        /* positions de base */
        .role-left{
            transform: translate(-18vw, 6vh) scale(.64);
            z-index: 1; opacity: .85;
        }
        .role-center{
            transform: translate(0, 0) scale(1);
            z-index: 3; opacity: 1;
        }
        .role-right{
            transform: translate(14vw, -3vh) scale(.62);
            z-index: 2; opacity: .85;
        }

        /* états en mouvement */
        .shift-next .role-left{   transform: translate(-26vw, 12vh) scale(.56); opacity:0; }
        .shift-next .role-center{ transform: translate(-18vw, 6vh)  scale(.64); }
        .shift-next .role-right{  transform: translate(0,0)       scale(1); }

        .shift-prev .role-right{  transform: translate(24vw, -12vh) scale(.54); opacity:0; }
        .shift-prev .role-center{ transform: translate(14vw, -3vh)  scale(.62); }
        .shift-prev .role-left{   transform: translate(0,0)         scale(1); }

        /* Légende sur la carte centrale */
        .legend{
            position:absolute; left:22px; bottom:22px;
            display:flex; align-items:center; gap:12px;
            color:#fff; font-size: clamp(18px, 2.2vw, 40px); font-weight:800;
            text-shadow: 0 2px 10px rgba(0,0,0,.35), 0 6px 30px rgba(0,0,0,.45);
            pointer-events:none;
        }
        .legend svg{ width: clamp(18px, 2vw, 42px); height:auto; filter: drop-shadow(0 2px 10px rgba(0,0,0,.5)); }

        /* responsive: pile en premier sous 980px */
        @media (max-width: 980px){
            .hero{ grid-template-columns: 1fr; }
            .stack{ order:-1; min-height: 54vh; }
            .card{ width: 88vw; }
            .role-left{  transform: translate(-14vw, 6vh) scale(.7); }
            .role-right{ transform: translate(14vw, -4vh) scale(.68); }
        }
    </style>
</head>
<body>
<section class="wrap">
    <div class="hero">
        <!-- Colonne texte -->
        <div>
            <p class="eyebrow">NOS DIFFERENTS SITES DE CONCEPTION</p>
            <h1 class="title" id="slideTitle">Kipstadium</h1>

            <div class="nav" aria-label="Contrôles du slider">
                <button class="btn-prev" aria-label="Précédent">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
                <button class="btn-next" aria-label="Suivant">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 6l6 6-6 6"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Colonne visuelle -->
        <div class="stack" id="stack" aria-live="polite">
            <!-- 3 cartes recyclées -->
            <figure class="card role-left"><img id="imgLeft"  alt=""></figure>

            <figure class="card role-center">
                <img id="imgCenter" alt="">
                <figcaption class="legend">
                    <!-- petite carte de France (SVG inline) -->
                    <svg viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M25 47l8-9 8 3 6-6 12 1 4-7 7 3 12-2 6 10-3 8 8 5-2 8 6 7-11 4-4 9-10-2-6 6-9-5-10 2-1-11-9-6 5-8-8-10z" fill="white" stroke="white" stroke-width="2" />
                    </svg>
                    <span id="slideCity">Tourcoing, Fr</span>
                </figcaption>
            </figure>

            <figure class="card role-right"><img id="imgRight" alt=""></figure>
        </div>
    </div>
</section>

<script>
    // --- Données slides : remplace les images/titres/lieux par les tiens ---
    const slides = [
        {
            title: "Kipstadium",
            city:  "Tourcoing, Fr",
            img:   "https://images.unsplash.com/photo-1468071174046-657d9d351a40?q=80&w=1400&auto=format&fit=crop"
        },
        {
            title: "Sailing Lab",
            city:  "Saint-Malo, Fr",
            img:   "https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/d9/fa/1b/lost-valley.jpg?w=900&h=500&s=1"
        },
        {
            title: "Design Center",
            city:  "Annecy, Fr",
            img:   "https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=1400&auto=format&fit=crop"
        },
        {
            title: "Mountain Lab",
            city:  "Chamonix, Fr",
            img:   "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1400&auto=format&fit=crop"
        }
    ];

    // --- DOM refs ---
    const stack = document.getElementById('stack');
    const imgL  = document.getElementById('imgLeft');
    const imgC  = document.getElementById('imgCenter');
    const imgR  = document.getElementById('imgRight');
    const title = document.getElementById('slideTitle');
    const city  = document.getElementById('slideCity');
    const prevBtn = document.querySelector('.btn-prev');
    const nextBtn = document.querySelector('.btn-next');

    // --- helpers ---
    let idx = 0;        // index actuel (carte centrale)
    let busy = false;   // évite double-clic pendant l'anim

    function modulo(n, m){ return ((n % m) + m) % m; }

    function setImages(){
        const N = slides.length;
        const prev = slides[modulo(idx-1, N)];
        const curr = slides[modulo(idx,   N)];
        const next = slides[modulo(idx+1, N)];

        imgL.src = prev.img; imgL.alt = prev.title;
        imgC.src = curr.img; imgC.alt = curr.title;
        imgR.src = next.img; imgR.alt = next.title;

        title.textContent = curr.title;
        city.textContent  = curr.city;
    }

    // initial
    setImages();

    function goNext(){
        if(busy) return;
        busy = true;
        stack.classList.add('shift-next');

        // À la fin de l’anim, on “tourne” les rôles des cartes et on met à jour l’index
        setTimeout(()=>{
            idx = modulo(idx+1, slides.length);
            // réinitialise la classe d'anim
            stack.classList.remove('shift-next');

            // rotation des rôles (sans flash) :
            // on échange les classes pour simuler le déplacement final
            const left  = stack.querySelector('.role-left');
            const cent  = stack.querySelector('.role-center');
            const right = stack.querySelector('.role-right');
            left.classList.replace('role-left','role-right');   // sort de gauche -> va à droite
            cent.classList.replace('role-center','role-left');  // centre -> devient gauche
            right.classList.replace('role-right','role-center');// droite -> devient centre

            // Après rotation, on recharge les images (prev/curr/next) dans les bons rôles
            setImages();
            busy = false;
        }, 560); // même durée que --t
    }

    function goPrev(){
        if(busy) return;
        busy = true;
        stack.classList.add('shift-prev');

        setTimeout(()=>{
            idx = modulo(idx-1, slides.length);
            stack.classList.remove('shift-prev');

            const left  = stack.querySelector('.role-left');
            const cent  = stack.querySelector('.role-center');
            const right = stack.querySelector('.role-right');
            right.classList.replace('role-right','role-left');   // droite -> gauche
            cent.classList.replace('role-center','role-right');  // centre -> droite
            left.classList.replace('role-left','role-center');   // gauche -> centre

            setImages();
            busy = false;
        }, 560);
    }

    nextBtn.addEventListener('click', goNext);
    prevBtn.addEventListener('click', goPrev);

    // Accessibilité : flèches clavier
    document.addEventListener('keydown', (e)=>{
        if(e.key === 'ArrowRight') goNext();
        if(e.key === 'ArrowLeft')  goPrev();
    });
</script>
</body>
</html>
