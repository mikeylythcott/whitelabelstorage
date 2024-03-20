<section class="container photo-gallery padding-top-80">
  <div class="row">
    <div class="col-12">

      <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

      <style>
        .splide__arrows, .splide__pagination {
          display: none;
        }

        .my-slider-progress {
          background: #2e2a27;
        }

        .splide {
          width: 100%;
          position: relative;
        }

        .splide__arrows {
          display: flex !important;
          width: 90px;
          height: 42px;
          text-align: right;
          display: flex;
          align-items: flex-end;
          justify-content: space-between;
          position: absolute;
          top: -70px;
          right: 0;
          z-index: 2;
        }

        .splide__arrow {
          background: none;
          width: 40px;
          height: 40px;
          border: 1px solid #B5B9BE;
          border-radius: 100%;
          display: flex;
          align-items: center;
          justify-content: center;
          position: relative;
          top: unset;
          left: unset;
          transform: none;
        }

        .splide__arrow.arrow-right {
          transform: rotate(180deg);
        }

        .splide__arrow--prev,
        .splide__arrow--next {
          left: unset;
          right: unset;
        }

        .splide__arrow svg {
          width: 20px;
          height: 20px;
        }

        .splide__arrow:hover svg {
          fill: #e62222;
        }

        .photo-gallery-arrow:hover svg {
          fill: #e62222;
        }

        .splide.slider {
          z-index: 9;
          cursor: grab;
        }

        .splide__track {
          width: 100%;
          overflow: visible;
        }

        .splide__list {
          justify-content: flex-start;
          align-items: flex-start;
          display: flex;
        }

        .splide__slide {
          width: 33.3333%;
          flex: none;
          align-items: flex-start;
        }

        .splide__slide:first-child {
          padding-left: 0;
        }

        .my-slider-progress {
          height: 4px;
          color: #e8e2da;
          background-color: #2e2a27;
          margin-top: 30px;
          overflow: hidden;
        }

        .my-slider-progress-bar {
          width: 30vw;
          height: 7px;
          background-color: #b3b641;
          height: 4px;
          transition: width 400ms ease;
          width: 0;
        }
      </style>

      <div class="testimonial-wrapper position-relative">
        <div class="photogallery-title">
          <h3 class="text-size-medium-32 photo-gal-title">Photo Gallery</h3>
        </div>

        <div class="splide slider" aria-labelledby="photo-gal-title" aria-label="Photo Gallery">
          <div class="splide__track">
            <div role="list" class="splide__list">

              <?php if( have_rows('unit_photos') ): ?>
                <?php while( have_rows('unit_photos') ): the_row();
                  $unit_photo = get_sub_field('unit_photo');
                  $gallery_image_size = 'photogal';
                  $unit_photo_src = wp_get_attachment_image_src(get_sub_field('unit_photo'), 'full');
                ?>

                  <div role="listitem" class="splide__slide">
                    <a href="<?php echo e($unit_photo_src[0]); ?>" class="projects_content-wrapper w-inline-block venobox">
                      <div class="projects-image-wrapper">
                        <?php if($unit_photo) { echo wp_get_attachment_image( $unit_photo, $gallery_image_size, "", ["class" => "projects-image"] ); } ?>
                      </div>
                    </a>
                  </div>

                <?php endwhile; ?>
              <?php endif; ?>

            </div>
          </div>

          <div id="progress" class="my-slider-progress">
            <div class="my-slider-progress-bar"></div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/app/themes/sage-10/resources/views/partials/home-photo-gallery.blade.php ENDPATH**/ ?>