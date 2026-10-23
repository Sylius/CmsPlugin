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

const kindOf = (file) => {
    if (file.type.startsWith('image/')) {
        return 'image';
    }

    return file.type.startsWith('video/') ? 'video' : 'file';
};

const formatSize = (bytes) => {
    if (bytes < 1024) {
        return `${bytes} B`;
    }

    if (bytes < 1024 * 1024) {
        return `${Math.round(bytes / 1024)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};

const formatDuration = (seconds) => {
    if (!Number.isFinite(seconds)) {
        return '—';
    }

    const total = Math.round(seconds);

    return `${Math.floor(total / 60)}:${String(total % 60).padStart(2, '0')}`;
};

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'input',
        'type',
        'detected',
        'selectedImage',
        'selectedVideo',
        'selectedFile',
        'selectedExtension',
        'selectedFileName',
        'selectedFileType',
        'detailType',
        'detailSize',
        'detailDimensions',
        'detailDimensionsRow',
        'detailDuration',
        'detailDurationRow',
        'detailName',
        'mismatch',
        'mismatchText',
        'switchType',
        'saveAsHelp',
        'currentVideo',
        'currentResolution',
        'currentDuration',
    ];

    static values = {
        mismatchMessage: String,
        switchMessage: String,
        saveAsMessage: String,
    };

    async connect() {
        this.file = null;
        this.detected = false;

        if (this.hasCurrentVideoTarget && this.currentVideoTarget.readyState >= 1) {
            this.describeCurrentVideo();
        }

        const root = this.element.closest('[data-controller~="live"]');
        if (!root) {
            return;
        }

        const component = await getComponent(root);
        if (!this.element.isConnected) {
            return;
        }

        this.component = component;
        this.onRender = () => this.refresh();
        this.component.on('render:finished', this.onRender);
    }

    disconnect() {
        this.component?.off('render:finished', this.onRender);
        this.component = null;
        this.revokePreview();
    }

    browse() {
        this.inputTarget.click();
    }

    select() {
        const file = this.inputTarget.files?.[0] ?? null;

        this.revokePreview();
        this.file = file;
        this.detected = false;

        if (file) {
            this.preview(file);
            this.detectType(file);
        }

        this.element.classList.toggle('has-file', file !== null);
        this.refresh();
    }

    clear() {
        this.inputTarget.value = '';
        this.inputTarget.dispatchEvent(new Event('change', { bubbles: true }));
    }

    dragOver(event) {
        if (!event.dataTransfer?.types.includes('Files')) {
            return;
        }

        event.preventDefault();
        event.dataTransfer.dropEffect = 'copy';
        this.element.classList.add('is-dragging');
    }

    dragLeave(event) {
        if (!this.element.contains(event.relatedTarget)) {
            this.element.classList.remove('is-dragging');
        }
    }

    drop(event) {
        const file = event.dataTransfer?.files?.[0];
        if (!file) {
            return;
        }

        event.preventDefault();
        this.element.classList.remove('is-dragging');

        const transfer = new DataTransfer();
        transfer.items.add(file);
        this.inputTarget.files = transfer.files;
        this.inputTarget.dispatchEvent(new Event('change', { bubbles: true }));
    }

    checkType(event) {
        if (!event.detail?.fromUpload) {
            this.detected = false;
        }

        this.refresh();
    }

    switchType() {
        if (this.file) {
            this.checkRadio(kindOf(this.file));
        }
    }

    async copyPath(event) {
        const button = event.currentTarget;
        const input = button.closest('.input-group')?.querySelector('input');
        if (!input) {
            return;
        }

        try {
            await navigator.clipboard.writeText(input.value);
        } catch {
            input.removeAttribute('disabled');
            input.select();
            document.execCommand('copy');
            input.setAttribute('disabled', 'disabled');
        }

        const label = button.querySelector('span');
        if (label && button.dataset.copied) {
            const original = label.textContent;
            label.textContent = button.dataset.copied;
            setTimeout(() => {
                label.textContent = original;
            }, 1500);
        }
    }

    describeCurrentVideo() {
        const video = this.currentVideoTarget;

        if (this.hasCurrentResolutionTarget) {
            this.currentResolutionTarget.textContent = `${video.videoWidth} × ${video.videoHeight}`;
        }
        if (this.hasCurrentDurationTarget) {
            this.currentDurationTarget.textContent = formatDuration(video.duration);
        }
    }

    preview(file) {
        const kind = kindOf(file);
        const extension = file.name.includes('.') ? file.name.split('.').pop() : '';

        this.objectUrl = kind === 'file' ? null : URL.createObjectURL(file);

        this.selectedImageTarget.hidden = kind !== 'image';
        this.selectedVideoTarget.hidden = kind !== 'video';
        this.selectedFileTarget.hidden = kind !== 'file';

        this.detailTypeTarget.textContent = file.type || '—';
        this.detailSizeTarget.textContent = formatSize(file.size);
        this.detailNameTarget.textContent = file.name;
        this.detailNameTarget.title = file.name;
        this.detailDimensionsTarget.textContent = '—';
        this.detailDurationTarget.textContent = '—';
        this.detailDimensionsRowTarget.hidden = kind === 'file';
        this.detailDurationRowTarget.hidden = kind !== 'video';

        if (kind === 'image') {
            this.selectedImageTarget.onload = () => {
                this.detailDimensionsTarget.textContent = `${this.selectedImageTarget.naturalWidth} × ${this.selectedImageTarget.naturalHeight} px`;
            };
            this.selectedImageTarget.src = this.objectUrl;
        } else if (kind === 'video') {
            this.selectedVideoTarget.onloadedmetadata = () => {
                this.detailDimensionsTarget.textContent = `${this.selectedVideoTarget.videoWidth} × ${this.selectedVideoTarget.videoHeight}`;
                this.detailDurationTarget.textContent = formatDuration(this.selectedVideoTarget.duration);
            };
            this.selectedVideoTarget.src = this.objectUrl;
        } else {
            this.selectedExtensionTarget.textContent = extension.slice(0, 4).toUpperCase();
            this.selectedFileNameTarget.textContent = file.name;
            this.selectedFileTypeTarget.textContent = [file.type, formatSize(file.size)].filter(Boolean).join(' · ');
        }

        this.suggestIdentity(file);
    }

    revokePreview() {
        if (this.objectUrl) {
            URL.revokeObjectURL(this.objectUrl);
            this.objectUrl = null;
        }

        if (this.hasSelectedImageTarget) {
            this.selectedImageTarget.removeAttribute('src');
            this.selectedVideoTarget.removeAttribute('src');
        }
    }

    detectType(file) {
        const kind = kindOf(file);
        if (this.typeTargets.some((radio) => radio.value === kind)) {
            this.detected = true;
            this.checkRadio(kind);
        }
    }

    checkRadio(value) {
        const radio = this.typeTargets.find((target) => target.value === value);
        if (!radio || radio.checked) {
            this.refresh();

            return;
        }

        radio.checked = true;
        radio.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { fromUpload: true } }));
    }

    suggestIdentity(file) {
        const form = this.element.closest('form');
        const base = file.name.replace(/\.[^.]+$/, '');
        const code = form?.querySelector('input[name$="[code]"]:not([disabled])');
        const name = form?.querySelector('input[name$="[name]"]');

        if (code && code.value === '') {
            this.fill(code, base.toLowerCase().replace(/[^a-z0-9_]+/g, '_').replace(/^_+|_+$/g, ''));
        }

        if (name && name.value === '') {
            const words = base.replace(/[_-]+/g, ' ').trim();
            this.fill(name, words.charAt(0).toUpperCase() + words.slice(1));
        }
    }

    fill(input, value) {
        input.value = value;
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    refresh() {
        const file = this.file;
        const checked = this.typeTargets.find((radio) => radio.checked);
        const kind = file ? kindOf(file) : null;
        const mismatch = file !== null && checked !== undefined && ['image', 'video'].includes(checked.value) && checked.value !== kind;

        this.element.classList.toggle('has-file', file !== null);

        if (this.hasDetectedTarget) {
            this.detectedTarget.classList.toggle('d-none', !(file && this.detected && checked?.value === kind));
        }

        if (this.hasSaveAsHelpTarget) {
            this.saveAsHelpTarget.dataset.originalText ??= this.saveAsHelpTarget.textContent;
            this.saveAsHelpTarget.textContent = file
                ? this.saveAsMessageValue.replace('%name%', file.name)
                : this.saveAsHelpTarget.dataset.originalText;
        }

        if (!this.hasMismatchTarget) {
            return;
        }

        this.mismatchTarget.classList.toggle('d-none', !mismatch);
        if (!mismatch) {
            return;
        }

        const expected = this.typeTargets.find((radio) => radio.value === kind);
        this.mismatchTextTarget.textContent = this.mismatchMessageValue
            .replace('%type%', this.labelOf(checked))
            .replace('%mime%', file.type || file.name);
        this.switchTypeTarget.hidden = expected === undefined;
        this.switchTypeTarget.textContent = expected ? this.switchMessageValue.replace('%type%', this.labelOf(expected)) : '';
    }

    labelOf(radio) {
        return this.element.querySelector(`label[for="${radio.id}"]`)?.textContent.trim() ?? radio.value;
    }
}
