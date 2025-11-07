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

    // Search and filter row with equal width
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

    // Church list
    $output .= '<div id="churchList" class="church-list">';
    foreach ($churches as $key => $church) {
        $output .= '<div class="church-item" style="margin-bottom:20px;padding:15px;border:1px solid #ddd;border-radius:8px;">';
        $output .= '<h3 class="church-name" style="margin-bottom:5px;">' . esc_html($church['churchName'] ?? 'Unknown Church') . '</h3>';
        $output .= '<p><strong>Diocese:</strong> <span class="church-diocese">' . esc_html($church['diocese'] ?? 'N/A') . '</span></p>';
        $output .= '</div>';
    }
    $output .= '</div></div>';

    // JavaScript for search + filter + toggle + updating button text
    $output .= "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('churchSearchInput');
        const dioceseFilter = document.getElementById('churchDioceseFilter');
        const items = document.querySelectorAll('#churchList .church-item');
        const filterToggle = document.getElementById('filterToggle');
        const filterText = document.getElementById('filterText');

        function filterChurches() {
    const filter = searchInput.value.toLowerCase();
    const selectedDiocese = dioceseFilter.value.toLowerCase();

    items.forEach(item => {
        const name = item.querySelector('.church-name').textContent.toLowerCase();
        const diocese = item.querySelector('.church-diocese').textContent.toLowerCase();

        // Only use the name and diocese for filtering
        const matchesSearch = name.includes(filter);
        const matchesDiocese = selectedDiocese === '' || diocese === selectedDiocese;

        if(matchesSearch && matchesDiocese) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}


        searchInput.addEventListener('keyup', filterChurches);

        // Toggle filter dropdown
        filterToggle.addEventListener('click', function() {
            dioceseFilter.style.display = (dioceseFilter.style.display === 'block') ? 'none' : 'block';
        });

        // Update button text when selection changes
        dioceseFilter.addEventListener('change', function() {
            filterText.textContent = dioceseFilter.value || 'Select Diocese';
            filterChurches();
            dioceseFilter.style.display = 'none';
        });

        // Click outside to close dropdown
        document.addEventListener('click', function(e) {
            if (!filterToggle.contains(e.target) && !dioceseFilter.contains(e.target)) {
                dioceseFilter.style.display = 'none';
            }
        });
    });
    </script>
    ";

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

