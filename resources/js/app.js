import './bootstrap';

// Dark mode toggle
document.addEventListener('DOMContentLoaded', function() {
    // Check localStorage for dark mode preference
    const isDark = localStorage.getItem('darkMode') === 'true';
    if (isDark) {
        document.documentElement.classList.add('dark');
    }

    // Dark mode toggle button handler
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            const isDarkMode = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDarkMode);
        });
    }
});
