<?php
if(!is_active_sidebar( 'footer-widgets' )){
	return;
}
?>

<aside class="widgets-layout" role="complementary" >
  <?php dynamic_sidebar( 'footer-widgets' ); ?>
</aside>
