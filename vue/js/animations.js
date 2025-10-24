document.addEventListener('DOMContentLoaded', function() {
    // Configuration des animations
    const animationConfig = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    // Fonction d'animation
    const animateOnScroll = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                // Ne plus observer l'élément après l'animation
                observer.unobserve(entry.target);
            }
        });
    };

    // Création de l'observateur
    const observer = new IntersectionObserver(animateOnScroll, animationConfig);

    // Cibler les éléments à animer
    const elementsToAnimate = document.querySelectorAll('.hero-text, .hero-image, .feature-card, .section-title, .cta-content');
    
    // Ajouter la classe d'animation initiale et observer chaque élément
    elementsToAnimate.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(element);
    });

    // Gestion spécifique des cartes de fonctionnalités avec un délai progressif
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach((card, index) => {
        card.style.transitionDelay = `${index * 0.15}s`;
    });

    // Gestion de l'animation du bouton d'appel à l'action
    const ctaButton = document.querySelector('.cta-content .btn');
    if (ctaButton) {
        ctaButton.style.transform = 'scale(0.95)';
        ctaButton.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
        
        ctaButton.addEventListener('mouseenter', () => {
            ctaButton.style.transform = 'scale(1.05)';
            ctaButton.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2)';
        });
        
        ctaButton.addEventListener('mouseleave', () => {
            ctaButton.style.transform = 'scale(1)';
            ctaButton.style.boxShadow = 'none';
        });
    }
});
