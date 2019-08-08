<?php
/**
 * Name:  Footer style mobile
 **/
$class_footer_mobile  = '';
$enable_footer_mobile = Funiter_Functions::funiter_get_option( 'enable_footer_mobile' );
if( ($enable_footer_mobile == 1) && (funiter_is_mobile())){
    $class_footer_mobile ='footer-mobile';
}
?>
<footer class="footer style1 <?php echo esc_attr( $class_footer_mobile );?>">
    <div class="container">
        <?php the_content(); ?>
    </div>
</footer>