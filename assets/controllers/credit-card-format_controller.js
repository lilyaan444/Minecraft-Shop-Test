import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('input', this.formatCardNumber.bind(this));
    }

    formatCardNumber(event) {
        let value = event.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        event.target.value = value;
    }
}