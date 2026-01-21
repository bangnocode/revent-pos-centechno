/**
 * POS Main Application Module
 * Menggabungkan semua modul POS menjadi aplikasi Vue
 */

import { createPosState, createRefs, createPaymentData } from './pos-state.js';
import { utils } from './pos-utils.js';
import { createComputedValues } from './pos-computed.js';
import { createCoreFunctions } from './pos-core.js';
import { createCartFunctions } from './pos-cart.js';
import { createEditFunctions } from './pos-edit.js';
import { createPaymentFunctions } from './pos-payment.js';
import { createKeyboardFunctions } from './pos-keyboard.js';

const {
    createApp,
    ref,
    computed,
    onMounted,
    onUnmounted,
    nextTick
} = Vue;

// Initialize all modules
const state = createPosState();
const refs = createRefs();
const pembayaran = createPaymentData();

const computedValues = createComputedValues(state, pembayaran);

const core = createCoreFunctions(state, refs, utils);
const cart = createCartFunctions(state, core);
const editMode = createEditFunctions(state, refs, core);
const payment = createPaymentFunctions(state, pembayaran, computedValues, utils, core, refs);
const keyboard = createKeyboardFunctions(state, core, cart, editMode, payment, refs);

// Create Vue app
createApp({
    setup() {
        // Lifecycle hooks
        onMounted(() => {
            document.addEventListener('keydown', keyboard.handleKeydown, true);
            core.focusBarcode();
        });

        onUnmounted(() => {
            document.removeEventListener('keydown', keyboard.handleKeydown, true);
        });

        // Return all reactive data and methods
        return {
            // State
            barcode: state.barcode,
            cart: state.cart,
            editMode: state.editMode,
            editSelectedIndex: state.editSelectedIndex,
            editQtyMode: state.editQtyMode,
            showModal: state.showModal,
            isProcessing: state.isProcessing,
            tempQty: state.tempQty,
            lastTransaction: state.lastTransaction,
            showModalCari: state.showModalCari,
            keywordCari: state.keywordCari,
            hasilPencarian: state.hasilPencarian,
            isLoadingCari: state.isLoadingCari,
            selectedSearchIndex: state.selectedSearchIndex,
            diskonTransaksi: state.diskonTransaksi,
            diskonInput: state.diskonInput,
            diskonMode: state.diskonMode,
            pembayaran,

            // Template refs
            barcodeInput: refs.barcodeInput,
            uangDibayarInput: refs.uangDibayarInput,
            qtyModalInput: refs.qtyModalInput,
            searchInput: refs.searchInput,

            // Computed
            ...computedValues,

            // Methods
            tambahSelectedBarang: core.tambahSelectedBarang,
            navigasiSearch: core.navigasiSearch,
            formatRupiah: utils.formatRupiah,
            formatUangDibayar: utils.formatUangDibayar,
            focusBarcode: core.focusBarcode,
            manualInput: core.manualInput,
            tambahBarang: core.tambahBarang,
            tambahQty: cart.tambahQty,
            kurangiQty: cart.kurangiQty,
            hapusBarang: cart.hapusBarang,
            startEditQty: editMode.startEditQty,
            applyEditQty: editMode.applyEditQty,
            batalEditQty: editMode.batalEditQty,
            toggleEditMode: editMode.toggleEditMode,
            batalEdit: editMode.batalEdit,
            handleEsc: keyboard.handleEsc,
            bukaModalPembayaran: payment.bukaModalPembayaran,
            tutupModal: payment.tutupModal,
            prosesPembayaran: payment.prosesPembayaran,
            cariBarangManual: core.cariBarangManual,
            tambahBarangDariPencarian: core.tambahBarangDariPencarian,
            tutupModalCari: core.tutupModalCari,
            setDiskonItem: core.setDiskonItem,
            handleDiskonInput: (event) => core.handleDiskonInput(event, computedValues),
            toggleDiskonMode: core.toggleDiskonMode,
        };
    }
}).mount('#app');