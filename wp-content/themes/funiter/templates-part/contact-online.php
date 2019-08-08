<?php
/**
 * Name:  Header style 01
 **/
?>
<?php
$funiter_icon    = Funiter_Functions::funiter_get_option( 'header_icon' );
$funiter_phone   = Funiter_Functions::funiter_get_option( 'header_phone' );
$funiter_text  = Funiter_Functions::funiter_get_option( 'header_text' );
?>
<?php if ((class_exists('Zopim')) || ( $funiter_phone ) ){ ?>
<div class="header-contact-online">
    <div class="header-contact-online-inner">
        <?php if (class_exists('Zopim')) : ?>
            <div class="line-chat"><span class="flaticon2-chat-comment-oval-speech-bubble-with-text-lines"></span> <?php echo esc_html( 'Chat', 'funiter' ); ?></div>
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