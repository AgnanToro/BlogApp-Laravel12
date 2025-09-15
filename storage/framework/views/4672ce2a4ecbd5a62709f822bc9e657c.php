

<?php $__env->startSection('title', 'Test Vue.js'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Test Vue.js</h3>
                </div>
                <div class="card-body">
                    <div id="test-app">
                        <h4>{{ message }}</h4>
                        <button @click="changeMessage" class="btn btn-primary">Change Message</button>
                        <p>Count: {{ count }}</p>
                        <button @click="increment" class="btn btn-success">+1</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            message: 'Hello Vue.js!',
            count: 0
        }
    },
    methods: {
        changeMessage() {
            this.message = 'Vue.js is working!';
        },
        increment() {
            this.count++;
        }
    }
}).mount('#test-app');
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\NGODING\Dafidea - testcase\resources\views/admin/comments/test-vue.blade.php ENDPATH**/ ?>