/**
 * POS State Management Module
 * Mengelola semua state aplikasi POS
 */

export function createPosState() {
    return {
        // Barcode input
        barcode: ref(''),

        // Cart management
        cart: ref([]),
        editMode: ref(false),
        editSelectedIndex: ref(-1),
        editQtyMode: ref(false),

        // Modal states
        showModal: ref(false),
        showModalCari: ref(false),

        // Processing state
        isProcessing: ref(false),

        // Edit quantity
        tempQty: ref(1),

        // Transaction info
        lastTransaction: ref(''),

        // Search states
        keywordCari: ref(''),
        hasilPencarian: ref([]),
        isLoadingCari: ref(false),
        selectedSearchIndex: ref(-1),

        // Discount states
        diskonTransaksi: ref(0),
        diskonInput: ref('0'),
        diskonMode: ref('nominal'), // 'nominal' atau 'persen'
    };
}

/**
 * Template Refs
 */
export function createRefs() {
    return {
        barcodeInput: ref(null),
        uangDibayarInput: ref(null),
        qtyModalInput: ref(null),
        searchInput: ref(null),
    };
}

/**
 * Payment Data
 */
export function createPaymentData() {
    return ref({
        nama_pelanggan: 'Pelanggan Umum',
        metode_pembayaran: 'tunai',
        uang_dibayar: 0
    });
}