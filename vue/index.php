<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul</title>
    <link rel="stylesheet" href="../assets/css/test.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<header>
    <nav class="navbar">
        <div class="logo"><img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" style="width: 200px"></div>
        <ul class="nav-links">
            <li><a href="#" class="active">Accueil</a></li>
            <li><a href="#">Nos produits</a></li>
            <li><a href="#" >Promotions</a></li>
            <li><a href="#">Nouveautés</a></li>
            <li><a href="#">Nos magasins</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="#" class="btn-light">Inscription</a>
            <a href="#" class="btn-dark">Connexion</a>
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
                    <a href="#" class="btn-outline">Nos promotions</a>
                    <a href="#" class="btn-outline dark" style="color: red">Trouver un magasin</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="../assets/img/video-paristanbul.gif" style="width: 600px" alt="Illustration" />
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
                <a href="#" class="btn-green" style="background-color: #003366">Découvrir nos rayons</a>
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
    <section class="produits-populaires">
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

    <section class="nouveautes">
        <div class="container">
            <h2 class="section-title">Nouveautés</h2>
            <p class="section-subtitle">Découvrez nos derniers produits arrivés en magasin.</p>

            <div class="produits-grid">
                <div class="produit-card">
                    <div class="badge">NOUVEAU</div>
                    <div class="produit-image"><img src="placeholder.png" alt="Quinoa Bio" /></div>
                    <div class="produit-info">
                        <h3>Quinoa Bio</h3>
                        <p>Quinoa biologique de haute qualité, riche en protéines.</p>
                        <div class="produit-footer">
                            <span class="prix">4,99 €</span><span class="poids">/ 500g</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>

                <div class="produit-card">
                    <div class="badge">NOUVEAU</div>
                    <div class="produit-image"><img src="placeholder.png" alt="Huile d'olive extra vierge" /></div>
                    <div class="produit-info">
                        <h3>Huile d'olive extra vierge</h3>
                        <p>Huile d’olive premium pressée à froid.</p>
                        <div class="produit-footer">
                            <span class="prix">8,75 €</span><span class="poids">/ 750ml</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>

                <div class="produit-card">
                    <div class="badge">NOUVEAU</div>
                    <div class="produit-image"><img src="placeholder.png" alt="Chocolat noir 85%" /></div>
                    <div class="produit-info">
                        <h3>Chocolat noir 85%</h3>
                        <p>Chocolat noir intense, faible en sucre et riche en cacao.</p>
                        <div class="produit-footer">
                            <span class="prix">2,99 €</span><span class="poids">/ 100g</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>

                <div class="produit-card">
                    <div class="badge">NOUVEAU</div>
                    <div class="produit-image"><img src="placeholder.png" alt="Thé vert matcha" /></div>
                    <div class="produit-info">
                        <h3>Thé vert matcha</h3>
                        <p>Thé vert matcha japonais de qualité supérieure.</p>
                        <div class="produit-footer">
                            <span class="prix">12,50 €</span><span class="poids">/ 50g</span>
                            <span class="panier"><img src="icon-cart.png" alt="Ajouter au panier" /></span>
                        </div>
                    </div>
                </div>

                <div class="produit-card">
                    <div class="badge">NOUVEAU</div>
                    <div class="produit-image"><img src="placeholder.png" alt="Miel de montagne" /></div>
                    <div class="produit-info">
                        <h3>Miel de montagne</h3>
                        <p>Miel artisanal récolté dans les montagnes françaises.</p>
                        <div class="produit-footer">
                            <span class="prix">7,90 €</span><span class="poids">/ 250g</span>
                            <span class="panier"><i class="bi bi-cart-fill"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="promotions">
        <div class="container">
            <h2 class="section-title">Nos promotions de la semaine</h2>
            <p class="section-subtitle">Profitez de nos offres spéciales et économisez sur vos achats.</p>


            <div class="promos-grid">
                <div class="promo-card">
                    <div class="promo-header" style="background-color: #003366">30%</div>
                    <h3>Fruits de saison</h3>
                    <p>Profitez de 30% de réduction sur tous les fruits de saison ce week-end.</p>
                    <p><span class="barre" >4,99 €</span> <span class="promo-prix" style="color: #003366">3,49 €</span> / kg</p>
                    <div class="promo-footer">
                        <span class="date" style="color: #003366">Jusqu'à dimanche</span>
                        <a href="#" class="voir-plus" style="background-color: #003366">Voir plus</a>
                    </div>
                </div>

                <div class="promo-card">
                    <div class="promo-header" style="background-color: #003366">2 + 1 GRATUIT</div>
                    <h3>Yaourts Bio</h3>
                    <p>Pour 2 packs de yaourts bio achetés, le 3ème est offert (le moins cher).</p>
                    <p><span class="barre">3,75 €</span> <span class="promo-prix" style="color: #003366">3,75 €</span> / 6x125g</p>
                    <div class="promo-footer">
                        <span class="date" style="color: #003366">Cette semaine</span>
                        <a href="#" class="voir-plus" style="background-color: #003366">Voir plus</a>
                    </div>
                </div>

                <div class="promo-card">
                    <div class="promo-header" style="background-color: #003366">-50% sur le 2ème</div>
                    <h3>Filet de saumon</h3>
                    <p>50% de réduction sur le deuxième filet de saumon acheté.</p>
                    <p><span class="barre">12,90 €</span> <span class="promo-prix" style="color: #003366">9,68 €</span> / 250g</p>
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

    <section class="magasins">
        <div class="container">
            <h2 class="section-title">Nos magasins</h2>
            <p class="section-subtitle">Trouvez le SuperFrais le plus proche de chez vous.</p>

            <div class="btn-add-rayon">
                <a href="#" class="btn-green">Nos magasins</a>
            </div>

            <div class="magasins-grid">
                <div class="magasin-card">
                    <h3>SuperFrais Paris Centre</h3>
                    <p><i class="bi bi-geo-alt-fill"></i> 123 Rue de Rivoli, 75001 Paris</p>
                    <p><i class="bi bi-clock-fill"></i> Lun-Sam: 8h–21h, Dim: 9h–13h</p>
                    <p><i class="bi bi-telephone-fill"></i> 01 23 45 67 89</p>
                    <a href="#" class="itineraire-btn">Itinéraire</a>
                </div>

                <div class="magasin-card">
                    <h3>SuperFrais Lyon</h3>
                    <p><i class="bi bi-geo-alt-fill"></i> 45 Rue de la République, 69002 Lyon</p>
                    <p><i class="bi bi-clock-fill"></i> Lun-Sam: 8h30–20h30, Dim: 9h–12h30</p>
                    <p><i class="bi bi-telephone-fill"></i> 04 56 78 90 12</p>
                    <a href="#" class="itineraire-btn">Itinéraire</a>
                </div>

                <div class="magasin-card">
                    <h3>SuperFrais Marseille</h3>
                    <p><i class="bi bi-geo-alt-fill"></i> 78 La Canebière, 13001 Marseille</p>
                    <p><i class="bi bi-clock-fill"></i> Lun-Sam: 8h–20h, Dim: Fermé</p>
                    <p><i class="bi bi-telephone-fill"></i> 04 91 23 45 67</p>
                    <a href="#" class="itineraire-btn">Itinéraire</a>
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
<footer class="footer">
    <div class="container footer-content">
        <div class="footer-column">
            <h3>Paristanbul</h3>
            <p>Nous créons des solutions numériques innovantes pour transformer votre vision en réalité.</p>
            <div class="social-icons">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-tiktok"></i></a>
                <a href="#"><i class="bi bi-youtube"></i></a>
            </div>
        </div>

        <div class="footer-column">
            <h4>Liens rapides</h4>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Catalogue</a></li>
                <li><a href="#">Nos promotions</a></li>
                <li><a href="#">Nos nouveautés</a></li>
                <li><a href="#">Notre histoire</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h4>Services</h4>
            <ul>
                <li><a href="#">Conception Web</a></li>
                <li><a href="#">Applications Mobiles</a></li>
                <li><a href="#">Marketing Digital</a></li>
                <li><a href="#">Référencement SEO</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h4>Newsletter</h4>
            <p>Abonnez-vous à notre newsletter pour recevoir nos dernières actualités.</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Votre email" required />
                <button type="submit"><i class="bi bi-arrow-right"></i></button>
            </form>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2025 Paristanbul. Tous droits réservés.
    </div>
</footer>


</body>
</html>
