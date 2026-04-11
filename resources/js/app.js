import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.openModal = function (name) {
    let event = new CustomEvent('open-modal', { detail: { name: name } });
    window.dispatchEvent(event);
}

window.closeModal = function () {
    let event = new CustomEvent('close-modal');
    window.dispatchEvent(event);
}

Alpine.start();
