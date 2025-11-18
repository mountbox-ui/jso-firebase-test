<?php
//  CHURCH SHORTCODE
error_log('firebase-shortcodes.php loaded');

function shortcode_firebase_churches()
{
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
            <input type="text" id="churchSearchInput" placeholder="Search Church or Diocese..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:outline-none" />

            <!-- Dropdown -->
            <select id="dioceseFilter"
                class="px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-gray-500">
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
                    data-diocese="<?php echo esc_attr(strtolower($diocese)); ?>" data-vicar="<?php echo esc_attr($vicar); ?>"
                    data-image="<?php echo esc_attr($image); ?>">
                    <div class="flex min-w-0 gap-x-4">
                        <img src="<?php echo esc_url($image); ?>"
                            class="h-12 w-12 flex-none rounded-full bg-gray-100 object-cover" />

                        <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold text-gray-900 mb-0">
                                <?php echo esc_html($name); ?>
                            </p>
                            <p class="text-sm text-gray-700">
                                <?php echo esc_html($diocese); ?>
                            </p>
                        </div>
                    </div>

                    <!-- <div class="hidden sm:flex sm:flex-col sm:items-end">
                        <p class="text-sm text-gray-700">
                            <?php echo esc_html($diocese); ?>
                        </p>
                    </div> -->
                </li>

            <?php endforeach; ?>
        </ul>
    </div>


    <!-- MODAL -->
    <div id="churchModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl relative">

            <button id="modalClose"
                class="absolute rounded-full top-3 right-3 p-3 text-2xl font-bold text-gray-600 hover:text-black p-10 bg-[#808080] rounded-full">
                &times;
            </button>

            <img id="modalImage" class="w-24 h-24 mx-auto rounded-full mb-4 object-cover" />

            <h2 id="modalName" class="text-xl font-semibold text-gray-900 text-center"></h2>
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