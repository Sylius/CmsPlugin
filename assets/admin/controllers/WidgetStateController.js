/*
 * This file is part of the Sylius CMS Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-live-component';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    async connect() {
        this.component = await getComponent(this.element);
        this.component.on('render:started', () => this.rememberTrixValues());
        this.component.on('render:finished', () => {
            this.syncAutocompletes();
            this.syncTrixEditors();
        });
    }

    removeAutocompleteItem({ params: { select, value } }) {
        document.getElementById(select)?.tomselect?.removeItem(String(value));
    }

    syncAutocompletes() {
        this.element.querySelectorAll('select[data-skip-morph]').forEach((select) => {
            if (select.tomselect?.revertSettings) {
                select.tomselect.revertSettings.innerHTML = select.innerHTML;
            }
        });
    }

    rememberTrixValues() {
        this.trixValues = new WeakMap();
        this.element.querySelectorAll('trix-editor').forEach((editor) => {
            if (editor.inputElement) {
                this.trixValues.set(editor, editor.inputElement.value);
            }
        });
    }

    syncTrixEditors() {
        this.element.querySelectorAll('trix-editor').forEach((editor) => {
            const input = editor.inputElement;

            if (!editor.editor || !input || !this.trixValues?.has(editor) || editor.contains(document.activeElement)) {
                return;
            }

            if (this.trixValues.get(editor) !== input.value) {
                editor.editor.loadHTML(input.value);
            }
        });
    }
}
