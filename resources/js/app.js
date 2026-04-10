import './bootstrap';

window.toggleCRM = function() {
    const menu = document.getElementById("crmMenu");
    const arrow = document.getElementById("arrow");
    const btn = document.getElementById("crmBtn");

    if (menu && arrow && btn) {
        menu.classList.toggle("hidden");
        arrow.classList.toggle("rotate-180");
        btn.classList.toggle("bg-[#142062]");
    }
};