<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - BlogSpace</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-blog text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">BlogSpace</h2>
                </div>

                <!-- Login Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali</h2>
                    <p class="text-gray-600">Silakan masuk ke akun Anda</p>
                </div>

                <!-- Notifikasi sukses reset password -->

                <?php if(session('status')): ?>
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-300 rounded p-3 text-center">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-300 rounded p-3 text-center">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="<?php echo e(old('email')); ?>"
                            required 
                            autocomplete="email" 
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan alamat email"
                        >
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan kata sandi"
                        >
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="mt-2 text-right">
                            <a href="<?php echo e(route('admin.password.request')); ?>" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    >
                        Masuk
                    </button>
                </form>

                <!-- Back to Homepage -->
                <div class="mt-6 text-center">
                    <a href="<?php echo e(route('posts.index')); ?>" 
                       class="inline-flex items-center justify-center w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\NGODING\Dafidea - testcase\resources\views/auth/login.blade.php ENDPATH**/ ?>