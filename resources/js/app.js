import './bootstrap';

window.toggleCRM = function() {
    const menu = document.getElementById("crmMenu");
    const arrow = document.getElementById("arrow");
    const btn = document.getElementById("crmBtn");

    if (menu && arrow && btn) {
     
        menu.classList.toggle("hidden");
       
        arrow.classList.toggle("rotate-180");
        // biar ttp ada bg
        btn.classList.toggle("bg-[#4155C6]");
    }
};

window.togglePassword = function() {
    const passwordInput = document.getElementById('passwordInput');
    const eyeOpen = document.getElementById('eyeIconOpen');
    const eyeClosed = document.getElementById('eyeIconClosed');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
};