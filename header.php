<?php
/**
 * Responsive Header for JSO Theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header">
  <div class="header-container">
    <div class="site-branding">
      <?php 
        if ( has_custom_logo() ) {
          the_custom_logo();
        } else {
          echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-title">' . get_bloginfo('name') . '</a>';
        }
      ?>
    </div>

    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
      ☰
    </button>

    <nav id="site-navigation" class="main-navigation">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'menu-1',
          'container'      => false,
          'menu_class'     => 'nav-menu',
          'fallback_cb'    => false,
        ));
      ?>
    </nav>
  </div>
</header>

<main id="content">
