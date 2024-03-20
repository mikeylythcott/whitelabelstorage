<?php


function wpgetapi_api_importer_get_post_types( $field ) {
    $args = array(
       'public'   => true,
       '_builtin' => false,
    );
    $output = 'objects'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
    $post_types = get_post_types( $args, $output, $operator ); 
    $items = array(
        'post' => 'Post',
        'page' => 'Page',
    );
    foreach ( $post_types  as $post_type ) {
        $items[ $post_type->name ] = esc_html( $post_type->labels->singular_name );
    }
    return $items;
}


function wpgetapi_api_importer_get_users( $field ) {
    $items = array();
    $users = get_users( array( 'role__in' => array( 'administrator', 'author', 'editor' ) ) );
    // Array of WP_User objects.
    $items[] = 'No author';
    foreach ( $users as $user ) :
        $items[ $user->ID ] = esc_html( $user->display_name );
    endforeach;
    return $items;
}


function wpgetapi_api_importer_get_mapping_options( $field ) {

    $theid = str_replace( 'wpgetapi_importer_', '', $_GET['page'] );

    // get our data from linked endpoint
    $endpoint_settings  = get_option( 'wpgetapi_importer_' . $theid );
    $linked_endpoint_id = isset( $endpoint_settings['linked_endpoint'] ) ? $endpoint_settings['linked_endpoint'] : null;
    
    if( $linked_endpoint_id ) {
        $linked_endpoint    = get_option( 'wpgetapi_importer_' . $linked_endpoint_id );
        $post_type          = $linked_endpoint['item_post_type'];
    } else {
        $post_type          = $endpoint_settings['item_post_type'];
    }
    
    //$taxonomies = get_taxonomies( array( 'object_type' => [ $post_type ] ), 'objects' );
    $taxonomies = get_object_taxonomies( $post_type );
    
    if( $post_type == 'product' ) {

        // Available options.
        $weight_unit    = get_option( 'woocommerce_weight_unit' );
        $dimension_unit = get_option( 'woocommerce_dimension_unit' );
        $options        = array(
            ''                      => __( 'Don\'t import', 'woocommerce' ),
            '_post_title'           => __( 'Product Name (overrides Post Title field)', 'wpgetapi-post-import' ),
            '_post_excerpt'         => __( 'Short description', 'woocommerce' ),
            '_post_content'         => __( 'Description', 'woocommerce' ),
            'sku'                   => __( 'SKU', 'woocommerce' ),
            'regular_price'         => __( 'Regular price', 'woocommerce' ),
            'sale_price'            => __( 'Sale price', 'woocommerce' ),
            'weight'                => sprintf( __( 'Weight (%s)', 'woocommerce' ), $weight_unit ),
            'length'                => sprintf( __( 'Length (%s)', 'woocommerce' ), $dimension_unit ),
            'width'                 => sprintf( __( 'Width (%s)', 'woocommerce' ), $dimension_unit ),
            'height'                => sprintf( __( 'Height (%s)', 'woocommerce' ), $dimension_unit ),
            'featured'              => __( 'Is featured?', 'woocommerce' ),
            'catalog_visibility'    => __( 'Visibility in catalog', 'woocommerce' ),
            'tax_status'            => __( 'Tax status', 'woocommerce' ),
            'tax_class'             => __( 'Tax class', 'woocommerce' ),
            'manage_stock'          => __( 'Manage stock?', 'woocommerce' ),
            'stock_status'          => __( 'In stock?', 'woocommerce' ),
            'stock_quantity'        => _x( 'Stock', 'Quantity in stock', 'woocommerce' ),
            'backorders'            => __( 'Backorders allowed?', 'woocommerce' ),
            'low_stock_amount'      => __( 'Low stock amount', 'woocommerce' ),
            'sold_individually'     => __( 'Sold individually?', 'woocommerce' ),
            'reviews_allowed'       => __( 'Allow customer reviews?', 'woocommerce' ),
            'purchase_note'         => __( 'Purchase note', 'woocommerce' ),
            'menu_order'            => __( 'Position', 'woocommerce' ),

            '_attribute'            => __( 'Attribute', 'woocommerce' ),
            '_meta'                 => __( 'Custom field', 'wpgetapi-post-import' ),
            '_meta_array'           => __( 'Custom fields (multiple fields when array)', 'wpgetapi-post-import' ),
            '_featured_image'       => __( 'Featured Image', 'wpgetapi-post-import' ),
            '_gallery_images'       => __( 'Gallery Images', 'wpgetapi-post-import' ),
            '_all_images'           => __( 'Featured & Gallery Images', 'wpgetapi-post-import' ),
            //'_tag'                  => __( 'Tag', 'wpgetapi-post-import' ),
            //'_category'             => __( 'Parent category', 'wpgetapi-post-import' ),
            '_post_date'            => __( 'Post Date', 'wpgetapi-post-import' ),
            '_post_date_gmt'        => __( 'Post Date GMT', 'wpgetapi-post-import' ),
            // 'parent_id'          => __( 'Parent', 'woocommerce' ),
            // 'upsell_ids'         => __( 'Upsells', 'woocommerce' ),
            // 'cross_sell_ids'     => __( 'Cross-sells', 'woocommerce' ),
            // 'grouped_products'   => __( 'Grouped products', 'woocommerce' ),
            // 'external'           => array(
            //     'name'    => __( 'External product', 'woocommerce' ),
            //     'options' => array(
            //         'product_url' => __( 'External URL', 'woocommerce' ),
            //         'button_text' => __( 'Button text', 'woocommerce' ),
            //     ),
            // ),
            // 'shipping_class_id'  => __( 'Shipping class', 'woocommerce' ),
            // ),
            //'id'                 => __( 'ID', 'woocommerce' ),
            //'type'               => __( 'Type', 'woocommerce' ),
            //'published'          => __( 'Published', 'woocommerce' ),
        );

        if( ! empty( $taxonomies ) ) {

            $items = array();
            foreach ( $taxonomies  as $key => $taxonomy ) {

                $tax = get_taxonomy( $taxonomy );

                // ignore attributes as they are set another way
                if( strpos( $tax->name, 'pa_' ) !== false || $tax->name == 'product_type' ) 
                    continue;

                $options[ '_tax_' . $tax->name ] = $tax->labels->singular_name;

                // if( $taxonomy->hierarchical ) {

                //     $options[ '_tax_' . $taxonomy->name ] = $taxonomy->labels->singular_name;

                //     foreach ( $field->args['item_keys'] as $i => $key ) {
                //         $options[ '_taxsub_' . $taxonomy->name . '-' . $key ] = sprintf( __( '&nbsp; Child %1s of ' ), $taxonomy->labels->singular_name ) . $key;
                //     }

                // }

            }

        }

    } else {

        // Available options.
        $options = array(
            ''                  => __( 'Don\'t map this', 'wpgetapi-post-import' ),
            '_meta'             => __( 'Custom field', 'wpgetapi-post-import' ),
            '_meta_array'       => __( 'Custom fields (creates multiple fields when data is array)', 'wpgetapi-post-import' ),
            '_featured_image'   => __( 'Featured Image', 'wpgetapi-post-import' ),
            '_post_title'       => __( 'Title (overrides Post Title field)', 'wpgetapi-post-import' ),
            '_post_content'     => __( 'Post Content', 'wpgetapi-post-import' ),
            '_post_excerpt'     => __( 'Post Excerpt', 'wpgetapi-post-import' ),
            '_post_slug'        => __( 'Post Slug', 'wpgetapi-post-import' ),
            '_post_date'        => __( 'Post Date', 'wpgetapi-post-import' ),
            '_post_date_gmt'    => __( 'Post Date GMT', 'wpgetapi-post-import' ),
            
            //'_gallery_images'       => __( 'Gallery Images', 'wpgetapi-post-import' ),
            //'_all_images'           => __( 'Featured & Gallery Images', 'wpgetapi-post-import' ),
            //'_tag'                  => __( 'Tag', 'wpgetapi-post-import' ),
        );

        if( ! empty( $taxonomies ) ) {

            $items = array();
            foreach ( $taxonomies  as $key => $taxonomy ) {

                $tax = get_taxonomy( $taxonomy );
                
                $options[ '_tax_' . $tax->name ] = $tax->labels->singular_name;

                if( $tax->hierarchical ) {

                    $options[ '_tax_' . $tax->name ] = $tax->labels->singular_name;

                    foreach ( $field->args['item_keys'] as $i => $key ) {
                        $options[ '_taxsub_' . $tax->name . '-' . $key ] = sprintf( __( '&nbsp; Child %1s of ' ), $tax->labels->singular_name ) . $key;
                    }

                }

            }

        }

    }

    return $options;
}