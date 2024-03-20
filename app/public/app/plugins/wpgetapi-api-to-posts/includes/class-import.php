<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The main class
 * @since 1.0.0
 */
class wpgetapi_api_importer_Importer {

    // our post data
    public $post_type = 'post';
    public $post_status = 'publish';

    // our saved options
    public $setup_opts;
    public $saved_settings;
    public $endpoint_opts;
    public $mapping_opts;
    public $api_data;

    public $import_interval = '';
    public $sync_posts = 'full_sync';
    public $post_author;
    public $post_name = '';
    public $filter_by = '';
    public $flatten = 'no';
    public $max_images = 15;

    // the key that is used to identify individual items
    public $item_key = 'id';

    // optional key that we need to step down into to get array of all items
    public $root_key = '';
    
    // args for the wpgetapi_endpoint call
    public $args = array( 'debug' => false );

    public $theid = '';
    public $api_id = '';
    public $endpoint_id = '';

    /**
     * Main constructor
     * @since 1.0.0
     */
    public function __construct() {

        $this->theid = isset( $_GET['page'] ) ? str_replace( 'wpgetapi_importer_', '', $_GET['page'] ) : null;

        if( ! $this->theid ) {
            $this->theid = isset( $_REQUEST['theid'] ) ? sanitize_text_field( $_REQUEST['theid'] ) : null;
            $this->api_id = isset( $_REQUEST['api_id'] ) ? sanitize_text_field( $_REQUEST['api_id'] ) : null;
            $this->endpoint_id = isset( $_REQUEST['endpoint_id'] ) ? sanitize_text_field( $_REQUEST['endpoint_id'] ) : null;
        }

        $this->init_settings();

        // import and create posts manually
        add_action( 'admin_post_wpgetapi_importer_import_manually', array( $this, 'import_manually' ) );
        add_action( 'admin_post_wpgetapi_importer_create_posts_manually', array( $this, 'create_posts_manually' ) );

        add_action( 'wp_ajax_wpgetapi_importer_run_post_creator_ajax', array($this, 'run_post_creator_ajax' ) );

        // cron job for auto imports
        add_action( 'init', array( $this, 'schedule_import_cron' ) );
        add_action( 'wpgetapi_auto_import', array( $this, 'import_cron_function' ), 10, 2 );
        add_action( 'wpgetapi_auto_post_creator', array( $this, 'post_creator_cron_function' ) );

        // Add admin notice, if we have any
        add_action( 'admin_notices', array( $this, 'admin_notice' ) );
        
        // allow base64 encoded - taken from Pro plugin
        add_action( 'wpgetapi_header_parameters', array( $this, 'maybe_base64_encode' ), 10, 2 );

        // allow query_variables - taken from Pro plugin
        add_filter( 'wpgetapi_final_url', array( $this, 'custom_query_variables' ), 11, 2 );

        // on save action
        add_action( 'cmb2_save_options-page_fields', array( $this, 'save_initial_data_on_save' ), 1, 4 );


        // if the API uses pagination with numbers
        add_action( 'wpgetapi_post_import_paginate', array( $this, 'do_pagination' ), 10, 1 );
        add_action( 'wpgetapi_post_import_pagination_event', array( $this, 'process_pagination_event' ), 10, 1 );

        // if the API uses pagination with numbers
        add_action( 'wpgetapi_post_import_paginate_for_urls', array( $this, 'do_pagination_for_urls' ), 10, 1 );
        add_action( 'wpgetapi_post_import_pagination_event_for_urls', array( $this, 'process_pagination_event_for_urls' ), 10, 1 );

        // background create posts
        add_action( 'wpgetapi_post_import_create_update_posts', array( $this, 'schedule_create_update' ), 10, 1 );
        add_action( 'wpgetapi_post_import_schedule_create_update_posts', array( $this, 'process_create_update_actions' ), 10, 4 );

        // ignore the unique SKU checks for woocommerce
        add_filter( 'wc_product_has_unique_sku', '__return_false' );
       
    }


    /**
     * 
     * @since  1.0.0
     */
    public function init_settings() {

        // get our options
        $this->setup_opts       = get_option( 'wpgetapi_setup' );
        $this->api_data         = $this->get_saved_items();
        $this->saved_settings   = get_option( 'wpgetapi_importer_' . $this->theid );
        $this->mapping_opts     = $this->get_mapping_opts();

        // set up our saved options
        $this->post_type        = isset( $this->saved_settings['item_post_type'] ) ? sanitize_text_field( $this->saved_settings['item_post_type'] ) : $this->post_type;
        $this->post_status      = isset( $this->saved_settings['post_status'] ) ? sanitize_text_field( $this->saved_settings['post_status'] ) : $this->post_status;
        $this->import_interval  = isset( $this->saved_settings['import_interval'] ) ? sanitize_text_field( $this->saved_settings['import_interval'] ) : $this->import_interval;
        $this->sync_posts       = isset( $this->saved_settings['sync_posts'] ) ? sanitize_text_field( $this->saved_settings['sync_posts'] ) : $this->sync_posts;
        $this->post_name        = isset( $this->saved_settings['item_post_name'] ) ? sanitize_text_field( $this->saved_settings['item_post_name'] ) : $this->post_name;
        $this->post_author      = isset( $this->saved_settings['post_author'] ) ? sanitize_text_field( $this->saved_settings['post_author'] ) : $this->post_author;
        $this->filter_by        = isset( $this->saved_settings['filter_by'] ) ? sanitize_text_field( $this->saved_settings['filter_by'] ) : $this->filter_by;
        $this->root_key         = isset( $this->saved_settings['root_key'] ) ? sanitize_text_field( $this->saved_settings['root_key'] ) : $this->root_key;
        $this->item_key         = isset( $this->saved_settings['item_key'] ) ? sanitize_text_field( $this->saved_settings['item_key'] ) : $this->item_key;
        $this->flatten          = isset( $this->saved_settings['flatten_array'] ) ? sanitize_text_field( $this->saved_settings['flatten_array'] ) : $this->flatten;


    }


    /**
     * See if we are saving an endpoint to be imported.
     * And then save our intitial data.
     * The initial data is used to detect the root, the type of array and the item ids
     * @since  1.0.0
     */
    public function save_initial_data_on_save( $object_id, $cmb_id, $updated, $cmb ) {
        
        $api_id = str_replace( 'wpgetapi_', '', $_GET['page'] );

        if( isset( $cmb->data_to_save['endpoints'] ) ) {
            foreach ( $cmb->data_to_save['endpoints'] as $key => $endpoint ) {

                $skip = false;

                if( isset( $endpoint['import_api'] ) &&  $endpoint['import_api'] == 'yes' ) {

                    if ( strpos( $endpoint['endpoint'], '(importer:item_key)' ) !== false )
                        $skip = true;

                    if ( isset( $endpoint['query_parameters'] ) && is_array( $endpoint['query_parameters'] ) ) {
                        foreach ( $endpoint['query_parameters'] as $key => $value ) {
                            if ( strpos( $value['value'], '(importer:item_key)' ) !== false )
                                $skip = true;
                        }
                    }

                    if ( isset( $endpoint['header_parameters'] ) && is_array( $endpoint['header_parameters'] ) ) {
                        foreach ( $endpoint['header_parameters'] as $key => $value ) {
                            if ( strpos( $value['value'], '(importer:item_key)' ) !== false )
                                $skip = true;
                        }
                    }

                    if ( isset( $endpoint['body_parameters'] ) && is_array( $endpoint['body_parameters'] ) ) {
                        foreach ( $endpoint['body_parameters'] as $key => $value ) {
                            if ( strpos( $value['value'], '(importer:item_key)' ) !== false )
                                $skip = true;
                        }
                    }

                    
                    if( ! $skip ) {
                        $this->save_initial_data( $api_id, $endpoint['id'], $endpoint['results_format'] );
                    } else {
                        $this->theid = $api_id . '_' . $endpoint['id'];
                        update_option( 'wpgetapi_importer_' . $this->theid . '_initial', 'item_key', 'no' );
                    }
                    
                }
            }
        }
    }
    
    /**
     * Save the initial data so we can use this for mapping.
     * @since  1.0.0
     */
    public function save_initial_data( $api_id, $endpoint_id, $results_format ) {

        $this->theid = $api_id . '_' . $endpoint_id;

        // force php array format if set to JSON string
        $args['results_format'] = $results_format == 'json_string' ? 'json_decoded' : $results_format;
        
        $api_data = wpgetapi_endpoint( $api_id, $endpoint_id, $args );

        if( empty( $api_data ) || ! is_array( $api_data ) )
            return;

        update_option( 'wpgetapi_importer_' . $this->theid . '_initial', $api_data, 'no' );

    }


    /**
     * Schedule our cron job.
     * @since  1.0.0
     */
    public function schedule_import_cron() {

        // when saving settings
        if( isset( $_POST['object_id'] ) && strpos( $_POST['object_id'], 'wpgetapi_importer_' ) !== false ) {
            
            // set our id
            //$theid  = str_replace( 'wpgetapi_importer_', '',  $_POST['object_id'] );
            $args   = array_values( array( 
                'api_id' => sanitize_text_field( $_POST['api_id'] ),
                'endpoint_id' => sanitize_text_field( $_POST['endpoint_id'] ),
            ) );

            if( isset( $_POST['import_interval'] ) && $_POST['import_interval'] != '' ) {

                if( ! $this->import_interval )
                    wp_clear_scheduled_hook( 'wpgetapi_auto_import', $args );

                wp_clear_scheduled_hook( 'wpgetapi_auto_import', $args );
                wp_schedule_event( time(), sanitize_text_field( $_POST['import_interval'] ), 'wpgetapi_auto_import', $args );

                // do it
                if ( ! wp_next_scheduled( 'wpgetapi_auto_import', $args ) )
                    wp_schedule_event( time(), $this->import_interval, 'wpgetapi_auto_import', $args );

            } else {

                wp_clear_scheduled_hook( 'wpgetapi_auto_import', $args );

            }

        }
        
    }


    /**
     * Run our cron job.
     * @since  1.0.0
     */
    public function import_cron_function( $api_id, $endpoint_id ) {
        
        if( ! $api_id || ! $endpoint_id )
            return;

        $this->theid = $api_id . '_' . $endpoint_id;
        $this->api_id = $api_id;
        $this->endpoint_id = $endpoint_id;
        $this->init_settings();

        $this->import_manually( 'Automatic' );
        $this->create_posts_manually();
    }


    /**
     * Import items manually - when button is hit.
     * @since 1.0.0
     */
    public function import_manually( $type = 'Manual' ) {
        
        if( ! isset( $this->setup_opts['apis'] ) )
            return;

        if( ! $type )
            $type = 'Manual';
        
        // if we are doing cron
        if( ! $this->theid ) 
            return;

        // set up our log data
        $this->update_log(
            'importer',
            array( 
                'started' => current_time( 'mysql' ),
                'type' => $type,
            ) 
        );

        // make sure we have our linked api
        // and run the endpoint for each item within the linked endpoint
        if( $this->saved_settings['api_type'] == 'detail' ) {

            $linked_endpoint    = get_option('wpgetapi_importer_' . $this->saved_settings['linked_endpoint'] );
            $this->item_key     = $linked_endpoint['item_key'];
            $linked_items       = $this->get_saved_items( $this->saved_settings['linked_endpoint'] );
            
            $ids = wp_list_pluck( $linked_items, $this->item_key ); 

            // reset our counter
            delete_option( 'wpgetapi_' . $this->theid . '_items_count' );

            $all_data = array();
            foreach ( $ids as $i => $id ) {
                $all_data[] = $this->run_importer_multiple( $id );
            }

            // save our items
            $this->save_items( $all_data );

            // update our log data
            $this->update_log( 'importer', array( 
                'completed' => current_time( 'mysql' ),
                'items' => count( $all_data ),
            ) );

            $this->set_admin_notice( __( 'The importer has successfully completed.', 'wpgetapi-api-to-posts' ) );

        } else {

            //$this->root_key = isset( $this->saved_settings[ $this->theid ][0]['root_key'] ) ? $this->saved_settings[ $this->theid ][0]['root_key'] : '';

            $this->run_importer();

        }

        if( $type == 'Manual' ) {
            wp_redirect( admin_url( '/admin.php?page=wpgetapi_importer_' . $this->theid ) );
            exit;
        }

    }


    /**
     * Run the importer for multiple endpoints.
     * @since 1.0.0
     */
    public function run_importer_multiple( $item_key ) {

        $this->args['item_key'] = $item_key;

        // get our data from the API
        $data = wpgetapi_endpoint( $this->api_id, $this->endpoint_id, $this->args );

        if( ! $data || ! is_array( $data ) )
            return '';

        $items = $data;

        // maybe step down into array, if root_key exists
        $items = $this->maybe_step_down( $items, $data );

        // if we don't have the item key in the detail data
        // we need to add it
        if( ! isset( $items[ $this->item_key ] ) )
            $items[ $this->item_key ] = $item_key;

        return $items;

    }



    /**
     * Run the importer.
     * @since 1.0.0
     */
    public function run_importer() {

        // get our data from the API
        $data = wpgetapi_endpoint( $this->api_id, $this->endpoint_id, $this->args );

        if( ! $data || ! is_array( $data ) )
            return '';

        $items = $data;

        // maybe step down into array, if root_key exists
        $items = $this->maybe_step_down( $items, $data );

        $items = apply_filters( 'wpgetapi_api_to_posts_importer_items_before_save', $items );

        // reset our counter
        delete_option( 'wpgetapi_' . $this->theid . '_items_count' );

        // if we have pagination, do it
        if( $next = $this->has_pagination( $data ) ) {

            // save our items
            $this->save_items( $items );

            $this->do_pagination( $data, $items, $next );
            
        // no pagination, finish it
        } else {

            // save our items
            $this->save_items( $items );

            // update our log data
            $this->update_log( 'importer', array( 
                'completed' => current_time( 'mysql' ),
                'items' => count( $items ),
            ) );

            $this->set_admin_notice( __( 'The importer has successfully completed.', 'wpgetapi-api-to-posts' ) );

        }

    }


    /**
     * do we have pagination.
     * returns the value of our pagination field, if it exists.
     *
     */
    public function has_pagination( $data ) {

        if( isset( $data['links']['next'] ) && ! empty( $data['links']['next'] ) )
            return $data['links']['next'];

        if( isset( $data['next'] ) && ! empty( $data['next'] ) )
            return $data['next'];

        // custom solutions - Gil
        if( isset( $data['Titles']['PageCount'] ) && $data['Titles']['Page'] < $data['Titles']['PageCount'] )
            return $data['Titles']['Page'] + 1;
        if( isset( $data['PageCount'] ) && $data['Page'] < $data['PageCount'] )
            return $data['Page'] + 1;

        // TMDB
        if( ( isset( $data['page'] ) && isset( $data['total_pages'] ) ) && $data['page'] < $data['total_pages'] )
            return $data['page'] + 1;

        // trendz nz
        if( ( isset( $data['page_current'] ) && isset( $data['page_count'] ) ) && $data['page_current'] < $data['page_count'] )
            return $data['page_current'] + 1;

        // spark
        if( ( isset( $data['D']['Pagination'] ) && isset( $data['D']['Pagination']['TotalPages'] ) ) && $data['D']['Pagination']['CurrentPage'] < $data['D']['Pagination']['TotalPages'] )
            return $data['D']['Pagination']['CurrentPage'] + 1;

        // growthhub
        if( ( isset( $data['stats']['maxPage'] ) && isset( $data['stats']['page'] ) ) && $data['stats']['page'] < $data['stats']['maxPage'] )
            return $data['stats']['page'] + 1;

        return false;

    }




    /**
     * get pagination type.
     * could be url, cursor, page, or offset
     */
    public function get_pagination_type( $data ) {

        // set page as default
        $type = 'page';
        $string = null;

        if( isset( $data['links']['next'] ) && $data['links']['next'] )
            $string = $data['links']['next'];

        if( isset( $data['next'] ) && $data['next'] ) 
            $string = $data['next'];
        
        if ( $string && preg_match( "/\bhttps?:\/\/\S+\b/", $string ) )
            $type = 'url';

        // trendz nz
        if( ( isset( $data['page_current'] ) && isset( $data['page_count'] ) ) )
           $type = 'page_current';

        // spark
        if( isset( $data['D']['Pagination'] ) )
           $type = 'Pagination';

        // growthhub
        if( isset( $data['stats']['maxPage'] ) )
           $type = 'maxPage';

        return $type;

    }
    

    /**
     * do the pagination.
     *
     */
    public function do_pagination( $data, $items, $next ) {
        
        $type = $this->get_pagination_type( $data );

        // if type is page, meaning that we are using the page number as a query_var
        if( $type == 'page' || $type == 'maxPage' ) {
            $this->args['query_variables'] = 'page=' . $next;
        }

        // if type is page_current, meaning that we are using the page_current number as a query_var
        // trendz nz
        if( $type == 'page_current' )
            $this->args['query_variables'] = 'page_no=' . $next;


        // if type is Pagination, meaning that we are using the Pagination number as a query_var
        // spark
        if( $type == 'Pagination' )
            $this->args['query_variables'] = '_page=' . $next;


        // if set to url, we want to change our url
        if( $type == 'url' )
            $this->args['paginate_url'] = $next;
        
        sleep( apply_filters( 'wpgetapi_api_to_posts_pagination_delay', 0.2 ) );

        // get our data from the API
        $data = wpgetapi_endpoint( $this->api_id, $this->endpoint_id, $this->args );

        if( ! $data || ! is_array( $data ) )
            return '';

        $items = $data;

        // maybe step down into array, if root_key exists
        $items = $this->maybe_step_down( $items, $data );

        // if we still have pagination, do it
        if( $next = $this->has_pagination( $data ) ) {

            // save more of the items
            $this->save_items( $items );

            $this->do_pagination( $data, $items, $next );

        // no more pagination, finish it    
        } else {

            // save the final lot of items
            $this->save_items( $items );

            $items = $this->get_saved_items();

            // update our log data
            $this->update_log( 'importer', array( 
                'completed' => current_time( 'mysql' ),
                'items' => count( $items ),
            ) );

            $this->set_admin_notice( __( 'The importer has successfully been run.', 'wpgetapi-api-to-posts' ) );

        }

    }


    /**
     * Create posts manually - when button is hit.
     * @since 1.0.0
     */
    public function create_posts_manually() {

        if( ! $this->theid )
            return;

        $this->update_log( 'post_creator', 
            array( 
                'started' => current_time( 'mysql' ),
            ) 
        );

       // $this->api_data = $this->get_saved_items();

        // do some checks
        if( empty( $this->api_data ) ) {
            
            $this->set_admin_notice( 'No items were found. Nothing was updated' );

        // add our posts
        } else {

            // check, create or delete posts
            $this->maybe_add_delete_posts( $this->api_data );

            $this->set_admin_notice( __( 'Creating posts in the background.<br>Reload the page to see progress.', 'wpgetapi-post-import' ) );

        }

        $this->update_log( 'post_creator', array( 'completed' => current_time( 'mysql' ) ) ); 

        wp_redirect( admin_url( '/admin.php?page=wpgetapi_importer_' . $this->theid ) );
        exit;

    }
    

    

    /**
     * Maybe add, delete or update posts.
     * @since 1.0.0
     */
    public function maybe_add_delete_posts( $items ) {

        $deleted = array();

        // get our data from linked endpoint
        $endpoint_settings  = get_option( 'wpgetapi_importer_' . $this->theid );
        $linked_endpoint_id = $endpoint_settings['linked_endpoint'];
        if( $linked_endpoint_id ) {
            $linked_endpoint    = get_option( 'wpgetapi_importer_' . $linked_endpoint_id );
            $this->post_type    = $linked_endpoint['item_post_type'];
            $this->item_key     = $linked_endpoint['item_key'];
        }

        // get our saved posts (if any yet)
        $args = array(
            'post_type' => $this->post_type,
            'post_status' => array( 'publish', 'draft', 'pending' ),
            'posts_per_page' => -1,
            'fields' => 'ids',
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'meta_key' => $this->item_key // only get posts created with the API
        );
        $posts = get_posts( $args );

        // loop through posts and get the item_key
        $post_refs = array();
        if( ! empty( $posts ) ) {
            foreach( $posts as $i => $post_id ) {
                $post_refs[] = get_post_meta( $post_id, $this->item_key, true );
            } 
        }
        
        // loop through the latest API items and get the id/item_key 
        $api_refs = array();
        if( $items ) {
            foreach( $items as $i => $api_item ) {
                $api_refs[] = isset( $api_item[ $this->item_key ] ) ? $api_item[ $this->item_key ] : null; 
            }
            $api_refs = array_values( $api_refs );
        }

        // delete needs to be first
        // get the posts that are EXPIRED and DELETE them
        if( $posts && $this->sync_posts == 'full_sync' ) {
            $deleted = $this->delete_posts( $post_refs, $api_refs, $posts );
        }

        // create posts
        if( $this->sync_posts != 'update_only' )
            $created = $this->create_posts( $api_refs, $post_refs, $items );

        // update posts
        if( $posts && $this->sync_posts !== 'no_delete_no_update'  ) {
            $updated = $this->update_posts( $api_refs, $post_refs, $items, $posts );
        }
        
        // update log
        $this->update_log( 'post_creator', 
            array( 
                'started' => current_time( 'mysql' ),
                'type' => 'Manual',
                'deleted' => count( $deleted ),
                'created' => count( $created ),
                'updated' => count( $updated ),
            ) 
        );

    }


    public function create_posts( $api_refs, $post_refs, $items, $process_id = null ) {

        // get array of reference ID's that need to be added
        $new = array_diff( $api_refs, $post_refs );
        
        // get our count of ids
        $ids = array();

        // if no new posts, bail
        if( empty( $new ) )
            return $ids;

        foreach( $items as $i => $api_item ) {
            
            // make sure it is a new one
            $ref = isset( $api_item[ $this->item_key ] ) ? $api_item[ $this->item_key ] : null; 

            // // get our saved posts (if any yet)
            $args = array(
                'post_type' => $this->post_type,
                //'post_status' => array( 'publish', 'draft', 'pending' ),
                'posts_per_page' => 1,
                'fields' => 'ids',
                'ignore_sticky_posts' => true,
                'no_found_rows' => true,
                'meta_value' => $ref,
                'meta_key' => $this->item_key // only get posts created with the API
            );
            $posts = get_posts( $args );

            if ( count( $posts ) > 0 )
                continue;

            $post = array(
                'post_type'     => $this->post_type,
                'post_status'   => $this->post_status, 
                'post_title'    => $this->create_post_name( $api_item ),
                'post_author'   => $this->post_author,
                'post_content'  => '',
                'post_excerpt'  => '',
            );

            // map all our data to relevant fields, categories, taxonomy, attributes
            $post = $this->map_fields_and_add_data( $post, $api_item );

            // force our itemkey
            $post['meta'][ $this->item_key ] = $api_item[ $this->item_key ];

            if( $this->post_type == 'product' ) {
                $ids[] = $this->insert_or_update_product( $post, $process_id );
            } else {
                $ids[] = $this->insert_or_update_post( $post, $process_id );
            }

        }

        return $ids;

    }

    /**
     * Maybe update posts.
     * @since 1.0.0
     */
    public function update_posts( $api_refs, $post_refs, $items, $posts ) {

        // get array of reference ID's that need to be updated
        $existing = array_intersect( $api_refs, $post_refs );

        // get our count of ids
        $ids = array();

        if( empty( $existing ) )
            return $ids;

        foreach( $posts as $i => $post_id ) {

            $ref = get_post_meta( $post_id, $this->item_key, true );

            if( ! in_array( $ref, $existing ) )
                continue;

            $api_item_id = array_search( $ref, array_column( $items, $this->item_key ) );
            $api_item = $items[ $api_item_id ];

            if( $this->post_type == 'product' ) {

                $post = array(
                    'id'            => $post_id,
                    'type'          => 'simple',
                    'status'        => $this->post_status, 
                    'post_title'    => $this->create_post_name( $api_item ),
                    'post_excerpt'  => '',
                    'post_author'   => $this->post_author,
                    'post_content'  => '',
                );

            } else {

                 $post = array(
                    'ID'            => $post_id,
                    'post_type'     => $this->post_type,
                    'post_status'   => $this->post_status, 
                    'post_title'    => $this->create_post_name( $api_item ),
                    'post_excerpt'  => '',
                    'post_author'   => $this->post_author,
                    'post_content'  => '',
                );

            }
           
            $post = $this->map_fields_and_add_data( $post, $api_item );

            // force our itemkey
            $post['meta'][ $this->item_key ] = $api_item[ $this->item_key ];

            if( $this->post_type == 'product' ) {
                $ids[] = $this->insert_or_update_product( $post );
            } else {
                $ids[] = $this->insert_or_update_post( $post );
            }

        }

        return $ids;

    }


    /**
     * create posts via AJAX.
     * @since 1.0.0
     */
    public function run_post_creator_ajax() {
        
        $this->theid        = sanitize_text_field( $_POST['theid'] );
        $this->api_id       = sanitize_text_field( $_POST['api_id'] );
        $this->endpoint_id  = sanitize_text_field( $_POST['endpoint_id'] );
        $num                = sanitize_text_field( $_POST['num'] );
        $process_id         = sanitize_text_field( $_POST['process_id'] );

        $time       = current_time( 'mysql' );
        $count      = get_option( 'wpgetapi_' . $this->theid . '_items_count' );
        $next       = ( $num < $count ) ? $num + 1 : '';
        $percent    = $num / $count * 100;
        $started    = false;

        if( $num == 1 ) {
            $this->update_log( 'post_creator', array( 'started' => current_time( 'mysql' ), 'type' => 'Manual' ) );
            $started = $time;
        }

        if( $num != '' ) {
            $this->api_data = $this->get_saved_items_ajax( $num );
        } else {
            $this->api_data = array();
        }


        // do some checks
        if( empty( $this->api_data ) ) {
            
            echo json_encode(['error'=> 'No items were found. Nothing was updated', 'progress' => 0, 'next' => '']);
            die();
            
        // add our posts
        } else {

            $fullData = $this->get_saved_items();

            // check, create or delete posts
            $results = $this->maybe_add_delete_posts_ajax( $this->api_data, $process_id, $fullData );
            
            echo json_encode( 
                array(
                    'progress' => number_format($percent, 2), 
                    'next' => $next,
                    'started' => $started,
                    'deleted' => $results['deleted'],
                    'updated' => $results['updated'],
                    'created' => $results['created'],
                    'completed' => $time,
                )
            );

        }

        $this->update_log( 'post_creator', array( 'completed' => $time ) );

        exit;

    }


    /**
     * Maybe add, delete or update posts via AJAX.
     * @since 1.0.0
     */
    public function maybe_add_delete_posts_ajax( $items, $process_id, $fullData ) {
                        
        $creator_log = get_option( 'wpgetapi_importer_' . $this->theid . '_post_creator_log' );

        $deletedCurrent = $createdCurrent = $updatedCurrent = 0;
        if(isset($creator_log['process_id']) && $creator_log['process_id'] == $process_id){
            $deletedCurrent = $creator_log['deleted'];
            $createdCurrent = $creator_log['created'];
            $updatedCurrent = $creator_log['updated'];
        }
        
        // get our data from linked endpoint
        $endpoint_settings  = get_option( 'wpgetapi_importer_' . $this->theid );
        $linked_endpoint_id = $endpoint_settings['linked_endpoint'];
        if( $linked_endpoint_id ) {
            $linked_endpoint    = get_option( 'wpgetapi_importer_' . $linked_endpoint_id );
            $this->post_type    = $linked_endpoint['item_post_type'];
            $this->item_key     = $linked_endpoint['item_key'];
        }

        $deleted = array();

        // get our saved posts (if any yet)
        $args = array(
            'post_type' => $this->post_type,
            'post_status' => array( 'publish', 'draft', 'pending' ),
            'posts_per_page' => -1,
            'fields' => 'ids',
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'meta_query' => array(
                array(
                    'key' => $this->item_key, // only get posts created with the API
                ),
                // array(
                //     'key' => 'importer_process_id',
                //     'value' => $process_id,
                //     'compare' => '!='
                // )
            )
        );

        $posts = get_posts( $args );

        // loop through posts and get the item_key
        $post_refs = array();
        if( ! empty( $posts ) ) {
            foreach( $posts as $i => $post_id ) {
                $post_refs[] = get_post_meta( $post_id, $this->item_key, true );
            } 
        }
        
        // loop through the latest API items and get the id/item_key 
        $api_refs = array();
        if( $items ) {
            foreach( $items as $i => $api_item ) {
                $api_refs[] = isset( $api_item[ $this->item_key ] ) ? $api_item[ $this->item_key ] : null; 
            }
            $api_refs = array_values( $api_refs );
        }
        
        $all_api_refs = array();
        if( $fullData ) {
            foreach( $fullData as $i => $api_item ) {
                $all_api_refs[] = isset( $api_item[ $this->item_key ] ) ? $api_item[ $this->item_key ] : null; 
            }
            $all_api_refs = array_values( $all_api_refs );
        }

        // delete needs to be first
        // get the posts that are EXPIRED and DELETE them
        $deleted = array();
        if(!isset($creator_log['process_id']) || $creator_log['process_id'] != $process_id ){
            if( $posts && $this->sync_posts == 'full_sync' ) {
                $deleted = $this->delete_posts( $post_refs, $all_api_refs, $posts );
            }
        }
        
        // create posts
        $created = array();
        if( $this->sync_posts !== 'update_only' )
            $created = $this->create_posts( $api_refs, $post_refs, $items, $process_id );

        
        // update posts
        $updated = array();
        if( $posts && $this->sync_posts !== 'no_delete_no_update'  ) {
            $updated = $this->update_posts( $api_refs, $post_refs, $items, $posts );
        }
        
        // update log
        $this->update_log( 'post_creator', 
            array( 
                'deleted' => $deletedCurrent + count( $deleted ),
                'created' => $createdCurrent + count( $created ),
                'updated' => $updatedCurrent + count( $updated ),
                'process_id' => $process_id
            ) 
        );

        return array(
            'deleted' => $deletedCurrent + count( $deleted ),
            'created' => $createdCurrent + count( $created ),
            'updated' => $updatedCurrent + count( $updated )
        );

    }



    /**
     * @since 1.0.0
     */
    public function insert_or_update_post( $post, $process_id = null ) {

        if( $post['post_title'] == '' )
            unset($post['post_title']);
        if( $post['post_excerpt'] == '' )
            unset($post['post_excerpt']);
        if( $post['post_content'] == '' )
            unset($post['post_content']);

        if( isset( $post['ID'] ) ) {
            $post_id = wp_update_post( $post, true );
        } else {
            $post_id = wp_insert_post( $post );
        }

        $this->update_meta_data( $post_id, $post );
        $this->update_taxonomies( $post_id, $post );
        $this->update_image( $post_id, $post );

        if( $process_id !== null )
            update_post_meta( $post_id, 'importer_process_id', $process_id );

        return $post_id;

    }
   

    /**
     * update image
     */
    public function update_image( $post_id, $post ) {

        if( ! isset( $post['image_id'] ) )
            return;

        set_post_thumbnail( $post_id, $post['image_id'] );

    }

    /**
     * update meta data
     */
    public function update_meta_data( $post_id, $post ) {

        if( ! isset( $post['meta'] ) )
            return;

        // loop through each field within the listing
        foreach ( $post as $key => $value ) {
            if( $key == 'meta' ) {
                foreach ( $value as $meta_key => $meta_value ) {
                    update_post_meta( $post_id, $meta_key, $meta_value );
                }
            }
        }

    }


    /**
     * update meta data
     */
    public function update_taxonomies( $post_id, $post ) {

        if( ! isset( $post['_do_taxonomies'] ) )
            return;
        
        $inserted = array();

        foreach ( $post['_do_taxonomies'] as $key => $data ) {
            // does absolute top level parent terms  
            if( isset( $data['parent_key'] ) ) {
                $post['_do_taxonomies'][ $data['parent_key'] ]['child'][] = $data;
                unset( $post['_do_taxonomies'][ $key ] );
            }
        }

        $all_terms = array();
        foreach ( $post['_do_taxonomies'] as $key => $data ) {

            wp_insert_term(
                $data['term'],
                $data['taxonomy'],
                array( 'slug' => $data['slug'] )
            );

            $parent_term = term_exists( $data['term'], $data['taxonomy'] );
            $parent_term_id = $parent_term['term_id'];
            $all_terms[ $data['taxonomy'] ][] = $parent_term_id;

            if( isset( $data['child'] ) ) {
                foreach ( $data['child'] as $key => $child ) {

                    $parent_term = term_exists( $child['parent_key'], $child['taxonomy'] ); // check parent term exist
            		$parent_term_id = $parent_term['term_id']; // set parent term_id

                    wp_insert_term(
                        $child['term'],
                        $child['taxonomy'],
                        array(
                            'slug' => $child['slug'],
                            'parent'=> $parent_term_id
                        )
                    );

                    $child_term = term_exists( $child['term'], $child['taxonomy'] );
                    $child_term_id = $child_term['term_id'];
                    $all_terms[ $child['taxonomy'] ][] = $child_term_id;
                }
            }
            

        }
        
        foreach ( $all_terms as $taxonomy => $terms ) {
            foreach ( $terms as $i => $term_id ) {

                $tax_details = get_taxonomy( $taxonomy );

                if( $tax_details->hierarchical != true ) {
                    $term_details = get_term_by( 'id', $term_id, $taxonomy );
                    $terms[] = $term_details->name;
                    unset( $terms[ $i ] );
                }
                
            }
            wp_set_post_terms( $post_id, $terms, $taxonomy );
        }
        
    }



    /**
     * Work out how our fields are mapped.
     * @since 1.0.0
     */
    public function map_fields_and_add_data( $post, $api_item ) {

        // if no opts set (everything set to dont import)
        if( ! $this->mapping_opts || ! is_array( $this->mapping_opts ) )
            return;

        // sort the array which will put all _category fields before _subcategory fields
        // allowing us to create the parent categories first
        asort( $this->mapping_opts );

        // set up empty taxs array
        $taxs = array();

        if( $this->flatten == 'yes' )
            $api_item = $this->array_flatten( $api_item );

        // Filter before mapping
        $post = apply_filters( 'wpgetapi_api_to_posts_before_map_fields_data', $post, $api_item, $this );

        $api_item = apply_filters( 'wpgetapi_api_to_posts_filter_item_data', $api_item, $this );

        // now work out where each field goes to ie attribute, meta etc
        foreach ( $this->mapping_opts as $map_key => $fields ) {
            
            foreach( $fields as $i => $field ) {

                // assign our value
                $our_value = $api_item[ $map_key ];

                // should be array, means we have custom meta field
                // or we have images field
                if( is_array( $field ) ) {
                    
                    // save for use later
                    $tmpField = $field;
                    $field = $field['name'];

                    // are we stepping down to get a nested value
                    if( isset( $tmpField['step_down'] ) && $tmpField['step_down'] !== '' ) {
                        $our_value = $this->nested_data( $api_item[ $map_key ], $tmpField['step_down'] );
                    } 

                }

                $our_value = is_string( $our_value ) ? trim( $our_value ) : $our_value;

                $our_value = apply_filters( 'wpgetapi_api_to_posts_mapped_value', $our_value );

                if ( $field == '_post_title' )
                    $post['post_title'] = $our_value;

                if ( $field == '_post_content' )
                    $post['post_content'] = $our_value;

                if ( $field == '_post_excerpt' )
                    $post['post_excerpt'] = $our_value;

                if ( $field == '_post_slug' )
                    $post['post_name'] = $our_value;

                if ( $field == '_post_date' )
                    $post['post_date'] = $our_value;

                if ( $field == '_post_date_gmt' )
                    $post['post_date_gmt'] = $our_value;

                if ( $field === '_meta' ) {

                    // replace our temp field if found
                    // used in filter
                    $map_key = str_replace( 'wpgetapi_cf_', '', $map_key );
                    
                    if( is_array( $our_value ) )
                        $our_value = json_encode( $our_value );
                    
                    // set the map_key to custom key name
                    if( $tmpField['value'] !== '' ) {
                        $custom_map_key = $tmpField['value'];
                        $post['meta'][ $custom_map_key ] = $our_value;
                    } else {
                        $post['meta'][ $map_key ] = $our_value;
                    }

                } 

                if ( $field === '_meta_array' ) {

                    // slightly silly way to do this - TODO
                    if( is_array( $our_value ) ) {

                        foreach ( $our_value as $key => $value ) :

                            if( is_array( $value ) ) {

                                foreach ( $value as $sub_key => $sub_value ) :
                                    
                                    $post['meta'][ $map_key . '_' . $key . '_' . $sub_key ] = $sub_value;

                                endforeach;

                            } else {

                                $post['meta'][ $map_key . '_' . $key ] = $value;

                            }

                        endforeach;
                        
                    } else {

                        $post['meta'][ $map_key ] = $our_value;

                    }

                } 

                // woocommerce field
                // FOR DEFAULT ONES. If key is not recognised they are simply ignored
                //$product[ $field ] = $our_value;

                // FOR DEFAULT ONES. If key is not recognised they are simply ignored
                $post[ $field ] = $our_value;

                // get parent taxonomy
                if ( strpos( $field, '_tax_' ) !== false ) {

                    $key        = (string) $map_key . '_' . $i;
                    $term       = (string) $our_value;
                    $slug       = sanitize_title_with_dashes( $term );
                    $tax        = str_replace( '_tax_', '', $field );

                    $taxs[$key] = array( 
                        'key' => $key,
                        'term' => $term,
                        'slug' => $slug,
                        'taxonomy' => $tax,
                    );

                }

                // get sub taxonomy
                if ( strpos( $field, '_taxsub_' ) !== false ) {

                    $key        = (string) $map_key . '_' . $i;
                    $term       = (string) $our_value;
                    $slug       = sanitize_title_with_dashes( $term );
                    $first      = substr( $field, 0, strpos( $field, "-") );
                    $parent_key = substr( $field, strpos( $field, "-" ) + 1 );
                    $tax        = str_replace( '_taxsub_', '', $first );
                    $parent_term = (string) $api_item[ $parent_key ];

                    $taxs[$key] = array(
                        'key' => $key,
                        'term' => $term,
                        'slug' => $slug,
                        'taxonomy' => $tax,
                        'parent_key' => $parent_key,
                        'parent_term' => $parent_term,
                    );

                }


                if ( $field === '_featured_image' ) {
                    
                    // set the image prefix if there is one
                    $prefix = '';
                    if( $tmpField['prefix'] !== '' ) {
                        $prefix = $tmpField['prefix'];
                    }

                    // if not an array, set as featured
                    if( ! is_array( $our_value ) ) {

                        $image_id = $this->add_image( $prefix . $our_value, $post );
                        if( $image_id )
                            $post['image_id'] = $image_id;

                    } else {

                        foreach ( $our_value as $i => $image_url ) {
                            // set first as featured
                            if( $i == 0 ) {

                                $image_id = $this->add_image( $prefix . $image_url, $post );
                                if( $image_id )
                                    $post['image_id'] = $image_id;
                            } 
                        }

                        // if no image id, go searching for it
                        if( ! $image_id ) {


                                //$flattened = $this->array_flatten( $our_value );

                                //if( is_array( $flattened ) ) {

                                    $imgExts = array( "gif", "jpg", "jpeg", "png", "tiff", "tif", "webp" );

                                    $found = 0;
                                    foreach ( $our_value as $key => $value ) {
                                        
                                        $urlExt = pathinfo( $value, PATHINFO_EXTENSION );

                                        if ( in_array( $urlExt, $imgExts ) ) {
                                            
                                            $image_id = $this->add_image( $prefix . $value );

                                            // set first as featured
                                            if( $found == 0 ) {

                                                if( $image_id )
                                                    $post['image_id'] = $image_id;

                                                $found = 1;

                                            } 

                                            // else {

                                            //     if( $i > $this->max_images )
                                            //         continue;
                                            //     $image_id = $this->add_image( $prefix . $value );
                                            //     if( $image_id )
                                            //         $post['gallery_ids'][] = $image_id;

                                            // } 

                                        }

                                    }

                                //}


                        }

                    }

                } 


                // woocommerce field
                if ( $field === '_category' ) {

                    $term_id    = null;
                    $term       = (string) $our_value;
                    $slug       = sanitize_file_name( strtolower( $term ) );

                    // add our term
                    $result = wp_insert_term(
                        $term, // the term/category
                        'product_cat', // the taxonomy
                        array( 'slug' => $slug )
                    );

                    // if term exists we can still get the ID
                    if( is_wp_error( $result ) ) {
                        $term_id = isset( $result->error_data['term_exists'] ) ? $result->error_data['term_exists'] : null;
                    } else {
                        if( isset( $result['term_id'] ) )
                            $term_id = $result['term_id'];
                    }

                    if( $term_id ) {
                        $cats[$map_key] = array( 
                            'parent_key' => false,
                            'key' => $map_key,
                            'term' => $our_value,
                            'slug' => $slug,
                            'id' => $term_id,
                            'level' => 1,
                        ); 
                    }
                   
                }

                // woocommerce field
                if ( strpos( $field, '_subcategory_' ) !== false ) {

                    $term_id    = null;
                    $term       = $our_value;
                    $slug       = sanitize_file_name( strtolower( $term ) );
                    $parent_key = str_replace( '_subcategory_', '', $field );

                    $cats[$map_key] = array( 
                        'parent_key' => $parent_key,
                        'key' => $map_key,
                        'term' => $our_value,
                        'slug' => $slug,
                        'level' => null,
                    );

                }

                // woocommerce field
                if ( $field === '_all_images' ) {
                    
                    // set the image prefix if there is one
                    $prefix = '';
                    if( $tmpField['prefix'] !== '' ) {
                        $prefix = $tmpField['prefix'];
                    }

                    // if not an array, try to create one
                    if( ! is_array( $our_value ) ) {

                        // if we have comma seperated URL's, create an array
                        if ( strpos( $our_value, ',' ) !== false ) {
                            $array = explode( ',', $our_value );
                            if( is_array( $array ) )
                                $our_value = $array;
                        }

                        // if we have space seperated URL's, create an array
                        if ( strpos( $our_value, ' ' ) !== false ) {
                            $array = explode( ' ', $our_value );
                            if( is_array( $array ) )
                                $our_value = $array;
                        }

                        // if we have newline seperated URL's, create an array
                        if ( strpos( $our_value, "\n" ) !== false ) {
                            $array = explode( "\n", $our_value );
                            if( is_array( $array ) )
                                $our_value = $array;
                        }

                    } 


                    // if not an array, set as featured
                    if( ! is_array( $our_value ) ) {

                        $image_id = $this->add_image( $prefix . $our_value, $post );
                        if( $image_id )
                            $post['image_id'] = $image_id;

                    } else {

                        foreach ( $our_value as $i => $image_url ) {
                            
                            if( is_string( $image_url ) ) {

                                // set first as featured
                                if( $i == 0 ) {

                                    $image_id = $this->add_image( $prefix . $image_url, $post );
                                    if( $image_id )
                                        $post['image_id'] = $image_id;

                                } else {

                                    if( $i > $this->max_images )
                                        continue;
                                    $image_id = $this->add_image( $prefix . $image_url );
                                    if( $image_id )
                                        $post['gallery_ids'][] = $image_id;

                                } 
                                

                            } else if( is_array( $image_url ) ) {

                                $flattened = $this->array_flatten( $image_url );

                                if( is_array( $flattened ) ) {

                                    $imgExts = array( "gif", "jpg", "jpeg", "png", "tiff", "tif", "webp" );

                                    $found = 0;
                                    foreach ( $flattened as $key => $value ) {
                                        
                                        $urlExt = pathinfo( $value, PATHINFO_EXTENSION );

                                        if ( in_array( $urlExt, $imgExts ) ) {
                                            
                                            $image_id = $this->add_image( $prefix . $value );

                                            // set first as featured
                                            if( $found == 0 ) {

                                                if( $image_id )
                                                    $post['image_id'] = $image_id;

                                                $found = 1;

                                            } else {

                                                if( $i > $this->max_images )
                                                    continue;
                                                $image_id = $this->add_image( $prefix . $value );
                                                if( $image_id )
                                                    $post['gallery_ids'][] = $image_id;

                                            } 

                                        }

                                    }

                                }

                            }
                            

                        }

                    }

                }
                


                // woocommerce field
                if ( $field === '_gallery_images' ) {
                    
                    // set the image prefix if there is one
                    $prefix = '';
                    if( $tmpField['prefix'] !== '' ) {
                        $prefix = $tmpField['prefix'];
                    }

                    // if value is not an array, try to create one
                    // this may be due to comma seperated urls or space seperated
                    if( ! is_array( $our_value ) ) {

                        // if we have comma seperated URL's, create an array
                        if ( strpos( $our_value, ',' ) !== false ) {
                            $array = explode( ',', $our_value );
                            if( is_array( $array ) )
                                $our_value = $array;
                        }

                        // if we have space seperated URL's, create an array
                        if ( strpos( $our_value, ' ' ) !== false ) {
                            $array = explode( ' ', $our_value );
                            if( is_array( $array ) )
                                $our_value = $array;
                        }

                        // if we have newline seperated URL's, create an array
                        if ( strpos( $our_value, "\n" ) !== false ) {
                            $array = explode( "\n", $our_value );
                            if( is_array( $array ) )
                                $our_value = $array;
                        }

                    } 

                    // if we have an array of images
                    if( is_array( $our_value ) ) {

                        foreach ( $our_value as $i => $image_url ) {

                            if( $i > $this->max_images )
                                continue;

                            if( is_string( $image_url ) ) {

                                $image_id = $this->add_image( $prefix . $image_url );
                                if( $image_id )
                                    $post['gallery_ids'][] = $image_id;

                            } else if( is_array( $image_url ) ) {

                                $flattened = $this->array_flatten( $image_url );
                                if( is_array( $flattened ) ) {

                                    $imgExts = array( "gif", "jpg", "jpeg", "png", "tiff", "tif", "webp" );

                                    foreach ( $flattened as $key => $value ) {
                                        
                                        $urlExt = pathinfo( $value, PATHINFO_EXTENSION );
                                        if (in_array($urlExt, $imgExts)) {
                                            $image_id = $this->add_image( $prefix . $value );
                                            if( $image_id )
                                                $post['gallery_ids'][] = $image_id;
                                        }

                                    }
                                }

                            }
                            
                        }

                    // else assume a single image
                    } else {

                        $image_id = $this->add_image( $prefix . $our_value, $post );
                        if( $image_id )
                            $post['gallery_ids'][] = $image_id;

                    }

                }

                // woocommerce field
                if ( $field === '_attribute' ) {

                    // set the image prefix if there is one
                    $prefix = '';
                    if( $tmpField['att_name'] !== '' ) {
                        $att_name = $tmpField['att_name'];
                    } else {
                        $att_name = $this->_format_key_to_label( $map_key );
                    }

                    $att_slug   = '_' . sanitize_file_name( strtolower( $att_name ) );
                    $term_slug  = sanitize_file_name( strtolower( $our_value ) );
                    $term_name  = (string) $our_value;

                    // create our attribute
                    $attribute = $this->createAttribute( $att_name, $att_slug );
                    $term = $this->createTerm( $term_name, $term_slug, $att_slug );

                    if( $term ) {
                        $post['attributes'][ 'pa_' . $att_slug ] = array(
                            'term_names' => array( $term_name ),
                            'is_visible' => true,
                            'for_variation' => false,
                        );
                        $post['default_attributes'][ 'pa_' . $att_slug ] = array(
                            'term_names' => array( $term_name ),
                            'is_visible' => true,
                            'for_variation' => false,
                        );
                    }

                } 

                // woocommerce field
                if ( $field === '_tag' ) {
                    
                    $term_slug  = sanitize_file_name( strtolower( $our_value ) );
                    $term_name = (string) $our_value;

                    $result = wp_insert_term(
                        $term_name, // the term/tag
                        'product_tag', // the taxonomy
                        array( 'slug' => $term_slug )
                    );

                    // if term exists we can still get the ID
                    if( is_wp_error( $result ) ) {
                        $post['tag_ids'][] = isset( $result->error_data['term_exists'] ) ? $result->error_data['term_exists'] : null;
                    } else {
                        if( isset( $result['term_id'] ) )
                            $post['tag_ids'][] = $result['term_id'];
                    }

                }


                // woocommerce field
                if( ! empty( $cats ) ) {

                    $post['category_ids'] = array();

                    foreach ( $cats as $key => $cat ) {

                        // if we have parent key, we need to add our level
                        if( $cat['parent_key'] != '' ) {
                            $parent = $cats[ $cat['parent_key'] ];
                            $cats[ $key ]['level'] = $parent['level'] + 1;
                        } else {
                            // else add our parent cat ids to product
                            $post['category_ids'][] = $cat['id'];
                        }

                    }

                    // now add the children
                    foreach ( $cats as $key => $cat ) {

                        if( $cat['level'] > 1 ) {
                            
                            $parent = $cats[ $cat['parent_key'] ];
                            
                            $result = wp_insert_term(
                                $cat['term'], // the term/category
                                'product_cat', // the taxonomy
                                array( 'slug' => $cat['slug'], 'parent' => $parent['id'] ), // the taxonomy
                            );

                            //if term exists we can still get the ID
                            if( is_wp_error( $result ) ) {
                                $term_id = $result->error_data['term_exists'];
                            } else {
                                if( isset( $result['term_id'] ) )
                                    $term_id = $result['term_id'];
                            }
                            $cats[ $key ]['id'] = $term_id; // don't need this, just used for debugging
                            $post['category_ids'][] = $term_id;

                        }
                        
                    }

                }

            }
        }

        $post['_do_taxonomies'] = $taxs;    

        return $post;

    }



    /**
     * Custom function for product creation (For Woocommerce 3+ only).
     * @since  1.0.0
     */
    public function insert_or_update_product( $args, $process_id = null ){

        // Get an empty instance of the product object (defining it's type)
        $product = $this->get_product_object_type( $args  );
        if( ! $product )
            return false;

        // Filter before doing anything. Allows you to adjust object props before save.
        $product = apply_filters( 'wpgetapi_wc_before_create_update_product', $product, $args );

        // Product name (Title) and slug
        if( isset( $args['post_title'] ) )
            $product->set_name( $args['post_title'] );

        // Description and short description:
        if( isset( $args['post_content'] ) )
            $product->set_description( $args['post_content'] );
        if( isset( $args['post_excerpt'] ) )
            $product->set_short_description( $args['post_excerpt'] );

        // Status ('publish', 'pending', 'draft' or 'trash')
        $product->set_status( isset( $args['post_status'] ) ? $args['post_status'] : 'publish' );

        // Visibility ('hidden', 'visible', 'search' or 'catalog')
        $product->set_catalog_visibility( isset( $args['visibility'] ) ? $args['visibility'] : 'visible' );

        // Featured (boolean)
        $product->set_featured( isset( $args['featured'] ) ? $args['featured'] : false );

        // Virtual (boolean)
        $product->set_virtual( isset( $args['virtual'] ) ? $args['virtual'] : false );

        // Prices
        $regular_price = isset( $args['regular_price'] ) ? (string) $args['regular_price'] : '';
        $sale_price = isset( $args['sale_price'] ) ? (string) $args['sale_price'] : '';

        // filter pricing before save
        $regular_price = apply_filters( 'wpgetapi_api_to_posts_regular_price_before_save', $regular_price );
        $sale_price = apply_filters( 'wpgetapi_api_to_posts_sale_price_before_save', $sale_price );
        
        if( $regular_price > 0 )
            $product->set_regular_price( $regular_price );

        if( $sale_price > 0 )
            $product->set_sale_price( $sale_price );

        if( $regular_price > 0 || $sale_price > 0 )
            $product->set_price( isset( $sale_price ) ? $sale_price : $regular_price );
        
        if( $sale_price > 0 ) {
            $product->set_date_on_sale_from( isset( $args['sale_from'] ) ? $args['sale_from'] : '' );
            $product->set_date_on_sale_to( isset( $args['sale_to'] ) ? $args['sale_to'] : '' );
        }

        // Downloadable (boolean)
        $product->set_downloadable( isset($args['downloadable'] ) ? $args['downloadable'] : false );
        if( isset($args['downloadable']) && $args['downloadable'] ) {
            $product->set_downloads( isset($args['downloads'] ) ? $args['downloads'] : array() );
            $product->set_download_limit( isset($args['download_limit'] ) ? $args['download_limit'] : '-1' );
            $product->set_download_expiry( isset($args['download_expiry'] ) ? $args['download_expiry'] : '-1' );
        }

        // Taxes
        if ( get_option( 'woocommerce_calc_taxes' ) === 'yes' ) {
            $product->set_tax_status( isset($args['tax_status'] ) ? $args['tax_status'] : 'taxable' );
            $product->set_tax_class( isset($args['tax_class'] ) ? $args['tax_class'] : '' );
        }

        // SKU and Stock (Not a virtual product)
        if( ! isset( $args['virtual'] ) || ! $args['virtual'] ) {

            if( isset( $args['sku'] ) )
                $product->set_sku( isset( $args['sku'] ) ? $args['sku'] : '' );

            if( isset( $args['stock_quantity'] ) )
                $args['manage_stock'] = true;

            $product->set_manage_stock( isset( $args['manage_stock'] ) ? $args['manage_stock'] : false );

            $product->set_stock_status( isset( $args['stock_status'] ) ? $args['stock_status'] : 'instock' );

            if( isset( $args['stock_quantity'] ) ) {
                $product->set_stock_quantity( $args['stock_quantity'] );
                $product->set_backorders( isset( $args['backorders'] ) ? $args['backorders'] : 'no' ); // 'yes', 'no' or 'notify'
            }

        }

        // Sold Individually
        $product->set_sold_individually( isset( $args['sold_individually'] ) ? $args['sold_individually'] : false );

        // Weight, dimensions and shipping class
        if( isset( $args['weight'] ) )
            $product->set_weight( isset( $args['weight'] ) ? $args['weight'] : '' );

        if( isset( $args['length'] ) )
            $product->set_length( isset( $args['length'] ) ? $args['length'] : '' );

        if( isset( $args['width'] ) )
            $product->set_width( isset( $args['width'] ) ? $args['width'] : '' );

        if( isset( $args['height'] ) )
            $product->set_height( isset( $args['height'] ) ? $args['height'] : '' );

        if( isset( $args['shipping_class_id'] ) )
            $product->set_shipping_class_id( $args['shipping_class_id'] );

        // Upsell and Cross sell (IDs)
        $product->set_upsell_ids( isset( $args['upsells'] ) ? $args['upsells'] : '' );
        $product->set_cross_sell_ids( isset( $args['cross_sells'] ) ? $args['upsells'] : '' );

        // Attributes et default attributes
        if( isset( $args['attributes'] ) )
            $product->set_attributes( $this->prepare_product_attributes( $args['attributes'] ) );

        if( isset( $args['default_attributes'] ) )
            $product->set_default_attributes( $args['default_attributes'] ); // Needs a special formatting

        // Reviews, purchase note and menu order
        $product->set_reviews_allowed( isset( $args['reviews'] ) ? $args['reviews'] : false );
        $product->set_purchase_note( isset( $args['note'] ) ? $args['note'] : '' );
        if( isset( $args['menu_order'] ) )
            $product->set_menu_order( $args['menu_order'] );

        // Product categories and Tags
        if( isset( $args['category_ids'] ) && ! empty( $args['category_ids'] ) )
            $product->set_category_ids( $args['category_ids'] );

        if( isset( $args['tag_ids'] ) && ! empty( $args['tag_ids'] ) )
            $product->set_tag_ids( $args['tag_ids'] );

        // Images and Gallery
        if( isset( $args['image_id'] ) )
            $product->set_image_id( $args['image_id'] );

        if( isset( $args['gallery_ids'] ) )
            $product->set_gallery_image_ids( isset( $args['gallery_ids'] ) ? $args['gallery_ids'] : array() );

        if( isset( $args['meta'] ) && is_array( $args['meta'] ) ) {
            foreach ( $args['meta'] as $key => $value ) {
                if( isset( $args['id'] ) && $args['id'] > 0 ) {
                    $product->update_meta_data( $key, $value );
                } else {
                    $product->add_meta_data( $key, $value );
                }
                
            }
        }

        // Filter before saving to the DB. Allows you to adjust object props before save.
        $product = apply_filters( 'wpgetapi_api_to_posts_wc_before_product_save', $product, $args );
        
        ## --- SAVE PRODUCT --- ##
        $product_id = $product->save();

        if( $process_id !== null )
            update_post_meta( $product_id, 'importer_process_id', $process_id );

        // added in 1.3.6
        $this->update_taxonomies( $product_id, $args );

        return $product_id;
    }


    /**
     * Utility function that returns the correct product object instance.
     * @since  1.0.0
     */
    public function get_product_object_type( $args ) {
        $id = isset( $args['id'] ) && $args['id'] > 0 ? absint( $args['id'] ) : 0;
        // Get an instance of the WC_Product object (depending on his type)
        if( isset($args['type']) && $args['type'] === 'variable' ){
            $product = new WC_Product_Variable( $id );
        } elseif( isset($args['type']) && $args['type'] === 'grouped' ){
            $product = new WC_Product_Grouped( $id );
        } elseif( isset($args['type']) && $args['type'] === 'external' ){
            $product = new WC_Product_External( $id );
        } else {
            $product = new WC_Product_Simple( $id ); // "simple" By default
        } 
        
        if( ! is_a( $product, 'WC_Product' ) )
            return false;
        else
            return $product;
    }



    /**
     * Register new attribute.
     * @since  1.0.0
     */
    public function createAttribute($attributeName, $attributeSlug) {

        if( ! $attributeName || ! $attributeSlug )
            return false;

        delete_transient('wc_attribute_taxonomies');
        \WC_Cache_Helper::invalidate_cache_group('woocommerce-attributes');

        $attributeLabels = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name');
        $attributeWCName = array_search($attributeSlug, $attributeLabels, TRUE);

        if (! $attributeWCName) {
            $attributeWCName = wc_sanitize_taxonomy_name($attributeSlug);
        }

        $attributeId = wc_attribute_taxonomy_id_by_name($attributeWCName);
        if (! $attributeId) {
            $taxonomyName = wc_attribute_taxonomy_name($attributeWCName);
            unregister_taxonomy($taxonomyName);
            $attributeId = wc_create_attribute(array(
                'name' => $attributeName,
                'slug' => $attributeSlug,
                'type' => 'select',
                'order_by' => 'menu_order',
                'has_archives' => 0,
            ));

            register_taxonomy($taxonomyName, apply_filters('woocommerce_taxonomy_objects_' . $taxonomyName, array(
                'product'
            )), apply_filters('woocommerce_taxonomy_args_' . $taxonomyName, array(
                'labels' => array(
                    'name' => $attributeSlug,
                ),
                'hierarchical' => FALSE,
                'show_ui' => FALSE,
                'query_var' => TRUE,
                'rewrite' => FALSE,
            )));
        }

        return wc_get_attribute($attributeId);

    }


    /**
     * Create a new term for attributes.
     * @since  1.0.0
     */
    public function createTerm($termName, $termSlug, $taxonomy, $order = 0) {

        if( ! $termName || ! $termSlug || ! $taxonomy )
            return false;

        $taxonomy = wc_attribute_taxonomy_name($taxonomy);
        if (! $term = get_term_by('slug', $termSlug, $taxonomy)) {
            $term = wp_insert_term($termName, $taxonomy, array(
                'slug' => $termSlug,
            ));
            $term = get_term_by('id', $term['term_id'], $taxonomy);
            if ($term) {
                update_term_meta($term->term_id, 'order', $order);
            }
        }
        return $term;
    }


    /**
     * Utility function that prepare product attributes before saving.
     * @since  1.0.0
     */
    public function prepare_product_attributes( $attributes ){
        global $woocommerce;

        $data = array();
        $position = 0;
        
        foreach( $attributes as $taxonomy => $values ){
            if( ! taxonomy_exists( $taxonomy ) )
                continue;
            // Get an instance of the WC_Product_Attribute Object
            $attribute = new WC_Product_Attribute();

            $term_ids = array();

            // Loop through the term names
            foreach( $values['term_names'] as $term_name ){
                if( term_exists( $term_name, $taxonomy ) )
                    // Get and set the term ID in the array from the term name
                    $term = get_term_by( 'name', $term_name, $taxonomy );
                    if( $term )
                        $term_ids[] = $term->term_id;
                else
                    continue;
            }

            $taxonomy_id = wc_attribute_taxonomy_id_by_name( $taxonomy ); // Get taxonomy ID

            $attribute->set_id( $taxonomy_id );
            $attribute->set_name( $taxonomy );
            $attribute->set_options( $term_ids );
            $attribute->set_position( $position );
            $attribute->set_visible( $values['is_visible'] );
            $attribute->set_variation( $values['for_variation'] );

            $data[$taxonomy] = $attribute; // Set in an array

            $position++; // Increase position

        }

        return $data;

    }


    /**
     * Add a product image or gallery image.
     * @since  1.0.0
     */
    public function add_image( $image_url ) {

        if ( empty( $image_url ) ) {
            return 0;
        }

        $image_url  = trim( $image_url );
        $id         = 0;
        $upload_dir = wp_upload_dir( null, false );
        $base_url   = $upload_dir['baseurl'] . '/';

        // Check first if attachment is inside the WordPress uploads directory, or we're given a filename only.
        if ( false !== strpos( $image_url, $base_url ) || false === strpos( $image_url, '://' ) ) {

            // Search for yyyy/mm/slug.extension or slug.extension - remove the base URL.
            $file = str_replace( $base_url, '', $image_url );
            $args = array(
                'post_type'   => 'attachment',
                'post_status' => 'any',
                'fields'      => 'ids',
                'ignore_sticky_posts' => true,
                'no_found_rows' => true,
                'meta_query'  => array( // @codingStandardsIgnoreLine.
                    'relation' => 'OR',
                    array(
                        'key'     => '_wp_attached_file',
                        'value'   => '^' . $file,
                        'compare' => 'REGEXP',
                    ),
                    array(
                        'key'     => '_wp_attached_file',
                        'value'   => '/' . $file,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => '_wc_attachment_source',
                        'value'   => '/' . $file,
                        'compare' => 'LIKE',
                    ),
                ),
            );
        } else {

            // This is an external URL, so compare to source.
            $args = array(
                'post_type'   => 'attachment',
                'post_status' => 'any',
                'fields'      => 'ids',
                'ignore_sticky_posts' => true,
                'no_found_rows' => true,
                'meta_query'  => array( // @codingStandardsIgnoreLine.
                    array(
                        'value' => $image_url,
                        'key'   => '_wc_attachment_source',
                    ),
                ),
            );

        }

        $ids = get_posts( $args );

        if ( $ids ) {
            $id = current( $ids );
        }
        
        // Upload if attachment does not exists.
        if ( ! $id && stristr( $image_url, '://' ) ) {

            $image = $this->upload_image( $image_url );

            if( ! is_wp_error( $image ) && isset( $image['upload'] ) ) {

                $upload = $image['upload'];
                $filename = $image['filename'];

                if ( is_wp_error( $upload ) ) {
                    throw new Exception( $upload->get_error_message(), 400 );
                }

                $wp_filetype = wp_check_filetype( basename( $filename ), null );

                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => $filename,
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $id = wp_insert_attachment( $attachment, $upload['file'] );

                $imagenew = get_post( $id );
                $fullsizepath = get_attached_file( $imagenew->ID );

                // need to include for cron
                if ( ! function_exists( 'wp_crop_image' ) ) {
                    include( ABSPATH . 'wp-admin/includes/image.php' );
                }
                $attach_data = wp_generate_attachment_metadata( $id, $fullsizepath );
                wp_update_attachment_metadata( $id, $attach_data );

                // Save attachment source for future reference.
                update_post_meta( $id, '_wc_attachment_source', $image_url );

            }
            

        }

        return $id;

    }


    /**
     * Upload an image from an external URL.
     * @since  1.0.0
     */
    public function upload_image( $image_url ) {

        // some url's are to pages and have no extension. Check for this and if no ext then add jpeg
        $ext        = wp_check_filetype( basename( $image_url ), null );
        $ext        = ! isset( $ext['ext'] ) || $ext['ext'] == '' ? '.jpeg' : '';
        $filename   = basename(parse_url($image_url . $ext, PHP_URL_PATH));

        $args = array(
            'timeout'     => 4,
            'sslverify'   => false,
            'headers'     => array(
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36'
            ),
        );

        $response = wp_safe_remote_get( 
            $image_url, 
            $args
        );

        if ( is_wp_error( $response ) ) { 
            return new WP_Error( 'woocommerce_rest_invalid_remote_image_url', sprintf( __( 'Error getting remote image %s.', 'woocommerce' ), $image_url ) . ' ' . sprintf( __( 'Error: %s.', 'woocommerce' ), $response->get_error_message() ), array( 'status' => 400 ) ); 
        } elseif ( 200 !== wp_remote_retrieve_response_code( $response ) ) { 
            return new WP_Error( 'woocommerce_rest_invalid_remote_image_url', sprintf( __( 'Error getting remote image %s.', 'woocommerce' ), $image_url ), array( 'status' => 400 ) ); 
        } 

        // Upload the file. 
        $upload = wp_upload_bits( $filename, '', wp_remote_retrieve_body( $response ) ); 
     
        if ( $upload['error'] ) { 
            return new WP_Error( 'woocommerce_rest_image_upload_error', $upload['error'], array( 'status' => 400 ) ); 
        } 
     
        // Get filesize. 
        $filesize = filesize( $upload['file'] ); 
     
        if ( 0 == $filesize ) { 
            @unlink( $upload['file'] ); 
            unset( $upload ); 
     
            return new WP_Error( 'woocommerce_rest_image_upload_file_error', __( 'Zero size file downloaded.', 'woocommerce' ), array( 'status' => 400 ) ); 
        } 
     
        do_action( 'woocommerce_rest_api_uploaded_image_from_url', $upload, $image_url ); 
     
        return array( 
            'upload' => $upload,
            'filename' => $filename,
        ); 

    }


    /**
     * Maybe delete posts.
     * @since 1.0.0
     */
    public function delete_posts( $post_refs, $api_refs, $posts ) {

        // get array of reference ID's that need to be deleted
        $expired = array_diff( $post_refs, $api_refs );
        
        // get our count of ids
        $ids = array();

        if( empty( $expired ) )
            return $ids;

        foreach( $posts as $i => $post_id ) {

            $ref = get_post_meta( $post_id, $this->item_key, true );
            if( ! in_array( $ref, $expired ) )
                continue;

            // delete featured image
            $featured_id = get_post_meta( $post_id, '_thumbnail_id', true );
            if( $featured_id ) {
                wp_delete_attachment( $featured_id, true ); // true for skip trash
            }

            // delete gallery images
            $gallery = get_post_meta( $post_id, '_post_image_gallery', true );
            if( $gallery ) {
                $image_ids = explode ( ',', $gallery );
                foreach ( $image_ids as $key => $image_id ) {
                    wp_delete_attachment( $image_id, true ); // true for skip trash
                }
            }

            // delete our post
            $ids[] = $post_id; 
            wp_delete_post( $post_id );  
             
        }

        return $ids;

    }



    /**
     * do we need to step down into array to get our items.
     *
     */
    public function maybe_step_down( $items, $data ) {

        if( ! $this->root_key )
            return $items;

        // if we have multiple
        if( strpos( $this->root_key, ',' ) !== false ) {

            $multi = explode( ',', $this->root_key );
            $count = count( $multi );

            switch ( $count ) {
                case 1:
                    $items = $data[ $multi[0] ];
                    break;
                case 2:
                    $items = $data[ $multi[0] ][ $multi[1] ];
                    break;
                case 3:
                    $items = $data[ $multi[0] ][ $multi[1] ][ $multi[2] ];
                    break;
                case 4:
                    $items = $data[ $multi[0] ][ $multi[1] ][ $multi[2] ][ $multi[3] ];
                    break;
            }
        } else {
            $items = $data[ $this->root_key ];
        }

        return $items;

    }


    
    public function array_flatten( $array, $parent = false ) {
    
        $return = array();

        if( ! is_array( $array ) ) {

            if( $parent ) {
                $return[$parent . '_' . $key] = $value;
            } else {
                $return[$key] = $value;
            }

        } else { 

            foreach ($array as $key => $value) {
                if ( is_array( $value ) ) {

                    if( $parent )
                        $key = $parent . '_' . $key;

                    $return = array_merge( $return, $this->array_flatten( $value, $key ) );

                } else {

                    if( $parent ) {
                        $return[$parent . '_' . $key] = $value;
                    } else {
                        $return[$key] = $value;
                    }
                    
                }
            }
        }
        
        return $return;

    }


    /**
     * Save chunked items in options table.
     * @since  1.0.0
     */
    public function save_items( $items ) {

        if( empty( $items ) )
            return;

        $chunks = array_chunk( $items, 100 ); // will be 500 when it goes live. 10 is just for testing.
        $total_chunks = count( $chunks );

        $count = get_option( 'wpgetapi_' . $this->theid . '_items_count' );
        $count = ! $count ? 0 : $count;

        foreach ( $chunks as $i => $chunk ) {
            $count++;
            update_option( 'wpgetapi_' . $this->theid . '_items_' . $count, $chunk, 'no' );
        }

        update_option( 'wpgetapi_' . $this->theid . '_items_count', $count, 'no' );
        
    }


    /**
     * Get our saved items from options table.
     * @since  1.0.0
     */
    public function get_saved_items( $theid = null ) {

        if( ! $theid )
            $theid = $this->theid;

        $count = get_option( 'wpgetapi_' . $theid . '_items_count' );

        if( $count == 1 ) {
            return get_option( 'wpgetapi_' . $theid . '_items_1' );
        }

        $to_merge = array();
        for ($i=1; $i <= $count; $i++) { 
            $to_merge[] = get_option( 'wpgetapi_' . $theid . '_items_' . $i );
        }

        $items = array_merge([], ...$to_merge);

        return $items;       
    }
    
    public function get_saved_items_ajax( $num ) {
        return get_option( 'wpgetapi_' . $this->theid . '_items_'.$num );      
    }

    /**
     * Create the post name if set.
     * @since 1.0.0
     */
    public function create_post_name( $item ) {
        
        if( ! $this->post_name )
            return '';

        // $this->post_name is the text we want to replace
        $post_name = $this->post_name;

        foreach ( $item as $key => $value ) {

            // if value is array and we have curly braces
            if( is_array( $value ) && strpos( $post_name, '{' ) ) {

                preg_match_all("/{(.*?)}/", $post_name, $matches);
                if( $matches[0] ) {
                    $value = $this->nested_data( $value, $matches[0] );

                    // remove the curly braces from the post_name
                    // otherwise they will be included in name
                    foreach ( $matches as $i => $match ) {
                        $post_name = str_replace( $match, '', $post_name );
                    }
                    
                }

            }

            // if the key matches anything in the string
            if ( is_string( $value ) && strpos( $post_name, $key ) !== false) {
                $post_name = str_replace( $key, $value, $post_name );
            }
        }

        return $post_name;

    }

    /**
     * Update a log entry with single or multiple data
     * @since 1.0.0
     */
    public function update_log( $type = 'post_creator', $data = array() ) {
        if( ! $data )
            return;
        $log = get_option( 'wpgetapi_importer_' . $this->theid . '_' . $type . '_log' );
        foreach ( $data as $key => $value ) {
            $log[ $key ] = $value;
        }
        update_option( 'wpgetapi_importer_' . $this->theid . '_' . $type . '_log', $log, 'no' );
    }

    /**
     * Get our saved mapping options
     * @since  1.0.0
     */
    public function get_mapping_opts() {
        if( ! $this->saved_settings ) 
            return;
        $mapping_opts = array();
        foreach ($this->saved_settings as $key => $value) {
            if ( strpos( $key, 'map_' ) !== false ) {
                $new_key = str_replace('map_', '', $key); // just the raw field name
                $mapping_opts[ $new_key ] = $value;
            }
        }
        return $mapping_opts;
    }

    /**
     * Format keys to readable words.
     * @since  1.0.0
     */
    public function _format_key_to_label( $string ) {
        $string = str_replace('_', '', ucwords($string, '_'));
        $string = str_replace('-', '', ucwords($string, '-'));
        $words = preg_replace('/(?<!\ )[A-Z]/', ' $0', $string);
        return ucwords($words);
    }

    /**
     * Maybe do variables on endpoint
     */
    public function custom_query_variables( $url, $api ) {

        // if we have endpoint variables set, proceed
        if( isset( $api->args['query_variables'] ) && ! empty( $api->args['query_variables'] ) ) {

            $vars = explode(',', $api->args['query_variables'] );
            $finalArray = array();

            if( $vars && is_array( $vars ) ) {
                
                foreach ($vars as $var) {
                    $couple = explode('=', $var);
                    $finalArray[$couple[0]] = $couple[1];
                }

            } else {

                $couple = explode( '=', $api->args['query_variables'] );
                $finalArray[$couple[0]] = $couple[1];

            }

            $url = $this->add_query_arg( $finalArray, $url );

        }
        
        return $url;
    }

    // adding our own function instead of WP function so we can parse . within keys
    // parse_str was the issue
    public function add_query_arg( ...$args ) {
        if ( is_array( $args[0] ) ) {
            if ( count( $args ) < 2 || false === $args[1] ) {
                $uri = $_SERVER['REQUEST_URI'];
            } else {
                $uri = $args[1];
            }
        } else {
            if ( count( $args ) < 3 || false === $args[2] ) {
                $uri = $_SERVER['REQUEST_URI'];
            } else {
                $uri = $args[2];
            }
        }

        $frag = strstr( $uri, '#' );
        if ( $frag ) {
            $uri = substr( $uri, 0, -strlen( $frag ) );
        } else {
            $frag = '';
        }

        if ( 0 === stripos( $uri, 'http://' ) ) {
            $protocol = 'http://';
            $uri      = substr( $uri, 7 );
        } elseif ( 0 === stripos( $uri, 'https://' ) ) {
            $protocol = 'https://';
            $uri      = substr( $uri, 8 );
        } else {
            $protocol = '';
        }

        if ( str_contains( $uri, '?' ) ) {
            list( $base, $query ) = explode( '?', $uri, 2 );
            $base                .= '?';
        } elseif ( $protocol || ! str_contains( $uri, '=' ) ) {
            $base  = $uri . '?';
            $query = '';
        } else {
            $base  = '';
            $query = $uri;
        }

        $qs = $this->parseQueryString($query);
        $qs = urlencode_deep( $qs ); // This re-URL-encodes things that were already in the query string.
        if ( is_array( $args[0] ) ) {
            foreach ( $args[0] as $k => $v ) {
                $qs[ $k ] = $v;
            }
        } else {
            $qs[ $args[0] ] = $args[1];
        }

        foreach ( $qs as $k => $v ) {
            if ( false === $v ) {
                unset( $qs[ $k ] );
            }
        }

        $ret = build_query( $qs );
        $ret = trim( $ret, '?' );
        $ret = preg_replace( '#=(&|$)#', '$1', $ret );
        $ret = $protocol . $base . $ret . $frag;
        $ret = rtrim( $ret, '?' );
        $ret = str_replace( '?#', '#', $ret );
        return $ret;
    }

    function parseQueryString($data) {
        $data = rawurldecode($data);   
        $pattern = '/(?:^|(?<=&))[^=&\[]*[^=&\[]*/';       
        $data = preg_replace_callback($pattern, function ($match){
            return bin2hex(urldecode($match[0]));
        }, $data);
        parse_str($data, $values);

        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }


    /**
     * Get the nested data.
     * Used if data is an array.
     */
    public function nested_data( $data = array(), $keys = array() ) {

        if( ! empty( $keys ) && ! is_array( $keys ) ) {
            // Create our array of values for keys
            // First, sanitize the data and remove white spaces
            $no_whitespaces_keys = preg_replace( '/\s*,\s*/', ',', filter_var( $keys, FILTER_SANITIZE_STRING ) );
            $keys = explode( ',', $no_whitespaces_keys );
        }

        // if we have keys
        if( $keys && is_array( $keys ) ) {
            
            $keys = wpgetapi_sanitize_text_or_array( $keys );

            foreach ( $keys as $i => $value ) {
                
                // check if we have curly braces
                if ( strpos( $value, '{' ) !== false ) {

                    // remove them
                    $value = str_replace( '{', '', $value );
                    $value = str_replace( '}', '', $value );

                    // extract the pipe seperator if any
                    if ( strpos( $value, '|' ) !== false ) 
                        $value = explode( '|', $value );

                    $value = ! is_array( $value ) ? array( $value ) : $value;

                    // this gets the last key so we can still use it for urls and images
                    $last = end( $value );
                    
                    $return[] = $this->get_the_keys( $data, $value );

                } else {

                    $return = $this->get_the_keys( $data, $keys );

                }

            }

        } else {

            $return = $data;
        }

        if( is_array( $return ) && count( $return ) == 1 )
            $return = $return[0];

        return $return;

    }

    public function get_the_keys( $data, $keys ) {
        // Check if the $data parameter is set
        if (!isset($data)) {
            return null;
        }

        // Use array_reduce() to access the keys
        $result = array_reduce($keys, function ($carry, $key) {
            return isset($carry[$key]) ? $carry[$key] : null;
        }, $data);

        return $result;
    }


    /**
     * Maybe_base64_encode for login.
     * @since  1.0.0
     */
    public function maybe_base64_encode( $headers, $api ) {

        // if we have headers
        if( isset( $headers['headers'] ) && ! empty( $headers['headers'] ) ) {

            foreach ( $headers['headers'] as $name => $value ) {

                // if we have value with 'base64' keyword
                if ( strpos( $value, 'base64_encode' ) !== false ) {

                    // extract the value to encode
                    preg_match('#\((.*?)\)#', $value, $match );
                    $to_encode = isset( $match[1] ) ? $match[1] : null;
                    if( ! $to_encode )
                        return $headers;

                    // get anything before the keyword such as Basic etc
                    list($before, $after) = explode( 'base64_encode', $value );
                    if( ! $before )
                        return $headers;

                    // encode it
                    $headers['headers'][ $name ] = $before . base64_encode( $to_encode );

                }

            }

        }

        return $headers;

    }

        /**
     * Set Admin Notice text in the transient.
     * This is for the import and post create functions.
     * @since  1.0.0
     */
    public function set_admin_notice( $text = '', $time = 5 ) {
        set_transient( 'wpgetapi_admin_notice', $text, $time );
    }


    /**
     * Display admin notice.
     * @since  1.0.0
     */
    public function admin_notice(){
        /* Check transient, if available display notice */
        if( $transient = get_transient( 'wpgetapi_admin_notice' ) ) { ?>
            <div class="updated notice is-dismissible">
                <p><?php _e( wp_kses_post( $transient ) ); ?></p>
            </div>
            <?php
            /* Delete transient, only display this notice once. */
            delete_transient( 'wpgetapi_admin_notice' );
        }
    }

}

return new wpgetapi_api_importer_Importer();