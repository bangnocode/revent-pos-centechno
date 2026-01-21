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
     * Handle input uang dibayar dengan format ribuan
     */
    handleUangDibayarInput: (e, pembayaran) => {
        let value = e.target.value.replace(/[^\d]/g, '');
        let num = parseInt(value) || 0;
        pembayaran.uang_dibayar = num;
        // Format display dengan titik ribuan
        e.target.value = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    /**
     * Print thermal receipt
     */
    printThermalReceipt: async (nomorFaktur) => {
        try {
            const response = await axios.get(`/pos/print-invoice-data/${nomorFaktur}`);
            const transaksi = response.data;

            if ('serial' in navigator) {
                const port = await navigator.serial.requestPort();
                await port.open({ baudRate: 9600 });

                const writer = port.writable.getWriter();
                const encoder = new TextEncoder();

                let receipt = '\x1B\x40'; // Initialize printer
                receipt += '\x1B\x61\x01'; // Center align
                receipt += 'TOKO CT\n';
                receipt += 'Jl. Contoh No. 123\n';
                receipt += 'Telp: 0812-3456-7890\n';
                receipt += '\n';
                receipt += `FAKTUR: ${transaksi.nomor_faktur}\n`;
                receipt += `Tanggal: ${new Date(transaksi.tanggal_transaksi).toLocaleString('id-ID')}\n`;
                receipt += `Kasir: ${transaksi.id_operator}\n`;
                receipt += `Pelanggan: ${transaksi.nama_pelanggan}\n`;
                receipt += '\x1B\x61\x00'; // Left align
                receipt += '================================\n';
                receipt += 'Barang          Qty    Subtotal\n';
                receipt += '================================\n';

                transaksi.details.forEach(item => {
                    const nama = item.nama_barang.substring(0, 14);
                    const qty = `${item.jumlah} ${item.satuan}`.padStart(6);
                    const subtotal = `Rp ${utils.formatRupiah(item.subtotal_item)}`.padStart(10);
                    receipt += `${nama.padEnd(14)} ${qty} ${subtotal}\n`;
                });

                receipt += '================================\n';
                receipt += `Subtotal: Rp ${utils.formatRupiah(transaksi.subtotal)}\n`;
                if (transaksi.diskon_transaksi > 0) {
                    receipt += `Diskon: Rp ${utils.formatRupiah(transaksi.diskon_transaksi)}\n`;
                }
                receipt += `Total: Rp ${utils.formatRupiah(transaksi.total_transaksi)}\n`;
                receipt += `Bayar: Rp ${utils.formatRupiah(transaksi.total_bayar)}\n`;
                const kembalian = transaksi.total_bayar - transaksi.total_transaksi;
                if (kembalian >= 0) {
                    receipt += `Kembalian: Rp ${utils.formatRupiah(kembalian)}\n`;
                } else {
                    receipt += `Kurang: Rp ${utils.formatRupiah(Math.abs(kembalian))}\n`;
                }
                receipt += '\nTerima Kasih!\n';
                receipt += '\x1D\x56\x42\x00'; // Cut paper

                await writer.write(encoder.encode(receipt));
                await writer.close();
                await port.close();
            } else {
                // Fallback to browser print
                const printWindow = window.open(
                    `/pos/print-invoice/${nomorFaktur}?autoprint=true`,
                    '_blank',
                    'width=400,height=600'
                );
                setTimeout(() => {
                    if (printWindow && !printWindow.closed) {
                        printWindow.print();
                        setTimeout(() => printWindow.close(), 5000);
                    }
                }, 1000);
            }
        } catch (error) {
            console.error('Print error:', error);
            // Fallback to browser print
            const printWindow = window.open(
                `/pos/print-invoice/${nomorFaktur}?autoprint=true`,
                '_blank',
                'width=400,height=600'
            );
            setTimeout(() => {
                if (printWindow && !printWindow.closed) {
                    printWindow.print();
                    setTimeout(() => printWindow.close(), 5000);
                }
            }, 1000);
        }
    }
};