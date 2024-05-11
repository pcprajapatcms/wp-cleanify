<?php
/**
 * WP_Cleanify.
 *
 * @package WP_Cleanify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WP_Cleanify_Setting_Options.
 *
 * @since 1.0.0
 */
class WP_Cleanify_Setting_Options {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setup_plugin_setting_options();
	}

	/**
	 * Setup plugin setting options
	 */
	public function setup_plugin_setting_options() {
		$this->wp_cleanify_remove_emoji_emojis();
		$this->wp_cleanify_remove_global_styles();
		$this->wp_cleanify_remove_rsd_link();
		$this->wp_cleanify_remove_shortlink();
		$this->wp_cleanify_remove_wp_version();
		$this->wp_cleanify_disable_wp_frontend();
		$this->wp_cleanify_redirect_rss_feed();
		add_action( 'wp_footer', array( $this, 'wp_cleanify_remove_embed' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_cleanify_remove_dashicons' ) );
		add_action( 'init', array( $this, 'wp_cleanify_disable_heartbeat' ) );
		add_action( 'init', array( $this, 'wp_cleanify_disable_restapi' ) );
		add_action( 'init', array( $this, 'wp_cleanify_disable_rss_feed' ) );
		add_action( 'init', array($this, 'wp_cleanify_redirect_on_custom_login_url') );
		add_filter( 'lostpassword_url', array($this, 'wp_cleanify_customize_lost_password_url') );
		add_filter( 'register_url', array($this, 'wp_cleanify_customize_register_url') );
		add_action( 'wp_loaded', array($this, 'wp_cleanify_redirect_on_default_login_urls') );
		add_action( 'wp_login_failed', array($this, 'wp_cleanify_redirect_to_custom_login_url_on_login_fail') );
		add_filter( 'login_message', array($this, 'wp_cleanify_add_failed_login_message') );
		add_action( 'wp_logout', array($this, 'wp_cleanify_redirect_to_custom_login_url_on_logout_success') );
	}

	/**
	 * Remove_emoji Emojis
	 */
	public function wp_cleanify_remove_emoji_emojis() {
		$options                  = get_option( 'wp_cleanify_options' );
		$wp_cleanify_remove_emoji = isset( $options['_wp_cleanify_remove_emoji'] ) ? $options['_wp_cleanify_remove_emoji'] : false;
		if ( $wp_cleanify_remove_emoji ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		}
	}

	/**
	 * Remove Global Styles
	 */
	public function wp_cleanify_remove_global_styles() {
		$options                              = get_option( 'wp_cleanify_options' );
		$wp_cleanify_remove_full_site_editing = isset( $options['_wp_cleanify_remove_full_site_editing'] ) ? $options['_wp_cleanify_remove_full_site_editing'] : false;
		if ( $wp_cleanify_remove_full_site_editing ) {
			remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
			remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
		}
	}

	/**
	 * Remove RSD Link
	 */
	public function wp_cleanify_remove_rsd_link() {
		$options                      = get_option( 'wp_cleanify_options' );
		$wp_cleanify_remove_rsd_links = isset( $options['_wp_cleanify_remove_rsd_links'] ) ? $options['_wp_cleanify_remove_rsd_links'] : false;
		if ( $wp_cleanify_remove_rsd_links ) {
			remove_action( 'wp_head', 'rsd_link' );
		}
	}

	/**
	 * Remove Shortlink
	 */
	public function wp_cleanify_remove_shortlink() {
		$options                      = get_option( 'wp_cleanify_options' );
		$wp_cleanify_remove_shortlink = isset( $options['_wp_cleanify_remove_shortlink'] ) ? $options['_wp_cleanify_remove_shortlink'] : false;
		if ( $wp_cleanify_remove_shortlink ) {
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		}
	}

	/**
	 * Remove Embed JS
	 */
	public function wp_cleanify_remove_embed() {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_disable_embed = isset( $options['_wp_cleanify_disable_embed'] ) ? $options['_wp_cleanify_disable_embed'] : false;
		if ( $wp_cleanify_disable_embed ) {
			wp_dequeue_script( 'wp-embed' );
		}
	}

	/**
	 * Remove WordPress Version
	 */
	public function wp_cleanify_remove_wp_version() {
		$options                        = get_option( 'wp_cleanify_options' );
		$wp_cleanify_disable_wp_version = isset( $options['_wp_cleanify_disable_wp_version'] ) ? $options['_wp_cleanify_disable_wp_version'] : false;
		if ( $wp_cleanify_disable_wp_version ) {
			remove_action( 'wp_head', 'wp_generator' );
		}
	}

	/**
	 * Remove Dashicons from frontend only
	 */
	public function wp_cleanify_remove_dashicons() {
		$options                       = get_option( 'wp_cleanify_options' );
		$wp_cleanify_disable_dashicons = isset( $options['_wp_cleanify_disable_dashicons'] ) ? $options['_wp_cleanify_disable_dashicons'] : false;
		if ( $wp_cleanify_disable_dashicons ) {
			if ( current_user_can( 'manage_options' ) ) {
				return;
			}
				wp_deregister_style( 'dashicons' );
		}
	}

	/**
	 * Disable Heartbeat API from frontend only
	 */
	public function wp_cleanify_disable_heartbeat() {
		$options                        = get_option( 'wp_cleanify_options' );
		$_wp_cleanify_disable_heartbeat = isset( $options['_wp_cleanify_disable_heartbeat'] ) ? $options['_wp_cleanify_disable_heartbeat'] : false;
		if ( $_wp_cleanify_disable_heartbeat ) {
			wp_deregister_script( 'heartbeat' );
		}
	}

	/**
	 * Disable Rest API for non login users only
	 */
	public function wp_cleanify_disable_restapi() {
		$options                     = get_option( 'wp_cleanify_options' );
		$wp_cleanify_disable_restapi = isset( $options['_wp_cleanify_disable_restapi'] ) ? $options['_wp_cleanify_disable_restapi'] : false;
		if ( $wp_cleanify_disable_restapi ) {
			add_filter(
				'rest_endpoints',
				function ( $endpoints ) {
					// Check if the current user is logged in and has the necessary permissions
					if ( is_user_logged_in() && ( current_user_can( 'edit_posts' ) || current_user_can( 'publish_posts' ) || current_user_can( 'delete_posts' ) ) ) {
						// If the user has the necessary permissions, return the endpoints without modifications
						return $endpoints;
					} else {
						// If the user does not have the necessary permissions, disable all endpoints
						foreach ( $endpoints as $route => $handler ) {
							unset( $endpoints[ $route ] );
						}
						return $endpoints;
					}
				}
			);
		}
	}

	/**
	 * Disable WordPress fronted (for headless)
	 */
	public function wp_cleanify_disable_wp_frontend() {
		$options                      = get_option( 'wp_cleanify_options' );
		$wp_cleanify_disable_frontend = isset( $options['_wp_cleanify_disable_frontend'] ) ? $options['_wp_cleanify_disable_frontend'] : false;
		if ( $wp_cleanify_disable_frontend ) {
			add_action( 'template_redirect', 'wp_cleanify_redirect_to_login_if_not_logged_in' );
			function wp_cleanify_redirect_to_login_if_not_logged_in() {
				if ( ! is_user_logged_in() && ! is_admin() ) {
					auth_redirect();
				}
			}
		}
	}

	/**
	 * Disable RSS feed and links
	 */
	public function wp_cleanify_disable_rss_feed() {
		$options                      = get_option( 'wp_cleanify_options' );
		$wp_cleanify_disable_rss_feed = isset( $options['_wp_cleanify_disable_rss_feed'] ) ? $options['_wp_cleanify_disable_rss_feed'] : false;
		if ( $wp_cleanify_disable_rss_feed ) {
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		}
	}

	/**
	 * Redirect feed requests to the original page
	 */
	public function wp_cleanify_redirect_rss_feed() {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_redirect_feed = isset( $options['_wp_cleanify_redirect_feed'] ) ? $options['_wp_cleanify_redirect_feed'] : false;
		if ( $wp_cleanify_redirect_feed ) {
			function wp_cleanify_redirect_feed_requests_to_original_page( $query ) {
				if ( $query->is_feed ) {
					global $wp;
					$current_url  = home_url( add_query_arg( array(), $wp->request ) );
					$original_url = preg_replace( '/\/feed(\/.*|$)/', '', $current_url );
					wp_redirect( $original_url, 301 );
					exit;
				}
			}
			add_action( 'parse_query', 'wp_cleanify_redirect_feed_requests_to_original_page' );
		}
	}

	/**
	 * Custom Login URL
	 */

	public function wp_cleanify_redirect_on_custom_login_url() {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_change_login_url = isset( $options['_wp_cleanify_change_login_url'] ) ? $options['_wp_cleanify_change_login_url'] : false;
		if ( $wp_cleanify_change_login_url ) {
			$wp_cleanify_login_url = get_option('wp_cleanify_login_url');
			$url_input = sanitize_text_field( $_SERVER['REQUEST_URI'] );
			// Make sure $url_input ends with /
			if ( false !== strpos( $url_input, $wp_cleanify_login_url ) ) {
				if ( substr( $url_input, -1 ) != '/' ) {
					$url_input = $url_input . '/';
				}
			}
			// If URL contains the custom login slug, redirect to the dashboard
			if ( false !== strpos( $url_input, '/' . $wp_cleanify_login_url . '/' ) ) {
				if ( is_user_logged_in() ) {
					// Redirect to dashboard
					wp_safe_redirect( get_admin_url() );
				} else {
					// Redirect to the login URL with custom login slug in the query parameters
					wp_safe_redirect( site_url( '/wp-login.php?' . $wp_cleanify_login_url . '&redirect=false' ) );
				}
				exit;
			}
		}
    }

	public function wp_cleanify_customize_login_url( $lostpassword_url ) {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_change_login_url = isset( $options['_wp_cleanify_change_login_url'] ) ? $options['_wp_cleanify_change_login_url'] : false;
		if ( $wp_cleanify_change_login_url ) {
			$wp_cleanify_login_url = get_option('wp_cleanify_login_url');
			return home_url( '/' . $wp_cleanify_login_url . '/' );
		}
    }

	public function wp_cleanify_customize_lost_password_url( $lostpassword_url ) {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_change_login_url = isset( $options['_wp_cleanify_change_login_url'] ) ? $options['_wp_cleanify_change_login_url'] : false;
		if ( $wp_cleanify_change_login_url ) {
			$wp_cleanify_login_url = get_option('wp_cleanify_login_url');
			return $lostpassword_url . '&' . $wp_cleanify_login_url;
		}
    }

	public function wp_cleanify_customize_register_url( $registration_url ) {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_change_login_url = isset( $options['_wp_cleanify_change_login_url'] ) ? $options['_wp_cleanify_change_login_url'] : false;
		if ( $wp_cleanify_change_login_url ) {
			$wp_cleanify_login_url = get_option('wp_cleanify_login_url');
			// return home_url( '/wp-login.php?manage&action=lostpassword' );
			return $registration_url . '&' . $wp_cleanify_login_url;
		}
    }

	public function wp_cleanify_redirect_on_default_login_urls() {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_change_login_url = isset( $options['_wp_cleanify_change_login_url'] ) ? $options['_wp_cleanify_change_login_url'] : false;
		if ( $wp_cleanify_change_login_url ) {
			global $wp_cleanify_login;
			$wp_cleanify_login_url = get_option('wp_cleanify_login_url');
			// e.g. manage
			$url_input = sanitize_text_field( $_SERVER['REQUEST_URI'] );
			$redirect_slug = '404';
			// When logging in
			if ( isset( $_POST['log'] ) && isset( $_POST['pwd'] ) || isset( $_POST['post_password'] ) || is_user_logged_in() ) {
			} else {
				// When landing on the login page
				if ( false !== strpos( $url_input, 'wp-login' ) ) {
					if ( isset( $_GET['action'] ) && ('logout' == $_GET['action'] || 'rp' == $_GET['action'] || 'resetpass' == $_GET['action']) || isset( $_GET['checkemail'] ) && ('confirm' == $_GET['checkemail'] || 'registered' == $_GET['checkemail']) || isset( $_GET['interim-login'] ) && '1' == $_GET['interim-login'] || 'success' == $wp_cleanify_login ) {
					} elseif ( isset( $_GET['action'] ) && ('lostpassword' == $_GET['action'] || 'register' == $_GET['action']) ) {
						// When resetting password or registering an account
						if ( isset( $_POST['user_login'] ) ) {
							// Sending the form to reset password or register an account...
						} else {
							// When landing on the password reset or registration form
							if ( false === strpos( $url_input, $wp_cleanify_login_url ) ) {
								// Redirect to /404/
								wp_safe_redirect( home_url( $redirect_slug . '/' ), 302 );
								exit;
							}
						}
					} else {
						if ( false === strpos( $url_input, $wp_cleanify_login_url ) ) {
							// Redirect to /404/
							wp_safe_redirect( home_url( $redirect_slug . '/' ), 302 );
							exit;
						}
					}
				}
			}
		}
    }

	public function wp_cleanify_redirect_to_custom_login_url_on_login_fail() {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_change_login_url = isset( $options['_wp_cleanify_change_login_url'] ) ? $options['_wp_cleanify_change_login_url'] : false;
		if ( $wp_cleanify_change_login_url ) {
			global $asenha_limit_login;
			$wp_cleanify_login_url = get_option('wp_cleanify_login_url');
			if ( isset( $asenha_limit_login ) && is_array( $asenha_limit_login ) && $asenha_limit_login['within_lockout_period'] ) {
				// Do nothing. This prevents redirection loop.
			} else {
				$should_redirect = true;
				if ( $should_redirect ) {
					// Append 'failed_login=true' so we can output custom error message above the login form
					wp_safe_redirect( home_url( 'wp-login.php?' . $wp_cleanify_login_url . '&redirect=false&failed_login=true' ) );
					exit;
				}
			}
		}
    }

	public function wp_cleanify_add_failed_login_message( $message ) {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_change_login_url = isset( $options['_wp_cleanify_change_login_url'] ) ? $options['_wp_cleanify_change_login_url'] : false;
			if ( $wp_cleanify_change_login_url ) {
			global $asenha_limit_login;
			if ( isset( $_REQUEST['failed_login'] ) && $_REQUEST['failed_login'] == 'true' ) {
				if ( is_null( $asenha_limit_login ) ) {
					$message = '<div id="login_error" class="notice notice-error"><b>Error:</b> Invalid username/email or incorrect password.</div>';
				}
			}
			return $message;
		}
    }

	public function wp_cleanify_redirect_to_custom_login_url_on_logout_success() {
		$options                   = get_option( 'wp_cleanify_options' );
		$wp_cleanify_change_login_url = isset( $options['_wp_cleanify_change_login_url'] ) ? $options['_wp_cleanify_change_login_url'] : false;
		if ( $wp_cleanify_change_login_url ) {
			$wp_cleanify_login_url = get_option('wp_cleanify_login_url');
			// Redirect to the login URL with custom login slug in it
			wp_safe_redirect( home_url( 'wp-login.php?' . $wp_cleanify_login_url . '&redirect=false' ) );
			exit;
		}
    }
}

/**
 *  Prepare if class 'WP_Cleanify_Setting_Options' exist. Kicking this off by creating 'new' instance.
 */
new WP_Cleanify_Setting_Options();
