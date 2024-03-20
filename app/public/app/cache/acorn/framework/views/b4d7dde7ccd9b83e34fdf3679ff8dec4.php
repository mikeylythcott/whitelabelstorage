
<?php
  $storage_options_title = get_field('storage_options_title');
  $storage_options_tagline = get_field('storage_options_tagline');

  $location_photo = get_field('location_photo');
  $photo_size = 'large';
?>

<section class="container storage-options-wrapper padding-top-80">
  <div class="row">
    <div class="col-12">

      <div class="centered-title d-flex align-items-center flex-column text-center pb-5">
        <h1 class="standard-subtitle w-full blue text-size-medium-32">
          <?php if($storage_options_title): ?>
            <?php echo $storage_options_title; ?>

          <?php endif; ?>
        </h1>

        <?php if($storage_options_tagline): ?>
          <h3 class="standard-red-h3-subtitle w-full red text-size-medium-24 font-weight-700 mb-0 mb-1">
            <?php echo $storage_options_tagline; ?>

          </h3>
        <?php endif; ?>
      </div>

      <div class="grid-3-up">
        <?php if( have_rows('options') ): ?>
          <?php while( have_rows('options') ): the_row();
            $option_title = get_sub_field('option_title');
            $option_icon = get_sub_field('option_icon');
            $option_description = get_sub_field('option_description');
            $icon_size = 'small-square';
            $option_icon_src= wp_get_attachment_image_src(get_sub_field('option_icon'), 'small-square');
          ?>

            <div role="listitem" class="storage-options border-radius-8px p-3 p-lg-4">
              <div class="storage-options-header d-flex justify-content-start align-items-center w-100 mb-3">
                <div class="service-icon d-flex align-items-center justify-content-center">
                  <img loading="lazy" alt="<?php echo $option_title; ?>" src="<?php echo e($option_icon_src[0]); ?>" class="service-icon-image">
                </div>

                <h4 class="service-title">
                  <?php if($option_title): ?>
                    <?php echo $option_title; ?>

                  <?php endif; ?>
                </h4>
              </div>

              <div class="text-size-small-16">
                <?php if($option_description): ?>
                  <?php echo $option_description; ?>

                <?php endif; ?>
              </div>
            </div>

          <?php endwhile; ?>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/wp-content/themes/sage-10/resources/views/partials/home-storage-units.blade.php ENDPATH**/ ?>