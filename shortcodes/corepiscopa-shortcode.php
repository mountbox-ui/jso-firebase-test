<?php
//  COR-EPISCOPA LIST SHORTCODE
function jso_corepiscopa_list_shortcode()
{

    $corepiscopa = fetch_firebase_data('clergy/corepiscopa');

    $placeholder = get_template_directory_uri() . '/assets/images/corepiscopa.jpg';
    $uniq = uniqid('metro_');

    if (empty($corepiscopa)) {
        return '<p class="text-center text-gray-500">No Cor-episcopa found.</p>';
    }

    ob_start();
    ?>

        <!-- SEARCH BAR -->
        <div class="my-6">
            <div class="mb-6 sm:my-5 max-w-lg mx-auto">
                <input id="<?php echo $uniq; ?>_search" type="text" placeholder="Search Cor-episcopa..."
                    class="w-full px-8 py-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 my-6" />
            </div>
        </div>

        <ul id="<?php echo $uniq; ?>_list" role="list" class="divide-y divide-gray-100 transition-all">

            <?php foreach ($corepiscopa as $id => $m):
                $name = $m['fatherName'] ?? 'Unknown Corepiscopa';
                $role = $m['vicarAt'] ?? 'Corepiscopa';
                $image = $m['image'] ?? $placeholder;
                $email = $m['emailId'] ?? '';
                $phone = $m['phoneNumber'] ?? '';
                $diocese = $m['vicarAt'] ?? '';
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
            No matching corepiscopa found.
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
add_shortcode('corepiscopa_list', 'jso_corepiscopa_list_shortcode');