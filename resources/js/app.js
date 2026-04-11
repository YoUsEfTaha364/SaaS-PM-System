import './bootstrap';

import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;
window.Sortable = Sortable;

window.openModal = function(name) {
    let event = new CustomEvent('open-modal', { detail: { name: name } });
    window.dispatchEvent(event);
}

window.closeModal = function() {
    let event = new CustomEvent('close-modal');
    window.dispatchEvent(event);
}

Alpine.start();
