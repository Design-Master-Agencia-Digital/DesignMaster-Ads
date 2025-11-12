<?php
/**
 * Frontend Display
 */
class DM_Ads_Display {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Enqueue public styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            DM_ADS_PLUGIN_URL . 'assets/css/public.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Enqueue public scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            DM_ADS_PLUGIN_URL . 'assets/js/banner-rotator.js',
            array('jquery'),
            $this->version,
            true
        );
        
        // Lazy loading script
        wp_enqueue_script(
            $this->plugin_name . '-lazy-loading',
            DM_ADS_PLUGIN_URL . 'assets/js/lazy-loading.js',
            array(),
            $this->version,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script($this->plugin_name, 'dmAds', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dm_ads_nonce'),
            'trackViews' => get_option('dm_ads_track_views', true),
            'trackClicks' => get_option('dm_ads_track_clicks', true)
        ));
    }
    
    /**
     * Shortcode handler: [dm_ads zone="zone-slug"]
     */
    public function shortcode_handler($atts) {
        $atts = shortcode_atts(array(
            'zone' => ''
        ), $atts);
        
        if (empty($atts['zone'])) {
            return '';
        }
        
        return $this->display_zone($atts['zone']);
    }
    
    /**
     * Display banner zone
     */
    public function display_zone($zone_slug) {
        $zone = DM_Ads_Zone::get_zone($zone_slug);
        
        if (!$zone) {
            // Debug mode - show error for admins
            if (current_user_can('manage_options') && defined('WP_DEBUG') && WP_DEBUG) {
                return '<!-- DM Ads Debug: Zone "' . esc_html($zone_slug) . '" not found -->';
            }
            return '';
        }
        
        $banners = DM_Ads_Zone::get_zone_banners($zone_slug);
        
        if (empty($banners)) {
            // Debug mode - show error for admins
            if (current_user_can('manage_options') && defined('WP_DEBUG') && WP_DEBUG) {
                return '<!-- DM Ads Debug: No active banners found for zone "' . esc_html($zone_slug) . '" -->';
            }
            return '';
        }
        
        ob_start();
        
        switch ($zone['type']) {
            case 'fixed':
                $this->render_fixed_banner($zone, $banners);
                break;
            case 'reload':
                $this->render_reload_banner($zone, $banners);
                break;
            case 'timed':
                $this->render_timed_banner($zone, $banners);
                break;
        }
        
        return ob_get_clean();
    }
    
    /**
     * Render fixed banner (always shows the first/highest priority)
     */
    private function render_fixed_banner($zone, $banners) {
        $banner = $banners[0]; // Get highest priority banner
        include DM_ADS_PLUGIN_DIR . 'templates/public/banner-fixed.php';
    }
    
    /**
     * Render reload banner (random on each page load)
     */
    private function render_reload_banner($zone, $banners) {
        // Weighted random selection based on priority
        $banner = $this->weighted_random_banner($banners);
        include DM_ADS_PLUGIN_DIR . 'templates/public/banner-reload.php';
    }
    
    /**
     * Render timed banner (rotates automatically)
     */
    private function render_timed_banner($zone, $banners) {
        include DM_ADS_PLUGIN_DIR . 'templates/public/banner-timed.php';
    }
    
    /**
     * Weighted random banner selection
     */
    private function weighted_random_banner($banners) {
        $total_weight = 0;
        foreach ($banners as $banner) {
            $total_weight += intval($banner['priority']);
        }
        
        $random = wp_rand(1, $total_weight);
        $current_weight = 0;
        
        foreach ($banners as $banner) {
            $current_weight += intval($banner['priority']);
            if ($random <= $current_weight) {
                return $banner;
            }
        }
        
        return $banners[0];
    }
}

/**
 * Helper function for theme integration
 */
function dm_ads_display($zone_slug) {
    $display = new DM_Ads_Display('designmaster-ads', DM_ADS_VERSION);
    echo wp_kses_post($display->display_zone($zone_slug));
}
