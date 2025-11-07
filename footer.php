<?php
/**
 * Clean Footer for JSO Theme
 */
?>

<footer id="colophon" style="background:#111; color:#fff; text-align:center; padding:25px 10px; margin-top:60px;">
  <div class="footer-content" style="max-width:900px; margin:auto;">
    <p style="margin:0; font-size:15px; letter-spacing:0.3px;">
      © <?php echo date('Y'); ?> <strong><?php bloginfo('name'); ?></strong>. All rights reserved.
    </p>
    <p style="margin:5px 0 0 0; font-size:14px; opacity:0.8;">
      Designed & Developed by <a href="https://mountbox.in" target="_blank" style="color:#fff; text-decoration:underline;">MountBox</a>
    </p>
  </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const menuToggle = document.querySelector(".menu-toggle");
  const navigation = document.querySelector(".main-navigation");

  menuToggle.addEventListener("click", function() {
    navigation.classList.toggle("active");
  });
});
</script>


<?php wp_footer(); ?>
</body>
</html>
