<?php
/**
 * Zone Management
 */
class DM_Ads_Zone {
    
    /**
     * Initialize
     */
    public function init() {
        // Zones are stored as options, not CPT
    }
    
    /**
     * Get all zones
     */
    public static function get_all_zones() {
        global $wpdb;
        
        $zones = array();
        $options = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'dm_ads_zone_%'");
        
        foreach ($options as $option) {
            $zone = maybe_unserialize($option->option_value);
            if (is_array($zone)) {
                $zones[] = $zone;
            }
        }
        
        return $zones;
    }
    
    /**
     * Get zone by slug
     */
    public static function get_zone($slug) {
        return get_option('dm_ads_zone_' . $slug, false);
    }
    
    /**
     * Save zone
     */
    public static function save_zone($slug, $data) {
        $zone = array(
            'name' => sanitize_text_field($data['name']),
            'slug' => sanitize_title($slug),
            'type' => sanitize_text_field($data['type']),
            'width' => absint($data['width']),
            'height' => absint($data['height']),
            'rotation_interval' => isset($data['rotation_interval']) ? absint($data['rotation_interval']) : 5,
        );
        
        return update_option('dm_ads_zone_' . $slug, $zone);
    }
    
    /**
     * Delete zone
     */
    public static function delete_zone($slug) {
        return delete_option('dm_ads_zone_' . $slug);
    }
    
    /**
     * Get banners for zone
     */
    public static function get_zone_banners($zone_slug, $limit = -1) {
        $args = array(
            'post_type' => 'dm_banner',
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_dm_banner_zone',
                    'value' => $zone_slug,
                    'compare' => '='
                ),
                array(
                    'key' => '_dm_banner_status',
                    'value' => 'active',
                    'compare' => '='
                )
            ),
            'meta_key' => '_dm_banner_priority',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        );
        
        // Filter by schedule - start date
        $args['meta_query'][] = array(
            'relation' => 'OR',
            array(
                'key' => '_dm_banner_start_date',
                'value' => current_time('mysql'),
                'compare' => '<=',
                'type' => 'DATETIME'
            ),
            array(
                'key' => '_dm_banner_start_date',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => '_dm_banner_start_date',
                'value' => '',
                'compare' => '='
            )
        );
        
        $query = new WP_Query($args);
        $banners = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                // Check end date using current_time for consistency
                $end_date = get_post_meta(get_the_ID(), '_dm_banner_end_date', true);
                if ($end_date && $end_date < current_time('mysql')) {
                    continue;
                }
                
                $image_id = get_post_meta(get_the_ID(), '_dm_banner_image_id', true);
                $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
                
                // Skip banner if no image
                if (empty($image_url)) {
                    continue;
                }
                
                $banners[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'image' => $image_url,
                    'url' => get_post_meta(get_the_ID(), '_dm_banner_url', true),
                    'priority' => get_post_meta(get_the_ID(), '_dm_banner_priority', true)
                );
            }
            wp_reset_postdata();
        }
        
        return $banners;
    }
}
