<?php
/**
 * Plugin Name: WP Booking System - Search Add-on
 * Plugin URI: https://www.wpbookingsystem.com/
 * Description: A search add-on for WP Booking System
 * Version: 1.0.11
 * Author: Veribo, Roland Murg
 * Author URI: https://www.wpbookingsystem.com/
 * Text Domain: wp-booking-system-search
 * License: GPL2
 *
 * == Copyright ==
 * Copyright 2019 WP Booking System (www.wpbookingsystem.com)
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 *
 */
class WP_Booking_System_Search
{

    /**
     * The current instance of the object
     *
     * @access private
     * @var    WP_Booking_System_Search
     *
     */
    private static $instance;

    /**
     * A list with the objects that handle submenu pages
     *
     * @access public
     * @var    array
     *
     */
    public $submenu_pages = array();

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        // Check if main plugin is active
        if (!in_array('wp-booking-system-premium/index.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            return false;
        }

        // Defining constants
        define('WPBS_S_VERSION', '1.0.11');
        define('WPBS_S_MIN_WPBS_VERSION', '5.5.1');
        define('WPBS_S_FILE', __FILE__);
        define('WPBS_S_BASENAME', plugin_basename(__FILE__));
        define('WPBS_S_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('WPBS_S_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

        $this->include_files();

        define('WPBS_S_TRANSLATION_TEXTDOMAIN', 'wp-booking-system-search');

        // Check if we have the required version of the main plugin.
        add_action('plugins_loaded', array($this, 'requirement_check'), 10);

        // Check if just updated
        add_action('plugins_loaded', array($this, 'update_check'), 20);

        // Load the textdomain and the translation folders
        add_action('plugins_loaded', array($this, 'load_text_domain'), 30);

        // Admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Front-end scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_front_end_scripts'));

        // Remove plugin query args from the URL
        add_filter('removable_query_args', array($this, 'removable_query_args'));

        register_activation_hook(__FILE__, array($this, 'set_cron_jobs'));
        register_deactivation_hook(__FILE__, array($this, 'unset_cron_jobs'));

        /**
         * Plugin initialized
         *
         */
        do_action('wpbs_s_initialized');

    }

    /**
     * Returns an instance of the plugin object
     *
     * @return WP_Booking_System_Search
     *
     */
    public static function instance()
    {

        if (!isset(self::$instance) && !(self::$instance instanceof WP_Booking_System)) {
            self::$instance = new WP_Booking_System_Search;
        }

        return self::$instance;

    }

    /**
     * Checks to see if the add-on is compatible with the main plugin
     *
     * @return void
     *
     */
    public function requirement_check()
    {

        if (version_compare(WPBS_S_MIN_WPBS_VERSION, WPBS_VERSION) > 0) {
            // Add-on Installed
            wpbs_admin_notices()->register_notice('search_mimimum_version', '<p>' . __('The <strong>Search</strong> Add-on requires WP Booking System version ' . WPBS_S_MIN_WPBS_VERSION . ' or greater. Please <a href="'.admin_url('plugins.php').'">update</a> to the latest version.', 'wp-booking-system-search') . '</p>', 'error');
            wpbs_admin_notices()->display_notice('search_mimimum_version');
        }
    }

    /**
     * Checks to see if the current version of the plugin matches the version
     * saved in the database
     *
     * @return void
     *
     */
    public function update_check()
    {

        $db_version = get_option('wpbs_s_version', '');
        $do_update = false;

        // If current version number differs from saved version number
        if ($db_version != WPBS_S_VERSION) {

            $do_update = true;

            // Update the version number in the db
            update_option('wpbs_s_version', WPBS_S_VERSION);

            // Add first activation time
            if (get_option('wpbs_s_first_activation', '') == '') {
                update_option('wpbs_s_first_activation', time());
            }

        }

        if ($do_update) {

            // Hook for fresh update
            do_action('wpbs_s_update_check', $db_version);

            // Trigger set cron jobs
            $this->set_cron_jobs();

        }

    }

    /**
     * Loads plugin text domain
     *
     */
    public function load_text_domain()
    {

        $locale = apply_filters( 'plugin_locale', get_locale(), WPBS_S_TRANSLATION_TEXTDOMAIN );

        // Search for Translation in /wp-content/languages/plugin/
        if (file_exists(trailingslashit( WP_LANG_DIR ) . 'plugins' . WPBS_S_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo')) {
            load_plugin_textdomain(WPBS_S_TRANSLATION_TEXTDOMAIN, false, trailingslashit( WP_LANG_DIR ));
        }
        // Search for Translation in /wp-content/languages/
        elseif (file_exists(trailingslashit( WP_LANG_DIR ) . WPBS_S_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo')) {
            load_textdomain(WPBS_S_TRANSLATION_TEXTDOMAIN, trailingslashit( WP_LANG_DIR ) . WPBS_S_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo');
        // Search for Translation in /wp-content/plugins/wp-booking-system-premium/languages/
        } else {
            load_plugin_textdomain(WPBS_S_TRANSLATION_TEXTDOMAIN, false, plugin_basename(dirname(__FILE__)) . '/languages');
        }

    }

    /**
     * Sets an action hook for modules to add custom schedules
     *
     */
    public function set_cron_jobs()
    {

        do_action('wpbs_s_set_cron_jobs');

    }

    /**
     * Sets an action hook for modules to remove custom schedules
     *
     */
    public function unset_cron_jobs()
    {

        do_action('wpbs_s_unset_cron_jobs');

    }

    /**
     * Include files
     *
     * @return void
     *
     */
    public function include_files()
    {

        /**
         * Include all functions.php files from all plugin folders
         *
         */
        $this->_recursively_include_files(WPBS_S_PLUGIN_DIR . 'includes');

        /**
         * Helper hook to include files early
         *
         */
        do_action('wpbs_s_include_files');

    }

    /**
     * Recursively includes all functions.php files from the given directory path
     *
     * @param string $dir_path
     *
     */
    protected function _recursively_include_files($dir_path)
    {

        $folders = array_filter(glob($dir_path . '/*'), 'is_dir');

        foreach ($folders as $folder_path) {

            if (file_exists($folder_path . '/functions.php')) {
                include $folder_path . '/functions.php';
            }

            $this->_recursively_include_files($folder_path);

        }

    }

    /**
     * Enqueue the scripts and style for the admin area
     *
     */
    public function enqueue_admin_scripts($hook)
    {

        // Plugin styles
        wp_register_style('wpbs-s-admin-style', WPBS_S_PLUGIN_DIR_URL . 'assets/css/style-admin.css', array(), WPBS_S_VERSION);
        wp_enqueue_style('wpbs-s-admin-style');

        // Plugin script
        wp_register_script('wpbs-s-admin-script', WPBS_S_PLUGIN_DIR_URL . 'assets/js/script-admin.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-ui-datepicker'), WPBS_S_VERSION);
        wp_enqueue_script('wpbs-s-admin-script');

        /**
         * Hook to enqueue scripts immediately after the plugin's scripts
         *
         */
        do_action('wpbs_s_enqueue_admin_scripts');

    }

    /**
     * Enqueue the scripts and style for the front-end part
     *
     */
    public function enqueue_front_end_scripts()
    {

        // Plugin styles
        wp_register_style('wpbs-s-style', WPBS_S_PLUGIN_DIR_URL . 'assets/css/style-front-end.min.css', array(), WPBS_S_VERSION);
        wp_enqueue_style('wpbs-s-style');

        wp_register_script('wpbs-s-script', WPBS_S_PLUGIN_DIR_URL . 'assets/js/script-front-end.min.js', array('jquery', 'jquery-ui-datepicker'), WPBS_S_VERSION, true);
        wp_localize_script('wpbs-s-script', 'wpbs_s_localized_data', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'search_form_nonce' => wp_create_nonce('wpbs_s_search_form'),
            'date_format' => wpbs_convert_php_to_jqueryui_format(get_option('date_format')),
        ));
        wp_enqueue_script('wpbs-s-script');

        wp_enqueue_style('dashicons');

        /**
         * Hook to enqueue scripts immediately after the plugin's scripts
         *
         */
        do_action('wpbs_s_enqueue_front_end_scripts');

    }

    /**
     * Removes the query variables from the URL upon page load
     *
     */
    public function removable_query_args($args = array())
    {

        $args[] = 'wpbs_s_message';

        return $args;

    }

}

/**
 * Returns the WP Booking System instanced object
 *
 */
function wp_booking_system_search()
{

    return WP_Booking_System_Search::instance();

}

// Let's get the party started
wp_booking_system_search();
