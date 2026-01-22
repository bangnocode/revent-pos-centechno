/**
 * POS Utility Functions Module
 * Berisi fungsi-fungsi utilitas umum
 */

export const utils = {
    /**
     * Format angka ke Rupiah
     */
    formatRupiah: (angka) => {
        let num = parseFloat(angka) || 0;
        let str = Math.floor(num).toString();
        return str.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    /**
     * Format input uang dibayar
     */
    formatUangDibayar: (e) => {
        let value = e.target.value.replace(/[^\d]/g, '');
        return value ? parseInt(value) : 0;
    },

    /**
     * Print thermal receipt
     */
    printThermalReceipt: (nomorFaktur) => {
        const printWindow = window.open(
            `/pos/print-invoice/${nomorFaktur}?autoprint=true`,
            '_blank',
            'width=400,height=600'
        );

        // Window will handle its own printing and the user can close it, 
        // or we can attempt to close it after some time.
        if (printWindow) {
            setTimeout(() => {
                if (!printWindow.closed) {
                    // We don't force print() here as the child window has autoprint logic
                }
            }, 1000);
        }
    }
};