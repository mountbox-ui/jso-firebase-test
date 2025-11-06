<?php
/**
 * Template Name: Home Page
 * Description: Clean homepage showing Firebase sections
 */
get_header();
?>

<main id="primary" class="site-main">
  <section class="home-intro" style="text-align:center; padding: 80px 20px;">
    <h1>Welcome to JSO Community</h1>
    <p style="max-width: 600px; margin: 0 auto; color: #666;">
      Explore the community’s directory of churches and big fathers — live data powered by Firebase.
    </p>
  </section>

  <section class="home-sections" style="display:grid; grid-template-columns: repeat(auto-fit,minmax(280px,1fr)); gap:40px; padding:60px 10%; text-align:center;">
    <div class="home-card" style="background:#fff; border-radius:16px; box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:40px;">
      <img src="<?php echo get_template_directory_uri(); ?>./wp-content/uploads/2025/11/Vector.png" alt="Church" style="width:80px; margin-bottom:15px;">
      <h2>Church Directory</h2>
      <p style="color:#666;">Find all churches listed across the JSO community.</p>
      <a href="<?php echo site_url('/churches'); ?>" class="view-btn">View Churches</a>
    </div>

    <div class="home-card" style="background:#fff; border-radius:16px; box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:40px;">
      <img src="<?php echo get_template_directory_uri("./wp-content/uploads/2025/11/B-Father.png"); ?>" alt="Big Fathers" style="width:80px; margin-bottom:15px;">
      <h2>Big Fathers</h2>
      <p style="color:#666;">Meet the big fathers, see their profiles and contact info.</p>
      <a href="<?php echo site_url('big-fathers'); ?>" class="view-btn">View Big Fathers</a>
    </div>
  </section>
</main>

<?php get_footer(); ?>
