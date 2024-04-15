<?php
/**
 * Register & load the option page for wp cleanify
 *
 * @since      1.0.0
 * @author     PhoolChand <wp.pcprajapat@gmail.com>
 *
 * @package    WP_Cleanify
 * @subpackage WP_Cleanify/classes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WP_Cleanify_Option_Page {
    /**
     * Constructor
     */
    public function __construct() {
        $this->setup_actions_filters();
    }

    /**
     * Setup Actions Filters.
     *
     * @since 1.0.0
     */
    private function setup_actions_filters() {
        add_action('admin_menu', array( $this, 'wp_cleanify_plugin_menu') );
        add_action('admin_init', array( $this, 'wp_cleanify_register_settings') );
        add_action('wp_ajax_wp_cleanify_handle_checkbox_toggle', array($this, 'wp_cleanify_handle_checkbox_toggle'));
    }

    /**
     * Create the plugin menu item.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function wp_cleanify_plugin_menu() {
        add_menu_page('WP Cleanify', 'WP Cleanify', 'manage_options', 'wp-cleanify-settings', array( $this,'wp_cleanify_settings_page'), WP_CLEANIFY_URL . 'assets/images/icons/wp-cleanify-icon.svg');
    }

    /**
     * Create the plugin page.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function wp_cleanify_settings_page() {
        ?>
        <section class="wp-cleanify-settings-wrapper">
            <div class="wp-cleanify-settings-header wp-cleanify-shadow">
                <div class="wp-cleanify-header-isrow">
                    <div class="wp-cleanify-header-lside">
                        <div class="wp-cleanify-header-logo">
                            <img class="wp-cleanify-header-logo__image" src="<?php echo WP_CLEANIFY_URL . 'assets/images/wp-cleanify-logo.svg'; ?>" alt="wp cleanify" width="160" heigh="35">
                        </div>
                        <div class="wp-cleanify-header-msg">
                            <h1 class="wp-cleanify-header-msg__title">WP Cleanify Settings</h1>
                        </div>
                    </div>
                    <div class="wp-cleanify-header-rside">
                        <div class="wp-cleanify-header-kb">
                            <a class="wp-cleanify-header-kb__link" href="#">Knowledge Base</a>
                        </div>
                        <div class="wp-cleanify-header-version">
                            <span class="wp-cleanify-header-version__number">1.0.0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wp-cleanify-settings-content">
                <div class="wrap container">
                    <div class="wp-cleanify-toast" style="display:none;">
                        <div class="wp-cleanify-settings-message">
                            <div class="wp-cleanify-msg-wrapper">
                                <div class="wp-cleanify-msg-icon">
                                    <img src="<?php echo WP_CLEANIFY_URL . 'assets/images/icons/checkmark.svg'; ?>" alt="check" width="52" height="52">
                                </div>
                                <div class="wp-cleanify-msg-text">Successfully saved!</div>
                            </div>
                            <div class="wp-cleanify-msg-close">
                                <img src="<?php echo WP_CLEANIFY_URL . 'assets/images/icons/close.svg'; ?>" alt="close" width="52" height="52" class="msg-close-btn">
                            </div>
                        </div>
                    </div>
                    <form method="post" action="options.php">
                        <?php settings_fields('wp_cleanify_options'); ?>
                        <div class="custom-settings-section">
                            <div class="grid">
                                <?php $this->wp_cleanify_section_callback(); ?>
                            </div>
                        </div>
                        <?php submit_button(); ?>
                        <input type="hidden" id="wp_cleanify_ajax_nonce" value="<?php echo wp_create_nonce( 'wp-cleanify-nonce' ); ?>">
                    </form>
                </div>
            </div>
        </section>

        <?php
    }

    // Section callback
    public function wp_cleanify_section_callback() {
        $remove_emoji_icon_svg = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/remove_emoji_icon.svg" alt="remove emoji" width="52" height="52">
        </div>';
        $remove_full_site_editing = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/remove_full_site_editing.svg" alt="remove site editing" width="52" height="52">
        </div>';
        $remove_rsd_links = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/remove_rsd_links.svg" alt="remove rsd links" width="52" height="52">
        </div>';
        $remove_shortlink = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/remove_shortlink.svg" alt="remove shortlinks" width="52" height="52">
        </div>';
        $disable_embed = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/disable_embed.svg" alt="remove embed" width="52" height="52">
        </div>';
        $disable_heartbeat = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/heartbeat-icon.svg" alt="remove heartbeat" width="52" height="52">
        </div>';
        $disable_wp_version = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/disable_wp_version.svg" alt="remove wp version" width="52" height="52">
        </div>';
        $disable_dashicons = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/disable_dashicons.svg" alt="remove dashicons" width="52" height="52">
        </div>';
        $disable_restapi = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/disable_restapi.svg" alt="remove rest api" width="52" height="52">
        </div>';
        $disable_frontend = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/disable_frontend.svg" alt="remove frontend" width="52" height="52">
        </div>';
        $disable_rss_feed = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/rss-feed.svg" alt="remove rss feed" width="52" height="52">
        </div>';
        $redirect_feeds = '<div class="wp-cleanify-icon">
            <img src="'.WP_CLEANIFY_URL . 'assets/images/icons/redirect-feed.svg" alt="redirect feed" width="52" height="52">
        </div>';
        $checkbox_fields = array(
            '_wp_cleanify_remove_emoji' => '<div class="wp-cleanify-box-header">'.$remove_emoji_icon_svg.'<div class="wp-cleanify-box-content"><h3>Remove Emoji Scripts</h3> <p>Remove the emoji scripts and styles from both the front-end and admin areas of your WordPress site.</p></div></div>',

            '_wp_cleanify_remove_full_site_editing' => '<div class="wp-cleanify-box-header">'.$remove_full_site_editing.'<div class="wp-cleanify-box-content"><h3>Remove Global Styles </h3> <p>Remove various aspects of the Global Styles feature, such as default styles, custom color palettes, custom font sizes.</p></div></div>',

            '_wp_cleanify_remove_rsd_links' => '<div class="wp-cleanify-box-header">'.$remove_rsd_links.'<div class="wp-cleanify-box-content"><h3>Remove RSD Links </h3> <p> Remove the RSD link from your WordPress site\'s header. The RSD link should no longer appear in the HTML output.</p></div></div>',

            '_wp_cleanify_remove_shortlink' => '<div class="wp-cleanify-box-header">'.$remove_shortlink.'<div class="wp-cleanify-box-content"><h3>Remove Shortlink </h3> <p> Prevent WordPress from outputting the shortlink meta tag in the HTML header and tag should no longer appear in the HTML.</p></div></div>',

            '_wp_cleanify_disable_embed' => '<div class="wp-cleanify-box-header">'.$disable_embed.'<div class="wp-cleanify-box-content"><h3>Disable Embed </h3> <p> Turn off oEmbed auto-discovery, remove oEmbed discovery links, and prevent oEmbed-specific JavaScript.</p></div></div>',

            '_wp_cleanify_disable_heartbeat' => '<div class="wp-cleanify-box-header">'.$disable_heartbeat.'<div class="wp-cleanify-box-content"><h3>Disable Heartbeat</h3> <p>Disabling the Heartbeat API in WordPress reduces server load, conserves resources, and addresses privacy concerns by preventing real-time communication</p></div></div>',

            '_wp_cleanify_disable_wp_version' => '<div class="wp-cleanify-box-header">'.$disable_wp_version.'<div class="wp-cleanify-box-content"><h3>Hide WordPress Version</h3> <p>Hiding the WordPress version is a common security measure to prevent potential attackers from exploiting vulnerabilities.</p></div></div>',

            '_wp_cleanify_disable_dashicons' => '<div class="wp-cleanify-box-header">'.$disable_dashicons.'<div class="wp-cleanify-box-content"><h3>Disable Dashicons</h3> <p>By preventing Dashicons from loading, it reduces the number of HTTP requests and page size, leading to faster loading times. </p></div></div>',

            '_wp_cleanify_disable_restapi' => '<div class="wp-cleanify-box-header">'.$disable_restapi.'<div class="wp-cleanify-box-content"><h3>Disable REST API Endpoints</h3> <p>Reducing potential security vulnerabilities. However, disabling the REST API may affect certain plugin functionalities. </p></div></div>',

            '_wp_cleanify_disable_frontend' => '<div class="wp-cleanify-box-header">'.$disable_frontend.'<div class="wp-cleanify-box-content"><h3>Disable WordPress Frontend</h3> <p>Disable WP Frontend for headless WordPress" ensures that WordPress does not render its frontend. Instead, the frontend is built in a different technology.</p></div></div>',

            '_wp_cleanify_disable_rss_feed' => '<div class="wp-cleanify-box-header">'.$disable_rss_feed.'<div class="wp-cleanify-box-content"><h3>Disable RSS Feed & Links</h3> <p>Disabling RSS feeds in WordPress enhances performance by reducing server load and streamlining content delivery.</p></div></div>',

            '_wp_cleanify_redirect_feed' => '<div class="wp-cleanify-box-header">'.$redirect_feeds.'<div class="wp-cleanify-box-content"><h3>Redirect Feed Url</h3> <p>The option redirects RSS feed requests to the main webpage, ensuring visitors access content directly, maintaining engagement, and avoiding accidental feed redirection.</p></div></div>',
        );

        foreach ($checkbox_fields as $option => $label) {
            $this->checkbox_callback(array('option' => $option, 'label' => $label));
        }
    }

    // Checkbox callback function
    public function checkbox_callback($args) {
        $options = get_option('wp_cleanify_options');
        $checked = isset($options[$args['option']]) ? $options[$args['option']] : false;
        ?>
        <div class="wp-celanify-box">
                <?php echo $args['label']; ?>
            <div class="wp-celanify-box-footer">
                <div class="checkbox_item citem_1">
                    <label class="checkbox_wrap">
                        <input type="checkbox" class="checkbox_inp" id="<?php echo esc_attr($args['option']); ?>" name="wp_cleanify_options[<?php echo esc_attr($args['option']); ?>]" value="1" <?php checked(1, $checked, true); ?>>
                        <span class="checkbox_mark"></span>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    // Register settings and fields
    public function wp_cleanify_register_settings() {
        register_setting('wp_cleanify_options', 'wp_cleanify_options', array($this, 'wp_cleanify_options_sanitize'));
    }

    // Sanitize input
    public function wp_cleanify_options_sanitize($input) {
        $output = array();
        foreach ($input as $key => $value) {
            $output[$key] = isset($value) ? true : false;
        }
        return $output;
    }

    // AJAX callback function to handle checkbox toggle
    public function wp_cleanify_handle_checkbox_toggle() {
        // Check if the request is coming from a valid source
        if ( ! check_ajax_referer( 'wp-cleanify-nonce', 'security', false ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        // Get the option name and new value from the AJAX request
        $option_name = isset( $_POST['option_name'] ) ? sanitize_key( $_POST['option_name'] ) : '';
        $new_value   = isset( $_POST['new_value'] ) ? intval( $_POST['new_value'] ) : 0;
        // Get the existing options array
        $options_array = get_option('wp_cleanify_options');
        // If the options array doesn't exist, create an empty array
        if ( ! $options_array ) {
            $options_array = array();
        }
        // If the specified option doesn't exist, add it
        if ( ! isset( $options_array[$option_name] ) ) {
            $options_array[$option_name] = $new_value;
            update_option('wp_cleanify_options', $options_array);
            wp_send_json_success( 'Option added successfully' );
            return;
        }
        // Remove the specified option
        unset( $options_array[$option_name] );
        // Update the option with the modified value
        update_option('wp_cleanify_options', $options_array);
        // Send a success response
        wp_send_json_success( 'Option updated successfully' );
    }
}

/**
 *  Prepare if class 'WP_Cleanify_Option_Page' exist. Kicking this off by creating 'new' instance.
 */
new WP_Cleanify_Option_Page();
?>
