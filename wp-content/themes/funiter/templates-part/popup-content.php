<?php
global $post;
$funiter_enable_popup            = Funiter_Functions::funiter_get_option( 'funiter_enable_popup' );
$funiter_popup_title             = Funiter_Functions::funiter_get_option( 'funiter_popup_title', 'Sign up & connect to Funiter' );
$funiter_popup_desc              = Funiter_Functions::funiter_get_option( 'funiter_popup_desc', '' );
$funiter_popup_input_submit      = Funiter_Functions::funiter_get_option( 'funiter_popup_input_submit', '' );
$funiter_popup_input_placeholder = Funiter_Functions::funiter_get_option( 'funiter_popup_input_placeholder', 'Email address here...' );
$funiter_popup_background        = Funiter_Functions::funiter_get_option( 'funiter_popup_background' );
$funiter_page_newsletter         = Funiter_Functions::funiter_get_option( 'funiter_select_newsletter_page' );
if ( isset( $post->ID ) ) {
	$id = $post->ID;
}
if ( isset( $post->post_type ) ) {
	$post_type = $post->post_type;
}
if ( is_array( $funiter_page_newsletter ) && in_array( $id, $funiter_page_newsletter ) && $post_type == 'page' && $funiter_enable_popup == 1 ) :?>
    <!--  Popup Newsletter-->
    <div class="modal fade" id="popup-newsletter" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <?php esc_html_e('X','funiter');?>
                </button>
                <div class="modal-inner">
					<?php if ( $funiter_popup_background ) : ?>
                        <div class="modal-thumb">
							<?php
							$image_thumb = wp_get_attachment_image_src( $funiter_popup_background, 'full' );
							$img_lazy    = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $image_thumb[1] . "%20" . $image_thumb[2] . "%27%2F%3E";
							?>
                            <img class="lazy" src="<?php echo esc_attr( $img_lazy ); ?>"
                                 data-src="<?php echo esc_url( $image_thumb[0] ) ?>"
								<?php echo image_hwstring( $image_thumb[1], $image_thumb[2] ); ?>
                                 alt="<?php echo esc_attr__( 'Newsletter', 'funiter' ); ?>">
                        </div>
					<?php endif; ?>
                    <div class="modal-info">
						<?php if ( $funiter_popup_title ): ?>
                            <h2 class="title"><?php echo esc_html( $funiter_popup_title ); ?></h2>
						<?php endif; ?>
						<?php if ( $funiter_popup_desc ): ?>
                            <p class="des"><?php echo wp_specialchars_decode( $funiter_popup_desc ); ?></p>
						<?php endif; ?>
                        <div class="newsletter-form-wrap">
                            <input class="email" type="email" name="email"
                                   placeholder="<?php echo esc_attr( $funiter_popup_input_placeholder ); ?>">
                            <button type="submit" name="submit_button" class="btn-submit submit-newsletter">
								<?php echo esc_html( $funiter_popup_input_submit ); ?>
                            </button>
                        </div>
                        <div class="checkbox btn-checkbox">
                            <label>
                                <input class="funiter_disabled_popup_by_user" type="checkbox">
                                <span><?php echo esc_html__( 'Don&rsquo;t show this popup again!', 'funiter' ); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--  Popup Newsletter-->
<?php endif;