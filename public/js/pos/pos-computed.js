/**
 * POS Computed Properties Module
 * Mengelola semua computed properties
 */

export function createComputedValues(state, pembayaran) {
    const computedValues = {};

    computedValues.subtotal = computed(() => {
        return state.cart.value.reduce((sum, item) => {
            const harga = parseFloat(item.harga_satuan) || 0;
            const qty = parseFloat(item.jumlah) || 0;
            return sum + (harga * qty);
        }, 0);
    });

    computedValues.subtotalSetelahDiskonItem = computed(() => {
        return state.cart.value.reduce((sum, item) => {
            const harga = parseFloat(item.harga_satuan) || 0;
            const qty = parseFloat(item.jumlah) || 0;
            const diskonItem = parseFloat(item.diskon_item) || 0;
            return sum + ((harga * qty) - diskonItem);
        }, 0);
    });

    computedValues.total = computed(() => {
        const subtotalSetelahItem = computedValues.subtotalSetelahDiskonItem.value;
        let diskonGlobal = parseFloat(state.diskonTransaksi.value) || 0;

        if (state.diskonMode.value === 'persen') {
            diskonGlobal = (diskonGlobal / 100) * subtotalSetelahItem;
        }

        return Math.max(0, subtotalSetelahItem - diskonGlobal);
    });

    computedValues.totalDiskon = computed(() => {
        const subtotalSetelahItem = computedValues.subtotalSetelahDiskonItem.value;
        let diskonGlobal = parseFloat(state.diskonTransaksi.value) || 0;

        if (state.diskonMode.value === 'persen') {
            diskonGlobal = (diskonGlobal / 100) * subtotalSetelahItem;
        }

        return computedValues.subtotal.value - computedValues.total.value;
    });

    computedValues.totalQty = computed(() => state.cart.value.reduce((sum, item) =>
        sum + (parseFloat(item.jumlah) || 0), 0));

    computedValues.kembalian = computed(() => {
        const bayar = parseFloat(pembayaran.value.uang_dibayar) || 0;
        const total = computedValues.total.value;
        return bayar - total;
    });

    computedValues.uangDibayarFormatted = computed({
        get: () => {
            const num = parseFloat(pembayaran.value.uang_dibayar) || 0;
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        set: (value) => {
            const num = parseInt(value.replace(/[^\d]/g, '')) || 0;
            pembayaran.value.uang_dibayar = num;
        }
    });

    computedValues.currentDate = computed(() => new Date().toLocaleDateString('id-ID'));
    computedValues.currentTime = computed(() => new Date().toLocaleTimeString('id-ID'));
    computedValues.selectedItem = computed(() => state.cart.value[state.editSelectedIndex.value]);

    return computedValues;
}