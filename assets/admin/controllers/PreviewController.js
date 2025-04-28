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
    static targets = ['modal', 'parent', 'container'];
    modal = null;

    connect() {
        window.addEventListener('sylius_cms:admin:preview:render', (event) => {
            this.modalElement = this.modalTarget;
            this.modal = bootstrap.Modal.getOrCreateInstance(this.modalElement);

            this.updateContent(event.detail.content);

            this.modal.show();
        });
        window.addEventListener('sylius_cms:admin:preview:reload', (event) => {
            this.updateContent(event.detail.content);
            this.refreshForm();
        });
    }

    updateContent(content) {
        const contentBlob = new Blob([content], {type: 'text/html'});
        const iframe= document.createElement('iframe');

        iframe.style.width = '100%';
        iframe.style.height = '85vh';
        iframe.src = window.URL.createObjectURL(contentBlob);

        this.containerTarget.innerHTML = '';
        this.containerTarget.appendChild(iframe);
    }

    refreshForm() {
        const firstInput = document.querySelector('[data-controller="live"] input.form-control:not([disabled])');
        if (firstInput !== undefined) {
            firstInput.value = firstInput.value + ' ';
            firstInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
}
