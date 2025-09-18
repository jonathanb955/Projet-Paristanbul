<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul</title>

    <!-- Styles externes -->
    <link rel="stylesheet" href="../assets/css/index.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ===== Modal + Spinner (Paristanbul) ===== -->
    <style>
        /* ===== Modal base ===== */
        .pi-modal{position:fixed;inset:0;display:grid;place-items:center;opacity:0;pointer-events:none;transition:opacity .25s ease;z-index:9999}
        .pi-modal--open{opacity:1;pointer-events:auto}
        .pi-modal__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45);backdrop-filter:saturate(120%) blur(2px)}
        .pi-modal__dialog{
            position:relative;width:min(560px,92vw);border-radius:20px;padding:28px 24px;background:#fff;
            box-shadow:0 20px 60px rgba(0,0,0,.25);transform:translateY(8px) scale(.96);opacity:.9
        }
        .pi-modal--open .pi-modal__dialog{animation:piZoom .42s cubic-bezier(.2,.8,.2,1) forwards}
        @keyframes piZoom{to{transform:translateY(0) scale(1);opacity:1}}

        /* Badge + effet burst */
        .pi-modal__badge{
            width:82px;height:82px;border-radius:50%;display:grid;place-items:center;margin:-70px auto 12px;
            background:linear-gradient(135deg,#003366 0%,#2a6bb0 100%);color:#fff;font-size:40px;position:relative;
            box-shadow:0 10px 24px rgba(0,51,102,.35)
        }
        .pi-modal--open .pi-modal__badge{animation:pop .45s ease-out}
        @keyframes pop{0%{transform:scale(.6)}70%{transform:scale(1.08)}100%{transform:scale(1)}}
        .pi-modal__badge .burst{
            position:absolute;inset:-16px;border-radius:50%;pointer-events:none;
            background:
                    radial-gradient(circle at 50% 0%, rgba(42,107,176,.35), transparent 60%),
                    radial-gradient(circle at 100% 50%, rgba(0,51,102,.35), transparent 55%),
                    radial-gradient(circle at 0% 50%, rgba(0,51,102,.25), transparent 55%);
            filter:blur(8px);opacity:0
        }
        .pi-modal--open .pi-modal__badge .burst{animation:burst .6s ease-out .12s forwards}
        @keyframes burst{to{opacity:1;filter:blur(14px);}}

        .pi-modal__title{ text-align:center; margin:6px 0 6px; font-size:1.35rem; font-weight:800; color:#003366}
        .pi-modal__text{ text-align:center; color:#2b2b2b; margin:0 8px 18px; line-height:1.45}
        .pi-modal__actions{display:flex; justify-content:center; gap:12px}
        .pi-btn{border:0;border-radius:12px;padding:10px 16px;font-weight:700;cursor:pointer;transition:transform .08s ease,box-shadow .18s ease}
        .pi-btn--primary{background:#003366;color:#fff;box-shadow:0 6px 16px rgba(0,51,102,.35)}
        .pi-btn--primary:active{transform:translateY(1px);box-shadow:0 2px 10px rgba(0,51,102,.35)}
        .pi-modal__close{position:absolute;top:10px;right:10px;width:40px;height:40px;border-radius:50%;border:0;background:#f3f5f8;cursor:pointer;font-size:22px}
        .pi-modal__close:hover{background:#e8ecf2}
        body.pi-modal-open{overflow:hidden}

        /* Bouton "Envoyer" : état chargement */
        #contactForm .btn-green[disabled]{opacity:.75;cursor:not-allowed}
        .pi-spinner{display:inline-block;width:18px;height:18px;border-radius:50%;border:3px solid rgba(255,255,255,.5);border-top-color:#fff;vertical-align:-3px;animation:spin .7s linear infinite;margin-left:8px}
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>
</head>

<body>
<header>
    <nav class="navbar">
        <div class="logo"><img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" style="width: 300px"></div>
        <ul class="nav-links">
            <li><a href="#" class="active">Accueil</a></li>
            <li><a href="nosMagasins.php">Nos magasins</a></li>
            <li><a href="quiSommesNous.html">Notre histoire</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="postuler.php">Postuler</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="pageConnexion.php" style="text-decoration: none; color: black; display: flex; flex-direction: column; align-items: center; font-weight: 500;">
                <i class="bi bi-person" style="font-size: 30px;"></i>
                <span>Me connecter / M'inscrire</span>
            </a>
        </div>
    </nav>
</header>

<main>
    <!-- Hero -->
    <header class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 style=" text-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);">Des produits frais et de qualité près de chez vous</h1>
                <p style=" text-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);">Découvrez notre large sélection de produits frais, locaux et à prix compétitifs.</p>
                <div class="hero-buttons">
                    <a href="#2" class="btn-outline">Nos produits populaires</a>
                    <a href="nosMagasins.php" class="btn-outline dark" style="color: red">Trouver un magasin</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="video-container" style="overflow: hidden; border-radius: 20px; width: fit-content;">
                    <iframe
                            width="120%" height="400" style="margin-right: 270px; border-radius: 20px;"
                            src="https://www.youtube.com/embed/WgkwUxDKi_0?autoplay=1&mute=1"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </header>

    <!-- Rayons -->
    <section class="rayons-section">
        <div class="container">
            <h2 class="section-title">Nos rayons</h2>
            <p class="section-subtitle">Découvrez la diversité de nos produits frais et de qualité.</p>
            <div class="btn-add-rayon">
                <a href="catalogue.php" class="btn-green" style="background-color: #003366">Découvrir nos rayons</a>
            </div>
            <div class="rayons-grid">
                <div class="rayon-card">
                    <div class="icon"><img src="../assets/img/boisson.jpeg" alt="Boissons" /></div>
                    <h3>Boissons</h3>
                    <p>Rafraîchissez-vous avec notre large sélection de jus, sodas, eaux et boissons chaudes pour tous les goûts.</p>
                    <a href="#" class="discover" style="color: #003366">Découvrir →</a>
                </div>
                <div class="rayon-card">
                    <div class="icon"><img src="../assets/img/viande.jpeg" alt="Viandes" /></div>
                    <h3>Viandes</h3>
                    <p>Des viandes fraîches et savoureuses, idéales pour vos repas du quotidien ou occasions spéciales.</p>
                    <a href="#" class="discover" style="color: #003366">Découvrir →</a>
                </div>
                <div class="rayon-card">
                    <div class="icon"><img src="../assets/img/produitFrais.jpeg" alt="Produits Frais" /></div>
                    <h3>Produits Frais</h3>
                    <p>Découvrez nos produits frais, riches en saveurs : fruits, légumes, produits laitiers et plus encore.</p>
                    <a href="#" class="discover" style="color: #003366">Découvrir →</a>
                </div>
                <div class="rayon-card">
                    <div class="icon"><img src="../assets/img/produitSec.jpeg" alt="Produits secs" /></div>
                    <h3>Produits secs</h3>
                    <p>Pâtes, riz, conserves… tout le nécessaire pour vos placards, toujours à portée de main.</p>
                    <a href="#" class="discover" style="color: #003366">Découvrir →</a>
                </div>
                <div class="rayon-card">
                    <div class="icon"><img src="../assets/img/surgeles.jpeg" alt="Surgelés" /></div>
                    <h3>Surgelés</h3>
                    <p>Des produits surgelés de qualité pour des repas rapides, savoureux et toujours disponibles.</p>
                    <a href="#" class="discover" style="color: #003366">Découvrir →</a>
                </div>
                <div class="rayon-card">
                    <div class="icon"><img src="../assets/img/emballage.jpeg" alt="Emballages" /></div>
                    <h3>Emballages</h3>
                    <p>Sacs, boîtes, papiers… des solutions pratiques pour stocker, protéger ou emporter vos produits.</p>
                    <a href="#" class="discover" style="color: #003366">Découvrir →</a>
                </div>
                <div class="rayon-card">
                    <div class="icon"><img src="../assets/img/hygiene.jpeg" alt="Hygiènes" /></div>
                    <h3>Hygiènes</h3>
                    <p>Prenez soin de vous avec notre gamme d’hygiène pour toute la famille : soins, savons, entretien personnel.</p>
                    <a href="#" class="discover" style="color: #003366">Découvrir →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Produits populaires (placeholders) -->
    <section class="produits-populaires" id="2">
        <div class="container">
            <h2 class="section-title">Nos produits populaires</h2>
            <p class="section-subtitle">Les produits préférés de nos clients.</p>

            <div class="produits-grid">
                <div class="produit-card">
                    <div class="produit-image"><img src="placeholder.png" alt="Pommes Gala" /></div>
                    <div class="produit-info">
                        <h3>Pommes Gala</h3>
                        <p>Pommes croquantes et sucrées, idéales pour une collation.</p>
                        <div class="produit-footer">
                            <span class="prix">2,49 €</span><span class="poids">/ kg</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>

                <div class="produit-card">
                    <div class="produit-image"><img src="placeholder.png" alt="Pain de campagne" /></div>
                    <div class="produit-info">
                        <h3>Pain de campagne</h3>
                        <p>Pain traditionnel à la mie dense et savoureuse.</p>
                        <div class="produit-footer">
                            <span class="prix">3,20 €</span><span class="poids">/ 500g</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>

                <div class="produit-card">
                    <div class="produit-image"><img src="placeholder.png" alt="Fromage de chèvre" /></div>
                    <div class="produit-info">
                        <h3>Fromage de chèvre</h3>
                        <p>Fromage de chèvre frais et crémeux.</p>
                        <div class="produit-footer">
                            <span class="prix">4,50 €</span><span class="poids">/ 200g</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>

                <div class="produit-card">
                    <div class="produit-image"><img src="placeholder.png" alt="Yaourt nature" /></div>
                    <div class="produit-info">
                        <h3>Yaourt nature</h3>
                        <p>Yaourt onctueux au lait entier.</p>
                        <div class="produit-footer">
                            <span class="prix">2,75 €</span><span class="poids">/ 4x125g</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>

                <div class="produit-card">
                    <div class="produit-image"><img src="placeholder.png" alt="Café moulu arabica" /></div>
                    <div class="produit-info">
                        <h3>Café moulu arabica</h3>
                        <p>Café arabica torréfié et moulu, saveur intense.</p>
                        <div class="produit-footer">
                            <span class="prix">5,90 €</span><span class="poids">/ 250g</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promotions -->
    <section class="promotions">
        <div class="container">
            <h2 class="section-title">Nos promotions de la semaine</h2>
            <p class="section-subtitle">Profitez de nos offres spéciales et économisez sur vos achats.</p>

            <div class="promos-grid">
                <div class="promo-card">
                    <div class="promo-header" style="background-color: #003366">30%</div>
                    <h3>Fruits de saison</h3>
                    <p>Profitez de 30% de réduction sur tous les fruits de saison ce week-end.</p>
                    <p><span class="barre">4,99 €</span> <span class="promo-prix" style="color: #003366">3,49 €</span> / kg</p>
                    <div class="promo-footer">
                        <span class="date" style="color: #003366">Jusqu'à dimanche</span>
                        <a href="#" class="voir-plus" style="background-color: #003366">Voir plus</a>
                    </div>
                </div>

                <div class="promo-card">
                    <div class="promo-header" style="background-color: #003366">2 + 1 GRATUIT</div>
                    <h3>Yaourts Bio</h3>
                    <p>Pour 2 packs de yaourts bio achetés, le 3ème est offert (le moins cher).</p>
                    <p><span class="barre">3,75 €</span> <span class="promo-prix" style="color: #003366">3,75 €</span> / 6x125g</p>
                    <div class="promo-footer">
                        <span class="date" style="color: #003366">Cette semaine</span>
                        <a href="#" class="voir-plus" style="background-color: #003366">Voir plus</a>
                    </div>
                </div>

                <div class="promo-card">
                    <div class="promo-header" style="background-color: #003366">-50% sur le 2ème</div>
                    <h3>Filet de saumon</h3>
                    <p>50% de réduction sur le deuxième filet de saumon acheté.</p>
                    <p><span class="barre">12,90 €</span> <span class="promo-prix" style="color: #003366">9,68 €</span> / 250g</p>
                    <div class="promo-footer">
                        <span class="date" style="color: #003366">Jusqu'à mercredi</span>
                        <a href="#" class="voir-plus" style="background-color: #003366">Voir plus</a>
                    </div>
                </div>
            </div>

            <div class="btn-all-promos">
                <a href="#" class="btn-green" style="background-color: #003366">Voir toutes les promotions</a>
            </div>
        </div>
    </section>

    <!-- Magasins -->
    <section class="magasins">
        <div class="container">
            <h2 class="section-title">Quelques magasins</h2>
            <p class="section-subtitle">Trouvez le Paristanbul le plus proche de chez vous.</p>

            <div class="btn-add-rayon">
                <a href="nosMagasins.php" class="btn-green" style="background-color: #003366">Tous nos magasins</a>
            </div>

            <div class="magasins-grid">
                <div class="magasin-card">
                    <h3>Paristanbul Villiers le bel</h3>
                    <p><i class="bi bi-geo-alt-fill" style="color:#003366"></i>117 Avenue Pierre Semard, 95400 Villiers-le-Bel</p>
                    <p><i class="bi bi-clock-fill" style="color:#003366"></i> Lun-Dim: 8h30–20h</p>
                    <p><i class="bi bi-telephone-fill" style="color:#003366"></i> +33 7 49 82 61 33</p>
                    <a href="#"
                       class="itineraire-btn"
                       data-address="117 Avenue Pierre Semard, 95400 Villiers-le-Bel"
                       data-lat="49.0094"
                       data-lon="2.3911"
                       style="background-color:#003366">Itinéraire</a>
                </div>

                <div class="magasin-card">
                    <h3>Paristanbul Bondy</h3>
                    <p><i class="bi bi-geo-alt-fill" style="color:#003366"></i> 116 Avenue Gallieni, 93140 Bondy</p>
                    <p><i class="bi bi-clock-fill" style="color:#003366"></i> Lun-Dim: 8h30–20h</p>
                    <p><i class="bi bi-telephone-fill" style="color:#003366"></i>+33 7 49 82 61 33</p>
                    <a href="#"
                       class="itineraire-btn"
                       data-address="116 Avenue Gallieni, 93140 Bondy"
                       data-lat="48.9022"
                       data-lon="2.48278"
                       style="background-color:#003366">Itinéraire</a>
                </div>

                <div class="magasin-card">
                    <h3>Paristanbul Drancy</h3>
                    <p><i class="bi bi-geo-alt-fill" style="color:#003366"></i> 83 Avenue Marceau, 93700 Drancy</p>
                    <p><i class="bi bi-clock-fill" style="color:#003366"></i> Lun-Dim: 8h30–20h30</p>
                    <p><i class="bi bi-telephone-fill" style="color:#003366"></i>+33 7 49 82 61 33</p>
                    <a href="#"
                       class="itineraire-btn"
                       data-address="83 Avenue Marceau, 93700 Drancy"
                       data-lat="48.924298"
                       data-lon="2.445676"
                       style="background-color:#003366">Itinéraire</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Contactez-nous</h2>
            <p class="section-subtitle">Une question, une suggestion ? N'hésitez pas à nous contacter.</p>

            <div class="contact-content">
                <!-- Formulaire de contact -->
                <div class="contact-form">
                    <h3>Envoyez-nous un message</h3>
                    <form id="contactForm" action="javascript:void(0)" method="post" enctype="multipart/form-data" data-endpoint="contactSubmit.php">
                        <label for name="nom">Nom complet</label>
                        <input type="text" name="nom" placeholder="Votre nom" required />

                        <label for name="email">Email</label>
                        <input type="email" name="email" placeholder="votre@email.com" required />

                        <label for name="sujet">Sujet</label>
                        <select name="sujet" required>
                            <option value="">Sélectionnez un sujet</option>
                            <option>Informations générales</option>
                            <option>Commande</option>
                            <option>Problème technique</option>
                        </select>

                        <label for name="message">Message</label>
                        <textarea name="message" rows="5" placeholder="Votre message..." required></textarea>

                        <button type="submit" class="btn-green" style="background-color: #003366">Envoyer le message</button>
                    </form>
                </div>

                <!-- Infos & Newsletter -->
                <div class="contact-side">
                    <div class="contact-box">
                        <h3>Service client</h3>
                        <p><i class="bi bi-telephone-fill"></i> <strong>Téléphone</strong><br>07 49 82 61 33 (appel gratuit)</p>
                        <p><i class="bi bi-envelope-fill"></i> <strong>Email</strong><br>parisistambulnogent@gmail.com</p>
                        <p><i class="bi bi-clock-fill"></i> <strong>Horaires du service client</strong><br>Lundi - Vendredi: 9h00 - 18h00</p>
                    </div>

                    <div class="contact-box">
                        <h3>Rejoignez notre newsletter</h3>
                        <p>Recevez nos promotions et actualités directement dans votre boîte mail.</p>
                        <div class="newsletter">
                            <input type="email" placeholder="Votre email" />
                            <button type="submit" class="btn-green" style="background-color: #003366">S'inscrire</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- ===== Footer ===== -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-column paristanbul-col">
            <h3>Paristanbul</h3>
            <p>Rejoignez-nous sur les réseaux et accédez à nos<br>offres et nouveautés en exclusivité.</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/supermarcheparistanbul/" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/paristanbul_supermarche/?hl=fr" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://www.tiktok.com/@supermarche_paristanbul" class="tiktok"><i class="bi bi-tiktok"></i></a>
                <a href="https://www.youtube.com/channel/UCsjy3bdpFzBwM7MF923gKvA" class="youtube"><i class="bi bi-youtube"></i></a>
                <a href="#" class="paristanbul"><img src="../assets/img/logo.app.pi.webp" alt="Paristanbul"></a>
            </div>
        </div>

        <div class="footer-column">
            <h3>L'enseigne Paristanbul</h3>
            <ul>
                <li><a href="#">Notre histoire</a></li>
                <li><a href="#">Trouver un magasin</a></li>
                <li><a href="#">Nous contacter</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Actualités</h3>
            <ul>
                <li><a href="#">Nos nouveautés</a></li>
                <li><a href="#">Nos promotions</a></li>
                <li><a href="#">Télécharger l'application</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Nous rejoindre</h3>
            <ul>
                <li><a href="#">Nos offres d'emploi</a></li>
                <li><a href="#">Télécharger l'application</a></li>
            </ul>
        </div>

        <div class="footer-column newsletter-col">
            <h3>Newsletters</h3>
            <p>Abonnez-vous à notre newsletter pour recevoir nos dernières actualités.</p>
            <div class="newsletter-form">
                <input type="email" placeholder="Votre email" class="newsletter-field" />
                <button class="newsletter-submit"><i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
    </div>
</footer>

<!-- ===== Pop-up “Merci pour votre confiance !” (UN SEUL exemplaire) ===== -->
<div id="piThanks" class="pi-modal" aria-hidden="true" role="dialog" aria-labelledby="piThanksTitle">
    <div class="pi-modal__backdrop" data-pi-close></div>
    <div class="pi-modal__dialog" role="document" tabindex="-1">
        <button class="pi-modal__close" aria-label="Fermer" data-pi-close>&times;</button>
        <div class="pi-modal__badge">
            <i class="bi bi-check2-circle"></i>
            <span class="burst"></span>
        </div>
        <h3 id="piThanksTitle" class="pi-modal__title">Merci pour votre confiance&nbsp;!</h3>
        <p class="pi-modal__text">Votre message a bien été envoyé. Notre équipe vous répondra rapidement.</p>
        <div class="pi-modal__actions">
            <button class="pi-btn pi-btn--primary" data-pi-close>OK</button>
        </div>
    </div>
</div>

<!-- ===== Scripts ===== -->
<script>
    // Lien store app
    document.addEventListener("DOMContentLoaded", function () {
        const link = document.getElementById('app-store-link');
        if (!link) return;
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const ua = navigator.userAgent || navigator.vendor || window.opera;
            if (/android/i.test(ua)) window.location.href = "https://play.google.com/store/apps/details?id=com.tonapp.nom";
            else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) window.location.href = "https://apps.apple.com/fr/app/ton-app/idXXXXXXXXX";
            else window.location.href = "https://paristanbul.fr/telecharger";
        });
    });
</script>

<!-- Animations / interactions UI -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Scroll reveal
        if (!prefersReduced && 'IntersectionObserver' in window) {
            const selectors = [
                '.hero-text','.hero-image .video-container',
                '.rayon-card','.produit-card','.promo-card','.magasin-card',
                '.contact-form','.contact-box','.footer-column'
            ];
            const els = selectors.flatMap(sel => Array.from(document.querySelectorAll(sel)));
            els.forEach((el, i) => {
                el.classList.add('reveal');
                el.style.setProperty('--reveal-delay', (i % 6) * 60 + 'ms');
            });
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('reveal--in');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -10% 0px' });
            els.forEach(el => io.observe(el));
        }

        // Navbar ombre
        const nav = document.querySelector('nav.navbar');
        window.addEventListener('scroll', () => {
            if (!nav) return;
            const y = window.scrollY || 0;
            nav.classList.toggle('nav--scrolled', y > 8);
            nav.classList.remove('nav--hide');
        }, {passive:true});

        // Ripple
        document.querySelectorAll('.btn-green, .btn-outline, .itineraire-btn, .voir-plus').forEach(btn => {
            btn.addEventListener('pointerdown', (e) => {
                const r = document.createElement('span');
                r.className = 'r';
                const rect = btn.getBoundingClientRect();
                const d = Math.max(rect.width, rect.height);
                r.style.width = r.style.height = d + 'px';
                r.style.left = (e.clientX - rect.left - d / 2) + 'px';
                r.style.top  = (e.clientY - rect.top  - d / 2) + 'px';
                btn.appendChild(r);
                r.addEventListener('animationend', () => r.remove());
            });
        });

        // Tilt
        const tiltCards = document.querySelectorAll('.rayon-card, .produit-card, .promo-card, .magasin-card');
        tiltCards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const r = card.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width * 2 - 1;
                const y = (e.clientY - r.top)  / r.height * 2 - 1;
                card.style.setProperty('--ry', ( x * 6).toFixed(2) + 'deg');
                card.style.setProperty('--rx', (-y * 6).toFixed(2) + 'deg');
                card.classList.add('tilt');
            });
            card.addEventListener('mouseleave', () => {
                card.classList.remove('tilt');
                card.style.removeProperty('--rx');
                card.style.removeProperty('--ry');
            });
        });

        // Bouton Top
        const toTop = document.createElement('button');
        toTop.id = 'toTop';
        toTop.setAttribute('aria-label', 'Remonter en haut');
        toTop.innerHTML = '<i class="bi bi-arrow-up"></i>';
        document.body.appendChild(toTop);
        const updateToTop = () => toTop.classList.toggle('show', window.scrollY > 500);
        updateToTop();
        window.addEventListener('scroll', updateToTop, {passive:true});
        toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    });
</script>

<!-- Loader / Parallax / Shimmer / Compteurs -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Loader
        const L = document.createElement('div');
        L.id = 'piLoader';
        L.innerHTML = `
        <div class="loader__wrap">
          <div class="loader__ring"></div>
          <div class="loader__ring"></div>
          <img class="loader__logo" src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
        </div>`;
        document.body.appendChild(L);
        const minShow = new Promise(r => setTimeout(r, 500));
        Promise.all([minShow, new Promise(r => (window.onload = r))]).then(() => {
            requestAnimationFrame(() => L.classList.add('hide'));
            setTimeout(() => L.remove(), 800);
        });

        // Parallax
        const hero = document.querySelector('.hero-section');
        const heroText = document.querySelector('.hero-text');
        const heroVid = document.querySelector('.hero-image .video-container');
        const parallax = () => {
            if (!hero) return;
            const rect = hero.getBoundingClientRect();
            const center = rect.top + rect.height/2 - window.innerHeight/2;
            const ratio = Math.max(-1, Math.min(1, center / (window.innerHeight/2)));
            if (heroText) heroText.style.setProperty('--py', (-ratio * 10).toFixed(2));
            if (heroVid)  heroVid.style.setProperty('--py',  ( ratio * 14).toFixed(2));
        };
        parallax();
        window.addEventListener('scroll', parallax, {passive:true});
        window.addEventListener('resize', parallax, {passive:true});

        // Shimmer
        document.querySelectorAll('.produit-image').forEach(box => {
            const img = box.querySelector('img');
            if (!img) return;
            const done = () => box.classList.remove('shimmer');
            box.classList.add('shimmer');
            if (img.complete) done(); else img.addEventListener('load', done, {once:true});
        });

        // Compteurs sur prix
        const formatFR = (n) => new Intl.NumberFormat('fr-FR',{minimumFractionDigits:(n%1?2:0), maximumFractionDigits:2}).format(n);
        const parsePrice = (txt) => {
            const raw = (txt||'').replace(/\s/g,'').replace(',', '.').match(/-?\d+(\.\d+)?/);
            return raw ? parseFloat(raw[0]) : 0;
        };
        const animateNumber = (el, to, duration=900) => {
            const from = 0;
            const start = performance.now();
            el.classList.add('counting');
            const step = (t) => {
                const p = Math.min(1, (t-start)/duration);
                const eased = 1 - Math.pow(1-p, 3);
                const val = from + (to-from)*eased;
                el.textContent = formatFR(val);
                if (p < 1) requestAnimationFrame(step);
                else {
                    el.textContent = formatFR(to);
                    el.classList.remove('counting');
                    el.classList.add('flip-in');
                    el.addEventListener('animationend', ()=> el.classList.remove('flip-in'), {once:true});
                }
            };
            requestAnimationFrame(step);
        };
        if ('IntersectionObserver' in window){
            const targets = document.querySelectorAll('.prix, .promo-prix');
            const io2 = new IntersectionObserver((entries)=>{
                entries.forEach(e=>{
                    if (!e.isIntersecting) return;
                    const el = e.target;
                    const n = parsePrice(el.textContent);
                    if (!el.dataset.animated) {
                        el.dataset.animated = '1';
                        animateNumber(el, n);
                    }
                    io2.unobserve(el);
                });
            }, {threshold:.6});
            targets.forEach(el=>io2.observe(el));
        }
    });
</script>

<!-- Itinéraires Google Maps -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.itineraire-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const lat = btn.dataset.lat;
                const lon = btn.dataset.lon;
                const addr = btn.dataset.address;

                let destination = '';
                if (lat && lon) destination = `${lat},${lon}`;
                else if (addr) destination = encodeURIComponent(addr);
                else return;

                const baseUrl = `https://www.google.com/maps/dir/?api=1&destination=${destination}`;

                if ('geolocation' in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            const origin = `${pos.coords.latitude},${pos.coords.longitude}`;
                            window.open(`${baseUrl}&origin=${origin}`, '_blank', 'noopener');
                        },
                        () => window.open(baseUrl, '_blank', 'noopener'),
                        { enableHighAccuracy: true, timeout: 6000, maximumAge: 0 }
                    );
                } else {
                    window.open(baseUrl, '_blank', 'noopener');
                }
            });
        });
    });
</script>

<!-- Envoi AJAX du formulaire + contrôle du pop-up -->
<script>
    (function(){
        const modal = document.getElementById('piThanks');
        const openModal = () => {
            modal.classList.add('pi-modal--open');
            document.body.classList.add('pi-modal-open');
            modal.querySelector('.pi-modal__dialog')?.focus();
        };
        const closeModal = () => {
            modal.classList.remove('pi-modal--open');
            document.body.classList.remove('pi-modal-open');
        };
        modal?.querySelectorAll('[data-pi-close]').forEach(el => el.addEventListener('click', closeModal));
        modal?.addEventListener('click', e => { if (e.target.classList.contains('pi-modal__backdrop')) closeModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        const form = document.getElementById('contactForm');
        if (!form) return;
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!form.reportValidity()) return;

            const oldBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Envoi en cours <span class="pi-spinner" aria-hidden="true"></span>';

            try {
                const endpoint = form.dataset.endpoint || form.getAttribute('action') || location.href;
                const res = await fetch(endpoint, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'fetch' }
                });
                const isJson = res.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await res.json() : { success: res.ok };

                if (!res.ok || !data.success) throw new Error(data?.error || 'Une erreur est survenue. Merci de réessayer.');

                form.reset();
                openModal();
            } catch (err) {
                alert(err.message || 'Erreur réseau. Merci de réessayer.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = oldBtnHtml;
            }
        });
    })();
</script>
</body>
</html>
