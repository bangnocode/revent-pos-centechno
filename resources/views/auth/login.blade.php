<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revent - Centechno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="px-8 py-8 md:px-10">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-blue-600">REVENT</h1>
                <p class="text-gray-500 text-sm mt-1">Silakan login untuk melanjutkan</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm"
                            placeholder="admin@example.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" 
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium py-2.5 rounded-lg transition-colors shadow-lg">
                        Masuk
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} Revent CENTECHNO
                </p>
            </div>
        </div>
    </div>
</body>
</html>
