
<?php
  $google_map_embed_code = get_field('google_map_embed_code');
?>

<?php if($google_map_embed_code): ?>
  <div class="google-map margin-top-80 position-relative w-100">
    <?php echo $google_map_embed_code; ?>

  </div>
<?php endif; ?>

<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/app/themes/sage-10/resources/views/partials/home-map.blade.php ENDPATH**/ ?>