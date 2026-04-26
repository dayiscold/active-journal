import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.themeToggle = function (initial) {
    return {
        isDark: initial === 'dark',
        toggle() {
            this.isDark = !this.isDark;
            const theme = this.isDark ? 'dark' : 'light';
            document.documentElement.className = theme;
            fetch('/user/theme', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ theme }),
            });
        },
    };
};

Alpine.start();