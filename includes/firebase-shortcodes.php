<?php

//  RAMBAN LIST SHORTCODE
function jso_ramban_list_shortcode()
{
    $ramban = fetch_firebase_data('clergy/ramban');

    $placeholder = get_template_directory_uri() . '/assets/images/ramban.jpg';
    $uniq = uniqid('metro_');

    if (empty($ramban)) {
        return '<p class="text-center text-gray-500">No Ramban found.</p>';
    }

    ob_start();
    ?>
            <!-- SEARCH BAR -->
            <div class="my-6">
                <div class="mb-6 sm:my-5 max-w-lg mx-auto">
                    <input id="<?php echo $uniq; ?>_search" type="text" placeholder="Search Ramban..."
                        class="w-full px-8 py-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 my-6" />
                </div>
            </div>

            <ul id="<?php echo $uniq; ?>_list" role="list" class="divide-y divide-gray-100 transition-all">

                <?php foreach ($ramban as $id => $m):
                    $name = $m['fatherName'] ?? 'Unknown ramban';
                    $role = $m['vicarAt'] ?? '';
                    $image = $m['image'] ?? $placeholder;
                    $email = $m['emailId'] ?? '';
                    $phone = $m['phoneNumber'] ?? '';
                    $diocese = $m[''] ?? '';
                    ?>

                    <li class="metro-item flex justify-between gap-x-6 py-5 cursor-pointer hover:bg-gray-50 transition"
                        data-name="<?php echo strtolower(esc_attr($name)); ?>"
                        data-role="<?php echo strtolower(esc_attr($role)); ?>" data-modal-name="<?php echo esc_attr($name); ?>"
                        data-modal-role="<?php echo esc_attr($role); ?>" data-image="<?php echo esc_attr($image); ?>"
                        data-email="<?php echo esc_attr($email); ?>" data-phone="<?php echo esc_attr($phone); ?>"
                        data-diocese="<?php echo esc_attr($diocese); ?>">
                        <div class="flex min-w-0 gap-x-4">
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($name); ?>"
                                class="w-12 h-12 flex-none rounded-full object-cover bg-gray-100"
                                onerror="this.onerror=null;this.src='<?php echo esc_js($placeholder); ?>';" />
                            <div class="min-w-0 flex-auto">
                                <p class="text-sm font-semibold text-gray-900"><?php echo esc_html($name); ?></p>
                                <p class="mt-1 truncate text-xs text-gray-500"><?php echo esc_html($role); ?></p>
                            </div>
                        </div>
                    </li>

                <?php endforeach; ?>

            </ul>

            <p id="<?php echo $uniq; ?>_no_results" class="hidden text-center text-gray-500 mt-4">
                No matching ramban found.
            </p>


            <!-- MODAL (unchanged except IDs) -->
            <div id="<?php echo $uniq; ?>_modal"
                class="fixed flex hidden inset-0 bg-black bg-opacity-50  items-center justify-center z-50">
                <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl relative">
                    <button
                        class="jso-modal-close absolute top-3 right-3 p-3 text-2xl font-bold text-gray-600 hover:text-black p-10 bg-[#808080] rounded-full">&times;</button>
                    <div class="flex justify-center mb-6">
                        <img id="<?php echo $uniq; ?>_image" src="<?php echo esc_url($placeholder); ?>"
                            class="w-24 h-24 rounded-full object-cover bg-gray-100" />
                    </div>

                    <div class="mt-4 gap-4">
                        <h4 id="<?php echo $uniq; ?>_name" class="text-xl font-semibold text-gray-900 text-center"></h4>
                        <div class="text-sm text-gray-700 space-y-1 text-center">
                            <p id="<?php echo $uniq; ?>_role"></p>
                            <p id="<?php echo $uniq; ?>_email"></p>
                            <p id="<?php echo $uniq; ?>_phone"></p>
                            <p id="<?php echo $uniq; ?>_diocese" class="font-medium"></p>
                        </div>
                    </div>
                </div>


                <script>
                    (function () {
                        const modal = document.getElementById('<?php echo $uniq; ?>_modal');
                        const searchInput = document.getElementById('<?php echo $uniq; ?>_search');
                        const list = document.getElementById('<?php echo $uniq; ?>_list');
                        const noResults = document.getElementById('<?php echo $uniq; ?>_no_results');

                        // OPEN MODAL
                        function openModal(li) {
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');

                            document.getElementById('<?php echo $uniq; ?>_name').textContent = li.dataset.modalName;
                            document.getElementById('<?php echo $uniq; ?>_role').textContent = li.dataset.modalRole;
                            document.getElementById('<?php echo $uniq; ?>_email').textContent = li.dataset.email ? "Email: " + li.dataset.email : "";
                            document.getElementById('<?php echo $uniq; ?>_phone').textContent = li.dataset.phone ? "Phone: " + li.dataset.phone : "";
                            document.getElementById('<?php echo $uniq; ?>_diocese').textContent = li.dataset.diocese ? "Diocese: " + li.dataset.diocese : "";
                            document.getElementById('<?php echo $uniq; ?>_image').src = li.dataset.image;
                        }

                        // CLOSE MODAL
                        function closeModal() {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }

                        // Click handlers
                        document.querySelectorAll(".metro-item").forEach(li => {
                            li.addEventListener("click", () => openModal(li));
                        });

                        modal.addEventListener("click", (e) => {
                            if (e.target === modal) closeModal();
                        });

                        modal.querySelectorAll(".jso-modal-close").forEach(btn =>
                            btn.addEventListener("click", closeModal)
                        );


                        // REALTIME SEARCH
                        searchInput.addEventListener("input", () => {
                            const query = searchInput.value.toLowerCase().trim();
                            let visibleCount = 0;

                            document.querySelectorAll(".metro-item").forEach(item => {
                                const match =
                                    item.dataset.name.includes(query) ||
                                    item.dataset.role.includes(query);

                                item.style.display = match ? "flex" : "none";
                                if (match) visibleCount++;
                            });

                            noResults.classList.toggle("hidden", visibleCount > 0);
                        });

                    })();
                </script>


                <?php
                return ob_get_clean();
}
add_shortcode('ramban_list', 'jso_ramban_list_shortcode');


//  PRIEST LIST SHORTCODE
function jso_priest_list_shortcode()
{
    $priest = fetch_firebase_data('clergy/priest');

    ob_start();

    if (empty($priest)) {
        echo '<p style="text-align:center;">No Priest found.</p>';
    } else {
        echo '<section style="padding:80px 10%; text-align:center;">';
        echo '<h2 style="font-size:32px; margin-bottom:40px;">Priests</h2>';
        echo '<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:30px;">';

        foreach ($priest as $id => $meta) {
            $name = isset($meta['fatherName']) ? esc_html($meta['fatherName']) : 'Unknown';
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
function jso_deacons_list_shortcode()
{
    $deacons = fetch_firebase_data('clergy/deacons');

    ob_start();

    if (empty($deacons)) {
        echo '<p style="text-align:center;">No Deacons found.</p>';
    } else {
        echo '<section style="padding:80px 10%; text-align:center;">';
        echo '<h2 style="font-size:32px; margin-bottom:40px;">Deacons</h2>';
        echo '<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:30px;">';

        foreach ($deacons as $id => $meta) {
            $name = isset($meta['fatherName']) ? esc_html($meta['fatherName']) : 'Unknown';
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


// //  DIOCESE LIST SHORTCODE

// function jso_diocese_list_shortcode() {
//   $dioceses = fetch_firebase_data('diocese');

//   ob_start();

//   if (empty($dioceses)) {
//     echo '<p style="text-align:center;">No Diocese found.</p>';
//   } else {
//     echo '<section style="padding:60px 10%; display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:30px;">';

//     foreach ($dioceses as $id => $diocese) {
//       $name = isset($diocese['dioceseName']) ? esc_html($diocese['dioceseName']) : 'Unnamed Diocese';
//       $churchCount = isset($diocese['churches']) && is_array($diocese['churches']) ? count($diocese['churches']) : 0;
//       $detail_url = site_url('/diocese-details/?id=' . urlencode($id));

//       echo '<div style="background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); padding:30px; text-align:center;">';
//       echo '<h2 style="font-size:22px; margin-bottom:10px;">' . $name . '</h2>';
//       echo '<p style="color:#666;">' . $churchCount . ' Churches</p>';
//      $detail_url = site_url('/diocese/?id=' . urlencode($id));
//      echo '<a href="' . esc_url($detail_url) . '" style="display:inline-block; margin-top:10px; color:#0073aa; text-decoration:none; font-weight:500;">View Details →</a>';
//       echo '</div>';
//     }

//     echo '</section>';
//   }

//   return ob_get_clean();
// }
// add_shortcode('diocese_list', 'jso_diocese_list_shortcode');



// Place in functions.php or in your includes and require it
function jso_diocese_list_shortcode()
{
    $dioceses = fetch_firebase_data('diocese'); // your existing fetch function
    $placeholder = get_template_directory_uri() . '/assets/images/church.jpg'; // fallback
    $uniq = uniqid('jso_');

    ob_start();





    if (empty($dioceses) || !is_array($dioceses)) {
        echo '<p class="text-center">No Diocese found.</p>';
        return ob_get_clean();
    }

    // Build the UL wrapper and each li matching the Tailwind structure you gave

    echo '
    <div class="mx-auto my-6 mt-5 mb-10">
        <input 
            type="text" 
            id="dioceseSearch"
            placeholder="Search Diocese..."
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />
    </div>
';
    echo '<ul role="list" class="divide-y divide-gray-100">';

    foreach ($dioceses as $id => $diocese) {
        // defensive reading - adjust keys if your DB uses different keys
        $name = isset($diocese['dioceseName']) ? $diocese['dioceseName'] : (isset($diocese['name']) ? $diocese['name'] : 'Unnamed Diocese');
        $image = isset($diocese['image']) && !empty($diocese['image']) ? $diocese['image'] : $placeholder;

        // counts
        $churchCount = isset($diocese['churches']) && is_array($diocese['churches']) ? count($diocese['churches']) : 0;
        $priestCount = isset($diocese['priests']) && is_array($diocese['priests']) ? count($diocese['priests']) : 0;

        // output li item (Tailwind layout)
        echo '<li class="flex justify-between gap-x-6 py-5" data-diocese-id="' . esc_attr($id) . '">';
        echo '<div class="flex min-w-0 gap-x-4">';
        // image
        echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($name) . '" class="w-12 h-12 flex-none rounded-full bg-gray-50" onerror="this.onerror=null;this.src=\'' . esc_url($placeholder) . '\';" />';
        // text block
        echo '<div class="min-w-0 flex-auto">';
        echo '<p class="text-sm/6 font-semibold text-gray-900">' . esc_html($name) . '</p>';
        // echo '<p class="mt-1 truncate text-xs/5 text-gray-500">' . esc_html($churchCount) . ' Churches • ' . esc_html($priestCount) . ' Priests</p>';
        echo '</div>';
        echo '</div>';

        // right column: show counts as clickable controls
        echo '<div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">';
        echo '<div class="mt-1 flex items-center gap-x-4">';
        // clickable church count
        echo '<button type="button" class="jso-open-sidebar text-xs/5 text-blue-600 font-medium underline p-10 bg-inherit border-rounded" data-action="churches" data-diocese="' . esc_attr($id) . '">' . esc_html($churchCount) . ' Churches</button>';
        // clickable priest count
        echo '<button type="button" class="jso-open-sidebar text-xs/5 text-blue-600 font-medium underline" data-action="priests" data-diocese="' . esc_attr($id) . '">' . esc_html($priestCount) . ' Priests</button>';
        echo '</div>';
        echo '</div>';
        echo '</li>';
    }

    echo '</ul>';

    // Sidebar + Modal markup (hidden by default). We'll append data and manage via JS.
    // Add container elements only once.
    ?>
                <!-- Sidebar (offcanvas) -->

                <div id="<?php echo esc_attr($uniq); ?>_sidebar" class="jso-sidebar hidden">
                    <div class="absolute inset-0 bg-black bg-opacity-50 jso-sidebar-backdrop"></div>

                    <div class="drawer shadow-xl">
                        <div class="flex items-center justify-between p-4 border-b bg-white sticky top-0 z-[100]">
                            <button type="button"
                                class="jso-close-sidebar absolute rounded-full top-3 right-3 p-3 text-2xl font-bold text-gray-600 hover:text-black">&times;</button>
                            <h3 id="<?php echo esc_attr($uniq); ?>_sidebar_title"
                                class="text-lg font-semibold text-gray-900 w-[290px]">
                                List</h3>
                        </div>

                        <div id="<?php echo esc_attr($uniq); ?>_sidebar_content" class="drawer-content p-4">
                            <!-- populated by JS -->
                        </div>
                    </div>
                </div>


                <!-- Modal -->
                <div id="<?php echo esc_attr($uniq); ?>_modal"
                    class="jso-modal fixed inset-0 hidden items-center justify-center">
                    <div class="fixed inset-0 bg-black bg-opacity-50 jso-modal-backdrop"></div>

                    <div class="modal-box bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                        <button
                            class="jso-close-modal absolute rounded-full top-3 right-3 p-3 text-2xl font-bold text-gray-600 hover:text-black">&times;</button>
                        <div class="mt-6 flex gap-4 items-center">
                            <img id="<?php echo esc_attr($uniq); ?>_modal_image"
                                class="w-12 h-12 rounded-full object-cover" />
                            <div>
                                <h4 id="<?php echo esc_attr($uniq); ?>_modal_title" class="text-lg font-semibold"></h4>
                                <p id="<?php echo esc_attr($uniq); ?>_modal_diocese" class="text-sm text-gray-500"></p>
                                <div id="<?php echo esc_attr($uniq); ?>_modal_body" class="mt-2 text-sm text-gray-700">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    /* -------- SIDEBAR -------- */
                    .jso-sidebar {
                        display: none;
                        position: fixed;
                        inset: 0;
                        z-index: 999999;
                        /* Higher than navbar */
                    }

                    .jso-sidebar.show {
                        display: block;
                    }

                    /* Drawer (right sliding panel) */
                    .jso-sidebar .drawer {
                        position: absolute;
                        top: 0;
                        right: 0;
                        bottom: 0;
                        width: 380px;
                        background: #fff;
                        transform: translateX(100%);
                        transition: transform .30s ease;
                        display: flex;
                        flex-direction: column;
                        overflow: hidden;
                    }

                    .jso-sidebar.show .drawer {
                        transform: translateX(0);
                    }

                    /* Scrollable content */
                    .jso-sidebar .drawer-content {
                        overflow-y: auto;
                        height: calc(100vh - 60px);
                    }


                    /* -------- MODAL -------- */
                    .jso-modal {
                        display: none;
                        z-index: 9999999;
                    }

                    .jso-modal.show {
                        display: flex;
                    }

                    /* Fix close button */
                    .jso-modal .modal-box {
                        position: relative;
                    }

                    .jso-modal .jso-close-modal {
                        position: absolute;
                        top: 10px;
                        right: 14px;
                    }
                </style>

                <script>
                    (function () {
                        var uniq = "<?php echo esc_js($uniq); ?>";
                        var placeholder = "<?php echo esc_js($placeholder); ?>";
                        var data = <?php echo wp_json_encode($dioceses); ?>;

                        function openSidebar(action, dioceseId) {
                            var sidebar = document.getElementById(uniq + '_sidebar');
                            var slide = sidebar.querySelector('.absolute.right-0');
                            var titleEl = document.getElementById(uniq + '_sidebar_title');
                            var contentEl = document.getElementById(uniq + '_sidebar_content');

                            var diocese = data[dioceseId];
                            if (!diocese) { titleEl.textContent = 'Not found'; contentEl.innerHTML = '<p class="text-sm text-gray-500">No data available.</p>'; showSidebar(); return; }

                            var list = [];
                            if (action === 'churches') { titleEl.textContent = 'Churches in ' + (diocese.dioceseName || diocese.name || 'Diocese'); list = diocese.churches ? Object.values(diocese.churches) : []; }
                            if (action === 'priests') { titleEl.textContent = 'Priests in ' + (diocese.dioceseName || diocese.name || 'Diocese'); list = diocese.priests ? Object.values(diocese.priests) : []; }

                            if (!list.length) { contentEl.innerHTML = '<p class="text-sm text-gray-500">No items found.</p>'; showSidebar(); return; }

                            var ul = document.createElement('ul'); ul.className = 'divide-y divide-gray-100';
                            list.forEach(function (item) {
                                var itemName = item.name || item.churchName || item.priestName || 'Unnamed';
                                var itemImage = item.image || item.photo || placeholder;
                                var li = document.createElement('li');
                                li.className = 'flex justify-between gap-x-6 py-5 cursor-pointer';
                                li.innerHTML = '\
                                                        <div class="flex min-w-0 gap-x-4">\
                                                          <img src="'+ itemImage + '" alt="' + itemName + '" class="size-12 flex-none rounded-full bg-gray-50 w-12 h-12 object-cover" onerror="this.onerror=null;this.src=\'' + placeholder + '\';"/>\
                                                          <div class="min-w-0 flex-auto">\
                                                            <p class="text-sm/6 font-semibold text-gray-900">'+ itemName + '</p>\
                                                            <p class="mt-1 truncate text-xs/5 text-gray-500">'+ (item.email || item.contact || '') + '</p>\
                                                          </div>\
                                                        </div>';

                                li.addEventListener('click', function () { openModal(item, diocese); });
                                ul.appendChild(li);
                            });

                            contentEl.innerHTML = ''; contentEl.appendChild(ul);
                            showSidebar();
                        }

                        function showSidebar() { var sb = document.getElementById(uniq + '_sidebar'); sb.classList.add('show'); }
                        function closeSidebar() { var sb = document.getElementById(uniq + '_sidebar'); sb.classList.remove('show'); }
                        document.querySelectorAll('.jso-open-sidebar').forEach(function (btn) { btn.addEventListener('click', function (e) { e.preventDefault(); openSidebar(btn.dataset.action, btn.dataset.diocese); }); });
                        document.querySelectorAll('.jso-close-sidebar').forEach(function (btn) { btn.addEventListener('click', function () { closeSidebar(); }); });
                        document.querySelectorAll('.jso-sidebar-backdrop').forEach(function (bd) { bd.addEventListener('click', function () { closeSidebar(); }); });

                        // Modal functions
                        function openModal(item, diocese) {
                            var modal = document.getElementById(uniq + '_modal');
                            modal.classList.add('show');
                            document.getElementById(uniq + '_modal_title').textContent = item.name || item.churchName || item.priestName || 'Unnamed';
                            var img = document.getElementById(uniq + '_modal_image'); img.src = item.image || item.photo || placeholder; img.alt = item.name || item.churchName || item.priestName || '';
                            document.getElementById(uniq + '_modal_diocese').textContent = diocese ? ('Diocese: ' + (diocese.dioceseName || diocese.name || '')) : '';
                            var body = document.getElementById(uniq + '_modal_body'); body.innerHTML = '';
                            if (item.address) body.innerHTML += '<p class="text-sm text-gray-700"><strong>Address:</strong> ' + item.address + '</p>';
                            if (item.contact) body.innerHTML += '<p class="text-sm text-gray-700 mt-1"><strong>Contact:</strong> ' + item.contact + '</p>';
                            if (item.email) body.innerHTML += '<p class="text-sm text-gray-700 mt-1"><strong>Email:</strong> ' + item.email + '</p>';
                        }
                        function closeModal() { document.getElementById(uniq + '_modal').classList.remove('show'); }
                        document.querySelectorAll('.jso-close-modal').forEach(function (btn) { btn.addEventListener('click', function () { closeModal(); }); });
                        document.querySelectorAll('.jso-modal-backdrop').forEach(function (bd) { bd.addEventListener('click', function () { closeModal(); }); });
                    })();

                    document.addEventListener("DOMContentLoaded", () => {
                        const searchInput = document.getElementById("dioceseSearch");
                        const rows = document.querySelectorAll("ul[role='list'] > li");

                        searchInput.addEventListener("input", (e) => {
                            const term = e.target.value.toLowerCase();

                            rows.forEach(li => {
                                const text = li.textContent.toLowerCase();
                                li.style.display = text.includes(term) ? "flex" : "none";
                            });
                        });
                    });
                </script>
                <?php

                return ob_get_clean();
}
add_shortcode('diocese_list', 'jso_diocese_list_shortcode');



