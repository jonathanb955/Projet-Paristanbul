<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Catalogue interactif</title>

    <!-- PageFlip -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/css/page-flip.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

    <style>
        :root{
            --bg:#0b0d12; --panel:#0f1525; --panel-2:#0d1321; --text:#e7ecf5;
            --muted:#8fa0bf; --primary:#3aa0ff; --ring:#3aa0ff55; --edge:#1b2233; --edge-2:#1f2942;
        }
        *{ box-sizing:border-box }
        html,body{ height:100% }
        body{
            margin:0; color:var(--text);
            background:
                    radial-gradient(1200px 600px at 12% -10%, #202b46 0%, transparent 60%),
                    radial-gradient(1200px 600px at 90% 110%, #151e33 0%, transparent 60%),
                    var(--bg);
            font:500 16px/1.45 system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial;
        }

        .app{ min-height:100dvh; display:flex; flex-direction:column }

        .toolbar{
            position:sticky; top:0; z-index:30;
            background:linear-gradient(180deg,#0d1321cc 0%,#0d132199 100%);
            backdrop-filter:blur(10px);
            border-bottom:1px solid #182037;
        }
        .toolbar .row{ max-width:1280px; margin:auto; padding:.65rem 1rem; display:flex; gap:.6rem; align-items:center; flex-wrap:wrap }
        .brand{ font-weight:800; letter-spacing:.2px; margin-right:auto; display:flex; gap:.6rem; align-items:center }
        .brand .dot{ width:.6rem; height:.6rem; border-radius:999px; background:var(--primary); box-shadow:0 0 0 4px #3aa0ff22; }

        .btn{
            appearance:none; border:1px solid var(--edge-2); background:#131a2a; color:var(--text);
            border-radius:999px; padding:.55rem .9rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; font-weight:600;
            box-shadow:0 1px 0 #ffffff10 inset, 0 1px 10px #0004; transition:transform .1s ease, border-color .2s ease, background .2s ease;
        }
        .btn:hover{ border-color:#2a3659; background:#0f1626 }
        .btn:active{ transform:translateY(1px) }
        .btn:focus{ outline:2px solid var(--ring); outline-offset:2px }
        .btn.icon{ padding:.55rem .7rem }
        .sep{ width:1px; height:28px; background:#223052; opacity:.6; margin:0 .25rem }

        .metric{
            margin-left:auto; display:inline-flex; align-items:center; gap:.5rem;
            font-weight:700; color:#cfe0ff; background:#0e1423; border:1px solid #1f2942;
            padding:.45rem .75rem; border-radius:.75rem; box-shadow:0 1px 0 #ffffff0d inset;
        }
        .metric small{ color:var(--muted); font-weight:600 }

        .wrap{ display:grid; grid-template-rows:1fr; gap:1rem; max-width:1280px; width:100%; margin:1rem auto; padding:0 1rem }
        .stage{
            position:relative; border:1px solid var(--edge); background:var(--panel); border-radius:18px;
            box-shadow:0 24px 60px #0009, inset 0 1px 0 #ffffff12, inset 0 0 0 1px #0008;
            display:grid; place-items:center; overflow:hidden;
        }
        .stage::after{ content:""; position:absolute; inset:0; pointer-events:none; background:radial-gradient(1400px 700px at 50% -10%, transparent 0%, #00000022 60%, #00000055 100%) }

        .stage-inner{ position:relative; transform-origin:50% 50%; transition:transform .15s ease; overflow:hidden; border-radius:14px }

        /* hauteur contrôlée (uniquement la hauteur) */
        #flipbook{ width:min(92vw,1040px); height:88vh }
        /* En plein écran, on passe par une classe pour éviter les soucis de :fullscreen */
        body.is-fs #flipbook{ height:96vh }

        .cover-gap{
            position:absolute; left:0; top:0; width:0; height:100%; background:var(--panel);
            border-top-left-radius:14px; border-bottom-left-radius:14px; pointer-events:none; z-index:2; display:none;
            box-shadow:inset -20px 0 40px #00000055;
        }

        .icon svg{ width:18px; height:18px; display:block }

        @media (max-width:768px){
            #flipbook{ height:92dvh }
            .metric{ display:none }
        }
    </style>
</head>
<body>
<div class="app">
    <div class="toolbar">
        <div class="row">
            <div class="brand"><span class="dot"></span> Catalogue interactif</div>

            <button id="prevBtn" class="btn icon" title="Page précédente">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button id="nextBtn" class="btn icon" title="Page suivante">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
            </button>

            <span class="sep"></span>

            <button id="zoomOut" class="btn icon" title="Zoom -"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/></svg></button>
            <button id="zoomIn" class="btn icon" title="Zoom +"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg></button>
            <button id="fitBtn" class="btn" title="Ajuster">Ajuster</button>

            <span class="sep"></span>

            <button id="fsBtn" class="btn" title="Plein écran">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:.25rem">
                    <path d="M8 3H5a2 2 0 0 0-2 2v3M16 3h3a2 2 0 0 1 2 2v3M8 21H5a2 2 0 0 1-2-2v-3M16 21h3a2 2 0 0 0 2-2v-3"/>
                </svg> Plein écran
            </button>

            <div class="metric"><small>Page</small> <span id="pageLabel">1 / 5</span></div>
        </div>
    </div>

    <div class="wrap">
        <div class="stage" id="stageBox">
            <div id="stageInner" class="stage-inner">
                <div id="coverGap" class="cover-gap" aria-hidden="true"></div>
                <div id="flipbook"></div>
            </div>
        </div>
    </div>
</div>

<script>
    /* ===== paramètres ===== */
    const TOTAL_PAGES = 5;
    const PATH = '/Projet-paristanbul/assets/pages';
    const FILENAME = i => String(i).padStart(2,'0') + '.jpg';
    const MOBILE_BREAKPOINT = 768;
    const MIN_W = 480, MAX_W = 1040;

    const pages = Array.from({length: TOTAL_PAGES}, (_,k) => `${PATH}/${FILENAME(k+1)}`);
    pages.forEach(src => { const i = new Image(); i.src = src; });

    let pageFlip, pageAspect = 0.707, pageW = 600, scale = 1, baseScale = 1;

    const stageBox  = document.getElementById('stageBox');
    const stageInner= document.getElementById('stageInner');
    const flipEl    = document.getElementById('flipbook');
    const coverGap  = document.getElementById('coverGap');
    const pageLabel = document.getElementById('pageLabel');

    function applyScale(){ stageInner.style.transform = `scale(${scale})`; }
    function updateMetric(){ if(pageFlip){ const i = pageFlip.getCurrentPageIndex(); pageLabel.textContent = `${i+1} / ${TOTAL_PAGES}`; } }

    async function detectAspect(){
        return new Promise(resolve=>{
            const probe = new Image();
            probe.onload = () => { if (probe.naturalWidth && probe.naturalHeight) pageAspect = probe.naturalWidth / probe.naturalHeight; resolve(); };
            probe.onerror = () => resolve();
            probe.src = pages[0];
        });
    }

    function computeSize(){
        const usePortrait = window.innerWidth < MOBILE_BREAKPOINT;
        const isFS = !!document.fullscreenElement;
        const hFactor = isFS ? 0.96 : 0.88;           // plein écran plus haut
        const height = Math.floor(window.innerHeight * hFactor);
        let width = Math.round(height * pageAspect);
        width = Math.min(MAX_W, Math.max(MIN_W, width));
        return { width, height, usePortrait };
    }

    function coverMaskAndCenter(){
        const idx = pageFlip.getCurrentPageIndex();
        const isDouble = !pageFlip.getSettings().usePortrait;
        const isFirst  = (idx === 0);
        const isLastSingle = (TOTAL_PAGES % 2 === 0) && (idx === TOTAL_PAGES - 1);

        flipEl.style.clipPath = 'inset(0 0 0 0)';
        flipEl.style.webkitClipPath = 'inset(0 0 0 0)';
        stageInner.style.transform = `scale(${scale})`;
        coverGap.style.display = 'none';

        if (isDouble && isFirst) {
            flipEl.style.clipPath = 'inset(0 0 0 50%)';
            flipEl.style.webkitClipPath = 'inset(0 0 0 50%)';
            stageInner.style.transform = `translateX(${-pageW/2}px) scale(${scale})`;
            coverGap.style.display = 'block';
        } else if (isDouble && isLastSingle) {
            flipEl.style.clipPath = 'inset(0 50% 0 0)';
            flipEl.style.webkitClipPath = 'inset(0 50% 0 0)';
            stageInner.style.transform = `translateX(${pageW/2}px) scale(${scale})`;
        }
        updateMetric();
    }

    async function initFlip(startIndex=0){
        await detectAspect();
        const { width, height, usePortrait } = computeSize();
        pageW = width;

        coverGap.style.width  = pageW + 'px';
        coverGap.style.height = height + 'px';
        coverGap.style.background = getComputedStyle(stageBox).backgroundColor;

        if(pageFlip){ pageFlip.destroy(); }

        pageFlip = new St.PageFlip(flipEl, {
            width, height, size:'fixed',
            showCover:true, usePortrait,
            autoSize:true, maxShadowOpacity:0.5, mobileScrollSupport:true,
            startPage:startIndex
        });

        pageFlip.loadFromImages(pages);
        pageFlip.on('flip', coverMaskAndCenter);

        scale = baseScale = 1;
        applyScale();
        coverMaskAndCenter();
    }

    // === Contrôles ===
    document.getElementById('nextBtn').onclick = ()=> pageFlip?.flipNext();
    document.getElementById('prevBtn').onclick = ()=> pageFlip?.flipPrev();
    document.getElementById('zoomIn').onclick  = ()=>{ scale = Math.min(2.5, scale + 0.1); applyScale(); coverMaskAndCenter(); };
    document.getElementById('zoomOut').onclick = ()=>{ scale = Math.max(0.6, scale - 0.1); applyScale(); coverMaskAndCenter(); };
    document.getElementById('fitBtn').onclick  = ()=>{ scale = baseScale = 1; applyScale(); coverMaskAndCenter(); };

    // Plein écran (on met la SCÈNE en FS)
    function enterFS(el){
        return (el.requestFullscreen?.() || el.webkitRequestFullscreen?.() || el.msRequestFullscreen?.() || el.mozRequestFullScreen?.());
    }
    function exitFS(){
        return (document.exitFullscreen?.() || document.webkitExitFullscreen?.() || document.msExitFullscreen?.() || document.mozCancelFullScreen?.());
    }
    document.getElementById('fsBtn').onclick = ()=> {
        if(!document.fullscreenElement){ enterFS(stageBox); }
        else { exitFS(); }
    };

    // Recalibrage robuste à l’entrée/sortie plein écran
    function relayoutAfterFS(){
        // toggler la classe pour la hauteur CSS
        document.body.classList.toggle('is-fs', !!document.fullscreenElement);
        const current = pageFlip ? pageFlip.getCurrentPageIndex() : 0;
        // attendre que le layout FS soit effectif
        requestAnimationFrame(()=> {
            requestAnimationFrame(()=> { initFlip(current); });
        });
    }
    document.addEventListener('fullscreenchange', relayoutAfterFS);
    document.addEventListener('webkitfullscreenchange', relayoutAfterFS);
    document.addEventListener('mozfullscreenchange', relayoutAfterFS);
    document.addEventListener('MSFullscreenChange', relayoutAfterFS);

    // Resize normal (conserve la page)
    let rt;
    window.addEventListener('resize', ()=>{
        clearTimeout(rt);
        rt = setTimeout(()=>{
            const current = pageFlip ? pageFlip.getCurrentPageIndex() : 0;
            initFlip(current);
        }, 150);
    });

    window.addEventListener('load', ()=> initFlip(0));
</script>
</body>
</html>
