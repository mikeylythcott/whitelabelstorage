{{-- Map --}}
<?php
  $google_map_embed_code = get_field('google_map_embed_code');
?>

@if($google_map_embed_code)
  <div class="google-map margin-top-80 position-relative w-100">
    {!! $google_map_embed_code!!}
  </div>
@endif
{{-- Map End --}}
