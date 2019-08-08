<?php
/**
 * Template Name: Template Box
 *
 * @package WordPress
 * @subpackage Funiter
 * @since Funiter 1.0
 */
?>
<?php
echo '<div class="box-template">';
get_header();
?>
    <div class="fullwidth-template">
        <div class="container">
			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
			?>
        </div>
    </div>
<?php
get_footer();
echo '</div>';