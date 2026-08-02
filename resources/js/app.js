(function () {
    const theme = localStorage.getItem('theme') ?? 'dark';
    document.documentElement.classList.remove('dark', 'light');
    document.documentElement.classList.add(theme);
})();

function setTheme(theme) {
    document.documentElement.classList.remove('dark', 'light');
    document.documentElement.classList.add(theme);
    localStorage.setItem('theme', theme);
    updateThemeToggle(theme);
}

function updateThemeToggle(theme) {
    const label = document.querySelector('[data-theme-label]');
    const darkIcon = document.querySelector('[data-theme-icon-dark]');
    const lightIcon = document.querySelector('[data-theme-icon-light]');

    if (label) {
        label.textContent = theme === 'dark' ? 'Light mode' : 'Dark mode';
    }

    if (darkIcon && lightIcon) {
        darkIcon.classList.toggle('hidden', theme === 'dark');
        lightIcon.classList.toggle('hidden', theme !== 'dark');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-theme-toggle]');

    if (!toggle) {
        return;
    }

    updateThemeToggle(document.documentElement.classList.contains('light') ? 'light' : 'dark');

    toggle.addEventListener('click', () => {
        const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
        setTheme(nextTheme);
    });
});
