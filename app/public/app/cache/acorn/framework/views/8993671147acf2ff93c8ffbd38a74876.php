<!doctype html>
<html <?php (language_attributes()); ?>>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
      $favicon = get_field('favicon');
    ?>

    <?php if($favicon): ?>
      <link rel="shortcut icon" type="image/x-icon" href="<?php echo e($favicon); ?>?v=2">
    <?php else: ?>
      <link rel="shortcut icon" type="image/x-icon" href="<?= \Roots\asset('images/favicon.ico'); ?>?v=2">
    <?php endif; ?>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">

    <!-- header code -->
    <?php $header_code = get_field('header_code'); ?>

    <?php if($header_code): ?>
      <?php echo $header_code; ?>
    <?php endif; ?>

    <?php (do_action('get_header')); ?>
    <?php (wp_head()); ?>

    <script>var $ = jQuery.noConflict();</script>
  </head>

  <body <?php (body_class()); ?>>
    <?php (wp_body_open()); ?>

    <a class="sr-only focus:not-sr-only d-none" href="#main">
      <?php echo e(__('Skip to content')); ?>

    </a>

    <section class="mt-0 pt-0">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <?php echo $__env->make('sections.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          </div>
        </div>
      </div>
    </section>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('sections.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php (do_action('get_footer')); ?>
    <?php (wp_footer()); ?>

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script>

      var splide = new Splide( '.splide' );
      var bar    = splide.root.querySelector( '.my-slider-progress-bar' );

      // Update the bar width:
      splide.on( 'mounted move', function () {
        var end = splide.Components.Controller.getEnd() + 1;
        bar.style.width = String( 100 * ( splide.index + 1 ) / end ) + '%';
      } );

      splide.mount();

      function slider() {

      let splides = $('.slider');
      for ( let i = 0, splideLength = splides.length; i < splideLength; i++ ) {
      	new Splide( splides[ i ], {
        // Desktop on down
      	perPage: 2.5,
      	perMove: 1,
        focus: 0, // 0 = left and 'center' = center
        type: 'loop', // 'loop' or 'slide'
        gap: '25px', // space between slides
        arrows: true, // 'slider' or false
        pagination: false, // 'slider' or false
        speed : 600, // transition speed in miliseconds
        dragAngleThreshold: 30, // default is 30
        autoWidth: false, // for cards with differing widths
        rewind : false, // go back to beginning when reach end
        rewindSpeed : 400,
        waitForTransition : false,
        updateOnMove : true,
        trimSpace: false, // true removes empty space from end of list
        classes: {
      		arrows: 'splide__arrows my_arrows',
      		arrow : 'splide__arrow',
      		prev  : 'splide__arrow--prev',
      		next  : 'splide__arrow--next',
        },
        breakpoints: {
      		991: {
          	// Tablet
      			perPage: 2.5,
            gap: '20px',
      		},
          767: {
          	// Mobile Landscape
      			perPage: 1.75,
            gap: '15px',
      		},
          479: {
          	// Mobile Portrait
      			perPage: 1.25,
            gap: '10px',
      		}
      	}
      } ).mount();
      }

      }
      slider();

    </script>
  </body>
</html>
<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/app/themes/sage-10/resources/views/layouts/app.blade.php ENDPATH**/ ?>