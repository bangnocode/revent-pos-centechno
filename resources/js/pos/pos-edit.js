/**
 * POS Edit Mode Module
 * Mengelola mode edit untuk cart
 */

export function createEditFunctions(state, refs, core) {
    const editFunctions = {
        /**
         * Start edit quantity
         */
        startEditQty: (index) => {
            state.editSelectedIndex.value = index;
            state.tempQty.value = parseFloat(state.cart.value[index].jumlah);
            state.editQtyMode.value = true;

            nextTick(() => {
                if (refs.qtyModalInput.value) {
                    refs.qtyModalInput.value.focus();
                    refs.qtyModalInput.value.select();
                }
            });
        },

        /**
         * Apply edit quantity
         */
        applyEditQty: () => {
            if (state.editSelectedIndex.value >= 0 && state.tempQty.value > 0) {
                const item = state.cart.value[state.editSelectedIndex.value];
                const newQty = parseFloat(state.tempQty.value);

                if (newQty > item.stok_sekarang) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Terbatas',
                        text: `Hanya tersedia ${item.stok_sekarang} unit untuk ${item.nama_barang}.`,
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                item.jumlah = newQty;
                core.updateSubtotal(state.editSelectedIndex.value);
                state.editQtyMode.value = false;

                // Otomatis keluar mode edit dan focus barcode
                editFunctions.batalEdit();
            } else if (state.tempQty.value <= 0) {
                state.editQtyMode.value = false;
                editFunctions.batalEdit();
            }
        },

        /**
         * Batal edit quantity
         */
        batalEditQty: () => {
            state.editQtyMode.value = false;
            // Otomatis keluar mode edit dan focus barcode
            editFunctions.batalEdit();
        },

        /**
         * Toggle edit mode
         */
        toggleEditMode: () => {
            if (state.cart.value.length === 0) {
                alert('Keranjang kosong');
                return;
            }

            state.editMode.value = !state.editMode.value;
            state.editSelectedIndex.value = state.editMode.value ? 0 : -1;

            if (state.editMode.value) {
                if (refs.barcodeInput.value) {
                    refs.barcodeInput.value.blur();
                }
            } else {
                core.focusBarcode();
            }
        },

        /**
         * Batal edit
         */
        batalEdit: () => {
            state.editMode.value = false;
            state.editSelectedIndex.value = -1;
            core.focusBarcode();
        }
    };

    return editFunctions;
}