<?php
// includes/firebase-shortcodes.php

//  CHURCH SHORTCODE
error_log('firebase-shortcodes.php loaded');

// function shortcode_firebase_churches() {
//     $churches = fetch_firebase_data('church');

//     if (empty($churches)) {
//         return '<p style="text-align:center>No church data found.</p>';
//     }

//     $output = '<div class="church-list">';
//     foreach ($churches as $key => $church) {
//         $output .= '<div class="church-item" style="margin-bottom:20px;">';
//         $output .= '<h3>' . esc_html($church['churchName'] ?? 'Unknown Church') . '</h3>';
//         $output .= '<p><strong>Diocese:</strong> ' . esc_html($church['diocese'] ?? 'N/A') . '</p>';
//         // if ($image) {
//         //     $output .= '<img src="' . $image . '" alt="' . $name . '">';
//         // }

//         $output .= '</div>';
//     }
//     $output .= '</div>';

//     return $output;
// }
// add_shortcode('firebase_churches', 'shortcode_firebase_churches');

function shortcode_firebase_churches() {
    $churches = fetch_firebase_data('church');

    if (empty($churches)) {
        return '<p>No church data found.</p>';
    }

    // Collect unique dioceses
    $dioceses = [];
    foreach ($churches as $church) {
        if (!empty($church['diocese'])) {
            $dioceses[] = $church['diocese'];
        }
    }
    $dioceses = array_unique($dioceses);
    sort($dioceses);

    // Start output
    $output = '<div class="church-search-container">';

    // Search + Filter Row
    $output .= '
    <div class="search-filter-row" style="display:flex;gap:10px;align-items:center;margin-bottom:20px;">
        <input type="text" id="churchSearchInput" placeholder="Search by Diocese..." style="flex:1;padding:10px;border:1px solid #ccc;border-radius:5px;">
        <div class="filter-dropdown-wrapper" style="flex:1;position:relative;">
            <button id="filterToggle" style="width:100%;padding:10px 15px;border:1px solid #ccc;background:#fff;border-radius:5px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;">
                <span id="filterText">Select Diocese</span>
                <span style="font-size:14px;">&#9660;</span>
            </button>
            <select id="churchDioceseFilter" style="position:absolute;top:100%;left:0;width:100%;margin-top:5px;padding:8px;border:1px solid #ccc;border-radius:5px;display:none;z-index:10;">
                <option value="">All Dioceses</option>';
    foreach ($dioceses as $diocese) {
        $output .= '<option value="' . esc_attr($diocese) . '">' . esc_html($diocese) . '</option>';
    }
    $output .= '</select>
        </div>
    </div>';

    // Church List
    $output .= '<div id="churchList" class="church-list">';
    foreach ($churches as $church) {
        $output .= '<div class="church-item" 
                        data-name="' . esc_attr($church['churchName'] ?? 'Unknown Church') . '" 
                        data-diocese="' . esc_attr($church['diocese'] ?? 'N/A') . '" 
                        data-primary-vicar="' . esc_attr($church['vicarAt'] ?? 'N/A') . '" 
                        data-image="' . esc_attr(get_template_directory_uri() . '/assets/images/church.jpg') . '" 
                        style="margin-bottom:20px;padding:15px;border:1px solid #ddd;border-radius:8px;cursor:pointer;">
                        <h3 class="church-name" style="margin-bottom:5px;">' . esc_html($church['churchName'] ?? 'Unknown Church') . '</h3>
                        <p><strong>Diocese:</strong> <span class="church-diocese">' . esc_html($church['diocese'] ?? 'N/A') . '</span></p>
                    </div>';
    }
    $output .= '</div>';

    // Modal
    $output .= '
<div id="churchModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:9999;">
    <div style="background:#fff;padding:20px;border-radius:10px;max-width:500px;width:200%;position:relative;text-align:center;">
        <span id="modalClose" style="position:absolute;top:10px;right:15px;font-size:20px;font-weight:bold;cursor:pointer;">&times;</span>
        <img id="modalChurchImage" src="' . get_template_directory_uri() . '/assets/images/church.jpg" alt="Church Image" style="max-width:120px;margin-bottom:15px;border-radius:50%;">
        <h2 id="modalChurchName" style="margin-bottom:10px;"></h2>
        <p><strong>Diocese:</strong> <span id="modalDiocese"></span></p>
        <p><strong>Primary Vicar:</strong> <span id="modalPrimaryVicar"></span></p>
    </div>
</div>';
    // JS
    $output .= "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('churchSearchInput');
        const dioceseFilter = document.getElementById('churchDioceseFilter');
        const items = document.querySelectorAll('#churchList .church-item');
        const filterToggle = document.getElementById('filterToggle');
        const filterText = document.getElementById('filterText');
        const modal = document.getElementById('churchModal');
        const modalClose = document.getElementById('modalClose');
        const modalChurchName = document.getElementById('modalChurchName');
        const modalDiocese = document.getElementById('modalDiocese');
        const modalPrimaryVicar = document.getElementById('modalPrimaryVicar');
        const modalChurchImage = document.getElementById('modalChurchImage');

        function filterChurches() {
            const searchVal = searchInput.value.toLowerCase();
            const selectedDiocese = dioceseFilter.value.toLowerCase();
            items.forEach(item => {
                const name = item.querySelector('.church-name').textContent.toLowerCase();
                const diocese = item.querySelector('.church-diocese').textContent.toLowerCase();
                item.style.display = (name.includes(searchVal) && (selectedDiocese === '' || diocese === selectedDiocese)) ? '' : 'none';
            });
        }

        searchInput.addEventListener('keyup', filterChurches);
        filterToggle.addEventListener('click', function() {
            dioceseFilter.style.display = (dioceseFilter.style.display === 'block') ? 'none' : 'block';
        });
        dioceseFilter.addEventListener('change', function() {
            filterText.textContent = dioceseFilter.value || 'Select Diocese';
            filterChurches();
            dioceseFilter.style.display = 'none';
        });
        document.addEventListener('click', function(e) {
            if (!filterToggle.contains(e.target) && !dioceseFilter.contains(e.target)) dioceseFilter.style.display = 'none';
        });

        items.forEach(item => {
            item.addEventListener('click', function() {
                modalChurchName.textContent = this.dataset.name;
                modalDiocese.textContent = this.dataset.diocese;
                modalPrimaryVicar.textContent = this.dataset.primaryVicar;
                modalChurchImage.src = this.dataset.image;
                modal.style.display = 'flex';
            });
        });

        modalClose.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', (e) => { if(e.target === modal) modal.style.display = 'none'; });
    });
    </script>";

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
      $vicar = isset($meta['vicarAt']) ? esc_html($meta['vicarAt']) : '';
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

//  COR-EPISCOPA LIST SHORTCODE
function jso_corepiscopa_list_shortcode() {
  $corepiscopa = fetch_firebase_data('clergy/corepiscopa');

  ob_start();

  if (empty($corepiscopa)) {
    echo '<p style="text-align:center;">No Cor-Episcopa found.</p>';
  } else {
    echo '<section style="padding:80px 10%; text-align:center;">';
    echo '<h2 style="font-size:32px; margin-bottom:40px;">Cor-Episcopa</h2>';
    echo '<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:30px;">';

    foreach ($corepiscopa as $id => $meta) {
      $name  = isset($meta['fatherName']) ? esc_html($meta['fatherName']) : 'Unknown';
      $vicar = isset($meta['vicarAt']) ? esc_html($meta['vicarAt']) : '';
      $image = isset($meta['image']) ? esc_url($meta['image']) : '';
      $detail_url = site_url('/corepiscopa/?id=' . urlencode($id));

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
add_shortcode('corepiscopa_list', 'jso_corepiscopa_list_shortcode');


//  RAMBAN LIST SHORTCODE
function jso_ramban_list_shortcode() {
  $ramban = fetch_firebase_data('clergy/ramban');

  ob_start();

  if (empty($ramban)) {
    echo '<p style="text-align:center;">No Ramban found.</p>';
  } else {
    echo '<section style="padding:80px 10%; text-align:center;">';
    echo '<h2 style="font-size:32px; margin-bottom:40px;">Ramban</h2>';
    echo '<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:30px;">';

    foreach ($ramban as $id => $meta) {
      $name  = isset($meta['fatherName']) ? esc_html($meta['fatherName']) : 'Unknown';
    //   $vicar = isset($meta['vicarAt']) ? esc_html($meta['vicarAt']) : 'Unknown';
      $image = isset($meta['image']) ? esc_url($meta['image']) : '';
      $detail_url = site_url('/ramban/?id=' . urlencode($id));

      echo '<div style="background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.05); padding:20px;">';
      if ($image) {
        echo '<img src="' . $image . '" alt="' . $name . '" style="width:100%; height:240px; object-fit:cover; border-radius:12px; margin-bottom:15px;">';
      }
      echo '<h3 style="font-size:20px; margin-bottom:8px;">' . $name . '</h3>';
    //   echo '<p style="font-size:20px; margin-bottom:8px;">' . $vicar . '</p>';
      echo '<a href="' . esc_url($detail_url) . '" style="color:#0073aa; text-decoration:none;">View Details →</a>';
      echo '</div>';
    }

    echo '</div>';
    echo '</section>';
  }

  return ob_get_clean();
}
add_shortcode('ramban_list', 'jso_ramban_list_shortcode');


//  PRIEST LIST SHORTCODE
function jso_priest_list_shortcode() {
  $priest = fetch_firebase_data('clergy/priest');

  ob_start();

  if (empty($priest)) {
    echo '<p style="text-align:center;">No Priest found.</p>';
  } else {
    echo '<section style="padding:80px 10%; text-align:center;">';
    echo '<h2 style="font-size:32px; margin-bottom:40px;">Priests</h2>';
    echo '<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:30px;">';

    foreach ($priest as $id => $meta) {
      $name  = isset($meta['fatherName']) ? esc_html($meta['fatherName']) : 'Unknown';
      $vicar = isset($meta['vicarAt']) ? esc_html($meta['vicarAt']) : '';
      $image = isset($meta['image']) ? esc_url($meta['image']) : '';
      $detail_url = site_url('/priest/?id=' . urlencode($id));

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
add_shortcode('priest_list', 'jso_priest_list_shortcode');


//  DEACONS LIST SHORTCODE
function jso_deacons_list_shortcode() {
  $deacons = fetch_firebase_data('clergy/deacons');

  ob_start();

  if (empty($deacons)) {
    echo '<p style="text-align:center;">No Deacons found.</p>';
  } else {
    echo '<section style="padding:80px 10%; text-align:center;">';
    echo '<h2 style="font-size:32px; margin-bottom:40px;">Deacons</h2>';
    echo '<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:30px;">';

    foreach ($deacons as $id => $meta) {
      $name  = isset($meta['fatherName']) ? esc_html($meta['fatherName']) : 'Unknown';
      $vicar = isset($meta['vicarAt']) ? esc_html($meta['vicarAt']) : '';
      $image = isset($meta['image']) ? esc_url($meta['image']) : '';
      $detail_url = site_url('/deacon/?id=' . urlencode($id));

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
add_shortcode('deacons_list', 'jso_deacons_list_shortcode');


//  DIOCESE LIST SHORTCODE

function jso_diocese_list_shortcode() {
  $dioceses = fetch_firebase_data('diocese');

  ob_start();

  if (empty($dioceses)) {
    echo '<p style="text-align:center;">No Diocese found.</p>';
  } else {
    echo '<section style="padding:60px 10%; display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:30px;">';

    foreach ($dioceses as $id => $diocese) {
      $name = isset($diocese['dioceseName']) ? esc_html($diocese['dioceseName']) : 'Unnamed Diocese';
      $churchCount = isset($diocese['churches']) && is_array($diocese['churches']) ? count($diocese['churches']) : 0;
      $detail_url = site_url('/diocese-details/?id=' . urlencode($id));

      echo '<div style="background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); padding:30px; text-align:center;">';
      echo '<h2 style="font-size:22px; margin-bottom:10px;">' . $name . '</h2>';
      echo '<p style="color:#666;">' . $churchCount . ' Churches</p>';
     $detail_url = site_url('/diocese/?id=' . urlencode($id));
     echo '<a href="' . esc_url($detail_url) . '" style="display:inline-block; margin-top:10px; color:#0073aa; text-decoration:none; font-weight:500;">View Details →</a>';
      echo '</div>';
    }

    echo '</section>';
  }

  return ob_get_clean();
}
add_shortcode('diocese_list', 'jso_diocese_list_shortcode');


