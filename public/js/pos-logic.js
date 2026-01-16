
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
                const num = parseFloat(angka) || 0;
                return new Intl.NumberFormat('id-ID').format(num);
            },

            formatUangDibayar: () => {
                let value = pembayaran.value.uang_dibayar.toString().replace(/[^\d]/g, '');
                pembayaran.value.uang_dibayar = value ? parseInt(value) : 0;
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
                const diskon = Math.max(0, parseFloat(value) || 0);
                const maxDiskon = item.harga_satuan * item.jumlah;

                // Limit diskon tidak lebih dari harga item
                item.diskon_item = Math.min(diskon, maxDiskon);
                core.updateSubtotal(index);
            },

            setDiskonTransaksi: (value) => {
                const diskon = Math.max(0, parseFloat(value) || 0);
                const maxDiskon = computedValues.subtotalSetelahDiskonItem.value;
                state.diskonTransaksi.value = Math.min(diskon, maxDiskon);
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
                            state.cart.value[existingIndex].jumlah =
                                parseFloat(state.cart.value[existingIndex].jumlah) + 1;
                            core.updateSubtotal(existingIndex);
                        } else {
                            state.cart.value.push({
                                kode_barang: barang.kode_barang,
                                barcode: barang.barcode,
                                nama_barang: barang.nama_barang,
                                harga_satuan: parseFloat(barang.harga_jual_normal),
                                jumlah: 1,
                                satuan: barang.satuan,
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
                    state.cart.value[existingIndex].jumlah =
                        parseFloat(state.cart.value[existingIndex].jumlah) + 1;
                    core.updateSubtotal(existingIndex);
                } else {
                    state.cart.value.push({
                        kode_barang: barang.kode_barang,
                        barcode: barang.barcode,
                        nama_barang: barang.nama_barang,
                        harga_satuan: parseFloat(barang.harga_jual_normal),
                        jumlah: 1,
                        satuan: barang.satuan,
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
                state.cart.value[index].jumlah = parseFloat(state.cart.value[index].jumlah) + 1;
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
                    state.cart.value[state.editSelectedIndex.value].jumlah = parseFloat(state
                        .tempQty.value);
                    core.updateSubtotal(state.editSelectedIndex.value);
                }
                state.editQtyMode.value = false;
                if (state.editMode.value) {
                    // Jangan reset editSelectedIndex, biarkan tetap pada item yang diedit
                    // editSelectedIndex.value = -1; // JANGAN DILAKUKAN
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

                        // Buka window untuk print dengan parameter autoprint
                        const printWindow = window.open(
                            `/pos/print-invoice/${response.data.nomor_faktur}?autoprint=true`,
                            '_blank',
                            'width=400,height=600'
                        );

                        // Atau langsung print setelah beberapa detik
                        setTimeout(() => {
                            if (printWindow && !printWindow.closed) {
                                printWindow.print();
                                // Optional: close setelah 5 detik
                                // setTimeout(() => printWindow.close(), 5000);
                            }
                        }, 1000);

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
            ...Object.fromEntries(Object.entries(state).map(([key, value]) => [key, value])),
            showModalCari: state.showModalCari,
            keywordCari: state.keywordCari,
            hasilPencarian: state.hasilPencarian,
            isLoadingCari: state.isLoadingCari,
            selectedSearchIndex: state.selectedSearchIndex,
            pembayaran,

            // Template refs
            ...Object.fromEntries(Object.entries(refs).map(([key, value]) => [key, value])),
            searchInput: refs.searchInput,

            // Computed
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
            tutupModalCari: core.tutupModalCari
        };
    }
}).mount('#app');