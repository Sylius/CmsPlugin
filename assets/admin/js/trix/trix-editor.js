import Trix from 'trix';
import 'trix/dist/trix.css';

document.addEventListener('trix-before-initialize', updateToolbars);

document.addEventListener('trix-blur', (event) => {
    const innerInput = document.getElementById(event.target.attributes.input.value);

    if (innerInput) {
        innerInput.dispatchEvent(new Event('change', { bubbles: true }));
        updateToolbars();
    }
});

document.addEventListener("trix-file-accept", (event) => {
    event.preventDefault();
});

function updateToolbars(event) {
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
