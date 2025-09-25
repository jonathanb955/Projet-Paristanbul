<!--
Catalogue interactif (flipbook) — Page unique centrée
- Pas de page blanche au début (showCover=false)
- Mode page UNIQUE forcé (usePortrait toujours vrai)
- Animation propre (séquentiel 1 → 2 → 3…)
- Hauteur légèrement augmentée
-->

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Catalogue interactif</title>

    <!-- PageFlip (ex-StPageFlip) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

    <style>
        :root{ --bg:#0b0d12; --panel:#121620; --muted:#7f8aa3; --text:#e7ecf5; --primary:#3aa0ff; --ring:#3aa0ff55; }
        *{ box-sizing:border-box }
        html,body{ height:100% }
        body{
            margin:0; color:var(--text);
            background:radial-gradient(1200px 600px at 10% -10%, #182033 0%, #0b0d12 55%) fixed, var(--bg);
            font:500 16px/1.4 system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial;
        }

        .app{ min-height:100dvh; display:flex; flex-direction:column }
        .toolbar{ position:sticky; top:0; z-index:20; background:linear-gradient(180deg,#0d1321cc 0%,#0d132199 100%); backdrop-filter:blur(8px); border-bottom:1px solid #1b2233 }
        .toolbar .row{ max-width:1200px; margin:auto; display:flex; gap:.5rem; align-items:center; padding:.5rem 1rem; flex-wrap:wrap }
        .toolbar .title{ font-weight:700; letter-spacing:.2px; margin-right:auto; display:flex; align-items:center; gap:.5rem }
        .toolbar button{ appearance:none; border:1px solid #1f2942; background:#121829; color:var(--text); border-radius:.75rem; padding:.55rem .9rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem }
        .toolbar button:focus{ outline:2px solid var(--ring); outline-offset:1px }
        .toolbar button:hover{ border-color:#2a3659; background:#0f1626 }

        .wrap{ display:grid; grid-template-rows:auto 1fr auto; gap:1rem; max-width:1200px; margin:1rem auto; padding:0 1rem; width:100% }
        .stage{ position:relative; border:1px solid #1b2233; background:#0f1525; border-radius:1rem; box-shadow:0 10px 30px #00000055, inset 0 1px 0 #ffffff0a; display:grid; place-items:center; min-height:65vh }
        .stage-inner{ position:relative; transform-origin:50% 50%; transition:transform .15s ease }
        /* Hauteur un peu augmentée */
        #flipbook{ width:min(92vw,1000px); height:78vh }

        .thumbs{ display:flex; gap:.5rem; overflow:auto; padding:.5rem; scrollbar-width:thin; border:1px solid #1b2233; border-radius:1rem; background:#0e1423 }
        .thumb{ flex:0 0 auto; width:90px; aspect-ratio:3/4; border-radius:.5rem; overflow:hidden; position:relative; border:1px solid #202942; cursor:pointer; opacity:.85 }
        .thumb img{ width:100%; height:100%; object-fit:cover; display:block }
        .thumb.active{ outline:2px solid var(--primary); opacity:1 }

        .kbd{ font:600 12px/1 ui-monospace, Menlo, Consolas; background:#0a1020; border:1px solid #202942; padding:.15rem .35rem; border-radius:.35rem; color:#9fb4d7 }

        @media (max-width: 768px){
            #flipbook{ height:82dvh } /* un chouïa plus haut sur mobile aussi */
            .thumb{ width:72px }
        }
    </style>
</head>
<body>
<div class="app">
    <div class="toolbar">
        <div class="row">
            <div class="title">📖 Catalogue interactif <span style="opacity:.6;font-weight:600;font-size:.9em">(page unique centrée)</span></div>
            <button id="prevBtn" title="Page précédente">⟨ Précédent</button>
            <button id="nextBtn" title="Page suivante">Suivant ⟩</button>
            <button id="zoomOut" title="Zoom -">−</button>
            <button id="zoomIn" title="Zoom +">+</button>
            <button id="fitBtn" title="Adapter à l’écran">Ajuster</button>
            <button id="fsBtn" title="Plein écran">⛶ Plein écran</button>
            <span style="opacity:.75;margin-left:.5rem">Astuces : flèches ⬅︎ ➜, <span class="kbd">Esc</span> pour quitter le plein écran</span>
        </div>
    </div>

    <div class="wrap">
        <div class="stage">
            <div id="stageInner" class="stage-inner">
                <div id="flipbook"></div>
            </div>
        </div>

        <div class="thumbs" id="thumbs"></div>
    </div>
</div>

<script>
    // === PARAMÈTRES À ADAPTER ===
    const TOTAL_PAGES = 5; // <-- mets le nombre réel: 01.jpg..0N.jpg
    const PATH = '/Projet-paristanbul/assets/pages'; // confirmé
    const FILENAME = i => String(i).padStart(2,'0') + '.jpg';
    const SHOW_COVER = false;        // <-- IMPORTANT: pas de page blanche/cover
    const FORCE_SINGLE = true;       // <-- page unique tout le temps
    const MIN_W = 480, MAX_W = 1000; // bornes largeur d'une page

    const pages = Array.from({length: TOTAL_PAGES}, (_,k) => `${PATH}/${FILENAME(k+1)}`);

    let pageFlip, scale = 1, baseScale = 1, pageAspect = 0.707;

    // Préchargement léger
    pages.forEach(src => { const img = new Image(); img.src = src; });

    function buildThumbs(){
        const wrap = document.getElementById('thumbs');
        wrap.innerHTML = '';
        pages.forEach((src, idx) => {
            const t = document.createElement('div');
            t.className = 'thumb';
            t.title = 'Page ' + (idx+1);
            const img = document.createElement('img'); img.loading='lazy'; img.src = src;
            t.appendChild(img);
            t.addEventListener('click', () => pageFlip.flip(idx));
            wrap.appendChild(t);
        });
    }

    function highlightThumb(idx){
        document.querySelectorAll('.thumb').forEach((el,i)=>el.classList.toggle('active', i===idx));
    }

    const stageInner = document.getElementById('stageInner');
    function applyScale(){ stageInner.style.transform = `scale(${scale})`; }

    // Boutons
    document.getElementById('prevBtn').onclick = () => { if (pageFlip) pageFlip.flipPrev(); };
    document.getElementById('nextBtn').onclick = () => { if (pageFlip) pageFlip.flipNext(); };
    document.getElementById('zoomIn').onclick  = () => { scale = Math.min(2.5, scale + 0.1); applyScale(); };
    document.getElementById('zoomOut').onclick = () => { scale = Math.max(0.6, scale - 0.1); applyScale(); };
    document.getElementById('fitBtn').onclick  = () => { scale = baseScale; applyScale(); };
    document.getElementById('fsBtn').onclick   = () => {
        const box = document.querySelector('.stage');
        if(!document.fullscreenElement) box.requestFullscreen?.(); else document.exitFullscreen?.();
    };

    // Clavier
    window.addEventListener('keydown', (e)=>{
        if(e.key==='ArrowRight') { if (pageFlip) pageFlip.flipNext(); }
        if(e.key==='ArrowLeft')  { if (pageFlip) pageFlip.flipPrev(); }
        if((e.ctrlKey||e.metaKey) && e.key==='+'){ e.preventDefault(); document.getElementById('zoomIn').click(); }
        if((e.ctrlKey||e.metaKey) && (e.key==='-'||e.key==='–')){ e.preventDefault(); document.getElementById('zoomOut').click(); }
        if(e.key==='0' && (e.ctrlKey||e.metaKey)){ e.preventDefault(); document.getElementById('fitBtn').click(); }
    });

    // Taille d'une page selon ratio détecté
    function computeSize(){
        const height = Math.floor(window.innerHeight * 0.78);   // hauteur = 78% viewport
        let width  = Math.round(height * pageAspect);           // largeur d'UNE page
        width = Math.min(MAX_W, Math.max(MIN_W, width));        // bornes
        return { width, height, usePortrait: true };            // portrait forcé
    }

    // Détecte le ratio W/H de la 1ère image
    async function detectAspect(){
        return new Promise(resolve=>{
            const probe = new Image();
            probe.onload = () => {
                if (probe.naturalWidth && probe.naturalHeight) {
                    pageAspect = probe.naturalWidth / probe.naturalHeight;
                }
                resolve();
            };
            probe.onerror = () => resolve();
            probe.src = pages[0];
        });
    }

    async function initFlip(){
        await detectAspect();
        const el = document.getElementById('flipbook');
        const { width, height, usePortrait } = computeSize();

        if(pageFlip){ pageFlip.destroy(); }

        pageFlip = new St.PageFlip(el, {
            width,            // taille d'UNE page
            height,           // taille d'UNE page
            size: 'fixed',
            showCover: SHOW_COVER,       // false => pas de page "couverture" à droite
            usePortrait: FORCE_SINGLE ? true : usePortrait, // portrait forcé
            autoSize: true,
            maxShadowOpacity: 0.5,
            mobileScrollSupport: true,
            startPage: 0
        });

        pageFlip.loadFromImages(pages);
        pageFlip.on('flip', e => highlightThumb(e.data));

        buildThumbs();
        highlightThumb(pageFlip.getCurrentPageIndex());

        scale = baseScale = 1;
        applyScale();
    }

    // Recalibrage au redimensionnement
    let rt;
    window.addEventListener('resize', ()=>{ clearTimeout(rt); rt = setTimeout(initFlip, 200); });
    window.addEventListener('load', initFlip);
</script>
</body>
</html>
