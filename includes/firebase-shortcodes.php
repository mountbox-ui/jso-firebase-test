<?php
// includes/firebase-shortcodes.php

//  CHURCH SHORTCODE
error_log('firebase-shortcodes.php loaded');

function shortcode_firebase_churches() {
    $churches = fetch_firebase_data('church');

    if (empty($churches)) {
        return '<p style="text-align:center>No church data found.</p>';
    }

    $output = '<div class="church-list">';
    foreach ($churches as $key => $church) {
        $output .= '<div class="church-item" style="margin-bottom:20px;">';
        $output .= '<h3>' . esc_html($church['churchName'] ?? 'Unknown Church') . '</h3>';
        $output .= '<p><strong>Diocese:</strong> ' . esc_html($church['diocese'] ?? 'N/A') . '</p>';
        // if ($image) {
        //     $output .= '<img src="' . $image . '" alt="' . $name . '">';
        // }

        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('firebase_churches', 'shortcode_firebase_churches');


//  BIGFATHERS SHORTCODE
function shortcode_firebase_bigfathers() {
    $bigfathers = fetch_firebase_data('bigFathers'); // Firebase node name

    if (empty($bigfathers)) {
        return '<p style="text-align:center>No Big Fathers found.</p>';
    }

    $output = '<div class="bigfathers-grid">';
    foreach ($bigfathers as $key => $father) {
        $name = esc_html($father['fatherName'] ?? 'Unknown');
        $positions = esc_html($father['positions'] ?? 'N/A');
        $image = esc_url($father['image'] ?? '');

        $output .= '<div class="bigfather-card">';
        if ($image) {
            $output .= '<img src="' . $image . '" alt="' . $name . '">';
        }
        $output .= '<h3>' . $name . '</h3>';
        $output .= '<p><strong></strong> ' . $positions . '</p>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('firebase_bigfathers', 'shortcode_firebase_bigfathers');


//  METROPOLITANS LIST SHORTCODE
function jso_metropolitans_list_shortcode() {
  $metropolitans = fetch_firebase_data('clergy/metropolitans');

  ob_start();

  if (empty($metropolitans)) {
    echo '<p style="text-align:center;">No Metropolitans found.</p>';
  } else {
    echo '<section style="padding:80px 10%; text-align:center;">';
    echo '<h2 style="font-size:32px; margin-bottom:40px;">Metropolitans</h2>';
    echo '<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:30px;">';

    foreach ($metropolitans as $id => $meta) {
      $name  = isset($meta['fatherName']) ? esc_html($meta['fatherName']) : 'Unknown';
      $vicar = isset($meta['vicarAt']) ? esc_html($meta['vicarAt']) : 'Unknown';
      $image = isset($meta['image']) ? esc_url($meta['image']) : '';
      $detail_url = site_url('/metropolitan/?id=' . urlencode($id));

      echo '<div style="background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.05); padding:20px;">';
      if ($image) {
        echo '<img src="' . $image . '" alt="' . $name . '" style="width:100%; height:240px; object-fit:cover; border-radius:12px; margin-bottom:15px;">';
      }
      echo '<h3 style="font-size:20px; margin-bottom:8px;">' . $name . '</h3>';
      echo '<p style="font-size:20px; margin-bottom:8px;">' . $vicar . '</p>';
      echo '<a href="' . esc_url($detail_url) . '" style="color:#0073aa; text-decoration:none;">View Details →</a>';
      echo '</div>';
    }

    echo '</div>';
    echo '</section>';
  }

  return ob_get_clean();
}
add_shortcode('metropolitans_list', 'jso_metropolitans_list_shortcode');

