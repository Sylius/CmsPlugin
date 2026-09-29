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
    const innerInput = syncTrixValueFromDocument(event.target);

    if (innerInput) {
        innerInput.dispatchEvent(new Event('change', { bubbles: true }));
        updateToolbars();
    }
});

function getTrixDocumentHtml(trixEditorElement) {
    return trixEditorElement.innerHTML.replace(/<!--block-->/g, '');
}

function textContentOf(html) {
    const element = document.createElement('div');
    element.innerHTML = html;

    return element.textContent;
}

function syncTrixValueFromDocument(trixEditorElement) {
    const innerInput = document.getElementById(trixEditorElement.getAttribute('input'));

    if (!innerInput) {
        return null;
    }

    const documentHtml = getTrixDocumentHtml(trixEditorElement);
    if (innerInput.value !== documentHtml && textContentOf(innerInput.value) === textContentOf(documentHtml)) {
        innerInput.value = documentHtml;
    }

    return innerInput;
}

document.addEventListener('live:connect', (event) => {
    const { component } = event.detail;

    if (!component) {
        return;
    }

    component.on('model:set', () => {
        document.querySelectorAll('trix-editor').forEach((trixEditorElement) => {
            const innerInput = syncTrixValueFromDocument(trixEditorElement);

            if (!innerInput?.hasAttribute('data-model')) {
                return;
            }

            const modelName = innerInput.getAttribute('name');
            const freshValue = innerInput.value;
            if (component.getData(modelName) !== freshValue) {
                component.set(modelName, freshValue, false);
            }
        });
    });
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
