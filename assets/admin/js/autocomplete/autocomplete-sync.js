// symfony/ux-autocomplete marks its <select> with data-skip-morph so Live Component never
// morphs its children (that would destroy the TomSelect UI built next to it). But Live
// Component's attribute reconciliation still touches the element's own attributes on every
// render, and that spuriously trips Stimulus's attribute-mutation-based controller matching
// (TokenListObserver): it treats the mutation as the controller token disappearing and
// reappearing, so it fully disconnects and reconnects the autocomplete controller. On
// disconnect, TomSelect.destroy() restores the <select> to the pristine, empty state it
// captured on its very first connect — wiping out any selection we push into it, whether
// through TomSelect's API or the `selected` HTML attribute, if we do it too early.
//
// That reconnect runs inside Stimulus's MutationObserver callback (a microtask), so it has
// always finished by the time a setTimeout(0) (a macrotask) runs. We read the server-rendered
// selection synchronously in render:finished (before any reconnect can reset it), then apply
// it a tick later, once any spurious reconnect has settled, against whatever TomSelect
// instance exists at that point — through its own API and mirrored onto the `selected` HTML
// attribute, since that's what a freshly (re)created TomSelect seeds itself from.
document.addEventListener('live:connect', (event) => {
    const { component } = event.detail;

    if (!component) {
        return;
    }

    component.on('render:finished', () => {
        const pending = Array.from(document.querySelectorAll(
            'select[data-controller*="symfony--ux-autocomplete--autocomplete"][data-model]',
        )).map((selectElement) => ({
            selectElement,
            options: Array.from(selectElement.options)
                .filter((option) => option.selected && option.value !== '')
                .map((option) => ({ value: option.value, text: option.textContent })),
        }));

        setTimeout(() => {
            pending.forEach(({ selectElement, options }) => {
                const tomSelect = selectElement.tomselect;

                if (!tomSelect) {
                    return;
                }

                const selectedValues = options.map((option) => option.value);
                const currentValues = [].concat(tomSelect.getValue()).filter((value) => value !== '');

                const isUnchanged = selectedValues.length === currentValues.length
                    && selectedValues.every((value) => currentValues.includes(value));

                if (isUnchanged) {
                    return;
                }

                options.forEach((option) => tomSelect.addOption(option));
                tomSelect.setValue(selectElement.multiple ? selectedValues : (selectedValues[0] ?? ''), true);

                Array.from(selectElement.options).forEach((option) => {
                    if (selectedValues.includes(option.value)) {
                        option.setAttribute('selected', 'selected');
                    } else {
                        option.removeAttribute('selected');
                    }
                });
            });
        }, 0);
    });
});
