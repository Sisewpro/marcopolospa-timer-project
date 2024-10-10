<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Active Therapists')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Display success messages -->
                    <?php if(session('success')): ?>
                    <div
                        class="alert alert-success shadow-lg bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">
                        <div>
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Button to create a new therapist -->
                    <?php if(auth()->user()->role === 'admin'): ?>
                    <div class="mb-6">
                        <a href="<?php echo e(route('active-therapists.create')); ?>"
                            class="btn btn-primary bg-blue-500 dark:bg-blue-700 text-white dark:text-gray-200">Add New
                            Therapist</a>
                    </div>
                    <?php endif; ?>

                    <!-- Therapists Table -->
                    <div class="overflow-x-auto">
                        <table
                            class="table w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Name</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Phone Number
                                    </th>
                                    <?php if(auth()->user()->role !== 'admin'): ?>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Status</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Actions</th>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->role === 'admin'): ?>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $therapists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $therapist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <?php echo e($therapist->name); ?></td>
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <?php echo e($therapist->phone_number); ?></td>
                                    <?php if(auth()->user()->role !== 'admin'): ?>
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <?php echo e($therapist->status); ?></td>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->role === 'admin'): ?>
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <a href="<?php echo e(route('active-therapists.edit', $therapist)); ?>"
                                            class="btn btn-warning bg-yellow-500 dark:bg-yellow-700 text-white dark:text-gray-200">Edit</a>
                                        <form action="<?php echo e(route('active-therapists.destroy', $therapist)); ?>"
                                            method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="btn btn-danger bg-red-500 dark:bg-red-700 text-white dark:text-gray-200">Delete</button>
                                        </form>
                                    </td>
                                    <?php elseif(auth()->user()->role === 'user'): ?>
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <a href="<?php echo e(route('active-therapists.edit', $therapist)); ?>"
                                            class="btn btn-warning bg-yellow-500 dark:bg-yellow-700 text-white dark:text-gray-200">Edit</a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="<?php echo e(auth()->user()->role === 'admin' ? '4' : '3'); ?>"
                                        class="py-2 px-4 text-center border-b border-gray-200 dark:border-gray-600">
                                        No therapists found.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\lurker\resources\views/active_therapists/index.blade.php ENDPATH**/ ?>