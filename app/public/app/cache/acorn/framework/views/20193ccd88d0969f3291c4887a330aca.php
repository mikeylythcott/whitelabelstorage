<?php
  $header_logo = get_field('header_logo','option');
  $header_logo_size = 'medium';
  $call_or_text_title = get_field('call_or_text_title','option');
  $header_phone_number = get_field('header_phone_number','option');
  $pay_your_bill_title = get_field('pay_your_bill_title','option','option');
  $bill_payment_link = get_field('bill_payment_link','option');
?>

<header class="banner d-flex justify-content-end align-items-center">
  <a class="brand me-auto" href="<?php echo e(home_url('/')); ?>">
    <?php if($header_logo): ?>
      <?php if($header_logo) { echo wp_get_attachment_image( $header_logo, $header_logo_size, "", ["class" => "d-inline-block main-logo w-auto"] ); } ?>
      <span class="text-hide d-inline-block"><?php echo e(get_bloginfo('name', 'display')); ?></span>
    <?php else: ?>
      <?php echo $siteName; ?>

    <?php endif; ?>
  </a>

  <div class="nav-menu-wrap me-3">
    <?php if($call_or_text_title && $header_phone_number): ?>
      <?php echo $call_or_text_title; ?> <span class="font-weight-700 blue"><?php echo $header_phone_number; ?></span>
    <?php endif; ?>
  </div>

  <?php if($pay_your_bill_title): ?>
    <a href="<?php echo $bill_payment_link; ?>" class="button is-icon is-white-shadowed is-small w-inline-block">
  <?php else: ?>
    <a href="#" class="button is-icon is-white-shadowed is-small w-inline-block">
  <?php endif; ?>
    <div class="icon-1x1-small blk w-embed">
      <svg width="16" height="19" viewbox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.83001 0.333252H9.16999C9.94257 0.333252 10.3289 0.333252 10.6404 0.441657C11.2312 0.647222 11.695 1.12471 11.8947 1.73289C12 2.05362 12 2.45129 12 3.24663V12.5827C12 13.1549 11.3433 13.4584 10.9279 13.0783C10.6839 12.855 10.3161 12.855 10.0721 13.0783L9.75 13.373C9.32228 13.7644 8.67772 13.7644 8.25 13.373C7.82228 12.9816 7.17772 12.9816 6.75 13.373C6.32228 13.7644 5.67772 13.7644 5.25 13.373C4.82228 12.9816 4.17772 12.9816 3.75 13.373C3.32228 13.7644 2.67772 13.7644 2.25 13.373L1.92794 13.0783C1.68388 12.855 1.31612 12.855 1.07206 13.0783C0.656665 13.4584 0 13.1549 0 12.5827V3.24663C0 2.45129 0 2.05362 0.105303 1.73289C0.304986 1.12471 0.768813 0.647222 1.35959 0.441657C1.67114 0.333252 2.05743 0.333252 2.83001 0.333252ZM8.03964 4.66626C8.22355 4.46028 8.20566 4.1442 7.99967 3.96029C7.79369 3.77637 7.47761 3.79426 7.2937 4.00025L5.28571 6.24919L4.7063 5.60025C4.52239 5.39426 4.20631 5.37637 4.00033 5.56029C3.79434 5.7442 3.77645 6.06028 3.96036 6.26626L4.91275 7.33293C5.00761 7.43918 5.14328 7.49992 5.28571 7.49992C5.42815 7.49992 5.56382 7.43918 5.65868 7.33293L8.03964 4.66626ZM3 8.83326C2.72386 8.83326 2.5 9.05711 2.5 9.33326C2.5 9.6094 2.72386 9.83326 3 9.83326H9C9.27614 9.83326 9.5 9.6094 9.5 9.33326C9.5 9.05711 9.27614 8.83326 9 8.83326H3Z" fill="#E62222"></path>
      </svg>
    </div>
    <?php if($pay_your_bill_title): ?>
      <div>
        <?php echo $pay_your_bill_title; ?>

      </div>
    <?php else: ?>
      <div>Pay Your Bill</div>
    <?php endif; ?>
  </a>

  <?php if(has_nav_menu('primary_navigation')): ?>
    <nav class="nav-primary" aria-label="<?php echo e(wp_get_nav_menu_name('primary_navigation')); ?>">
      <?php echo wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]); ?>

    </nav>
  <?php endif; ?>
</header>
<?php /**PATH /Users/mikey/Local Sites/white-label-storage-b2c/app/public/app/themes/sage-10/resources/views/sections/header.blade.php ENDPATH**/ ?>