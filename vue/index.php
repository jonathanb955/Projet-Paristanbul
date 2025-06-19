<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul</title>
    <link rel="stylesheet" href="../assets/css/index.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


</head>

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
                            width="120%"
                            height="400"
                            style="margin-right: 270px; border-radius: 20px;"
                            src="https://www.youtube.com/embed/WgkwUxDKi_0?autoplay=1&mute=1"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                    </iframe>
                </div>
            </div>



        </div>
    </header>
    <?php

    ?>
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
                    <p> Sacs, boîtes, papiers… des solutions pratiques pour stocker, protéger ou emporter vos produits.</p>
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
    <?php
    $pdo=""
    ?>
    <?php
    $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $req = $pdo->prepare('SELECT nom_produit, photo FROM produits LIMIT 5');



    ?>
    <section class="produits-populaires" id="2">
        <div class="container">
            <h2 class="section-title">Nos produits populaires</h2>

            <p class="section-subtitle">Les produits préférés de nos clients.</p>

            <div class="produits-grid">
                <?php

                ?>
                <div class="produit-card">
                    <div class="produit-image"><img src="placeholder.png" alt="Pommes Gala" /></div>
                    <div class="produit-info">
                        <h3>Pommes Gala</h3>
                        <p>Pommes croquantes et sucrées, idéales pour une collation.</p>
                        <div class="produit-footer">
                            <span class="prix">2,49 €</span><span class="poids">/ kg</span>
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
                            <span class="prix">3,20 €</span><span class="poids">/ 500g</span>
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
                            <span class="prix">4,50 €</span><span class="poids">/ 200g</span>
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
                            <span class="prix">2,75 €</span><span class="poids">/ 4x125g</span>
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
                            <span class="prix">5,90 €</span><span class="poids">/ 250g</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <section class="magasins">
        <div class="container">
            <h2 class="section-title">Quelques magasins</h2>
            <p class="section-subtitle" >Trouvez le Paristanbul le plus proche de chez vous.</p>

            <div class="btn-add-rayon">
                <a href="nosMagasins.php" class="btn-green" style="background-color: #003366">Tous nos magasins</a>
            </div>

            <div class="magasins-grid">
                <div class="magasin-card">
                    <h3>Paristanbul Villiers le bel</h3>
                    <p><i class="bi bi-geo-alt-fill" style="color : #003366"></i>117 Avenue Pierre Semard, 95400 Villiers‑le‑Bel</p>
                    <p><i class="bi bi-clock-fill" style="color : #003366"></i> Lun-Dim: 8h30–20h</p>
                    <p><i class="bi bi-telephone-fill" style="color : #003366"></i> +33 7 49 82 61 33</p>
                    <a href="#" class="itineraire-btn" style="background-color : #003366">Itinéraire</a>
                </div>

                <div class="magasin-card">
                    <h3>Paristanbul Bondy</h3>
                    <p><i class="bi bi-geo-alt-fill" style="color : #003366"></i> 116 Avenue Gallieni, 93140 Bondy</p>
                    <p><i class="bi bi-clock-fill" style="color : #003366"></i> Lun-Dim: 8h30–20h</p>
                    <p><i class="bi bi-telephone-fill" style="color : #003366"></i>+33 7 49 82 61 33</p>
                    <a href="#" class="itineraire-btn" style="background-color: #003366">Itinéraire</a>
                </div>

                <div class="magasin-card">
                    <h3>Paristanbul Drancy</h3>
                    <p><i class="bi bi-geo-alt-fill" style="color : #003366"></i> 83 Avenue Marceau, 93700 Drancy</p>
                    <p><i class="bi bi-clock-fill" style="color : #003366"></i>Lun-Dim: 8h30–20h30</p>
                    <p><i class="bi bi-telephone-fill" style="color : #003366"></i>+33 7 49 82 61 33</p>
                    <a href="#" class="itineraire-btn" style="background-color : #003366">Itinéraire</a>
                </div>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Contactez-nous</h2>
            <p class="section-subtitle">Une question, une suggestion ? N'hésitez pas à nous contacter.</p>

            <div class="contact-content">
                <!-- Formulaire de contact -->
                <div class="contact-form">
                    <h3>Envoyez-nous un message</h3>
                    <form>
                        <label for="nom">Nom complet</label>
                        <input type="text" id="nom" placeholder="Votre nom" required />

                        <label for="email">Email</label>
                        <input type="email" id="email" placeholder="votre@email.com" required />

                        <label for="sujet">Sujet</label>
                        <select id="sujet" required>
                            <option value="">Sélectionnez un sujet</option>
                            <option>Informations générales</option>
                            <option>Commande</option>
                            <option>Problème technique</option>
                        </select>

                        <label for="message">Message</label>
                        <textarea id="message" rows="5" placeholder="Votre message..." required></textarea>

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


<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Footer Paristanbul</title>
    <link rel="stylesheet" href="footer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<footer class="footer">
    <div class="footer-container">

        <!-- Colonne 1 -->
        <div class="footer-column paristanbul-col">
            <h3>Paristanbul</h3>
            <p>
                Rejoignez-nous sur les réseaux et accédez à nos<br>
                offres et nouveautés en exclusivité.
            </p>
            <div class="social-icons">
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="tiktok"><i class="bi bi-tiktok"></i></a>
                <a href="#" class="youtube"><i class="bi bi-youtube"></i></a>
                <a href="#" class="paristanbul"><img src="../assets/img/logo.app.pi.webp" alt="Paristanbul"></a>
            </div>
        </div>

            <!-- Colonne 2 -->
        <div class="footer-column">
            <h3>L'enseigne Paristanbul</h3>
            <ul>
                <li><a href="#">Notre histoire</a></li>
                <li><a href="#">Trouver un magasin</a></li>
                <li><a href="#">Nous contacter</a></li>
            </ul>
        </div>

        <!-- Colonne 3 -->
        <div class="footer-column">
            <h3>Actualités</h3>
            <ul>
                <li><a href="#">Nos nouveautés</a></li>
                <li><a href="#">Nos promotions</a></li>
                <li><a href="#">Télécharger l'application</a></li>
            </ul>
        </div>

        <!-- Colonne 4 -->
        <div class="footer-column">
            <h3>Nous rejoindre</h3>
            <ul>
                <li><a href="#">Nos offres d'emploi</a></li>
                <li><a href="#">Télécharger l'application</a></li>
            </ul>
        </div>

        <!-- Colonne 5 -->
        <div class="footer-column newsletter-col">
            <h3>Newsletters</h3>
            <p>
                Abonnez-vous à notre newsletter pour recevoir nos dernières actualités.
            </p>
            <div class="newsletter-form">
                <input type="email" placeholder="Votre email" class="newsletter-field" />
                <button class="newsletter-submit">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

    </div>
</footer>





<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('app-store-link').addEventListener('click', function(e) {
            e.preventDefault();

            const userAgent = navigator.userAgent || navigator.vendor || window.opera;

            if (/android/i.test(userAgent)) {
                window.location.href = "https://play.google.com/store/apps/details?id=com.tonapp.nom"; // Android
            } else if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                window.location.href = "https://apps.apple.com/fr/app/ton-app/idXXXXXXXXX"; // iOS
            } else {
                window.location.href = "https://paristanbul.fr/telecharger"; // Par défaut (PC etc.)
            }
        });
    });
</script>

</body>


</html>