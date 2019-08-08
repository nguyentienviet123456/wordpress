<?php
if ( !function_exists( 'funiter_custom_inline_css' ) ) {
	function funiter_custom_inline_css()
	{
		$css     = funiter_theme_color();
		$css     .= funiter_vc_custom_css_footer();
		$content = preg_replace( '/\s+/', ' ', $css );
		wp_add_inline_style( 'funiter-style', $content );
	}
}
add_action( 'wp_enqueue_scripts', 'funiter_custom_inline_css', 999 );
if ( !function_exists( 'funiter_theme_color' ) ) {
	function funiter_theme_color()
	{
		$main_color              = Funiter_Functions::funiter_get_option( 'funiter_main_color', '#a8854a' );
		$gradient_color_1        = Funiter_Functions::funiter_get_option( 'funiter_gradient_color_1', '#c467f5' );
        $gradient_color_1        = str_replace('#','',$gradient_color_1);
        $gradient_color_1        = '#'.$gradient_color_1;
		$gradient_color_2        = Funiter_Functions::funiter_get_option( 'funiter_gradient_color_2', '#53f3ff' );
        $gradient_color_2        = str_replace('#','',$gradient_color_2);
        $gradient_color_2        = '#'.$gradient_color_2;
		$funiter_page_404          = Funiter_Functions::funiter_get_option( 'funiter_page_404' );
		$page_faqs_product       = Funiter_Functions::funiter_get_option( 'funiter_add_page_product' );
		$funiter_enable_typography = Funiter_Functions::funiter_get_option( 'funiter_enable_typography' );
		$funiter_typography_group  = Funiter_Functions::funiter_get_option( 'typography_group' );
		$css                     = '';
		if ( $funiter_enable_typography == 1 && !empty( $funiter_typography_group ) ) {
			foreach ( $funiter_typography_group as $item ) {
				$css .= '
					' . $item['funiter_element_tag'] . '{
						font-family: ' . $item['funiter_typography_font_family']['family'] . ';
						font-weight: ' . $item['funiter_typography_font_family']['variant'] . ';
						font-size: ' . $item['funiter_typography_font_size'] . 'px;
						line-height: ' . $item['funiter_typography_line_height'] . 'px;
						color: ' . $item['funiter_body_text_color'] . ';
					}
				';
			}
		}
		$css .= 'a:hover, a:focus, a:active,
		.header-top-inner .top-bar-menu > .menu-item:hover > a > span,
		.header-top-inner .top-bar-menu > .menu-item:hover > a,
		.header-top-inner .top-bar-menu > .menu-item .wcml_currency_switcher .wcml-cs-submenu a:hover,
		.wcml-dropdown .wcml-cs-submenu li:hover > a {
			color: ' . $main_color . ';
		}
		blockquote, q {
			border-left: 4px solid ' . $main_color . ';
		}
		button:not(.pswp__button):hover, input[type="submit"]:hover, button:not(.pswp__button):focus, input[type="submit"]:focus {
			background: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		.owl-slick.nav-right .slick-arrow:hover,
		.owl-slick.nav-top-right .slick-arrow:hover {
			color: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		.owl-slick .slick-dots li button:hover,
		.owl-slick .slick-dots li.slick-active button,
		.owl-slick.dots-light .slick-dots li button:hover,
		.owl-slick.dots-light .slick-dots li.slick-active button,
		.wcml_currency_switcher:hover::before {
			background: ' . $main_color . ';
		}
		div.block-search .form-search .btn-submit {
			background: ' . $main_color . ';
		}
		.block-menu-bar .menu-bar:hover span {
			background-color: ' . $main_color . ';
		}
		.funiter-live-search-form.loading .search-box::before {
			border-top-color: ' . $main_color . ';
		}
		.funiter-live-search-form .keyword-current,
		span.total-price,
		.product_list_widget ins,
		.product_list_widget span.woocommerce-Price-amount.amount {
			color: ' . $main_color . ';
		}
		.woocommerce-mini-cart__buttons .button.checkout,
		.woocommerce-mini-cart__buttons .button:not(.checkout):hover {
			background: ' . $main_color . ';
		}
		.block-minicart .cart_list > .scroll-element .scroll-bar:hover {
			background-color: ' . $main_color . ';
		}
		.box-header-nav .main-menu .menu-item:hover > a {
			color: ' . $main_color . ';
		}
		.box-header-nav .main-menu > .menu-item > a::before {
			border-bottom: 2px solid ' . $main_color . ';
		}
		.box-header-nav .main-menu .menu-item .submenu .menu-item:hover > a,
		.box-header-nav .main-menu .menu-item:hover > .toggle-submenu,
		.box-header-nav .main-menu > .menu-item.menu-item-right:hover > a {
			color: ' . $main_color . ';
		}
		.block-nav-category .block-title {
			background: ' . $main_color . ';
		}
		.block-nav-category .block-content {
			border: 2px solid ' . $main_color . ';
		}
		.phone-online {
			background: ' . $main_color . ';
		}
		.box-header-menu {
			background: ' . $main_color . ';
		}
		.box-header-menu .gradient-menu .menu-item:hover > a {
			color: ' . $main_color . ';
		}
		.header.style2 div.block-search .form-search .btn-submit {
			background: ' . $main_color . ';
		}
		.header.style2 .block-nav-category .block-title .before {
			background: ' . $main_color . ';
		}
		.style3 div.block-search .form-search .btn-submit {
			background: ' . $main_color . ';
		}
		.header.style4 .phone-online {
			color: ' . $main_color . ';
		}
		.header.style4 .line-chat,
		.header.style5 .header-nav {
			background: ' . $main_color . ';
		}
		.header.style5 .phone-online {
			color: ' . $main_color . ';
		}
		.header.style6 .line-chat,
		.header.style6 .block-nav-category .block-title,
		.header.style5 .header-nav-inner {
			background: ' . $main_color . ';
		}
		.header.style6 .phone-online {
			color: ' . $main_color . ';
		}
		.header-mobile-right .block-cart-link a.link-dropdown .count {
			background-color: ' . $main_color . ';
		}
		.box-mobile-menu-tabs .mobile-back-nav-wrap,
		a.backtotop,
		.post-date {
			background: ' . $main_color . ';
		}
		.content-post .post-title::before {
			border-bottom: 1px solid ' . $main_color . ';
		}
		.content-post:not(.blog-grid) .info-meta .post-author a:hover,
		.post-standard + .post-footer .tags a:hover, .single-default div.single-tags a:hover {
			color: ' . $main_color . ';
		}
		div.single-tags a:hover {
			color: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		.post-item .post-title::before {
			border-bottom: 1px solid ' . $main_color . ';
		}
		div .date a:hover,
		div.funiter-mapper .funiter-pin .funiter-wc-price .amount,
		.post-categories a:hover {
			color: ' . $main_color . ';
		}
		.funiter-share-socials a:hover {
			border-color: ' . $main_color . ';
			color: ' . $main_color . ';
		}
		.post-single-author .author-info a:hover {
			background: ' . $main_color . ';
		}
		.info-meta a:hover {
			color: ' . $main_color . ';
		}
		.categories-product-woo .owl-slick .slick-arrow:hover {
			background: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		.prdctfltr_wc .prdctfltr_filter_title > span.prdctfltr_woocommerce_filter_title:hover,
		.main-content .prdctfltr_wc span.prdctfltr_reset label {
			background: ' . $main_color . ';
		}
		#widget-area .widget .prdctfltr_filter label.prdctfltr_active:not(.screen-reader-text),
		#widget-area .widget .prdctfltr_filter label:not(.screen-reader-text):hover {
			color: ' . $main_color . ';
		}
		div.prdctfltr_wc.prdctfltr_round .prdctfltr_filter label.prdctfltr_active > span::before {
			border-color: ' . $main_color . ';
			background: ' . $main_color . ';
		}
		span.prdctfltr_title_selected:hover,
		div.prdctfltr_wc.prdctfltr_woocommerce .prdctfltr_buttons span label:hover {
			background: ' . $main_color . ';
		}
		div.pf_rngstyle_flat .irs-from::after, div.pf_rngstyle_flat .irs-to::after, div.pf_rngstyle_flat .irs-single::after {
			border-top-color: ' . $main_color . ';
		}
		div.pf_rngstyle_flat .irs-from, div.pf_rngstyle_flat .irs-to, div.pf_rngstyle_flat .irs-single,
		div.pf_rngstyle_flat .irs-bar {
			background: ' . $main_color . ';
		}
		.price {
			display: inline-block;
			color: ' . $main_color . ';
		}
		.price ins,
		.yith-wcqv-button {
			color: ' . $main_color . ';
		}
		#yith-quick-view-close:hover {
			background: ' . $main_color . ';
		}
		.style-2 .quickview-item, .style-2 .compare-button, .style-2 .add-to-cart,
		.style-1 .quickview-item, .style-1 .compare-button, .style-1 .add-to-cart {
			color: ' . $main_color . ';
		}
		.style-1 .quickview-item:hover, .style-1 .compare-button:hover, .style-1 .add-to-cart:hover {
			background: ' . $main_color . ';
		}
		.list-attribute li:not(.photo) a:hover::before {
			border-left-color: ' . $main_color . ';
		}
		.product-item.list .add-to-cart,
		.product-item.list .yith-wcwl-add-to-wishlist,
		.product-item.list .compare-button,
		.product-item.list .yith-wcqv-button {
			border: 2px solid ' . $main_color . ';
			color: ' . $main_color . ';
		}
		.product-item.list .add-to-cart a::before,
		.product-item.list .add_to_wishlist::before,
		.product-item.list .compare.added::before {
			color: ' . $main_color . ';
		}
		.product-360-button a:hover,
		.product-video-button a:hover {
			color: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		.entry-summary .price,
		div.quantity .input-qty {
			color: ' . $main_color . ';
		}
		.variations .reset_variations:hover {
			background: ' . $main_color . ';
		}
		.entry-summary .compare:hover {
			background: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		.single-product .main-contain-summary .variations .data-val a.change-value.active {
			border-color: ' . $main_color . ';
			color: ' . $main_color . ';
		}
		.sticky_info_single_product button.funiter-single-add-to-cart-btn.btn.button {
			background: ' . $main_color . ';
		}
		.wc-tabs li a::before {
			border-bottom: 2px solid ' . $main_color . ';
		}	
		.product-grid .block-title a:hover {
			color: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		div.famibt-wrap .famibt-item .famibt-price,
		.famibt-wrap ins {
			color: ' . $main_color . ';
		}
		.products.product-grid .owl-slick .slick-arrow:hover {
			background: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		h3.title-share::before {
			border-bottom: 2px solid ' . $main_color . ';
		}
		.button-share:hover,
		.button-sliding:hover {
			background: ' . $main_color . ';
		}
		.button-close:hover {
			color: ' . $main_color . ';
		}
		body.woocommerce-cart .return-to-shop a:hover {
			background: ' . $main_color . ';
		}
		.shop_table .product-name a:not(.button):hover {
			color: ' . $main_color . ';
		}
		.woocommerce-cart-form .shop_table .actions button.button:hover {
			background-color: ' . $main_color . ';
		}
		.wc-proceed-to-checkout .checkout-button,
		.checkout_coupon .button,
		#place_order {
			background: ' . $main_color . ';
		}
		button.next-action:hover {
			color: ' . $main_color . ';
		}
		form.woocommerce-form-login .button:hover,
		form.register .button:hover {
			background: ' . $main_color . ';
		}
		.woocommerce-MyAccount-navigation > ul li.is-active a {
			color: ' . $main_color . ';
		}
		.woocommerce-MyAccount-content fieldset ~ p .woocommerce-Button,
		.woocommerce table.wishlist_table td.product-add-to-cart a,
		section.error-404 > a.button,
		.funiter-faqs .funiter-title,
		.loadmore-faqs a:hover {
			background: ' . $main_color . ';
		}
		body.wpb-js-composer .vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a {
			color: ' . $main_color . ' !important;
		}
		body .vc_toggle_default.vc_toggle_active .vc_toggle_title > h4 {
			color: ' . $main_color . ';
		}
		.tagcloud a:hover {
			background: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		.prdctfltr_filter[data-filter="product_cat"] .pf-help-title::before, #widget-area .widget_product_categories .widgettitle::before {
			background: ' . $main_color . ';
		}
		#widget-area .widget.widget_product_categories {
			border: 2px solid ' . $main_color . ';
		}
		#widget-area .widget.widget_product_categories .current-cat a,
		#widget-area .widget.widget_product_categories .cat-item a:hover {
			color: ' . $main_color . ';
		}
		.widget_product_categories .current-cat a::before,
		#widget-area .widget .select2-container--default .select2-selection--multiple .select2-selection__choice,
		.woocommerce-widget-layered-nav-dropdown .woocommerce-widget-layered-nav-dropdown__submit,
		.widget_price_filter .button,
		.widget_price_filter .ui-slider-range,
		.widget-funiter-socials .socials-list li a:hover {
			background: ' . $main_color . ';
		}
		.widget_categories .cat-item:hover {
			color: ' . $main_color . ';
		}
		@media (max-width: 1199px) {
			.funiter-custommenu .widgettitle::before {
				background: ' . $main_color . ';
			}
		}
		.funiter-banner.style-02 .desc {
			color: ' . $main_color . ';
		}
		.funiter-banner.style-03 .button:hover {
			background-color: ' . $main_color . ';
			border-color: ' . $main_color . ';
		}
		.funiter-banner.style-09 .desc {
			color: ' . $main_color . ';
		}
		.funiter-banner.style-10 .button:hover,
		.funiter-banner.style-11 .button,
		.funiter-button.style-01 a {
			background-color: ' . $main_color . ';
		}
		.funiter-countdown-sc.style-02 .text-date {
			color: ' . $main_color . ';
		}
		.funiter-countdown-sc.style-02 .button {
			background-color: ' . $main_color . ';
		}
		.funiter-heading.style-01 .funiter-title::before {
			border-bottom: 2px solid ' . $main_color . ';
		}
		.funiter-heading.style-01.light .funiter-title::before {
			border-color: ' . $main_color . ';
		}
		.funiter-heading.style-02 .funiter-title::before {
			border-bottom: 1px solid ' . $main_color . ';
		}
		.funiter-iconbox.style-01 .icon,
		.funiter-iconbox.style-02 .iconbox-inner .icon span,
		.funiter-iconbox.style-03 .iconbox-inner .icon,
		.funiter-iconbox.style-04 .iconbox-inner .icon {
			color: ' . $main_color . ';
		}
		.funiter-instagram-sc.style-01 .button::before {
			border-bottom: 1px solid ' . $main_color . ';
		}
		.funiter-member .member-info .positions::before {
			background-color: ' . $main_color . ';
		}
		.funiter-newsletter.style-01 .element-wrap::before,
		.product-name a:hover {
			color: ' . $main_color . ';
		}
		.loadmore-product a:hover {
			background-color: ' . $main_color . ';
		}
		.funiter-tabs.style-02 .tab-head .tab-link > li > a::before,
		.funiter-tabs.style-01 .tab-head .tab-link > li > a::before {
			border-bottom: 2px solid ' . $main_color . ';
		}
		.funiter-tabs.style-03 .tab-link li a:hover::before,
		.funiter-tabs.style-03 .tab-link li.active a::before {
			border-color: ' . $main_color . ' transparent transparent transparent;
		}
		.funiter-categorywrap.style-02 .category-head .category-list > li > a::before,
		.funiter-categorywrap.style-01 .category-head .category-list > li > a::before {
			border-bottom: 2px solid ' . $main_color . ';
		}
		.box-header-nav .funiter-custommenu .widgettitle::before {
			background: ' . $main_color . ';
		}
		.footer .widgettitle::before {
			border-bottom: 1px solid ' . $main_color . ';
		}
		.funiter-socials .content-socials .socials-list li a:hover span,
		.newsletter-form-wrap .submit-newsletter,
		#popup-newsletter .newsletter-form-wrap .submit-newsletter:hover {
			background: ' . $main_color . ';
		}
		.funiter-mapper .funiter-pin .funiter-popup-footer a:hover {
			background: ' . $main_color . ' !important;
			border-color: ' . $main_color . ' !important;
		}';
		/* GET CUSTOM 404 */
		if ( $funiter_page_404 )
			$css .= get_post_meta( $funiter_page_404, '_Funiter_Shortcode_custom_css', true );
		/* GET CUSTOM FAQS */
		if ( class_exists( 'WooCommerce' ) && $page_faqs_product && is_product() )
			$css .= get_post_meta( $page_faqs_product, '_Funiter_Shortcode_custom_css', true );
		/* Main color */
		$css .= '';

		return apply_filters( 'funiter_main_custom_css', $css );
	}
}
if ( !function_exists( 'funiter_vc_custom_css_footer' ) ) {
	function funiter_vc_custom_css_footer()
	{
		$data_meta           = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		$footer_options      = Funiter_Functions::funiter_get_option( 'funiter_footer_options' );
		$enable_theme_option = Funiter_Functions::funiter_get_option( 'enable_theme_options' );
		$footer_options      = $enable_theme_option == 1 && isset( $data_meta['metabox_funiter_footer_options'] ) && $data_meta['metabox_funiter_footer_options'] != '' ? $data_meta['metabox_funiter_footer_options'] : $footer_options;
		if ( !$footer_options ) {
			$query = new WP_Query( array( 'p' => $footer_options, 'post_type' => 'footer', 'posts_per_page' => 1 ) );
			while ( $query->have_posts() ): $query->the_post();
				$footer_options = get_the_ID();
			endwhile;
		}
		$shortcodes_custom_css = get_post_meta( $footer_options, '_Funiter_Shortcode_custom_css', true );

		return $shortcodes_custom_css;
	}
}
if ( !function_exists( 'funiter_write_custom_js ' ) ) {
	function funiter_write_custom_js()
	{
		$funiter_custom_js = Funiter_Functions::funiter_get_option( 'funiter_custom_js', '' );
		$content         = preg_replace( '/\s+/', ' ', $funiter_custom_js );
		wp_add_inline_script( 'funiter-script', $content );
	}
}
add_action( 'wp_enqueue_scripts', 'funiter_write_custom_js' );