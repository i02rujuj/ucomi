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

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}