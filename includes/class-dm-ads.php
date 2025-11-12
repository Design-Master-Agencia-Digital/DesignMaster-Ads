<?php
/**
 * The core plugin class
 */
class DM_Ads {
    
    /**
     * The loader that's responsible for maintaining and registering all hooks
     */
    protected $loader;
    
    /**
     * The unique identifier of this plugin
     */
    protected $plugin_name;
    
    /**
     * The current version of the plugin
     */
    protected $version;
    
    /**
     * Initialize the class
     */
    public function __construct() {
        $this->version = DM_ADS_VERSION;
        $this->plugin_name = 'designmaster-ads';
        
        $this->load_dependencies();
        $this->load_textdomain();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    /**
     * Load plugin text domain for translations
     */
    private function load_textdomain() {
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    }
    
    /**
     * Load the plugin text domain for translation
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'designmaster-ads',
            false,
            dirname(DM_ADS_PLUGIN_BASENAME) . '/languages/'
        );
    }
    
    /**
     * Load the required dependencies
     */
    private function load_dependencies() {
        // Core classes
        require_once DM_ADS_PLUGIN_DIR . 'includes/class-dm-banner.php';
        require_once DM_ADS_PLUGIN_DIR . 'includes/class-dm-zone.php';
        require_once DM_ADS_PLUGIN_DIR . 'includes/class-dm-tracker.php';
        require_once DM_ADS_PLUGIN_DIR . 'includes/class-dm-analytics.php';
        require_once DM_ADS_PLUGIN_DIR . 'includes/class-dm-display.php';
        require_once DM_ADS_PLUGIN_DIR . 'includes/class-dm-admin.php';
    }
    
    /**
     * Register all hooks related to the admin area
     */
    private function define_admin_hooks() {
        $admin = new DM_Ads_Admin($this->get_plugin_name(), $this->get_version());
        
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_scripts'));
        add_action('admin_menu', array($admin, 'add_admin_menu'));
    }
    
    /**
     * Register all hooks related to the public-facing functionality
     */
    private function define_public_hooks() {
        $display = new DM_Ads_Display($this->get_plugin_name(), $this->get_version());
        
        add_action('wp_enqueue_scripts', array($display, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($display, 'enqueue_scripts'));
        add_shortcode('dm_ads', array($display, 'shortcode_handler'));
        
        // AJAX handlers for tracking
        add_action('wp_ajax_dm_ads_track_view', array('DM_Ads_Tracker', 'track_view'));
        add_action('wp_ajax_nopriv_dm_ads_track_view', array('DM_Ads_Tracker', 'track_view'));
        add_action('wp_ajax_dm_ads_track_click', array('DM_Ads_Tracker', 'track_click'));
        add_action('wp_ajax_nopriv_dm_ads_track_click', array('DM_Ads_Tracker', 'track_click'));
    }
    
    /**
     * Run the loader to execute all of the hooks
     */
    public function run() {
        // Initialize banner custom post type
        $banner = new DM_Ads_Banner();
        $banner->init();
        
        // Initialize zone management
        $zone = new DM_Ads_Zone();
        $zone->init();
    }
    
    /**
     * Get plugin name
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }
    
    /**
     * Get plugin version
     */
    public function get_version() {
        return $this->version;
    }
}
