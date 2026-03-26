<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
    $id        = get_the_ID();
    $prix      = get_post_meta( $id, '_immo_prix',      true );
    $surface   = get_post_meta( $id, '_immo_surface',   true );
    $pieces    = get_post_meta( $id, '_immo_pieces',    true );
    $bains     = get_post_meta( $id, '_immo_bains',     true );
    $statut    = get_post_meta( $id, '_immo_statut',    true );
    $reference = get_post_meta( $id, '_immo_reference', true );
    $contact   = get_post_meta( $id, '_immo_contact',   true ) ?: '+261 34 83 498 86';
    $types     = wp_get_post_terms( $id, 'type_bien', ['fields' => 'names'] );
    $quartiers = wp_get_post_terms( $id, 'quartier',   ['fields' => 'names'] );
    $image     = get_the_post_thumbnail_url( $id, 'full' ) ?: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80';
    $wa_msg    = urlencode( 'Bonjour Immo Tana, je suis intéressé(e) par le bien : ' . get_the_title() . ' (Réf. ' . $reference . ')' );
?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container">
        <nav class="breadcrumb" aria-label="Fil d'Ariane">
            <a href="<?php echo esc_url( home_url('/') ); ?>"><i class="fas fa-home"></i> Accueil</a>
            <span>/</span>
            <a href="<?php echo esc_url( home_url('/#biens') ); ?>">Biens</a>
            <span>/</span>
            <span><?php the_title(); ?></span>
        </nav>
    </div>
</div>

<!-- Single bien -->
<section class="single-bien-section">
    <div class="container">
        <div class="single-bien-grid">

            <!-- Colonne gauche : image + galerie -->
            <div class="single-bien-media">
                <div class="single-image-wrap">
                    <img src="<?php echo esc_url($image); ?>"
                         alt="<?php the_title_attribute(); ?>"
                         class="single-main-image" id="main-image">
                    <?php echo immo_statut_badge($statut); ?>
                    <?php if ($reference): ?>
                        <span class="bien-ref">Réf. <?php echo esc_html($reference); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Description complète -->
                <div class="single-description">
                    <h3><i class="fas fa-align-left"></i> Description</h3>
                    <div class="description-content">
                        <?php the_content(); ?>
                        <?php if ( ! get_the_content() ): ?>
                            <p><?php echo get_the_excerpt() ?: 'Aucune description disponible pour ce bien.'; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Google Maps -->
                <?php if ( ! empty($quartiers) ): ?>
                <div class="single-map">
                    <h3><i class="fas fa-map-marker-alt"></i> Localisation — <?php echo esc_html($quartiers[0]); ?></h3>
                    <iframe
                        src="https://maps.google.com/maps?q=<?php echo urlencode($quartiers[0]); ?>+Antananarivo+Madagascar&output=embed&z=14"
                        width="100%" height="280"
                        style="border:0;border-radius:12px;display:block;"
                        allowfullscreen="" loading="lazy"
                        title="Localisation du bien">
                    </iframe>
                </div>
                <?php endif; ?>
            </div>

            <!-- Colonne droite : détails + contact -->
            <div class="single-bien-info">

                <!-- Titre + prix -->
                <div class="single-header">
                    <?php if ( ! empty($types) ): ?>
                        <span class="bien-type-badge"><?php echo esc_html($types[0]); ?></span>
                    <?php endif; ?>
                    <h1 class="single-title"><?php the_title(); ?></h1>
                    <?php if ( ! empty($quartiers) ): ?>
                        <p class="single-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo esc_html( implode(', ', $quartiers) ); ?>, Antananarivo
                        </p>
                    <?php endif; ?>
                    <div class="single-prix">
                        <?php echo esc_html( immo_format_prix($prix, $statut) ); ?>
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="single-features">
                    <h3>Caractéristiques</h3>
                    <div class="features-grid">
                        <?php if ($surface): ?>
                        <div class="feature-item">
                            <i class="fas fa-ruler-combined"></i>
                            <div>
                                <span class="feat-value"><?php echo esc_html($surface); ?> m²</span>
                                <span class="feat-label">Surface</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($pieces): ?>
                        <div class="feature-item">
                            <i class="fas fa-door-open"></i>
                            <div>
                                <span class="feat-value"><?php echo esc_html($pieces); ?></span>
                                <span class="feat-label">Pièces</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($bains): ?>
                        <div class="feature-item">
                            <i class="fas fa-bath"></i>
                            <div>
                                <span class="feat-value"><?php echo esc_html($bains); ?></span>
                                <span class="feat-label">Salle(s) de bain</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($statut): ?>
                        <div class="feature-item">
                            <i class="fas fa-tag"></i>
                            <div>
                                <span class="feat-value"><?php echo $statut === 'vente' ? 'Vente' : 'Location'; ?></span>
                                <span class="feat-label">Type d'offre</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Formulaire de contact pour CE bien -->
                <div class="single-contact-form">
                    <h3><i class="fas fa-envelope"></i> Demander des informations</h3>

                    <div id="single-form-alert" class="form-alert" role="alert"></div>

                    <form id="single-contact-form">
                        <input type="hidden" name="bien_title" value="<?php the_title_attribute(); ?>">
                        <input type="hidden" name="bien_ref" value="<?php echo esc_attr($reference); ?>">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Votre nom *" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Email *" required>
                            </div>
                            <div class="form-group">
                                <input type="tel" name="phone" placeholder="Téléphone *" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="message" rows="3"
                                placeholder="Je suis intéressé(e) par ce bien..."
                                required><?php echo "Bonjour, je souhaite avoir plus d'informations sur le bien : " . esc_textarea(get_the_title()) . " (Réf. " . esc_textarea($reference) . ")."; ?></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="single-submit" class="btn btn-gold btn-full">
                                <i class="fas fa-paper-plane"></i>
                                <span>Envoyer ma demande</span>
                            </button>
                        </div>
                    </form>

                    <!-- Boutons alternatifs -->
                    <div class="single-contact-alt">
                        <a href="https://wa.me/261348349886?text=<?php echo $wa_msg; ?>"
                           target="_blank" rel="noopener" class="btn btn-whatsapp btn-full">
                            <i class="fab fa-whatsapp"></i> Contacter via WhatsApp
                        </a>
                        <a href="tel:+261348349886" class="btn btn-outline btn-full">
                            <i class="fas fa-phone-alt"></i> Appeler maintenant
                        </a>
                    </div>
                </div>

            </div><!-- .single-bien-info -->
        </div><!-- .single-bien-grid -->
    </div>
</section>

<!-- Biens similaires -->
<section class="similaires-section">
    <div class="container">
        <h2 class="section-title">Biens <span class="text-gold">similaires</span></h2>
        <?php
        $similaires_args = [
            'post_type'      => 'bien_immo',
            'posts_per_page' => 3,
            'post__not_in'   => [ $id ],
            'post_status'    => 'publish',
            'orderby'        => 'rand',
        ];
        if ( ! empty($types) ) {
            $similaires_args['tax_query'] = [[
                'taxonomy' => 'type_bien',
                'field'    => 'name',
                'terms'    => $types,
            ]];
        }
        $similaires = immo_get_biens( $similaires_args );
        ?>
        <div class="biens-grid biens-grid-3">
            <?php foreach ( $similaires as $bien ) :
                $s_image  = $bien['image'] ?: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&q=80';
                $s_statut = $bien['statut'] ?? '';
            ?>
            <article class="bien-card">
                <div class="bien-card-image">
                    <img src="<?php echo esc_url($s_image); ?>"
                         alt="<?php echo esc_attr($bien['title']); ?>" loading="lazy">
                    <?php echo immo_statut_badge($s_statut); ?>
                </div>
                <div class="bien-card-body">
                    <h3 class="bien-title">
                        <a href="<?php echo esc_url($bien['permalink']); ?>"><?php echo esc_html($bien['title']); ?></a>
                    </h3>
                    <div class="bien-card-footer">
                        <div class="bien-prix"><?php echo esc_html( immo_format_prix($bien['prix'], $s_statut) ); ?></div>
                        <a href="<?php echo esc_url($bien['permalink']); ?>" class="btn-detail">Voir</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
