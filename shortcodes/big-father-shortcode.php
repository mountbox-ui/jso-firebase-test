<?php
//  BIGFATHERS SHORTCODE
function shortcode_firebase_bigfathers() {

    $bigfathers = fetch_firebase_data('bigFathers'); 
    $placeholder = get_template_directory_uri() . '/assets/images/bigfather.jpg';

    if (empty($bigfathers)) {
        return '<p class="text-center text-gray-500">No Big Fathers found.</p>';
    }

    ob_start();
?>
<div class="container mx-auto !max-w-none px-6">
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

<?php foreach ($bigfathers as $id => $father): 

    $name      = $father['fatherName'] ?? 'Unknown';
    $position  = $father['positions'] ?? 'No Role Assigned';
    $email     = $father['email'] ?? '';
    $phone     = $father['phone'] ?? '';
    $image     = $father['image'] ?? $placeholder;

?>
  
  <div class="col-span-1 flex flex-col divide-y divide-gray-200 rounded-lg bg-white text-center shadow hover:shadow-lg transition p-4">

      <div class="flex flex-col items-center flex-1 p-4">
        <img 
            src="<?php echo esc_url($image); ?>" 
            alt="<?php echo esc_attr($name); ?>" 
            class="mx-auto w-24 h-24 rounded-full object-cover bg-gray-200 outline outline-1 outline-black/5"
            onerror="this.onerror=null;this.src='<?php echo esc_js($placeholder); ?>';"
        />

        <h3 class="mt-4 text-base font-medium text-gray-900">
          <?php echo esc_html($name); ?>
        </h3>

        <p class="text-sm text-gray-500 mt-1">
          <?php echo esc_html($position); ?>
        </p>
      </div>

      <div class="flex divide-x divide-gray-200">
        
        <a href="mailto:<?php echo esc_attr($email); ?>" 
           class="flex-1 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100 flex items-center justify-center gap-2">
          <span>📧</span> Email
        </a>

        <a href="tel:<?php echo esc_attr($phone); ?>" 
           class="flex-1 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100 flex items-center justify-center gap-2">
          <span>📞</span> Call
        </a>

      </div>

  </div>

<?php endforeach; ?>

</div>
</div>

<?php 
return ob_get_clean();
}
add_shortcode('firebase_bigfathers', 'shortcode_firebase_bigfathers');
