import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('input', this.formatExpirationDate.bind(this));
    }

    formatExpirationDate(event) {
        let value = event.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        event.target.value = value;
    }
}