<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Funiter
 * @since 1.0
 * @version 1.0
 */
?>
<!DOCTYPE html>
<?php 
$sticky_info_w              = '';
$enable_info_product_single  = Funiter_Functions::funiter_get_option( 'enable_info_product_single' );
if ( $enable_info_product_single == 1 ) {
	$sticky_info_w = 'sticky-info_single_wrap';
}
$menu_sticky ='';
$enable_sticky = Funiter_Functions::funiter_get_option( 'funiter_enable_sticky_menu' );
if ( $enable_sticky == 1 ){
	$menu_sticky ='wrapper_menu-sticky';
}
?>
<html <?php language_attributes(); ?> class="no-js no-svg <?php echo esc_attr( $menu_sticky ); ?> <?php echo esc_attr( $sticky_info_w ); ?>">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
    </head>
<body <?php body_class(); ?>>
<?php
/**
 * Functions hooked into funiter_footer action
 *
 * @hooked funiter_header_content                   - 10
 */
do_action( 'funiter_header' );