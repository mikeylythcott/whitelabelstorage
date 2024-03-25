@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())

    @unless(is_home() || is_front_page())
      @include('partials.page-header')
    @endunless
    
    @includeFirst(['partials.content-page', 'partials.content'])

  @endwhile
@endsection
