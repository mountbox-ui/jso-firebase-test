const searchInput = document.getElementById("searchInput");
const dioceseFilter = document.getElementById("dioceseFilter");
const items = document.querySelectorAll(".church-item");

// Live filtering
function filterList() {
    const searchText = searchInput.value.toLowerCase();
    const dioceseText = dioceseFilter.value.toLowerCase();

    items.forEach(item => {
        const name = item.dataset.name;
        const dio = item.dataset.diocese;

        const matchesSearch = name.includes(searchText);
        const matchesDiocese = dioceseText === "" || dio === dioceseText;

        item.style.display = matchesSearch && matchesDiocese ? "flex" : "none";
    });
}

// searchInput.addEventListener("input", filterList);
dioceseFilter.addEventListener("change", filterList);

// Modal Logic
function openModal(data) {
    document.getElementById("modalImg").src = data.image;
    document.getElementById("modalName").innerText = data.churchName;
    document.getElementById("modalDiocese").innerText = data.diocese + " Diocese";
    document.getElementById("modalVicar").innerText = "Vicar: " + data.vicarAt;

    document.getElementById("detailModal").classList.remove("hidden");
}

function closeModal() {
    document.getElementById("detailModal").classList.add("hidden");
}
