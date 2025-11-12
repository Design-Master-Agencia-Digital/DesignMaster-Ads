<?php
/**
 * Banner Custom Post Type
 */
class DM_Ads_Banner {
    
    /**
     * Initialize the custom post type
     */
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_dm_banner', array($this, 'save_meta_boxes'));
    }
    
    /**
     * Register the Banner custom post type
     */
    public function register_post_type() {
        $labels = array(
            'name' => __('Banners', 'designmaster-ads'),
            'singular_name' => __('Banner', 'designmaster-ads'),
            'add_new' => __('Add New Banner', 'designmaster-ads'),
            'add_new_item' => __('Add New Banner', 'designmaster-ads'),
            'edit_item' => __('Edit Banner', 'designmaster-ads'),
            'new_item' => __('New Banner', 'designmaster-ads'),
            'view_item' => __('View Banner', 'designmaster-ads'),
            'search_items' => __('Search Banners', 'designmaster-ads'),
            'not_found' => __('No banners found', 'designmaster-ads'),
            'not_found_in_trash' => __('No banners found in Trash', 'designmaster-ads'),
            'menu_name' => __('DM Ads', 'designmaster-ads'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-megaphone',
            'menu_position' => 25,
            'supports' => array('title', 'thumbnail'),
            'has_archive' => false,
            'rewrite' => false,
            'capability_type' => 'post',
            'show_in_rest' => true,
        );
        
        register_post_type('dm_banner', $args);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'dm_banner_settings',
            __('Banner Settings', 'designmaster-ads'),
            array($this, 'render_settings_meta_box'),
            'dm_banner',
            'normal',
            'high'
        );
        
        add_meta_box(
            'dm_banner_schedule',
            __('Schedule', 'designmaster-ads'),
            array($this, 'render_schedule_meta_box'),
            'dm_banner',
            'side',
            'default'
        );
        
        add_meta_box(
            'dm_banner_stats',
            __('Statistics', 'designmaster-ads'),
            array($this, 'render_stats_meta_box'),
            'dm_banner',
            'side',
            'default'
        );
    }
    
    /**
     * Render settings meta box
     */
    public function render_settings_meta_box($post) {
        wp_nonce_field('dm_banner_settings', 'dm_banner_settings_nonce');
        
        $target_url = get_post_meta($post->ID, '_dm_banner_url', true);
        $zone = get_post_meta($post->ID, '_dm_banner_zone', true);
        $priority = get_post_meta($post->ID, '_dm_banner_priority', true) ?: 10;
        $status = get_post_meta($post->ID, '_dm_banner_status', true) ?: 'active';
        
        $zones = DM_Ads_Zone::get_all_zones();
        ?>
        <table class="form-table">
            <tr>
                <th><label for="dm_banner_url"><?php _e('Target URL', 'designmaster-ads'); ?></label></th>
                <td>
                    <input type="url" id="dm_banner_url" name="dm_banner_url" value="<?php echo esc_url($target_url); ?>" class="widefat">
                    <p class="description"><?php _e('URL to redirect when banner is clicked', 'designmaster-ads'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="dm_banner_zone"><?php _e('Banner Zone', 'designmaster-ads'); ?></label></th>
                <td>
                    <select id="dm_banner_zone" name="dm_banner_zone" class="widefat">
                        <option value=""><?php _e('Select a zone', 'designmaster-ads'); ?></option>
                        <?php foreach ($zones as $z): ?>
                            <option value="<?php echo esc_attr($z['slug']); ?>" <?php selected($zone, $z['slug']); ?>>
                                <?php echo esc_html($z['name']); ?> (<?php echo $z['width']; ?>x<?php echo $z['height']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="dm_banner_priority"><?php _e('Priority', 'designmaster-ads'); ?></label></th>
                <td>
                    <input type="number" id="dm_banner_priority" name="dm_banner_priority" value="<?php echo esc_attr($priority); ?>" min="1" max="100" class="small-text">
                    <p class="description"><?php _e('Higher priority = higher chance of display (1-100)', 'designmaster-ads'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="dm_banner_status"><?php _e('Status', 'designmaster-ads'); ?></label></th>
                <td>
                    <select id="dm_banner_status" name="dm_banner_status">
                        <option value="active" <?php selected($status, 'active'); ?>><?php _e('Active', 'designmaster-ads'); ?></option>
                        <option value="inactive" <?php selected($status, 'inactive'); ?>><?php _e('Inactive', 'designmaster-ads'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Render schedule meta box
     */
    public function render_schedule_meta_box($post) {
        wp_nonce_field('dm_banner_schedule', 'dm_banner_schedule_nonce');
        
        $start_date = get_post_meta($post->ID, '_dm_banner_start_date', true);
        $end_date = get_post_meta($post->ID, '_dm_banner_end_date', true);
        ?>
        <p>
            <label for="dm_banner_start_date"><strong><?php _e('Start Date/Time', 'designmaster-ads'); ?></strong></label><br>
            <input type="datetime-local" id="dm_banner_start_date" name="dm_banner_start_date" value="<?php echo esc_attr($start_date); ?>" class="widefat">
        </p>
        <p>
            <label for="dm_banner_end_date"><strong><?php _e('End Date/Time', 'designmaster-ads'); ?></strong></label><br>
            <input type="datetime-local" id="dm_banner_end_date" name="dm_banner_end_date" value="<?php echo esc_attr($end_date); ?>" class="widefat">
        </p>
        <p class="description"><?php _e('Leave empty for no time restrictions', 'designmaster-ads'); ?></p>
        <?php
    }
    
    /**
     * Render statistics meta box
     */
    public function render_stats_meta_box($post) {
        $stats = DM_Ads_Analytics::get_banner_stats($post->ID);
        ?>
        <div class="dm-banner-stats">
            <p>
                <strong><?php _e('Total Views:', 'designmaster-ads'); ?></strong><br>
                <span class="dm-stat-value"><?php echo number_format($stats['views']); ?></span>
            </p>
            <p>
                <strong><?php _e('Total Clicks:', 'designmaster-ads'); ?></strong><br>
                <span class="dm-stat-value"><?php echo number_format($stats['clicks']); ?></span>
            </p>
            <p>
                <strong><?php _e('CTR (Click-Through Rate):', 'designmaster-ads'); ?></strong><br>
                <span class="dm-stat-value"><?php echo $stats['ctr']; ?>%</span>
            </p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=dm-ads-analytics&banner_id=' . $post->ID); ?>" class="button button-secondary">
                    <?php _e('View Detailed Analytics', 'designmaster-ads'); ?>
                </a>
            </p>
        </div>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Check nonces
        if (!isset($_POST['dm_banner_settings_nonce']) || !wp_verify_nonce($_POST['dm_banner_settings_nonce'], 'dm_banner_settings')) {
            return;
        }
        
        if (!isset($_POST['dm_banner_schedule_nonce']) || !wp_verify_nonce($_POST['dm_banner_schedule_nonce'], 'dm_banner_schedule')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save settings
        if (isset($_POST['dm_banner_url'])) {
            update_post_meta($post_id, '_dm_banner_url', esc_url_raw($_POST['dm_banner_url']));
        }
        
        if (isset($_POST['dm_banner_zone'])) {
            update_post_meta($post_id, '_dm_banner_zone', sanitize_text_field($_POST['dm_banner_zone']));
        }
        
        if (isset($_POST['dm_banner_priority'])) {
            update_post_meta($post_id, '_dm_banner_priority', absint($_POST['dm_banner_priority']));
        }
        
        if (isset($_POST['dm_banner_status'])) {
            update_post_meta($post_id, '_dm_banner_status', sanitize_text_field($_POST['dm_banner_status']));
        }
        
        // Save schedule
        if (isset($_POST['dm_banner_start_date'])) {
            update_post_meta($post_id, '_dm_banner_start_date', sanitize_text_field($_POST['dm_banner_start_date']));
        }
        
        if (isset($_POST['dm_banner_end_date'])) {
            update_post_meta($post_id, '_dm_banner_end_date', sanitize_text_field($_POST['dm_banner_end_date']));
        }
    }
}
