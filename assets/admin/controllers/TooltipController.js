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
    // Admin tooltips are initialized on page load only, so the elements rendered by live components set them up here.
    connect() {
        bootstrap.Tooltip.getOrCreateInstance(this.element);
    }

    disconnect() {
        bootstrap.Tooltip.getInstance(this.element)?.dispose();
    }
}
