import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Make Chart.js available globally
window.Chart = Chart;

// Sidebar toggle functionality
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        open: window.innerWidth >= 1024,
        toggle() {
            this.open = !this.open;
        },
        init() {
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    this.open = true;
                }
            });
        }
    }));
    
    Alpine.data('dropdown', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        }
    }));
    
    Alpine.data('modal', () => ({
        open: false,
        show() {
            this.open = true;
        },
        hide() {
            this.open = false;
        }
    }));
});

