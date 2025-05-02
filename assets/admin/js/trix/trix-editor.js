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

function updateToolbars(event) {
    const toolbars = document.querySelectorAll('trix-toolbar');
    const html = Trix.config.toolbar.getDefaultHTML();

    toolbars.forEach((toolbar) => (toolbar.innerHTML = html));
}
