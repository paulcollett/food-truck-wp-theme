<?php
/*
 * Food Truck Theme Functions
 * https://github.com/paulcollett/food-truck-wp-theme
 * Paul Collett paulcollett.com
 *
 */

if ( ! version_compare( PHP_VERSION, '5.4', '>=' ) ) {
  add_action( 'admin_notices', 'ftt_fail_php_version' );
  add_action( 'after_switch_theme', 'ftt_restore_old_theme' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.5', '>=' ) ) {
  add_action( 'admin_notices', 'ftt_fail_wp_version' );
  add_action( 'after_switch_theme', 'ftt_restore_old_theme' );
} else {
	require get_parent_theme_file_path( 'inc/theme.php' );
}

function ftt_fail_php_version() {
	/* translators: %s: PHP version */
	$message = sprintf( esc_html__( 'Food Truck Theme requires PHP version %s+. Theme is NOT ACTIVE.', 'food-truck' ), '5.4' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

function ftt_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message = sprintf( esc_html__( 'Food Truck Theme requires WordPress version %s+. Because you are using an earlier version, the theme is NOT ACTIVE.', 'food-truck' ), '4.5' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

function ftt_restore_old_theme() {
  // Switch back to previous theme
  switch_theme(get_option('theme_switched'));
  // Not sure if needed
  return false;
}
