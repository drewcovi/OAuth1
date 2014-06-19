<?php
login_header(
	__('Authorize', 'oauth'),
	'',
	$errors
);

$current_user = wp_get_current_user();

$url = site_url( 'wp-login.php?action=oauth1_authorize', 'login_post' );
if($current_user->ID == 0){
$rememberme = !empty($_POST['rememberme']);
$interim_login = isset($_REQUEST['interim-login']);
$customize_login = isset($_REQUEST['customize-login']);
$query = $_SERVER['QUERY_STRING'];
// if (isset($_REQUEST['redirect_to'])) {
//     $redirect_to = $_REQUEST['redirect_to'];
//     // Redirect to https if user wants ssl
//     if ($secure_cookie && false !== strpos($redirect_to, 'wp-admin')) $redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
// } else {
//     $redirect_to = $url;
// }
?>
<form
	name="loginform"
	id="loginform"
	action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>"
    method="post">
	<p>
		<label
			for="user_login">
			<?php _e('Username') ?>
			<br />
			<input
				type="text"
				name="log"
				id="user_login"
				class="input"
				value=""
				size="20" />
		</label>
	</p>
	<p>
		<label for="user_pass">
			<?php _e('Password') ?>
			<br />
			<input
				type="password"
				name="pwd"
				id="user_pass"
				class="input"
				value=""
				size="20" />
		</label>
	</p>
	<?php
        
        /**
         * Fires following the 'Password' field in the login form.
         *
         * @since 2.1.0
         */
        do_action('login_form');
	?>
	<p class="forgetmenot">
		<label
			for="rememberme">
			<input
				name="rememberme"
				type="checkbox"
				id="rememberme"
				value="forever"
				<?php checked($rememberme); ?> />
				<?php esc_attr_e('Remember Me'); ?>
		</label>
	</p>
	<p class="submit">
		<input
			type="submit"
			name="wp-submit"
			id="wp-submit"
			class="button button-primary button-large"
			value="<?php esc_attr_e('Log In'); ?>"/>
		<?php if ($interim_login) { ?>
		<input
			type="hidden"
			name="interim-login"
			value="1" />
		<?php } else { ?>
		<input
			type="hidden"
			name="redirect_to"
			value="<?php echo esc_attr($url.'&'.$query); ?>" />
		<?php } ?>
		<?php if ($customize_login): ?>
		<input
			type="hidden"
			name="customize-login"
			value="1" />
		<?php
        endif; ?>
		<input
			type="hidden"
			name="testcookie"
			value="1" />
	</p>
</form>
<?php
}else{
?>
<form
	name="oauth1_authorize_form"
	id="oauth1_authorize_form"
	action="<?php echo esc_url( $url ); ?>"
	method="post">

	<h2>
		<?php
		echo esc_html(
				sprintf( __('Connect %1$s'),
				$consumer->post_title )
				);
		?>
	</h2>
	<p>
		<?php
		printf(
				__( 'Howdy <strong>%1$s</strong>, "%2$s" would like to connect to %3$s.'
				),
				$current_user->user_login,
				$consumer->post_title,
				get_bloginfo( 'name' )
			);
		?>
	</p>
	<?php
	/**
	 * Fires inside the lostpassword <form> tags, before the hidden fields.
	 *
	 * @since 2.1.0
	 */
	do_action( 'oauth1_authorize_form', $consumer ); ?>
	<p class="submit">
		<button
			type="submit"
			name="wp-submit"
			value="cancel"
			class="button button-primary button-large">
			<?php _e('Cancel'); ?>
		</button>
		<button
			type="submit"
			name="wp-submit"
			value="authorize"
			class="button button-primary button-large">
			<?php _e('Authorize'); ?>
		</button>
	</p>
</form>

<p id="nav">
	<a
		href="<?php echo esc_url( wp_login_url( $url, true ) ); ?>">
		<?php _e( 'Switch user' ) ?>
	</a>
	<?php
	if ( get_option( 'users_can_register' ) ) {
		$registration_url = sprintf(
								'<a href="%s">%s</a>',
								esc_url( wp_registration_url() ),
								__( 'Register' )
								);
		/**
		 * Filter the registration URL below the login form.
		 *
		 * @since 1.5.0
		 *
		 * @param string $registration_url Registration URL.
		 */
		echo ' | ' . apply_filters( 'register', $registration_url );
	}
	?>
</p>
<?php
}
login_footer();
