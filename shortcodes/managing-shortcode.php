<?php

function shortcode_managing_profiles() {

    $profiles = fetch_firebase_data('uploads/data', true);

    if(empty($profiles)) {
        return '<p class="text-center py-10 text-gray-500">No profiles found.</p>';
    }

    // Fallback image
    $fallback_image = get_template_directory_uri() . '/assets/images/church.jpg';

    // Safe getter
    function safe_val($item, $key, $fallback = '') {
        return isset($item[$key]) && $item[$key] !== '' ? esc_html($item[$key]) : $fallback;
    }

    ob_start(); ?>

    <ul class="divide-y divide-gray-100 overflow-hidden bg-white shadow-sm outline outline-1 outline-gray-900/5 sm:rounded-xl">

    <?php foreach($profiles as $index => $item): 

        $photo = isset($item['Photo (90×90)']) && $item['Photo (90×90)'] != '' 
                    ? esc_url($item['Photo (90×90)']) 
                    : esc_url($fallback_image);

    ?>
        <li class="profile-card relative cursor-pointer flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-50 sm:px-6"
            data-photo="<?php echo $photo; ?>"
            data-name="<?php echo safe_val($item,'Name (as-is)'); ?>"
            data-designation="<?php echo safe_val($item,'Category'); ?>"
            data-phone="<?php echo safe_val($item,'Phone'); ?>"
            data-diocese="<?php echo safe_val($item,'Diocese'); ?>"
            data-parish="<?php echo safe_val($item,'Parish'); ?>"
            data-address="<?php echo safe_val($item,'Address (single line)'); ?>">

            <div class="flex min-w-0 gap-x-4 items-center">
                <img src="<?php echo $photo; ?>" 
                     alt="<?php echo esc_attr(safe_val($item,'Name (as-is)')); ?>" 
                     class="w-12 h-12 rounded-full  flex-none object-cover bg-gray-50" />

                <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold text-gray-900"><?php echo safe_val($item,'Name (as-is)'); ?></p>
                    <p class="text-sm text-gray-900"><?php echo safe_val($item,'Diocese'); ?></p>
                </div>
            </div>

            <div class="flex shrink-0 items-center gap-x-4">
                <div class="hidden sm:flex sm:flex-col sm:items-end">
                    <!-- <p class="text-sm text-gray-900"><?php echo safe_val($item,'Diocese'); ?></p> -->
                </div>
                <!-- <svg viewBox="0 0 20 20" class="size-1 text-gray-400">
                    <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"/>
                </svg> -->
            </div>
        </li>
    <?php endforeach; ?>
    </ul>


    <!-- Modal -->
    <div id="profileModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-[90%] max-w-lg p-6 relative">
            <button id="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-black text-xl">✕</button>

            <div class="flex flex-col items-center text-center gap-3">
                <img id="modalPhoto" class="w-24 h-24 rounded-full object-cover" src="" />
                <h2 id="modalName" class="text-lg font-bold"></h2>
                <p id="modalDesignation" class="text-sm text-gray-600"></p>
                <p id="modalPhone" class="text-sm text-gray-500"></p>
                <p id="modalDiocese" class="text-sm text-gray-500"></p>
                <p id="modalParish" class="text-sm text-gray-500"></p>
                <p id="modalAddress" class="text-sm text-gray-500"></p>
            </div>
        </div>
    </div>


<script>
const fallbackImage = "<?php echo esc_url($fallback_image); ?>";

document.querySelectorAll('.profile-card').forEach(card => {
    card.addEventListener('click', () => {

        let photo = card.dataset.photo || fallbackImage;

        document.getElementById('modalPhoto').src = photo;
        document.getElementById('modalName').innerText = card.dataset.name;
        document.getElementById('modalDesignation').innerText = card.dataset.designation;
        document.getElementById('modalPhone').innerText = " " + card.dataset.phone;
        document.getElementById('modalDiocese').innerText = " Diocese: " + card.dataset.diocese;
        document.getElementById('modalParish').innerText = " Parish: " + card.dataset.parish;
        document.getElementById('modalAddress').innerText = " " + card.dataset.address;

        document.getElementById('profileModal').classList.remove('hidden');
    });
});

document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('profileModal').classList.add('hidden');
});
</script>

<?php return ob_get_clean();
}

add_shortcode('managing_profiles', 'shortcode_managing_profiles');
