import Trix from 'trix';
import 'trix/dist/trix.css';

document.addEventListener('trix-before-initialize', updateToolbars);

function trixToolbarObserver(trixToolbarElement) {
    if (trixToolbarElement.dataset.hasTrixToolbarObserver) {
        return;
    }
    trixToolbarElement.dataset.hasTrixToolbarObserver = 'true';

    const observer = new MutationObserver((mutationsList, observer) => {
        const hasChildren = trixToolbarElement.children.length > 0;
        if (!hasChildren) {
            updateToolbars();
        }
    });

    observer.observe(trixToolbarElement, { childList: true });
}

document.querySelectorAll('trix-toolbar').forEach(trixToolbarObserver);

const bodyObserver = new MutationObserver((mutationsList) => {
    for (const mutation of mutationsList) {
        if (mutation.type === 'childList') {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1) {
                    if (node.matches('trix-toolbar')) {
                        trixToolbarObserver(node);
                    }

                    node.querySelectorAll('trix-toolbar').forEach(trixToolbarObserver);
                }
            });
        }
    }
});

bodyObserver.observe(document.body, { childList: true, subtree: true });

document.addEventListener('trix-blur', (event) => {
    const innerInput = document.getElementById(event.target.attributes.input.value);

    if (innerInput) {
        innerInput.dispatchEvent(new Event('change', { bubbles: true }));
        updateToolbars();
    }
});

document.addEventListener('trix-file-accept', (event) => {
    event.preventDefault();
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('trix-editor').forEach((editor) => {
        const innerInput = document.getElementById(editor.attributes.input.value);

        if (innerInput) {
            editor.innerHTML = innerInput.value;
        }
    });
});

function updateToolbars() {
    const toolbars = document.querySelectorAll('trix-toolbar');
    const html = removeToolbarFileTools(Trix.config.toolbar.getDefaultHTML());

    toolbars.forEach((toolbar) => (toolbar.innerHTML = html));
}

function removeToolbarFileTools(html) {
    const temporaryElement = document.createElement('div');
    temporaryElement.innerHTML = html;

    const fileToolsElement = temporaryElement.querySelector('[data-trix-button-group="file-tools"]');
    if (fileToolsElement) {
        fileToolsElement.remove();
    }

    return temporaryElement.innerHTML;
}
