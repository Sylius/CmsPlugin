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
    static targets = ['snapshot'];

    async connect() {
        this.showTabWithErrors();

        this.component = await getComponent(this.element);
        this.component.on('render:finished', () => this.applySnapshot());
    }

    select(event) {
        event.preventDefault();

        const prop = event.params.prop;
        const value = String(event.params.value ?? '');

        this.apply(prop, value);
        this.store(prop, value);
    }

    confirm(event) {
        if (!window.confirm(event.params.message)) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }

    showTabWithErrors() {
        const hasErrors = (pane) => pane.querySelector('.is-invalid, .alert-danger') !== null;
        const panes = [...this.element.querySelectorAll('.tab-pane[id]')];

        if (panes.some((pane) => pane.classList.contains('active') && hasErrors(pane))) {
            return;
        }

        const pane = panes.find(hasErrors);
        if (pane) {
            this.element.querySelector(`[data-bs-toggle="tab"][data-bs-target="#${pane.id}"]`)?.click();
        }
    }

    applySnapshot() {
        if (!this.hasSnapshotTarget) {
            return;
        }

        const state = JSON.parse(this.snapshotTarget.dataset.state || '{}');
        Object.entries(state).forEach(([prop, value]) => this.apply(prop, String(value ?? '')));
    }

    apply(prop, value) {
        this.element.querySelectorAll(`[data-ui-pane="${prop}"]`).forEach((pane) => {
            pane.classList.toggle('d-none', !pane.dataset.uiPaneValue.split(' ').includes(value));
        });

        this.element.querySelectorAll(`[data-ui-trigger="${prop}"]`).forEach((trigger) => {
            const isActive = trigger.dataset.uiTriggerValue === value;
            trigger.classList.toggle('active', isActive);
            trigger.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
    }

    store(prop, value) {
        if (!this.component) {
            return;
        }

        const [root, ...path] = prop.split('.');
        if (path.length === 0) {
            this.component.set(root, value, false);

            return;
        }

        const current = this.component.getData(root);
        const data = current && !Array.isArray(current) ? { ...current } : {};
        data[path.join('.')] = value;

        this.component.set(root, data, false);
    }
}
