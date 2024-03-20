<?php

/**
 * Theme setup.
 */

namespace App;

use function Roots\bundle;

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue();
}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(400, 267, array( 'center', 'top'));
    add_image_size( 'photogal', 900, 600, true );

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        // 'comment-form',
        // 'comment-list',
        'gallery',
        // 'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});


/*-----------------------------------------------------------------------------------*/
/* Allow SVG in text fields in ACF
/*-----------------------------------------------------------------------------------*/

add_filter( 'wp_kses_allowed_html', __NAMESPACE__ . '\\acf_add_allowed_svg_tag', 10, 2 );
function acf_add_allowed_svg_tag( $tags, $context ) {
    if ( $context === 'acf' ) {
        $tags['svg']  = array(
            'xmlns'       => true,
            'fill'        => true,
            'viewbox'     => true,
            'role'        => true,
            'aria-hidden' => true,
            'focusable'   => true,
        );
        $tags['path'] = array(
            'd'    => true,
            'fill' => true,
        );
    }

    return $tags;
}


/*-----------------------------------------------------------------------------------*/
/* // remove XML PRC
/*-----------------------------------------------------------------------------------*/

add_filter('xmlrpc_enabled', __NAMESPACE__ . '\\__return_false');


/*-----------------------------------------------------------------------------------*/
/* // Get The Archive Title
/*-----------------------------------------------------------------------------------*/

add_filter( 'get_the_archive_title', function ($title) {

    if ( is_category() ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag() ) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = '<span class="vcard">' . get_the_author() . '</span>' ;
        }
    return $title;

});

/*-----------------------------------------------------------------------------------*/
/* // Simplify TINY MCE
/*-----------------------------------------------------------------------------------*/

/**
 * Customize TinyMCE's configuration
 *
 * @param   array
 * @return  array
 */
function configure_tinymce($in) {
  $in['paste_preprocess'] = "function(plugin, args){
    // Strip all HTML tags except those we have whitelisted
    var whitelist = 'p,b,strong,i,em,h2,h1,h3,h4,h5,h6,ul,li,ol,a';
    var stripped = jQuery('<div>' + args.content + '</div>');
    var els = stripped.find('*').not(whitelist);
    for (var i = els.length - 1; i >= 0; i--) {
      var e = els[i];
      jQuery(e).replaceWith(e.innerHTML);
    }
    // Strip all class and id attributes
    stripped.find('*').removeAttr('id').removeAttr('class');
    // Return the clean HTML
    args.content = stripped.html();
  }";
  return $in;
}

add_filter('tiny_mce_before_init', __NAMESPACE__ . '\\configure_tinymce');


/*-----------------------------------------------------------------------------------*/
/* // Disable comments on specific post types - in this case, attachments. Can add more types via comma.
/*-----------------------------------------------------------------------------------*/

function filter_media_comment_status( $open, $post_id ) {
  $post = get_post( $post_id );
  if( $post->post_type == 'attachment' ) {
    return false;
  }
  return $open;
}
add_filter( 'comments_open', __NAMESPACE__ . '\\filter_media_comment_status', 10 , 2 );


/*-----------------------------------------------------------------------------------*/
/* // Disable HTML in Comments
/*-----------------------------------------------------------------------------------*/

// This will occur when the comment is posted
function plc_comment_post( $incoming_comment ) {

// convert everything in a comment to display literally
$incoming_comment['comment_content'] = htmlspecialchars($incoming_comment['comment_content']);

// the one exception is single quotes, which cannot be #039; because WordPress marks it as spam
$incoming_comment['comment_content'] = str_replace( "'", '&apos;', $incoming_comment['comment_content'] );

return( $incoming_comment );
}

// This will occur before a comment is displayed
function plc_comment_display( $comment_to_display ) {

// Put the single quotes back in
$comment_to_display = str_replace( '&apos;', "'", $comment_to_display );

return $comment_to_display;
}

/*-----------------------------------------------------------------------------------*/
/* // Default Media Type For Galleries //
/*-----------------------------------------------------------------------------------*/

function my_gallery_default_type_set_link( $settings ) {
    $settings['galleryDefaults']['link'] = 'file';
    $settings['galleryDefaults']['columns'] = '3';
    $settings['galleryDefaults']['size'] = 'medium';
    return $settings;
}
add_filter( 'media_view_settings', __NAMESPACE__ . '\\my_gallery_default_type_set_link');


/*-----------------------------------------------------------------------------------*/
/* Advanced Custom Fields - options page
/*-----------------------------------------------------------------------------------*/

if( function_exists('acf_add_options_page') ) {

  acf_add_options_page(array(
    'page_title'  => 'Sitewide',
    'menu_title'  => 'Sitewide Options',
    'menu_slug'   => 'theme-general-settings',
    'capability'  => 'edit_posts',
    'icon_url'    => 'dashicons-admin-site',
    'redirect'    => false
  ));

}

/*-----------------------------------------------------------------------------------*/
/* Add Featured Image Columns in ADMIN
/*-----------------------------------------------------------------------------------*/

add_image_size( 'small-square', 90, 90, true );

// Add the posts and pages columns filter. They can both use the same function.
add_filter('manage_posts_columns', __NAMESPACE__ . '\\crunchify_add_post_admin_thumbnail_column', 2);
add_filter('manage_pages_columns', __NAMESPACE__ . '\\crunchify_add_post_admin_thumbnail_column', 2);

// Add the column
function crunchify_add_post_admin_thumbnail_column($crunchify_columns){
  $crunchify_columns['crunchify_thumb'] = __('Image');
  return $crunchify_columns;
}

// Let's manage Post and Page Admin Panel Columns
add_action('manage_posts_custom_column', __NAMESPACE__ . '\\crunchify_show_post_thumbnail_column', 2, 2);
add_action('manage_pages_custom_column', __NAMESPACE__ . '\\crunchify_show_post_thumbnail_column', 2, 2);

// Here we are grabbing featured-thumbnail size post thumbnail and displaying it
function crunchify_show_post_thumbnail_column($crunchify_columns, $crunchify_id){
  switch($crunchify_columns){
    case 'crunchify_thumb':
    if( function_exists('the_post_thumbnail') )
      echo the_post_thumbnail( 'small-square' );
    else
      echo 'Your theme doesn\'t support featured image...';
    break;
  }
}


/*-----------------------------------------------------------------------------------*/
/* Add ACF styling to fields in admin
/*-----------------------------------------------------------------------------------*/

function my_acf_admin_head() {
  ?>
  <style type="text/css">

  .acf-postbox {
    background: #e5e5e5 !important;
    border-right: 1px solid #d5d5d5 !important;
    border-left: 1px solid #d5d5d5 !important;
    border-top: none !important;
    border-bottom: 1px solid #d5d5d5 !important;
    margin-bottom: 15px !important;
  }

  .acf-postbox .acf-fields .acf-field {
    background: #ffffff !important;
    margin: 0 !important;
  }

  .acf-postbox>.inside {
    border-bottom: none !important;
  }

  .acf-postbox .acf-fields .acf-field .acf-field {
    margin-bottom: 0 !important;
  }

  .acf-postbox h2.hndle.ui-sortable-handle {
    background-color: #161616 !important;
    color: #fff !important;
    padding: 15px !important;
    text-transform: uppercase;
  }

  .postbox-container .postbox-header {
    background-color: #161616 !important;
    color: #fff !important;
  }

  .postbox-container .postbox-header h2 {
    color: #fff !important;
    padding: 15px !important;
    text-transform: uppercase;
  }

  .acf-postbox h2.hndle.ui-sortable-handle span,
  .acf-admin-page #poststuff .postbox-header h2,
  .acf-admin-page #poststuff .postbox-header h3 {
    color: #fff !important;
  }

  .acf-postbox span.toggle-indicator {
    color: #fff !important;
  }

  .acf-postbox span.toggle-indicator:before {
    margin-top: 12px !important;
  }

  .acf-field {
    padding: 25px !important;
  }

  .acf-field .acf-label {
    font-size: 114%;
    color: #161616;
    margin-bottom: 20px !important;
  }

  .acf-field .acf-label.acf-accordion-title {
    margin-bottom: 0 !important;
    background: #f9f9f9;
  }

  .acf-accordion .acf-accordion-title label {
    font-size: 16px;
    font-weight: 400;
  }

  .acf-table tr.acf-row td {
    border: none;
    border-bottom: 10px solid #161616;
    margin-bottom: -10px;
  }

  #adminmenu .wp-menu-image img {
    width: 16px;
    height: 16px;
  }

  </style>

  <script type="text/javascript">
  (function($){

    /* ... */

  })(jQuery);
  </script>
  <?php
}

add_action('acf/input/admin_head', __NAMESPACE__ . '\\my_acf_admin_head');


/*-----------------------------------------------------------------------------------*/
/* ACF PRO - add ability to show post types in select dropdown field
/*-----------------------------------------------------------------------------------*/

add_filter('acf/load_field/name=post_type', __NAMESPACE__ . '\\yourprefix_acf_load_post_types');
/*
 *  Load Select Field `select_post_type` populated with the value and labels of the singular
 *  name of all public post types
 */
function yourprefix_acf_load_post_types( $field ) {

    $choices = get_post_types( array( 'show_in_nav_menus' => true ), 'objects' );

    foreach ( $choices as $post_type ) :
        $field['choices'][$post_type->name] = $post_type->labels->singular_name;
    endforeach;
    return $field;
}

/*-----------------------------------------------------------------------------------*/
/* Allow SVG's in media uploads
/*-----------------------------------------------------------------------------------*/

function upload_svg_files( $allowed ) {
    if ( !current_user_can( 'manage_options' ) )
        return $allowed;
    $allowed['svg'] = 'image/svg+xml';
    return $allowed;
}
add_filter( 'upload_mimes', __NAMESPACE__ . '\\upload_svg_files');


/*-----------------------------------------------------------------------------------*/
/* Disable Author Archives
/*-----------------------------------------------------------------------------------*/

function shapeSpace_disable_author_archives() {

  if (is_author()) {

    global $wp_query;
    $wp_query->set_404();
    status_header(404);

  } else {
    redirect_canonical();
  }

}
remove_filter('template_redirect', __NAMESPACE__ . '\\redirect_canonical');
add_action('template_redirect', __NAMESPACE__ . '\\shapeSpace_disable_author_archives');


// block WP enum scans
if (!is_admin()) {
  // default URL format
  if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
  add_filter('redirect_canonical', __NAMESPACE__ . '\\shapeSpace_check_enum', 10, 2);
}
function shapeSpace_check_enum($redirect, $request) {
  // permalink URL format
  if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
  else return $redirect;
}


/*-----------------------------------------------------------------------------------*/
/* Disable Author Archives & Block Enumeration
/*-----------------------------------------------------------------------------------*/


add_filter( 'rest_endpoints', function( $endpoints ){
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
});

remove_action( 'wp_head', __NAMESPACE__ . '\\rest_output_link_wp_head', 10 );

add_filter( 'rest_authentication_errors', function( $result ) {
    if ( ! empty( $result ) ) {
        return $result;
    }
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_not_logged_in', 'Only authenticated users can access the REST API.', array( 'status' => 401 ) );
    }
    return $result;
});


/*-----------------------------------------------------------------------------------*/
/* Automatically set the image Title, Alt-Text, Caption & Description upon upload
-------------------------------------------------------------------------------------*/

add_action( 'add_attachment', __NAMESPACE__ . '\\my_set_image_meta_upon_image_upload' );
function my_set_image_meta_upon_image_upload( $post_ID ) {

	// Check if uploaded file is an image, else do nothing

	if ( wp_attachment_is_image( $post_ID ) ) {

		$my_image_title = get_post( $post_ID )->post_title;

		// Sanitize the title:  remove hyphens, underscores & extra spaces:
		$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );

		// Sanitize the title:  capitalize first letter of every word (other letters lower case):
		$my_image_title = ucwords( strtolower( $my_image_title ) );

		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			'ID'		=> $post_ID,			// Specify the image (ID) to be updated
			'post_title'	=> $my_image_title,		// Set image Title to sanitized title
			// 'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title
			'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
		);

		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );

		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $my_image_meta );

	}
}
