<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
  'type' => null,
  'message' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
  'type' => null,
  'message' => null,
]); ?>
<?php foreach (array_filter(([
  'type' => null,
  'message' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php ($class = match ($type) {
  'success' => 'text-green-50 bg-green-400',
  'caution' => 'text-yellow-50 bg-yellow-400',
  'warning' => 'text-red-50 bg-red-400',
  default => 'text-indigo-50 bg-indigo-400',
}); ?>

<div <?php echo e($attributes->merge(['class' => "px-2 py-1 {$class}"])); ?>>
  <?php echo $message ?? $slot; ?>

</div>
<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/app/themes/sage-10/resources/views/components/alert.blade.php ENDPATH**/ ?>