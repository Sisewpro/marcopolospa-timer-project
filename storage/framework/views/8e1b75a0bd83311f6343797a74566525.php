<!-- resources/views/export.blade.php -->

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

                    <!-- Export to PDF Button -->
                    <div class="mb-6">
                        <form method="GET" action="<?php echo e(route('export.pdf')); ?>">
                            <button type="submit" class="btn btn-secondary">Export to PDF</button>
                        </form>
                    </div>

                    <!-- Timer Cards Table for Export -->
                    <div class="overflow-x-auto">
                        <table class="table w-full text-base-content dark:text-base-content">
                            <thead>
                                <tr>
                                    <th>Card Name</th>
                                    <th>Locker</th>
                                    <th>Therapist</th>
                                    <th>Customer</th>
                                    <th>Session</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $timerCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timerCard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($timerCard->id); ?></td>
                                    <td><?php echo e($timerCard->card_name); ?></td>
                                    <td><?php echo e(optional($timerCard->user)->name ?? 'No Staff Assigned'); ?></td>
                                    <td><?php echo e($timerCard->customer); ?></td>
                                    <td><?php echo e($timerCard->time); ?></td>
                                    <td>
                                        <span
                                            class="badge <?php echo e($timerCard->status == 'Ready' ? 'bg-success text-success-content' : 'bg-warning text-warning-content'); ?>">
                                            <?php echo e($timerCard->status); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($timerCard->formatted_date); ?></td>
                                    <td>
                                        <!-- Add action buttons if needed -->
                                    </td>
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
<?php endif; ?><?php /**PATH D:\laragon\www\spa-counter-rev-ver-1\resources\views\export.blade.php ENDPATH**/ ?>