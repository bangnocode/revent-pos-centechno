/**
 * POS Keyboard Shortcuts Module
 * Mengelola shortcut keyboard
 */

export function createKeyboardFunctions(state, core, cart, editMode, payment, refs) {
    const keyboard = {};

    keyboard.handleEsc = () => {
        if (state.editMode.value) {
            editMode.batalEdit();
        } else {
            if (document.activeElement === refs.barcodeInput.value) {
                return;
            }
            core.focusBarcode();
        }
    };

    keyboard.handleArrowNavigation = (direction) => {
        if (!state.editMode.value || state.editSelectedIndex.value === -1) return;

        const newIndex = state.editSelectedIndex.value + direction;
        if (newIndex >= 0 && newIndex < state.cart.value.length) {
            state.editSelectedIndex.value = newIndex;
        }
    };

    keyboard.handlePlusMinus = (operation) => {
        if (!state.editMode.value || state.editSelectedIndex.value === -1) return;

        const index = state.editSelectedIndex.value;
        if (operation === 'plus') {
            cart.tambahQty(index);
        } else if (operation === 'minus') {
            cart.kurangiQty(index);
        }
    };

    keyboard.handleKeydown = (e) => {
        // Handle keyboard di modal pencarian
        if (state.showModalCari.value) {
            switch (e.key) {
                case 'Escape':
                    e.preventDefault();
                    core.tutupModalCari();
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    core.navigasiSearch(-1);
                    break;

                case 'ArrowDown':
                    e.preventDefault();
                    core.navigasiSearch(1);
                    break;

                case 'Enter':
                    e.preventDefault();

                    if (state.hasilPencarian.value.length > 0 && state.selectedSearchIndex.value >= 0) {
                        core.tambahSelectedBarang();
                    } else {
                        core.cariBarangManual();
                    }
                    break;

                case 'Tab':
                    e.preventDefault();
                    if (e.shiftKey) {
                        core.navigasiSearch(-1);
                    } else {
                        core.navigasiSearch(1);
                    }
                    break;
            }

            return;
        }

        // Prevent default F-key actions
        const fKeysUsedByApp = ['F2', 'F3', 'F8', 'F9'];
        if (fKeysUsedByApp.includes(e.key)) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Global shortcuts
        switch (e.key) {
            case 'F2':
                core.focusBarcode();
                break;
            case 'F3':
                e.preventDefault();
                core.manualInput();
                break;
            case 'F8':
                editMode.toggleEditMode();
                break;
            case 'F9':
                payment.bukaModalPembayaran();
                break;

            case 'Escape':
                if (state.editQtyMode.value) {
                    e.preventDefault();
                    editMode.batalEditQty();
                    return;
                } else if (state.showModal.value) {
                    payment.tutupModal();
                } else if (state.editMode.value) {
                    editMode.batalEdit();
                } else {
                    core.focusBarcode();
                }
                break;

            case 'ArrowUp':
                if (state.editMode.value) keyboard.handleArrowNavigation(-1);
                break;
            case 'ArrowDown':
                if (state.editMode.value) keyboard.handleArrowNavigation(1);
                break;

            case '+':
            case '=':
                if (state.editMode.value) keyboard.handlePlusMinus('plus');
                break;
            case '-':
            case '_':
                if (state.editMode.value) keyboard.handlePlusMinus('minus');
                break;

            case 'Enter':
                if (state.editQtyMode.value) {
                    e.preventDefault();
                    editMode.applyEditQty();
                    return;
                } else if (state.editMode.value && state.editSelectedIndex.value >= 0) {
                    editMode.startEditQty(state.editSelectedIndex.value);
                }
                break;

            case 'Delete':
                if (state.editMode.value && state.editSelectedIndex.value >= 0) {
                    cart.hapusBarang(state.editSelectedIndex.value);
                }
                break;
        }
    };

    return keyboard;
}