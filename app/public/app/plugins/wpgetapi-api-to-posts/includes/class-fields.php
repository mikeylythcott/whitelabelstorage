<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Handles custom field typea.
 */
class WpGetApi_Post_Import_Mapping_Field extends CMB2_Type_Base {


	public static function init_mapping() {
		add_filter( 'cmb2_render_class_mapping', array( __CLASS__, 'class_name' ) );
		add_filter( 'cmb2_sanitize_mapping', array( __CLASS__, 'maybe_save_split_values' ), 12, 4 );
		/**
		 * The following snippets are required for allowing the users field
		 * to work as a repeatable field, or in a repeatable group
		 */
		add_filter( 'cmb2_sanitize_mapping', array( __CLASS__, 'sanitize' ), 10, 5 );
		add_filter( 'cmb2_types_esc_mapping', array( __CLASS__, 'escape' ), 10, 4 );
	}

	public static function class_name() { return __CLASS__; }
	

	/**
	 * Handles outputting the users field.
	 */
	public function render() {

		// make sure we assign each part of the value we need.
		$value = wp_parse_args( $this->field->escaped_value(), array(
			'name' 	=> '',
			'value' => '',
			'prefix' => '',
			'att_name' => '',
			'step_down' => '',
		) );

		// display extra field for meta custom name
		$display = $value['name'] === '_meta' && $value['value'] !== '' ? 'inline-block' : 'none';

		// display extra field for images prefix
		$display = strpos( $value['name'], '_image' ) !== false && $value['prefix'] !== '' ? 'inline-block' : 'none';

		// display extra field for images prefix
		$display = strpos( $value['name'], '_attribute' ) !== false && $value['att_name'] !== '' ? 'inline-block' : 'none';

		ob_start();
		// Do html
		?>

		<div class="name input-wrap">

			<?php echo $this->types->select( array(
				'name'  => $this->_name( '[name]' ),
				'id'    => $this->_id( '_name' ),
				'value' => $value['name'],
				'options_cb' => 'wpgetapi_post_import_get_mapping_options',
			) ); ?>
			<p class="cmb2-metabox-description"><?php _e( 'Map <b>' . $this->field->args['item_key'] . '</b> to a WordPress field.', 'wpgetapi-post-import' ); ?></p>
		</div><!--
		--><div class="value input-wrap" style="display:<?php echo esc_attr( $display ); ?>">
			<?php echo $this->types->input( array(
				'name'  => $this->_name( '[value]' ),
				'id'    => $this->_id( '_value' ),
				'value' => $value['value'],
				'placeholder'  => __( '', 'wpgetapi' ),
			) ); ?>
			<p class="cmb2-metabox-description"><?php _e( 'Name the custom field.', 'wpgetapi-post-import' ); ?></p>
		</div>
		<div class="prefix input-wrap" style="display:<?php echo esc_attr( $display ); ?>">
			<?php echo $this->types->input( array(
				'name'  => $this->_name( '[prefix]' ),
				'id'    => $this->_id( '_prefix' ),
				'value' => $value['prefix'],
				'placeholder'  => __( 'https://', 'wpgetapi' ),
			) ); ?>
			<p class="cmb2-metabox-description"><?php _e( 'Prefix the image URL(s) if required.', 'wpgetapi-post-import' ); ?></p>
		</div>
		<div class="att_name input-wrap" style="display:<?php echo esc_attr( $display ); ?>">
			<?php echo $this->types->input( array(
				'name'  => $this->_name( '[att_name]' ),
				'id'    => $this->_id( '_att_name' ),
				'value' => $value['att_name'],
				'placeholder'  => __( '', 'wpgetapi' ),
			) ); ?>
			<p class="cmb2-metabox-description"><?php _e( 'Name of the attribute.', 'wpgetapi-post-import' ); ?></p>
		</div>

		<?php if( is_array( $this->field->args['item_value'] ) ) { ?>
			<div class="step_down input-wrap" style="">
				<?php echo $this->types->input( array(
					'name'  => $this->_name( '[step_down]' ),
					'id'    => $this->_id( '_step_down' ),
					'value' => $value['step_down'],
					'placeholder'  => __( '{first_key|next_key}', 'wpgetapi' ),
				) ); ?>
				<p class="cmb2-metabox-description"><?php _e( 'Step down into array.', 'wpgetapi-post-import' ); ?></p>
			</div>
		<?php } ?>

		<?php wpgetapi_pp( $this->field->args['item_value'] ); ?>
		<?php

		// grab the data from the output buffer.
		return $this->rendered( ob_get_clean() );
	}


	/**
	 * Optionally save the values into separate fields
	 */
	public static function maybe_save_split_values( $override_value, $value, $object_id, $field_args ) {

		// Don't do the override
		if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] )
			return $override_value;
	
		//$encryption = new WpGetApi_Encryption();

		$mapping_keys = array( 'name', 'value', 'prefix', 'att_name', 'step_down' );
		foreach ( $mapping_keys as $key ) {
			if ( ! empty( $value[ $key ] ) ) {
				//$updated_value = $encryption->encrypt( $value[ $key ] );
				update_post_meta( $object_id, $field_args['id'] . 'mapping_'. $key, $value[ $key ] );
			}
		}

		remove_filter( 'cmb2_sanitize_mapping', array( __CLASS__, 'sanitize' ), 10, 5 );
		// Tell CMB2 we already did the update
		return true;
	}


	public static function sanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {
		
		// if not repeatable, bail out.
		if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] )
			return $check;

		//$encryption = new WpGetApi_Encryption();

		foreach ( $meta_value as $i => $param ) {

			// if both empty, unset and continue
			if( 
				( $param['name'] == '' && $param['value'] == '' ) || 
				( $param['name'] == '' && $param['prefix'] == '' ) ||
				( $param['name'] == '' && $param['att_name'] == '' ) ||
				( $param['name'] == '' && $param['step_down'] == '' ) 
			) {
				unset( $meta_value[ $i ] );
				continue;
			}

			foreach ( $param as $key => $val ) {
				$meta_value[ $i ][ $key ] = $val;
			}

		}
		
		return array_filter($meta_value);
	}


	public static function escape( $check, $meta_value, $field_args, $field_object ) {

		// if not repeatable, bail out.
		if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] )
			return $check;

		//$encryption = new WpGetApi_Encryption();

		foreach ( $meta_value as $i => $param ) {

			// if both empty, unset and continue
			if( 
				( $param['name'] == '' && $param['value'] == '' ) || 
				( $param['name'] == '' && $param['prefix'] == '' ) ||
				( $param['name'] == '' && $param['att_name'] == '' ) ||
				( $param['name'] == '' && $param['step_down'] == '' ) 
			) {
				unset( $meta_value[ $i ] );
				continue;
			}

			foreach ( $param as $key => $val ) {
				$meta_value[ $i ][ $key ] = $val;
			}

		}

		return array_filter($meta_value);
	}


}