<?php
/**
 * Tracking System - Views and Clicks
 */
class DM_Ads_Tracker {
    
    /**
     * Track banner view (AJAX handler)
     */
    public static function track_view() {
        check_ajax_referer('dm_ads_nonce', 'nonce');
        
        $banner_id = isset($_POST['banner_id']) ? absint($_POST['banner_id']) : 0;
        $zone_id = isset($_POST['zone_id']) ? sanitize_text_field($_POST['zone_id']) : '';
        
        if (!$banner_id) {
            wp_send_json_error('Invalid banner ID');
        }
        
        $result = self::track_event($banner_id, $zone_id, 'view');
        
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to track view');
        }
    }
    
    /**
     * Track banner click (AJAX handler)
     */
    public static function track_click() {
        check_ajax_referer('dm_ads_nonce', 'nonce');
        
        $banner_id = isset($_POST['banner_id']) ? absint($_POST['banner_id']) : 0;
        $zone_id = isset($_POST['zone_id']) ? sanitize_text_field($_POST['zone_id']) : '';
        
        if (!$banner_id) {
            wp_send_json_error('Invalid banner ID');
        }
        
        $result = self::track_event($banner_id, $zone_id, 'click');
        
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to track click');
        }
    }
    
    /**
     * Track event (view or click)
     */
    private static function track_event($banner_id, $zone_id, $event_type) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        // Get user information
        $user_ip = self::get_user_ip();
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $device_type = self::get_device_type($user_agent);
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
        // Anonymize IP if enabled
        if (get_option('dm_ads_anonymize_ip', true)) {
            $user_ip = self::anonymize_ip($user_ip);
        }
        
        // Get country code (optional, requires additional service)
        $country_code = null;
        
        return $wpdb->insert(
            $table,
            array(
                'banner_id' => $banner_id,
                'zone_id' => $zone_id,
                'event_type' => $event_type,
                'user_ip' => $user_ip,
                'user_agent' => $user_agent,
                'device_type' => $device_type,
                'country_code' => $country_code,
                'referer' => $referer,
                'created_at' => current_time('mysql')
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
    }
    
    /**
     * Get user IP address
     */
    private static function get_user_ip() {
        $ip_keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return '0.0.0.0';
    }
    
    /**
     * Anonymize IP address
     */
    private static function anonymize_ip($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\.\d+$/', '.0', $ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return preg_replace('/([\da-f]+:[\da-f]+:[\da-f]+:[\da-f]+):.*/', '$1::', $ip);
        }
        return $ip;
    }
    
    /**
     * Detect device type from user agent
     */
    private static function get_device_type($user_agent) {
        $mobile_regex = '/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i';
        $tablet_regex = '/tablet|ipad/i';
        
        if (preg_match($tablet_regex, $user_agent)) {
            return 'tablet';
        } elseif (preg_match($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }
    
    /**
     * Clean old stats (for cron job)
     */
    public static function clean_old_stats($days = 365) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        return $wpdb->query($wpdb->prepare(
            "DELETE FROM $table WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
            $days
        ));
    }
}
