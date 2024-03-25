<?php
  $company_name = get_field('company_name','option');
  $company_address = get_field('company_address','option');
  $company_phone = get_field('company_phone','option');
?>

<div class="container">
  <div class="row">

    
    <div class="col-12 col-lg-3 col-xl-4 mb-4">
      <div class="bordered-box background-lightest-gray p-3 p-xl-4 border-radius-8px">
        <?php if($company_name): ?>
          <h2 class="h4 mb-1 font-weight-700">
            <?php echo $company_name; ?>

          </h2>
        <?php endif; ?>

        <?php if($company_address): ?>
          <h3 class="h6 font-weight-400">
            <?php echo $company_address; ?>

          </h3>
        <?php endif; ?>

        <div class="text-size-tiny-14 text-uppercase font-weight-700 border-top pt-3 pt-xl-4 mt-3 mt-xl-4">
          Selected Unit
        </div>

        <div class="d-flex align-items-start justify-content-between">
          <div class="pr-3">
            <h4 class="text-size-medium-32 font-weight-700 mb-1">
              6x6x7
            </h4>
            <div class="font-weight-400 text-size-small-16 text-color-grey line-height-1-2">
              Indoor, Climate Controlled
            </div>
          </div>

          <div class="flex-grow-0 flex-shrink-0">
            <h4 class="text-size-medium-24 font-weight-700 text-color-red">
              $124
            </h4>
          </div>
        </div>
      </div>
    </div>
    


    
    <div class="col-12 col-lg-9 col-xl-8 mb-4">
      <div class="bordered-box p-3 p-xl-4 border-radius-8px">

        
        <form>
          <div class="row">
            <div class="col-12">

              <?php the_content(); ?>

            </div>
          </div>
        </form>
        

      </div>
    </div>
    

  </div>
</div>
<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/app/themes/sage-10/resources/views/partials/checkout.blade.php ENDPATH**/ ?>