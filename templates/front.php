<?php
/**
 * Template Name: Home Page
 * Description: Clean homepage showing Firebase sections
 */
get_header();
?>

<main id="primary" class="site-main">

  <section class="home-intro" style="text-align:center; padding: 80px 20px;">
    <h4>Welcome to</h4>
    <h1 style="max-width: 600px; margin: 0 auto; color: #666;">
      JSO community
    </h1>
  </section>

  <?php echo do_shortcode('[firebase_bigfathers]'); ?>


  <section class="home-intro" style="text-align:center; padding: 80px 20px 0 20px;">
    <h1>Clergy</h1>
    <!-- <p style="max-width: 600px; margin: 0 auto; color: #666;">
      Explore the community’s directory of churches and big fathers — live data powered by Firebase.
    </p> -->
  </section>

  <section class="home-sections" style="display:grid; grid-template-columns: repeat(auto-fit,minmax(280px,1fr)); gap:40px; text-align:center;">
    <div class="home-card" style="background:rgba(230, 227, 255, 1); border-radius:16px; padding:40px;">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Metropolitan.png' ); ?>" alt="Big Fathers" style="width:80px; margin-bottom:15px;">
      <h2>Metropolitans</h2>
      <p style="color:#666;">Meet the Metropolitans across the JSO community.</p>
      <a href="<?php echo site_url('metropolitans'); ?>" class="view-btn">View Metropolitans</a>
    </div>

    <div class="home-card" style="background:rgba(255, 239, 179, 1); border-radius:16px; padding:40px;">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Corepiscopa.png' ); ?>" alt="Church" style="width:80px; margin-bottom:15px;">
      <h2>Cor-Episcopa</h2>
      <p style="color:#666;">Find all Cor-Episcopa listed across the JSO community.</p>
      <a href="<?php echo site_url('/cor-episcopas'); ?>" class="view-btn">View Cor-Episcopa</a>
    </div>

    <div class="home-card" style="background:rgba(255, 233, 227, 1); border-radius:16px; padding:40px;">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Ramban.png' ); ?>" alt="Church" style="width:80px; margin-bottom:15px;">
      <h2>Rambans</h2>
      <p style="color:#666;">Find all Rambans listed across the JSO community.</p>
      <a href="<?php echo site_url('/rambans'); ?>" class="view-btn">View Rambans</a>
    </div>

    <div class="home-card" style="background:rgba(215, 245, 229, 1); border-radius:16px; padding:40px;">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Preist.png' ); ?>" alt="Church" style="width:80px; margin-bottom:15px;">
      <h2>Priests</h2>
      <p style="color:#666;">Find all Priests listed across the JSO community.</p>
      <a href="<?php echo site_url('/priests'); ?>" class="view-btn">View Priests</a>
    </div>

    <div class="home-card" style="background:rgba(229, 240, 255, 1); border-radius:16px; padding:40px;">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Deacons.png' ); ?>" alt="Church" style="width:80px; margin-bottom:15px;">
      <h2>Deacons</h2>
      <p style="color:#666;">Find all Deacons listed across the JSO community.</p>
      <a href="<?php echo site_url('/deacons'); ?>" class="view-btn">View Deacons</a>
    </div>
  </section>

  <section class="home-intro" style="text-align:center; padding: 80px 20px 0 20px;">
    <h1>Diocese</h1>
    <!-- <p style="max-width: 600px; margin: 0 auto; color: #666;">
      Explore the community’s directory of churches and big fathers — live data powered by Firebase.
    </p> -->
  </section>

  <section>
    <div class="home-card" style="background:radial-gradient(74.87% 74.87% at 50% 50%, #FFF 0%, #E5F0FF 100%); border-radius:16px; box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:40px;">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Vector.png' ); ?>" alt="Church" style="width:80px; margin-bottom:15px;">
      <h2>Diocese</h2>
      <p style="color:#666;">Find all Diocese listed across the JSO community.</p>
      <a href="<?php echo site_url('/Dioceses'); ?>" class="view-btn">View Diocese</a>
    </div>
  </section>
</main>


<?php get_footer(); ?>
