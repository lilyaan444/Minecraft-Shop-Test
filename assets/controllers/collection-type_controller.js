import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container'];
    
    connect() {
        console.log('Collection Type Controller connected');
        this.index = this.element.querySelectorAll('.credit-card-form').length;
    }

    addForm(event) {
        console.log('Add form triggered');
        event.preventDefault();

        const prototype = this.element.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, this.index);
        this.index++;

        const container = document.createElement('div');
        container.innerHTML = newForm;
        container.classList.add('credit-card-form', 'mb-4');

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Remove Card';
        deleteButton.classList.add('btn-minecraft', 'btn-danger', 'mt-2');
        deleteButton.addEventListener('click', (e) => this.removeForm(e, container));

        container.appendChild(deleteButton);
        this.element.appendChild(container);
    }

    removeForm(event, container) {
        event.preventDefault();
        container.remove();
    }
}
