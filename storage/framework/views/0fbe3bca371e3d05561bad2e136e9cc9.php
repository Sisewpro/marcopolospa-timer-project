<footer class="footer footer-center text-base-content pt-4">
    <aside class="grid-flow-col items-center">
        <?php if (isset($component)) { $__componentOriginalda9bc475b825242b18c29bb41b6b2e85 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda9bc475b825242b18c29bb41b6b2e85 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.powered-by','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('powered-by'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>&copy; 2024 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda9bc475b825242b18c29bb41b6b2e85)): ?>
<?php $attributes = $__attributesOriginalda9bc475b825242b18c29bb41b6b2e85; ?>
<?php unset($__attributesOriginalda9bc475b825242b18c29bb41b6b2e85); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda9bc475b825242b18c29bb41b6b2e85)): ?>
<?php $component = $__componentOriginalda9bc475b825242b18c29bb41b6b2e85; ?>
<?php unset($__componentOriginalda9bc475b825242b18c29bb41b6b2e85); ?>
<?php endif; ?>
    </aside>

</footer><?php /**PATH D:\laragon\www\spa-counter-rev-ver-1\resources\views/components/footer.blade.php ENDPATH**/ ?>