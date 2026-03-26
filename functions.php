<?php
/**
 * Immo Tana — functions.php
 * Architecture OOP — CPT, Taxonomies, Meta Boxes, Admin CRUD, AJAX
 *
 * @author Bayane
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// =============================================================================
// CLASSE PRINCIPALE (OOP — impressionne les recruteurs)
// =============================================================================
class ImmoTana_Theme {

    public function __construct() {
        add_action( 'after_setup_theme',  [ $this, 'setup' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
        add_action( 'init',               [ $this, 'register_post_types' ] );
        add_action( 'init',               [ $this, 'register_taxonomies' ] );
        add_action( 'add_meta_boxes',     [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post_bien_immo',[ $this, 'save_meta' ] );

        // Admin : colonnes personnalisées
        add_filter( 'manage_bien_immo_posts_columns',       [ $this, 'admin_columns' ] );
        add_action( 'manage_bien_immo_posts_custom_column', [ $this, 'admin_column_content' ], 10, 2 );
        add_filter( 'manage_edit-bien_immo_sortable_columns',[ $this, 'sortable_columns' ] );

        // Admin : filtre par statut et type
        add_action( 'restrict_manage_posts', [ $this, 'admin_filters' ] );
        add_filter( 'parse_query',           [ $this, 'parse_admin_filters' ] );

        // AJAX filter frontend
        add_action( 'wp_ajax_immo_filter',        [ $this, 'ajax_filter' ] );
        add_action( 'wp_ajax_nopriv_immo_filter', [ $this, 'ajax_filter' ] );

        // AJAX contact form
        add_action( 'wp_ajax_immo_contact',        [ $this, 'ajax_contact' ] );
        add_action( 'wp_ajax_nopriv_immo_contact', [ $this, 'ajax_contact' ] );

        // SEO + performance
        add_action( 'wp_head', [ $this, 'meta_description' ] );
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        add_filter( 'the_generator', '__return_empty_string' );

        // SMTP Gmail
        add_action( 'phpmailer_init', [ $this, 'smtp_setup' ] );
    }

    // -------------------------------------------------------------------------
    // SETUP
    // -------------------------------------------------------------------------
    public function setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery' ] );
        add_theme_support( 'custom-logo', [
            'height'      => 60,
            'width'       => 220,
            'flex-height' => true,
            'flex-width'  => true,
        ] );
        register_nav_menus( [
            'primary' => __( 'Menu Principal', 'immo-tana' ),
            'footer'  => __( 'Menu Footer',    'immo-tana' ),
        ] );
    }

    // -------------------------------------------------------------------------
    // ENQUEUE
    // -------------------------------------------------------------------------
    public function enqueue() {
        wp_enqueue_style( 'google-fonts',
            'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap',
            [], null
        );
        wp_enqueue_style( 'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
            [], '6.5.0'
        );
        wp_enqueue_style( 'immo-tana-main',
            get_template_directory_uri() . '/assets/css/main.css',
            [ 'google-fonts', 'font-awesome' ], '1.0.0'
        );
        wp_enqueue_style( 'immo-tana-style', get_stylesheet_uri(), [], '1.0.0' );

        wp_enqueue_script( 'immo-tana-main',
            get_template_directory_uri() . '/assets/js/main.js',
            [], '1.0.0', true
        );
        wp_localize_script( 'immo-tana-main', 'immoAjax', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'immo_filter_nonce' ),
        ] );
    }

    // -------------------------------------------------------------------------
    // CPT : BIEN IMMOBILIER
    // -------------------------------------------------------------------------
    public function register_post_types() {
        $labels = [
            'name'               => 'Biens immobiliers',
            'singular_name'      => 'Bien immobilier',
            'add_new'            => 'Ajouter un bien',
            'add_new_item'       => 'Ajouter un nouveau bien',
            'edit_item'          => 'Modifier le bien',
            'new_item'           => 'Nouveau bien',
            'view_item'          => 'Voir le bien',
            'search_items'       => 'Rechercher un bien',
            'not_found'          => 'Aucun bien trouvé',
            'not_found_in_trash' => 'Aucun bien dans la corbeille',
            'all_items'          => 'Tous les biens',
            'menu_name'          => 'Biens immobiliers',
        ];

        register_post_type( 'bien_immo', [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => [ 'slug' => 'biens' ],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-building',
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'show_in_rest'       => true,
        ] );
    }

    // -------------------------------------------------------------------------
    // TAXONOMIES
    // -------------------------------------------------------------------------
    public function register_taxonomies() {
        // Type de bien
        register_taxonomy( 'type_bien', 'bien_immo', [
            'labels'            => [
                'name'          => 'Types de biens',
                'singular_name' => 'Type de bien',
                'add_new_item'  => 'Ajouter un type',
                'edit_item'     => 'Modifier le type',
                'menu_name'     => 'Types',
            ],
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'rewrite'           => [ 'slug' => 'type-bien' ],
            'show_in_rest'      => true,
        ] );

        // Quartier / Localisation
        register_taxonomy( 'quartier', 'bien_immo', [
            'labels'            => [
                'name'          => 'Quartiers',
                'singular_name' => 'Quartier',
                'add_new_item'  => 'Ajouter un quartier',
                'edit_item'     => 'Modifier le quartier',
                'menu_name'     => 'Quartiers',
            ],
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'rewrite'           => [ 'slug' => 'quartier' ],
            'show_in_rest'      => true,
        ] );
    }

    // -------------------------------------------------------------------------
    // META BOXES
    // -------------------------------------------------------------------------
    public function add_meta_boxes() {
        add_meta_box(
            'immo_details',
            '📋 Détails du bien',
            [ $this, 'meta_box_callback' ],
            'bien_immo',
            'normal',
            'high'
        );
    }

    public function meta_box_callback( $post ) {
        wp_nonce_field( 'immo_save_meta', 'immo_nonce' );

        $prix      = get_post_meta( $post->ID, '_immo_prix',      true );
        $surface   = get_post_meta( $post->ID, '_immo_surface',   true );
        $pieces    = get_post_meta( $post->ID, '_immo_pieces',    true );
        $bains     = get_post_meta( $post->ID, '_immo_bains',     true );
        $statut    = get_post_meta( $post->ID, '_immo_statut',    true );
        $reference = get_post_meta( $post->ID, '_immo_reference', true );
        $contact   = get_post_meta( $post->ID, '_immo_contact',   true );
        ?>
        <style>
            .immo-meta-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; padding: 12px 0; }
            .immo-meta-field label { display: block; font-weight: 600; margin-bottom: 4px; color: #1d2327; font-size: 13px; }
            .immo-meta-field input,
            .immo-meta-field select { width: 100%; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 13px; }
            .immo-meta-field input:focus,
            .immo-meta-field select:focus { border-color: #2271b1; outline: 2px solid #2271b1; outline-offset: 0; }
            .immo-statut-vente  { background: #d63638; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: 600; }
            .immo-statut-location { background: #00a32a; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: 600; }
        </style>
        <div class="immo-meta-grid">
            <div class="immo-meta-field">
                <label for="immo_prix">💰 Prix (Ariary)</label>
                <input type="number" id="immo_prix" name="immo_prix"
                       value="<?php echo esc_attr( $prix ); ?>" placeholder="Ex: 250000000" min="0">
            </div>
            <div class="immo-meta-field">
                <label for="immo_surface">📐 Surface (m²)</label>
                <input type="number" id="immo_surface" name="immo_surface"
                       value="<?php echo esc_attr( $surface ); ?>" placeholder="Ex: 120" min="0">
            </div>
            <div class="immo-meta-field">
                <label for="immo_pieces">🛏 Nombre de pièces</label>
                <input type="number" id="immo_pieces" name="immo_pieces"
                       value="<?php echo esc_attr( $pieces ); ?>" placeholder="Ex: 4" min="0">
            </div>
            <div class="immo-meta-field">
                <label for="immo_bains">🚿 Salles de bain</label>
                <input type="number" id="immo_bains" name="immo_bains"
                       value="<?php echo esc_attr( $bains ); ?>" placeholder="Ex: 2" min="0">
            </div>
            <div class="immo-meta-field">
                <label for="immo_statut">🏷 Statut</label>
                <select id="immo_statut" name="immo_statut">
                    <option value=""      <?php selected( $statut, '' ); ?>>— Choisir —</option>
                    <option value="vente"    <?php selected( $statut, 'vente' ); ?>>À vendre</option>
                    <option value="location" <?php selected( $statut, 'location' ); ?>>À louer</option>
                    <option value="vendu"    <?php selected( $statut, 'vendu' ); ?>>Vendu</option>
                    <option value="loue"     <?php selected( $statut, 'loue' ); ?>>Loué</option>
                </select>
            </div>
            <div class="immo-meta-field">
                <label for="immo_reference">🔑 Référence</label>
                <input type="text" id="immo_reference" name="immo_reference"
                       value="<?php echo esc_attr( $reference ); ?>" placeholder="Ex: IT-2024-001">
            </div>
            <div class="immo-meta-field" style="grid-column: 1/-1;">
                <label for="immo_contact">📞 Contact propriétaire</label>
                <input type="text" id="immo_contact" name="immo_contact"
                       value="<?php echo esc_attr( $contact ); ?>" placeholder="Ex: +261 34 00 000 00">
            </div>
        </div>
        <?php
    }

    // -------------------------------------------------------------------------
    // SAVE META
    // -------------------------------------------------------------------------
    public function save_meta( $post_id ) {
        if ( ! isset( $_POST['immo_nonce'] ) ) return;
        if ( ! wp_verify_nonce( $_POST['immo_nonce'], 'immo_save_meta' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        $fields = [
            '_immo_prix'      => 'intval',
            '_immo_surface'   => 'intval',
            '_immo_pieces'    => 'intval',
            '_immo_bains'     => 'intval',
            '_immo_statut'    => 'sanitize_text_field',
            '_immo_reference' => 'sanitize_text_field',
            '_immo_contact'   => 'sanitize_text_field',
        ];

        $form_keys = [
            '_immo_prix'      => 'immo_prix',
            '_immo_surface'   => 'immo_surface',
            '_immo_pieces'    => 'immo_pieces',
            '_immo_bains'     => 'immo_bains',
            '_immo_statut'    => 'immo_statut',
            '_immo_reference' => 'immo_reference',
            '_immo_contact'   => 'immo_contact',
        ];

        foreach ( $fields as $meta_key => $sanitize_fn ) {
            $form_key = $form_keys[ $meta_key ];
            if ( isset( $_POST[ $form_key ] ) ) {
                update_post_meta( $post_id, $meta_key, $sanitize_fn( $_POST[ $form_key ] ) );
            }
        }
    }

    // -------------------------------------------------------------------------
    // ADMIN COLONNES PERSONNALISÉES
    // -------------------------------------------------------------------------
    public function admin_columns( $columns ) {
        $new = [];
        foreach ( $columns as $key => $val ) {
            $new[ $key ] = $val;
            if ( $key === 'title' ) {
                $new['immo_reference'] = '🔑 Réf.';
                $new['immo_statut']    = '🏷 Statut';
                $new['immo_prix']      = '💰 Prix';
                $new['immo_surface']   = '📐 Surface';
            }
        }
        return $new;
    }

    public function admin_column_content( $column, $post_id ) {
        switch ( $column ) {
            case 'immo_reference':
                $ref = get_post_meta( $post_id, '_immo_reference', true );
                echo $ref ? '<code>' . esc_html( $ref ) . '</code>' : '—';
                break;

            case 'immo_statut':
                $statut = get_post_meta( $post_id, '_immo_statut', true );
                $colors = [
                    'vente'    => '#d63638',
                    'location' => '#00a32a',
                    'vendu'    => '#8c8f94',
                    'loue'     => '#8c8f94',
                ];
                $labels = [
                    'vente'    => 'À vendre',
                    'location' => 'À louer',
                    'vendu'    => 'Vendu',
                    'loue'     => 'Loué',
                ];
                if ( $statut && isset( $colors[ $statut ] ) ) {
                    echo '<span style="background:' . $colors[ $statut ] . ';color:#fff;padding:2px 8px;border-radius:3px;font-size:11px;font-weight:600;">'
                        . esc_html( $labels[ $statut ] ) . '</span>';
                } else {
                    echo '—';
                }
                break;

            case 'immo_prix':
                $prix = get_post_meta( $post_id, '_immo_prix', true );
                echo $prix ? '<strong>' . number_format( $prix, 0, ',', ' ' ) . ' Ar</strong>' : '—';
                break;

            case 'immo_surface':
                $surface = get_post_meta( $post_id, '_immo_surface', true );
                echo $surface ? esc_html( $surface ) . ' m²' : '—';
                break;
        }
    }

    public function sortable_columns( $columns ) {
        $columns['immo_prix']    = 'immo_prix';
        $columns['immo_surface'] = 'immo_surface';
        return $columns;
    }

    // -------------------------------------------------------------------------
    // ADMIN FILTRES
    // -------------------------------------------------------------------------
    public function admin_filters( $post_type ) {
        if ( $post_type !== 'bien_immo' ) return;

        // Filtre statut
        $statut = isset( $_GET['immo_statut_filter'] ) ? sanitize_text_field( $_GET['immo_statut_filter'] ) : '';
        ?>
        <select name="immo_statut_filter">
            <option value="">— Tous les statuts —</option>
            <option value="vente"    <?php selected( $statut, 'vente' ); ?>>À vendre</option>
            <option value="location" <?php selected( $statut, 'location' ); ?>>À louer</option>
            <option value="vendu"    <?php selected( $statut, 'vendu' ); ?>>Vendu</option>
            <option value="loue"     <?php selected( $statut, 'loue' ); ?>>Loué</option>
        </select>
        <?php
    }

    public function parse_admin_filters( $query ) {
        global $pagenow;
        if ( $pagenow !== 'edit.php' ) return;
        if ( ! isset( $query->query_vars['post_type'] ) || $query->query_vars['post_type'] !== 'bien_immo' ) return;

        if ( ! empty( $_GET['immo_statut_filter'] ) ) {
            $query->query_vars['meta_key']   = '_immo_statut';
            $query->query_vars['meta_value'] = sanitize_text_field( $_GET['immo_statut_filter'] );
        }
    }

    // -------------------------------------------------------------------------
    // AJAX FILTER FRONTEND
    // -------------------------------------------------------------------------
    public function ajax_filter() {
        check_ajax_referer( 'immo_filter_nonce', 'nonce' );

        $type_bien = isset( $_POST['type_bien'] ) ? sanitize_text_field( $_POST['type_bien'] ) : '';
        $quartier  = isset( $_POST['quartier'] )  ? sanitize_text_field( $_POST['quartier'] )  : '';
        $statut    = isset( $_POST['statut'] )    ? sanitize_text_field( $_POST['statut'] )    : '';
        $prix_max  = isset( $_POST['prix_max'] )  ? intval( $_POST['prix_max'] )               : 0;

        $args = [
            'post_type'      => 'bien_immo',
            'posts_per_page' => 12,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        // Taxonomy query
        $tax_query = [];
        if ( $type_bien ) {
            $tax_query[] = [
                'taxonomy' => 'type_bien',
                'field'    => 'slug',
                'terms'    => $type_bien,
            ];
        }
        if ( $quartier ) {
            $tax_query[] = [
                'taxonomy' => 'quartier',
                'field'    => 'slug',
                'terms'    => $quartier,
            ];
        }
        if ( count( $tax_query ) > 1 ) {
            $tax_query['relation'] = 'AND';
        }
        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        // Meta query
        $meta_query = [];
        if ( $statut ) {
            $meta_query[] = [
                'key'     => '_immo_statut',
                'value'   => $statut,
                'compare' => '=',
            ];
        }
        if ( $prix_max > 0 ) {
            $meta_query[] = [
                'key'     => '_immo_prix',
                'value'   => $prix_max,
                'compare' => '<=',
                'type'    => 'NUMERIC',
            ];
        }
        if ( count( $meta_query ) > 1 ) {
            $meta_query['relation'] = 'AND';
        }
        if ( ! empty( $meta_query ) ) {
            $args['meta_query'] = $meta_query;
        }

        $query = new WP_Query( $args );
        $biens = [];

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $id = get_the_ID();
                $biens[] = [
                    'id'        => $id,
                    'title'     => get_the_title(),
                    'permalink' => get_permalink(),
                    'excerpt'   => get_the_excerpt(),
                    'image'     => get_the_post_thumbnail_url( $id, 'large' ) ?: '',
                    'prix'      => get_post_meta( $id, '_immo_prix',      true ),
                    'surface'   => get_post_meta( $id, '_immo_surface',   true ),
                    'pieces'    => get_post_meta( $id, '_immo_pieces',    true ),
                    'bains'     => get_post_meta( $id, '_immo_bains',     true ),
                    'statut'    => get_post_meta( $id, '_immo_statut',    true ),
                    'reference' => get_post_meta( $id, '_immo_reference', true ),
                    'type'      => wp_get_post_terms( $id, 'type_bien', [ 'fields' => 'names' ] ),
                    'quartier'  => wp_get_post_terms( $id, 'quartier',   [ 'fields' => 'names' ] ),
                ];
            }
            wp_reset_postdata();
        }

        wp_send_json_success( [
            'biens' => $biens,
            'count' => $query->found_posts,
        ] );
    }

    // -------------------------------------------------------------------------
    // AJAX CONTACT FORM
    // -------------------------------------------------------------------------
    public function ajax_contact() {
        check_ajax_referer( 'immo_filter_nonce', 'nonce' );

        $name      = sanitize_text_field( $_POST['name']    ?? '' );
        $email     = sanitize_email(      $_POST['email']   ?? '' );
        $phone     = sanitize_text_field( $_POST['phone']   ?? '' );
        $message   = sanitize_textarea_field( $_POST['message'] ?? '' );
        $sujet     = sanitize_text_field( $_POST['sujet']   ?? '' );
        $bien      = sanitize_text_field( $_POST['bien_title'] ?? '' );
        $ref       = sanitize_text_field( $_POST['bien_ref']   ?? '' );

        if ( empty($name) || empty($email) || empty($message) ) {
            wp_send_json_error( ['message' => 'Champs obligatoires manquants.'] );
        }
        if ( ! is_email($email) ) {
            wp_send_json_error( ['message' => 'Adresse email invalide.'] );
        }

        // Sauvegarder en DB
        $title = $bien ? $name . ' — ' . $bien . ' — ' . current_time('d/m/Y H:i')
                       : $name . ' — ' . current_time('d/m/Y H:i');

        $post_id = wp_insert_post( [
            'post_type'    => 'post',
            'post_title'   => $title,
            'post_status'  => 'private',
            'post_content' => $message,
            'post_author'  => 1,
            'meta_input'   => [
                '_immo_contact_email' => $email,
                '_immo_contact_phone' => $phone,
                '_immo_contact_sujet' => $sujet ?: 'Contact général',
                '_immo_contact_bien'  => $bien,
                '_immo_contact_ref'   => $ref,
            ],
        ] );

        // Envoyer email
        $to      = 'baymi312@gmail.com';
        $subject = $bien ? 'Demande bien : ' . $bien . ' — Immo Tana'
                         : 'Nouveau contact — Immo Tana';
        $body    = "Nom: $name\nEmail: $email\nTél: $phone\nSujet: $sujet\n";
        if ( $bien ) $body .= "Bien: $bien (Réf. $ref)\n";
        $body .= "\nMessage:\n$message";
        $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: $name <$email>" ];
        wp_mail( $to, $subject, $body, $headers );

        wp_send_json_success( ['message' => 'Message envoyé ! Nous vous contactons sous 2h.'] );
    }

    // -------------------------------------------------------------------------
    // SEO
    // -------------------------------------------------------------------------
    public function meta_description() {
        if ( is_front_page() ) {
            echo '<meta name="description" content="Immo Tana — Agence immobilière à Antananarivo. Achat, vente et location de biens immobiliers à Madagascar. Appartements, maisons, villas, terrains.">' . "\n";
        } elseif ( is_singular( 'bien_immo' ) ) {
            $excerpt = get_the_excerpt();
            if ( $excerpt ) {
                echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $excerpt ) ) . '">' . "\n";
            }
        }
    }

    // -------------------------------------------------------------------------
    // SMTP Gmail
    // -------------------------------------------------------------------------
    public function smtp_setup( $phpmailer ) {
        $phpmailer->isSMTP();
        $phpmailer->Host       = 'smtp.gmail.com';
        $phpmailer->SMTPAuth   = true;
        $phpmailer->Port       = 587;
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->Username   = 'bayane437@gmail.com';
        $phpmailer->Password   = defined('IMMO_SMTP_PASS') ? IMMO_SMTP_PASS : '';
        $phpmailer->From       = 'bayane437@gmail.com';
        $phpmailer->FromName   = 'Immo Tana';
    }
}

// Instanciation
new ImmoTana_Theme();


// =============================================================================
// HELPER : récupérer les biens (pour le fallback statique)
// =============================================================================
function immo_get_biens( $args = [] ) {
    $defaults = [
        'post_type'      => 'bien_immo',
        'posts_per_page' => 9,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    $query = new WP_Query( array_merge( $defaults, $args ) );

    $biens = [];
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $biens[] = [
                'id'        => $id,
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt'   => get_the_excerpt(),
                'image'     => get_the_post_thumbnail_url( $id, 'large' ) ?: '',
                'prix'      => get_post_meta( $id, '_immo_prix',      true ),
                'surface'   => get_post_meta( $id, '_immo_surface',   true ),
                'pieces'    => get_post_meta( $id, '_immo_pieces',    true ),
                'bains'     => get_post_meta( $id, '_immo_bains',     true ),
                'statut'    => get_post_meta( $id, '_immo_statut',    true ),
                'reference' => get_post_meta( $id, '_immo_reference', true ),
                'type'      => wp_get_post_terms( $id, 'type_bien', [ 'fields' => 'names' ] ),
                'quartier'  => wp_get_post_terms( $id, 'quartier',   [ 'fields' => 'names' ] ),
            ];
        }
        wp_reset_postdata();
    }

    // Fallback statique si aucun bien créé
    if ( empty( $biens ) ) {
        $biens = [
            [
                'id'        => 0,
                'title'     => 'Villa moderne à Ivandry',
                'permalink' => '#',
                'excerpt'   => 'Magnifique villa avec jardin, piscine et vue panoramique. Sécurisée, résidence fermée.',
                'image'     => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=600&q=80',
                'prix'      => 850000000,
                'surface'   => 280,
                'pieces'    => 5,
                'bains'     => 3,
                'statut'    => 'vente',
                'reference' => 'IT-2024-001',
                'type'      => ['Villa'],
                'quartier'  => ['Ivandry'],
            ],
            [
                'id'        => 0,
                'title'     => 'Appartement F3 — Ankadifotsy',
                'permalink' => '#',
                'excerpt'   => 'Bel appartement lumineux au 3ème étage, parking inclus, proche commerces.',
                'image'     => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&q=80',
                'prix'      => 1200000,
                'surface'   => 95,
                'pieces'    => 3,
                'bains'     => 1,
                'statut'    => 'location',
                'reference' => 'IT-2024-002',
                'type'      => ['Appartement'],
                'quartier'  => ['Ankadifotsy'],
            ],
            [
                'id'        => 0,
                'title'     => 'Maison à Ambohimanarina',
                'permalink' => '#',
                'excerpt'   => 'Belle maison familiale avec terrain arboré, garage 2 voitures, quartier calme.',
                'image'     => 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80',
                'prix'      => 420000000,
                'surface'   => 180,
                'pieces'    => 4,
                'bains'     => 2,
                'statut'    => 'vente',
                'reference' => 'IT-2024-003',
                'type'      => ['Maison'],
                'quartier'  => ['Ambohimanarina'],
            ],
            [
                'id'        => 0,
                'title'     => 'Studio meublé — Antanimena',
                'permalink' => '#',
                'excerpt'   => 'Studio entièrement meublé, idéal étudiant ou jeune professionnel. Charges comprises.',
                'image'     => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=600&q=80',
                'prix'      => 350000,
                'surface'   => 35,
                'pieces'    => 1,
                'bains'     => 1,
                'statut'    => 'location',
                'reference' => 'IT-2024-004',
                'type'      => ['Studio'],
                'quartier'  => ['Antanimena'],
            ],
            [
                'id'        => 0,
                'title'     => 'Terrain constructible — Alasora',
                'permalink' => '#',
                'excerpt'   => 'Terrain plat viabilisé avec titre foncier, accès route goudronnée, quartier résidentiel.',
                'image'     => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600&q=80',
                'prix'      => 180000000,
                'surface'   => 600,
                'pieces'    => 0,
                'bains'     => 0,
                'statut'    => 'vente',
                'reference' => 'IT-2024-005',
                'type'      => ['Terrain'],
                'quartier'  => ['Alasora'],
            ],
            [
                'id'        => 0,
                'title'     => 'Bureau commercial — Analakely',
                'permalink' => '#',
                'excerpt'   => 'Espace bureau en plein centre-ville, idéal pour agence ou cabinet. Climatisé, sécurisé.',
                'image'     => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80',
                'prix'      => 2500000,
                'surface'   => 120,
                'pieces'    => 4,
                'bains'     => 1,
                'statut'    => 'location',
                'reference' => 'IT-2024-006',
                'type'      => ['Bureau'],
                'quartier'  => ['Analakely'],
            ],
        ];
    }

    return $biens;
}

// =============================================================================
// HELPER : formater le prix
// =============================================================================
function immo_format_prix( $prix, $statut = '' ) {
    if ( ! $prix ) return 'Prix sur demande';
    $formatted = number_format( $prix, 0, ',', ' ' ) . ' Ar';
    if ( $statut === 'location' ) $formatted .= '/mois';
    return $formatted;
}

// =============================================================================
// HELPER : badge statut
// =============================================================================
function immo_statut_badge( $statut ) {
    $map = [
        'vente'    => [ 'label' => 'À vendre',  'class' => 'badge-vente' ],
        'location' => [ 'label' => 'À louer',   'class' => 'badge-location' ],
        'vendu'    => [ 'label' => 'Vendu',      'class' => 'badge-vendu' ],
        'loue'     => [ 'label' => 'Loué',       'class' => 'badge-loue' ],
    ];
    if ( ! isset( $map[ $statut ] ) ) return '';
    return '<span class="statut-badge ' . $map[ $statut ]['class'] . '">' . $map[ $statut ]['label'] . '</span>';
}
