<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header">
    <div class="container header-inner">

        <!-- Logo -->
        <div class="site-logo">
            <?php if ( has_custom_logo() ): ?>
                <?php the_custom_logo(); ?>
            <?php else: ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo-link">
                    <span class="logo-icon">
                        <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="38" height="38" rx="8" fill="#c9a84c"/>
                            <path d="M8 28V16l11-8 11 8v12H23v-7h-6v7H8z" fill="white"/>
                            <rect x="16" y="13" width="6" height="5" rx="1" fill="#c9a84c"/>
                        </svg>
                    </span>
                    <span class="logo-text">
                        <strong>Immo</strong><span>Tana</span>
                    </span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Navigation -->
        <nav class="site-nav" id="site-nav" aria-label="Navigation principale">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'nav-list',
                'container'      => false,
                'fallback_cb'    => function() {
                    echo '<ul class="nav-list">
                        <li><a href="#accueil">Accueil</a></li>
                        <li><a href="#biens">Nos biens</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#apropos">À propos</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>';
                },
            ]);
            ?>
        </nav>

        <!-- CTA -->
        <div class="header-actions">
            <a href="<?php echo esc_url( admin_url() ); ?>" class="btn-admin-header" title="Accès démo : admin / admin123">
                <i class="fas fa-lock"></i>
                <span>Admin</span>
            </a>
            <a href="https://wa.me/261348349886" target="_blank" rel="noopener" class="btn-whatsapp-header">
                <i class="fab fa-whatsapp"></i>
                <span>Contactez-nous</span>
            </a>
            <button class="burger-menu" id="burger-menu" aria-label="Menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</header>

<div class="nav-overlay" id="nav-overlay"></div>
