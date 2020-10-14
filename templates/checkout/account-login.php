<?php
/**
 * Template for displaying log in form.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/form-login.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( is_user_logged_in() ) {
	return;
}

$profile = LP_Global::profile();
$fields  = $profile->get_login_fields();
?>

<input type="radio" id="checkout-account-switch-to-login" checked="checked" name="checkout-account-switch-form" value="login"/>
<div id="checkout-account-login" class="lp-checkout-block left">

	<h4><?php esc_html_e( 'Sign in', 'learnpress' ); ?></h4>

	<ul class="lp-form-fields">
		<?php foreach ( $fields as $field ) : ?>
			<li class="form-field">
				<?php LP_Meta_Box_Helper::show_field( $field ); ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php
	/**
	 * LP Hook
	 *
	 * @since 4.0.0
	 */
	do_action( 'learn-press/after-checkout-account-login-fields' );
	?>

	<input type="hidden" name="learn-press-checkout-nonce" value="<?php echo wp_create_nonce( 'learn-press-checkout-login' ); ?>">
	<p class="lp-checkout-remember">
		<label>
			<input type="checkbox" name="rememberme"/>
			<?php esc_html_e( 'Remember me', 'learnpress' ); ?>
		</label>

		<a class="lp-lost-password-link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
			<?php esc_html_e( 'Lost password?', 'learnpress' ); ?>
		</a>
	</p>

	<p class="lp-checkout-sign-up-link">
		<?php esc_html_e( 'Don\'t have an account?', 'learnpress' ); ?>
		<a href="javascript: void(0);">
			<label for="checkout-account-switch-to-register"><?php echo esc_html_x( 'Sign up', 'checkout sign up link', 'learnpress' ); ?></label>
		</a>.

		<?php learn_press_get_template( 'checkout/guest-checkout-link' ); ?>
	</p>
</div>
