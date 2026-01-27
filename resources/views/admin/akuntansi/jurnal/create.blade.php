@extends('admin.layout.app')

@section('content')
<div x-data="jurnalApp()" class="flex flex-col h-[calc(100vh-80px)] -mt-5">
    <!-- HEADER & CART TABLE -->
    <div class="flex-grow overflow-auto space-y-6 pb-60 sm:pb-48">
        <!-- Header Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2.5 sm:p-3 top-3 sticky z-20">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-sm sm:text-base font-bold text-gray-800">Pencatatan Jurnal</h2>

                <div class="flex gap-1.5 text-xs sm:text-sm font-bold">
                    <div class="bg-green-50 px-2.5 py-1.5 rounded border border-green-200 text-green-700">
                        <span class="hidden sm:inline">D: </span><span x-text="formatRupiah(totalDebit)"></span>
                    </div>
                    <div class="bg-red-50 px-2.5 py-1.5 rounded border border-red-200 text-red-700">
                        <span class="hidden sm:inline">K: </span><span x-text="formatRupiah(totalKredit)"></span>
                    </div>
                    <div class="px-2.5 py-1.5 rounded border font-bold"
                        :class="isBalanced ? 'bg-blue-50 text-blue-700 border-blue-200' :
                                'bg-amber-50 text-amber-700 border-amber-200'">
                        <span x-text="isBalanced ? '✓' : '⚠'"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto text-sm">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-gray-500 font-bold uppercase text-xs">
                            <th class="px-3 py-2.5 text-left w-16 uppercase">Tgl</th>
                            <th class="px-3 py-2.5 text-left w-20 uppercase">Kode</th>
                            <th class="px-3 py-2.5 text-left uppercase">Rekening</th>
                            <th class="px-3 py-2.5 text-left hidden md:table-cell uppercase">Keterangan</th>
                            <th class="px-3 py-2.5 text-right w-28 uppercase">Debit</th>
                            <th class="px-3 py-2.5 text-right w-28 uppercase">Kredit</th>
                            <th class="px-3 py-2.5 w-10 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in cart" :key="index">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3 text-gray-500 font-mono" x-text="formatDate(item.tgl)"></td>
                                <td class="px-3 py-3">
                                    <span class="font-mono text-blue-700 font-bold" x-text="item.koder"></span>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="font-bold text-gray-800 truncate max-w-[120px] sm:max-w-none" x-text="item.nama_rekening"></div>
                                    <div class="md:hidden text-xs text-gray-400 truncate max-w-[120px]" x-text="item.ket"></div>
                                </td>
                                <td class="px-3 py-3 text-gray-400 italic hidden md:table-cell" x-text="item.ket || '-'"></td>
                                <td class="px-3 py-3 text-right font-mono text-green-600 font-bold" x-text="item.dk === 'D' ? formatRupiah(item.nominal) : '-'"></td>
                                <td class="px-3 py-3 text-right font-mono text-red-600 font-bold" x-text="item.dk === 'K' ? formatRupiah(item.nominal) : '-'"></td>
                                <td class="px-3 py-3 text-center">
                                    <button @click="removeItem(index)" class="text-red-300 hover:text-red-500 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="cart.length === 0">
                            <td colspan="7" class="text-center py-12 text-gray-300">
                                <p class="text-[10px] font-bold uppercase tracking-widest">Belum ada item ditambahkan</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- FORM INPUT - FIXED AT BOTTOM -->
    <div class="fixed bottom-0 left-0 md:left-56 right-0 bg-white border-t border-gray-200 shadow-[0_-15px_40px_rgb(0,0,0,0.1)] z-30">
        <div class="p-4 sm:p-5 space-y-2 max-w-screen-2xl mx-auto">
            
            <!-- GRID 1: Tanggal, Kode Akun, Nama Akun, Nominal (Desktop: Single Row) -->
            <div class="grid grid-cols-12 gap-3 items-end">
                <div class="col-span-12 sm:col-span-2">
                    <label class="text-xs text-gray-400 font-bold mb-1 block truncate">Tanggal transaksi</label>
                    <input type="date" x-model="lineItem.tgl"
                        class="w-full text-sm px-3 h-10 rounded-lg border border-gray-200 focus:border-blue-400 focus:ring-1 focus:ring-blue-100 outline-none bg-gray-50/50 font-medium transition-all">
                </div>

                <div class="col-span-6 sm:col-span-2 relative">
                    <label class="text-xs text-gray-400 font-bold mb-1 block truncate leading-none">Kode akun</label>
                    <input type="text" x-model="lineItem.koder" @input.debounce.700ms="searchRekening"
                        @keydown.enter.prevent="searchRekening" placeholder="1XXX"
                        :class="rekeningStatus === 'not_found' ? 'border-red-300 bg-red-50' : (rekeningStatus === 'found' ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-white')"
                        class="w-full text-sm px-3 h-10 rounded-lg border focus:ring-1 focus:ring-blue-100 transition-all font-mono font-bold">
                    <div x-show="isSearching" class="absolute right-2 top-7">
                        <svg class="animate-spin h-4 w-4 text-blue-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-5">
                    <label class="text-xs text-gray-400 font-bold mb-1 block truncate leading-none">Nama akun</label>
                    <input type="text" readonly :value="lineItem.nama_rekening" placeholder="..."
                        class="w-full text-sm px-3 h-10 rounded-lg border border-gray-100 bg-gray-50 text-gray-700 font-semibold truncate transition-all">
                </div>

                <div class="col-span-6 sm:col-span-3 hidden sm:block">
                    <label class="text-xs text-gray-400 font-bold mb-1 block truncate leading-none">Nominal (Rp)</label>
                    <input x-ref="amountInputDesktop" type="text" :value="nominalFormatted" inputmode="numeric"
                        @input="handleNominalInput($event.target.value)" @keydown.enter.prevent="tryAddToCart" placeholder="0"
                        class="w-full text-base px-3 h-10 rounded-lg border border-gray-100 outline-none text-right font-mono font-bold text-gray-700 bg-white shadow-sm">
                </div>
            </div>

            <!-- GRID 2: Nominal (Mobile), Keterangan, D/K, Buttons -->
            <div class="grid grid-cols-12 gap-3 items-end">
                <!-- Mobile only Nominal -->
                <div class="col-span-6 sm:hidden">
                    <label class="text-sm text-blue-500 font-bold mb-1 block leading-none">Nominal (Rp)</label>
                    <input x-ref="amountInputMobile" type="text" :value="nominalFormatted" inputmode="numeric"
                        @input="handleNominalInput($event.target.value)" @keydown.enter.prevent="tryAddToCart" placeholder="0"
                        class="w-full text-base px-3 h-10 rounded-lg border border-blue-200 text-right font-mono font-bold text-blue-700 bg-white">
                </div>

                <div class="col-span-6 sm:col-span-7">
                    <label class="text-xs text-gray-400 font-bold mb-1 block leading-none">Keterangan</label>
                    <input type="text" x-model="lineItem.ket" placeholder="Keterangan transaksi..." 
                        class="w-full text-sm px-3 h-10 rounded-lg border border-gray-200 focus:border-blue-400 outline-none bg-white transition-all">
                </div>

                <div class="col-span-12 sm:col-span-2">
                     <label class="text-xs text-gray-400 font-bold mb-1 block text-center leading-none">Debet/Kredit</label>
                     <select x-model="lineItem.dk" class="w-full text-sm h-10 rounded-lg border border-gray-200 font-bold text-center appearance-none cursor-pointer transition-all" :class="lineItem.dk === 'D' ? 'text-green-600 bg-green-50 border-green-200' : 'text-red-600 bg-red-50 border-red-200'">
                        <option value="D">DEBET</option>
                        <option value="K">KREDIT</option>
                     </select>
                </div>

                <div class="col-span-12 sm:col-span-3 grid grid-cols-2 gap-2">
                    <button type="button" @click="tryAddToCart" :disabled="!isValidLine"
                        class="bg-slate-800 text-white h-11 rounded-xl text-xs font-bold tracking-widest transition-all active:scale-95 disabled:opacity-20 flex items-center justify-center gap-1.5 hover:bg-slate-900">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        Tambah
                    </button>
                    <button type="button" @click="submitJurnal" :disabled="!canSubmit || isSubmitting"
                        class="bg-blue-600 text-white h-11 rounded-xl text-xs font-bold tracking-widest shadow-lg shadow-blue-100/50 hover:bg-blue-700 transition-all active:scale-95 disabled:opacity-20 flex items-center justify-center gap-1.5">
                        <span x-show="!isSubmitting">✓ Simpan</span>
                        <span x-show="isSubmitting" class="animate-spin text-lg">.</span>
                    </button>
                </div>
            </div>
            
            <div x-show="rekeningStatus === 'not_found'" class="text-[10px] text-red-500 font-bold bg-red-50 px-2 py-0.5 rounded inline-block">
                ⚠️ Rekening tidak ditemukan
            </div>
        </div>
    </div>
</div>

<script>
function jurnalApp() {
    return {
        cart: [],
        lineItem: {
            tgl: new Date().toISOString().split('T')[0],
            rekening_id: '',
            koder: '',
            nama_rekening: '',
            ket: '',
            nominal: 0,
            dk: 'D'
        },
        nominalFormatted: '',
        rekeningStatus: 'idle',
        isSearching: false,
        isSubmitting: false,

        get totalDebit() {
            return this.cart.reduce((sum, item) => sum + (item.dk === 'D' ? item.nominal : 0), 0);
        },

        get totalKredit() {
            return this.cart.reduce((sum, item) => sum + (item.dk === 'K' ? item.nominal : 0), 0);
        },

        get isBalanced() {
            return this.cart.length > 0 && 
                   Math.abs(this.totalDebit - this.totalKredit) < 0.01 && 
                   this.totalDebit > 0;
        },

        get isValidLine() {
            return this.rekeningStatus === 'found' && this.lineItem.nominal > 0;
        },

        get canSubmit() {
            return this.isBalanced;
        },

        formatRupiah(val) {
            return new Intl.NumberFormat('id-ID').format(val);
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return d.getDate().toString().padStart(2, '0') + '/' + (d.getMonth() + 1).toString().padStart(2, '0');
        },

        async searchRekening() {
            if (!this.lineItem.koder) {
                this.resetAccountState();
                return;
            }

            this.isSearching = true;
            this.rekeningStatus = 'searching';

            try {
                const url = `{{ route("admin.rekening.search") }}?keyword=${encodeURIComponent(this.lineItem.koder)}`;
                const response = await fetch(url);
                const result = await response.json();

                if (result.success) {
                    this.lineItem.rekening_id = result.data.id;
                    this.lineItem.nama_rekening = result.data.nama_rekening;
                    this.rekeningStatus = 'found';
                } else {
                    this.resetAccountState();
                    this.rekeningStatus = 'not_found';
                }
            } catch (error) {
                console.error('Search error:', error);
                this.rekeningStatus = 'not_found';
            } finally {
                this.isSearching = false;
            }
        },

        resetAccountState() {
            this.lineItem.rekening_id = '';
            this.lineItem.nama_rekening = '';
            this.rekeningStatus = 'idle';
        },

        handleNominalInput(val) {
            // Hanya ambil angka saja
            const raw = val.replace(/\D/g, '');
            const num = parseInt(raw) || 0;
            
            this.lineItem.nominal = num;
            this.nominalFormatted = num > 0 ? new Intl.NumberFormat('id-ID').format(num) : '';
            
            // Re-sync input value to show formatted version even if someone tries to type letters
            this.$nextTick(() => {
                if (this.$refs.amountInputDesktop) this.$refs.amountInputDesktop.value = this.nominalFormatted;
                if (this.$refs.amountInputMobile) this.$refs.amountInputMobile.value = this.nominalFormatted;
            });
        },

        tryAddToCart() {
            if (!this.isValidLine) return;

            this.cart.push({ ...this.lineItem });

            this.lineItem.koder = '';
            this.lineItem.nama_rekening = '';
            this.lineItem.nominal = 0;
            this.lineItem.rekening_id = '';
            this.lineItem.ket = '';
            this.nominalFormatted = '';
            this.rekeningStatus = 'idle';

            this.$nextTick(() => {
                const els = document.querySelectorAll('input');
                // Target index 1 for Code Account input search
                if (els[1]) els[1].focus();
            });
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        async submitJurnal() {
            if (!this.canSubmit || this.isSubmitting) return;
            if (!confirm('Simpan pencatatan jurnal ini?')) return;

            this.isSubmitting = true;

            const payload = {
                tanggal: this.cart[0]?.tgl || new Date().toISOString().split('T')[0],
                keterangan_jurnal: this.cart[0]?.ket || 'Jurnal Umum',
                details: this.cart.map(item => ({
                    rekening_id: item.rekening_id,
                    debit: item.dk === 'D' ? item.nominal : 0,
                    kredit: item.dk === 'K' ? item.nominal : 0,
                    keterangan: item.ket
                }))
            };

            try {
                const response = await fetch('{{ route("admin.jurnal.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Jurnal berhasil disimpan');
                    window.location.href = result.redirect_url;
                } else {
                    alert('Gagal menyimpan: ' + (result.message || JSON.stringify(result.errors)));
                }
            } catch (error) {
                console.error('Submit error:', error);
                alert('Terjadi kesalahan jaringan');
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
@endsection
