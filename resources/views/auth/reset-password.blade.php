
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reset Password - Admin BlogSpace</title>
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

		<!-- Right Side - Reset Password Form -->
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
					<h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h2>
					<p class="text-gray-600">Masukkan password baru untuk akun admin Anda.</p>
				</div>
				@if (session('status'))
					<div class="alert alert-success">{{ session('status') }}</div>
				@endif
				<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
					@csrf
					<input type="hidden" name="token" value="{{ $token }}">
					<input type="hidden" name="email" value="{{ request('email') }}">
					<div>
						<label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
						<input id="password" type="password" name="password" required autofocus class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') is-invalid @enderror">
						@error('password')
							<span class="invalid-feedback">{{ $message }}</span>
						@enderror
					</div>
					<div>
						<label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
						<input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
					</div>
					<button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">Reset Password</button>
				</form>
				<div class="mt-3 text-center">
					<a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline">Kembali ke Login</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
