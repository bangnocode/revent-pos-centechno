/**
 * POS Cart Management Module
 * Mengelola operasi cart (tambah, kurang, hapus qty)
 */

export function createCartFunctions(state, core) {
    const cart = {};

    cart.tambahQty = (index) => {
        const item = state.cart.value[index];
        if (parseFloat(item.jumlah) + 1 > item.stok_sekarang) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: `Hanya tersedia ${item.stok_sekarang} unit untuk ${item.nama_barang}.`,
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        item.jumlah = parseFloat(item.jumlah) + 1;
        core.updateSubtotal(index);
    };

    cart.kurangiQty = (index) => {
        if (state.cart.value[index].jumlah > 1) {
            state.cart.value[index].jumlah = parseFloat(state.cart.value[index].jumlah) - 1;
            core.updateSubtotal(index);
        } else {
            cart.hapusBarang(index);
        }
    };

    cart.hapusBarang = (index) => {
        if (confirm('Hapus barang dari keranjang?')) {
            state.cart.value.splice(index, 1);
            if (state.editMode.value && state.cart.value.length > 0) {
                state.editSelectedIndex.value = Math.max(
                    0,
                    Math.min(state.editSelectedIndex.value, state.cart.value.length - 1)
                );
            }
        }
    };

    return cart;
}