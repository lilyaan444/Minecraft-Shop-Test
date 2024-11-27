import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['total', 'count', 'items'];
    static values = {
        updateUrl: String,
        removeUrl: String
    }

    async updateQuantity(event) {
        const id = event.target.dataset.productId;
        const quantity = event.target.value;

        const response = await fetch(this.updateUrlValue.replace('ID', id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity })
        });

        const data = await response.json();
        this.updateCart(data);
    }

    async removeItem(event) {

        event.preventDefault();
        const id = event.currentTarget.dataset.productId;
        console.log('Remove button clicked, ID:', id);

        const response = await fetch(this.removeUrlValue.replace('ID', id), {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();
        this.updateCart(data);

        // Animate removal
        const item = event.currentTarget.closest('tr');
        item.style.opacity = '0';
        setTimeout(() => item.remove(), 300);
    }

    updateCart(data) {
        this.totalTarget.textContent = `${data.total} diamonds`;
        this.countTarget.textContent = data.count;
        
        if (data.count === 0) {
            this.itemsTarget.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">
                        Your cart is empty. 
                        <a href="${window.location.origin}" class="btn-minecraft">Continue shopping</a>
                    </td>
                </tr>
            `;
        }
    }
}