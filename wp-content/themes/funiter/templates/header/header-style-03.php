<?php
/**
 * Name:  Header style 03
 **/
?>
<?php
$funiter_icon           = Funiter_Functions::funiter_get_option( 'header_icon' );
$funiter_phone          = Funiter_Functions::funiter_get_option( 'header_phone' );
$funiter_text           = Funiter_Functions::funiter_get_option( 'header_text' );
$funiter_noitice_top    = Funiter_Functions::funiter_get_option( 'funiter_header_top' );
$enable_sticky          = Funiter_Functions::funiter_get_option( 'funiter_enable_sticky_menu' );
$enable_header_mobile   = Funiter_Functions::funiter_get_option( 'enable_header_mobile' );
$funiter_block_vertical  = Funiter_Functions::funiter_get_option( 'funiter_block_vertical_menu' );
$post_type             = '';
$id                    = '';
if ( isset( $post->ID ) ) {
	$id = $post->ID;
}
if ( isset( $post->post_type ) ) {
	$post_type = $post->post_type;
}
$class                  = array( 'header', 'style3' );
if ( $enable_sticky == 1 ):
	$class[] = 'header-sticky';
endif;
if ( is_array( $funiter_block_vertical ) && in_array( $id, $funiter_block_vertical ) && $post_type == 'page' ) {
	$class[] = 'vertical_always-open';
}
if( ($enable_header_mobile == 1) && (funiter_is_mobile())){ 
	get_template_part( 'templates/header', 'mobile' ); 
}else{ ?>
<header id="header" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
	<?php funiter_header_background(); ?>
	<div class="main-top">
		<?php if ( $funiter_noitice_top ) : ?>
		<div class="header-top-noitice">
			<div class="container">
				<div class="top-noitice-inner">
					<?php echo esc_html( $funiter_noitice_top ); ?>
					<span class="close-notice"></span>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="main-header">
		<?php if ( has_nav_menu( 'top_left_menu' ) || has_nav_menu( 'top_right_menu' ) ): ?>
	        <div class="header-top">
	            <div class="container">
	                <div class="header-top-inner">
						<?php
						if ( has_nav_menu( 'top_left_menu' ) ) {
							wp_nav_menu( array(
									'menu'            => 'top_left_menu',
									'theme_location'  => 'top_left_menu',
									'depth'           => 1,
									'container'       => '',
									'container_class' => '',
									'container_id'    => '',
									'menu_class'      => 'funiter-nav top-bar-menu',
									'fallback_cb'     => 'Funiter_navwalker::fallback',
									'walker'          => new Funiter_navwalker(),
								)
							);
						}
						if ( has_nav_menu( 'top_right_menu' ) ) {
							wp_nav_menu( array(
									'menu'            => 'top_right_menu',
									'theme_location'  => 'top_right_menu',
									'depth'           => 1,
									'container'       => '',
									'container_class' => '',
									'container_id'    => '',
									'menu_class'      => 'funiter-nav top-bar-menu right',
									'fallback_cb'     => 'Funiter_navwalker::fallback',
									'walker'          => new Funiter_navwalker(),
								)
							);
						}
						?>
	                </div>
	            </div>
	        </div>
			<?php endif; ?>

		    <div class="header-middle">
		        <div class="container">
		            <div class="header-middle-inner">
		                <div class="logo">
							<?php funiter_get_logo(); ?>
		                </div>
						<?php funiter_search_form(); ?>
		                <div class="header-control">
		                    <div class="header-control-inner">
		                        <div class="meta-woo">
		                            <div class="block-menu-bar">
		                                <a class="menu-bar menu-toggle" href="#">
		                                    <span></span>
		                                    <span></span>
		                                    <span></span>
		                                </a>
		                            </div>
									<?php
									funiter_user_link();
									do_action( 'funiter_header_wishlist' );
									do_action( 'funiter_header_mini_cart' );
									?>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div class="header-nav header-wrap-stick">
		        <div class="container">
		            <div class="header-nav-inner header-position">
						<?php funiter_header_vertical(); ?>
		                <div class="box-header-nav funiter-menu-wapper">
							<?php
							wp_nav_menu( array(
									'menu'            => 'primary',
									'theme_location'  => 'primary',
									'depth'           => 3,
									'container'       => '',
									'container_class' => '',
									'container_id'    => '',
									'menu_class'      => 'clone-main-menu funiter-clone-mobile-menu funiter-nav main-menu',
									'fallback_cb'     => 'Funiter_navwalker::fallback',
									'walker'          => new Funiter_navwalker(),
								)
							);
							?>
		                </div>
		                <?php if ((class_exists('Zopim')) || ( $funiter_phone ) ){ ?>
							<div class="header-contact-online">
							    <div class="header-contact-online-inner">
							        <?php if ((class_exists('Zopim')) || (class_exists('jivosite')) ) : ?>
							            <div class="line-chat"><span class="flaticon-comment"></span> <?php echo esc_html( 'Chat', 'funiter' ); ?></div>
							        <?php endif; ?>
									<?php if ( $funiter_phone ) : ?>
							            <div class="phone-online">
											<?php if ( $funiter_icon ) : ?>
							                    <span class="<?php echo esc_attr( $funiter_icon ); ?>"></span>
											<?php endif; ?>
							                <div class="online-number">
							                    <p class="contact-text"><?php echo esc_html( $funiter_text ); ?></p>
							                    <p class="contact-number"><?php echo esc_html( $funiter_phone ); ?></p>
							                </div>
							            </div>
									<?php endif; ?>
							    </div>
							</div>
						<?php } ?>
		            </div>
		        </div>
		    </div>
	    </div>
	</div>
	<div class="action-res">
		<div class="logo">
            <?php funiter_get_logo(); ?>
        </div> 
		
        <div class="meta-woo">
        	<div class="block-menu-bar">
	            <a class="menu-bar menu-toggle" href="#">
	                <span></span>
	                <span></span>
	                <span></span>
	            </a>
	        </div>
			<?php
			funiter_user_link();
			do_action( 'funiter_header_wishlist' );
			do_action( 'funiter_header_mini_cart' );
			?>
        </div>
        <?php funiter_search_form(); ?>
    </div>
</header>
<?php } ?>