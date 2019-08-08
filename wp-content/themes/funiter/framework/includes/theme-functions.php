<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'funiter_get_single_page_id' ) ) {
	/**
	 * Get single post, page, post type, shop page, my account ... id
	 */
	function funiter_get_single_page_id() {
		$single_id = 0;
		
		if ( is_front_page() && is_home() ) {
			// Default homepage
			$single_id = 0;
		} elseif ( is_front_page() ) {
			// static homepage
			$single_id = get_option( 'page_on_front' );
		} elseif ( is_home() ) {
			// blog page
			$single_id = get_option( 'page_for_posts' );
		} else {
			//everyting else
			if ( is_singular( 'page' ) ) {
				$single_id = get_the_ID();
			} else {
				if ( class_exists( 'WooCommerce' ) ) {
					if ( is_shop() ) {
						$single_id = wc_get_page_id( 'shop' );
					}
					if ( is_account_page() ) {
						$single_id = wc_get_page_id( 'myaccount' );
					}
					if ( is_cart() ) {
						$single_id = wc_get_page_id( 'cart' );
					}
					if ( is_checkout() ) {
						$single_id = wc_get_page_id( 'checkout' );
					}
				}
			}
		}
		
		return $single_id;
	}
}

/**
 *
 * HOOK FOOTER
 */
add_action( 'funiter_footer', 'funiter_footer_content', 10 );
/**
 *
 * HOOK HEADER
 */
add_action( 'funiter_header', 'funiter_header_content', 10 );
/**
 *
 * HOOK BLOG META
 */
/* POST INFO */
add_action( 'funiter_post_info_content', 'funiter_post_title', 10 );
add_action( 'funiter_post_info_content', 'funiter_post_content', 20 );

/**
 *
 * HOOK BLOG GRID
 */
add_action( 'funiter_after_blog_content', 'funiter_paging_nav', 10 );
add_action( 'funiter_post_content', 'funiter_post_thumbnail', 10 );
add_action( 'funiter_post_content', 'funiter_post_info', 20 );
/**
 *
 * HOOK BLOG SINGLE
 */
add_action( 'funiter_single_post_content', 'funiter_post_thumbnail', 10 );
add_action( 'funiter_single_post_content', 'funiter_post_info', 20 );
add_action( 'funiter_single_post_bottom_content', 'funiter_post_single_author', 30 );
/**
 *
 * HOOK TEMPLATE
 */
add_filter( 'wp_nav_menu_items', 'funiter_menu_detailing', 10, 2 );
add_filter( 'wp_nav_menu_items', 'funiter_top_right_menu', 10, 2 );
/**
 *
 * HOOK AJAX
 */
add_filter( 'wcml_multi_currency_ajax_actions', 'funiter_add_action_to_multi_currency_ajax', 10, 1 );
add_action( 'wp_ajax_funiter_ajax_tabs', 'funiter_ajax_tabs' );
add_action( 'wp_ajax_nopriv_funiter_ajax_tabs', 'funiter_ajax_tabs' );
/**
 *
 * HOOK AJAX
 */
add_action( 'wp_ajax_funiter_ajax_loadmore', 'funiter_ajax_loadmore' );
add_action( 'wp_ajax_nopriv_funiter_ajax_loadmore', 'funiter_ajax_loadmore' );
add_action( 'wp_ajax_funiter_ajax_faqs_loadmore', 'funiter_ajax_faqs_loadmore' );
add_action( 'wp_ajax_nopriv_funiter_ajax_faqs_loadmore', 'funiter_ajax_faqs_loadmore' );
?>
<?php
/**
 *
 * HOOK TEMPLATE FUNCTIONS
 */
if ( ! function_exists( 'funiter_get_logo' ) ) {
	function funiter_get_logo() {
		$width    = Funiter_Functions::funiter_get_option( 'funiter_width_logo', '103' );
		$width    .= 'px';
		$logo_url = get_theme_file_uri( '/assets/images/logo.svg' );
		$logo     = Funiter_Functions::funiter_get_option( 'funiter_logo' );
		if ( $logo != '' ) {
			$logo_url = wp_get_attachment_image_url( $logo, 'full' );
		}
		$html = '<a href="' . esc_url( home_url( '/' ) ) . '"><img style="width:' . esc_attr( $width ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '" class="logo" /></a>';
		echo apply_filters( 'funiter_site_logo', $html );
	}
}
if ( ! function_exists( 'funiter_get_logo_mobile' ) ) {
	function funiter_get_logo_mobile() {
		$width    = Funiter_Functions::funiter_get_option( 'funiter_width_logo', '103' );
		$width    .= 'px';
		$logo_url = get_theme_file_uri( '/assets/images/logo.svg' );
		$logo     = Funiter_Functions::funiter_get_option( 'funiter_mobile_logo' );
		if ( $logo != '' ) {
			$logo_url = wp_get_attachment_image_url( $logo, 'full' );
		}
		$html = '<a href="' . esc_url( home_url( '/' ) ) . '"><img style="width:' . esc_attr( $width ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '" class="logo" /></a>';
		echo apply_filters( 'funiter_site_logo', $html );
	}
}
if ( ! function_exists( 'funiter_set_post_views' ) ) {
	function funiter_set_post_views( $postID ) {
		if ( get_post_type( $postID ) == 'post' ) {
			$count_key = 'funiter_post_views_count';
			$count     = get_post_meta( $postID, $count_key, true );
			if ( $count == '' ) {
				$count = 0;
				delete_post_meta( $postID, $count_key );
				add_post_meta( $postID, $count_key, '0' );
			} else {
				$count ++;
				update_post_meta( $postID, $count_key, $count );
			}
		}
	}
}
if ( ! function_exists( 'funiter_get_post_views' ) ) {
	function funiter_get_post_views( $postID ) {
		$count_key = 'funiter_post_views_count';
		$count     = get_post_meta( $postID, $count_key, true );
		if ( $count == '' ) {
			delete_post_meta( $postID, $count_key );
			add_post_meta( $postID, $count_key, '0' );
			echo 0;
		}
		echo esc_html( $count );
	}
}
if ( ! function_exists( 'funiter_detected_shortcode' ) ) {
	function funiter_detected_shortcode( $id, $tab_id = null, $product_id = null ) {
		$post              = get_post( $id );
		$content           = preg_replace( '/\s+/', ' ', $post->post_content );
		$shortcode_section = '';
		if ( $tab_id == null ) {
			$out = array();
			preg_match_all( '/\[funiter_products(.*?)\]/', $content, $matches );
			if ( $matches[0] && is_array( $matches[0] ) && count( $matches[0] ) > 0 ) {
				foreach ( $matches[0] as $key => $value ) {
					if ( shortcode_parse_atts( $matches[1][ $key ] )['products_custom_id'] == $product_id ) {
						$out['atts']    = shortcode_parse_atts( $matches[1][ $key ] );
						$out['content'] = $value;
					}
				}
			}
			$shortcode_section = $out;
		}
		if ( $product_id == null ) {
			preg_match_all( '/\[vc_tta_section(.*?)vc_tta_section\]/', $content, $matches );
			if ( $matches[0] && is_array( $matches[0] ) && count( $matches[0] ) > 0 ) {
				foreach ( $matches[0] as $key => $value ) {
					preg_match_all( '/tab_id="([^"]+)"/', $matches[0][ $key ], $matches_ids );
					foreach ( $matches_ids[1] as $matches_id ) {
						if ( $tab_id == $matches_id ) {
							$shortcode_section = $value;
						}
					}
				}
			}
		}
		
		return $shortcode_section;
	}
}
if ( ! function_exists( 'funiter_add_action_to_multi_currency_ajax' ) ) {
	function funiter_add_action_to_multi_currency_ajax( $ajax_actions ) {
		$ajax_actions[] = 'funiter_ajax_tabs'; // Add a AJAX action to the array
		
		return $ajax_actions;
	}
}
if ( ! function_exists( 'funiter_ajax_tabs' ) ) {
	function funiter_ajax_tabs() {
		$response   = array(
			'html'    => '',
			'message' => '',
			'success' => 'no',
		);
		$section_id = isset( $_POST['section_id'] ) ? $_POST['section_id'] : '';
		$id         = isset( $_POST['id'] ) ? $_POST['id'] : '';
		$shortcode  = funiter_detected_shortcode( $id, $section_id, null );
		WPBMap::addAllMappedShortcodes();
		$response['html']    = wpb_js_remove_wpautop( $shortcode );
		$response['success'] = 'ok';
		wp_send_json( $response );
		die();
	}
}
if ( ! function_exists( 'funiter_menu_detailing' ) ) {
	function funiter_menu_detailing( $items, $args ) {
		if ( $args->theme_location == 'primary' ) {
			$funiter_block_detailing = Funiter_Functions::funiter_get_option( 'funiter_block_detailing', '' );
			$content                 = '';
			ob_start();
			$content .= $items;
			ob_start();
			if ( $funiter_block_detailing != '' ) : ?>
                <li class="menu-item block-detailing">
                    <p><?php echo wp_specialchars_decode( $funiter_block_detailing ); ?></p>
                </li>
			<?php endif;
			$content .= ob_get_clean();
			$items   = $content;
		}
		
		return $items;
	}
}
if ( ! function_exists( 'funiter_header_language' ) ) {
	function funiter_header_language() {
		$current_language = '';
		$list_language    = '';
		$languages        = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );
		if ( ! empty( $languages ) ) {
			foreach ( $languages as $l ) {
				$img = array(
					'url'    => $l['country_flag_url'],
					'width'  => 18,
					'height' => 12
				);
				if ( ! $l['active'] ) {
					$list_language .= '
						<li class="menu-item ">
                            <a href="' . esc_url( $l['url'] ) . '">
                                ' . Funiter_Functions::img_output( $img, '', $l['language_code'] ) . '
								' . esc_html( $l['native_name'] ) . '
                            </a>
                        </li>';
				} else {
					$current_language = '
						<a href="' . esc_url( $l['url'] ) . '" data-funiter="funiter-dropdown">
                            ' . Funiter_Functions::img_output( $img, '', $l['language_code'] ) . '
							' . esc_html( $l['native_name'] ) . '
                        </a>
                        <span class="toggle-submenu"></span>';
				}
			}
			$menu_language = '
                 <li class="menu-item funiter-dropdown block-language">
                    ' . $current_language . '
                    <ul class="sub-menu">
                        ' . $list_language . '
                    </ul>
                </li>';
			echo wp_specialchars_decode( $menu_language );
			echo '<li class="menu-item block-currency">';
			do_action( 'wcml_currency_switcher', array( 'format' => '%code%', 'switcher_style' => 'wcml-dropdown' ) );
			echo '</li>';
		}
	}
}
if ( ! function_exists( 'funiter_top_right_menu' ) ) {
	function funiter_top_right_menu( $items, $args ) {
		if ( $args->theme_location == 'top_right_menu' ) {
			$content = '';
			$content .= $items;
			ob_start();
			funiter_header_language();
			$content .= ob_get_clean();
			$items   = $content;
		}
		
		return $items;
	}
}
/**
 *
 * TEMPLATE BLOG
 */
if ( ! function_exists( 'funiter_paging_nav' ) ) {
	function funiter_paging_nav() {
		global $wp_query;
		$max = $wp_query->max_num_pages;
		// Don't print empty markup if there's only one page.
		if ( $max >= 2 ) {
			echo get_the_posts_pagination( array(
				                               'screen_reader_text' => '&nbsp;',
				                               'before_page_number' => '',
				                               'prev_text'          => esc_html__( 'Prev', 'funiter' ),
				                               'next_text'          => esc_html__( 'Next', 'funiter' ),
			                               )
			);
		}
	}
}
if ( ! function_exists( 'funiter_post_single_author' ) ) {
	function funiter_post_single_author() {
		$enable_author_info = Funiter_Functions::funiter_get_option( 'enable_author_info' );
		if ( $enable_author_info == 1 ):
			?>
            <div class="post-single-author">
                <figure class="avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 140 ); ?></figure>
                <div class="author-info">
                    <h4 class="name"><?php the_author(); ?></h4>
                    <p class="desc">
						<?php the_author_meta( 'description' ); ?>
                    </p>
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
						<?php echo esc_html__( 'All author posts', 'funiter' ); ?>
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div>
			<?php
		endif;
	}
}
if ( ! function_exists( 'funiter_post_thumbnail' ) ) {
	function funiter_post_thumbnail() {
		$funiter_blog_style = Funiter_Functions::funiter_get_option( 'funiter_blog_list_style', 'standard' );
		$funiter_post_meta  = get_post_meta( get_the_ID(), '_custom_metabox_post_options', true );
		$gallery_post       = isset( $funiter_post_meta['gallery_post'] ) ? $funiter_post_meta['gallery_post'] : '';
		$video_post         = isset( $funiter_post_meta['video_post'] ) ? $funiter_post_meta['video_post'] : '';
		$quote_post         = isset( $funiter_post_meta['quote_post'] ) ? $funiter_post_meta['quote_post'] : '';
		$post_format        = get_post_format();
		$class              = 'post-thumb';
		$check              = false;
		if ( $gallery_post != '' && $post_format == 'gallery' ) {
			$check = true;
		}
		if ( $video_post != '' && $post_format == 'video' ) {
			$check = true;
		}
		if ( $quote_post != '' && $post_format == 'quote' ) {
			$check = true;
		}
		if ( $funiter_blog_style != 'grid' ) {
			$width  = 1040;
			$height = 640;
		} else {
			$width  = 442;
			$height = 328;
		}
		if ( has_post_thumbnail() ) :
			?>
            <div class="<?php echo esc_attr( $class ); ?>">
				<?php
				if ( $check == true && $funiter_blog_style != 'grid' ) {
					if ( $post_format == 'gallery' ) :
						$gallery_post = explode( ',', $gallery_post );
						?>
                        <div class="owl-slick"
                             data-slick='{"arrows": true, "dots": false, "infinite": false, "slidesToShow": 1}'>
                            <figure>
								<?php
								$image_thumb = apply_filters( 'funiter_resize_image', get_post_thumbnail_id(), $width, $height, true, true );
								echo wp_specialchars_decode( $image_thumb['img'] );
								?>
                            </figure>
							<?php foreach ( $gallery_post as $item ) : ?>
                                <figure>
									<?php
									$image_gallery = apply_filters( 'funiter_resize_image', $item, $width, $height, true, true );
									echo wp_specialchars_decode( $image_gallery['img'] );
									?>
                                </figure>
							<?php endforeach; ?>
                        </div>
					<?php endif;
					if ( $post_format == 'quote' ) {
						echo '<p class="quote">' . wp_specialchars_decode( $quote_post ) . '</p>';
					}
					if ( $post_format == 'video' ) {
						the_widget( 'WP_Widget_Media_Video', 'url=' . $video_post . '' );
					}
				} else {
					if ( is_single() ) {
						the_post_thumbnail( 'full' );
					} else {
						$image_thumb = apply_filters( 'funiter_resize_image', get_post_thumbnail_id(), $width, $height, true, true );
						echo '<a href="' . get_permalink() . '">';
						echo wp_specialchars_decode( $image_thumb['img'] );
						echo '</a>';
					}
				}
				?>
            </div>
			<?php
		endif;
	}
}
if ( ! function_exists( 'funiter_callback_comment' ) ) {
	/**
	 * Funiter comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args    the comment args.
	 * @param int   $depth   the comment depth.
	 *
	 * @since 1.0.0
	 */
	function funiter_callback_comment( $comment, $args, $depth ) {
		if ( 'div' == $args['style'] ) {
			$tag       = 'div ';
			$add_below = 'comment';
		} else {
			$tag       = 'li ';
			$add_below = 'div-comment';
		}
		?>
        <<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php echo get_comment_ID(); ?>">
        <div class="comment_container">
            <div class="comment-avatar">
				<?php echo get_avatar( $comment, 120 ); ?>
            </div>
            <div class="comment-text commentmetadata">
                <div class="comment-author vcard">
					<?php printf( wp_kses_post( '%s', 'funiter' ), get_comment_author_link() ); ?>
                </div>
				<?php if ( '0' == $comment->comment_approved ) : ?>
                    <em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'funiter' ); ?></em>
                    <br/>
				<?php endif; ?>
                <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( get_comment_ID() ) ) ); ?>"
                   class="comment-date">
					<?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date() . '</time>'; ?>
                </a>
				<?php edit_comment_link( __( 'Edit', 'funiter' ), '  ', '' ); ?>
				<?php comment_reply_link( array_merge( $args, array(
					'add_below' => $add_below,
					'depth'     => $depth,
					'max_depth' => $args['max_depth']
				) ) ); ?>
				<?php echo ( 'div' != $args['style'] ) ? '<div id="div-comment-' . get_comment_ID() . '" class="comment-content">' : '' ?>
				<?php comment_text(); ?>
				<?php echo 'div' != $args['style'] ? '</div>' : ''; ?>
            </div>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_post_title' ) ) {
	function funiter_post_title() {
		if ( is_single() ) { ?>
            <div class="post-meta clearfix">
                <div class="date">
                    <a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
                </div>
            </div>
            <h2 class="post-title"><?php the_title(); ?></h2>
		<?php } else {
			?>
            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php }
	}
}
if ( ! function_exists( 'funiter_post_content' ) ) {
	function funiter_post_content() {
		$funiter_blog_style = Funiter_Functions::funiter_get_option( 'funiter_blog_list_style', 'standard' );
		if ( $funiter_blog_style == 'grid' && ! is_single() ):
			?>
            <div class="post-content">
				<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 15, esc_html__( '...', 'funiter' ) ); ?>
            </div>
            <a href="<?php the_permalink(); ?>"
               class="post-link"><?php echo esc_html__( ' Read more', 'funiter' ); ?></a>
		<?php elseif ( ( $funiter_blog_style == 'classic' && ! is_single() ) ): ?>
            <div class="post-content">
				<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 50, esc_html__( '...', 'funiter' ) ); ?>
            </div>
            <a href="<?php the_permalink(); ?>"
               class="post-link"><?php echo esc_html__( ' Read more', 'funiter' ); ?></a>
		<?php elseif ( ( $funiter_blog_style == 'standard' && ! is_single() ) ): ?>
            <div class="post-content post-standard">
				<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					             esc_html__( 'Continue reading %s', 'funiter' ),
					             the_title( '<span class="screen-reader-text">', '</span>', false )
				             )
				);
				wp_link_pages( array(
					               'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'funiter' ) . '</span>',
					               'after'       => '</div>',
					               'link_before' => '<span>',
					               'link_after'  => '</span>',
				               ) );
				?>
            </div>
		
		<?php else: ?>
            <div class="single-post-content">
				<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					             esc_html__( 'Continue reading %s', 'funiter' ),
					             the_title( '<span class="screen-reader-text">', '</span>', false )
				             )
				);
				wp_link_pages( array(
					               'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'funiter' ) . '</span>',
					               'after'       => '</div>',
					               'link_before' => '<span>',
					               'link_after'  => '</span>',
				               ) );
				?>
            </div>
		<?php endif; ?>
	
	<?php }
}
if ( ! function_exists( 'funiter_post_single_content' ) ) {
	function funiter_post_single_content() {
		?>
        <div class="post-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				             esc_html__( 'Continue reading %s', 'funiter' ),
				             the_title( '<span class="screen-reader-text">', '</span>', false )
			             )
			);
			wp_link_pages( array(
				               'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'funiter' ) . '</span>',
				               'after'       => '</div>',
				               'link_before' => '<span>',
				               'link_after'  => '</span>',
			               )
			);
			?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_post_sticky' ) ) {
	function funiter_post_sticky() {
		if ( is_sticky() ) : ?>
            <li class="sticky-post"><i class="fa fa-flag"></i>
				<?php echo esc_html__( ' Sticky', 'funiter' ); ?>
            </li>
		<?php endif;
	}
}
if ( ! function_exists( 'funiter_post_date_meta' ) ) {
	function funiter_post_date_meta() {
		?>
        <div class="post-meta">
            <ul class="info-meta">
                <li class="date">
                    <a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
                </li>
            </ul>
            <div class="comment">
                <span class="flaticon-comment-1"></span>
				<?php
				comments_number(
					esc_html__( '0', 'funiter' ),
					esc_html__( '1', 'funiter' ),
					esc_html__( '%', 'funiter' )
				);
				?>
            </div>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_post_footer' ) ) {
	function funiter_post_footer() { ?>
        <div class="post-footer">
			<?php
			funiter_post_tags_single();
			funiter_post_category();
			?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_post_author' ) ) {
	function funiter_post_author() {
		?>
        <div class="post-meta clearfix">
            <div class="date">
                <a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
            </div>
            <ul class="info-meta">
                <li class="post-author">
                    <span><?php echo esc_html__( 'By ', 'funiter' ) ?></span>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
						<?php the_author() ?>
                    </a>
                </li>
                <li class="comment">
                    <span class="flaticon-comment-1"></span>
					<?php
					comments_number(
						esc_html__( '0', 'funiter' ),
						esc_html__( '1', 'funiter' ),
						esc_html__( '%', 'funiter' )
					);
					?>
                </li>
            </ul>

        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_share_button' ) ) {
	function funiter_share_button( $post_id ) {
		$share_image_url       = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
		$share_link_url        = get_permalink( $post_id );
		$share_link_title      = get_the_title();
		$share_twitter_summary = get_the_excerpt();
		?>
        <div class="funiter-share-socials">
            <a target="_blank" class="facebook"
               href="https://www.facebook.com/sharer.php?s=100&amp;p%5Btitle%5D=<?php echo esc_html( $share_link_title ); ?>&amp;p%5Burl%5D=<?php echo urlencode( $share_link_url ); ?>"
               title="<?php echo esc_attr( 'Facebook' ) ?>"
               onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                <i class="fa fa-facebook-f"></i>
            </a>
            <a target="_blank" class="twitter"
               href="https://twitter.com/share?url=<?php echo urlencode( $share_link_url ) ?>&amp;text=<?php echo esc_html( $share_twitter_summary ); ?>"
               title="<?php echo esc_attr( 'Twitter' ) ?>"
               onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                <i class="fa fa-twitter"></i>
            </a>
            <a target="_blank" class="pinterest"
               href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( $share_link_url ) ?>&amp;description=<?php echo esc_html( $share_twitter_summary ); ?>&amp;media=<?php echo urlencode( $share_image_url[0] ); ?>"
               title="<?php echo esc_attr( 'Pinterest' ) ?>"
               onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                <i class="fa fa-pinterest"></i>
            </a>
            <a target="_blank" class="googleplus"
               href="https://plus.google.com/share?url=<?php echo urlencode( $share_link_url ) ?>&amp;title=<?php echo esc_html( $share_link_title ); ?>"
               title="<?php echo esc_attr( 'Google+' ) ?>"
               onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                <i class="fa fa-google-plus"></i>
            </a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_post_tags' ) ) {
	function funiter_post_tags() {
		$get_term_tag = get_the_terms( get_the_ID(), 'post_tag' );
		if ( ! is_wp_error( $get_term_tag ) && ! empty( $get_term_tag ) ) : ?>
            <div class="tags">
				<?php
				echo esc_html__( 'Tags: ', 'funiter' );
				the_tags( '' );
				?>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'funiter_post_tags_single' ) ) {
	function funiter_post_tags_single() {
		$get_term_tag = get_the_terms( get_the_ID(), 'post_tag' );
		if ( ! is_wp_error( $get_term_tag ) && ! empty( $get_term_tag ) ) : ?>
            <div class="tags single-tags">
            	<span class="title-cat">
					<?php
					echo esc_html__( 'Tags: ', 'funiter' );
					?>
				</span>
				<?php
				the_tags( '' );
				?>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'funiter_post_category' ) ) {
	function funiter_post_category() {
		$get_term_cat = get_the_terms( get_the_ID(), 'category' );
		if ( ! is_wp_error( $get_term_cat ) && ! empty( $get_term_cat ) ) : ?>
            <div class="categories">
				<span class="title-cat">
				<?php
				echo esc_html__( 'Categories: ', 'funiter' );
				?>
				</span>
				<?php the_category(); ?>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'funiter_post_single_meta' ) ) {
	function funiter_post_single_meta() {
		$enable_share_post = Funiter_Functions::funiter_get_option( 'enable_share_post' );
		?>
        <div class="single-meta-post">
            <div class="single-meta">
				<?php
				funiter_post_tags_single();
				funiter_post_category();
				?>
            </div>
			
			<?php if ( $enable_share_post == 1 ) {
				?>
                <div class="post_single-social">
					<?php funiter_share_button( get_the_ID() ); ?>
                </div>
			<?php } ?>

        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_post_info' ) ) {
	function funiter_post_info() { ?>
        <div class="post-info">
			<?php
			/**
			 * Functions hooked into funiter_post_info_content action
			 *
			 * @hooked funiter_post_title               - 10
			 * @hooked funiter_post_content             - 20
			 * @hooked funiter_post_author              - 30
			 */
			do_action( 'funiter_post_info_content' );
			?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_search_form' ) ) {
	function funiter_search_form() {
		$key_words = Funiter_Functions::funiter_get_option( 'key_word' );
		$selected  = '';
		if ( isset( $_GET['product_cat'] ) && $_GET['product_cat'] ) {
			$selected = $_GET['product_cat'];
		}
		$args = array(
			'show_option_none'  => esc_html__( 'All Categories', 'funiter' ),
			'taxonomy'          => 'product_cat',
			'class'             => 'category-search-option',
			'hide_empty'        => 1,
			'orderby'           => 'name',
			'order'             => 'ASC',
			'tab_index'         => true,
			'hierarchical'      => true,
			'id'                => rand(),
			'name'              => 'product_cat',
			'value_field'       => 'slug',
			'selected'          => $selected,
			'option_none_value' => '0',
		);
		
		$has_wc_cats_dropdown = class_exists( 'WooCommerce' );
		$block_search_class   = $has_wc_cats_dropdown ? 'has-woo-cat-select' : 'no-woo-cat-select';
		
		?>
        <div class="block-search <?php echo esc_attr( $block_search_class ); ?>">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>"
                  class="form-search block-search funiter-live-search-form">
                <div class="form-content search-box results-search">
                    <div class="inner">
                        <input autocomplete="off" type="text" class="searchfield txt-livesearch input" name="s"
                               value="<?php echo esc_attr( get_search_query() ); ?>"
                               placeholder="<?php echo esc_attr__( 'Searching for ...', 'funiter' ); ?>">
                    </div>
                </div>
				<?php if ( $has_wc_cats_dropdown ) { ?>
                    <input type="hidden" name="post_type" value="product"/>
                    <input type="hidden" name="taxonomy" value="product_cat">
                    <div class="category">
						<?php wp_dropdown_categories( $args ); ?>
                    </div>
				<?php } else { ?>
                    <input type="hidden" name="post_type" value="post"/>
				<?php }; ?>
                <button type="submit" class="btn-submit">
                    <span class="flaticon-magnifying-glass"></span>
                </button>
            </form><!-- block search -->
			<?php if ( ! empty( $key_words ) ): ?>
                <div class="key-word-search">
                    <span class="title-key"><?php echo esc_html__( 'Most searched:', 'funiter' ); ?></span>
                    <div class="listkey-word">
						<?php foreach ( $key_words as $key_word ): ?>
                            <a class="key-item" href="<?php echo esc_url( $key_word['key_word_link'] ); ?>">
								<?php echo esc_html( $key_word['key_word_item'] ); ?>
                            </a>
						<?php endforeach; ?>
                    </div>
                </div>
			<?php endif; ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_header_vertical' ) ) {
	function funiter_header_vertical() {
		global $post;
		/* MAIN THEME OPTIONS */
		$funiter_enable_vertical = Funiter_Functions::funiter_get_option( 'funiter_enable_vertical_menu' );
		$funiter_block_vertical  = Funiter_Functions::funiter_get_option( 'funiter_block_vertical_menu' );
		$funiter_item_visible    = Funiter_Functions::funiter_get_option( 'funiter_vertical_item_visible', 10 );
		if ( $funiter_enable_vertical == 1 ) :
			$locations = get_nav_menu_locations();
			$menu_id             = $locations['vertical_menu'];
			$menu_items          = wp_get_nav_menu_items( $menu_id );
			$count               = 0;
			foreach ( $menu_items as $menu_item ) {
				if ( $menu_item->menu_item_parent == 0 ) {
					$count ++;
				}
			}
			/* MAIN THEME OPTIONS */
			$vertical_title        = Funiter_Functions::funiter_get_option( 'funiter_vertical_menu_title', esc_html__( 'CATEGORIES', 'funiter' ) );
			$vertical_button_all   = Funiter_Functions::funiter_get_option( 'funiter_vertical_menu_button_all_text', esc_html__( 'All Categories', 'funiter' ) );
			$vertical_button_close = Funiter_Functions::funiter_get_option( 'funiter_vertical_menu_button_close_text', esc_html__( 'Close', 'funiter' ) );
			$funiter_block_class   = array( 'vertical-wrapper block-nav-category' );
			$id                    = '';
			$post_type             = '';
			if ( $funiter_enable_vertical == 1 ) {
				$funiter_block_class[] = 'has-vertical-menu';
			}
			if ( isset( $post->ID ) ) {
				$id = $post->ID;
			}
			if ( isset( $post->post_type ) ) {
				$post_type = $post->post_type;
			}
			if ( is_array( $funiter_block_vertical ) && in_array( $id, $funiter_block_vertical ) && $post_type == 'page' ) {
				$funiter_block_class[] = 'always-open';
			}
			?>
            <!-- block category -->
            <div data-items="<?php echo esc_attr( $funiter_item_visible ); ?>"
                 class="<?php echo implode( ' ', $funiter_block_class ); ?>">
                <div class="block-title">
                    <span class="before">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="text-title"><?php echo esc_html( $vertical_title ); ?></span>
                </div>
                <div class="block-content verticalmenu-content">
					<?php
					wp_nav_menu( array(
						             'menu'            => 'vertical_menu',
						             'theme_location'  => 'vertical_menu',
						             'depth'           => 3,
						             'container'       => '',
						             'container_class' => '',
						             'container_id'    => '',
						             'menu_class'      => 'funiter-nav vertical-menu',
						             'fallback_cb'     => 'Funiter_navwalker::fallback',
						             'walker'          => new Funiter_navwalker(),
					             )
					);
					if ( $count > $funiter_item_visible ) : ?>
                        <div class="view-all-category">
                            <a href="#" data-closetext="<?php echo esc_attr( $vertical_button_close ); ?>"
                               data-alltext="<?php echo esc_attr( $vertical_button_all ) ?>"
                               class="btn-view-all open-cate"><?php echo esc_html( $vertical_button_all ) ?></a>
                        </div>
					<?php endif; ?>
                </div>
            </div><!-- block category -->
		<?php endif;
	}
}
/**
 *
 * TEMPLATE FOOTER
 */
if ( ! function_exists( 'funiter_footer_content' ) ) {
	function funiter_footer_content() {
		$single_id                            = funiter_get_single_page_id();
		$funiter_metabox_enable_custom_header = Funiter_Functions::funiter_get_option( 'funiter_metabox_enable_custom_footer' );
		$data_meta                            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
		$footer_options                       = Funiter_Functions::funiter_get_option( 'funiter_footer_options' );
		$enable_theme_option                  = Funiter_Functions::funiter_get_option( 'enable_theme_options' );
		$enable_footer_mobile                 = Funiter_Functions::funiter_get_option( 'enable_footer_mobile' );
		if ( $single_id > 0 ) {
			// Override custom header (if request from url)
			if ( isset( $_GET['funiter_metabox_enable_custom_footer'] ) ) {
				$data_meta['funiter_metabox_enable_custom_footer'] = $_GET['funiter_metabox_enable_custom_footer'] == 'yes';
			}
			if ( isset( $data_meta['funiter_metabox_enable_custom_footer'] ) ) {
				$enable_custom_footer = $data_meta['funiter_metabox_enable_custom_footer'];
			}
		}
		if ( ! empty( $data_meta ) && $enable_custom_footer ) {
			$footer_options = isset( $data_meta['metabox_funiter_footer_options'] ) && $data_meta['metabox_funiter_footer_options'] != '' ? $data_meta['metabox_funiter_footer_options'] : $footer_options;
		};
		if ( ( $enable_footer_mobile == 1 ) && ( funiter_is_mobile() ) ) {
			$footer_options = Funiter_Functions::funiter_get_option( 'funiter_footer_mobile_options' );
		}
		$meta_template_style   = get_post_meta( $footer_options, '_custom_footer_options', true );
		$footer_template_style = isset( $meta_template_style['funiter_footer_style'] ) ? $meta_template_style['funiter_footer_style'] : 'style-01';
		
		$footer_options = apply_filters( 'wpml_object_id', $footer_options, 'footer' );
		
		ob_start();
		$query = new WP_Query( array( 'p' => $footer_options, 'post_type' => 'footer', 'posts_per_page' => 1 ) );
		if ( $query->have_posts() ):
			while ( $query->have_posts() ): $query->the_post();
				get_template_part( 'templates/footer/footer', $footer_template_style );
			endwhile;
		endif;
		wp_reset_postdata();
		echo ob_get_clean();
	}
}
if ( ! function_exists( 'funiter_homepage_mobile' ) ) {
	function funiter_homepage_mobile() {
		$funiter_enable_homepage_mobile = Funiter_Functions::funiter_get_option( 'funiter_enable_homepage_mobile' );
		if ( ( $funiter_enable_homepage_mobile == 1 ) && ( funiter_is_mobile() ) ) {
			$homepage_mobile_options = Funiter_Functions::funiter_get_option( 'funiter_homepage_mobile' );
		}
		$query = new WP_Query( array( 'p' => $homepage_mobile_options, 'post_type' => 'page', 'posts_per_page' => 1 ) );
		if ( $query->have_posts() ):
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
		endif;
		wp_reset_postdata();
		echo ob_get_clean();
	}
}
/**
 *
 * TEMPLATE HEADER
 */
if ( ! function_exists( 'funiter_header_content' ) ) {
	function funiter_header_content() {
		$single_id            = funiter_get_single_page_id();
		$enable_theme_option  = Funiter_Functions::funiter_get_option( 'enable_theme_options' );
		$enable_custom_header = Funiter_Functions::funiter_get_option( 'funiter_metabox_enable_custom_header' );
		$data_meta            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
		$header_options       = Funiter_Functions::funiter_get_option( 'funiter_used_header', 'style-02' );
		if ( $single_id > 0 ) {
			// Override custom header (if request from url)
			if ( isset( $_GET['funiter_metabox_enable_custom_header'] ) ) {
				$data_meta['funiter_metabox_enable_custom_header'] = $_GET['funiter_metabox_enable_custom_header'] == 'yes';
			}
			if ( isset( $data_meta['funiter_metabox_enable_custom_header'] ) ) {
				$enable_custom_header = $data_meta['funiter_metabox_enable_custom_header'];
			}
		}
		if ( ! empty( $data_meta ) && $enable_custom_header ) {
			$header_options = isset( $data_meta['metabox_funiter_used_header'] ) && $data_meta['metabox_funiter_used_header'] != '' ? $data_meta['metabox_funiter_used_header'] : $header_options;
		};
		
		$enable_header_mobile = Funiter_Functions::funiter_get_option( 'enable_header_mobile' );
		$is_mobile            = Funiter_Functions::is_mobile();
		
		// Check header mobile for single product
		if ( is_singular( 'product' ) && $is_mobile ) {
			$enable_single_product_mobile = Funiter_Functions::funiter_get_option( 'enable_single_product_mobile', false );
			if ( $enable_single_product_mobile ) {
				get_template_part( 'templates/header', 'mobile-single-product' );
				
				return;
			}
		}
		
		// Check if header mobile is enabled
		if ( $enable_header_mobile && $is_mobile ) {
			
			get_template_part( 'templates/header', 'mobile' );
			
			return;
		}
		
		get_template_part( 'templates/header/header', $header_options );
	}
}
if ( ! function_exists( 'funiter_header_background' ) ) {
	function funiter_header_background() {
		$funiter_header_background = Funiter_Functions::funiter_get_option( 'funiter_header_background' );
		$funiter_background_url    = Funiter_Functions::funiter_get_option( 'funiter_background_url', '#' );
		
		if ( $funiter_header_background ):
			?>
            <a href="<?php echo esc_url( $funiter_background_url ); ?>">
				<?php
				$image_gallery = apply_filters( 'funiter_resize_image', $funiter_header_background, false, false, true, true );
				echo wp_specialchars_decode( $image_gallery['img'] );
				?>
            </a>
			<?php
		endif;
	}
}
if ( ! function_exists( 'funiter_blog_banner' ) ) {
	function funiter_blog_banner() {
		$funiter_blog_banner      = Funiter_Functions::funiter_get_option( 'funiter_blog_banner' );
		$funiter_blog_banner_link = Funiter_Functions::funiter_get_option( 'funiter_blog_banner_link', '#' );
		if ( $funiter_blog_banner ):
			?>
            <div class="banner-blog">
                <a href="<?php echo esc_url( $funiter_blog_banner_link ); ?>">
					<?php
					$image_gallery = apply_filters( 'funiter_resize_image', $funiter_blog_banner, false, false, true, true );
					echo wp_specialchars_decode( $image_gallery['img'] );
					?>
                </a>
            </div>
			<?php
		endif;
	}
}
if ( ! function_exists( 'funiter_user_link' ) ) {
	function funiter_user_link() {
		$myaccount_link = wp_login_url();
		if ( class_exists( 'WooCommerce' ) ) {
			$myaccount_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		}
		?>
        <div class="menu-item block-user funiter-dropdown">
			<?php if ( is_user_logged_in() ): ?>
                <a data-funiter="funiter-dropdown" class="woo-wishlist-link"
                   href="<?php echo esc_url( $myaccount_link ); ?>">
                    <span class="flaticon-profile"></span>
                </a>
				<?php if ( function_exists( 'wc_get_account_menu_items' ) ): ?>
                    <ul class="sub-menu">
						<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                            <li class="menu-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                            </li>
						<?php endforeach; ?>
                    </ul>
				<?php else: ?>
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php esc_html_e( 'Logout', 'funiter' ); ?></a>
                        </li>
                    </ul>
				<?php endif;
			else: ?>
                <a href="#login-popup" data-effect="mfp-zoom-in" class="acc-popup">
                    <span class="flaticon-profile"></span>
                </a>
			<?php endif; ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'funiter_header_sticky' ) ) {
	function funiter_header_sticky() {
		$enable_sticky_menu = Funiter_Functions::funiter_get_option( 'funiter_sticky_menu' );
		if ( $enable_sticky_menu == 1 ): ?>
            <div class="header-sticky">
                <div class="container">
                    <div class="header-nav-inner">
						<?php funiter_header_vertical(); ?>
                        <div class="box-header-nav main-menu-wapper">
							<?php
							wp_nav_menu( array(
								             'menu'            => 'primary',
								             'theme_location'  => 'Primary Menu',
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
                    </div>
                </div>
            </div>
		<?php endif;
	}
}
/**
 *
 * TEMPLATE LOAD MORE
 */
if ( ! function_exists( 'funiter_ajax_loadmore' ) ) {
	function funiter_ajax_loadmore() {
		$response = array(
			'html'     => '',
			'loop_id'  => array(),
			'out_post' => 'no',
			'message'  => '',
			'success'  => 'no',
		);
		
		// Check security
		$nonce = isset( $_POST['security'] ) ? $_POST['security'] : '';
		if ( ! wp_verify_nonce( $nonce, 'funiter_ajax_frontend' ) ) {
			$response['success'] = 'no';
			wp_send_json( $response );
		}
		
		$out_post             = 'no';
		$args                 = isset( $_POST['loop_query'] ) ? $_POST['loop_query'] : array();
		$class                = isset( $_POST['loop_class'] ) ? $_POST['loop_class'] : array();
		$loop_id              = isset( $_POST['loop_id'] ) ? $_POST['loop_id'] : array();
		$loop_style           = isset( $_POST['loop_style'] ) ? $_POST['loop_style'] : '';
		$loop_thumb           = isset( $_POST['loop_thumb'] ) ? explode( 'x', $_POST['loop_thumb'] ) : '';
		$args['post__not_in'] = $loop_id;
		
		$product_size_args = array(
			'width'  => $loop_thumb[0],
			'height' => $loop_thumb[1]
		);
		
		$loop_posts       = new WP_Query( $args );
		$response['args'] = $args;
		ob_start();
		if ( $loop_posts->have_posts() ) {
			while ( $loop_posts->have_posts() ) : $loop_posts->the_post(); ?>
				<?php $loop_id[] = get_the_ID(); ?>
                <div <?php post_class( $class ); ?>>
					<?php wc_get_template( 'product-styles/content-product-style-' . $loop_style . '.php', $product_size_args ); ?>
                </div>
				<?php
			endwhile;
		} else {
			$out_post = 'yes';
		}
		wp_reset_postdata();
		$response['html']     = ob_get_clean();
		$response['loop_id']  = $loop_id;
		$response['out_post'] = $out_post;
		$response['success']  = 'yes';
		wp_send_json( $response );
		die();
	}
}
if ( ! function_exists( 'funiter_ajax_faqs_loadmore' ) ) {
	function funiter_ajax_faqs_loadmore() {
		$response             = array(
			'html'     => '',
			'loop_id'  => array(),
			'out_post' => 'no',
			'message'  => '',
			'success'  => 'no',
		);
		$out_post             = 'no';
		$args                 = isset( $_POST['loop_query'] ) ? $_POST['loop_query'] : array();
		$class                = isset( $_POST['loop_class'] ) ? $_POST['loop_class'] : array();
		$loop_id              = isset( $_POST['loop_id'] ) ? $_POST['loop_id'] : array();
		$args['post__not_in'] = $loop_id;
		$loop_posts           = new WP_Query( $args );
		ob_start();
		if ( $loop_posts->have_posts() ) {
			while ( $loop_posts->have_posts() ) : $loop_posts->the_post(); ?>
				<?php $loop_id[] = get_the_ID(); ?>
                <article <?php post_class( $class ); ?>>
                    <div class="question">
                        <span class="icon"><?php echo esc_html__( 'Q', 'funiter' ); ?></span>
                        <p class="text"><?php the_title(); ?></p>
                    </div>
                    <div class="answer">
                        <span class="icon"><?php echo esc_html__( 'A', 'funiter' ); ?></span>
                        <p class="text"><?php the_content(); ?></p>
                    </div>
                </article>
				<?php
			endwhile;
		} else {
			$out_post = 'yes';
		}
		$response['html']     = ob_get_clean();
		$response['loop_id']  = $loop_id;
		$response['out_post'] = $out_post;
		$response['success']  = 'yes';
		wp_send_json( $response );
		die();
	}
}
if ( ! function_exists( 'funiter_is_mobile' ) ) {
	function funiter_is_mobile() {
		$is_mobile = false;
		if ( function_exists( 'funiter_toolkit_is_mobile' ) ) {
			$is_mobile = funiter_toolkit_is_mobile();
		}
		
		$force_mobile = isset( $_REQUEST['force_mobile'] ) ? $_REQUEST['force_mobile'] == 'yes' || $_REQUEST['force_mobile'] == 'true' : false;
		if ( $force_mobile ) {
			$is_mobile = true;
		}
		
		$is_mobile = apply_filters( 'funiter_is_mobile', $is_mobile );
		
		return $is_mobile;
	}
}
// Login
if ( ! function_exists( 'funiter_login_modal' ) ) {
	/**
	 * Add login modal to footer
	 */
	function funiter_login_modal() {
		if ( ! shortcode_exists( 'woocommerce_my_account' ) ) {
			return;
		}
		
		if ( is_user_logged_in() ) {
			return;
		}
		
		// Don't load login popup on real mobile when header mobile is enabled
		$enable_header_mobile = Funiter_Functions::funiter_get_option( 'enable_header_mobile' );
		if ( ( $enable_header_mobile == 1 ) && ( funiter_is_mobile() ) ) {
			return;
		}
		
		?>

        <div id="login-popup" class="woocommerce-account md-content mfp-with-anim mfp-hide">
            <div class="funiter-modal-content">
				<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
            </div>
        </div>
		
		<?php
	}
	
	add_action( 'wp_footer', 'funiter_login_modal' );
};