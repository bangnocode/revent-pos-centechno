
const {
    createApp,
    ref,
    computed,
    onMounted,
    onUnmounted,
    nextTick
} = Vue;

createApp({
    setup() {
        // State Management
        const state = {
            barcode: ref(''),
            cart: ref([]),
            editMode: ref(false),
            editSelectedIndex: ref(-1),
            editQtyMode: ref(false),
            showModal: ref(false),
            isProcessing: ref(false),
            tempQty: ref(1),
            lastTransaction: ref(''),
            showModalCari: ref(false),
            keywordCari: ref(''),
            hasilPencarian: ref([]),
            isLoadingCari: ref(false),
            selectedSearchIndex: ref(-1),
            diskonTransaksi: ref(0),
            diskonInput: ref('0'),
        };

        // Template Refs
        const refs = {
            barcodeInput: ref(null),
            uangDibayarInput: ref(null),
            qtyModalInput: ref(null),
            searchInput: ref(null),
        };

        // Payment Data
        const pembayaran = ref({
            nama_pelanggan: 'Pelanggan Umum',
            metode_pembayaran: 'tunai',
            uang_dibayar: 0
        });

        // Computed Properties
        const computedValues = {
            subtotal: computed(() => {
                return state.cart.value.reduce((sum, item) => {
                    const harga = parseFloat(item.harga_satuan) || 0;
                    const qty = parseFloat(item.jumlah) || 0;
                    return sum + (harga * qty);
                }, 0);
            }),

            subtotalSetelahDiskonItem: computed(() => {
                return state.cart.value.reduce((sum, item) => {
                    const harga = parseFloat(item.harga_satuan) || 0;
                    const qty = parseFloat(item.jumlah) || 0;
                    const diskonItem = parseFloat(item.diskon_item) || 0;
                    return sum + ((harga * qty) - diskonItem);
                }, 0);
            }),

            total: computed(() => {
                const subtotalSetelahItem = computedValues.subtotalSetelahDiskonItem.value;
                const diskonGlobal = parseFloat(state.diskonTransaksi.value) || 0;
                return subtotalSetelahItem - diskonGlobal;
            }),

            totalDiskon: computed(() => {
                return computedValues.subtotal.value - computedValues.total.value;
            }),

            totalQty: computed(() => state.cart.value.reduce((sum, item) =>
                sum + (parseFloat(item.jumlah) || 0), 0)),

            kembalian: computed(() => {
                const bayar = parseFloat(pembayaran.value.uang_dibayar) || 0;
                return bayar - computedValues.total.value;
            }),

            currentDate: computed(() => new Date().toLocaleDateString('id-ID')),
            currentTime: computed(() => new Date().toLocaleTimeString('id-ID')),
            selectedItem: computed(() => state.cart.value[state.editSelectedIndex.value])
        };

        // Utility Functions
        const utils = {
            formatRupiah: (angka) => {
                let num = parseFloat(angka) || 0;
                let str = Math.floor(num).toString(); // Ambil bagian integer
                return str.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            },

            formatUangDibayar: (e) => {
                // Extract only numbers from input
                let value = e.target.value.replace(/[^\d]/g, '');
                pembayaran.value.uang_dibayar = value ? parseInt(value) : 0;
            },

            printThermalReceipt: async (nomorFaktur) => {
                try {
                    // Fetch invoice data
                    const response = await axios.get(`/pos/print-invoice-data/${nomorFaktur}`);
                    const transaksi = response.data;

                    // Check if Web Serial API is supported
                    if ('serial' in navigator) {
                        const port = await navigator.serial.requestPort();
                        await port.open({ baudRate: 9600 });

                        const writer = port.writable.getWriter();
                        const encoder = new TextEncoder();

                        // ESC/POS commands for thermal printer
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

        // Core Functions
        const core = {
            focusBarcode: () => {
                if (refs.barcodeInput.value) {
                    refs.barcodeInput.value.focus();
                    refs.barcodeInput.value.select();
                }
            },

            setDiskonItem: (index, value) => {
                const item = state.cart.value[index];
                // Parse value and ensure it is a valid non-negative integer
                let diskon = Math.max(0, parseInt(value) || 0);
                const maxDiskon = item.harga_satuan * item.jumlah;

                // Limit diskon tidak lebih dari harga total item (harga * qty)
                if (diskon > maxDiskon) {
                    diskon = maxDiskon;
                }

                item.diskon_item = diskon;
                core.updateSubtotal(index);
            },

            manualInput: () => {
                state.showModalCari.value = true;
                state.keywordCari.value = '';
                state.hasilPencarian.value = [];
                state.selectedSearchIndex.value = -1; // Reset selected index

                nextTick(() => {
                    if (refs.searchInput.value) {
                        refs.searchInput.value.focus();
                    }
                });
            },

            updateSubtotal: (index) => {
                const item = state.cart.value[index];
                const harga = parseFloat(item.harga_satuan) || 0;
                const qty = parseFloat(item.jumlah) || 0;
                const diskonItem = parseFloat(item.diskon_item) || 0;

                // Subtotal = (harga * qty) - diskon item
                item.subtotal = (harga * qty) - diskonItem;
            },

            tambahBarang: async () => {
                if (!state.barcode.value.trim() || state.isProcessing.value) return;

                try {
                    const response = await axios.post('/pos/cari-barang', {
                        keyword: state.barcode.value
                    });

                    if (response.data.success) {
                        const barang = response.data.data;
                        const existingIndex = state.cart.value.findIndex(
                            item => item.kode_barang === barang.kode_barang
                        );

                        if (existingIndex >= 0) {
                            const newTotal = parseFloat(state.cart.value[existingIndex].jumlah) + 1;
                            if (newTotal > parseFloat(barang.stok_sekarang)) {
                                alert(`Stok tidak cukup untuk ${barang.nama_barang}! (Tersedia: ${barang.stok_sekarang})`);
                                state.barcode.value = '';
                                core.focusBarcode();
                                return;
                            }
                            state.cart.value[existingIndex].jumlah = newTotal;
                            core.updateSubtotal(existingIndex);
                        } else {
                            if (parseFloat(barang.stok_sekarang) < 1) {
                                alert(`Stok ${barang.nama_barang} habis!`);
                                state.barcode.value = '';
                                core.focusBarcode();
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
                                subtotal: parseFloat(barang.harga_jual_normal)
                            });
                        }

                        state.barcode.value = '';
                        core.focusBarcode();
                    } else {
                        alert(`Barang tidak ditemukan: ${state.barcode.value}`);
                        state.barcode.value = '';
                        core.focusBarcode();
                    }
                } catch (error) {
                    console.error('Error mencari barang:', error);
                    alert('Terjadi kesalahan saat mencari barang');
                    state.barcode.value = '';
                    core.focusBarcode();
                }
            },

            manualInput: () => {
                // Buka modal pencarian manual
                state.showModalCari.value = true;
                state.keywordCari.value = '';
                state.hasilPencarian.value = [];

                nextTick(() => {
                    if (refs.searchInput.value) {
                        refs.searchInput.value.focus();
                    }
                });
            },

            handleDiskonInput: (event) => {
                let value = event.target.value;
                // Allow only numbers and dots, but we'll format
                value = value.replace(/[^\d]/g, ''); // Remove non-digits for parsing
                let numericValue = value;
                let diskon = Math.max(0, parseInt(numericValue) || 0);
                const maxDiskon = computedValues.subtotalSetelahDiskonItem.value;
                state.diskonTransaksi.value = Math.min(diskon, maxDiskon);
                // Format the input value
                let formatted = utils.formatRupiah(state.diskonTransaksi.value);
                event.target.value = formatted;
                state.diskonInput.value = formatted;
            },

            cariBarangManual: async () => {
                if (!state.keywordCari.value.trim()) return;

                state.isLoadingCari.value = true;

                try {
                    const response = await axios.post('/pos/cari-barang', {
                        keyword: state.keywordCari.value,
                        mode: 'manual' // optional: untuk membedakan dengan scan barcode
                    });

                    if (response.data.success) {
                        // Jika hasil single item (kode/barcode tepat)
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
            },

            tambahBarangDariPencarian: (barang) => {
                const existingIndex = state.cart.value.findIndex(
                    item => item.kode_barang === barang.kode_barang
                );

                if (existingIndex >= 0) {
                    const newTotal = parseFloat(state.cart.value[existingIndex].jumlah) + 1;
                    if (newTotal > parseFloat(barang.stok_sekarang)) {
                        alert(`Stok tidak cukup untuk ${barang.nama_barang}! (Tersedia: ${barang.stok_sekarang})`);
                        return;
                    }
                    state.cart.value[existingIndex].jumlah = newTotal;
                    core.updateSubtotal(existingIndex);
                } else {
                    if (parseFloat(barang.stok_sekarang) < 1) {
                        alert(`Stok ${barang.nama_barang} habis!`);
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
                        subtotal: parseFloat(barang.harga_jual_normal)
                    });
                }

                // Tidak tutup modal, tapi reset selected index
                state.selectedSearchIndex.value = -1;

                // Beri feedback visual (opsional)
                const feedback = document.createElement('div');
                feedback.className =
                    'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg animate-pulse';
                feedback.textContent = `✓ ${barang.nama_barang} ditambahkan ke cart`;
                document.body.appendChild(feedback);

                setTimeout(() => feedback.remove(), 2000);
            },

            tambahSelectedBarang: () => {
                if (state.selectedSearchIndex.value >= 0 && state.selectedSearchIndex.value < state
                    .hasilPencarian.value.length) {
                    const barang = state.hasilPencarian.value[state.selectedSearchIndex.value];
                    core.tambahBarangDariPencarian(barang);
                }
            },

            navigasiSearch: (direction) => {
                if (state.hasilPencarian.value.length === 0) return;

                let newIndex = state.selectedSearchIndex.value + direction;

                // Wrap around jika melewati batas
                if (newIndex < 0) {
                    newIndex = state.hasilPencarian.value.length - 1;
                } else if (newIndex >= state.hasilPencarian.value.length) {
                    newIndex = 0;
                }

                state.selectedSearchIndex.value = newIndex;

                // Scroll ke item yang dipilih
                nextTick(() => {
                    const selectedElement = document.querySelector(
                        `[data-search-index="${newIndex}"]`);
                    if (selectedElement) {
                        selectedElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }
                });
            },

            tutupModalCari: () => {
                state.showModalCari.value = false;
                state.keywordCari.value = '';
                state.hasilPencarian.value = [];
                state.isLoadingCari.value = false;
                core.focusBarcode();
            },
        };

        // Cart Management
        const cart = {
            tambahQty: (index) => {
                const item = state.cart.value[index];
                if (parseFloat(item.jumlah) + 1 > item.stok_sekarang) {
                    alert(`Stok tidak cukup! (Tersedia: ${item.stok_sekarang})`);
                    return;
                }
                item.jumlah = parseFloat(item.jumlah) + 1;
                core.updateSubtotal(index);
            },

            kurangiQty: (index) => {
                if (state.cart.value[index].jumlah > 1) {
                    state.cart.value[index].jumlah = parseFloat(state.cart.value[index].jumlah) - 1;
                    core.updateSubtotal(index);
                } else {
                    cart.hapusBarang(index);
                }
            },

            hapusBarang: (index) => {
                if (confirm('Hapus barang dari keranjang?')) {
                    state.cart.value.splice(index, 1);
                    if (state.editMode.value && state.cart.value.length > 0) {
                        state.editSelectedIndex.value = Math.max(
                            0,
                            Math.min(state.editSelectedIndex.value, state.cart.value.length - 1)
                        );
                    }
                }
            }
        };

        // Edit Mode Management
        const editMode = {
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

            applyEditQty: () => {
                if (state.editSelectedIndex.value >= 0 && state.tempQty.value > 0) {
                    const item = state.cart.value[state.editSelectedIndex.value];
                    const newQty = parseFloat(state.tempQty.value);

                    if (newQty > item.stok_sekarang) {
                        // Let UI handle the error state but block application here too
                        return;
                    }

                    item.jumlah = newQty;
                    core.updateSubtotal(state.editSelectedIndex.value);
                    state.editQtyMode.value = false;
                } else if (state.tempQty.value <= 0) {
                    state.editQtyMode.value = false;
                }
            },

            batalEditQty: () => {
                state.editQtyMode.value = false;
                if (state.editMode.value) {
                    state.editSelectedIndex.value = -1;
                }
            },

            toggleEditMode: () => {
                if (state.cart.value.length === 0) {
                    alert('Keranjang kosong');
                    return;
                }

                state.editMode.value = !state.editMode.value;
                state.editSelectedIndex.value = state.editMode.value ? 0 : -1;

                // Hapus fokus dari input barcode saat mode edit aktif
                if (state.editMode.value) {
                    if (refs.barcodeInput.value) {
                        refs.barcodeInput.value.blur(); // Hilangkan fokus
                    }
                } else {
                    core.focusBarcode();
                }
            },

            batalEdit: () => {
                state.editMode.value = false;
                state.editSelectedIndex.value = -1;
                core.focusBarcode(); // Kembali fokus ke input setelah keluar edit mode
            }
        };

        // Payment Management
        const payment = {
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

            tutupModal: () => {
                if (state.isProcessing.value) return;

                state.showModal.value = false;
                pembayaran.value.uang_dibayar = 0;
                core.focusBarcode();
            },

            prosesPembayaran: async () => {
                // Modifikasi: Boleh kurang bayar jika metode = hutang
                const isHutang = pembayaran.value.metode_pembayaran === 'hutang';
                const kurangBayar = pembayaran.value.uang_dibayar < computedValues.total.value;

                if ((kurangBayar && !isHutang) || state.isProcessing.value) {
                    return;
                }

                state.isProcessing.value = true;

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');
                    const dataToSend = {
                        items: state.cart.value.map(item => ({
                            kode_barang: item.kode_barang,
                            nama_barang: item.nama_barang,
                            harga_satuan: parseFloat(item.harga_satuan),
                            jumlah: parseFloat(item.jumlah),
                            satuan: item.satuan,
                            diskon_item: parseFloat(item.diskon_item || 0), // Kirim diskon item
                            subtotal: parseFloat(item.subtotal) // Subtotal item setelah diskon item
                        })),
                        nama_pelanggan: pembayaran.value.nama_pelanggan,
                        metode_pembayaran: pembayaran.value.metode_pembayaran,
                        total_bayar: parseFloat(pembayaran.value.uang_dibayar),
                        subtotal: parseFloat(computedValues.subtotal.value), // Subtotal sebelum diskon
                        diskon_transaksi: parseFloat(state.diskonTransaksi.value || 0), // Diskon global
                        total_transaksi: parseFloat(computedValues.total.value), // Total setelah semua diskon
                    };

                    const response = await axios.post('/pos/simpan-transaksi', dataToSend, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.data.success) {
                        state.lastTransaction.value = response.data.nomor_faktur;
                        state.cart.value = [];
                        state.showModal.value = false;
                        state.diskonTransaksi.value = 0;

                        // Print thermal receipt
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

        // Keyboard Shortcuts
        const keyboard = {
            handleEsc: () => {
                if (state.editMode.value) {
                    editMode.batalEdit();
                } else {
                    if (document.activeElement === refs.barcodeInput.value) {
                        // Jika sudah fokus di input, tidak perlu melakukan apa-apa
                        return;
                    }
                    core.focusBarcode();
                }
            },

            handleArrowNavigation: (direction) => {
                if (!state.editMode.value || state.editSelectedIndex.value === -1) return;

                const newIndex = state.editSelectedIndex.value + direction;
                if (newIndex >= 0 && newIndex < state.cart.value.length) {
                    state.editSelectedIndex.value = newIndex;
                }
            },

            handlePlusMinus: (operation) => {
                if (!state.editMode.value || state.editSelectedIndex.value === -1) return;

                const index = state.editSelectedIndex.value;
                if (operation === 'plus') {
                    cart.tambahQty(index);
                } else if (operation === 'minus') {
                    cart.kurangiQty(index);
                }
            },

            handleKeydown: (e) => {

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

                            // Kode yg pasti bekerja:
                            if (state.showModalCari.value) {
                                if (state.hasilPencarian.value.length > 0 && state
                                    .selectedSearchIndex.value >= 0) {
                                    // Ada hasil pencarian dan ada item yg dipilih: TAMBAH KE CART
                                    core.tambahSelectedBarang();
                                } else {
                                    // Tidak ada item yg dipilih: SEARCH
                                    core.cariBarangManual();
                                }
                            }
                            break;

                        case 'Tab':
                            e.preventDefault();
                            // Alternatif: tab untuk pindah item
                            if (e.shiftKey) {
                                core.navigasiSearch(-1);
                            } else {
                                core.navigasiSearch(1);
                            }
                            break;
                    }

                    // Jangan lanjut ke handler global jika di modal pencarian
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
                        core.manualInput(); // Ganti dari alert ke fungsi baru
                        break;
                    case 'F8':
                        editMode.toggleEditMode();
                        break;
                    case 'F9':
                        payment.bukaModalPembayaran();
                        break;

                    case 'Escape':
                        if (state.showModalCari.value) {
                            core.tutupModalCari();
                        } else if (state.editQtyMode.value) {
                            e.preventDefault();
                            editMode.batalEditQty();
                            return;
                        } else if (state.showModal.value) payment.tutupModal();
                        else if (state.editMode.value) editMode.batalEdit();
                        else core.focusBarcode();
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
            }
        };

        // Lifecycle
        onMounted(() => {
            document.addEventListener('keydown', keyboard.handleKeydown, true);
            core.focusBarcode();
        });

        onUnmounted(() => {
            document.removeEventListener('keydown', keyboard.handleKeydown, true);
        });

        // Return all necessary values
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
            pembayaran,

            // Template refs
            barcodeInput: refs.barcodeInput,
            uangDibayarInput: refs.uangDibayarInput,
            qtyModalInput: refs.qtyModalInput,
            searchInput: refs.searchInput,

            // Computed
            ...Object.fromEntries(Object.entries(computedValues).map(([key, value]) => [key, value])),
            ...Object.fromEntries(Object.entries(computedValues).map(([key, value]) => [key, value])),

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
            manualInput: core.manualInput,
            cariBarangManual: core.cariBarangManual,
            tambahBarangDariPencarian: core.tambahBarangDariPencarian,
            tutupModalCari: core.tutupModalCari,
            setDiskonItem: core.setDiskonItem,
            handleDiskonInput: core.handleDiskonInput,
        };
    }
}).mount('#app');