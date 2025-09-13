

<?php $__env->startSection('title', $post->judul . ' - BlogSpace'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="gradient-bg text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Article Title -->
            <h1 class="text-3xl md:text-4xl font-bold mb-6 leading-tight"><?php echo e($post->judul); ?></h1>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Article Header Image -->
        <?php if($post->foto): ?>
            <img src="<?php echo e(asset('storage/' . $post->foto)); ?>" alt="<?php echo e($post->judul); ?>" class="w-full h-64 md:h-96 object-cover object-center rounded-xl mb-8">
        <?php else: ?>
            <div class="h-64 md:h-96 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl mb-8 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-image text-8xl text-white opacity-50 mb-4"></i>
                    <p class="text-white text-xl font-semibold"><?php echo e($post->judul); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Article Content -->
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-700 leading-relaxed text-lg">
                <?php echo nl2br(e($post->konten)); ?>

            </div>
        </div>
        
        <!-- Article Footer -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-clock mr-2"></i>
                    <span>Dipublikasikan <?php echo e($post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i')); ?></span>
                    <?php if($post->user): ?>
                        <span class="mx-3">•</span>
                        <i class="fas fa-user mr-2"></i>
                        <span>Oleh <strong><?php echo e($post->user->name); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-600">
                        <i class="fas fa-comments mr-1"></i><?php echo e($post->comments->count()); ?> komentar
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comments Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">
                <i class="fas fa-comments text-blue-600 mr-3"></i>
                Komentar (<?php echo e($post->comments->count()); ?>)
            </h2>
            
            <!-- Vue.js Comment Form -->
            <comment-form 
                post-id="<?php echo e($post->id); ?>"
                csrf-token="<?php echo e(csrf_token()); ?>"
                class="mb-8">
                
                <!-- Fallback HTML form -->
                <div class="p-6 bg-blue-50 rounded-xl">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-comment-dots mr-2 text-blue-500"></i>
                        Tinggalkan Komentar
                    </h3>
                    <form method="POST" action="<?php echo e(route('comments.store', $post)); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label for="nama_komentator" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Anda
                            </label>
                            <input type="text" 
                                   id="nama_komentator" 
                                   name="nama_komentator" 
                                   class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nama_komentator'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('nama_komentator')); ?>"
                                   placeholder="Masukkan nama Anda"
                                   required>
                            <?php $__errorArgs = ['nama_komentator'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-6">
                            <label for="isi_komentar" class="block text-sm font-medium text-gray-700 mb-2">
                                Komentar
                            </label>
                            <textarea id="isi_komentar" 
                                      name="isi_komentar" 
                                      rows="4"
                                      class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none <?php $__errorArgs = ['isi_komentar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="Tulis komentar Anda di sini..."
                                      required><?php echo e(old('isi_komentar')); ?></textarea>
                            <?php $__errorArgs = ['isi_komentar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                                <i class="fas fa-paper-plane"></i>
                                <span>Kirim Komentar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </comment-form>
            
            <!-- Comments List -->
            <?php if($post->comments->where('approved', true)->count() > 0): ?>
                <div class="space-y-6">
                    <?php $__currentLoopData = $post->comments->where('approved', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gray-50 p-6 rounded-xl border-l-4 border-blue-500">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800"><?php echo e($comment->nama_komentator); ?></h4>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            <?php echo e($comment->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed"><?php echo e($comment->isi_komentar); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comments text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Komentar</h3>
                    <p class="text-gray-600">Jadilah yang pertama memberikan komentar untuk artikel ini!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Navigation Button -->
<section class="py-8 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <a href="<?php echo e(url('/')); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\NGODING\Dafidea - testcase\resources\views/posts/show.blade.php ENDPATH**/ ?>