/*
 * This file is part of the Sylius CMS Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['modal', 'title'];

    static values = {
        count: Number,
        current: String,
    };

    disconnect() {
        this.pending = null;
        bootstrap.Modal.getInstance(this.modalTarget)?.dispose();
    }

    confirm(event) {
        const radio = event.currentTarget;
        if (this.countValue === 0 || radio.value === this.currentValue) {
            return;
        }

        // Keeps the current type checked until the change is confirmed.
        event.preventDefault();
        this.pending = radio;
        this.titleTarget.textContent = radio.dataset.confirmTitle;
        bootstrap.Modal.getOrCreateInstance(this.modalTarget).show();
    }

    apply() {
        const radio = this.pending;
        this.pending = null;
        if (!radio) {
            return;
        }

        // The live re-render must not morph the modal while it is still animating.
        this.modalTarget.addEventListener('hidden.bs.modal', () => {
            radio.checked = true;
            radio.dispatchEvent(new Event('change', { bubbles: true }));
        }, { once: true });
        bootstrap.Modal.getOrCreateInstance(this.modalTarget).hide();
    }
}
