<div class="overflow-x-auto">
    <!-- Therapist Details -->
    <?php if(!$therapist): ?>
    <p class="text-gray-700 dark:text-gray-300">Therapist not found.</p>
    <?php else: ?>
    <table
        class="table-auto w-full mb-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700">
        <thead class="bg-gray-200 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Name</th>
                <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Phone Number</th>
                <?php if (! (auth()->user()->role === 'admin')): ?>
                <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Status</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600"><?php echo e($therapist->name); ?></td>
                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600"><?php echo e($therapist->phone_number); ?></td>
                <?php if (! (auth()->user()->role === 'admin')): ?>
                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600"><?php echo e($therapist->status); ?></td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>
</div><?php /**PATH D:\laragon\www\spa-counter-rev-ver-1\resources\views\active_therapists.blade.php ENDPATH**/ ?>