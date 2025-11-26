<?php
// shortcodes/managing-shortcode.php

if ( ! function_exists( 'jso_managing_profiles_shortcode' ) ) {

    function jso_managing_profiles_shortcode() {

        // This one uses the FIRST Firebase project
        $profiles = fetch_firebase_data('/data', true);

        if ( empty($profiles) ) {
            return '<p class="text-center py-10 text-gray-500">No profiles found.</p>';
        }

        // Fallback image
        $fallback_image = get_template_directory_uri() . '/assets/images/church.jpg';

        // Safe getter (closure so it won't clash with other files)
        $get_value = function($item, $field, $fallback = '') {
            return isset($item[$field]) && $item[$field] !== '' ? esc_html($item[$field]) : $fallback;
        };

        ob_start(); ?>

        <ul class="divide-y divide-gray-200 overflow-hidden bg-white shadow-md sm:rounded-xl">

        <?php foreach ($profiles as $item):

            $photo = !empty($item['Photo (90×90)'])
                ? esc_url($item['Photo (90×90)'])
                : esc_url($fallback_image);
        ?>
            <li class="profile-card flex justify-between items-center cursor-pointer px-4 py-5 hover:bg-gray-50 transition"
                data-photo="<?php echo $photo; ?>"
                data-name="<?php echo $get_value($item,'Name (as-is)'); ?>"
                data-designation="<?php echo $get_value($item,'Category'); ?>"
                data-phone="<?php echo $get_value($item,'Phone'); ?>"
                data-diocese="<?php echo $get_value($item,'Diocese'); ?>"
                data-parish="<?php echo $get_value($item,'Parish'); ?>"
                data-address="<?php echo $get_value($item,'Address (single line)'); ?>">

                <div class="flex items-center gap-4 min-w-0">
                    <img 
                        src="<?php echo $photo; ?>" 
                        class="w-12 h-12 rounded-full object-cover shadow-sm"
                        alt="<?php echo $get_value($item,'Name (as-is)'); ?>"
                    >

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">
                            <?php echo $get_value($item,'Name (as-is)'); ?>
                        </p>
                        <p class="text-xs text-gray-600 truncate">
                            <?php echo $get_value($item,'Diocese'); ?>
                        </p>
                    </div>
                </div>

                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="w-1 h-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg> -->
            </li>
        <?php endforeach; ?>
        </ul>

        <!-- Modal -->
        <div id="profileModal" class="hidden fixed inset-0 bg-black/50 z-50 flex justify-center items-center">
            <div class="bg-white rounded-lg shadow-lg w-[90%] max-w-lg p-6 relative">

                <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">
                    ✕
                </button>

                <div class="text-center space-y-3">
                    <img id="modalPhoto" class="w-24 h-24 rounded-full object-cover mx-auto" src="">
                    <h2 id="modalName" class="text-lg font-bold"></h2>
                    <p id="modalDesignation" class="text-sm text-gray-600"></p>

                    <div class="space-y-1 text-sm text-gray-700 pt-2">
                        <p id="modalPhone"></p>
                        <p id="modalDiocese"></p>
                        <p id="modalParish"></p>
                        <p id="modalAddress"></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
        const fallbackImage = "<?php echo esc_url($fallback_image); ?>";

        document.querySelectorAll('.profile-card').forEach(card => {
            card.addEventListener('click', () => {
                const modal = document.getElementById('profileModal');

                document.getElementById('modalPhoto').src = card.dataset.photo || fallbackImage;
                document.getElementById('modalName').innerText = card.dataset.name;
                document.getElementById('modalDesignation').innerText = card.dataset.designation;
                document.getElementById('modalPhone').innerText = " " + card.dataset.phone;
                document.getElementById('modalDiocese').innerText = " Diocese: " + card.dataset.diocese;
                document.getElementById('modalParish').innerText = " Parish: " + card.dataset.parish;
                document.getElementById('modalAddress').innerText = " " + card.dataset.address;

                modal.classList.remove('hidden');
            });
        });

        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('profileModal').classList.add('hidden');
        });
        </script>

        <?php
        return ob_get_clean();
    }

    // Register shortcode name used in the page: [managing_profiles]
    add_shortcode('managing_profiles', 'jso_managing_profiles_shortcode');
}
