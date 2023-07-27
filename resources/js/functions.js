import $ from 'jquery';

// Funciones reutilizables que no dependan de un archivo específico

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

export {
    toggleMenu
}

var acc = document.getElementsByClassName("accordion-submenu");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("submenu-active");
    var submenu = this.nextElementSibling;

    if (submenu.style.maxHeight || submenu.classList.contains("submenu-visible")) {
        submenu.style.maxHeight = null;
        submenu.classList.remove("submenu-visible");
    } else {
        submenu.style.maxHeight = submenu.scrollHeight + "px";
    }

  });
}

var acc = document.getElementsByClassName("accordion-info");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("info-active");
    var submenu = this.nextElementSibling;

    if (submenu.style.maxHeight || submenu.classList.contains("info-visible")) {
        submenu.style.maxHeight = null;
        submenu.classList.remove("info-visible");
    } else {
        submenu.style.maxHeight = submenu.scrollHeight + "px";
    }

  });
}
