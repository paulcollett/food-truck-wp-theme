<?php 

// active
acf_render_field_wrap(array(
	'label'			=> __('Status','food-truck'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'active',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['active'],
	'choices' 		=> array(
		1				=>	__("Active",'food-truck'),
		0				=>	__("Disabled",'food-truck'),
	)
));


// style
acf_render_field_wrap(array(
	'label'			=> __('Style','food-truck'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'style',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['style'],
	'choices' 		=> array(
		'default'			=>	__("Standard (WP metabox)",'food-truck'),
		'seamless'			=>	__("Seamless (no metabox)",'food-truck'),
	)
));


// position
acf_render_field_wrap(array(
	'label'			=> __('Position','food-truck'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'position',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['position'],
	'choices' 		=> array(
		'acf_after_title'	=> __("High (after title)",'food-truck'),
		'normal'			=> __("Normal (after content)",'food-truck'),
		'side' 				=> __("Side",'food-truck'),
	),
	'default_value'	=> 'normal'
));


// label_placement
acf_render_field_wrap(array(
	'label'			=> __('Label placement','food-truck'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'label_placement',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['label_placement'],
	'choices' 		=> array(
		'top'			=>	__("Top aligned",'food-truck'),
		'left'			=>	__("Left Aligned",'food-truck'),
	)
));


// instruction_placement
acf_render_field_wrap(array(
	'label'			=> __('Instruction placement','food-truck'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'instruction_placement',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['instruction_placement'],
	'choices' 		=> array(
		'label'		=>	__("Below labels",'food-truck'),
		'field'		=>	__("Below fields",'food-truck'),
	)
));


// menu_order
acf_render_field_wrap(array(
	'label'			=> __('Order No.','food-truck'),
	'instructions'	=> __('Field groups with a lower order will appear first','food-truck'),
	'type'			=> 'number',
	'name'			=> 'menu_order',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['menu_order'],
));


// description
acf_render_field_wrap(array(
	'label'			=> __('Description','food-truck'),
	'instructions'	=> __('Shown in field group list','food-truck'),
	'type'			=> 'text',
	'name'			=> 'description',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['description'],
));


// hide on screen
acf_render_field_wrap(array(
	'label'			=> __('Hide on screen','food-truck'),
	'instructions'	=> __('<b>Select</b> items to <b>hide</b> them from the edit screen.','food-truck') . '<br /><br />' . __("If multiple field groups appear on an edit screen, the first field group's options will be used (the one with the lowest order number)",'food-truck'),
	'type'			=> 'checkbox',
	'name'			=> 'hide_on_screen',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['hide_on_screen'],
	'toggle'		=> true,
	'choices' => array(
		'permalink'			=>	__("Permalink", 'food-truck'),
		'the_content'		=>	__("Content Editor",'food-truck'),
		'excerpt'			=>	__("Excerpt", 'food-truck'),
		'custom_fields'		=>	__("Custom Fields", 'food-truck'),
		'discussion'		=>	__("Discussion", 'food-truck'),
		'comments'			=>	__("Comments", 'food-truck'),
		'revisions'			=>	__("Revisions", 'food-truck'),
		'slug'				=>	__("Slug", 'food-truck'),
		'author'			=>	__("Author", 'food-truck'),
		'format'			=>	__("Format", 'food-truck'),
		'page_attributes'	=>	__("Page Attributes", 'food-truck'),
		'featured_image'	=>	__("Featured Image", 'food-truck'),
		'categories'		=>	__("Categories", 'food-truck'),
		'tags'				=>	__("Tags", 'food-truck'),
		'send-trackbacks'	=>	__("Send Trackbacks", 'food-truck'),
	)
));


// 3rd party settings
do_action('acf/render_field_group_settings', $field_group);
		
?>
<div class="acf-hidden">
	<input type="hidden" name="acf_field_group[key]" value="<?php echo $field_group['key']; ?>" />
</div>
<script type="text/javascript">
if( typeof acf !== 'undefined' ) {
		
	acf.postbox.render({
		'id': 'acf-field-group-options',
		'label': 'left'
	});	

}
</script>
