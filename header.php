<?php
/**
 * Clean Header for JSO Theme
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

<header id="masthead" style="display:flex; justify-content:space-between; align-items:center; padding:20px 8%; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.05); position:sticky; top:0; z-index:100;">
  
  <div class="site-branding" style="display:flex; align-items:center; gap:12px;">
    <?php 
      if ( has_custom_logo() ) {
        the_custom_logo();
      } else {
        echo '<a href="' . esc_url( home_url( '' ) ) . '" style="font-size:22px; font-weight:700; color:#222; text-decoration:none;">' . get_bloginfo('name') . '</a>';
      }
    ?>
  </div>

  <nav id="site-navigation" style="display:flex; gap:25px;">
    <?php
      wp_nav_menu(array(
        'theme_location' => 'menu-1',
        'container'      => false,
        'items_wrap'     => '<ul id="%1$s" class="%2$s" style="display:flex; list-style:none; margin:0; padding:0; gap:25px;">%3$s</ul>',
        'link_before'    => '<span style="font-weight:500; font-size:16px; color:#222; text-decoration:none;">',
        'link_after'     => '</span>',
      ));
    ?>
  </nav>

</header>

<main id="content" style="padding-top:40px;">
