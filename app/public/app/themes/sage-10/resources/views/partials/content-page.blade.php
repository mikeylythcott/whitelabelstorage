@if(is_home() || is_front_page())

  {{-- Hero --}}
    @include('partials.home-hero')
  {{-- end hero --}}

  {{-- Units & Filter --}}
    @include('partials.home-units')
  {{-- end Units & Filter --}}

  {{-- Photo Gallery --}}
    @include('partials.home-photo-gallery')
  {{-- end Photo Gallery --}}

  {{-- Storage Units --}}
    @include('partials.home-storage-units')
  {{-- end Storage Units --}}

  {{-- Map --}}
    @include('partials.home-map')
  {{-- end Map --}}

  {{-- About --}}
    @include('partials.home-about')
  {{-- About End --}}

  {{-- Testimonials --}}
    @include('partials.home-testimonials')
  {{-- Testimonials End --}}

  {{-- FAQ --}}
    @include('partials.home-faq')
  {{-- FAQ End --}}

@elseif(is_page('full-form'))

  {{-- Full Form --}}
    @include('partials.checkout-full-form')
  {{-- end full form --}}

@elseif(is_page('checkout'))

  {{-- Checkout page 1 --}}
    @include('partials.checkout')
  {{-- Checkout page 1 --}}

@else

<section class="container">
  <div class="row">
    <div class="col-12">
      @php(the_content())
    </div>
  </div>
</section>

@endif

@if ($pagination)

  <section class="container">
    <div class="row">
      <div class="col-12">
        <nav class="page-nav" aria-label="Page">
          {!! $pagination !!}
        </nav>
      </div>
    </div>
  </section>

@endif
