<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$customer_login_id = uniqid( 'customer_login_' );
$login_id          = uniqid( 'login_' );
$register_id       = uniqid( 'register_' );
$rememberme_id     = uniqid( 'rememberme_' );
?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="u-columns col2-set" id="customer_login">

	<div class="myaccount-action u-column1 col-1">

<?php endif; ?>
		<div class="login-icon"><span class="flaticon-profile"></span></div>
		<p class="des-login"><?php esc_html_e( 'Create an account to expedite future checkouts, track order history & receive emails, discounts, & special offers', 'funiter' ); ?></p>
		<div class="login-form block-form">
				<form class="woocommerce-form woocommerce-form-login login" method="post">

                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <input placeholder="<?php esc_attr_e( 'Username', 'funiter' ); ?> " type="text"
                               class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                               id="<?php echo esc_attr( uniqid( 'username_' ) ); ?>"
                               value="<?php if ( ! empty( $_POST['username'] ) ) {
							       echo esc_attr( $_POST['username'] );
						       } ?>"/>
                    </p>
                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <input placeholder="<?php esc_attr_e( 'Password', 'funiter' ); ?>"
                               class="woocommerce-Input woocommerce-Input--text input-text" type="password"
                               name="password" id="<?php echo esc_attr( uniqid( 'password_' ) ); ?>"/>
                    </p>
					
					<?php do_action( 'woocommerce_login_form' ); ?>

                    <p class="form-row">
						<?php
						$login_nonce = wp_create_nonce( 'woocommerce-login' );
						?>
                        <input type="hidden" id="<?php echo esc_attr( uniqid( 'woocommerce-login-nonce-' ) ); ?>"
                               name="woocommerce-login-nonce" value="<?php echo esc_attr( $login_nonce ); ?>"/>
						<?php wp_referer_field(); ?>
                        <label for="<?php echo esc_attr( $rememberme_id ); ?>" class="rememberme">
                            <input class="woocommerce-Input woocommerce-Input--checkbox" name="rememberme"
                                   type="checkbox" id="<?php echo esc_attr( $rememberme_id ); ?>"
                                   value="forever"/>
                            <span><?php esc_html_e( 'Remember me', 'funiter' ); ?></span>
                        </label>
                        <a class="woocommerce-LostPassword lost_password"
                           href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'funiter' ); ?></a>

                        <input type="submit" class="woocommerce-Button button" name="login"
                               value="<?php esc_attr_e( 'Login', 'funiter' ); ?>"/>
                    </p>
                </form>
            </div>
		<div class="spec"><span><?php esc_html_e( 'Or', 'funiter' ); ?></span></div>
		<button class="next-action"><?php esc_html_e( 'Create an account', 'funiter' ); ?></button>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

	</div>

	<div class="myaccount-action u-column2 col-2">
		<div class="login-icon"><span class="flaticon-login"></span></div>
		<h2><?php esc_html_e( 'Register', 'funiter' ); ?></h2>

		<div class="register-form block-form">

                <form method="post" class="register">
					
					<?php do_action( 'woocommerce_register_form_start' ); ?>
					
					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                        <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                            <input placeholder="<?php esc_attr_e( 'Username', 'funiter' ); ?>" type="text"
                                   class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                                   id="<?php echo esc_attr( uniqid( 'reg_username_' ) ); ?>"
                                   value="<?php if ( ! empty( $_POST['username'] ) ) {
								       echo esc_attr( $_POST['username'] );
							       } ?>"/>
                        </p>
					
					<?php endif; ?>

                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <input placeholder="<?php esc_attr_e( 'Email address', 'funiter' ); ?>" type="email"
                               class="woocommerce-Input woocommerce-Input--text input-text" name="email"
                               id="<?php echo esc_attr( uniqid( 'reg_email_' ) ); ?>"
                               value="<?php if ( ! empty( $_POST['email'] ) ) {
							       echo esc_attr( $_POST['email'] );
						       } ?>"/>
                    </p>
					
					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                            <input placeholder="<?php esc_attr_e( 'Password', 'funiter' ); ?>" type="password"
                                   class="woocommerce-Input woocommerce-Input--text input-text" name="password"
                                   id="<?php echo esc_attr( uniqid( 'reg_password_' ) ); ?>"/>
                        </p>
					
					<?php endif; ?>

                    <!-- Spam Trap -->
                    <div style="<?php echo( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label
                                for="trap"><?php esc_html_e( 'Anti-spam', 'funiter' ); ?></label><input type="text"
                                                                                                      name="email_2"
                                                                                                      id="<?php echo esc_attr( uniqid( 'trap_' ) ); ?>"
                                                                                                      tabindex="-1"
                                                                                                      autocomplete="off"/>
                    </div>
					
					<?php do_action( 'woocommerce_register_form' ); ?>
					<?php do_action( 'register_form' ); ?>

                    <p class="woocomerce-FormRow form-row">
						<?php
						$register_nonce = wp_create_nonce( 'woocommerce-register' );
						?>
                        <input type="hidden" id="<?php echo esc_attr( uniqid( 'woocommerce-register-nonce-' ) ); ?>"
                               name="woocommerce-register-nonce" value="<?php echo esc_attr( $register_nonce ); ?>"/>
						<?php wp_referer_field(); ?>
                        <input type="submit" class="woocommerce-Button button" name="register"
                               value="<?php esc_attr_e( 'Register', 'funiter' ); ?>"/>
                    </p>
					
					<?php do_action( 'woocommerce_register_form_end' ); ?>

                </form>
            </div>
		<div class="spec"><span><?php esc_html_e( 'Or', 'funiter' ); ?></span></div>
		<button class="next-action"><?php esc_html_e( 'Back to login', 'funiter' ); ?></button>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
