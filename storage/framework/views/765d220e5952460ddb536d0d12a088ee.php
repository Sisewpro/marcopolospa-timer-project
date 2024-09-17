

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Update Status</h2>

    <form method="POST" action="<?php echo e(route('active-therapists.update', $therapist)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Status
            </label>
            <select id="status" name="status" required
                class="select select-bordered w-full dark:bg-gray-900 dark:border-gray-600 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                <option value="active" <?php echo e(old('status', $therapist->status) == 'active' ? 'selected' : ''); ?>>Active
                </option>
                <option value="inactive" <?php echo e(old('status', $therapist->status) == 'inactive' ? 'selected' : ''); ?>>
                    Inactive
                </option>
            </select>
            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="text-red-500 text-sm mt-1 block"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="flex justify-end gap-4">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?php echo e(route('active-therapists.index')); ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\spa-counter-rev-ver-1\resources\views\active_therapists\edit-user.blade.php ENDPATH**/ ?>