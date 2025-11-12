<?php
/**
 * Analytics and Statistics
 */
class DM_Ads_Analytics {
    
    /**
     * Get banner statistics
     */
    public static function get_banner_stats($banner_id, $start_date = null, $end_date = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        $where = $wpdb->prepare("banner_id = %d", $banner_id);
        
        if ($start_date) {
            $where .= $wpdb->prepare(" AND created_at >= %s", $start_date);
        }
        
        if ($end_date) {
            $where .= $wpdb->prepare(" AND created_at <= %s", $end_date);
        }
        
        $views = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE $where AND event_type = 'view'");
        $clicks = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE $where AND event_type = 'click'");
        
        $ctr = $views > 0 ? round(($clicks / $views) * 100, 2) : 0;
        
        return array(
            'views' => intval($views),
            'clicks' => intval($clicks),
            'ctr' => $ctr
        );
    }
    
    /**
     * Get zone statistics
     */
    public static function get_zone_stats($zone_slug, $start_date = null, $end_date = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        // Get all banners in this zone
        $banners = DM_Ads_Zone::get_zone_banners($zone_slug);
        $banner_ids = array_column($banners, 'id');
        
        if (empty($banner_ids)) {
            return array(
                'views' => 0,
                'clicks' => 0,
                'ctr' => 0
            );
        }
        
        $placeholders = implode(',', array_fill(0, count($banner_ids), '%d'));
        $where = "banner_id IN ($placeholders)";
        
        if ($start_date) {
            $where .= $wpdb->prepare(" AND created_at >= %s", $start_date);
        }
        
        if ($end_date) {
            $where .= $wpdb->prepare(" AND created_at <= %s", $end_date);
        }
        
        $views = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE $where AND event_type = 'view'", ...$banner_ids));
        $clicks = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE $where AND event_type = 'click'", ...$banner_ids));
        
        $ctr = $views > 0 ? round(($clicks / $views) * 100, 2) : 0;
        
        return array(
            'views' => intval($views),
            'clicks' => intval($clicks),
            'ctr' => $ctr
        );
    }
    
    /**
     * Get stats by date range
     */
    public static function get_stats_by_date($banner_id = null, $days = 7, $start_date = null, $end_date = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        // Use custom date range if provided, otherwise use days
        if ($start_date && $end_date) {
            $where = $wpdb->prepare("created_at >= %s AND created_at <= %s", $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        } else {
            $where = "created_at >= DATE_SUB(NOW(), INTERVAL $days DAY)";
        }
        
        if ($banner_id) {
            $where .= $wpdb->prepare(" AND banner_id = %d", $banner_id);
        }
        
        $results = $wpdb->get_results("
            SELECT 
                DATE(created_at) as date,
                event_type,
                COUNT(*) as count
            FROM $table
            WHERE $where
            GROUP BY DATE(created_at), event_type
            ORDER BY date ASC
        ");
        
        $stats = array();
        foreach ($results as $row) {
            $date = $row->date;
            if (!isset($stats[$date])) {
                $stats[$date] = array('views' => 0, 'clicks' => 0);
            }
            $stats[$date][$row->event_type . 's'] = intval($row->count);
        }
        
        return $stats;
    }
    
    /**
     * Get stats by device type
     */
    public static function get_stats_by_device($banner_id = null, $days = 30, $start_date = null, $end_date = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        // Use custom date range if provided, otherwise use days
        if ($start_date && $end_date) {
            $where = $wpdb->prepare("created_at >= %s AND created_at <= %s", $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        } else {
            $where = "created_at >= DATE_SUB(NOW(), INTERVAL $days DAY)";
        }
        
        if ($banner_id) {
            $where .= $wpdb->prepare(" AND banner_id = %d", $banner_id);
        }
        
        $results = $wpdb->get_results("
            SELECT 
                device_type,
                event_type,
                COUNT(*) as count
            FROM $table
            WHERE $where
            GROUP BY device_type, event_type
        ");
        
        $stats = array(
            'desktop' => array('views' => 0, 'clicks' => 0),
            'mobile' => array('views' => 0, 'clicks' => 0),
            'tablet' => array('views' => 0, 'clicks' => 0)
        );
        
        foreach ($results as $row) {
            $stats[$row->device_type][$row->event_type . 's'] = intval($row->count);
        }
        
        return $stats;
    }
    
    /**
     * Get top performing banners
     */
    public static function get_top_banners($limit = 10, $days = 30, $start_date = null, $end_date = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        // Use custom date range if provided, otherwise use days
        if ($start_date && $end_date) {
            $where = $wpdb->prepare("created_at >= %s AND created_at <= %s", $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        } else {
            $where = $wpdb->prepare("created_at >= DATE_SUB(NOW(), INTERVAL %d DAY)", $days);
        }
        
        $results = $wpdb->get_results($wpdb->prepare("
            SELECT 
                banner_id,
                SUM(CASE WHEN event_type = 'view' THEN 1 ELSE 0 END) as views,
                SUM(CASE WHEN event_type = 'click' THEN 1 ELSE 0 END) as clicks
            FROM $table
            WHERE $where
            GROUP BY banner_id
            ORDER BY clicks DESC
            LIMIT %d
        ", $limit));
        
        $banners = array();
        foreach ($results as $row) {
            $post = get_post($row->banner_id);
            if ($post) {
                $ctr = $row->views > 0 ? round(($row->clicks / $row->views) * 100, 2) : 0;
                $banners[] = array(
                    'id' => $row->banner_id,
                    'title' => $post->post_title,
                    'views' => intval($row->views),
                    'clicks' => intval($row->clicks),
                    'ctr' => $ctr
                );
            }
        }
        
        return $banners;
    }
    
    /**
     * Get hourly stats for heatmap
     */
    public static function get_hourly_stats($banner_id = null, $days = 7, $start_date = null, $end_date = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        // Use custom date range if provided, otherwise use days
        if ($start_date && $end_date) {
            $where = $wpdb->prepare("created_at >= %s AND created_at <= %s", $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        } else {
            $where = "created_at >= DATE_SUB(NOW(), INTERVAL $days DAY)";
        }
        
        if ($banner_id) {
            $where .= $wpdb->prepare(" AND banner_id = %d", $banner_id);
        }
        
        $results = $wpdb->get_results("
            SELECT 
                HOUR(created_at) as hour,
                event_type,
                COUNT(*) as count
            FROM $table
            WHERE $where
            GROUP BY HOUR(created_at), event_type
            ORDER BY hour ASC
        ");
        
        $stats = array();
        for ($i = 0; $i < 24; $i++) {
            $stats[$i] = array('views' => 0, 'clicks' => 0);
        }
        
        foreach ($results as $row) {
            $stats[$row->hour][$row->event_type . 's'] = intval($row->count);
        }
        
        return $stats;
    }
    
    /**
     * Export stats to CSV
     */
    public static function export_to_csv($banner_id = null, $start_date = null, $end_date = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'dm_ads_stats';
        
        $where = "1=1";
        
        if ($banner_id) {
            $where .= $wpdb->prepare(" AND banner_id = %d", $banner_id);
        }
        
        if ($start_date) {
            $where .= $wpdb->prepare(" AND created_at >= %s", $start_date);
        }
        
        if ($end_date) {
            $where .= $wpdb->prepare(" AND created_at <= %s", $end_date);
        }
        
        $results = $wpdb->get_results("
            SELECT * FROM $table WHERE $where ORDER BY created_at DESC
        ", ARRAY_A);
        
        $filename = 'dm-ads-stats-' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($results)) {
            fputcsv($output, array_keys($results[0]));
            foreach ($results as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
}
