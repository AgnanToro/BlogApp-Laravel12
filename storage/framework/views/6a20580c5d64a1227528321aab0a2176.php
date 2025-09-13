

<?php $__env->startSection('title', 'View Post: ' . $post->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><?php echo e($post->judul); ?></h1>
            <div>
                <a href="<?php echo e(route('admin.posts.edit', $post)); ?>" class="btn btn-warning">Edit</a>
                <a href="<?php echo e(route('admin.posts.index')); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo e($post->judul); ?></h5>
                <p class="text-muted">
                    <small>Dipublikasikan: <?php echo e($post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i')); ?></small>
                </p>
                <div class="card-text">
                    <?php echo nl2br(e($post->konten)); ?>

                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Post</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo e($post->id); ?></p>
                <p><strong>Tanggal Post:</strong> <?php echo e($post->tanggal_post->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i')); ?></p>
                <p><strong>Dibuat:</strong> <?php echo e($post->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i')); ?></p>
                <p><strong>Diupdate:</strong> <?php echo e($post->updated_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i')); ?></p>
                <p><strong>Total Komentar:</strong> <?php echo e($post->comments->count()); ?></p>
            </div>
        </div>
        
        <?php if($post->comments->count() > 0): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5>Komentar</h5>
            </div>
            <div class="card-body" id="admin-comments-list">
                <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2" id="comment-<?php echo e($comment->id); ?>">
                        <div>
                            <strong><?php echo e($comment->nama_komentator); ?></strong>
                            <div class="text-xs text-muted"><?php echo e($comment->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i')); ?></div>
                            <div><?php echo e($comment->isi_komentar); ?></div>
                        </div>
                        <button class="btn btn-sm btn-danger btn-delete-comment" data-id="<?php echo e($comment->id); ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Action</h5>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('posts.show', $post)); ?>" class="btn btn-success btn-sm" target="_blank">Lihat di Public</a>
                <a href="<?php echo e(route('admin.posts.edit', $post)); ?>" class="btn btn-warning btn-sm">Edit Post</a>
                <form method="POST" action="<?php echo e(route('admin.posts.destroy', $post)); ?>" 
                      style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus post ini?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger btn-sm">Delete Post</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-comment').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(confirm('Yakin hapus komentar ini?')) {
                var commentId = this.getAttribute('data-id');
                fetch('/admin/comments/' + commentId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async res => {
                    if (!res.ok) {
                        let msg = 'Gagal menghapus komentar';
                        try { const data = await res.json(); if(data.message) msg = data.message; } catch(e){}
                        alert(msg);
                        return;
                    }
                    return res.json();
                })
                .then(data => {
                    if(data && data.success) {
                        document.getElementById('comment-' + commentId).remove();
                    }
                })
                .catch(function(err) {
                    alert('Error JS: ' + err);
                });
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\NGODING\Dafidea - testcase\resources\views/admin/posts/show.blade.php ENDPATH**/ ?>