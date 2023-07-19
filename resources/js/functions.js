// Funciones reutilizables que no dependan de un archivo específico

import $ from 'jquery';

// Readonly and required
$(".readonly").on('keydown paste focus mousedown', function(e){
    if(e.keyCode != 9) // ignore tab
        e.preventDefault();
});

/* Responsive Menu */

function toggleMenu() {
    let menu = document.getElementById("menu");
    menu.classList.toggle("-translate-x-full");
    menu.classList.toggle("opacity-0");
    menu.classList.toggle("invisible");
}

document.getElementById("mostrar_menu").addEventListener("click", toggleMenu);
