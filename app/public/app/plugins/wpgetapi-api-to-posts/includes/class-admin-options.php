<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * The main class
 * @since 1.0.0
 */
class WpGetApi_Api_To_Posts_Admin_Options
{

    /**
     * Array of metaboxes/fields
     * @var array
     */
    public $option_metabox = array();

    public $metabox_id = '';
    protected $title = '';
    protected $menu_title = '';
    public $options_pages = array();

    // our saved options
    public $setup_opts;
    public $saved_settings;
    public $api_data;

    public $item_key;
    public $flatten;
    public $mapping_opts;

    /**
     * Holds an instance of the object
     *
     **/
    private static $instance = null;

    /**
     * Constructor
     * @since 0.1.0
     */
    private function __construct()
    {

        $this->theid = isset( $_GET['page'] ) ? str_replace( 'wpgetapi_importer_', '', $_GET['page'] ) : null;

        // get our options
        $this->setup_opts       = get_option('wpgetapi_setup');
        $this->api_data         = $this->get_saved_items();
        $this->saved_settings   = get_option('wpgetapi_importer_' . $this->theid);

        // set up our saved options
        $this->item_key         = isset($this->saved_settings['item_key']) ? sanitize_text_field($this->saved_settings['item_key']) : $this->item_key;
        $this->flatten          = isset($this->saved_settings['item_flatten']) ? sanitize_text_field($this->saved_settings['item_flatten']) : $this->flatten;
        $this->flatten          = isset($this->saved_settings['item_flatten']) ? sanitize_text_field($this->saved_settings['item_flatten']) : $this->flatten;

        // Set our title
        $this->menu_title = __('API to Posts', 'wpgetapi');
        $this->title = __('API to Posts', 'wpgetapi');
    }

    /**
     * Returns the running object
     *
     **/
    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->hooks();
        }
        return self::$instance;
    }

    /**
     * Initiate our hooks
     * @since 0.1.0
     */
    public function hooks()
    {

        add_action('admin_menu', array($this, 'add_options_pages'));

        add_action( 'cmb2_before_form', array( $this, 'before_form' ), 10, 4 );
        add_action( 'cmb2_after_form', array( $this, 'after_form' ), 10, 4 );

        add_action('cmb2_admin_init', array($this, 'init_custom_fields'));

        // add new save and import fields to the endpoint
        add_filter('wpgetapi_fields_endpoints', array($this, 'import_save_field'), 999, 2);
    }


    /**
     * Setup our custom fields
     * @since  0.1.0
     */
    public function init_custom_fields()
    {
        require_once WPGETAPIAPITOPOSTSDIR . 'includes/class-fields.php';
        WpGetApi_Post_Import_Mapping_Field::init_mapping();
    }

    /**
     * Add new Import field within the endpoint settings page
     */
    public function import_save_field($endpoint_fields)
    {

        if (isset($_GET['page']) && strpos($_GET['page'], 'wpgetapi_') !== false) {

            $endpoint_data = get_option(sanitize_text_field($_GET['page']));

            if (isset($endpoint_data['endpoints'])) {
                foreach ($endpoint_data['endpoints'] as $key => $data) {

                    $import_api = array(
                        array(
                            'name' => __('Import this Endpoint', 'wpgetapi-api-to-posts'),
                            'id'   => 'import_api',
                            'type' => 'radio_inline',
                            'classes' => 'field-import_api',
                            'desc' => __('Activate this endpoint to allow importing & saving.<br>After setting to Yes and saving, you will see this endpoint within the API to Posts menu.', 'wpgetapi-api-to-posts'),
                            'options' => array(
                                'no' => __('No', 'wpgetapi-api-to-posts'),
                                'yes' => __('Yes', 'wpgetapi-api-to-posts'),
                            ),
                            'default' => 'no',
                        )
                    );

                    $endpoint_fields = array_merge($endpoint_fields, $import_api);
                }
            }
        }


        return $endpoint_fields;
    }



    /**
     * Add the options metabox to the array of metaboxes
     * @since  0.1.0
     */
    public function option_fields()
    {

        //Only need to initiate the array once per page-load
        if (!empty($this->option_metabox)) {
            return $this->option_metabox;
        }

        $mapping_field_ids = array(
            'field_mapping_title',
            'item_post_name',
            'field_mapping_end',
        );
        foreach ($this->get_all_keys() as $i => $key) {
            // skip our item key as this will always be mapped to a custom meta
            // if( $key == $this->item_key )
            //     continue;
            $mapping_field_ids[] = 'map_' . $key;
        }

        // setup tab
        $option_metabox[] = array(
            'title'      => __('Dashboard', 'wpgetapi'),
            'menu_title' => __('Dashboard', 'wpgetapi'),
            'id'         => 'wpgetapi_importer_dashboard',
            'desc'       => __('', 'wpgetapi'),
            'show_on'    => array('key' => 'options-page', 'value' => array('wpgetapi_importer_dashboard'),),
            'show_names' => true,
            'fields'     => $this->dashboard_fields(),
        );

        if( isset( $this->setup_opts['apis'] ) ) {
            foreach ( $this->setup_opts['apis'] as $i => $api ) {

                $the_api = get_option('wpgetapi_' . $api['id']);

                if (isset($the_api['endpoints'])) {

                    foreach ($the_api['endpoints'] as $i => $endpoint) {

                        // if this endpoint is set to save
                        if (isset($endpoint['import_api']) && $endpoint['import_api'] == 'yes') {

                            // the id of this endpoint. Made up of api_id and endpoint_id
                            $theid = $api['id'] . '_' . $endpoint['id'];
                            $this->api_id = $api['id'];
                            $this->endpoint_id = $endpoint['id'];

                            $desc = 'API: ' . '<b>' . $api['name'] . '</b><br>';
                            $desc .= 'Endpoint: ' . '<b>' . $endpoint['id'] . '</b><br>';
                            $desc .= __('Clicking the \'Test Endpoint\' button within your <a target="_blank" href="' . admin_url( '/admin.php?page=wpgetapi_' . $this->api_id ) . '">endpoint settings</a> page can help you with these settings.<br>Ensure that your endpoint is returning the correct data in PHP array format.', 'wpgetapi-api-to-posts');

                            $insert = array(
                                'title'      => $api['name'] . ' ' . $endpoint['id'],
                                'menu_title' => $api['name'] . ' ' . $endpoint['id'],
                                'id'         => 'wpgetapi_importer_' . $theid,
                                'desc'       => $desc,
                                'show_on'    => array('key' => 'options-page', 'value' => array($theid),),
                                'show_names' => true,
                                'vertical_tabs' => false,
                                'tabs'          => array(
                                    array(
                                        'id'    => 'tab-1',
                                        'title' => '<span>1</span> API Settings',
                                        'fields' => array(
                                            'api_setup_title',
                                            'api_type',
                                            'linked_endpoint',
                                            'key_title',
                                            'item_key',
                                            'root_key',
                                            'flatten_array',
                                            'filter_by',
                                        ),
                                    ),
                                    array(
                                        'id'    => 'tab-2',
                                        'title' => '<span>2</span> Post Settings',
                                        'fields' => array(
                                            'post_setup_title',
                                            'item_post_type',
                                            'post_status',
                                            'post_author',
                                            'sync_posts',
                                            'import_interval',
                                        ),
                                    ),
                                    array(
                                        'id'    => 'tab-3',
                                        'title' => '<span>3</span> API Importer',
                                        'fields' => array(
                                            'api_importer',
                                        ),
                                    ),
                                    array(
                                        'id'    => 'tab-4',
                                        'title' => '<span>4</span> Data Mapping',
                                        'fields' => $mapping_field_ids
                                    ),
                                    array(
                                        'id'    => 'tab-5',
                                        'title' => '<span>5</span> Post Creator',
                                        'fields' => array(
                                            'post_creator',
                                        ),
                                    ),
                                ),
                            );

                            $insert['fields'] = array_merge(
                                $this->api_settings_fields(),
                                $this->post_settings_fields(),
                                $this->importer_fields(),
                                $this->mapping_fields(),
                                $this->post_creator_fields(),
                            );

                            $option_metabox = $this->_array_insert($option_metabox, 2, $insert);
                        }
                    }
                }
            }
        }

        return $option_metabox;
    }


    /**
     * Try to work out what type of endpoint we have, to be used in the settings page as hints to user
     * @since 1.0.0
     */
    public function detection() {

        $api_data = get_option('wpgetapi_importer_' . $this->theid . '_initial');

        if ( ! $api_data || $api_data == 'item_key' ) {
            return array(
                'type' => 'detail',
                'maybe_root' => '',
                'maybe_id' => '',
            );
        }

        $type = 'associative';
        $maybe_root = null;
        $maybe_id = null;
        $sample_item = null;
        $possible_roots = array('response', 'results', 'items', 'data', 'products');
        $possible_ids = array('id', '_id', 'uuid', 'product_id', 'productid', 'item_id', 'itemid', 'post_id', 'postid');

        /**
         * detect the type of array
         * 
         */
        // if we have found indexed
        if (isset($api_data[0])) {
            $type = 'single_item';
            $sample_item = $api_data[0];
        }

        // if we have found indexed multiple
        if (isset($api_data[0]) && isset($api_data[1]))
            $type = 'multiple_items';

        /**
         * detect the root of associative array.
         * We are assuming a lot of things here and making best guess we can!
         * 
         */
        if ($type == 'associative') {

            foreach ($api_data as $key => $data) {

                // loop through the possible roots and see if it exists in api data
                foreach ($possible_roots as $possible_root) {

                    if (strtolower($key) == $possible_root) {

                        if (isset($data[0])) {
                            //$type = 'single_item';
                            $sample_item = $data[0];
                        }

                        if (isset($data[0]) && isset($data[1]))
                            //$type = 'multiple_items';

                        $maybe_root = $key;

                        break;
                    }
                }
            }
        }

        /**
         * detect the id within our sample item.
         * We are assuming a lot of things here and making best guess we can!
         * 
         */
        if (is_array($sample_item)) {

            foreach ($sample_item as $key => $data) {

                // loop through the possible ids and see if it exists in api data
                foreach ($possible_ids as $possible_id) {

                    if (strtolower($key) == $possible_id) {

                        $maybe_id = $key;

                        break;
                    }
                }
            }
        }

        return array(
            'type' => $type,
            'maybe_root' => $maybe_root,
            'maybe_id' => $maybe_id,
        );
    }

    /**
     * Get all endpoints with importer active
     * @since 1.0.0
     */
    public function get_endpoints_for_import() {

        $options[] = '';
        foreach ( $this->setup_opts['apis'] as $i => $api ) {

            $the_api = get_option('wpgetapi_' . $api['id']);

            if (isset($the_api['endpoints'])) {

                foreach ($the_api['endpoints'] as $i => $endpoint) {

                    // if this endpoint is set to save
                    if ( isset($endpoint['import_api']) && $endpoint['import_api'] == 'yes') {

                        if( $api['id'] . '_' . $endpoint['id'] == $this->theid )
                            continue;

                        $options[ $api['id'] . '_' . $endpoint['id'] ] = $api['id'] . ' ' . $endpoint['id'];

                    }

                }

            }

        }

        return $options;

    }


    /**
     * Add our settings fields.
     * @since 1.0.0
     */
    public function api_settings_fields()
    {

        // get our type and set a default in case it wasn't saved
        $detected = $this->detection( $this->theid );

        if( ! $detected )
            return array();

        // api type
        if ( ! isset( $detected['type'] ) ) {

            $type_desc = 'We couldn\'t detect the type of endpoint.';
            $type_default = 'multiple_items';

        } else if ($detected['type'] == 'single_item') {

            $type_desc = 'We\'ve detected that this endpoint contains a single item.<br>If this is correct, leave this setting as Single Item.';
            $type_default = 'single_item';

        } else if ( $detected['type'] == 'detail' ) {

            $type_desc = 'We\'ve detected that this endpoint contains detail data that links to another endpoint.<br>';
            $type_default = 'detail';

        } else {

            $type_desc = 'We\'ve detected that this endpoint contains multiple items.<br>If this is correct, leave this setting as Multiple Items.';
            $type_default = 'multiple_items';

        }


        if (isset($detected['maybe_id']) && $detected['maybe_id']) {

            $item_key_desc = 'We\'ve detected that the unique item key is likely <b>' . $detected['maybe_id'] . '</b>.';

        } else {

            $detected['maybe_id'] = null;
            $item_key_desc = 'We couldn\'t detect the item key.<br>It could be something like id, ID, postID, product_id, itemNo or similar.';

        }

        if ( isset( $detected['type'] ) && $detected['type'] == 'associative' && isset( $detected['maybe_root'] ) ) {

            $root_key_desc = 'We\'ve detected that the root key is likely <b>' . $detected['maybe_root'] . '</b>.';
            
        } else {

            $detected['maybe_root'] = null;
            $root_key_desc = 'We couldn\'t detect the root key.<br>It is likely there is no root key, so you can leave this blank.';

        }


        // api setup
        $fields[] = array(
            'name' => '<span class="dashicons dashicons-admin-generic"></span> ' . __('API Settings', 'wpgetapi-api-to-posts'),
            'id'   => 'api_setup_title',
            'type' => 'title',
            'desc' => __('Determines how items are imported from the API.', 'wpgetapi-api-to-posts'),
            //'before_row' => '<div class="section-wrapper">',
        );

        $fields[] = array(
            'id'   => 'api_id',
            'type' => 'hidden',
            'default' => $this->api_id,
        );
        $fields[] = array(
            'id'   => 'endpoint_id',
            'type' => 'hidden',
            'default' => $this->endpoint_id,
        );

        $fields[] = array(
            'name' => __('Data Type', 'wpgetapi-api-to-posts'),
            'id'   => 'api_type',
            'type' => 'select',
            'desc' => __($type_desc, 'wpgetapi-api-to-posts'),
            'options' => array(
                'multiple_items' => 'Multiple Items',
                'single_item'  => 'Single Item',
                'detail'  => 'Detail',
            ),
            'default' => $type_default,
        );

        $fields[] = array(
            'name' => __('Linked Endpoint', 'wpgetapi-api-to-posts'),
            'id'   => 'linked_endpoint',
            'type' => 'select',
            'desc' => 'Select the endpoint that this one links to.',
            'options' => $this->get_endpoints_for_import(),
            'attributes'    => array(
                'data-conditional-id'     => 'api_type',
                'data-conditional-value'  => 'detail',
            ),
        );

        $fields[] = array(
            'name' => __('Unique Item Key', 'wpgetapi-api-to-posts'),
            'id'   => 'item_key',
            'type' => 'text',
            'desc' => $item_key_desc,
            'default' => $detected['maybe_id'],
            'attributes'    => array(
                'data-conditional-id'     => 'api_type',
                'data-conditional-value'  => wp_json_encode( array( 'single_item', 'multiple_items' ) ),
            ),
        );
        $fields[] = array(
            'name' => __('Root Key', 'wpgetapi-api-to-posts'),
            'id'   => 'root_key',
            'type' => 'text',
            'desc' => $root_key_desc,
            'default' => $detected['maybe_root'],
            // 'attributes'    => array(
            //     'data-conditional-id'     => 'api_type',
            //     'data-conditional-value'  => wp_json_encode( array( 'single_item', 'multiple_items' ) ),
            // ),
        );
        // $fields[] = array(
        //     'name' => __('Flatten Array', 'wpgetapi-api-to-posts'),
        //     'id'   => 'flatten_array',
        //     'type' => 'select',
        //     'desc' => __('Flattens nested, multi-dimensional arrays.<br>If data within each API item is heavily nested, this can help with mapping data.<br>Leave as no if you unsure.', 'wpgetapi-api-to-posts'),
        //     'options' => array(
        //         'no' => 'No',
        //         'yes' => 'Yes',
        //     ),
        //     'attributes'    => array(
        //         'data-conditional-id'     => 'api_type',
        //         'data-conditional-value'  => 'detail',
        //         'data-conditional-invert' => true
        //     ),
        // );
        // $fields[] = array(
        //     'name' => __('Filter By', 'wpgetapi-api-to-posts'),
        //     'id'   => 'filter_by',
        //     'type' => 'text',
        //     'desc' => __('Filter items by a [key:value] pair if you only need to get certain items.<br>Multiple values can be comma seperated.<br>Example: Get only items with a specific storeId - [storeId:12345] or multiple - [storeId:12345,445566]', 'wpgetapi-api-to-posts'),
        //     'attributes' => array(
        //         'placeholder' => '[storeId:12345]',
        //     ),
        //     //'after_row' => '</div>',
        // );

        return $fields;
    }


    /**
     * Add our settings fields.
     * @since 1.0.0
     */
    public function post_settings_fields()
    {

        // post setup
        $fields[] = array(
            'name' => '<span class="dashicons dashicons-archive"></span> ' . __('Post Settings', 'wpgetapi-api-to-posts'),
            'id'   => 'post_setup_title',
            'type' => 'title',
            'desc' => __('Determines how your posts will be created.', 'wpgetapi-api-to-posts'),
            //'before_row' => '<div class="section-wrapper">',
        );
        $fields[] = array(
            'name' => __('Post Type', 'wpgetapi-api-to-posts'),
            'id'   => 'item_post_type',
            'type' => 'select',
            'desc' => __('Select which registered post type you would like to create the items as.', 'wpgetapi-api-to-posts'),
            'options_cb' => 'wpgetapi_api_importer_get_post_types',
            'attributes'    => array(
                'data-conditional-id'     => 'api_type',
                'data-conditional-value'  => wp_json_encode( array( 'single_item', 'multiple_items' ) ),
            ),
        );
        $fields[] = array(
            'name' => __('Post Status', 'wpgetapi-api-to-posts'),
            'id'   => 'post_status',
            'type' => 'select',
            'desc' => __('Select default status of imported posts', 'wpgetapi-api-to-posts'),
            'options' => array(
                'publish' => 'Published',
                'pending' => 'Pending Review',
                'draft' => 'Draft',
            ),
            'attributes'    => array(
                'data-conditional-id'     => 'api_type',
                'data-conditional-value'  => wp_json_encode( array( 'single_item', 'multiple_items' ) ),
            ),
        );
        $fields[] = array(
            'name' => __('Post Author', 'wpgetapi-api-to-posts'),
            'id'   => 'post_author',
            'type' => 'select',
            'desc' => __('Select the author of imported posts', 'wpgetapi-api-to-posts'),
            'options_cb' => 'wpgetapi_api_importer_get_users',
            'attributes'    => array(
                'data-conditional-id'     => 'api_type',
                'data-conditional-value'  => wp_json_encode( array( 'single_item', 'multiple_items' ) ),
            ),
        );

        $fields[] = array(
            'name' => __('Import Type', 'wpgetapi-api-to-posts'),
            'id'   => 'sync_posts',
            'type' => 'select',
            'desc' => __('How to handle the creating/updating/deleting of posts from the API.', 'wpgetapi-api-to-posts'),
            'options' => array(
                'full_sync' => 'Syncs with API (create, update & delete posts)',
                'no_delete' => 'Create & Update (no delete)',
                'update_only' => 'Update only (no delete & no create)',
                'no_delete_no_update' => 'Create new only (no delete & no update)',
            ),
        );
        $fields[] = array(
            'name' => __('Import Interval', 'wpgetapi-api-to-posts'),
            'id'   => 'import_interval',
            'type' => 'select',
            'desc' => __('The interval that the API is called to import and sync posts.', 'wpgetapi-api-to-posts'),
            'options' => array(
                '' => 'No automatic sync',
                'five_minutes' => 'Every 5 Minutes',
                'thirty_minutes' => 'Every 30 Minutes',
                'hourly' => 'Every Hour',
                'twicedaily' => 'Twice Daily',
                'daily' => 'Once Daily',
                'weekly' => 'Once Weekly',
            ),
            //'after_row' => '</div>',
        );

        return $fields;
    }


    /**
     * Add our dashboard fields.
     * @since 1.0.0
     */
    public function dashboard_fields()
    {

        $fields[] = array(
            'name' => '',
            'id'   => 'dashboard',
            'type' => 'title',
            'desc' => $this->show_dashboard(),
        );

        return $fields;
    }

    /**
     * Add our importer fields.
     * @since 1.0.0
     */
    public function importer_fields()
    {

        $fields[] = array(
            'name' => '',
            'id'   => 'api_importer',
            'type' => 'title',
            'desc' => $this->show_importer(),
        );
        
        // $fields[] = array(
        //     'name' => '',
        //     'id'   => 'api_importer',
        //     'type' => 'title',
        //     'desc' => $this->show_importer(),
        // );

        return $fields;
    }

    /**
     * Add our mapping fields.
     * @since 1.0.0
     */
    public function mapping_fields()
    {

        $fields[] = array(
            'name' => '<span class="dashicons dashicons-randomize"></span> ' . __('Data Mapping', 'wpgetapi-api-to-posts'),
            'id'   => 'field_mapping_title',
            'type' => 'title',
            'desc' => __('Map API data to WordPress categories, tags, custom fields, images etc.', 'wpgetapi-api-to-posts'),
            //'before_row' => '<div class="section-wrapper field-mapping">',
        );
        
        if ( ! $this->api_data || ! isset( $this->api_data[0] ) ) {

            $fields[] = array(
                'name' => '',
                'id'   => 'field_mapping_end',
                'type' => 'title',
                'desc' => '<div class="side-notice notice-warning"><p><b>Notice:</b> Data Mapping options will appear here after running the API Importer.</p></div>',
                'after_row' => '',
            );

        } else {

            // allows you to select any item to use as the mapping 'base' item
            $get_item = apply_filters( 'wpgetapi_api_to_posts_get_item_for_mapping', 0 );
            $item = $this->api_data[ $get_item ];
            
            $keys = $this->get_all_keys();
            $values = array_values($keys);
            
            $title_default = '';
            $title_desc_prepend = '';

            if( $this->saved_settings['api_type'] != 'detail' ) {
                
                if( $values ) {
                    foreach ( $values as $i => $key_name ) {
                        if( $key_name == 'name' )
                            $title_default = 'name';
                        if( $key_name == 'title' )
                            $title_default = 'title';
                        if( $key_name == 'product_title' )
                            $title_default = 'product_title';
                        if( $key_name == 'product_name' )
                            $title_default = 'product_name';
                    }
                }

            } else {
                $title_desc_prepend = '<b>Leave blank if this was already set in your linked endpoint.</b><br>';
            }

            $fields[] = array(
                'name' => __('Post Title', 'wpgetapi-api-to-posts'),
                'id'   => 'item_post_name',
                'type' => 'text',
                'default' => $title_default,
                'desc' => $title_desc_prepend . __('Create the post title from single or multiple API keys.<br>Add the keys from the API that you want to use as the post title.<br>eg. keys of \'year - make model\' might become \'2022 - Toyota Camry\'', 'wpgetapi-api-to-posts'),
                'attributes' => array(
                    'placeholder' => 'year - make model',
                ),
            );

            foreach ( $keys as $i => $key ) {

                // $set_item_key = false;
                // if( $key == $this->item_key )
                //     $set_item_key = true;

                $fields = apply_filters('wpgetapi_post_import_before_field_mapping', $fields, $key, $item);

                $fields[] = array(
                    'name' => $key,
                    'id'   => 'map_' . $key,
                    'type' => 'mapping',
                    'options_cb' => 'wpgetapi_api_importer_get_mapping_options',
                    'item_keys' => $values,
                    'item_key' => $key,
                    'item_value' => ( isset( $item[ $key ] ) ? $item[ $key ] : '' ),
                    'repeatable' => true,
                );

                $fields = apply_filters('wpgetapi_post_import_after_field_mapping', $fields, $key, $item);
            }

            $fields[] = array(
                'name' => '',
                'id'   => 'field_mapping_end',
                'type' => 'title',
                'desc' => '',
            );
        }

        return $fields;
    }



    /**
     * Add our post creator fields.
     * @since 1.0.0
     */
    public function post_creator_fields()
    {

        $fields[] = array(
            'name' => '',
            'id'   => 'post_creator',
            'type' => 'title',
            'desc' => $this->show_post_creator(),
        );

        return $fields;
    }




    /**
     * Make sure we get all keys from every item.
     * Some items may not include every key, so loop through each one to get them all.
     * @since 1.0.0
     */
    public function get_all_keys()
    {

        $all_keys = array();
        $items = $this->api_data;

        // flatten if set to yes
        if ($this->flatten == 'yes' && is_array($items) && !empty($items)) {
            foreach ($items as $i => $item) {
                $items[$i] = $this->array_flatten($item);
            }
        }

        if ( $items ) {
            foreach ( $items as $i => $item ) {
                if( is_array( $item ) ) {
                    foreach ( $item as $key => $value ) {
                        if (!in_array( $key, $all_keys ) )
                            $all_keys[] = $key;
                    }
                }
                
            }
        }

        return $all_keys;
    }

    /**
     * The product creator
     * @since 1.0.0
     */
    public function show_importer()
    {

        ob_start();

        // get our items (if any)
        $log                = get_option('wpgetapi_importer_' . $this->theid . '_importer_log');
        $import_disabled    = true;
        $import_url         = '#';
        $linked             = true;
        

        if ( ! empty( $this->saved_settings ) ) {

            $import_disabled = false;
            $import_url = admin_url('admin-post.php?action=wpgetapi_importer_import_manually&api_id=' . $this->api_id . '&endpoint_id=' . $this->endpoint_id . '&theid=' . $this->theid );

            // make sure we have our linked api
            if( $this->saved_settings['api_type'] == 'detail' ) {

                $linked_endpoint    = get_option('wpgetapi_importer_' . $this->saved_settings['linked_endpoint'] );
                $linked_item_key    = $linked_endpoint['item_key'];
                $linked_items       = get_option('wpgetapi_' . $this->saved_settings['linked_endpoint'] . '_items_count' );
                
                if( ! $linked_items || ! $linked_item_key ) {
                    $import_disabled    = true;
                    $import_url         = '#';
                    $linked             = false;
                }

            }

        }

?>

        <div class="box half">
            <h4><?php _e('Import from API', 'wpgetapi-api-to-posts'); ?></h4>

            <?php if ( $import_disabled && $linked ) { ?>
                <div class="side-notice notice-warning">
                    <p><b><?php _e('Notice:', 'wpgetapi-api-to-posts'); ?></b> <?php _e('Save the API Settings & Post Settings before running the Importer.', 'wpgetapi-api-to-posts'); ?></p>
                </div>
            <?php } else if ( $import_disabled && ! $linked ) { ?>
                <div class="side-notice notice-warning">
                    <p><?php _e('You first need to save the linked endpoint details and run the Importer there.', 'wpgetapi-api-to-posts'); ?><br><a href="<?php echo admin_url( '/admin.php?page=wpgetapi_importer_' . $this->saved_settings['linked_endpoint'] ); ?>">Click here</a> to run the Importer on that endpoint.</p>
                </div>
            <?php } ?>

            <p><a class="button-primary" href="<?php esc_attr_e($import_url) ?>" <?php echo $import_disabled ? 'disabled' : ''; ?>>
                    <?php _e('Run Importer', 'wpgetapi-api-to-posts'); ?>
                </a></p>
        </div>
        <div class="box half">
            <h4><?php _e('Importer Log', 'wpgetapi-api-to-posts'); ?></h4>
            <?php if ($log) { ?>
                <p><?php _e(
                        sprintf(
                            'Started: <b>%1s</b><br>' .
                            'Items: <b>%2s</b><br>' .
                            'Completed: <b>%3s</b><br>' .
                            'Type: <b>%4s</b>',
                            $log['started'],
                            $log['items'],
                            $log['completed'],
                            $log['type'],
                        ),
                        'wpgetapi-api-to-posts'
                    ); ?>
                </p>
            <?php } ?>

        </div>

    <?php

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }


    /**
     * The dashboard
     * @since 1.0.0
     */
    public function show_dashboard()
    {

        ob_start(); ?>

        <h4>Endpoints ready for import</h4>

        <?php
        $found = false;
        foreach ( $this->setup_opts['apis'] as $i => $api ) {

            $the_api = get_option('wpgetapi_' . $api['id']);

            if (isset($the_api['endpoints'])) {

                foreach ($the_api['endpoints'] as $i => $endpoint) {

                    // if this endpoint is set to save
                    if (isset($endpoint['import_api']) && $endpoint['import_api'] == 'yes') {

                        $found = true;
                        // the id of this endpoint. Made up of api_id and endpoint_id
                        $theid = $api['id'] . '_' . $endpoint['id'];
                        ?>

                        <div class="an-api">
                            <?php
                                $desc = 'API: ' . '<b>' . $api['name'] . '</b><br>';
                                $desc .= 'Endpoint: ' . '<b>' . $endpoint['id'] . '</b><br>';
                                $desc .= '<a class="button button-secondary" href="' . admin_url( '/admin.php?page=wpgetapi_importer_' . $theid ) . '">Setup this endpoint</a>';

                                echo $desc;
                            ?>
                        </div>
                        <?php

                    }

                }

            }

        }

        if( !$found ) { ?>

            <div class="an-api">
                No endpoints were found.<br>
                Visit your endpoint and set the <b>Import this Endpoint</b> option to 'Yes'.
            </div>

        <?php } ?>

        
    <?php

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Get our saved mapping options
     * @since  1.0.0
     */
    public function get_mapping_opts()
    {
        if (!$this->saved_settings)
            return;
        $mapping_opts = array();
        foreach ($this->saved_settings as $key => $value) {
            if (strpos($key, 'map_') !== false) {
                $new_key = str_replace('map_', '', $key); // just the raw field name
                $mapping_opts[$new_key] = $value;
            }
        }
        return $mapping_opts;
    }

    /**
     * The post creator
     * @since 1.0.0
     */
    public function show_post_creator()
    {

        ob_start();

        $log                = get_option('wpgetapi_importer_' . $this->theid . '_post_creator_log');
        $creator_disabled   = true;
        $creator_url        = '#';

        if ($this->get_mapping_opts()) {
            $creator_disabled = false;
            $creator_url = admin_url('admin-post.php?action=wpgetapi_importer_create_posts_manually&api_id=' . $this->api_id . '&endpoint_id=' . $this->endpoint_id . '&theid=' . $this->theid);
        }

    ?>
        <div class="box half">

            <h4><?php _e('Post Creator', 'wpgetapi-api-to-posts'); ?></h4>

            <?php if ($creator_disabled) { ?>
                <div class="side-notice">
                    <p><b><?php _e('Notice:', 'wpgetapi-api-to-posts'); ?></b> <?php _e('The Data Mapping needs to be saved before running the Post Creator.', 'wpgetapi-api-to-posts'); ?></p>
                </div>
            <?php } ?>


            <div class="run-importer">
                <button class="button-primary wpgetpiimporter" style="margin-bottom:15px"><?php _e('Run Post Creator', 'wpgetapi-api-to-posts'); ?></button>
                <div id="importerStatus" style=" border: 1px solid #ccc; border-radius: 5px; display:none">
                <div class="progress-bar" style="height: 25px; width:0; color:#fff; text-align:center; background-color: #4CAF50; border-radius:5px;align-items:center;display:grid;transition: all ease 1s;"></div>
                </div>
                <div class="notify hidden" style="margin:15px 0 15px;color: #888;text-align: center;"><span>Processing...</span> do not close this window.</div>
            </div>

        </div>
        <div class="box half">
            <h4><?php _e('Post Creator Log', 'wpgetapi-api-to-posts'); ?></h4>

            <div class="log">
                <span class="started">Started: <b><?php echo ( isset( $log['started'] ) ) ? $log['started'] : ''; ?></b></span>
                <span class="completed">Completed: <b><?php echo ( isset( $log['completed'] ) ) ? $log['completed'] : ''; ?></b></span>
                <span class="created">Created: <b><?php echo ( isset( $log['created'] ) ) ? $log['created'] : ''; ?></b></span>
                <span class="updated">Updated: <b><?php echo ( isset( $log['updated'] ) ) ? $log['updated'] : ''; ?></b></span>
                <span class="deleted">Deleted: <b><?php echo ( isset( $log['deleted'] ) ) ? $log['deleted'] : ''; ?></b></span>
                <span class="type">Type: <b><?php echo ( isset( $log['type'] ) ) ? $log['type'] : ''; ?></b></span>
            </div>

        </div>

        <script>

            jQuery('.wpgetpiimporter').on('click', function(e) {

                e.preventDefault();
                jQuery('#importerStatus').show();
                jQuery(this).prop('disabled', true);
                jQuery( ".cmb2-id-post-creator .log .completed b" ).text( '' );
                jQuery( ".cmb2-id-post-creator .run-importer .notify" ).removeClass( 'hidden' );
                var id = 1;
                initAjaxWPImporter(id, '<?php echo rand(); ?>');

            });

            function initAjaxWPImporter(id, process_id){
                jQuery.ajax({

                    type: "POST",
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    data: {
                        action: "wpgetapi_importer_run_post_creator_ajax",
                        theid: "<?php echo $this->theid; ?>",
                        api_id: "<?php echo $this->api_id; ?>",
                        endpoint_id: "<?php echo $this->endpoint_id; ?>",
                        num : id,
                        process_id : process_id
                    },

                    success: function(response) {

                        var data = JSON.parse(response);

                        jQuery( "#importerStatus .progress-bar" ).width( data.progress+'%' );
                        jQuery( "#importerStatus .progress-bar" ).text( data.progress+'%' );

                        if( data.started != false ) 
                            jQuery( ".cmb2-id-post-creator .log .started b" ).text( data.started );

                        jQuery( ".cmb2-id-post-creator .log .created b" ).text( data.created );
                        jQuery( ".cmb2-id-post-creator .log .updated b" ).text( data.updated );
                        jQuery( ".cmb2-id-post-creator .log .deleted b" ).text( data.deleted );

                        if( data.next != '' ) {
                            initAjaxWPImporter( data.next, process_id );
                        } else {
                            jQuery( '.wpgetpiimporter').prop('disabled', false );
                            jQuery( ".cmb2-id-post-creator .log .completed b" ).text( data.completed );
                            jQuery( ".cmb2-id-post-creator .run-importer .notify" ).text( 'All Done!' );
                        }

                    }
                    
                });
            }
        </script>

    <?php

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }


    public function add_options_pages()
    {

        $option_tabs = self::option_fields();

        foreach ($option_tabs as $index => $option_tab) {
            if ($index == 0) {

                $this->options_pages[] = add_menu_page(
                    $this->title,
                    $this->menu_title,
                    'manage_options',
                    $option_tab['id'],
                    array($this, 'admin_page_display'),
                    'none'
                ); //Link admin menu to first tab

                add_submenu_page($option_tabs[0]['id'], $this->menu_title, $option_tab['menu_title'], 'manage_options', $option_tab['id'], array($this, 'admin_page_display')); //Duplicate menu link for first submenu page
            } else {
                $this->options_pages[] = add_submenu_page($option_tabs[0]['id'], $this->menu_title, $option_tab['menu_title'], 'manage_options', $option_tab['id'], array($this, 'admin_page_display'));
            }
        }

        foreach ($this->options_pages as $page) {
            // Include CMB CSS in the head to avoid FOUC
            add_action("admin_print_styles-{$page}", array('CMB2_Hookup', 'enqueue_cmb_css'));
        }
    }

    /**
     * Admin page markup. Mmply handled by CMB2
     * @since  0.1.0
     */
    public function admin_page_display()
    {

        $option_tabs = self::option_fields(); //get all option tabs
        $tab_forms = array();
    ?>

        <div class="wrap wpgetapi">

            <div class="main_content_cell">

                <h1 class="wp-heading-inline">
                    <img width="24" height="22" src="<?php echo esc_url(WPGETAPIURL . 'assets/img/wpgetapi-icon.png'); ?>" /> <?php esc_html_e($this->title, 'wpgetapi') ?> <span class="vnum"><?php echo WPGETAPIAPITOPOSTSVERSION; ?></span>
                </h1>

                <div class="outer-box">
                    <!-- Options Page Nav Tabs -->
                    <h2 class="nav-tab-wrapper">
                        <?php foreach ($option_tabs as $option_tab) :

                            $tab_slug = $option_tab['id'];
                            $nav_class = 'nav-tab';
                            if ($tab_slug == $_GET['page']) {
                                $nav_class .= ' nav-tab-active'; //add active class to current tab
                                $tab_forms[] = $option_tab; //add current tab to forms to be rendered
                            }

                        ?>

                            <a class="<?php esc_attr_e($nav_class); ?>" href="<?php esc_url(menu_page_url($tab_slug)); ?>"><?php esc_attr_e($option_tab['menu_title'], 'wpgetapi'); ?></a>

                        <?php endforeach; ?>
                    </h2>
                    <!-- End of Nav Tabs -->

                    <?php
                    //render all tab forms (normaly just 1 form) 
                    foreach ($tab_forms as $tab_form) : ?>

                        <div id="<?php esc_attr_e($tab_form['id']); ?>" class="cmb-form group">
                            <div class="metabox-holder">
                                <div class="pmpbox pad">
                                    <h3 class="title"><?php esc_html_e($tab_form['title'], 'wpgetapi'); ?></h3>
                                    <div class="desc"><?php echo wp_kses_post($tab_form['desc']); ?></div>
                                    <?php cmb2_metabox_form($tab_form, $tab_form['id'], $tab_form); ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>

            <div class="sidebar_cell">

                <?php
                $sidebar = self::sidebar_display();
                echo apply_filters( 'wpgetapi_admin_sidebar_display', $sidebar );
                ?>

            </div>

        </div>

    <?php

    }
    /**
     * Display our sidebar options.
     * @since 1.0.0
     */
    public function sidebar_display()
    {

        // only show on import page
        if( isset( $_GET['page'] ) && strpos( $_GET['page'], 'wpgetapi_importer_' ) !== false ) {
            return $this->import_sidebar();
        }

        return $sidebar;
    }


    /**
     * Sidebar notes.
     * @since 1.0.0
     */
    public function import_sidebar()
    {

    ?>
        <div class="box">
            <h4><?php esc_html_e('How It Works', 'wpgetapi-api-to-posts'); ?></h4>
            <p>There are 5 steps involved to take your API data and turn it into WordPress posts. Whether your data is products, real estate listings or anything else, we call them 'items'.</p>
            <hr>
            <p><b>1 - API Settings</b></p>
            <ul>
                <li><b><?php _e('Data Type', 'wpgetapi-api-to-posts'); ?></b> - Select the type of data that this endpoint contains.</li>
                <li><b><?php _e('Unique Item Key', 'wpgetapi-api-to-posts'); ?></b> - this will usually be something like id, product_id, sku, listing_id or item_no.</li>
                <li><b><?php _e('Root Key', 'wpgetapi-api-to-posts'); ?></b> - if items are not on the top level array, add the root key(s). Can be comma seperate to step down multiple levels: data,results,items (your keys may be different to this though).</li>
                <li><b><?php _e('Linked Endpoint', 'wpgetapi-api-to-posts'); ?></b> - available when 'Detail' is the Data Type. This links this endpoint to another endpoint. The linked endpoint should provide multiple items and the 'Detail' endpoint contains extra data for each of the items.</li>
            </ul>
            <hr>
            <p><b>2 - Post Settings</b></p>
            <p>These settings determine how your posts are created. Simply select the options you require here.<p>

            <hr>
            <p><b>3 - API Importer</b></p>
            <p>Running the Importer calls the endpoint and saves the raw API data.<p>

            <hr>
            <p><b>4 - Data Mapping</b></p>
            <p>Map the API data to WordPress tags, custom fields, images & more.<p>
            <ul>
                <li><b><?php _e('Custom Field(s)', 'wpgetapi-api-to-posts'); ?></b> - There are 2 options for mapping to custom fields:</li>
                <li><b><?php _e('Custom Field', 'wpgetapi-api-to-posts'); ?></b> - creates a single custom field. Can be used for strings and array data. If the data contains an array, this array will be saved as a JSON string.</li>
                <li><b><?php _e('Custom Fields', 'wpgetapi-api-to-posts'); ?></b> - creates multiple custom fields. Should only be used if your data is an array. This will create multiple custom fields, 1 for each item within the array.</li>
            </ul> 

            <hr>
            <p><b>5 - Post Creator</b></p>
            <p>This will create/update/delete the posts. Items that were imported will be used to create the posts and will be based on the Data Mapping options that you have set.<p>
   
        </div>

        <!-- <div class="box">
            <strong><?php esc_html_e('Getting Help', 'wpgetapi'); ?></strong>
            <ul>
                <li><?php
                    printf(
                        esc_html__('View the %1$s', 'cmb2'),
                        '<a target="_blank" href="https://wpgetapi.com/docs/getting-started-api-importer/">API Importer Instructions</a>'
                    );
                    ?></li>
            </ul>
        </div> -->


<?php

    }



    /**
     * Get our saved items from options table.
     * @since  1.0.0
     */
    public function get_saved_items()
    {

        $items = array();

        $count = get_option('wpgetapi_' . $this->theid . '_items_count');

        // for legacy versions below 1.1.3 taht are still using unchunked version
        if ($count == 0 || !$count) {
            return get_option('wpgetapi_' . $this->theid . '_items');
        }

        if ($count == 1) {
            return get_option('wpgetapi_' . $this->theid . '_items_1');
        }

        $to_merge = array();
        for ($i = 1; $i <= $count; $i++) {
            $to_merge[] = get_option('wpgetapi_' . $this->theid . '_items_' . $i);
        }

        if (!empty($to_merge))
            $items = array_merge([], ...$to_merge);

        return $items;
    }


    /**
     * Insert arrays into arrays.
     * @since  1.0.0
     */
    public function _array_insert($array, $position, $insert)
    {
        if ($position > 0) {
            if ($position == 1) {
                array_unshift($array, array());
            } else {
                $position = $position - 1;
                array_splice($array, $position, 0, array(
                    ''
                ));
            }
            $array[$position] = $insert;
        }
        return $array;
    }


    public function array_flatten($array, $parent = false)
    {

        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {

                if ($parent)
                    $key = $parent . '_' . $key;

                $return = array_merge($return, $this->array_flatten($value, $key));
            } else {

                if ($parent) {
                    $return[$parent . '_' . $key] = $value;
                } else {
                    $return[$key] = $value;
                }
            }
        }
        return $return;
    }



    /**
     * Public getter method for retrieving protected/private variables
     * @since  0.1.0
     * @param  string  $field Field to retrieve
     * @return mixed          Field value or exception is thrown
     */
    public function __get($field)
    {
        // Allowed fields to retrieve
        if (in_array($field, array('key', 'fields', 'title', 'options_pages'), true)) {
            return $this->{$field};
        }
        if ('option_metabox' === $field) {
            return $this->option_fields();
        }
        throw new Exception('Invalid property: ' . $field);
    }



    /**
     * Render tabs
     *
     * @param array  $cmb_id      The current box ID
     * @param int    $object_id   The ID of the current object
     * @param string $object_type The type of object you are working with.
     * @param array  $cmb         This CMB2 object
     */
    public function before_form( $cmb_id, $object_id, $object_type, $cmb ) {

        if( strpos( $cmb_id, 'wpgetapi_importer_' ) !== false && $cmb->prop( 'tabs' ) && is_array( $cmb->prop( 'tabs' ) ) ) : ?>
            <div class="cmb-tabs-wrap cmb-tabs-<?php echo ( ( $cmb->prop( 'vertical_tabs' ) ) ? 'vertical' : 'horizontal' ) ?>">
                <div class="cmb-tabs">

                    <?php foreach( $cmb->prop( 'tabs' ) as $tab ) :
                        $fields_selector = array();

                        if( ! isset( $tab['id'] ) ) {
                            continue;
                        }

                        if( ! isset( $tab['fields'] ) ) {
                            $tab['fields'] = array();
                        }

                        if( ! is_array( $tab['fields'] ) ) {
                            $tab['fields'] = array();
                        }

                        foreach( $tab['fields'] as $tab_field )  :
                            $fields_selector[] = '.' . 'cmb2-id-' . str_replace( '_', '-', sanitize_html_class( $tab_field ) ) . ':not(.cmb2-tab-ignore)';
                        endforeach;

                        $fields_selector = apply_filters( 'cmb2_tabs_tab_fields_selector', $fields_selector, $tab, $cmb_id, $object_id, $object_type, $cmb );
                        $fields_selector = apply_filters( 'cmb2_tabs_tab_' . $tab['id'] . '_fields_selector', $fields_selector, $tab, $cmb_id, $object_id, $object_type, $cmb );
                        ?>

                        <div id="<?php echo $cmb_id . '-tab-' . $tab['id']; ?>" class="cmb-tab" data-fields="<?php echo implode( ', ', $fields_selector ); ?>">

                            <?php if( isset( $tab['icon'] ) && ! empty( $tab['icon'] ) ) :
                                $tab['icon'] = strpos($tab['icon'], 'dashicons') !== false ? 'dashicons ' . $tab['icon'] : $tab['icon']?>
                                <span class="cmb-tab-icon"><i class="<?php echo $tab['icon']; ?>"></i></span>
                            <?php endif; ?>

                            <?php if( isset( $tab['title'] ) && ! empty( $tab['title'] ) ) : ?>
                                <span class="cmb-tab-title"><?php echo $tab['title']; ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                </div> <!-- .cmb-tabs -->
        <?php endif;
    }

    /**
     * Close tabs
     *
     * @param array  $cmb_id      The current box ID
     * @param int    $object_id   The ID of the current object
     * @param string $object_type The type of object you are working with.
     * @param array  $cmb         This CMB2 object
     */
    public function after_form( $cmb_id, $object_id, $object_type, $cmb ) {
        if( strpos( $cmb_id, 'wpgetapi_importer_' ) !== false && $cmb->prop( 'tabs' ) && is_array( $cmb->prop( 'tabs' ) ) ) : ?>
            </div> <!-- .cmb-tabs-wrap -->
        <?php endif;
    }



}

/**
 * Helper function to get/return the WpGetApi_Admin_Options object
 * @since  0.1.0
 * @return WpGetApi_Admin_Options object
 */
function wpgetapi_importer_admin_options()
{
    return WpGetApi_Api_To_Posts_Admin_Options::get_instance();
}

// Get it started
wpgetapi_importer_admin_options();
