<?php
/**
 * Template Name: Home Page
 * Description: Converted Next.js Home to WordPress
 */
get_header();
?>

<div class="home-hero">
  <?php get_template_part('template-parts/navbar'); ?>

  <div class="home-quote">
    <p>
      Come, let us bow down in worship, let us kneel<br> before the Lord our Maker.
    </p>
  </div>
</div>

<!-- ===== CHURCH INTRO SECTION ===== -->
<section class="church-intro">
  <div class="church-content">
    <h3>The Syriac Orthodox Church: Apostolic in Origin</h3>
    <div class="church-text">
      <p>
        The Syriac Orthodox Church, one of the oldest Christian denominations in the world,
        traces its origin to the apostolic era, specifically to St. Peter the Apostle,
        who established the Church in Antioch around 37 AD. This makes the Syriac Orthodox Church
        one of the few Churches that can claim unbroken continuity from the apostles themselves.
      </p>
      <p>
        In Syriac, the liturgical and ecclesiastical language of the Church, its name is
        ‘Idto Suryoyto Treeysath Shubho’, which translates to “The Holy Syriac Church.”
        Historically referred to in English as the “Syrian Orthodox Church,” the Holy Synod of the Church,
        in a session held from March 28 to April 3, 2000, approved the use of
        “Syriac Orthodox Church” for English-speaking countries to better reflect its heritage
        and clarify its distinct identity.
      </p>
      <p>
        The Church rightly prides itself on being one of the earliest and continuously existing
        Apostolic Churches. According to Acts 11:26, it was in Antioch that the followers of Jesus Christ
        were first called “Christians.” This highlights Antioch’s critical role in shaping Christian identity
        and leadership in the early centuries.
      </p>
    </div>
  </div>
</section>

<!-- ===== LEADERSHIP SECTION ===== -->
<section class="leadership-section">
  <div class="leadership-bg">
    <div class="leadership-container">
      <div class="leaders-grid">
        <div class="leader-card">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/HisHoliness.svg" alt="His Holiness Moran Mor Ignatius Aphrem II">
          <p class="leader-name">His Holiness Moran Mor Ignatius Aphrem II</p>
          <p class="leader-subtitle">Holy Patriarch of Antioch and all the East</p>
        </div>

        <div class="leader-card">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/AboonMor.svg" alt="H. B. Aboon Mor Baselios Joseph Catholicos">
          <p class="leader-name">H. B. Aboon Mor Baselios Joseph Catholicos</p>
          <p class="leader-subtitle">Under The Holy Apostolic See of Antioch and All The East</p>
        </div>
      </div>
    </div>
  </div>

  <!-- SECOND ROW -->
  <div class="leadership-inner">
    <div class="leader-box">
      <div class="leader-main">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/geevarghese.svg" alt="H.G. Geevarghese Mor Stephanos">
        <p class="leader-name">H.G. Geevarghese Mor Stephanos</p>
        <p class="leader-subtitle">Clergy Trust President</p>
      </div>

      <div class="leader-list">
        <div class="person-row">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fr_roy.svg" alt="Rev. Fr. Roy George">
          <div>
            <p class="person-name">Rev. Fr. Roy George</p>
            <p class="person-role">Sabha Vaideeka Trustee</p>
          </div>
        </div>

        <div class="person-row">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fr_john.svg" alt="Rev. Fr. John Iype Mangatt">
          <div>
            <p class="person-name">Rev. Fr. John Iype Mangatt</p>
            <p class="person-role">JSOC Trust Secretary</p>
          </div>
        </div>

        <div class="person-row">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fr_samson.svg" alt="Rev. Fr. Samson Kuriakose Meloth">
          <div>
            <p class="person-name">Rev. Fr. Samson Kuriakose Meloth</p>
            <p class="person-role">JSOC Trust Treasurer</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===== GALLERY SECTION ===== -->
<section class="gallery-section">
  <div class="gallery-container">
    <div class="gallery-header">
      <h2>Gallery</h2>
      <p>Explore our church community and events</p>
    </div>

    <!-- Auto-scroll image strip -->
    <div class="gallery-wrapper">
      <div class="gallery-track">
      <?php
        $gallery_images = array(
        get_template_directory_uri() . '/assets/images/HisHoliness.svg',
        get_template_directory_uri() . '/assets/images/AboonMor.svg',
        get_template_directory_uri() . '/assets/images/geevarghese.svg',
        get_template_directory_uri() . '/assets/images/fr_roy.svg',
        get_template_directory_uri() . '/assets/images/fr_john.svg',
        get_template_directory_uri() . '/assets/images/fr_samson.svg',
        get_template_directory_uri() . '/assets/images/hero_bg.svg',
        get_template_directory_uri() . '/assets/images/jso_logo_figma.svg',
        );
        ?>

        <section class="gallery-section">
            <div class="gallery-track">
            <?php foreach ($gallery_images as $index => $image) : ?>
                <div class="gallery-item">
                <img src="<?php echo esc_url($image); ?>" alt="Gallery image <?php echo $index + 1; ?>" class="gallery-image" />
                </div>
            <?php endforeach; ?>
            </div>
        </section>

      </div>
    </div>
  </div>

  <!-- Modal Popup -->
  <div id="galleryModal" class="gallery-modal">
    <span class="gallery-close">&times;</span>
    <img class="gallery-modal-content" id="galleryModalImg">
  </div>
</section>




<?php get_footer(); ?>
