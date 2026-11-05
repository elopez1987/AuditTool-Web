(() => {
    'use strict';

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || !form.matches('[data-maintenance-action]')) {
            return;
        }

        if (!window.confirm('¿Confirmas que deseas ejecutar esta operación del sistema?')) {
            event.preventDefault();
        }
    });
})();
