// FILTROS

const filterFunction = () => {
    const searchInput = document.querySelector("#search-input");
    const value = searchInput.value.toLowerCase();
    const cards = document.querySelectorAll(".card");

    cards.forEach(function (card) {
        const cardText = card.textContent.toLowerCase();
        if (cardText.indexOf(value) !== -1) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
};

const searchInput = document.querySelector("#search-input");
searchInput.addEventListener("keyup", filterFunction);

document.addEventListener("DOMContentLoaded", function (event) {
    filterFunction();
});

document.addEventListener("DOMContentLoaded", function (event) {
    filterFunction();
});

document.querySelector("#buscar-habilitado").addEventListener("click", function () {
    document.querySelector("#search-input").value = "Habilitada";
    filterFunction();
});

document.querySelector("#buscar-deshabilitado").addEventListener("click", function () {
    document.querySelector("#search-input").value = "Deshabilitado";
    filterFunction();
});