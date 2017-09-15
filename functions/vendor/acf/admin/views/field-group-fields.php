<?php 

// vars
$fields = false;
$layout = false;
$parent = 0;
$clone = false;


// use fields if passed in
extract( $args );

?>
<div class="acf-field-list-wrap">
	
	<ul class="acf-hl acf-thead">
		<li class="li-field-order"><?php _e('Order','food-truck'); ?></li>
		<li class="li-field-label"><?php _e('Label','food-truck'); ?></li>
		<li class="li-field-name"><?php _e('Name','food-truck'); ?></li>
		<li class="li-field-type"><?php _e('Type','food-truck'); ?></li>
	</ul>
	
	<div class="acf-field-list<?php if( $layout ){ echo " layout-{$layout}"; } ?>">	
		<?php
		
		if( $fields ) {
			
			foreach( $fields as $i => $field ) {
				
				acf_get_view('field-group-field', array( 'field' => $field, 'i' => $i ));
				
			}
		
		}
		
		?>
		<div class="no-fields-message" <?php if( $fields ){ echo 'style="display:none;"'; } ?>>
			<?php _e("No fields. Click the <strong>+ Add Field</strong> button to create your first field.",'food-truck'); ?>
		</div>
	</div>
	
	<ul class="acf-hl acf-tfoot">
		<li class="acf-fr">
			<a href="#" class="button button-primary button-large add-field"><?php _e('+ Add Field','food-truck'); ?></a>
		</li>
	</ul>
	
<?php if( !$parent ):
	
	// get clone
	$clone = acf_get_valid_field(array(
		'ID'		=> 'acfcloneindex',
		'key'		=> 'acfcloneindex',
		'label'		=> __('New Field','food-truck'),
		'name'		=> 'new_field',
		'type'		=> 'text'
	));
	
	?>
	<script type="text/html" id="tmpl-acf-field">
	<?php acf_get_view('field-group-field', array( 'field' => $clone )); ?>
	</script>
<?php endif;?>
	
</div>
