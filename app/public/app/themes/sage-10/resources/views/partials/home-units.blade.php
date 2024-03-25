<script>
  var myModal = document.getElementById('myModal')
  var myInput = document.getElementById('myInput')

  myModal.addEventListener('shown.bs.modal', function () {
    myInput.focus()
  })
</script>

<section class="container filter-units-wrapper padding-top-80">
  <div class="row ">
    {{-- Filter --}}
    <div class="col-3">
      <div class="filter-units border-radius-8px p-4">

        <h3 class="filter-units-header text-size-medium-30 font-weight-800">
          Filter Units
        </h3>

        <h4 class="font-weight-600 text-size-medium-24 mb-1">
          Unit Size / Type
        </h4>

      </div>
    </div>
    {{-- End Filter --}}


    {{-- Units --}}
    <div class="col-9">
      <div class="filtered-items">

        <style>
          .filtered-item-details-container .filtered-item-details-wrap:last-child {
            border-bottom: 0 !important;
            padding-bottom: 0 !important;
          }
        </style>

        {{-- Filter Item --}}


        <?php
          $args = array(
            'post_type' => 'storage-unit',
            'posts_per_page' => 200,
            'orderby' => 'date'
          );
          $unitquery = new WP_Query( $args );
        ?>

        @while ($unitquery->have_posts()) @php $unitquery->the_post() @endphp

          <?php
            $width = get_field('width');
            $length = get_field('length');
            $height = get_field('height');
            $size = get_field('size');
            $price = get_field('price');
            $standard_rate = get_field('standard_rate');
            $original_price = get_field('original_price');
            $managed_rate = get_field('managed_rate');
            $available_for_move_in = get_field('available_for_move_in');
            $unit_image = get_field('unit_image');
            $unit_image_sizde = 'thumbmail';
            $reserve_url = get_field('reserve_url');
            $status = get_field('status');
            $unit_type = get_field('unit_type');
          ?>

          <div class="filtered-item @unless($available_for_move_in) occupied @endunless">

            <div class="filter-item-image-container position-relative">
              <a href="#" class="button is-icon is-smaller-light w-inline-block size-link">
                <div class="small-white-icon-box">
                  <div class="icon-1x1-small blk w-embed">
                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewbox="0 0 24 24">
                      <defs>
                        <style>
                          .cls-1 {
                            fill: none;
                            stroke: #646f79;
                          }
                        </style>
                      </defs>
                      <path class="cls-1" d="M4.99,16.51s0,.02,0,.02c0,0,0,.02,0,.02,0,.02,0,.05,0,.08,0,.06,0,.15,0,.25,0,.21,0,.49,0,.79v1.1s.01.05.01.05h13.84v-2.38h-.05s-6.86-.01-6.86-.01c-2.76,0-4.48,0-5.5,0-.51,0-.86,0-1.07.02-.11,0-.18,0-.24.01-.03,0-.05,0-.06,0,0,0-.02,0-.02,0,0,0,0,0-.01,0,0,0-.01.01-.02.02ZM4.99,16.51l.05.02M4.99,16.51h0l.05.02M5.04,16.53s1.36-.05,6.88-.05h6.86v2.29H5.04v-1.1c-.02-.6-.01-1.12,0-1.15ZM11.17.81l-.02-.04.02.04c.27-.14.44-.22.55-.27.11-.04.17-.05.22-.04,0,0,0,0,0,0,0,0,.01,0,.03.01.02,0,.05.02.09.04.08.04.19.09.33.16.28.14.68.34,1.18.6,1,.51,2.39,1.24,4.04,2.11,2.32,1.22,3.71,1.96,4.52,2.4.41.22.67.37.84.47.08.05.14.09.18.12.04.03.06.05.06.05l.09.14v8.16c0,4.01,0,6.06-.01,7.12,0,.53-.01.81-.03.97,0,.08-.01.12-.02.15,0,.03-.01.04-.02.05h0s-.02.04-.03.06c0,.02-.01.03-.02.04,0,0,0,.02-.02.02,0,0-.03.02-.05.03-.06.02-.15.03-.32.05-.16.01-.39.02-.72.03-1.1.03-3.29.03-7.67.03-.77,0-1.6,0-2.51,0s-1.74,0-2.51,0c-4.38,0-6.57,0-7.67-.03-.32,0-.55-.02-.72-.03-.16-.01-.26-.03-.32-.05-.03,0-.04-.02-.05-.03,0,0-.01-.01-.02-.02,0,0,0-.02-.01-.03,0,0,0,0,0-.01,0-.02-.01-.04-.03-.06h0s-.02-.03-.02-.05c0-.03-.01-.07-.02-.15-.01-.16-.02-.44-.03-.97-.01-1.06-.01-3.1-.01-7.12V6.6s.09-.14.09-.14l-.04-.02.04.02s.02-.02.06-.05c.04-.03.09-.06.17-.11.15-.09.39-.23.76-.43.73-.4,1.97-1.05,4.02-2.14,2.71-1.43,5.21-2.74,5.57-2.93ZM18.8,11.95h.05v-2.38H4.97v2.38h13.83ZM18.78,15.38h.05v-.05s.01-1.13.01-1.13v-1.13s.01-.05.01-.05H4.96v.05s.01,1.13.01,1.13v1.13s.01.05.01.05h13.79ZM18.8,22.25h.05v-.05s-.01-1.13-.01-1.13v-1.13s-.01-.05-.01-.05h-.05s-6.85-.01-6.85-.01c-1.88,0-3.6,0-4.85,0-.62,0-1.13,0-1.48,0-.18,0-.31,0-.41,0-.05,0-.08,0-.11,0-.01,0-.02,0-.03,0,0,0,0,0-.01,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0-.02.01,0,0-.01.02-.01.02h0s0,0,0,0c0,0,0,0,0,0,0,0,0,.02,0,.02,0,.02,0,.04,0,.07,0,.06,0,.15,0,.25,0,.21,0,.49,0,.8v1.16h13.83Z"></path>
                    </svg>
                  </div>
                </div>
                <div>Medium</div>
              </a>

              @if (has_post_thumbnail() && current_user_can('mepr_auth'))
                {{ the_post_thumbnail('post-thumbnail', array('class' => 'w-100 h-100 object-fit-cover')) }}
              @else
                <img src="@asset('images/blank-unit-image.png')" alt="{!! get_the_title() !!} - {{ get_bloginfo('name', 'display') }}" class="w-100 h-100 object-fit-cover">
              @endif
            </div>

            <div class="filtered-item-details-container">
              <div class="filtered-item-details-wrap">
                <div class="filter-item-detail size">

                  <div>
                    <h5 class="text-size-medium-24 text-weight-semibold">
                      @if($size)
                        {!! $size !!}
                      @endif
                    </h5>
                    <div class="text-size-tiny-14 text-weight-normal text-color-grey line-height-1-2">
                      @if($unit_type)
                        {!! $unit_type !!}
                      @endif
                    </div>
                  </div>

                  <a href="#" class="button is-icon is-smaller size-help w-inline-block">
                    <div class="small-white-icon-box">
                      <div class="icon-1x1-small blk w-embed">
                        <svg width="17px" height="17px" stroke-width="1.5" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000">
                          <path d="M9 9L4 4M4 4V8M4 4H8" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M15 9L20 4M20 4V8M20 4H16" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M9 15L4 20M4 20V16M4 20H8" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M15 15L20 20M20 20V16M20 20H16" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                      </div>
                    </div>
                    <div>Size Help</div>
                  </a>

                </div>
              </div>

              <div class="filtered-item-details-wrap size-help-individual">
                <div class="filter-item-detail size-help">
                  <a href="#" class="button is-icon is-smaller hidden w-inline-block">
                    <div class="small-white-icon-box">
                      <div class="icon-1x1-small blk w-embed">
                        <svg width="17px" height="17px" stroke-width="1.5" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000">
                          <path d="M9 9L4 4M4 4V8M4 4H8" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M15 9L20 4M20 4V8M20 4H16" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M9 15L4 20M4 20V16M4 20H8" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M15 15L20 20M20 20V16M20 20H16" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                      </div>
                    </div>
                    <div>Size Help</div>
                  </a>
                </div>
              </div>

              <div class="filtered-item-details-wrap">
                <div class="filter-item-detail">
                  <div class="text-size-regular-20 text-color-red text-weight-semibold line-height-1-2 montserrat">50% off</div>
                  <div class="text-size-small-16 text-color-red line-height-1-2">first 3 months!</div>
                </div>
              </div>

              <div class="filtered-item-details-wrap">
                <div class="filter-item-detail right-flex-end-justified">

                  <div class="filter-detail-price-block">
                    @if($original_price)
                      <h5 class="text-size-small-16 text-weight-normal text-color-grey text-style-strikethrough">
                        ${!! $original_price !!}
                      </h5>
                    @endif

                    <h5 class="text-size-medium-24 text-weight-semibold text-color-red">
                      @if($standard_rate)
                        ${!! $standard_rate !!}
                      @endif
                    </h5>
                  </div>

                  <div class="text-size-tiny-14 text-weight-normal text-color-grey line-height-1-2">
                    @if($available_for_move_in)
                      Available
                    @else
                      Occupied
                    @endif
                  </div>

                  <div class="reserve-now">
                    @if($available_for_move_in)
                      <a href="#" class="button is-blue is-icon is-smaller mt-1 w-inline-block">
                    @else
                      <div class="button is-gray is-icon is-smaller mt-1 w-inline-block">
                    @endif
                      <div class="icon-1x1-small w-embed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-cart-fill" viewbox="0 0 16 16">
                          <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"></path>
                        </svg>
                      </div>

                      <div>Reserve Now</div>
                    @if($available_for_move_in)
                      </a>
                    @else
                      </div>
                    @endif
                  </div>

                </div>
              </div>

              <div class="filtered-item-details-wrap reserve-now-individual">
                <div class="filter-item-detail">
                  @if($available_for_move_in)
                    <a href="#" class="button is-blue is-smaller w-inline-block">
                  @else
                    <div href="#" class="button is-blue is-smaller w-inline-block">
                  @endif
                    <div>Reserve Now</div>
                  @if($available_for_move_in)
                    </a>
                  @else
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>

        @endwhile

        <?php wp_reset_postdata(); ?>

        {{-- End Filter Item --}}



        {{-- MODAL --}}
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal" data-unit-id="78c97c4d-5639-44fd-a6c7-6cf0a0d8d036">
          Reserve Now
        </button>

        <!-- Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Booking Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <?php echo do_shortcode('[gravityform id="1" title="true"]'); ?>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div>
        {{-- MODAL END --}}



        {{-- the_content --}}

          <?php the_content(); ?>

        {{-- END --}}

      </div>
    </div>
    {{-- End Units --}}
  </div>
</section>
