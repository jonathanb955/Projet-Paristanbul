// Optionnel : Animation au scroll ou effets interactifs
window.addEventListener('scroll', () => {
    document.querySelectorAll('.slide-up, .zoom-in').forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            el.style.animationPlayState = 'running';
        }
    });
});
