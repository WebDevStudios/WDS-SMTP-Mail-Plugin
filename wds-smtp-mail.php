<?php
/**
 * Plugin Name: WDS SMTP Mail Plugin
 * Plugin URI: http://webdevstudios.com
 * Description:Reconfigures the wp_mail() function to use SMTP instead of mail()
 * Author: WebDevStudios
 * Author URI: http://webdevstudios.com
 * Version: 1.0.0
 * License: GPLv2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'WDS_SMTP_Mail' ) ):

class WDS_SMTP_Mail {
	public $options = array(
		'mail_from' => null,
		'mail_from_name' => null,
		'mailer' => 'smtp',
		'mail_set_return_path' => false,
		'smtp_host' => 'localhost',
		'smtp_port' => '25',
		'smtp_ssl' => 'none',
		'smtp_auth' => false,
		'smtp_user' => null,
		'smtp_pass' => null,
		'smtp_user' => null,
	);

	public function __construct() {
		// Set base vars for plugin
		$this->basename = plugin_basename( __FILE__ );
		$this->directory_path = plugin_dir_path( __FILE__ );
		$this->directory_url = plugins_url( dirname( $this->basename ) );
	}

	// For the hooks
	public function do_hooks() {
		// Include required files
		add_action( 'init', array( $this, 'includes' ) );

		// Activation/Deactivation Hooks
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Add an action on phpmailer_init
		add_action( 'phpmailer_init', array( $this, 'phpmailer_init_smtp' ) );

		// Whitelist our options
		add_filter( 'whitelist_options', array( $this, 'whitelist_options' ) );

		//Add the create pages options
		add_action( 'admin_menu', array( $this, 'menus' ) );

		// Adds "Settings" link to the plugin action page
		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

		// Add filters to replace the mail from name and email address
		add_filter( 'wp_mail_from', array( $this, 'mail_from' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'mail_from_name' ) );
	}

	// Include Plugin Dependencies
	public function includes() { }

	// Activation tasks
	public function activate() {
		foreach( $this->options as $name => $value ) {
			add_option( $name, $value );
		}
	}

	// Deactivation tasks
	public function deactivate() { }

	public function whitelist_options( $whitelist_options ) {
		$whitelist_options['email'] =  array_keys( $this->options );
		return $whitelist_options;
	}

	public function phpmailer_init_smtp( $phpmailer ) {
		// We can't use smtp without a host
		if( !get_option( 'mailer' ) || ( 'smtp' == get_option( 'mailer' ) && !get_option( 'smtp_host' )) ) {
			return;
		}

		// Set the mailer type - overriding isMail method
		$phpmailer->Mailer = get_option( 'mailer' );

		// Sender return path
		if( get_option( 'mail_set_return_path' ) )
			$phpmailer->Sender = $phpmailer->From;

		// Set SMTPSecure value
		$phpmailer->SMTPSecure = 'none' == get_option( 'smtp_ssl' ) ? '' : get_option( 'smtp_ssl' );

		if( 'smtp' == get_option( 'mailer' ) ) {
			// Set the SMTPSecure value
			$phpmailer->SMTPSecure = get_option( 'smtp_ssl' ) == 'none' ? '' : get_option( 'smtp_ssl' );

			$phpmailer->Host = get_option( 'smtp_host' );
			$phpmailer->Port = get_option( 'smtp_port' );

			// Is SMTP Auth on?
			if( get_option( 'smtp_auth' ) == true ) {
				$phpmailer->SMTPAuth = true;
				$phpmailer->Username = get_option( 'smtp_user' );
				$phpmailer->Password = get_option( 'smtp_pass' );
			}
		}

		$phpmailer = apply_filters( 'smtp_custom_options', $phpmailer );
	}

	public function options_page() {
		global $phpmailer;

		if( ! is_object( $phpmailer ) || ! is_a( $phpmailer, 'PHPMailer' ) ) {
			require_once ABSPATH . WPINC . '/class-phpmailer.php';
			$phpmailer = new PHPMailer(true);
		}

		if( isset( $_POST['wds_smtp_action'] )
			&& $_POST['wds_smtp_action'] == __( 'Send Test', 'wds_smtp' )
			&& isset( $_POST['to'] )
		) {
			check_admin_referer('test-email');

			// Set up the mail vars
			$to = $_POST['to'];
			$subject = 'wds SMTP: ' . __( 'Test mail to ', 'wds_smtp' ) . $to;
			$message = __('This is a test email generated by the wds SMTP WordPress plugin');

			// Set SMTPDebug to true
			$phpmailer->SMTPDebug = true;

			// Start output buffer
			ob_start();

			$result = wp_mail( $to, $subject, $message );

			$smtp_debug = ob_get_clean();
			?>
			<div id="message" class="updated fade">
				<p><strong><?php _e( 'Test Message Sent', 'wds_smtp' ); ?></strong></p>
				<p><?php _e( 'The result was:', 'wds_smtp' ); ?></p>
				<pre><?php var_dump( $result ); ?></pre>
				<p><?php _e( 'The full debugging output is shown below:', 'wds_smtp' ); ?></p>
				<pre><?php var_dump( $phpmailer ); ?></pre>
				<p><?php _e( 'The SMTP debugging output is shown below:', 'wds_smtp' ); ?></p>
				<pre><?php echo $smtp_debug ?></pre>
			</div>
			<?php
			// Destroy the mailer
			unset( $phpmailer );
		}

		require_once( 'options-form.php' );
	}

	public function menus() {
		add_options_page(
			__( 'Advanced Email Options', 'wds_smtp' ),
			__( 'Email Options', 'wds_smtp' ),
			'manage_options',
			'wds_smtp_mail',
			array( $this, 'options_page' )
		);
	}

	// Sets the from email value
	public function mail_from( $orig ) {
		// This is copied from pluggable.php lines 348-354 as at revision 10150
		// http://trac.wordpress.org/browser/branches/2.7/wp-includes/pluggable.php#L348

		// Get the site domain and get rid of www.
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );

		if( 'www.' == substr( $sitename, 0, 4 ) ) {
			$sitename = substr( $sitename, 4 );
		}

		$default_from = 'wordpress@' . $sitename;
		// End of copied code

		// If the from email is not the default, return it unchanged
		if( $orig != $default_from ) {
			return $orig;
		}

		if( is_email( get_option( 'mail_from' ), false ) ) {
			return get_option( 'mail_from' );
		}

		// If in doubt, return the original value
		return $orig;
	}

	// This function sets the from name value
	public function mail_from_name( $orig ) {
		// Only filter if the from name is the default
		if( $orig == 'WordPress' ) {
			if( "" != get_option( 'mail_from_name' ) && is_string( get_option( 'mail_from_name' ) ) ) {
				return get_option( 'mail_from_name' );
			}
		}

		// return the original value
		return $orig;
	}

	public function plugin_action_links( $links, $file ) {
		if( $file != plugin_basename( __FILE__ ) ) {
			return $links;
		}

		$settings_link = '<a href="options-general.php?page=wds_smtp_mail">' . __( 'Settings', 'wds_smtp' ) . '</a>';

		array_unshift($links, $settings_link);
		return $links;
	}
}

$GLOBALS['wds_smtp'] = new WDS_SMTP_Mail;
$GLOBALS['wds_smtp']->do_hooks();

endif; // end class_exists check
