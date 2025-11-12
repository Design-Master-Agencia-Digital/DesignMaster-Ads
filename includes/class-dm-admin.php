<?php
/**
 * Admin Interface
 */
class DM_Ads_Admin {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Enqueue admin styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            DM_ADS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_scripts($hook) {
        // Enqueue media uploader on banner edit screens
        global $post_type;
        if ('dm_banner' === $post_type) {
            wp_enqueue_media();
        }
        
        // Chart.js for analytics
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
            array(),
            '4.4.0',
            true
        );
        
        wp_enqueue_script(
            $this->plugin_name . '-admin',
            DM_ADS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            $this->version,
            true
        );
        
        wp_enqueue_script(
            $this->plugin_name . '-analytics',
            DM_ADS_PLUGIN_URL . 'assets/js/analytics.js',
            array('jquery', 'chartjs'),
            $this->version,
            true
        );
        
        wp_localize_script($this->plugin_name . '-analytics', 'dmAdsAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dm_ads_admin_nonce')
        ));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main menu is already added by the CPT
        
        // Analytics submenu
        add_submenu_page(
            'edit.php?post_type=dm_banner',
            __('Analytics', 'designmaster-ads'),
            __('Analytics', 'designmaster-ads'),
            'manage_options',
            'dm-ads-analytics',
            array($this, 'analytics_page')
        );
        
        // Zones submenu
        add_submenu_page(
            'edit.php?post_type=dm_banner',
            __('Banner Zones', 'designmaster-ads'),
            __('Zones', 'designmaster-ads'),
            'manage_options',
            'dm-ads-zones',
            array($this, 'zones_page')
        );
        
        // Dashboard submenu (first position)
        add_submenu_page(
            'edit.php?post_type=dm_banner',
            __('Dashboard', 'designmaster-ads'),
            __('Dashboard', 'designmaster-ads'),
            'manage_options',
            'dm-ads-dashboard',
            array($this, 'dashboard_page')
        );
        
        // Settings submenu
        add_submenu_page(
            'edit.php?post_type=dm_banner',
            __('Settings', 'designmaster-ads'),
            __('Settings', 'designmaster-ads'),
            'manage_options',
            'dm-ads-settings',
            array($this, 'settings_page')
        );
    }
    
    /**
     * Dashboard page
     */
    public function dashboard_page() {
        include DM_ADS_PLUGIN_DIR . 'templates/admin/dashboard.php';
    }
    
    /**
     * Analytics page
     */
    public function analytics_page() {
        include DM_ADS_PLUGIN_DIR . 'templates/admin/analytics.php';
    }
    
    /**
     * Zones management page
     */
    public function zones_page() {
        // Handle form submissions
        if (isset($_POST['dm_ads_save_zone'])) {
            check_admin_referer('dm_ads_zone_action', 'dm_ads_zone_nonce');
            
            $slug = sanitize_title($_POST['zone_slug']);
            DM_Ads_Zone::save_zone($slug, $_POST);
            
            add_settings_error('dm_ads_zones', 'zone_saved', __('Zone saved successfully', 'designmaster-ads'), 'success');
        }
        
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['zone'])) {
            check_admin_referer('dm_ads_delete_zone_' . $_GET['zone']);
            
            DM_Ads_Zone::delete_zone($_GET['zone']);
            
            add_settings_error('dm_ads_zones', 'zone_deleted', __('Zone deleted successfully', 'designmaster-ads'), 'success');
        }
        
        include DM_ADS_PLUGIN_DIR . 'templates/admin/zones-manager.php';
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        // Handle form submission
        if (isset($_POST['dm_ads_save_settings'])) {
            check_admin_referer('dm_ads_settings_action', 'dm_ads_settings_nonce');
            
            update_option('dm_ads_track_views', isset($_POST['track_views']));
            update_option('dm_ads_track_clicks', isset($_POST['track_clicks']));
            update_option('dm_ads_anonymize_ip', isset($_POST['anonymize_ip']));
            update_option('dm_ads_cache_time', absint($_POST['cache_time']));
            update_option('dm_ads_default_rotation_time', absint($_POST['default_rotation_time']));
            
            add_settings_error('dm_ads_settings', 'settings_saved', __('Settings saved successfully', 'designmaster-ads'), 'success');
        }
        
        // Get current settings
        $settings = array(
            'track_views' => get_option('dm_ads_track_views', true),
            'track_clicks' => get_option('dm_ads_track_clicks', true),
            'anonymize_ip' => get_option('dm_ads_anonymize_ip', true),
            'cache_time' => get_option('dm_ads_cache_time', 3600),
            'default_rotation_time' => get_option('dm_ads_default_rotation_time', 5),
        );
        
        include DM_ADS_PLUGIN_DIR . 'templates/admin/settings.php';
    }
}
