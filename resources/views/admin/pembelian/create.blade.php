@extends('admin.layout.app')

@section('content')
<div class="max-w-5xl mx-auto" x-data="kulakanHandler()">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Input Kulakan (PO)</h2>
            <p class="text-sm text-gray-500">Catat pembelian barang dari supplier</p>
        </div>
    </div>

    <form @submit.prevent="submitForm">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Transaction Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                    <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">Info Transaksi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Faktur</label>
                            <input type="text" x-model="form.nomor_faktur" readonly
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="datetime-local" x-model="form.tanggal" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                            <select x-model="form.supplier_id" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                            <select x-model="form.metode_pembayaran" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                                <option value="tunai">Tunai</option>
                                <option value="hutang">Hutang</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea x-model="form.keterangan" rows="2"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                     <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">Ringkasan</h3>
                     <div class="flex justify-between items-center mb-2">
                         <span class="text-gray-600">Total Item:</span>
                         <span class="font-medium" x-text="form.items.length"></span>
                     </div>
                     <div class="flex justify-between items-center text-lg font-bold text-gray-900 mt-4 pt-4 border-t border-dashed">
                         <span>Grand Total:</span>
                         <span x-text="formatRupiah(grandTotal)"></span>
                     </div>
                     <button type="submit" 
                        :disabled="isSubmitting || form.items.length === 0"
                        class="w-full mt-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors shadow-sm flex justify-center items-center gap-2">
                        <svg x-show="isSubmitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Transaksi'"></span>
                     </button>
                </div>
            </div>

            <!-- Right: Items -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">List Barang</h3>

                <!-- Add Item Form -->
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="space-y-4">
                        <!-- Row 1: Kode Barang and Quantity -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang <span class="text-red-500">*</span></label>
                                <input type="text" x-model="newItem.kode_barang" @input="validateKodeBarang"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                    placeholder="Masukkan kode barang">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                                <input type="number" x-model.number="newItem.jumlah" min="1" step="1" maxlength="6" max="100000"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                    placeholder="0">
                            </div>
                        </div>

                        <!-- Row 2: Validation Message and Button -->
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                            <div class="flex-1">
                                <div x-show="validationMessage" class="text-sm" :class="isValidKode ? 'text-green-600' : 'text-red-600'" x-text="validationMessage"></div>
                                <div x-show="selectedBarang" class="mt-1 text-sm text-gray-600">
                                    <strong x-text="selectedBarang.nama_barang"></strong> | Stok: <span x-text="selectedBarang.stok_sekarang"></span> | Harga Jual: <span x-text="formatRupiah(selectedBarang.harga_jual_normal)"></span>
                                </div>
                            </div>
                            <div class="w-full sm:w-auto">
                                <button type="button" @click="addItem" :disabled="!isValidKode || !newItem.jumlah"
                                    class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors text-sm">
                                    Tambah Barang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Barang</th>
                                <th class="px-4 py-3 w-24 text-center">Qty</th>
                                <th class="px-4 py-3 w-32 text-right">Harga Beli</th>
                                <th class="px-4 py-3 w-32 text-right">Harga Jual</th>
                                <th class="px-4 py-3 w-32 text-right">Subtotal</th>
                                <th class="px-4 py-3 w-10 text-center rounded-r-lg"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(item, index) in form.items" :key="index">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900" x-text="item.nama_barang"></div>
                                        <div class="text-xs text-slate-500" x-text="item.kode_barang"></div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.jumlah" @input="item.jumlah = formatNumberRibuan($event.target.value)"
                                            class="w-full px-2 py-1 border border-gray-200 rounded text-center focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm" inputmode="numeric">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.harga_beli" @input="item.harga_beli = formatNumberRibuan($event.target.value)"
                                            class="w-full px-2 py-1 border border-gray-200 rounded text-right focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm" inputmode="numeric">
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="text-gray-900" x-text="formatRupiah(item.harga_jual)"></div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium text-gray-900" x-text="formatRupiah(unformatNumberRibuan(item.jumlah) * unformatNumberRibuan(item.harga_beli))">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="form.items.length === 0">
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-sm">
                                    Belum ada barang dipilih. Klik tombol + Tambah Barang.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
window.unformatNumberRibuan = function(n) {
    if (!n) return "";
    return String(n).replace(/\./g, "");
};

window.formatNumberRibuan = function(n) {
    if (!n) return "";
    return String(n).replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

document.addEventListener('alpine:init', () => {
    Alpine.data('kulakanHandler', () => ({
        form: {
            nomor_faktur: '{{ $nomorFaktur }}',
            tanggal: new Date().toISOString().slice(0, 16),
            supplier_id: '',
            metode_pembayaran: '',
            keterangan: '',
            items: []
        },
        newItem: {
            kode_barang: '',
            jumlah: 0
        },
        selectedBarang: null,
        isValidKode: false,
        validationMessage: '',
        isSubmitting: false,

        get grandTotal() {
            const total = this.form.items.reduce((sum, item) => {
                const qty = parseFloat(unformatNumberRibuan(item.jumlah)) || 0;
                const harga = parseFloat(unformatNumberRibuan(item.harga_beli)) || 0;
                return sum + (qty * harga);
            }, 0);
            return Math.round(total);
        },

        async validateKodeBarang() {
            if (this.newItem.kode_barang.length < 2) {
                this.isValidKode = false;
                this.validationMessage = '';
                this.selectedBarang = null;
                return;
            }
            try {
                const response = await fetch(`{{ route("admin.barang.search") }}?keyword=${this.newItem.kode_barang}`);
                const data = await response.json();
                const barang = data.find(b => b.kode_barang === this.newItem.kode_barang);
                if (barang) {
                    this.isValidKode = true;
                    this.validationMessage = 'Barang ditemukan';
                    this.selectedBarang = barang;
                } else {
                    this.isValidKode = false;
                    this.validationMessage = 'Kode barang tidak ditemukan';
                    this.selectedBarang = null;
                }
            } catch (e) {
                this.isValidKode = false;
                this.validationMessage = 'Error validasi';
                this.selectedBarang = null;
                console.error(e);
            }
        },

        addItem() {
            if (!this.isValidKode || !this.newItem.jumlah) return;
            // Check if already in cart
            const existing = this.form.items.find(i => i.kode_barang === this.newItem.kode_barang);
            if (existing) {
                existing.jumlah = (parseFloat(existing.jumlah) + parseFloat(this.newItem.jumlah)).toString();
            } else {
                this.form.items.push({
                    kode_barang: this.newItem.kode_barang,
                    nama_barang: this.selectedBarang.nama_barang,
                    jumlah: this.newItem.jumlah.toString(),
                    harga_beli: formatNumberRibuan(Math.round(this.selectedBarang.harga_beli_terakhir || 0)),
                    harga_jual: Math.round(this.selectedBarang.harga_jual_normal || 0)
                });
            }
            // Reset
            this.newItem.kode_barang = '';
            this.newItem.jumlah = 0;
            this.selectedBarang = null;
            this.isValidKode = false;
            this.validationMessage = '';
        },

        removeItem(index) {
            this.form.items.splice(index, 1);
        },

        async submitForm() {
            if (this.form.items.length === 0) return;
            if (!confirm('Simpan transaksi pembelian ini? Stok barang akan bertambah.')) return;

            this.isSubmitting = true;
            // Prepare data by unformatting numbers
            const preparedForm = {
                ...this.form,
                items: this.form.items.map(item => ({
                    ...item,
                    jumlah: unformatNumberRibuan(item.jumlah),
                    harga_beli: unformatNumberRibuan(item.harga_beli)
                }))
            };

            try {
                const response = await fetch('{{ route("admin.pembelian.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(preparedForm)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert('Berhasil! ' + result.message);
                    window.location.href = result.redirect;
                } else {
                    alert('Gagal: ' + (result.message || 'Terjadi kesalahan sistem'));
                }
            } catch (e) {
                alert('Connection Error');
                console.error(e);
            } finally {
                this.isSubmitting = false;
            }
        },

        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number || 0);
        }
    }));
});
</script>
@endsection
