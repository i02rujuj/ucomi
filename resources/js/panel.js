document.addEventListener('DOMContentLoaded', () => {
    let acc = document.getElementsByClassName("accordion-submenu");
    
    for (let i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("submenu-active");
        let submenu = this.nextElementSibling;
    
        if (submenu.style.maxHeight || submenu.classList.contains("submenu-visible")) {
            submenu.style.maxHeight = null;
            submenu.classList.remove("submenu-visible");
        } else {
            submenu.style.maxHeight = submenu.scrollHeight + "px";
        }
    
      });
    }
})

document.getElementById("mostrar_menu").addEventListener("click", () => {
    let menu = document.getElementById("menu")
    menu.classList.toggle("-translate-x-full")
    menu.classList.toggle("opacity-0")
    menu.classList.toggle("invisible")
});


