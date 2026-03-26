<?php get_header(); ?>

<!-- =====================================================
     HERO
     ===================================================== -->
<section class="hero-section" id="accueil">
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1600&q=80"
             alt="Immobilier Antananarivo" class="hero-bg-img" loading="eager">
        <div class="hero-overlay"></div>
    </div>
    <div class="container hero-inner">
        <div class="hero-content">
            <span class="hero-tag">🏡 Agence immobilière — Antananarivo</span>
            <h1 class="hero-title">
                Trouvez votre<br>
                <span class="text-gold">bien idéal</span><br>
                à Madagascar
            </h1>
            <p class="hero-desc">
                Appartements, maisons, villas, terrains et bureaux à Antananarivo.
                Des biens vérifiés, des prix transparents, un accompagnement personnalisé.
            </p>
            <div class="hero-actions">
                <a href="#biens" class="btn btn-gold">
                    <i class="fas fa-search"></i> Voir nos biens
                </a>
                <a href="https://wa.me/261348349886" target="_blank" rel="noopener" class="btn btn-outline-white">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number" data-count="120">0</span>
                <span class="stat-label">Biens disponibles</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-count="8">0</span>
                <span class="stat-label">Ans d'expérience</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-count="450">0</span>
                <span class="stat-label">Clients satisfaits</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" data-count="98">0</span>
                <span class="stat-label">% Satisfaction</span>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="scroll-indicator">
        <span></span>
    </div>
</section>

<!-- =====================================================
     SEARCH BAR RAPIDE
     ===================================================== -->
<section class="search-bar-section">
    <div class="container">
        <div class="search-bar-card">
            <div class="search-bar-inner" id="quick-search-form">
                <div class="search-field">
                    <label><i class="fas fa-home"></i> Type de bien</label>
                    <select name="type_bien" id="q-type">
                        <option value="">Tous les types</option>
                        <option value="appartement">Appartement</option>
                        <option value="maison">Maison</option>
                        <option value="villa">Villa</option>
                        <option value="terrain">Terrain</option>
                        <option value="bureau">Bureau</option>
                        <option value="studio">Studio</option>
                    </select>
                </div>
                <div class="search-field">
                    <label><i class="fas fa-map-marker-alt"></i> Quartier</label>
                    <select name="quartier" id="q-quartier">
                        <option value="">Tous les quartiers</option>
                        <option value="ivandry">Ivandry</option>
                        <option value="ankadifotsy">Ankadifotsy</option>
                        <option value="analakely">Analakely</option>
                        <option value="ambohimanarina">Ambohimanarina</option>
                        <option value="antanimena">Antanimena</option>
                        <option value="alasora">Alasora</option>
                    </select>
                </div>
                <div class="search-field">
                    <label><i class="fas fa-tag"></i> Statut</label>
                    <select name="statut" id="q-statut">
                        <option value="">Vente & Location</option>
                        <option value="vente">À vendre</option>
                        <option value="location">À louer</option>
                    </select>
                </div>
                <div class="search-field">
                    <label><i class="fas fa-money-bill-wave"></i> Budget max</label>
                    <select name="prix_max" id="q-prix">
                        <option value="">Sans limite</option>
                        <option value="500000">500 000 Ar/mois</option>
                        <option value="1000000">1 000 000 Ar/mois</option>
                        <option value="100000000">100 000 000 Ar</option>
                        <option value="300000000">300 000 000 Ar</option>
                        <option value="500000000">500 000 000 Ar</option>
                    </select>
                </div>
                <button class="btn btn-gold btn-search" id="btn-search">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>
        </div>
    </div>
</section>

<!-- =====================================================
     LISTING BIENS
     ===================================================== -->
<section class="biens-section" id="biens">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Nos biens immobiliers</span>
            <h2 class="section-title">Tous nos <span class="text-gold">biens disponibles</span></h2>
            <p class="section-desc">Parcourez notre sélection de biens vérifiés à Antananarivo et ses environs.</p>
        </div>

        <!-- Résultats count -->
        <div class="results-bar">
            <span id="results-count" class="results-count">
                <i class="fas fa-list"></i>
                <span id="count-num"><?php echo count( immo_get_biens() ); ?></span> bien(s) trouvé(s)
            </span>
            <div class="view-toggle">
                <button class="view-btn active" id="view-grid" aria-label="Vue grille">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" id="view-list" aria-label="Vue liste">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Grille des biens -->
        <div class="biens-grid" id="biens-grid">
            <?php
            $biens = immo_get_biens();
            foreach ( $biens as $bien ) :
                $statut  = $bien['statut'] ?? '';
                $prix    = $bien['prix']   ?? 0;
                $surface = $bien['surface'] ?? 0;
                $pieces  = $bien['pieces']  ?? 0;
                $bains   = $bien['bains']   ?? 0;
                $type    = ! empty( $bien['type'] )     ? $bien['type'][0]     : '';
                $qtier   = ! empty( $bien['quartier'] ) ? $bien['quartier'][0] : '';
                $image   = $bien['image'] ?: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&q=80';
                $link    = $bien['permalink'] ?: '#';
                $ref     = $bien['reference'] ?? '';
            ?>
            <article class="bien-card" data-statut="<?php echo esc_attr($statut); ?>">
                <div class="bien-card-image">
                    <img src="<?php echo esc_url($image); ?>"
                         alt="<?php echo esc_attr($bien['title']); ?>"
                         loading="lazy">
                    <?php echo immo_statut_badge($statut); ?>
                    <?php if ($ref): ?>
                        <span class="bien-ref"><?php echo esc_html($ref); ?></span>
                    <?php endif; ?>
                    <div class="bien-card-overlay">
                        <a href="<?php echo esc_url($link); ?>" class="btn-voir">
                            <i class="fas fa-eye"></i> Voir le bien
                        </a>
                    </div>
                </div>
                <div class="bien-card-body">
                    <?php if ($type || $qtier): ?>
                    <div class="bien-meta-top">
                        <?php if ($type): ?>
                            <span class="bien-type"><i class="fas fa-home"></i> <?php echo esc_html($type); ?></span>
                        <?php endif; ?>
                        <?php if ($qtier): ?>
                            <span class="bien-location"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($qtier); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <h3 class="bien-title">
                        <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($bien['title']); ?></a>
                    </h3>

                    <?php if ($bien['excerpt']): ?>
                        <p class="bien-excerpt"><?php echo esc_html( wp_trim_words($bien['excerpt'], 15) ); ?></p>
                    <?php endif; ?>

                    <div class="bien-features">
                        <?php if ($surface): ?>
                            <span><i class="fas fa-ruler-combined"></i> <?php echo esc_html($surface); ?> m²</span>
                        <?php endif; ?>
                        <?php if ($pieces): ?>
                            <span><i class="fas fa-door-open"></i> <?php echo esc_html($pieces); ?> pièces</span>
                        <?php endif; ?>
                        <?php if ($bains): ?>
                            <span><i class="fas fa-bath"></i> <?php echo esc_html($bains); ?> bain(s)</span>
                        <?php endif; ?>
                    </div>

                    <div class="bien-card-footer">
                        <div class="bien-prix">
                            <?php echo esc_html( immo_format_prix($prix, $statut) ); ?>
                        </div>
                        <div class="bien-actions">
                            <a href="<?php echo esc_url($link); ?>" class="btn-detail">Détails</a>
                            <a href="https://wa.me/261348349886?text=Bonjour%2C+je+suis+intéressé+par+le+bien+<?php echo urlencode($bien['title']); ?>"
                               target="_blank" rel="noopener" class="btn-wa" aria-label="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Loading spinner -->
        <div class="biens-loading" id="biens-loading" style="display:none;">
            <div class="spinner"></div>
            <p>Recherche en cours…</p>
        </div>

        <!-- Aucun résultat -->
        <div class="no-results" id="no-results" style="display:none;">
            <i class="fas fa-search"></i>
            <h3>Aucun bien trouvé</h3>
            <p>Essayez de modifier vos critères de recherche.</p>
            <button class="btn btn-gold" id="btn-reset">Réinitialiser les filtres</button>
        </div>
    </div>
</section>

<!-- =====================================================
     SERVICES
     ===================================================== -->
<section class="services-section" id="services">
    <div class="container">
        <div class="section-header centered">
            <span class="section-tag">Ce que nous faisons</span>
            <h2 class="section-title">Nos <span class="text-gold">services</span></h2>
        </div>
        <div class="services-grid">
            <?php
            $services = [
                ['icon' => 'fas fa-search-location', 'title' => 'Recherche de bien',    'desc' => 'Nous trouvons le bien qui correspond exactement à vos critères et votre budget.'],
                ['icon' => 'fas fa-hand-holding-usd','title' => 'Vente immobilière',    'desc' => 'Nous vendons votre bien au meilleur prix grâce à notre réseau d\'acheteurs qualifiés.'],
                ['icon' => 'fas fa-key',             'title' => 'Gestion locative',     'desc' => 'Gestion complète de votre bien en location : locataires, loyers, entretien.'],
                ['icon' => 'fas fa-file-contract',   'title' => 'Accompagnement légal', 'desc' => 'Sécurisation des transactions, vérification des titres fonciers, conseils juridiques.'],
                ['icon' => 'fas fa-chart-line',      'title' => 'Estimation gratuite',  'desc' => 'Évaluation professionnelle de votre bien immobilier, sans engagement.'],
                ['icon' => 'fas fa-headset',         'title' => 'Suivi personnalisé',   'desc' => 'Un agent dédié vous accompagne de la recherche jusqu\'à la signature.'],
            ];
            foreach ($services as $s):
            ?>
            <div class="service-card">
                <div class="service-icon">
                    <i class="<?php echo esc_attr($s['icon']); ?>"></i>
                </div>
                <h3 class="service-title"><?php echo esc_html($s['title']); ?></h3>
                <p class="service-desc"><?php echo esc_html($s['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- =====================================================
     À PROPOS
     ===================================================== -->
<section class="apropos-section" id="apropos">
    <div class="container apropos-grid">
        <div class="apropos-image-wrap">
            <img src="https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=700&q=80"
                 alt="Équipe Immo Tana" loading="lazy" class="apropos-img">
            <div class="apropos-badge badge-exp">
                <span class="badge-num">8+</span>
                <span class="badge-label">Ans d'expérience</span>
            </div>
            <div class="apropos-badge badge-biens">
                <i class="fas fa-home"></i>
                <span>120+ biens gérés</span>
            </div>
        </div>
        <div class="apropos-content">
            <span class="section-tag">Notre histoire</span>
            <h2 class="section-title">L'immobilier à <span class="text-gold">Madagascar</span>,<br>c'est notre passion</h2>
            <p>Fondée en 2016, Immo Tana est une agence immobilière de référence à Antananarivo. Nous accompagnons les particuliers et entreprises dans leurs projets d'achat, vente et location.</p>
            <p>Notre équipe de 5 agents spécialisés couvre tous les quartiers d'Antananarivo et garantit des transactions transparentes et sécurisées.</p>
            <ul class="apropos-list">
                <li><i class="fas fa-check-circle"></i> Titres fonciers vérifiés</li>
                <li><i class="fas fa-check-circle"></i> Prix du marché garantis</li>
                <li><i class="fas fa-check-circle"></i> Accompagnement de A à Z</li>
                <li><i class="fas fa-check-circle"></i> Réseau de 450+ clients</li>
            </ul>
            <a href="#contact" class="btn btn-gold">
                <i class="fas fa-phone-alt"></i> Nous contacter
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
