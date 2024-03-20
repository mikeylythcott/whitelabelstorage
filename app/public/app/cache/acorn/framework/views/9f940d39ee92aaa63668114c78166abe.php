<?php
  $faq_title = get_field('faq_title');
  $faq_tagline = get_field('faq_tagline');
?>

<section class="container home-testimonials padding-top-80">
  <div class="row">
    <div class="col-12">

      <div class="centered-title d-flex align-items-center flex-column text-center pb-5">
        <h1 class="standard-subtitle w-full blue text-size-medium-32">
          <?php if($faq_title): ?>
            <?php echo $faq_title; ?>

          <?php endif; ?>
        </h1>

        <?php if($faq_tagline): ?>
          <h3 class="standard-red-h3-subtitle w-full red text-size-medium-24 font-weight-700 mb-0 mb-1">
             <?php echo $faq_tagline; ?>

          </h3>
        <?php endif; ?>
      </div>

      <style>
        .accordion-button::after {
          background-image: url(<?= \Roots\asset('images/Accordion-Icon.svg'); ?>);
        }

        .accordion-button:not(.collapsed):after {
          background-image: url(<?= \Roots\asset('images/Accordion-Icon.svg'); ?>);
          transform: rotate(45deg);
        }
      </style>

      <div class="accordion accordion-flush" id="accordionFaq">
        <?php if( have_rows('faqs') ): $counterindicators = -1; ?>
          <?php while( have_rows('faqs') ): the_row();
            $counterindicators++;
            $faq_question = get_sub_field('faq_question');
            $faq_answer = get_sub_field('faq_answer');
          ?>

            <div class="accordion-item">
              <h2 class="accordion-header" id="heading<?php echo $counterindicators; ?>">
                <button class="accordion-button collapsed accordion-title blue font-weight-700 text-size-medium-24 p-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $counterindicators; ?>" aria-expanded="false" aria-controls="collapse<?php echo $counterindicators; ?>">
                  <?php if($faq_question): ?>
                   <?php echo e($faq_question); ?>

                  <?php endif; ?>
                </button>
              </h2>

              <div id="collapse<?php echo $counterindicators; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $counterindicators; ?>" data-bs-parent="#accordionFaq">
                <div class="accordion-body pt-0 px-4 pb-4">
                  <?php if($faq_answer): ?>
                   <?php echo e($faq_answer); ?>

                  <?php endif; ?>
                </div>
              </div>
            </div>

          <?php endwhile; ?>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>
<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/wp-content/themes/sage-10/resources/views/partials/home-faq.blade.php ENDPATH**/ ?>