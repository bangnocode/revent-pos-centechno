
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>REVENT - Centechno</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Heroicons CDN -->
    <script src="https://unpkg.com/@heroicons/vue@2.0.0/outline/index.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Thermal Printer Library -->
    <script src="https://cdn.jsdelivr.net/npm/escpos@3.0.0-alpha.6/dist/escpos.min.js"></script>

    <style>
        [v-cloak] {
            display: none;
        }

        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }

            body {
                font-size: 12px;
                padding: 10px;
            }

            button {
                display: none;
            }
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .focused-item {
            background-color: #fef3c7 !important;
            outline: 2px solid #f59e0b;
        }

        .focused-search-item {
            background-color: #eff6ff !important;
            outline: 2px solid #3b82f6;
        }

        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div id="app" v-cloak>
        <!-- Header Component -->
        @include('pos.components.header')

        <main class="px-3 sm:px-4 py-3 bg-gray-50 min-h-screen">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 max-w-7xl mx-auto">
                <!-- Kolom Kiri -->
                <div class="lg:col-span-2 space-y-3">
                    <!-- Input Section Component -->
                    @include('pos.components.input-section')

                    <!-- Cart Component -->
                    @include('pos.components.cart')
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-3">
                    <!-- Summary Component -->
                    @include('pos.components.summary')
                </div>
            </div>
        </main>

        <!-- Modals Component -->
        @include('pos.components.modals')
    </div>

    <!-- Vue 3 Production CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="{{ asset('js/pos.js') }}?v={{ file_exists(public_path('js/pos.js')) ? filemtime(public_path('js/pos.js')) : time() }}"></script>
