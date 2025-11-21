// Gestion du toast
function initToast() {
    const toast = document.getElementById('toast');
    if (toast) {
        setTimeout(() => {
            toast.style.transition = 'opacity .35s ease, transform .35s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-6px)';
            setTimeout(() => toast.remove(), 380);
        }, 2200);
    }
}

// Fonction utilitaire pour afficher des toasts
function showToast(msg, ok = true) {
    const t = document.createElement('div');
    t.style.cssText = `
        position: fixed;
        right: 16px;
        top: 16px;
        z-index: 9999;
        padding: 10px 14px;
        border-radius: 10px;
        font-weight: 700;
        border: 1px solid;
        box-shadow: 0 10px 30px rgba(0,0,0,.25);
        color: #fff;
    `;
    t.style.background = ok ? 'rgba(16,185,129,.95)' : 'rgba(220,38,38,.95)';
    t.style.borderColor = ok ? 'rgba(16,185,129,.4)' : 'rgba(220,38,38,.4)';
    t.textContent = msg || (ok ? "Opération réussie" : "Une erreur est survenue");
    document.body.appendChild(t);
    
    setTimeout(() => {
        t.style.transition = 'opacity .35s, transform .35s';
        t.style.opacity = '0';
        t.style.transform = 'translateY(-6px)';
        setTimeout(() => t.remove(), 380);
    }, 2200);
}

// Fonction pour l'inscription à la newsletter
async function subscribeNewsletter(e, form) {
    e.preventDefault();
    try {
        const res = await fetch(form.action, { 
            method: 'POST', 
            body: new FormData(form) 
        });
        const json = await res.json().catch(() => ({ ok: false, msg: 'Réponse invalide' }));
        showToast(json.msg || (json.ok ? "Inscription validée" : "Erreur"), !!json.ok);
        if (json.ok) form.reset();
    } catch (error) {
        showToast("Impossible de joindre le service.", false);
    }
    return false;
}

// Utilitaires DOM
const $ = (sel, el = document) => el.querySelector(sel);
const $$ = (sel, el = document) => [...el.querySelectorAll(sel)];

// Initialisation des animations au chargement
document.addEventListener('DOMContentLoaded', () => {
    initToast();
    
    // Afficher directement les sections sans animation
    $$('.section-hd, .catalog-app, .pi-sites, #advantages .carousel, #stores, #contact .contact-panel, footer .wrap').forEach(n => {
        n.style.opacity = '1';
        n.style.transform = 'none';
    });
    
    // Initialiser l'effet de parallaxe
    initParallax();
    
    // Initialiser le catalogue
    initCatalog();
    
    // Initialiser le carrousel des magasins
    initStoresCarousel();
    
    // Initialiser la carte des magasins
    initStoresMap();
});

// Effet de parallaxe sur les éléments avec data-parallax
function initParallax() {
    const parallaxNodes = $$('[data-parallax]');
    
    function onScrollParallax() {
        const y = window.scrollY || document.documentElement.scrollTop;
        parallaxNodes.forEach(n => {
            const speed = parseFloat(n.dataset.speed || '0.05');
            n.style.transform = `translateY(${y * speed}px)`;
        });
    }
    
    onScrollParallax();
    window.addEventListener('scroll', onScrollParallax, { passive: true });
}

// Initialisation du catalogue
function initCatalog() {
    const flipEl = document.getElementById('flipbook');
    if (!flipEl) return;
    
    // Configuration du catalogue
    const PATH = '/Projet-Paristanbul/assets/pages';
    const FILENAME = i => String(i).padStart(2, '0') + '.jpg';
    const PAGES_ORDER = [1, 3, 4, 5, 6, 7];
    const BUST = `?v=${Date.now()}`;
    const pages = PAGES_ORDER.map(n => `${PATH}/${FILENAME(n)}${BUST}`);
    const TOTAL_PAGES = pages.length;
    
    // Précharger les images
    pages.forEach(src => { const img = new Image(); img.src = src; });
    
    const stageInner = document.getElementById('stageInner');
    const pageLabel = document.getElementById('pageLabel');
    
    let pageFlip;
    let pageAspect = 0.707;
    let pageW = 600;
    let scale = 1;
    let baseScale = 1;
    
    // Fonctions pour gérer le catalogue
    async function detectAspect() {
        return new Promise(resolve => {
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
    
    function computeSize() {
        const MOBILE_BREAKPOINT = 768;
        const MIN_W = 480;
        const MAX_W = 1040;
        const usePortrait = window.innerWidth < MOBILE_BREAKPOINT;
        const height = Math.floor(window.innerHeight * 0.88);
        let width = Math.round(height * pageAspect);
        width = Math.min(MAX_W, Math.max(MIN_W, width));
        return { width, height, usePortrait };
    }
    
    function updateMetric() {
        if (!pageFlip) return;
        const i = pageFlip.getCurrentPageIndex();
        pageLabel.textContent = `${i + 1} / ${TOTAL_PAGES}`;
    }
    
    function coverMaskAndCenter() {
        const idx = pageFlip.getCurrentPageIndex();
        const isDouble = !pageFlip.getSettings().usePortrait;
        
        flipEl.style.clipPath = 'none';
        flipEl.style.webkitClipPath = 'none';
        stageInner.style.transform = `scale(${scale})`;
        
        if (isDouble && idx === 0) {
            flipEl.style.clipPath = 'inset(0 0 0 50%)';
            flipEl.style.webkitClipPath = 'inset(0 0 0 50%)';
            stageInner.style.transform = `translateX(${-pageW/2}px) scale(${scale})`;
        } else if (isDouble && (TOTAL_PAGES % 2 === 0) && idx === TOTAL_PAGES - 1) {
            flipEl.style.clipPath = 'inset(0 50% 0 0)';
            flipEl.style.webkitClipPath = 'inset(0 50% 0 0)';
            stageInner.style.transform = `translateX(${pageW/2}px) scale(${scale})`;
        }
        
        updateMetric();
    }
    
    async function initFlip(startIndex = 0) {
        await detectAspect();
        const { width, height, usePortrait } = computeSize();
        pageW = width;
        
        if (pageFlip) { 
            pageFlip.destroy(); 
        }
        
        pageFlip = new St.PageFlip(flipEl, {
            width,
            height,
            size: 'fixed',
            showCover: true,
            usePortrait,
            autoSize: true,
            maxShadowOpacity: 0.5,
            mobileScrollSupport: true,
            startPage: startIndex
        });
        
        pageFlip.loadFromImages(pages);
        pageFlip.on('flip', coverMaskAndCenter);
        
        scale = baseScale;
        coverMaskAndCenter();
    }
    
    // Gestion des boutons du catalogue
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');
    const fitBtn = document.getElementById('fitBtn');
    
    if (prevBtn) prevBtn.addEventListener('click', () => pageFlip?.flipPrev());
    if (nextBtn) nextBtn.addEventListener('click', () => pageFlip?.flipNext());
    
    if (zoomIn) {
        zoomIn.addEventListener('click', () => {
            scale = Math.min(2.0, scale + 0.1);
            coverMaskAndCenter();
        });
    }
    
    if (zoomOut) {
        zoomOut.addEventListener('click', () => {
            scale = Math.max(0.6, scale - 0.1);
            coverMaskAndCenter();
        });
    }
    
    if (fitBtn) {
        fitBtn.addEventListener('click', () => {
            scale = baseScale;
            coverMaskAndCenter();
        });
    }
    
    // Gestion du redimensionnement
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const current = pageFlip ? pageFlip.getCurrentPageIndex() : 0;
            initFlip(current);
        }, 150);
    });
    
    // Initialisation du catalogue
    if (document.readyState !== 'loading') {
        initFlip(0);
    } else {
        window.addEventListener('load', () => initFlip(0));
    }
}

// Initialisation du carrousel des magasins
function initStoresCarousel() {
    const vp = document.querySelector('#advantages .track-viewport');
    const track = document.getElementById('adv-track');
    const prev = document.getElementById('adv-prev');
    const next = document.getElementById('adv-next');
    
    if (!vp || !track) return;
    
    const GAP = 16;
    let index = 0;
    let startIndex = 0;
    let originals = [...track.children];
    let autoplay = null;
    
    function cardW() { 
        return originals[0]?.getBoundingClientRect().width || 300; 
    }
    
    function visibleCount() {
        const w = vp.getBoundingClientRect().width;
        return Math.max(1, Math.floor((w + GAP) / (cardW() + GAP)));
    }
    
    function clearClones() {
        [...track.children].forEach(n => {
            if (n.dataset && n.dataset.clone) n.remove();
        });
    }
    
    function cloneNode(n) {
        const c = n.cloneNode(true);
        c.dataset.clone = '1';
        return c;
    }
    
    function instantTranslate() {
        const t = track.style.transition;
        track.style.transition = 'none';
        translate();
        track.offsetHeight; // Force reflow
        track.style.transition = t || 'transform .45s cubic-bezier(.22,.84,.3,1)';
    }
    
    function setupClones() {
        clearClones();
        originals = [...track.children].filter(el => !el.dataset.clone);
        const V = visibleCount();
        const head = originals.slice(-V).map(cloneNode);
        head.forEach(n => track.insertBefore(n, track.firstChild));
        const tail = originals.slice(0, V).map(cloneNode);
        tail.forEach(n => track.appendChild(n));
        startIndex = V;
        index = startIndex;
        instantTranslate();
    }
    
    function translate() {
        const x = -(index * (cardW() + GAP));
        track.style.transform = `translateX(${x}px)`;
    }
    
    function goNext() {
        index++;
        translate();
    }
    
    function goPrev() {
        index--;
        translate();
    }
    
    // Gestion des événements
    track.addEventListener('transitionend', () => {
        const V = startIndex;
        const total = originals.length;
        const tailStart = V + total;
        if (index >= tailStart) {
            index -= total;
            instantTranslate();
        } else if (index < V) {
            index += total;
            instantTranslate();
        }
    });
    
    if (prev) prev.addEventListener('click', e => { e.stopPropagation(); goPrev(); });
    if (next) next.addEventListener('click', e => { e.stopPropagation(); goNext(); });
    
    // Gestion du glisser-déposer
    let dragging = false, downX = 0, base = 0;
    
    function onDown(e) {
        dragging = true;
        downX = (e.touches ? e.touches[0].clientX : e.clientX);
        const m = track.style.transform.match(/-?\d+(\.\d+)?/);
        base = m ? parseFloat(m[0]) : -(index * (cardW() + GAP));
        track.style.transition = 'none';
    }
    
    function onMove(e) {
        if (!dragging) return;
        const cur = (e.touches ? e.touches[0].clientX : e.clientX);
        const dx = cur - downX;
        track.style.transform = `translateX(${base + dx}px)`;
    }
    
    function onUp() {
        if (!dragging) return;
        dragging = false;
        track.style.transition = '';
        const m = track.style.transform.match(/-?\d+(\.\d+)?/);
        const pos = m ? parseFloat(m[0]) : 0;
        const w = cardW() + GAP;
        index = Math.round(-pos / w);
        translate();
    }
    
    vp.addEventListener('mousedown', onDown);
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onUp);
    vp.addEventListener('touchstart', onDown, { passive: true });
    vp.addEventListener('touchmove', onMove, { passive: true });
    vp.addEventListener('touchend', onUp);
    
    // Autoplay
    function startAuto() {
        stopAuto();
        autoplay = setInterval(goNext, 4000);
    }
    
    function stopAuto() {
        if (autoplay) {
            clearInterval(autoplay);
            autoplay = null;
        }
    }
    
    startAuto();
    vp.addEventListener('mouseenter', stopAuto);
    vp.addEventListener('mouseleave', startAuto);
    document.addEventListener('visibilitychange', () => { 
        document.hidden ? stopAuto() : startAuto(); 
    });
    
    // Initialisation
    setupClones();
    window.addEventListener('resize', setupClones);
}

// Données des magasins
const storesData = {
    villiers1: {
        title: 'Paristanbul VILLIERS-LE-BEL',
        image: '/Projet-Paristanbul/assets/img/magasins/villiers1.jpg',
        address: '3 avenue des entrepreneurs, VILLIERS-LE-BEL',
        hours: 'Lundi à Dimanche : 08:30-20:00',
        phone: '+33 7 49 82 61 33',
        coordinates: [49.0010, 2.3894]
    },
    drancy: {
        title: 'Paristanbul DRANCY',
        image: '/Projet-Paristanbul/assets/img/magasins/drancy.jpg',
        address: '83 avenue Marceau, DRANCY',
        hours: 'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00',
        phone: '+33 7 49 82 61 33',
        coordinates: [48.9242, 2.4456]
    },
    bondy: {
        title: 'Paristanbul BONDY',
        image: '/Projet-Paristanbul/assets/img/magasins/bondy.jpg',
        address: '116 Av. Gallieni, BONDY',
        hours: 'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00',
        phone: '+33 7 49 82 61 33',
        coordinates: [48.9024, 2.4823]
    },
    villemomble: {
        title: 'Paristanbul VILLEMOMBLE',
        image: '/Projet-Paristanbul/assets/img/magasins/villemomble.jpg',
        address: '68 Allée du Plateau, VILLEMOMBLE',
        hours: 'Lundi à Dimanche : 08:00-20:30',
        phone: '+33 7 49 82 61 33',
        coordinates: [48.8844, 2.5103]
    },
    nogent: {
        title: 'Paristanbul NOGENT-SUR-OISE',
        image: '/Projet-Paristanbul/assets/img/magasins/nogent.jpg',
        address: '171 Rue Jean Monnet, NOGENT-SUR-OISE',
        hours: 'Lundi à Samedi : 09:30-20:00, Dimanche : 10:00-19:00',
        phone: '+33 7 49 82 61 33',
        coordinates: [49.2765, 2.2011]
    },
    vertsaintdenis: {
        title: 'Paristanbul VERT-SAINT-DENIS',
        image: '/Projet-Paristanbul/assets/img/magasins/vertsaintdenis.jpg',
        address: 'La Fontaine Ronde, VERT-SAINT-DENIS',
        hours: 'Lundi à Dimanche : 08:30-20:30',
        phone: '+33 7 49 82 61 33',
        coordinates: [48.6478, 2.6223]
    }
};

// Initialisation de la carte des magasins
function initStoresMap() {
    const mapEl = document.getElementById('map');
    if (!mapEl || !window.L) return; // Vérifier si Leaflet est chargé
    
    // Créer la carte centrée sur Paris
    const map = L.map('map', {
        center: [48.8566, 2.3522],
        zoom: 10,
        zoomControl: false,
        attributionControl: false
    });
    
    // Ajouter une couche de tuiles (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Ajouter les marqueurs pour chaque magasin
    Object.entries(storesData).forEach(([id, store]) => {
        const marker = L.marker(store.coordinates).addTo(map);
        
        // Personnaliser l'icône du marqueur
        marker.setIcon(
            L.divIcon({
                html: `
                    <div class="store-marker" style="
                        background: #A32929;
                        width: 16px;
                        height: 16px;
                        border-radius: 50%;
                        border: 2px solid #fff;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                        position: relative;
                    ">
                        <div style="
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            width: 8px;
                            height: 8px;
                            background: #fff;
                            border-radius: 50%;
                        "></div>
                    </div>
                `,
                className: 'store-marker',
                iconSize: [20, 20],
                iconAnchor: [10, 10],
                popupAnchor: [0, -10]
            })
        );
        
        // Ajouter une infobulle au clic sur le marqueur
        marker.bindPopup(`
            <div style="min-width: 200px;">
                <h4 style="margin: 0 0 8px 0; color: #A32929; font-weight: 700;">${store.title}</h4>
                <p style="margin: 0 0 8px 0; font-size: 0.9em;">
                    <i class="fas fa-map-marker-alt" style="color: #A32929; width: 16px; text-align: center;"></i> 
                    ${store.address}
                </p>
                <p style="margin: 0 0 8px 0; font-size: 0.9em;">
                    <i class="far fa-clock" style="color: #A32929; width: 16px; text-align: center;"></i> 
                    ${store.hours}
                </p>
                <p style="margin: 0; font-size: 0.9em;">
                    <i class="fas fa-phone" style="color: #A32929; width: 16px; text-align: center;"></i> 
                    <a href="tel:${store.phone.replace(/\s+/g, '')}" style="color: #2E4C97; text-decoration: none;">
                        ${store.phone}
                    </a>
                </p>
            </div>
        `);
    });
    
    // Ajuster la vue pour afficher tous les marqueurs
    const group = new L.featureGroup(Object.values(storesData).map(store => L.marker(store.coordinates)));
    map.fitBounds(group.getBounds().pad(0.1));
    
    return map;
}

// Exposer les fonctions globales
window.showToast = showToast;
window.subscribeNewsletter = subscribeNewsletter;

// Slides data for the carousel
const slides = [
    {
        title: "Boucherie sélection",
        desc: "Viandes fraîches et savoureuses, sélectionnées avec soin",
        img: "/Projet-Paristanbul/assets/img/rayons/boucherie.jpg"
    },
    {
        title: "Épicerie du monde",
        desc: "Découvrez nos produits d'épicerie du monde entier",
        img: "/Projet-Paristanbul/assets/img/rayons/epicerie.jpg"
    },
    {
        title: "Fruits & Légumes",
        desc: "Des produits frais de saison pour une alimentation saine",
        img: "/Projet-Paristanbul/assets/img/rayons/fruits-legumes.jpg"
    },
    {
        title: "Boulangerie & Pâtisserie",
        desc: "Du pain frais et des pâtisseries faites maison",
        img: "/Projet-Paristanbul/assets/img/rayons/boulangerie.jpg"
    },
    {
        title: "Produits Laitiers & Œufs",
        desc: "Une large sélection de produits laitiers frais",
        img: "/Projet-Paristanbul/assets/img/rayons/laitages.jpg"
    }
];

// Initialize slides
function initSlides() {
    const container = document.querySelector('.pi-sites');
    if (!container) return;

    // Create slides HTML
    container.innerHTML = `
        <div class="slides-container">
            ${slides.map((slide, index) => `
                <div class="slide ${index === 0 ? 'active' : ''}" data-index="${index}">
                    <img src="${slide.img}" alt="${slide.title}" class="slide-img">
                    <div class="slide-content">
                        <h3>${slide.title}</h3>
                        <p>${slide.desc}</p>
                    </div>
                </div>
            `).join('')}
        </div>
        <div class="slide-nav">
            ${slides.map((_, index) => `
                <button class="slide-dot ${index === 0 ? 'active' : ''}" data-index="${index}" aria-label="Aller au slide ${index + 1}"></button>
            `).join('')}
        </div>
    `;

    // Add event listeners for slide navigation
    const dots = container.querySelectorAll('.slide-dot');
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.dataset.index);
            goToSlide(index);
        });
    });

    // Auto-play slides
    startAutoPlay();
}

// Go to specific slide
function goToSlide(index) {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slide-dot');
    
    // Update active slide
    slides.forEach((slide, i) => {
        if (i === index) {
            slide.classList.add('active');
        } else {
            slide.classList.remove('active');
        }
    });
    
    // Update active dot
    dots.forEach((dot, i) => {
        if (i === index) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

// Auto-play functionality
let autoPlayInterval;

function startAutoPlay() {
    const DELAY = 4800;
    const slides = document.querySelectorAll('.slide');
    
    // Clear any existing interval
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
    }
    
    // Set up new interval
    autoPlayInterval = setInterval(() => {
        const currentSlide = document.querySelector('.slide.active');
        const currentIndex = currentSlide ? parseInt(currentSlide.dataset.index) : 0;
        const nextIndex = (currentIndex + 1) % slides.length;
        goToSlide(nextIndex);
    }, DELAY);
    
    // Pause on hover
    const container = document.querySelector('.pi-sites');
    if (container) {
        container.addEventListener('mouseenter', pauseAutoPlay);
        container.addEventListener('mouseleave', startAutoPlay);
        container.addEventListener('touchstart', pauseAutoPlay);
        container.addEventListener('touchend', startAutoPlay);
    }
}

function pauseAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
    }
}

// Initialize slides when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initSlides();
});
