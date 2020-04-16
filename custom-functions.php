<?php

/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) {

			 // Woocommerc PHOTOSWIPE deregister, register new location
			 add_action( 'wp_enqueue_scripts', 'load_photoswipe_scripts' );			
			} 
			 
			 else { return false; }
	}
}
 
function load_photoswipe_scripts() {
	global $wp_scripts; 
	$wp_scripts->registered[ 'photoswipe' ]->src = get_template_directory_uri() . 'assets/js/photoswipe/lib/photoswipe.js';
	$wp_scripts->registered[ 'photoswipe-ui-default' ]->src = get_template_directory_uri() . 'assets/js/photoswipe/lib/photoswipe-ui-default.js';
	}
	
add_filter( 'woocommerce_add_to_cart_fragments', 'cart_count_fragments', 10, 1 );
 
function cart_count_fragments( $fragments ) {

	// cart list
	ob_start();
	get_template_part( 'includes/shopdock' );
	$shopdock = ob_get_clean();

	global $woocommerce;

	$fragments['#shopdock-ultra'] = $shopdock;
	$fragments['.check-cart'] = sprintf( '<span class="%s"></span>', WC()->cart->get_cart_contents_count() > 0 ? 'check-cart show-count' : 'check-cart' );    
    $fragments['#cart-icon span'] = sprintf( '<span>%s</span>', WC()->cart->get_cart_contents_count() );
    
    return $fragments;
    
} 
// Add specific CSS class by filter.
function slide_cart_body_class( $classes ) {
	
		$classes[] = 'slide-cart';
	

	return $classes;
}
add_filter( 'body_class', 'slide_cart_body_class' );

/*
Add Custom Post Types shortcode
*/
function my_form_shortcode() {
	ob_start();
	get_template_part('includes/posts');
	return ob_get_clean();   
 } 
 add_shortcode( 'shortcoderecipe', 'my_form_shortcode' );

?>

