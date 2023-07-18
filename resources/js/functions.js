// Funciones reutilizables que no dependan de un archivo específico

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

/* Floating Button */

document.addEventListener("DOMContentLoaded", function () {
    const scrollToTopButton = document.getElementById("scrollToTop");

    // Ocultar el botón al cargar la página
    scrollToTopButton.classList.add("opacity-0");

    // Escuchar el evento de desplazamiento (scroll) y actualizar la visibilidad del botón
    window.addEventListener("scroll", function () {
        if (window.pageYOffset > 0) {
            scrollToTopButton.classList.remove("opacity-0");
        } else {
            scrollToTopButton.classList.add("opacity-0");
        }
    });

    scrollToTopButton.addEventListener("click", function () {
        smoothScrollToTop();
    });

    function smoothScrollToTop() {
        const startPosition = window.pageYOffset;
        const totalTime = 1000; // Duración total de la animación en milisegundos
        const easingFunction = (t) =>
            t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; // Función de easing (aceleración y desaceleración)

        let startTime;

        function step(currentTime) {
            if (!startTime) startTime = currentTime;
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / totalTime, 1);

            const easedProgress = easingFunction(progress);
            const scrollPosition = startPosition * (1 - easedProgress);

            window.scrollTo(0, scrollPosition);

            if (elapsed < totalTime) {
                requestAnimationFrame(step);
            }
        }

        requestAnimationFrame(step);
    }
});

/* Responsive Menu */

function toggleMenu() {
    let menu = document.getElementById("menu");
    menu.classList.toggle("-translate-x-full");
    menu.classList.toggle("opacity-0");
    menu.classList.toggle("invisible");
}

document.getElementById("mostrar_menu").addEventListener("click", toggleMenu);

// FILTROS

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
