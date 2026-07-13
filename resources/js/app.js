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

const toggleThemeButton = getById('toggle-theme');

toggleThemeButton?.addEventListener('click', () => {
    let currentTheme = localStorage.getItem('theme');

    switch (currentTheme) {
        case 'light':
            localStorage.setItem('theme', 'dark');
            break;
        case 'dark':
            localStorage.setItem('theme', 'light');
            break;
        default:
            localStorage.setItem('theme', 'light');
            break;
    }
    updateTheme();
});

function updateTheme() {
    let mode = localStorage?.getItem('theme');

    if (mode == 'light') {
        document.body.classList.remove('dark');
    }
    if (mode == 'dark') {
        document.body.classList.add('dark');
    }
}

const toggleSidebarButton = getById('toggle-sidebar');

toggleSidebarButton?.addEventListener('click', toggleSidebar);

document.addEventListener('keydown', function (event) {
    if (event.shiftKey && event.key.toLowerCase() === 's') {
        toggleSidebar();
    }
});

function toggleSidebar() {
    let currentSidebar = localStorage?.getItem('sidebar');

    switch (currentSidebar) {
        case 'show':
            localStorage.setItem('sidebar', 'hidden');
            break;
        case 'hidden':
            localStorage.setItem('sidebar', 'show');
            break;
        default:
            localStorage.setItem('sidebar', 'show');
            break;
    }
    updateSidebar();
}

function updateSidebar() {
    const sidebar = getById('sidebar');

    if (sidebar == null) return;

    let mode = localStorage.getItem('sidebar');
    if (mode == 'show') {
        sidebar.classList.add('w-54');
        sidebar.classList.remove('w-18.5');
    }
    if (mode == 'hidden') {
        sidebar.classList.remove('w-54');
        sidebar.classList.add('w-18.5');
    }
}

function updateAll() {
    updateTheme();
    updateSidebar();
}

updateAll();

window.Alpine = Alpine;

Alpine.start();
