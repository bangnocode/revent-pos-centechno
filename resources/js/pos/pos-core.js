/**
 * POS Core Functions Module
 * Berisi fungsi-fungsi inti aplikasi POS
 */

export function createCoreFunctions(state, refs, utils) {
    const core = {};

    core.focusBarcode = () => {
        if (refs.barcodeInput.value) {
            refs.barcodeInput.value.focus();
            refs.barcodeInput.value.select();
        }
    };

    core.setDiskonItem = (index, value) => {
        const item = state.cart.value[index];
        let numericStr = value.replace(/[^\d]/g, '');
        let diskon = Math.max(0, parseInt(numericStr) || 0);

        const maxDiskon = item.harga_satuan * item.jumlah;
        if (diskon > maxDiskon) {
            diskon = maxDiskon;
        }

        item.diskon_item = diskon;
        core.updateSubtotal(index);
    };

    core.updateSubtotal = (index) => {
        const item = state.cart.value[index];
        const harga = parseFloat(item.harga_satuan) || 0;
        const qty = parseFloat(item.jumlah) || 0;
        const diskonItem = parseFloat(item.diskon_item) || 0;

        item.subtotal = (harga * qty) - diskonItem;
    };

    core.tambahBarang = async () => {
        if (!state.barcode.value.trim() || state.isProcessing.value) return;

        try {
            const response = await axios.post('/pos/cari-barang', {
                keyword: state.barcode.value.trim()
            });

            if (response.data.success) {
                const barang = response.data.data;

                if (parseFloat(barang.stok_sekarang) <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Kosong!',
                        text: `Barang "${barang.nama_barang}" tidak dapat ditambahkan karena stoknya 0.`,
                        confirmButtonColor: '#3b82f6'
                    });
                    state.barcode.value = '';
                    core.focusBarcode();
                    return;
                }

                const existingIndex = state.cart.value.findIndex(
                    item => item.kode_barang === barang.kode_barang
                );

                if (existingIndex >= 0) {
                    const newTotal = parseFloat(state.cart.value[existingIndex].jumlah) + 1;
                    if (newTotal > parseFloat(barang.stok_sekarang)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Stok Terbatas',
                            text: `Hanya tersedia ${barang.stok_sekarang} unit untuk ${barang.nama_barang}.`,
                            confirmButtonColor: '#3b82f6'
                        });
                        state.barcode.value = '';
                        core.focusBarcode();
                        return;
                    }
                    state.cart.value[existingIndex].jumlah = newTotal;
                    core.updateSubtotal(existingIndex);
                } else {
                    state.cart.value.push({
                        kode_barang: barang.kode_barang,
                        barcode: barang.barcode,
                        nama_barang: barang.nama_barang,
                        harga_satuan: parseFloat(barang.harga_jual_normal),
                        jumlah: 1,
                        satuan: barang.satuan,
                        stok_sekarang: barang.stok_sekarang,
                        diskon_item: 0,
                        subtotal: parseFloat(barang.harga_jual_normal)
                    });
                }

                state.barcode.value = '';
                core.focusBarcode();
            } else {
                alert(`Barang tidak ditemukan: ${state.barcode.value} - ${response.data.message || 'Unknown error'}`);
                state.barcode.value = '';
                core.focusBarcode();
            }
        } catch (error) {
            console.error('Error mencari barang:', error);
            alert('Terjadi kesalahan saat mencari barang: ' + error.message);
            state.barcode.value = '';
            core.focusBarcode();
        }
    };

    core.manualInput = () => {
        state.showModalCari.value = true;
        state.keywordCari.value = '';
        state.hasilPencarian.value = [];
        state.selectedSearchIndex.value = -1;

        nextTick(() => {
            if (refs.searchInput.value) {
                refs.searchInput.value.focus();
            }
        });
    };

    core.handleDiskonInput = (event, computedValues) => {
        let value = event.target.value;
        let numericStr = value.replace(/[^\d]/g, '');
        let numericValue = parseInt(numericStr) || 0;

        const subtotalSetelahItem = computedValues.subtotalSetelahDiskonItem.value;

        if (state.diskonMode.value === 'persen') {
            if (numericValue > 100) numericValue = 100;
            state.diskonTransaksi.value = numericValue;
            state.diskonInput.value = numericValue + '%';
        } else {
            if (numericValue > subtotalSetelahItem) numericValue = subtotalSetelahItem;
            state.diskonTransaksi.value = numericValue;
            state.diskonInput.value = utils.formatRupiah(numericValue);
        }

        event.target.value = state.diskonInput.value;
    };

    core.toggleDiskonMode = () => {
        state.diskonMode.value = state.diskonMode.value === 'nominal' ? 'persen' : 'nominal';
        state.diskonTransaksi.value = 0;
        state.diskonInput.value = state.diskonMode.value === 'persen' ? '0%' : '0';

        nextTick(() => {
            const inputDiskon = document.querySelector('input[placeholder="0"][maxlength="15"]');
            if (inputDiskon) {
                inputDiskon.focus();
                inputDiskon.select();
            }
        });
    };

    core.cariBarangManual = async () => {
        if (!state.keywordCari.value.trim()) return;

        state.isLoadingCari.value = true;

        try {
            const response = await axios.post('/pos/cari-barang', {
                keyword: state.keywordCari.value,
                mode: 'manual'
            });

            if (response.data.success) {
                if (response.data.data && !Array.isArray(response.data.data)) {
                    state.hasilPencarian.value = [response.data.data];
                } else {
                    state.hasilPencarian.value = response.data.data || [];
                }
            } else {
                state.hasilPencarian.value = [];
            }
        } catch (error) {
            console.error('Error mencari barang:', error);
            alert('Terjadi kesalahan saat mencari barang');
            state.hasilPencarian.value = [];
        } finally {
            state.isLoadingCari.value = false;
        }
    };

    core.tambahBarangDariPencarian = (barang) => {
        const existingIndex = state.cart.value.findIndex(
            item => item.kode_barang === barang.kode_barang
        );

        if (existingIndex >= 0) {
            const newTotal = parseFloat(state.cart.value[existingIndex].jumlah) + 1;
            if (newTotal > parseFloat(barang.stok_sekarang)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Terbatas',
                    text: `Hanya tersedia ${barang.stok_sekarang} unit untuk ${barang.nama_barang}.`,
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            state.cart.value[existingIndex].jumlah = newTotal;
            core.updateSubtotal(existingIndex);
        } else {
            if (parseFloat(barang.stok_sekarang) <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Kosong!',
                    text: `Barang "${barang.nama_barang}" tidak dapat ditambahkan karena stoknya 0.`,
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            state.cart.value.push({
                kode_barang: barang.kode_barang,
                barcode: barang.barcode,
                nama_barang: barang.nama_barang,
                harga_satuan: parseFloat(barang.harga_jual_normal),
                jumlah: 1,
                satuan: barang.satuan,
                stok_sekarang: barang.stok_sekarang,
                diskon_item: 0,
                subtotal: parseFloat(barang.harga_jual_normal)
            });
        }

        state.selectedSearchIndex.value = -1;

        const feedback = document.createElement('div');
        feedback.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg animate-pulse';
        feedback.textContent = `✓ ${barang.nama_barang} ditambahkan ke cart`;
        document.body.appendChild(feedback);

        setTimeout(() => feedback.remove(), 2000);
    };

    core.tambahSelectedBarang = () => {
        if (state.selectedSearchIndex.value >= 0 && state.selectedSearchIndex.value < state.hasilPencarian.value.length) {
            const barang = state.hasilPencarian.value[state.selectedSearchIndex.value];
            core.tambahBarangDariPencarian(barang);
        }
    };

    core.navigasiSearch = (direction) => {
        if (state.hasilPencarian.value.length === 0) return;

        let newIndex = state.selectedSearchIndex.value + direction;

        if (newIndex < 0) {
            newIndex = state.hasilPencarian.value.length - 1;
        } else if (newIndex >= state.hasilPencarian.value.length) {
            newIndex = 0;
        }

        state.selectedSearchIndex.value = newIndex;

        nextTick(() => {
            const selectedElement = document.querySelector(`[data-search-index="${newIndex}"]`);
            if (selectedElement) {
                selectedElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        });
    };

    core.tutupModalCari = () => {
        state.showModalCari.value = false;
        state.keywordCari.value = '';
        state.hasilPencarian.value = [];
        state.isLoadingCari.value = false;
        core.focusBarcode();
    };

    return core;
}