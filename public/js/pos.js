/**
 * POS Application - Modular Version
 * Menggabungkan semua modul POS dalam satu file untuk kompatibilitas browser
 */

const {
    createApp,
    ref,
    computed,
    onMounted,
    onUnmounted,
    nextTick
} = Vue;

// POS State Management
function createPosState() {
    return {
        barcode: ref(''),
        cart: ref([]),
        editMode: ref(false),
        editSelectedIndex: ref(-1),
        editQtyMode: ref(false),
        showModal: ref(false),
        showModalCari: ref(false),
        isProcessing: ref(false),
        tempQty: ref(1),
        lastTransaction: ref(''),
        keywordCari: ref(''),
        hasilPencarian: ref([]),
        isLoadingCari: ref(false),
        selectedSearchIndex: ref(-1),
        diskonTransaksi: ref(0),
        diskonInput: ref('0'),
        diskonMode: ref('nominal'),
    };
}

function createRefs() {
    return {
        barcodeInput: ref(null),
        uangDibayarInput: ref(null),
        qtyModalInput: ref(null),
        searchInput: ref(null),
    };
}

function createPaymentData() {
    return ref({
        nama_pelanggan: 'Pelanggan Umum',
        metode_pembayaran: 'tunai',
        uang_dibayar: 0
    });
}

// POS Utils
const utils = {
    formatRupiah: (angka) => {
        let num = parseFloat(angka) || 0;
        let str = Math.floor(num).toString();
        return str.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    formatUangDibayar: (e) => {
        let value = e.target.value.replace(/[^\d]/g, '');
        return value ? parseInt(value) : 0;
    },

    handleUangDibayarInput: (e, pembayaran) => {
        let value = e.target.value.replace(/[^\d]/g, '');
        let num = parseInt(value) || 0;
        pembayaran.uang_dibayar = num;
        // Format display dengan titik ribuan
        e.target.value = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    printThermalReceipt: async (nomorFaktur) => {
        try {
            const response = await axios.get(`/pos/print-invoice-data/${nomorFaktur}`);
            const transaksi = response.data;

            if ('serial' in navigator) {
                const port = await navigator.serial.requestPort();
                await port.open({ baudRate: 9600 });

                const writer = port.writable.getWriter();
                const encoder = new TextEncoder();

                let receipt = '\x1B\x40';
                receipt += '\x1B\x61\x01';
                receipt += 'TOKO CT\n';
                receipt += 'Jl. Contoh No. 123\n';
                receipt += 'Telp: 0812-3456-7890\n';
                receipt += '\n';
                receipt += `FAKTUR: ${transaksi.nomor_faktur}\n`;
                receipt += `Tanggal: ${new Date(transaksi.tanggal_transaksi).toLocaleString('id-ID')}\n`;
                receipt += `Kasir: ${transaksi.id_operator}\n`;
                receipt += `Pelanggan: ${transaksi.nama_pelanggan}\n`;
                receipt += '\x1B\x61\x00';
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
                receipt += '\x1D\x56\x42\x00';

                await writer.write(encoder.encode(receipt));
                await writer.close();
                await port.close();
            } else {
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

// POS Computed Values
function createComputedValues(state, pembayaran) {
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

    computedValues.diskonInputFormatted = computed({
        get: () => {
            const num = parseFloat(state.diskonTransaksi.value) || 0;
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        set: (value) => {
            const num = parseInt(value.replace(/[^\d]/g, '')) || 0;
            let finalValue = num;

            // Validasi maksimal
            if (state.diskonMode.value === 'persen') {
                if (finalValue > 100) finalValue = 100;
            } else {
                // Mode nominal - maksimal subtotalSetelahDiskonItem
                const maxDiskon = computedValues.subtotalSetelahDiskonItem.value;
                if (finalValue > maxDiskon) finalValue = maxDiskon;
            }

            state.diskonTransaksi.value = finalValue;
            state.diskonInput.value = finalValue.toString();
        }
    });

    computedValues.currentDate = computed(() => new Date().toLocaleDateString('id-ID'));
    computedValues.currentTime = computed(() => new Date().toLocaleTimeString('id-ID'));
    computedValues.selectedItem = computed(() => state.cart.value[state.editSelectedIndex.value]);

    return computedValues;
}

// POS Core Functions
function createCoreFunctions(state, refs, utils) {
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

    core.toggleDiskonMode = () => {
        state.diskonMode.value = state.diskonMode.value === 'nominal' ? 'persen' : 'nominal';
        // Reset diskon ke 0
        state.diskonTransaksi.value = 0;
        state.diskonInput.value = '0';
    };

    return core;
}

// POS Payment Functions

// POS Cart Functions
function createCartFunctions(state, core) {
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

// POS Edit Functions
function createEditFunctions(state, refs, core) {
    return {
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Terbatas',
                        text: `Hanya tersedia ${item.stok_sekarang} unit untuk ${item.nama_barang}.`,
                        confirmButtonColor: '#3b82f6'
                    });
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

            if (state.editMode.value) {
                if (refs.barcodeInput.value) {
                    refs.barcodeInput.value.blur();
                }
            } else {
                core.focusBarcode();
            }
        },

        batalEdit: () => {
            state.editMode.value = false;
            state.editSelectedIndex.value = -1;
            core.focusBarcode();
        }
    };
}

// POS Payment Functions
function createPaymentFunctions(state, pembayaran, computedValues, utils, core, refs) {
    return {
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
            // Reset ke default saat modal ditutup
            pembayaran.value.nama_pelanggan = 'Pelanggan Umum';
            pembayaran.value.metode_pembayaran = 'tunai';
            pembayaran.value.uang_dibayar = 0;
            core.focusBarcode();
        },

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

                    // Reset cart dan diskon
                    state.cart.value = [];
                    state.diskonTransaksi.value = 0;
                    state.diskonInput.value = '0';
                    state.diskonMode.value = 'nominal';

                    // Reset data pembeli dan pembayaran ke default
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

// POS Keyboard Functions
function createKeyboardFunctions(state, core, cart, editMode, payment, refs) {
    const keyboard = {};

    keyboard.handleEsc = () => {
        if (state.editMode.value) {
            editMode.batalEdit();
        } else {
            if (document.activeElement === refs.barcodeInput.value) {
                return;
            }
            core.focusBarcode();
        }
    };

    keyboard.handleArrowNavigation = (direction) => {
        if (!state.editMode.value || state.editSelectedIndex.value === -1) return;

        const newIndex = state.editSelectedIndex.value + direction;
        if (newIndex >= 0 && newIndex < state.cart.value.length) {
            state.editSelectedIndex.value = newIndex;
        }
    };

    keyboard.handlePlusMinus = (operation) => {
        if (!state.editMode.value || state.editSelectedIndex.value === -1) return;

        const index = state.editSelectedIndex.value;
        if (operation === 'plus') {
            cart.tambahQty(index);
        } else if (operation === 'minus') {
            cart.kurangiQty(index);
        }
    };

    keyboard.handleKeydown = (e) => {
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

                    if (state.hasilPencarian.value.length > 0 && state.selectedSearchIndex.value >= 0) {
                        core.tambahSelectedBarang();
                    } else {
                        core.cariBarangManual();
                    }
                    break;

                case 'Tab':
                    e.preventDefault();
                    if (e.shiftKey) {
                        core.navigasiSearch(-1);
                    } else {
                        core.navigasiSearch(1);
                    }
                    break;
            }

            return;
        }

        const fKeysUsedByApp = ['F2', 'F3', 'F8', 'F9'];
        if (fKeysUsedByApp.includes(e.key)) {
            e.preventDefault();
            e.stopPropagation();
        }

        switch (e.key) {
            case 'F2':
                core.focusBarcode();
                break;
            case 'F3':
                e.preventDefault();
                core.manualInput();
                break;
            case 'F8':
                editMode.toggleEditMode();
                break;
            case 'F9':
                payment.bukaModalPembayaran();
                break;

            case 'Escape':
                if (state.editQtyMode.value) {
                    e.preventDefault();
                    editMode.batalEditQty();
                    return;
                } else if (state.showModal.value) {
                    payment.tutupModal();
                } else if (state.editMode.value) {
                    editMode.batalEdit();
                } else {
                    core.focusBarcode();
                }
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
    };

    return keyboard;
}

// Initialize POS Application
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
        onMounted(() => {
            document.addEventListener('keydown', keyboard.handleKeydown, true);
            core.focusBarcode();
        });

        onUnmounted(() => {
            document.removeEventListener('keydown', keyboard.handleKeydown, true);
        });

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
            toggleDiskonMode: core.toggleDiskonMode,
        };
    }
}).mount('#app');