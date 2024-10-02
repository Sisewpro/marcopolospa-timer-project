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
        <!-- Optional header content -->
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 dark:bg-base-300 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content dark:text-base-content">

                    <!-- Export to PDF and Date Filters -->
                    <div class="mb-6">
                        <!-- Filter Form for Display and Export -->
                        <form method="GET" action="<?php echo e(route('export.pdf')); ?>">
                            <div class="form-control mb-4">
                                <label for="start_date" class="label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="input input-bordered"
                                    value="<?php echo e(request('start_date')); ?>">
                            </div>

                            <div class="form-control mb-4">
                                <label for="end_date" class="label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="input input-bordered"
                                    value="<?php echo e(request('end_date')); ?>">
                            </div>
                        </form>

                        <!-- Export to PDF Button (includes date filters) -->
                        <form method="GET" action="<?php echo e(route('export.pdf')); ?>">
                            <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
                            <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
                            <button type="submit" class="btn btn-secondary mt-2">Export to PDF</button>
                        </form>
                    </div>

                    <!-- Rekap Table for Display -->
                    <div class="overflow-x-auto">
                        <table class="table w-full text-base-content dark:text-base-content">
                            <thead>
                                <tr>
                                    <th>Locker</th>
                                    <th>Therapist</th>
                                    <th>Customer</th>
                                    <th>Session</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $rekaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rekap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($rekap->timerCard->card_name ?? 'No Locker'); ?></td>
                                    <td><?php echo e($rekap->therapist_name); ?></td>
                                    <td><?php echo e($rekap->customer); ?></td>
                                    <td><?php echo e($rekap->time); ?></td>
                                    <td>
                                        <span
                                            class="badge <?php echo e($rekap->status == 'Ready' ? 'bg-success text-success-content' : 'bg-success text-warning-content'); ?>">
                                            <?php echo e($rekap->status); ?>

                                        </span>
                                    </td>
                                    <!-- Date format dd/mm/yyyy -->
                                    <td><?php echo e($rekap->created_at->format('d/m/Y')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH D:\laragon\www\spa-counter-rev-ver-1\resources\views/export.blade.php ENDPATH**/ ?>