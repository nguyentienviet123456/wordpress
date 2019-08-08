<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$enable_header_mini_cart_mobile      = Funiter_Functions::funiter_get_option( 'enable_header_mini_cart_mobile' );
$enable_header_product_search_mobile = Funiter_Functions::funiter_get_option( 'enable_header_product_search_mobile' );
$enable_wishlist_mobile              = Funiter_Functions::funiter_get_option( 'enable_wishlist_mobile' );
$enable_lang_mobile                  = Funiter_Functions::funiter_get_option( 'enable_lang_mobile' );
$has_search                          = 'no-search';
if ( $enable_header_product_search_mobile ) {
	$has_search = '';
}
?>
<header class="header-mobile <?php echo esc_attr( $has_search ); ?>">
    <div class="main-header">
        <a href="#" class="mobile-hamburger-navigation">
            <div class="hamburger hamburger--collapse js-hamburger">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
        </a>
        <div class="logo">
			<?php funiter_get_logo_mobile(); ?>
        </div>
        <div class="header-mobile-right">
			<?php
			if ( $enable_header_mini_cart_mobile && class_exists( 'WooCommerce' ) ) {
				funiter_header_cart_link();
			}
			
			?>

        </div>
    </div>
	<?php if ( $enable_header_product_search_mobile && class_exists( 'WooCommerce' ) ) { ?>
        <div class="header-middle">
			<?php funiter_search_form(); ?>
        </div>
	<?php } ?>
    <div id="box-mobile-menu" class="box-mobile-menu full-height box-mobile-menu-tabs box-tabs">
        <div class="box-mibile-overlay"></div>
        <div class="box-mobile-menu-inner">
            <div id="mobile-menu-content-tab"
                 class="box-inner mn-mobile-content-tab box-tab-content active">
                <div class="box-inner-content">
                    <div class="mobile-back-nav-wrap">
                        <a href="#" id="back-menu" class="back-menu"><i
                                    class="fa fa-angle-left"></i></a>
                        <span class="box-title"><?php echo esc_html__( 'Menu', 'funiter' ); ?></span>
                    </div>
					
					<?php
					wp_nav_menu( array(
						             'menu'            => 'primary',
						             'theme_location'  => 'primary',
						             'depth'           => 3,
						             'container'       => '',
						             'container_class' => '',
						             'container_id'    => '',
						             'menu_class'      => 'clone-main-menu funiter-nav main-menu',
						             'fallback_cb'     => 'Funiter_navwalker::fallback',
						             'walker'          => new Funiter_navwalker(),
					             )
					);
					
					if ( $enable_wishlist_mobile ) {
						$wish_list_url = funiter_wisth_list_url();
						if ( trim( $wish_list_url ) != '' ) {
							echo '<div class="wish-list-mobile-menu-link-wrap"><a href="' . esc_url( $wish_list_url ) . '" class="wish-list-mobile-menu-link">' . esc_html__( 'My Wishlist', 'funiter' ) . '</a> <span class="flaticon-heart-1"></span></div>';
						}
					}

					
					?>
                    <?php if ( $enable_lang_mobile ) { ?>
                        <ul class="header-lang-mobile">
                            <?php funiter_header_language(); ?> 
                        </ul>
                    <?php } ?>
                </div>
            </div>

            <div id="mobile-login-content-tab" class="box-inner mn-mobile-content-tab box-tab-content">
                <div class="my-account-wrap">
					<?php
					if ( shortcode_exists( 'woocommerce_my_account' ) ) {
						echo do_shortcode( '[woocommerce_my_account]' );
					}
					?>
                </div>
            </div>

            <div class="box-tabs-nav-wrap">
                <div class="box-tabs-nav">
                    <a href="#mobile-menu-content-tab" class="box-tab-nav active">
                        <div class="hamburger hamburger--collapse js-hamburger">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                        <span class="nav-text"><?php esc_html_e( 'Menu', 'funiter' ); ?></span>
                    </a>
                    <a href="#mobile-login-content-tab"
                       class="box-tab-nav">
                        <i class="flaticon-profile"></i>
                        <div class="nav-text account-text">
							<?php
							if ( is_user_logged_in() ) {
								esc_html_e( 'My Account', 'funiter' );
							} else {
								esc_html_e( 'Login', 'funiter' );
							}
							?>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
</header><!-- /header -->