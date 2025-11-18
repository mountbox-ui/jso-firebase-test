<?php
// //  DIOCESE LIST SHORTCODE
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