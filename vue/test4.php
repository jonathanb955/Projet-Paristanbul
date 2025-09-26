<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Paristanbul - Nos Magasins</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>

        /* Définition des nouvelles couleurs */
        :root {
            --color-black: #0A0A0A; /* Noir très foncé, dominant */
            --color-dark-blue: #1C305C; /* Bleu nuit/sombre */
            --color-dark-red: #8B1A1A; /* Rouge bordeaux/sombre */
            --color-light-red: #A32929; /* Rouge d'accent (légèrement plus clair) */
            --color-text-light: #E0E0E0;
            --color-ui-bg: rgba(255, 255, 255, 0.05); /* Fond transparent */
            --color-ui-border: rgba(255, 255, 255, 0.15);
        }

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }



        body {

            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

            background: var(--color-black); /* Fond principal : Noir foncé */

            color: var(--color-text-light);

            padding: 2rem;

            min-height: 100vh;

        }



        .container {

            max-width: 1200px;

            margin: 0 auto;

        }



        .nav-tabs {

            display: flex;

            justify-content: center;

            gap: 1rem;

            margin-bottom: 3rem;

            flex-wrap: wrap;

        }



        .nav-tab {

            background: transparent;

            border: none;

            color: var(--color-text-light);

            font-size: 1rem;

            font-weight: 600;

            padding: 0.8rem 1.2rem;

            cursor: pointer;

            border-radius: 25px;

            transition: all 0.3s ease;

            position: relative;

            white-space: nowrap;

            display: flex;

            align-items: center;

            gap: 0.5rem;

        }



        .nav-tab:hover {

            color: white;

            background: rgba(255, 255, 255, 0.1);

        }



        .nav-tab.active {

            color: white;

            background: var(--color-dark-blue); /* Actif : Bleu foncé */

            box-shadow: 0 0 10px rgba(28, 48, 92, 0.5); /* Ombre subtile */
        }



        .content-area {

            background: var(--color-ui-bg); /* Fond de la zone de contenu (transparent) */

            border-radius: 25px;

            padding: 2rem;

            backdrop-filter: blur(15px);

            border: 1px solid var(--color-ui-border);

            min-height: 500px;

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 2rem;

            align-items: stretch;

        }



        .map-section {

            border-radius: 20px;

            overflow: hidden;

            position: relative;

            min-height: 450px;

        }



        .map-container {

            width: 100%;

            height: 100%;

            min-height: 450px;

        }



        #map {

            width: 100%;

            height: 100%;

            border-radius: 20px;

        }



        .info-section {

            display: flex;

            flex-direction: column;

            gap: 1.5rem;

        }



        .store-image {

            width: 100%;

            height: 200px;

            border-radius: 15px;

            object-fit: cover;

            border: 2px solid var(--color-ui-border);

        }



        .store-info {

            flex: 1;

            display: flex;

            flex-direction: column;

            gap: 1rem;

        }



        .store-title {

            font-size: 1.8rem;

            font-weight: 700;

            /* Gradient sombre entre le rouge sombre et le bleu foncé */
            background: linear-gradient(45deg, var(--color-dark-red), var(--color-dark-blue));

            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;

            background-clip: text;

            margin-bottom: 0.5rem;

        }



        .info-item {

            display: flex;

            align-items: center;

            gap: 1rem;

            padding: 0.8rem;

            background: rgba(255, 255, 255, 0.05); /* Fond des info items */

            border-radius: 12px;

            font-size: 0.95rem;

        }



        .icon {

            width: 20px;

            height: 20px;

            fill: var(--color-light-red); /* Icônes en rouge d'accent */

            flex-shrink: 0;

        }



        .actions {

            display: flex;

            gap: 1rem;

            margin-top: auto;

        }



        .btn {

            padding: 0.8rem 1.5rem;

            border: none;

            border-radius: 25px;

            font-weight: 600;

            cursor: pointer;

            transition: all 0.3s ease;

            text-decoration: none;

            display: inline-flex;

            align-items: center;

            gap: 0.5rem;

            font-size: 0.9rem;

            flex: 1;

            justify-content: center;

        }



        .btn-primary {

            /* Bouton principal en dégradé de rouge sombre */
            background: linear-gradient(45deg, var(--color-light-red), var(--color-dark-red));

            color: white;

        }



        .btn-secondary {

            background: var(--color-dark-blue); /* Bouton secondaire en bleu foncé */

            color: white;

            border: 1px solid var(--color-ui-border);

        }



        .btn:hover {

            transform: translateY(-2px);

            box-shadow: 0 8px 25px rgba(139, 26, 26, 0.4); /* Ombre rouge sombre */

        }



        .placeholder-text {

            display: flex;

            align-items: center;

            justify-content: center;

            height: 100%;

            color: #666;

            font-size: 1.1rem;

            text-align: center;

            grid-column: 1 / -1;

        }



        @media (max-width: 1024px) {

            .nav-tabs {

                gap: 0.5rem;

                justify-content: flex-start;

                overflow-x: auto;

                padding-bottom: 0.5rem;

            }

        }



        @media (max-width: 768px) {

            .content-area {

                grid-template-columns: 1fr;

                gap: 1.5rem;

                padding: 1.5rem;

            }



            .nav-tab {

                font-size: 0.9rem;

                padding: 0.6rem 1rem;

            }



            .map-section {

                min-height: 300px;

            }



            .map-container {

                min-height: 300px;

            }



            .actions {

                flex-direction: column;

            }



            body {

                padding: 1rem;

            }

        }



        .leaflet-popup-content-wrapper {

            background: var(--color-dark-blue); /* Pop-up Leaflet en bleu foncé */

            color: white;

            border-radius: 10px;

        }



        .leaflet-popup-content {

            color: white;

            font-weight: 500;

        }



        /* Style de l'étiquette NEW (mis à jour en rouge sombre) */

        .new-badge {

            background-color: var(--color-light-red);

            color: white;

            font-size: 0.75rem;

            font-weight: 700;

            padding: 0.2rem 0.5rem;

            border-radius: 10px;

            margin-left: 0.25rem;

            text-transform: uppercase;

            animation: pulse 1.5s infinite;

        }



        @keyframes pulse {

            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(139, 26, 26, 0.4); } /* Utilisation du rouge sombre */

            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(139, 26, 26, 0); }

            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(139, 26, 26, 0); }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="nav-tabs">

        <button class="nav-tab active" data-store="villiers1">Villiers-le-Bel</button>

        <button class="nav-tab" data-store="villiers2">Villiers-le-Bel 2</button>

        <button class="nav-tab" data-store="drancy">Drancy</button>

        <button class="nav-tab" data-store="bondy">Bondy</button>

        <button class="nav-tab" data-store="villemomble">Villemomble</button>

        <button class="nav-tab" data-store="nogent">Nogent-sur-Oise</button>

        <button class="nav-tab" data-store="vertsaintdenis">

            Vert-Saint-Denis

            <span class="new-badge">New</span>

        </button>

    </div>



    <div class="content-area" id="contentArea">

        <div class="placeholder-text">

            Chargement...

        </div>

    </div>

</div>



<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

    const storesData = {

        villiers1: {

            title: 'Paristanbul VILLIERS-LE-BEL',

            image: 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=200&fit=crop&crop=center',

            address: '3 avenue des entrepreneurs, VILLIERS-LE-BEL',

            hours: 'Lundi à Dimanche : 08:30-20:00',

            phone: '01 39 94 12 34',

            coordinates: [49.0010, 2.3894]

        },

        villiers2: {

            title: 'Paristanbul VILLIERS-LE-BEL 2',

            image: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=200&fit=crop&crop=center',

            address: '117 Avenue Pierre Semard, VILLIERS-LE-BEL',

            hours: 'Lundi à Dimanche : 08:30-20:00',

            phone: '01 39 95 12 34',

            coordinates: [49.0015, 2.3900]

        },

        drancy: {

            title: 'Paristanbul DRANCY',

            image: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&h=200&fit=crop&crop=center',

            address: '83 avenue Marceau, DRANCY',

            hours: 'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00',

            phone: '01 48 95 12 34',

            coordinates: [48.9242, 2.4456]

        },

        bondy: {

            title: 'Paristanbul BONDY',

            image: 'https://images.unsplash.com/photo-1574719602651-bce1b0cb84a3?w=400&h=200&fit=crop&crop=center',

            address: '116 Av. Gallieni, BONDY',

            hours: 'Lundi à Samedi : 09:00-21:00, Dimanche : 09:00-19:00',

            phone: '01 48 47 12 34',

            coordinates: [48.9024, 2.4823]

        },

        villemomble: {

            title: 'Paristanbul VILLEMOMBLE',

            image: 'https://images.unsplash.com/photo-1534723328310-e82dad3ee43f?w=400&h=200&fit=crop&crop=center',

            address: '68 ALLEE DU PLATEAU, VILLEMOMBLE',

            hours: 'Lundi à Dimanche : 08:00-20:30',

            phone: '01 45 28 12 34',

            coordinates: [48.8844, 2.5103]

        },

        nogent: {

            title: 'Paristanbul NOGENT-SUR-OISE',

            image: 'https://images.unsplash.com/photo-1604719312566-9d6eed8dd866?w=400&h=200&fit=crop&crop=center',

            address: '171 Rue Jean Monnet, NOGENT-SUR-OISE',

            hours: 'Lundi à Samedi : 09:30-20:00, Dimanche : 10:00-19:00',

            phone: '03 44 74 12 34',

            coordinates: [49.2765, 2.2011]

        },

        vertsaintdenis: {

            title: 'Paristanbul VERT-SAINT-DENIS',

            image: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=200&fit=crop&crop=center',

            address: 'La Fontaine Ronde, VERT-SAINT-DENIS',

            hours: 'Lundi à Dimanche : 08:30-20:30',

            phone: '01 64 10 12 34',

            coordinates: [48.6478, 2.6223]

        }

    };



    let currentMap = null;



    function createStoreContent(storeKey) {

        const store = storesData[storeKey];

        return `

                <div class="map-section">

                    <div class="map-container">

                        <div id="map"></div>

                    </div>

                </div>

                <div class="info-section">

                    <img src="${store.image}" alt="${store.title}" class="store-image">

                    <div class="store-info">

                        <h2 class="store-title">${store.title}</h2>

                        <div class="info-item">

                            <svg class="icon" viewBox="0 0 24 24">

                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>

                            </svg>

                            <span>${store.address}</span>

                        </div>

                        <div class="info-item">

                            <svg class="icon" viewBox="0 0 24 24">

                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>

                            </svg>

                            <span>${store.hours}</span>

                        </div>

                        <div class="info-item">

                            <svg class="icon" viewBox="0 0 24 24">

                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>

                            </svg>

                            <span>${store.phone}</span>

                        </div>

                    </div>

                    <div class="actions">

                        <a href="#" class="btn btn-primary" onclick="openDirections('${store.address}')">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">

                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>

                            </svg>

                            Itinéraire

                        </a>

                        <a href="tel:${store.phone.replace(/\s/g, '')}" class="btn btn-secondary">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">

                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>

                            </svg>

                            Appeler

                        </a>

                    </div>

                </div>

            `;

    }



    function initMap(lat, lng, title, address) {

        if (currentMap) {

            currentMap.remove();

        }



        currentMap = L.map('map', {

            zoomControl: true,

            scrollWheelZoom: true

        }).setView([lat, lng], 15);



        L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png', {

            attribution: '© OpenStreetMap contributors, © CARTO',

            subdomains: 'abcd',

            maxZoom: 19

        }).addTo(currentMap);



        const customIcon = L.divIcon({

            html: '<div style="background: var(--color-light-red); width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.3);"></div>', /* Marqueur en rouge d'accent */

            iconSize: [26, 26],

            iconAnchor: [13, 13]

        });



        L.marker([lat, lng], { icon: customIcon })

            .addTo(currentMap)

            .bindPopup(`<strong>${title}</strong><br>${address}`)

            .openPopup();



        setTimeout(() => {

            currentMap.invalidateSize();

        }, 100);

    }



    function openDirections(address) {

        const encodedAddress = encodeURIComponent(address);

        window.open(`https://www.google.com/maps/dir/?api=1&destination=$${encodedAddress}`, '_blank');

    }



    function selectStore(storeKey) {

        // Mettre à jour les onglets

        document.querySelectorAll('.nav-tab').forEach(tab => {

            tab.classList.remove('active');

        });

        document.querySelector(`[data-store="${storeKey}"]`).classList.add('active');



        // Mettre à jour le contenu

        const contentArea = document.getElementById('contentArea');

        contentArea.innerHTML = createStoreContent(storeKey);



        // Initialiser la carte

        const store = storesData[storeKey];

        setTimeout(() => {

            initMap(store.coordinates[0], store.coordinates[1], store.title, store.address);

        }, 100);

    }



    // Event listeners pour les onglets

    document.querySelectorAll('.nav-tab').forEach(tab => {

        tab.addEventListener('click', () => {

            const storeKey = tab.getAttribute('data-store');

            selectStore(storeKey);

        });

    });



    // Sélectionner le premier magasin par défaut

    document.addEventListener('DOMContentLoaded', () => {

        setTimeout(() => {

            selectStore('villiers1');

        }, 100);

    });

</script>

</body>

</html>