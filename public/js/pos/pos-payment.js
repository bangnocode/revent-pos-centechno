/**
 * POS Payment Module
 * Mengelola proses pembayaran
 */

export function createPaymentFunctions(state, pembayaran, computedValues, utils, core, refs) {
    return {
        /**
         * Buka modal pembayaran
         */
        bukaModalPembayaran: () => {
            if (state.cart.value.length === 0 || state.isProcessing.value) return;

            state.showModal.value = true;
            pembayaran.value.uang_dibayar = computedValues.total.value;

            nextTick(() => {
                if (refs.uangDibayarInput.value) {
                    refs.uangDibayarInput.value.focus();
                    refs.uangDibayarInput.value.select();
                }
            });
        },

        /**
         * Tutup modal pembayaran
         */
        tutupModal: () => {
            if (state.isProcessing.value) return;

            state.showModal.value = false;
            pembayaran.value.nama_pelanggan = 'Pelanggan Umum';
            pembayaran.value.metode_pembayaran = 'tunai';
            pembayaran.value.uang_dibayar = 0;
            core.focusBarcode();
        },

        /**
         * Proses pembayaran
         */
        prosesPembayaran: async () => {
            const isHutang = pembayaran.value.metode_pembayaran === 'hutang';
            const kurangBayar = pembayaran.value.uang_dibayar < computedValues.total.value;

            if ((kurangBayar && !isHutang) || state.isProcessing.value) {
                return;
            }

            state.isProcessing.value = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const dataToSend = {
                    items: state.cart.value.map(item => ({
                        kode_barang: item.kode_barang,
                        nama_barang: item.nama_barang,
                        harga_satuan: parseFloat(item.harga_satuan),
                        jumlah: parseFloat(item.jumlah),
                        satuan: item.satuan,
                        diskon_item: parseFloat(item.diskon_item || 0),
                        subtotal: parseFloat(item.subtotal)
                    })),
                    nama_pelanggan: pembayaran.value.nama_pelanggan,
                    metode_pembayaran: pembayaran.value.metode_pembayaran,
                    total_bayar: parseFloat(pembayaran.value.uang_dibayar),
                    subtotal: parseFloat(computedValues.subtotal.value),
                    diskon_transaksi: (() => {
                        let diskon = parseFloat(state.diskonTransaksi.value || 0);
                        if (state.diskonMode.value === 'persen') {
                            diskon = (diskon / 100) * computedValues.subtotalSetelahDiskonItem.value;
                        }
                        return diskon;
                    })(),
                    total_transaksi: parseFloat(computedValues.total.value),
                };

                const response = await axios.post('/pos/simpan-transaksi', dataToSend, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (response.data.success) {
                    state.lastTransaction.value = response.data.nomor_faktur;

                    // Reset semua state sebelum menutup modal
                    state.cart.value = [];
                    state.diskonTransaksi.value = 0;
                    state.diskonInput.value = '0';
                    state.diskonMode.value = 'nominal';

                    // Reset form pembayaran
                    pembayaran.value.nama_pelanggan = 'Pelanggan Umum';
                    pembayaran.value.metode_pembayaran = 'tunai';
                    pembayaran.value.uang_dibayar = 0;

                    state.showModal.value = false;

                    await utils.printThermalReceipt(response.data.nomor_faktur);
                    core.focusBarcode();
                }
            } catch (error) {
                console.error('Error menyimpan transaksi:', error);
                alert('Terjadi kesalahan saat menyimpan transaksi');
            } finally {
                state.isProcessing.value = false;
            }
        }
    };
}