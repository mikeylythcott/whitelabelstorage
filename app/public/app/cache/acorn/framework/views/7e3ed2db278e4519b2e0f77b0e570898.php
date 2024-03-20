
<?php
  $company_name = get_field('company_name');
  $tagline = get_field('tagline');
  $company_description = get_field('company_description');
  $address = get_field('address');
  $google_map_link = get_field('google_map_link');
  $access_hours_title = get_field('access_hours_title');
  $access_hours = get_field('access_hours');
  $access_hours_details = get_field('access_hours_details');
  $support_hours_title = get_field('support_hours_title');
  $support_hours = get_field('support_hours');
  $support_hours_details = get_field('support_hours_details');
  $amenities_features = get_field('amenities_features');
  $google_map_hero = get_field('google_map_hero');
  $location_photo = get_field('location_photo');
  $photo_size = 'large';
?>

<section class="container hero-wrapper">
  <div class="row d-flex justify-content-center align-items-stretch">

    
    <div class="col-12 col-lg-6 hero-block pt-4 pe-2 d-flex flex-column flex-wrap gap-4">

      <div class="hero-info-wrapper w-full d-flex justify-content-start align-items-start">
        <div>
          <?php if($company_name): ?>
            <h1 class="hero-h1 text-size-huge-48 font-weight-800">
              <?php echo $company_name; ?>

            </h1>
          <?php endif; ?>

          <?php if($tagline): ?>
            <h1 class="hero-h2 text-size-medium-24 mb-3 font-weight-700 red">
              <?php echo $tagline; ?>

            </h1>
          <?php endif; ?>

          <?php if($company_description): ?>
            <p class="hero-p mb-0">
              <?php echo $company_description; ?>

            </p>
          <?php endif; ?>

          <div class="google-review d-none">
            <div class="w-embed w-script">
              <script src="https://scripts.feedspring.co/google-reviews-attrs.js" async="" defer=""></script>
            </div>
          </div>

        </div>
      </div>

      <div class="hero-info-wrapper w-full d-flex justify-content-start align-items-start">

        <div class="hero-address d-flex align-items-center font-weight-700 w-full gap-2">
          <div class="standard-svg-icon">
            <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M21 16.8289V11.1622C21 10.1186 21 9.59686 20.7169 9.20403C20.4881 8.8867 20.1212 8.71803 19.4667 8.49097C19.3328 10.0972 18.8009 11.7375 17.9655 13.1731C16.9928 14.8447 15.5484 16.3393 13.697 17.1469C12.618 17.6176 11.382 17.6176 10.303 17.1469C8.45164 16.3393 7.00718 14.8447 6.03449 13.1731C5.40086 12.0842 4.9418 10.8775 4.69862 9.65727C4.31607 9.60093 4.0225 9.62984 3.76917 9.77118C3.66809 9.82757 3.57388 9.89547 3.48841 9.97353C3 10.4196 3 11.2491 3 12.908V17.8377C3 18.8813 3 19.403 3.28314 19.7959C3.56627 20.1887 4.06129 20.3537 5.05132 20.6837L5.43488 20.8116L5.43489 20.8116C7.01186 21.3372 7.80035 21.6001 8.60688 21.6016C8.8498 21.6021 9.09242 21.5848 9.33284 21.55C10.131 21.4344 10.8809 21.0595 12.3806 20.3096C13.5299 19.735 14.1046 19.4477 14.715 19.3144C14.9292 19.2676 15.1463 19.2349 15.3648 19.2166C15.9875 19.1645 16.6157 19.2692 17.8721 19.4786C19.1455 19.6909 19.7821 19.797 20.247 19.53C20.4048 19.4394 20.5449 19.3207 20.6603 19.1799C21 18.7653 21 18.1198 21 16.8289Z" fill="none" stroke="#E62222"></path>
              <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C8.68629 2 6 4.55211 6 7.70031C6 10.8238 7.91499 14.4687 10.9028 15.7721C11.5993 16.076 12.4007 16.076 13.0972 15.7721C16.085 14.4687 18 10.8238 18 7.70031C18 4.55211 15.3137 2 12 2ZM12 10C13.1046 10 14 9.10457 14 8C14 6.89543 13.1046 6 12 6C10.8954 6 10 6.89543 10 8C10 9.10457 10.8954 10 12 10Z" fill="none" stroke="#E62222"></path>
            </svg>
          </div>

          <?php if($address): ?>
            <?php if($google_map_link): ?>
              <a href="<?php echo $google_map_link; ?>" title="Property Google Map" target="_blank" class="d-flex align-items-center underline">
            <?php else: ?>
              <div class="d-flex align-items-center underline blue">
            <?php endif; ?>

              <?php echo $address; ?>


            <?php if($google_map_link): ?>
              </a>
            <?php else: ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (! (!$google_map_link)): ?>
            <svg width="12" height="12" viewbox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M11.9939 2.11043C12.0549 1.56152 11.6593 1.06711 11.1104 1.00612L2.16548 0.0122322C1.61657 -0.0487575 1.12215 0.346778 1.06116 0.895684C1.00017 1.44459 1.39571 1.93901 1.94462 2L9.89569 2.88345L9.01223 10.8345C8.95124 11.3834 9.34678 11.8778 9.89569 11.9388C10.4446 11.9998 10.939 11.6043 11 11.0554L11.9939 2.11043ZM1.6247 10.7809L11.6247 2.78087L10.3753 1.21913L0.375305 9.21913L1.6247 10.7809Z" fill="#00173C"></path>
            </svg>
          <?php endif; ?>
        </div>
      </div>

      <div class="hero-info-wrapper w-full d-flex justify-content-start align-items-start">
        <div class="hours w-100 d-flex flex-column">
          <div class="blue montserrat font-weight-700">
            <?php if($access_hours_title): ?>
              <?php echo $access_hours_title; ?>

            <?php else: ?>
              Access Hours
            <?php endif; ?>
          </div>

          <?php if($access_hours_details): ?>
            <div class="text-size-tiny-14">
              <?php echo $access_hours_details; ?>

            </div>
          <?php endif; ?>

          <div class="store-hours pt-2">
            <?php if( have_rows('access_hours') ): ?>
              <?php while( have_rows('access_hours') ): the_row();
                $hours_title = get_sub_field('hours_title');
                $hours_time = get_sub_field('hours_time');
              ?>

                <div class="text-size-small-16">
                  <strong class="blue montserrat font-weight-700"><?php echo $hours_title; ?></strong> <?php echo $hours_time; ?>

                </div>

              <?php endwhile; ?>
            <?php endif; ?>
          </div>
        </div>

        <div class="hours w-100 d-flex flex-column">
          <div class="blue montserrat font-weight-700">
            <?php if($support_hours_title): ?>
              <?php echo $support_hours_title; ?>

            <?php else: ?>
              Support Hours
            <?php endif; ?>
          </div>

          <?php if($support_hours_details): ?>
            <div class="text-size-tiny-14">
              <?php echo $support_hours_details; ?>

            </div>
          <?php endif; ?>

          <div class="store-hours pt-2">
            <?php if( have_rows('support_hours') ): ?>
              <?php while( have_rows('support_hours') ): the_row();
                $hours_title = get_sub_field('hours_title');
                $hours_time = get_sub_field('hours_time');
              ?>

                <div class="text-size-small-16">
                  <strong class="blue montserrat font-weight-700"><?php echo $hours_title; ?></strong> <?php echo $hours_time; ?>

                </div>

              <?php endwhile; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="hero-features border-radius-8px d-flex justify-content-center align-items-stretch flex-wrap w-100 mt-3 px-3 py-4">
        <?php
          $amenities_features = get_field('amenities_features');
        ?>

        <?php if( $amenities_features && in_array('security', $amenities_features) ) { ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="#E62222" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 11.5C2 8.21252 2 6.56878 2.90796 5.46243C3.07418 5.25989 3.25989 5.07418 3.46243 4.90796C4.56878 4 6.21252 4 9.5 4C12.7875 4 14.4312 4 15.5376 4.90796C15.7401 5.07418 15.9258 5.25989 16.092 5.46243C17 6.56878 17 8.21252 17 11.5V12.5C17 15.7875 17 17.4312 16.092 18.5376C15.9258 18.7401 15.7401 18.9258 15.5376 19.092C14.4312 20 12.7875 20 9.5 20C6.21252 20 4.56878 20 3.46243 19.092C3.25989 18.9258 3.07418 18.7401 2.90796 18.5376C2 17.4312 2 15.7875 2 12.5V11.5Z" stroke="#E62222"></path>
                <path d="M17 9.49995L17.6584 9.17077C19.6042 8.19783 20.5772 7.71135 21.2886 8.15102C22 8.5907 22 9.67848 22 11.8541V12.1458C22 14.3214 22 15.4092 21.2886 15.8489C20.5772 16.2885 19.6042 15.8021 17.6584 14.8291L17 14.4999V9.49995Z" stroke="#E62222"></path>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">Security Cameras</div>
          </div>
        <?php } ?>

        <?php if($amenities_features && in_array('gated', $amenities_features)): ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="#E62222" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.75736 10 5.17157 10 8 10H16C18.8284 10 20.2426 10 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16Z" stroke="#E62222"></path>
                <path d="M6 10V8C6 4.68629 8.68629 2 12 2C15.3137 2 18 4.68629 18 8V10" stroke="#E62222" stroke-linecap="round"></path>
                <path d="M9 16C9 16.5523 8.55228 17 8 17C7.44772 17 7 16.5523 7 16C7 15.4477 7.44772 15 8 15C8.55228 15 9 15.4477 9 16Z" fill="none" stroke="#E62222"></path>
                <path d="M13 16C13 16.5523 12.5523 17 12 17C11.4477 17 11 16.5523 11 16C11 15.4477 11.4477 15 12 15C12.5523 15 13 15.4477 13 16Z" fill="none" stroke="#E62222"></path>
                <path d="M17 16C17 16.5523 16.5523 17 16 17C15.4477 17 15 16.5523 15 16C15 15.4477 15.4477 15 16 15C16.5523 15 17 15.4477 17 16Z" fill="none" stroke="#E62222"></path>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">Gated Access</div>
          </div>
        <?php endif; ?>

        <?php if($amenities_features && in_array('247booking', $amenities_features)): ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#E62222" stroke="none" class="bi bi-check-lg" viewbox="0 0 16 16">
                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" fill="none" stroke="#E62222"></path>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">24/7 Online Booking</div>
          </div>
        <?php endif; ?>

        <?php if($amenities_features && in_array('aircon', $amenities_features)): ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#E62222" stroke="none" fill="currentColor" class="bi bi-snow" viewBox="0 0 16 16">
                <path d="M8 16a.5.5 0 0 1-.5-.5v-1.293l-.646.647a.5.5 0 0 1-.707-.708L7.5 12.793V8.866l-3.4 1.963-.496 1.85a.5.5 0 1 1-.966-.26l.237-.882-1.12.646a.5.5 0 0 1-.5-.866l1.12-.646-.884-.237a.5.5 0 1 1 .26-.966l1.848.495L7 8 3.6 6.037l-1.85.495a.5.5 0 0 1-.258-.966l.883-.237-1.12-.646a.5.5 0 1 1 .5-.866l1.12.646-.237-.883a.5.5 0 1 1 .966-.258l.495 1.849L7.5 7.134V3.207L6.147 1.854a.5.5 0 1 1 .707-.708l.646.647V.5a.5.5 0 1 1 1 0v1.293l.647-.647a.5.5 0 1 1 .707.708L8.5 3.207v3.927l3.4-1.963.496-1.85a.5.5 0 1 1 .966.26l-.236.882 1.12-.646a.5.5 0 0 1 .5.866l-1.12.646.883.237a.5.5 0 1 1-.26.966l-1.848-.495L9 8l3.4 1.963 1.849-.495a.5.5 0 0 1 .259.966l-.883.237 1.12.646a.5.5 0 0 1-.5.866l-1.12-.646.236.883a.5.5 0 1 1-.966.258l-.495-1.849-3.4-1.963v3.927l1.353 1.353a.5.5 0 0 1-.707.708l-.647-.647V15.5a.5.5 0 0 1-.5.5z"/>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">Air Conditioned</div>
          </div>
        <?php endif; ?>

        <?php if($amenities_features && in_array('lighting', $amenities_features)): ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="#E62222" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.5 19.5H9.5M14.5 19.5C14.5 18.7865 14.5 18.4297 14.5381 18.193C14.6609 17.4296 14.6824 17.3815 15.1692 16.7807C15.3201 16.5945 15.8805 16.0927 17.0012 15.0892C18.5349 13.7159 19.5 11.7206 19.5 9.5C19.5 5.35786 16.1421 2 12 2C7.85786 2 4.5 5.35786 4.5 9.5C4.5 11.7206 5.4651 13.7159 6.99876 15.0892C8.11945 16.0927 8.67987 16.5945 8.83082 16.7807C9.31762 17.3815 9.3391 17.4296 9.46192 18.193C9.5 18.4297 9.5 18.7865 9.5 19.5M14.5 19.5C14.5 20.4346 14.5 20.9019 14.299 21.25C14.1674 21.478 13.978 21.6674 13.75 21.799C13.4019 22 12.9346 22 12 22C11.0654 22 10.5981 22 10.25 21.799C10.022 21.6674 9.83261 21.478 9.70096 21.25C9.5 20.9019 9.5 20.4346 9.5 19.5" stroke="#E62222"></path>
                <path d="M12 17V15" stroke="#E62222" stroke-linecap="round"></path>
                <path d="M13.7324 14C13.3866 14.5978 12.7403 15 12 15C11.2597 15 10.6134 14.5978 10.2676 14" stroke="#E62222" stroke-linecap="round"></path>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">Lighting</div>
          </div>
        <?php endif; ?>

        <?php if($amenities_features && in_array('monthlease', $amenities_features)): ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg width="24" height="24" viewbox="0 0 20 20" fill="#E62222" stroke="" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">Month-To-Month Leases</div>
          </div>
        <?php endif; ?>

        <?php if($amenities_features && in_array('contactless', $amenities_features)): ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg width="24" height="24" viewbox="0 0 16 16" fill="#E62222" stroke="" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5"/>
                <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">Contactless Move-ins</div>
          </div>
        <?php endif; ?>

        <?php if($amenities_features && in_array('keypad', $amenities_features)): ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg width="24" height="24" viewbox="0 0 16 16" fill="#E62222" stroke="" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 2v2H2V2zm1 12v-2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m0-5V7a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m0-5V2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m5 10v-2a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m0-5V7a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m0-5V2a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1M9 2v2H7V2zm5 0v2h-2V2zM4 7v2H2V7zm5 0v2H7V7zm5 0h-2v2h2zM4 12v2H2v-2zm5 0v2H7v-2zm5 0v2h-2v-2zM12 1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm-1 6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm1 4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1z"/>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">Keypad Entry</div>
          </div>
        <?php endif; ?>

        <?php if($amenities_features && in_array('business', $amenities_features)): ?>
          <div class="hero-features-item d-flex align-items-center justify-content-center flex-wrap">
            <div class="standard-svg-icon w-embed">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#E62222" stroke="none" fill="currentColor" class="bi bi-snow" viewBox="0 0 16 16">
                <path d="M8 16a.5.5 0 0 1-.5-.5v-1.293l-.646.647a.5.5 0 0 1-.707-.708L7.5 12.793V8.866l-3.4 1.963-.496 1.85a.5.5 0 1 1-.966-.26l.237-.882-1.12.646a.5.5 0 0 1-.5-.866l1.12-.646-.884-.237a.5.5 0 1 1 .26-.966l1.848.495L7 8 3.6 6.037l-1.85.495a.5.5 0 0 1-.258-.966l.883-.237-1.12-.646a.5.5 0 1 1 .5-.866l1.12.646-.237-.883a.5.5 0 1 1 .966-.258l.495 1.849L7.5 7.134V3.207L6.147 1.854a.5.5 0 1 1 .707-.708l.646.647V.5a.5.5 0 1 1 1 0v1.293l.647-.647a.5.5 0 1 1 .707.708L8.5 3.207v3.927l3.4-1.963.496-1.85a.5.5 0 1 1 .966.26l-.236.882 1.12-.646a.5.5 0 0 1 .5.866l-1.12.646.883.237a.5.5 0 1 1-.26.966l-1.848-.495L9 8l3.4 1.963 1.849-.495a.5.5 0 0 1 .259.966l-.883.237 1.12.646a.5.5 0 0 1-.5.866l-1.12-.646.236.883a.5.5 0 1 1-.966.258l-.495-1.849-3.4-1.963v3.927l1.353 1.353a.5.5 0 0 1-.707.708l-.647-.647V15.5a.5.5 0 0 1-.5.5z"/>
              </svg>
            </div>
            <div class="blue montserrat font-weight-700 text-size-small-16 w-100 text-center line-height-12 pt-1">Business Storage</div>
          </div>
        <?php endif; ?>
      </div>

    </div>
    

    
    <div class="col-12 col-lg-6 hero-image-block">
      <?php if($location_photo) { echo wp_get_attachment_image( $location_photo, $photo_size, "", ["class" => "hero-image"] ); } ?>
    </div>
    

  </div>
</section>

<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/wp-content/themes/sage-10/resources/views/partials/home-hero.blade.php ENDPATH**/ ?>