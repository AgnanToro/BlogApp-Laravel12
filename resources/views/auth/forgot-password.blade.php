<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - BlogSpace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Image/Brand -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 items-center justify-center p-12">
            <div class="text-center text-white">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-blog text-4xl text-white"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4">BlogSpace</h1>
                <p class="text-xl text-blue-100">Platform berbagi cerita dan pengetahuan untuk komunitas Indonesia</p>
            </div>
        </div>

        <!-- Right Side - Forgot Password Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-blog text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">BlogSpace</h2>
                </div>
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password</h2>
                    <p class="text-gray-600">Masukkan email Anda untuk menerima link reset password.</p>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') is-invalid @enderror" required autofocus value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">Kirim Link Reset</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
