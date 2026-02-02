<?php
/**
 * Fired during plugin activation
 */
class DM_Ads_Activator {
    
    /**
     * Activation process
     */
    public static function activate() {
        self::create_database_tables();
        self::set_default_options();
        self::create_default_zones();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Create custom database tables for analytics
     */
    private static function create_database_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'dm_ads_stats';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            banner_id bigint(20) NOT NULL,
            zone_id bigint(20) DEFAULT 0,
            event_type enum('view','click') NOT NULL,
            user_ip varchar(45) DEFAULT NULL,
            user_agent text DEFAULT NULL,
            device_type enum('desktop','mobile','tablet') DEFAULT 'desktop',
            country_code varchar(2) DEFAULT NULL,
            referer text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY banner_id (banner_id),
            KEY zone_id (zone_id),
            KEY event_type (event_type),
            KEY created_at (created_at),
            KEY device_type (device_type)
        ) $charset_collate;";
        
        if (!function_exists('dbDelta')) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }
        dbDelta($sql);
    }
    
    /**
     * Set default plugin options
     */
    private static function set_default_options() {
        $defaults = array(
            'dm_ads_version' => DM_ADS_VERSION,
            'dm_ads_track_views' => true,
            'dm_ads_track_clicks' => true,
            'dm_ads_anonymize_ip' => true,
            'dm_ads_cache_time' => 3600,
            'dm_ads_default_rotation_time' => 5,
        );
        
        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }
    
    /**
     * Create default banner zones
     */
    private static function create_default_zones() {
        $default_zones = array(
            array(
                'name' => 'Header Banner',
                'slug' => 'header-banner',
                'type' => 'fixed',
                'width' => 728,
                'height' => 90,
            ),
            array(
                'name' => 'Sidebar Banner',
                'slug' => 'sidebar-banner',
                'type' => 'reload',
                'width' => 300,
                'height' => 250,
            ),
            array(
                'name' => 'Footer Banner',
                'slug' => 'footer-banner',
                'type' => 'timed',
                'width' => 970,
                'height' => 90,
            ),
        );
        
        foreach ($default_zones as $zone) {
            if (!get_option('dm_ads_zone_' . $zone['slug'])) {
                add_option('dm_ads_zone_' . $zone['slug'], $zone);
            }
        }
    }
}
