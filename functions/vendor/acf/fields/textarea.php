<?php

/*
*  ACF Text Area Field Class
*
*  All the logic for this field type
*
*  @class 		acf_field_textarea
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_field_textarea') ) :

class acf_field_textarea extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
		
		// vars
		$this->name = 'textarea';
		$this->label = __("Text Area",'food-truck');
		$this->defaults = array(
			'default_value'	=> '',
			'new_lines'		=> '',
			'maxlength'		=> '',
			'placeholder'	=> '',
			'rows'			=> ''
		);
		
		
		// do not delete!
    	parent::__construct();
	}
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function render_field( $field ) {
		
		// vars
		$atts = array();
		$o = array( 'id', 'class', 'name', 'placeholder', 'rows' );
		$s = array( 'readonly', 'disabled' );
		$e = '';
		
		
		// maxlength
		if( $field['maxlength'] ) {
		
			$o[] = 'maxlength';
			
		}
		
		
		// rows
		if( empty($field['rows']) ) {
		
			$field['rows'] = 8;
			
		}
		
		
		// append atts
		foreach( $o as $k ) {
		
			$atts[ $k ] = $field[ $k ];	
			
		}
		
		
		// append special atts
		foreach( $s as $k ) {
		
			if( !empty($field[ $k ]) ) $atts[ $k ] = $k;
			
		}
		

		$e .= '<textarea ' . acf_esc_attr( $atts ) . ' >';
		$e .= esc_textarea( $field['value'] );
		$e .= '</textarea>';
		
		
		// return
		echo $e;
		
	}
	
	/*
	*  render_field_settings()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @param	$field	- an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function render_field_settings( $field ) {
		
		// ACF4 migration
		if( empty($field['ID']) ) {
			
			$field['new_lines'] = 'wpautop';
			
		}
		
		
		// default_value
		acf_render_field_setting( $field, array(
			'label'			=> __('Default Value','food-truck'),
			'instructions'	=> __('Appears when creating a new post','food-truck'),
			'type'			=> 'textarea',
			'name'			=> 'default_value',
		));
		
		
		// placeholder
		acf_render_field_setting( $field, array(
			'label'			=> __('Placeholder Text','food-truck'),
			'instructions'	=> __('Appears within the input','food-truck'),
			'type'			=> 'text',
			'name'			=> 'placeholder',
		));
		
		
		// maxlength
		acf_render_field_setting( $field, array(
			'label'			=> __('Character Limit','food-truck'),
			'instructions'	=> __('Leave blank for no limit','food-truck'),
			'type'			=> 'number',
			'name'			=> 'maxlength',
		));
		
		
		// rows
		acf_render_field_setting( $field, array(
			'label'			=> __('Rows','food-truck'),
			'instructions'	=> __('Sets the textarea height','food-truck'),
			'type'			=> 'number',
			'name'			=> 'rows',
			'placeholder'	=> 8
		));
		
		
		// formatting
		acf_render_field_setting( $field, array(
			'label'			=> __('New Lines','food-truck'),
			'instructions'	=> __('Controls how new lines are rendered','food-truck'),
			'type'			=> 'select',
			'name'			=> 'new_lines',
			'choices'		=> array(
				'wpautop'		=> __("Automatically add paragraphs",'food-truck'),
				'br'			=> __("Automatically add &lt;br&gt;",'food-truck'),
				''				=> __("No Formatting",'food-truck')
			)
		));
		
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value or not for template
		if( empty($value) || !is_string($value) ) {
			
			return $value;
		
		}
				
		
		// new lines
		if( $field['new_lines'] == 'wpautop' ) {
			
			$value = wpautop($value);
			
		} elseif( $field['new_lines'] == 'br' ) {
			
			$value = nl2br($value);
			
		}
		
		
		// return
		return $value;
	}
	
}


// initialize
acf_register_field_type( new acf_field_textarea() );

endif; // class_exists check

?>
