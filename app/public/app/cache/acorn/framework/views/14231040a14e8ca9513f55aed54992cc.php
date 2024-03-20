<?php if(is_home() || is_front_page()): ?>

  
    <?php echo $__env->make('partials.home-hero', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

  
    <?php echo $__env->make('partials.home-units', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

  
    <?php echo $__env->make('partials.home-photo-gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

  
    <?php echo $__env->make('partials.home-storage-units', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

  
    <?php echo $__env->make('partials.home-map', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

  
    <?php echo $__env->make('partials.home-about', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

  
    <?php echo $__env->make('partials.home-testimonials', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

  
    <?php echo $__env->make('partials.home-faq', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

<?php else: ?>

<section class="container">
  <div class="row">
    <div class="col-12">
      <?php (the_content()); ?>
    </div>
  </div>
</section>

<?php endif; ?>

<?php if($pagination): ?>

  <section class="container">
    <div class="row">
      <div class="col-12">
        <nav class="page-nav" aria-label="Page">
          <?php echo $pagination; ?>

        </nav>
      </div>
    </div>
  </section>
  
<?php endif; ?>
<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/wp-content/themes/sage-10/resources/views/partials/content-page.blade.php ENDPATH**/ ?>