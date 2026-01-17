<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REVENT - Centechno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for modern icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .icon-box {
            @apply w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-lg;
        }
        .feature-icon {
            @apply text-3xl sm:text-4xl mb-3 sm:mb-4 text-blue-600;
        }
    </style>
</head>
<body class="bg-white text-gray-800">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-xl sm:text-2xl font-bold text-blue-600">REVENT</span>
                </div>
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8 font-medium">
                    <a href="#fitur" class="text-gray-500 hover:text-blue-600 transition duration-200">Fitur</a>
                    <a href="#keunggulan" class="text-gray-500 hover:text-blue-600 transition duration-200">Tentang</a>
                    <a href="#footer" class="text-gyray-500 hover:text-blue-600 transition duration-200">Kontak</a>
                    <a href="{{route('login')}}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Login</a>
                </div>
                <div class="md:hidden flex items-center space-x-3">
                    <a href="{{route('login')}}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition duration-200 font-medium">Login</a>
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-blue-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 font-medium">
                <a href="#fitur" class="block py-2 text-gray-500 hover:text-blue-600 transition duration-200">Fitur</a>
                <a href="#keunggulan" class="block py-2 text-gray-500 hover:text-blue-600 transition duration-200">Tentang</a>
                <a href="#footer" class="block py-2 text-gray-500 hover:text-blue-600 transition duration-200">Kontak</a>
            </div>
        </div>
    </nav>

    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking on a link
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    </script>

    <!-- Hero Section -->
    <section class="bg-gray-50 py-12 sm:py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-6xl md:text-8xl font-bold text-blue-600 mb-6">REVENT</h1>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4 sm:mb-6 leading-tight">
                    Solusi Kasir & Inventori Toko Ritel
                </h1>
                <p class="text-base sm:text-lg text-gray-500 mb-6 sm:mb-8 px-4">
                    REVENT adalah aplikasi web Point of Sale dan manajemen inventori yang membantu pemilik toko mengelola barang, stok, supplier, pembelian, dan laporan penjualan dalam satu sistem yang sederhana dan efisien.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                    <a href="{{route('login')}}" class="bg-blue-600 text-white px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 text-center">
                        Masuk
                    </a>
                    <a href="#fitur" class="bg-white text-blue-600 px-6 sm:px-8 py-3 rounded-lg font-semibold border-2 border-blue-600 hover:bg-blue-50 transition duration-200 text-center">
                        Coba Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section id="fitur" class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">Fitur Utama</h2>
                <p class="text-base sm:text-lg text-gray-500 px-4">Semua yang Anda butuhkan untuk mengelola toko ritel dengan mudah</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                <!-- Fitur 1 -->
                <div class="bg-white border border-gray-200 rounded-lg p-5 sm:p-6 hover:shadow-lg transition duration-200">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-boxes text-blue-600 fa-2x"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Manajemen Barang & Stok</h3>
                    <p class="text-sm sm:text-base text-gray-500">Kelola data barang, kategori, harga, dan pantau stok secara real-time dengan mudah.</p>
                </div>

                <!-- Fitur 2 -->
                <div class="bg-white border border-gray-200 rounded-lg p-5 sm:p-6 hover:shadow-lg transition duration-200">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-handshake text-blue-600 fa-2x"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Manajemen Supplier</h3>
                    <p class="text-sm sm:text-base text-gray-500">Catat dan kelola data supplier beserta riwayat pembelian untuk kontrol yang lebih baik.</p>
                </div>

                <!-- Fitur 3 -->
                <div class="bg-white border border-gray-200 rounded-lg p-5 sm:p-6 hover:shadow-lg transition duration-200">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shipping-fast text-blue-600 fa-2x"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Pencatatan Kulakan</h3>
                    <p class="text-sm sm:text-base text-gray-500">Catat setiap pembelian stok dari supplier dengan detail lengkap dan terorganisir.</p>
                </div>

                <!-- Fitur 4 -->
                <div class="bg-white border border-gray-200 rounded-lg p-5 sm:p-6 hover:shadow-lg transition duration-200">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-chart-line text-blue-600 fa-2x"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Laporan Penjualan & Keuangan</h3>
                    <p class="text-sm sm:text-base text-gray-500">Dapatkan laporan penjualan dan keuangan dengan filter tanggal untuk analisis bisnis.</p>
                </div>

                <!-- Fitur 5 -->
                <div class="bg-white border border-gray-200 rounded-lg p-5 sm:p-6 hover:shadow-lg transition duration-200">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-cash-register text-blue-600 fa-2x"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Point of Sale Cepat</h3>
                    <p class="text-sm sm:text-base text-gray-500">Sistem kasir yang cepat dan mudah digunakan untuk melayani pelanggan dengan efisien.</p>
                </div>

                <!-- Fitur 6 -->
                <div class="bg-white border border-gray-200 rounded-lg p-5 sm:p-6 hover:shadow-lg transition duration-200">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-laptop text-blue-600 fa-2x"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Akses Web Responsif</h3>
                    <p class="text-sm sm:text-base text-gray-500">Akses dari berbagai perangkat tanpa perlu install aplikasi, kapan saja dan di mana saja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section id="keunggulan" class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">Mengapa Memilih REVENT?</h2>
                <p class="text-base sm:text-lg text-gray-500 px-4">Keunggulan yang membuat bisnis Anda lebih efisien</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
                <!-- Keunggulan 1 -->
                <div class="flex items-start space-x-3 sm:space-x-4">
                    <div class="icon-box text-blue-600">
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1 sm:mb-2">Cepat & Mudah Digunakan</h3>
                        <p class="text-sm sm:text-base text-gray-500">Interface yang intuitif membuat Anda langsung produktif tanpa perlu pelatihan lama.</p>
                    </div>
                </div>

                <!-- Keunggulan 2 -->
                <div class="flex items-start space-x-3 sm:space-x-4">
                    <div class="icon-box text-blue-600">
                        <i class="fas fa-bullseye fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1 sm:mb-2">Cocok Untuk Toko Kecil hingga Menengah</h3>
                        <p class="text-sm sm:text-base text-gray-500">Dirancang khusus untuk kebutuhan toko ritel dengan fitur yang tepat guna.</p>
                    </div>
                </div>

                <!-- Keunggulan 3 -->
                <div class="flex items-start space-x-3 sm:space-x-4">
                    <div class="icon-box text-blue-600">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1 sm:mb-2">Data Penjualan Tercatat Rapi</h3>
                        <p class="text-sm sm:text-base text-gray-500">Setiap transaksi tercatat otomatis, memudahkan pelacakan dan pelaporan keuangan.</p>
                    </div>
                </div>

                <!-- Keunggulan 4 -->
                <div class="flex items-start space-x-3 sm:space-x-4">
                    <div class="icon-box text-blue-600">
                        <i class="fas fa-globe fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1 sm:mb-2">Akses Web</h3>
                        <p class="text-sm sm:text-base text-gray-500">Tidak perlu install aplikasi, cukup buka browser dan mulai kelola toko Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-blue-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4 sm:mb-6 leading-tight">
                Siap Mengelola Toko Ritel Anda dengan Lebih Baik?
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-blue-100 mb-6 sm:mb-8 px-4">
                Mulai gunakan REVENT sekarang dan rasakan kemudahan mengelola inventori dan penjualan.
            </p>
            <a href="{{route('login')}}" class="inline-block bg-white text-blue-600 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg hover:bg-gray-100 transition duration-200">
                Masuk ke Aplikasi
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer" class="bg-gray-50 border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-blue-600 mb-4">REVENT</h3>
                    <p class="text-gray-500">
                        Solusi Point of Sale dan manajemen inventori untuk toko ritel yang sederhana dan efisien.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4">Fitur</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><i class="fas fa-boxes mr-2 text-blue-600"></i>Manajemen Barang</li>
                        <li><i class="fas fa-cash-register mr-2 text-blue-600"></i>Point of Sale</li>
                        <li><i class="fas fa-chart-line mr-2 text-blue-600"></i>Laporan Penjualan</li>
                        <li><i class="fas fa-handshake mr-2 text-blue-600"></i>Manajemen Supplier</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><i class="fas fa-envelope mr-2 text-blue-600"></i>Email: info@revent.id</li>
                        <li><i class="fas fa-phone mr-2 text-blue-600"></i>Telepon: (021) 1234-5678</li>
                        <li><i class="fab fa-whatsapp mr-2 text-blue-600"></i>WhatsApp: +62 812-3456-7890</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-8 text-center text-gray-500">
                <p>&copy; 2025 REVENT by Centechno.</p>
            </div>
        </div>
    </footer>

</body>
</html>