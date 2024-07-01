document.addEventListener('DOMContentLoaded', () => {
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
})

/* Responsive Menu */
function toggleMenu() {
  let menu = document.getElementById("menu");
  menu.classList.toggle("-translate-x-full");
  menu.classList.toggle("opacity-0");
  menu.classList.toggle("invisible");
}

document.getElementById("mostrar_menu").addEventListener("click", toggleMenu);


