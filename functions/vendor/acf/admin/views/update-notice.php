<?php 

// defaults
$args = wp_parse_args($args, array(
	'button_url'	=> '',
	'button_text'	=> '',
	'confirm'		=> true
));

extract($args);

?>
<div id="acf-upgrade-notice" class="acf-cf">
	
	<div class="inner">
		
		<div class="acf-icon logo">
			<i class="acf-sprite-logo"></i>
		</div>
		
		<div class="content">
			
			<h2><?php _e("Database Upgrade Required",'food-truck'); ?></h2>
			
			<p><?php printf(__("Thank you for updating to %s v%s!",'food-truck'), acf_get_setting('name'), acf_get_setting('version') ); ?><br /><?php _e("Before you start using the new awesome features, please update your database to the newest version.", 'food-truck'); ?></p>
			
			<p><a id="acf-notice-action" href="<?php echo $button_url; ?>" class="button button-primary"><?php echo $button_text; ?></a></p>
			
		<?php if( $confirm ): ?>
			<script type="text/javascript">
			(function($) {
				
				$("#acf-notice-action").on("click", function(){
			
					var answer = confirm("<?php _e( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'food-truck'); ?>");
					return answer;
			
				});
				
			})(jQuery);
			</script>
		<?php endif; ?>
		
		</div>
		
	</div>
	
</div>
