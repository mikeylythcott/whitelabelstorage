<?php
$company_title = get_field('company_title','option');
$footer_phone = get_field('footer_phone','option');
$footer_address = get_field('footer_address','option');
$footer_email = get_field('footer_email','option');
$footer_legal_line = get_field('footer_legal_line','option');
$footer_logo = get_field('footer_logo','option');
$footer_logo_size = 'large';
$footer_all_rights_reserved = get_field('footer_all_rights_reserved');
?>

<footer class="content-info">
  <?php (dynamic_sidebar('sidebar-footer')); ?>
</footer>

<section class="container-full footer white padding-top-80">
  <div class="row">
    <div class="col-12">
      <div class="container">
        <div class="row">

          
          <div class="col-12 col-sm-6 mb-5">
            <h3 class="text-size-medium-32 white">
              <?php if($company_title): ?>
                <?php echo $company_title; ?>

              <?php endif; ?>
            </h3>

            <div class="footer-contact-wrap">

              <div class="footer-contact-item d-flex align-items-center justify-content-start d-flex align-items-center justify-content-start">
                <div class="icon-24x24">
                  <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.5562 12.9062L16.1007 13.359C16.1007 13.359 15.0181 14.4355 12.0631 11.4972C9.10812 8.55901 10.1907 7.48257 10.1907 7.48257L10.4775 7.19738C11.1841 6.49484 11.2507 5.36691 10.6342 4.54348L9.37326 2.85908C8.61028 1.83992 7.13596 1.70529 6.26145 2.57483L4.69185 4.13552C4.25823 4.56668 3.96765 5.12559 4.00289 5.74561C4.09304 7.33182 4.81071 10.7447 8.81536 14.7266C13.0621 18.9492 17.0468 19.117 18.6763 18.9651C19.1917 18.9171 19.6399 18.6546 20.0011 18.2954L21.4217 16.883C22.3806 15.9295 22.1102 14.2949 20.8833 13.628L18.9728 12.5894C18.1672 12.1515 17.1858 12.2801 16.5562 12.9062Z" fill="#E62222"></path>
                  </svg>
                </div>
                <div class="text-size-small-16">
                  <?php if($footer_phone): ?>
                    <?php echo $footer_phone; ?>

                  <?php endif; ?>
                </div>
              </div>

              <div class="footer-contact-item d-flex align-items-center justify-content-start d-flex align-items-center justify-content-start">
                <div class="icon-24x24">
                  <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 16.8289V11.1622C21 10.1186 21 9.59686 20.7169 9.20403C20.4881 8.8867 20.1212 8.71803 19.4667 8.49097C19.3328 10.0972 18.8009 11.7375 17.9655 13.1731C16.9928 14.8447 15.5484 16.3393 13.697 17.1469C12.618 17.6176 11.382 17.6176 10.303 17.1469C8.45164 16.3393 7.00718 14.8447 6.03449 13.1731C5.40086 12.0842 4.9418 10.8775 4.69862 9.65727C4.31607 9.60093 4.0225 9.62984 3.76917 9.77118C3.66809 9.82757 3.57388 9.89547 3.48841 9.97353C3 10.4196 3 11.2491 3 12.908V17.8377C3 18.8813 3 19.403 3.28314 19.7959C3.56627 20.1887 4.06129 20.3537 5.05132 20.6837L5.43488 20.8116L5.43489 20.8116C7.01186 21.3372 7.80035 21.6001 8.60688 21.6016C8.8498 21.6021 9.09242 21.5848 9.33284 21.55C10.131 21.4344 10.8809 21.0595 12.3806 20.3096C13.5299 19.735 14.1046 19.4477 14.715 19.3144C14.9292 19.2676 15.1463 19.2349 15.3648 19.2166C15.9875 19.1645 16.6157 19.2692 17.8721 19.4786C19.1455 19.6909 19.7821 19.797 20.247 19.53C20.4048 19.4394 20.5449 19.3207 20.6603 19.1799C21 18.7653 21 18.1198 21 16.8289Z" fill="#E62222"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C8.68629 2 6 4.55211 6 7.70031C6 10.8238 7.91499 14.4687 10.9028 15.7721C11.5993 16.076 12.4007 16.076 13.0972 15.7721C16.085 14.4687 18 10.8238 18 7.70031C18 4.55211 15.3137 2 12 2ZM12 10C13.1046 10 14 9.10457 14 8C14 6.89543 13.1046 6 12 6C10.8954 6 10 6.89543 10 8C10 9.10457 10.8954 10 12 10Z" fill="#E62222"></path>
                  </svg>
                </div>
                <div class="text-size-small-16">
                  <?php if($footer_address): ?>
                    <?php echo $footer_address; ?>

                  <?php endif; ?>
                </div>
              </div>

              <div class="footer-contact-item d-flex align-items-center justify-content-start d-flex align-items-center justify-content-start">
                <div class="icon-24x24">
                  <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.17157 5.17157C2 6.34315 2 8.22876 2 12C2 15.7712 2 17.6569 3.17157 18.8284C4.34315 20 6.22876 20 10 20H14C17.7712 20 19.6569 20 20.8284 18.8284C22 17.6569 22 15.7712 22 12C22 8.22876 22 6.34315 20.8284 5.17157C19.6569 4 17.7712 4 14 4H10C6.22876 4 4.34315 4 3.17157 5.17157ZM18.5762 7.51986C18.8413 7.83807 18.7983 8.31099 18.4801 8.57617L16.2837 10.4066C15.3973 11.1452 14.6789 11.7439 14.0448 12.1517C13.3843 12.5765 12.7411 12.8449 12 12.8449C11.2589 12.8449 10.6157 12.5765 9.95518 12.1517C9.32112 11.7439 8.60271 11.1452 7.71636 10.4066L5.51986 8.57617C5.20165 8.31099 5.15866 7.83807 5.42383 7.51986C5.68901 7.20165 6.16193 7.15866 6.48014 7.42383L8.63903 9.22291C9.57199 10.0004 10.2197 10.5384 10.7666 10.8901C11.2959 11.2306 11.6549 11.3449 12 11.3449C12.3451 11.3449 12.7041 11.2306 13.2334 10.8901C13.7803 10.5384 14.428 10.0004 15.361 9.22291L17.5199 7.42383C17.8381 7.15866 18.311 7.20165 18.5762 7.51986Z" fill="#E62222"></path>
                  </svg>
                </div>

                <div class="text-size-small-16">
                  <?php if($footer_email): ?>
                    <?php echo $footer_email; ?>

                  <?php else: ?>
                    <a href="/contact" title="Contact Us" class="white-link-hover">
                      Email / Contact
                    </a>
                  <?php endif; ?>
                </div>

              </div>
            </div>
          </div>
          

          
          <div class="col-12 col-sm-6 footer-logo-wrap d-flex align-items-center mb-5">
            <?php if($footer_logo): ?>
              <?php echo wp_get_attachment_image( $footer_logo, $footer_logo_size, "", ["class" => "footer-logo"] ); ?>
            <?php endif; ?>
          </div>
          

          
          <div class="col-12 text-center pt-4">
            <div class="footer-legal">
              <?php if($footer_legal_line): ?>
                <h6 class="powered-by w-100 mb-2 text-center white text-size-small-16">
                  <?php echo $footer_legal_line; ?>

                </h6>
              <?php endif; ?>

              <div class="footer-links text-size-tiny-14">
                &copy;<?php echo date('Y'); ?> WSL - All Rights Reserved - <a href="/privacy-policy" title="Privacy Policy" class="gray-to-white-hover">Privacy Policy</a> - <a href="/terms" title="Terms & Conditions" class="gray-to-white-hover">Terms</a>
              </div>
            </div>
          </div>
          

        </div>
      </div>
    </div>
  </div>
</section>
<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/app/themes/sage-10/resources/views/sections/footer.blade.php ENDPATH**/ ?>