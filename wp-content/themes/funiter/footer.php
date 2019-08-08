<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Funiter
 * @since 1.0
 * @version 1.0
 */
?>
<?php
/**
 * Functions hooked into funiter_footer action
 *
 * @hooked funiter_footer_content            - 10
 */
do_action( 'funiter_footer' );
get_template_part( 'templates-part/popup', 'content' ); ?>
<a href="#" class="backtotop">
    <i class="fa fa-angle-up"></i>
</a> 
<?php wp_footer(); ?>
</body>
</html>
