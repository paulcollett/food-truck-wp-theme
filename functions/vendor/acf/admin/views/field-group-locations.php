<?php 

// vars
$rule_types = apply_filters('acf/location/rule_types', array(
	__("Post",'food-truck') => array(
		'post_type'		=>	__("Post Type",'food-truck'),
		'post_status'	=>	__("Post Status",'food-truck'),
		'post_format'	=>	__("Post Format",'food-truck'),
		'post_category'	=>	__("Post Category",'food-truck'),
		'post_taxonomy'	=>	__("Post Taxonomy",'food-truck'),
		'post'			=>	__("Post",'food-truck')
	),
	__("Page",'food-truck') => array(
		'page_template'	=>	__("Page Template",'food-truck'),
		'page_type'		=>	__("Page Type",'food-truck'),
		'page_parent'	=>	__("Page Parent",'food-truck'),
		'page'			=>	__("Page",'food-truck')
	),
	__("User",'food-truck') => array(
		'current_user'		=>	__("Current User",'food-truck'),
		'current_user_role'	=>	__("Current User Role",'food-truck'),
		'user_form'			=>	__("User Form",'food-truck'),
		'user_role'			=>	__("User Role",'food-truck')
	),
	__("Forms",'food-truck') => array(
		'attachment'	=>	__("Attachment",'food-truck'),
		'taxonomy'		=>	__("Taxonomy Term",'food-truck'),
		'comment'		=>	__("Comment",'food-truck'),
		'widget'		=>	__("Widget",'food-truck')
	)
));

$rule_operators = apply_filters( 'acf/location/rule_operators', array(
	'=='	=>	__("is equal to",'food-truck'),
	'!='	=>	__("is not equal to",'food-truck'),
));
						
?>
<div class="acf-field">
	<div class="acf-label">
		<label><?php _e("Rules",'food-truck'); ?></label>
		<p><?php _e("Create a set of rules to determine which edit screens will use these advanced custom fields",'food-truck'); ?></p>
	</div>
	<div class="acf-input">
		<div class="rule-groups">
			
			<?php foreach( $field_group['location'] as $group_id => $group ): 
				
				// validate
				if( empty($group) ) {
				
					continue;
					
				}
				
				
				// $group_id must be completely different to $rule_id to avoid JS issues
				$group_id = "group_{$group_id}";
				$h4 = ($group_id == "group_0") ? __("Show this field group if",'acf') : __("or",'food-truck');
				
				?>
			
				<div class="rule-group" data-id="<?php echo $group_id; ?>">
				
					<h4><?php echo $h4; ?></h4>
					
					<table class="acf-table -clear">
						<tbody>
							<?php foreach( $group as $rule_id => $rule ): 
								
								// valid rule
								$rule = wp_parse_args( $rule, array(
									'field'		=>	'',
									'operator'	=>	'==',
									'value'		=>	'',
								));
								
															
								// $group_id must be completely different to $rule_id to avoid JS issues
								$rule_id = "rule_{$rule_id}";
								
								?>
								<tr data-id="<?php echo $rule_id; ?>">
								<td class="param"><?php 
									
									// create field
									acf_render_field(array(
										'type'		=> 'select',
										'prefix'	=> "acf_field_group[location][{$group_id}][{$rule_id}]",
										'name'		=> 'param',
										'value'		=> $rule['param'],
										'choices'	=> $rule_types,
										'class'		=> 'location-rule-param'
									));
		
								?></td>
								<td class="operator"><?php 	
									
									// create field
									acf_render_field(array(
										'type'		=> 'select',
										'prefix'	=> "acf_field_group[location][{$group_id}][{$rule_id}]",
										'name'		=> 'operator',
										'value'		=> $rule['operator'],
										'choices' 	=> $rule_operators,
										'class'		=> 'location-rule-operator'
									)); 	
									
								?></td>
								<td class="value"><?php 
									
									$this->render_location_value(array(
										'group_id'	=> $group_id,
										'rule_id'	=> $rule_id,
										'value'		=> $rule['value'],
										'param'		=> $rule['param'],
										'class'		=> 'location-rule-value'
									)); 
									
								?></td>
								<td class="add">
									<a href="#" class="button add-location-rule"><?php _e("and",'food-truck'); ?></a>
								</td>
								<td class="remove">
									<a href="#" class="acf-icon -minus remove-location-rule"></a>
								</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					
				</div>
			<?php endforeach; ?>
			
			<h4><?php _e("or",'food-truck'); ?></h4>
			
			<a href="#" class="button add-location-group"><?php _e("Add rule group",'food-truck'); ?></a>
			
		</div>
	</div>
</div>
<script type="text/javascript">
if( typeof acf !== 'undefined' ) {
		
	acf.postbox.render({
		'id': 'acf-field-group-locations',
		'label': 'left'
	});	

}
</script>
