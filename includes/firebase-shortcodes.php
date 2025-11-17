<?php
//  CHURCH SHORTCODE
error_log('firebase-shortcodes.php loaded');

function shortcode_firebase_churches() {
    $churches = fetch_firebase_data('church');

    if (empty($churches)) {
        return '<p class="text-center text-gray-500">No church data found.</p>';
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

    ob_start();
    ?>

<div class="max-w-3xl mx-auto">

    <!-- Search + Filter Row -->
    <div class="flex gap-3 mb-5 mt-5">
        <!-- Search -->
        <input 
            type="text" 
            id="churchSearchInput" 
            placeholder="Search Church or Diocese..." 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:outline-none"
        />

        <!-- Dropdown -->
        <select 
            id="dioceseFilter" 
            class="px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-gray-500"
        >
            <option value="">All Dioceses</option>
            <?php foreach ($dioceses as $d): ?>
                <option value="<?php echo esc_attr($d); ?>">
                    <?php echo esc_html($d); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- LIST -->
    <ul role="list" id="churchList" class="divide-y divide-gray-200">

        <?php foreach ($churches as $church): ?>

            <?php 
                $name = $church['churchName'] ?? 'Unknown Church';
                $diocese = $church['diocese'] ?? '';
                $vicar = $church['vicarAt'] ?? '';

                // ⬇ Fallback image from Firebase → if empty → use church.jpg
                $image = (!empty($church['image'])) 
                    ? $church['image'] 
                    : get_template_directory_uri() . '/assets/images/church.jpg';
            ?>

            <li class="flex justify-between gap-x-6 py-5 cursor-pointer hover:bg-gray-50 transition item-row"
                data-name="<?php echo esc_attr(strtolower($name)); ?>"
                data-diocese="<?php echo esc_attr(strtolower($diocese)); ?>"
                data-vicar="<?php echo esc_attr($vicar); ?>"
                data-image="<?php echo esc_attr($image); ?>"
            >
                <div class="flex min-w-0 gap-x-4">
                    <img src="<?php echo esc_url($image); ?>" 
                        class="h-12 w-12 flex-none rounded-full bg-gray-100 object-cover" />

                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold text-gray-900">
                            <?php echo esc_html($name); ?>
                        </p>
                    </div>
                </div>

                <div class="hidden sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm text-gray-700">
                        <?php echo esc_html($diocese); ?>
                    </p>
                </div>
            </li>

        <?php endforeach; ?>
    </ul>
</div>


<!-- MODAL -->
<div id="churchModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl relative">

        <button id="modalClose" class="absolute rounded-full top-3 right-3 p-3 text-2xl font-bold text-gray-600 hover:text-black">
            &times;
        </button>

        <img id="modalImage" class="w-24 h-24 mx-auto rounded-full mb-4 object-cover" />

        <h2 id="modalName" class="text-xl font-semibold text-gray-900 text-center"></h2>

        <!-- <p class="text-center text-gray-700 mt-2">
            <strong>Diocese:</strong> <span id="modalDiocese"></span>
        </p>

        <p class="text-center text-gray-700 mt-1">
            <strong>Vicar:</strong> <span id="modalVicar"></span>
        </p> -->

        <p class="text-center text-gray-700 mt-2">
            <span id="modalDiocese"></span>
        </p>

        <p class="text-center text-gray-700 mt-1">
            <span id="modalVicar"></span>
        </p>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {

    // Elements
    const searchInput = document.getElementById('churchSearchInput');
    const dioceseFilter = document.getElementById('dioceseFilter');
    const items = document.querySelectorAll('#churchList .item-row');

    const modal = document.getElementById('churchModal');
    const closeBtn = document.getElementById('modalClose');
    const modalName = document.getElementById('modalName');
    const modalDiocese = document.getElementById('modalDiocese');
    const modalVicar = document.getElementById('modalVicar');
    const modalImage = document.getElementById('modalImage');

    // FILTER FUNCTION
    function filterList() {
        const search = searchInput.value.toLowerCase();
        const selected = dioceseFilter.value.toLowerCase();

        items.forEach(item => {
            const name = item.dataset.name;
            const diocese = item.dataset.diocese;

            const matchSearch = name.includes(search) || diocese.includes(search);
            const matchDiocese = selected === "" || diocese === selected;

            item.style.display = (matchSearch && matchDiocese) ? "flex" : "none";
        });
    }

    searchInput.addEventListener("input", filterList);
    dioceseFilter.addEventListener("change", filterList);

    // OPEN MODAL
    items.forEach(item => {
        item.addEventListener("click", () => {
            modalName.textContent = item.dataset.name;
            modalDiocese.textContent = item.dataset.diocese;
            modalVicar.textContent = item.dataset.vicar;
            modalImage.src = item.dataset.image;

            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });
    });

    // CLOSE MODAL
    closeBtn.addEventListener("click", () => modal.classList.add("hidden"));
    modal.addEventListener("click", (e) => {
        if (e.target === modal) modal.classList.add("hidden");
    });

});
</script>

<?php
return ob_get_clean();
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


