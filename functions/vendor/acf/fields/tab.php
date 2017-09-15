<?php

/*
*  ACF Tab Field Class
*
*  All the logic for this field type
*
*  @class 		acf_field_tab
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_field_tab') ) :

class acf_field_tab extends acf_field {
	
	
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
		$this->name = 'tab';
		$this->label = __("Tab",'food-truck');
		$this->category = 'layout';
		$this->defaults = array(
			'value'		=> false, // prevents ACF from attempting to load value
			'placement'	=> 'top',
			'endpoint'	=> 0 // added in 5.2.8
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
		$atts = array(
			'class'				=> 'acf-tab',
			'data-placement'	=> $field['placement'],
			'data-endpoint'		=> $field['endpoint']
		);
		
		?>
		<div <?php acf_esc_attr_e( $atts ); ?>><?php echo $field['label']; ?></div>
		<?php
		
		
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
		
		// message
		$message = '';
		$message .= '<span class="acf-error-message"><p>' . __("The tab field will display incorrectly when added to a Table style repeater field or flexible content field layout", 'food-truck') . '</p></span>';
		$message .= '<p>' . __( 'Use "Tab Fields" to better organize your edit screen by grouping fields together.', 'food-truck') . '</p>';
		$message .= '<p>' . __( 'All fields following this "tab field" (or until another "tab field" is defined) will be grouped together using this field\'s label as the tab heading.','food-truck') . '</p>';
		
		// default_value
		acf_render_field_setting( $field, array(
			'label'			=> __('Instructions','food-truck'),
			'instructions'	=> '',
			'type'			=> 'message',
			'message'		=> $message,
			'new_lines'		=> ''
		));
		
		
		// preview_size
		acf_render_field_setting( $field, array(
			'label'			=> __('Placement','food-truck'),
			'type'			=> 'select',
			'name'			=> 'placement',
			'choices' 		=> array(
				'top'			=>	__("Top aligned",'food-truck'),
				'left'			=>	__("Left Aligned",'food-truck'),
			)
		));
		
		
		// endpoint
		acf_render_field_setting( $field, array(
			'label'			=> __('End-point','food-truck'),
			'instructions'	=> __('Use this field as an end-point and start a new group of tabs','food-truck'),
			'type'			=> 'radio',
			'name'			=> 'endpoint',
			'choices'		=> array(
				1				=> __("Yes",'food-truck'),
				0				=> __("No",'food-truck'),
			),
			'layout'	=>	'horizontal',
		));
				
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field ) {
		
		// remove name
		$field['name'] = '';
		$field['required'] = 0;
		
		
		// return
		return $field;
	}
	
}


// initialize
acf_register_field_type( new acf_field_tab() );

endif; // class_exists check

?>
