<?php
/* Template Name: Diocese Detail */
get_header();

if (isset($_GET['id'])) {
  $diocese_id = sanitize_text_field($_GET['id']);
  $diocese = fetch_firebase_data('diocese/' . $diocese_id);

  if (!$diocese) {
    echo '<p style="text-align:center;">Diocese not found.</p>';
  } else {
    $name = isset($diocese['dioceseName']) ? esc_html($diocese['dioceseName']) : 'Unknown Diocese';
    $phone = isset($diocese['phoneNumber']) ? esc_html($diocese['phoneNumber']) : 'N/A';

    // Count churches under this diocese
    $churches = fetch_firebase_data('church');
    $church_count = 0;
    $church_list = [];

    if ($churches && is_array($churches)) {
      foreach ($churches as $cid => $church) {
        if (isset($church['dioceseId']) && $church['dioceseId'] === $diocese_id) {
          $church_count++;
          $church_list[$cid] = $church;
        }
      }
    }

    // Fetch priests under this diocese
    $priests = fetch_firebase_data('clergy/priest');
    $priest_count = 0;
    $priest_list = [];

    if ($priests && is_array($priests)) {
      foreach ($priests as $pid => $priest) {
        if (isset($priest['dioceseId']) && $priest['dioceseId'] === $diocese_id) {
          $priest_count++;
          $priest_list[$pid] = $priest;
        }
      }
    }

    echo '<section style="padding:80px 10%;">';
    echo '<h1 style="font-size:32px; margin-bottom:20px;">' . $name . '</h1>';
    echo '<p><strong>Phone:</strong> ' . $phone . '</p>';
    echo '<p><strong>Total Churches:</strong> ' . $church_count . '</p>';
    echo '<p><strong>Total Priests:</strong> ' . $priest_count . '</p>';

    // Churches list
    if ($church_count > 0) {
      echo '<h2 style="margin-top:40px;">Churches</h2>';
      echo '<ul>';
      foreach ($church_list as $cid => $church) {
        $cname = isset($church['churchName']) ? esc_html($church['churchName']) : 'Unnamed';
        echo '<li>' . $cname . '</li>';
      }
      echo '</ul>';
    }

    // Priests list
    if ($priest_count > 0) {
      echo '<h2 style="margin-top:40px;">Priests</h2>';
      echo '<ul>';
      foreach ($priest_list as $pid => $priest) {
        $pname = isset($priest['fatherName']) ? esc_html($priest['fatherName']) : 'Unnamed';
        echo '<li>' . $pname . '</li>';
      }
      echo '</ul>';
    }

    // Diocese Metropolitan and Secretary
    if (isset($diocese['metropolitanId'])) {
      $metropolitan = fetch_firebase_data('clergy/metropolitans/' . $diocese['metropolitanId']);
      if ($metropolitan) {
        echo '<h3 style="margin-top:40px;">Metropolitan</h3>';
        echo '<p>' . esc_html($metropolitan['fatherName']) . '</p>';
      }
    }

    if (isset($diocese['secretaryId'])) {
      $secretary = fetch_firebase_data('clergy/preiest/' . $diocese['secretaryId']);
      if ($secretary) {
        echo '<h3>Secretary</h3>';
        echo '<p>' . esc_html($secretary['name']) . '</p>';
      }
    }

    echo '</section>';
  }
} else {
  echo '<p style="text-align:center;">No Diocese ID provided.</p>';
}

get_footer();
