import Alpine from 'alpinejs';

function getById(id) {
    return document.getElementById(id);
}
function getByClass(className) {
    return document.querySelector(className);
}
function getByClassAll(className) {
    return document.querySelectorAll(className);
}

window.Alpine = Alpine;

Alpine.start();
