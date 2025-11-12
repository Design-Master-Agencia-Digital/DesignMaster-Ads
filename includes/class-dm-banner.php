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
        
        // Custom columns
        add_filter('manage_dm_banner_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_dm_banner_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
        add_filter('manage_edit-dm_banner_sortable_columns', array($this, 'sortable_columns'));
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
            'supports' => array('title'),
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
            'dm_banner_image',
            __('Banner Image', 'designmaster-ads'),
            array($this, 'render_image_meta_box'),
            'dm_banner',
            'normal',
            'high'
        );
        
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
     * Render image upload meta box
     */
    public function render_image_meta_box($post) {
        wp_nonce_field('dm_banner_image', 'dm_banner_image_nonce');
        
        $image_id = get_post_meta($post->ID, '_dm_banner_image_id', true);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        ?>
        <div class="dm-banner-image-container">
            <div class="dm-banner-image-preview" style="margin-bottom: 10px;">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; background: #f9f9f9;">
                <?php else: ?>
                    <div style="border: 2px dashed #ddd; padding: 40px; text-align: center; background: #f9f9f9; color: #666;">
                        <span class="dashicons dashicons-format-image" style="font-size: 48px; width: 48px; height: 48px;"></span>
                        <p><?php _e('No image selected', 'designmaster-ads'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <input type="hidden" id="dm_banner_image_id" name="dm_banner_image_id" value="<?php echo esc_attr($image_id); ?>">
            
            <p>
                <button type="button" class="button button-primary dm-upload-image-button">
                    <span class="dashicons dashicons-upload" style="margin-top: 3px;"></span>
                    <?php _e('Upload Image', 'designmaster-ads'); ?>
                </button>
                
                <?php if ($image_url): ?>
                    <button type="button" class="button dm-remove-image-button">
                        <span class="dashicons dashicons-no" style="margin-top: 3px;"></span>
                        <?php _e('Remove Image', 'designmaster-ads'); ?>
                    </button>
                <?php endif; ?>
            </p>
            
            <p class="description">
                <?php _e('Click to upload or select an image for this banner', 'designmaster-ads'); ?>
            </p>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            var mediaUploader;
            
            $('.dm-upload-image-button').on('click', function(e) {
                e.preventDefault();
                
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                
                mediaUploader = wp.media({
                    title: '<?php _e('Select Banner Image', 'designmaster-ads'); ?>',
                    button: {
                        text: '<?php _e('Use this image', 'designmaster-ads'); ?>'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#dm_banner_image_id').val(attachment.id);
                    $('.dm-banner-image-preview').html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; background: #f9f9f9;">');
                    
                    if ($('.dm-remove-image-button').length === 0) {
                        $('.dm-upload-image-button').after('<button type="button" class="button dm-remove-image-button"><span class="dashicons dashicons-no" style="margin-top: 3px;"></span> <?php _e('Remove Image', 'designmaster-ads'); ?></button>');
                    }
                });
                
                mediaUploader.open();
            });
            
            $(document).on('click', '.dm-remove-image-button', function(e) {
                e.preventDefault();
                $('#dm_banner_image_id').val('');
                $('.dm-banner-image-preview').html('<div style="border: 2px dashed #ddd; padding: 40px; text-align: center; background: #f9f9f9; color: #666;"><span class="dashicons dashicons-format-image" style="font-size: 48px; width: 48px; height: 48px;"></span><p><?php _e('No image selected', 'designmaster-ads'); ?></p></div>');
                $(this).remove();
            });
        });
        </script>
        <?php
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
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save image
        if (isset($_POST['dm_banner_image_nonce']) && wp_verify_nonce($_POST['dm_banner_image_nonce'], 'dm_banner_image')) {
            if (isset($_POST['dm_banner_image_id'])) {
                update_post_meta($post_id, '_dm_banner_image_id', absint($_POST['dm_banner_image_id']));
            }
        }
        
        // Save settings
        if (isset($_POST['dm_banner_settings_nonce']) && wp_verify_nonce($_POST['dm_banner_settings_nonce'], 'dm_banner_settings')) {
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
        }
        
        // Save schedule
        if (isset($_POST['dm_banner_schedule_nonce']) && wp_verify_nonce($_POST['dm_banner_schedule_nonce'], 'dm_banner_schedule')) {
            if (isset($_POST['dm_banner_start_date'])) {
                update_post_meta($post_id, '_dm_banner_start_date', sanitize_text_field($_POST['dm_banner_start_date']));
            }
            
            if (isset($_POST['dm_banner_end_date'])) {
                update_post_meta($post_id, '_dm_banner_end_date', sanitize_text_field($_POST['dm_banner_end_date']));
            }
        }
    }
    
    /**
     * Set custom columns for banner list
     */
    public function set_custom_columns($columns) {
        $new_columns = array();
        
        // Checkbox
        $new_columns['cb'] = $columns['cb'];
        
        // Thumbnail
        $new_columns['thumbnail'] = __('Image', 'designmaster-ads');
        
        // Title
        $new_columns['title'] = $columns['title'];
        
        // Zone
        $new_columns['zone'] = __('Zone', 'designmaster-ads');
        
        // URL
        $new_columns['url'] = __('Target URL', 'designmaster-ads');
        
        // Status
        $new_columns['banner_status'] = __('Status', 'designmaster-ads');
        
        // Priority
        $new_columns['priority'] = __('Priority', 'designmaster-ads');
        
        // Schedule
        $new_columns['schedule'] = __('Schedule', 'designmaster-ads');
        
        // Stats
        $new_columns['views'] = __('Views', 'designmaster-ads');
        $new_columns['clicks'] = __('Clicks', 'designmaster-ads');
        $new_columns['ctr'] = __('CTR', 'designmaster-ads');
        
        // Date
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    /**
     * Populate custom columns content
     */
    public function custom_column_content($column, $post_id) {
        global $wpdb;
        
        switch ($column) {
            case 'thumbnail':
                $image_id = get_post_meta($post_id, '_dm_banner_image_id', true);
                if ($image_id) {
                    $image_url = wp_get_attachment_image_url($image_id, array(80, 80));
                    if ($image_url) {
                        echo '<img src="' . esc_url($image_url) . '" style="width:80px;height:auto;border-radius:4px;" />';
                    } else {
                        echo '<span style="color:#999;">—</span>';
                    }
                } else {
                    echo '<span style="color:#999;">—</span>';
                }
                break;
                
            case 'zone':
                $zone_slug = get_post_meta($post_id, '_dm_banner_zone', true);
                if ($zone_slug) {
                    $zones = get_option('dm_ads_zones', array());
                    $zone_name = '';
                    foreach ($zones as $zone) {
                        if ($zone['slug'] === $zone_slug) {
                            $zone_name = $zone['name'];
                            break;
                        }
                    }
                    if ($zone_name) {
                        echo '<strong>' . esc_html($zone_name) . '</strong><br>';
                        echo '<span style="color:#666;font-size:12px;">(' . esc_html($zone_slug) . ')</span>';
                    } else {
                        echo '<span style="color:#999;">' . esc_html($zone_slug) . '</span>';
                    }
                } else {
                    echo '<span style="color:#999;">—</span>';
                }
                break;
                
            case 'url':
                $url = get_post_meta($post_id, '_dm_banner_url', true);
                if ($url) {
                    echo '<a href="' . esc_url($url) . '" target="_blank" style="word-break:break-all;">' . esc_html(substr($url, 0, 40)) . (strlen($url) > 40 ? '...' : '') . '</a>';
                } else {
                    echo '<span style="color:#999;">—</span>';
                }
                break;
                
            case 'banner_status':
                $status = get_post_meta($post_id, '_dm_banner_status', true);
                $start_date = get_post_meta($post_id, '_dm_banner_start_date', true);
                $end_date = get_post_meta($post_id, '_dm_banner_end_date', true);
                
                $is_scheduled = false;
                $is_expired = false;
                $current_time = current_time('mysql');
                
                if ($start_date && $start_date > $current_time) {
                    $is_scheduled = true;
                }
                
                if ($end_date && $end_date < $current_time) {
                    $is_expired = true;
                }
                
                if ($status === 'active' && !$is_expired && !$is_scheduled) {
                    echo '<span style="color:#46b450;font-weight:600;">● ' . __('Active', 'designmaster-ads') . '</span>';
                } elseif ($is_scheduled) {
                    echo '<span style="color:#00a0d2;font-weight:600;">◐ ' . __('Scheduled', 'designmaster-ads') . '</span>';
                } elseif ($is_expired) {
                    echo '<span style="color:#dc3232;font-weight:600;">● ' . __('Expired', 'designmaster-ads') . '</span>';
                } else {
                    echo '<span style="color:#999;font-weight:600;">○ ' . __('Inactive', 'designmaster-ads') . '</span>';
                }
                break;
                
            case 'priority':
                $priority = get_post_meta($post_id, '_dm_banner_priority', true);
                if ($priority) {
                    $color = '#999';
                    if ($priority >= 80) $color = '#dc3232';
                    elseif ($priority >= 60) $color = '#f56e28';
                    elseif ($priority >= 40) $color = '#00a0d2';
                    elseif ($priority >= 20) $color = '#46b450';
                    
                    echo '<span style="color:' . $color . ';font-weight:600;font-size:16px;">' . esc_html($priority) . '</span>';
                } else {
                    echo '<span style="color:#999;">50</span>';
                }
                break;
                
            case 'schedule':
                $start_date = get_post_meta($post_id, '_dm_banner_start_date', true);
                $end_date = get_post_meta($post_id, '_dm_banner_end_date', true);
                
                if ($start_date || $end_date) {
                    if ($start_date) {
                        echo '<strong>' . __('Start:', 'designmaster-ads') . '</strong> ' . date_i18n('d/m/Y H:i', strtotime($start_date)) . '<br>';
                    }
                    if ($end_date) {
                        echo '<strong>' . __('End:', 'designmaster-ads') . '</strong> ' . date_i18n('d/m/Y H:i', strtotime($end_date));
                    }
                } else {
                    echo '<span style="color:#999;">∞ ' . __('Always', 'designmaster-ads') . '</span>';
                }
                break;
                
            case 'views':
                $table_name = $wpdb->prefix . 'dm_ads_stats';
                $views = $wpdb->get_var($wpdb->prepare(
                    "SELECT SUM(views) FROM $table_name WHERE banner_id = %d",
                    $post_id
                ));
                echo '<strong>' . number_format_i18n($views ? $views : 0) . '</strong>';
                break;
                
            case 'clicks':
                $table_name = $wpdb->prefix . 'dm_ads_stats';
                $clicks = $wpdb->get_var($wpdb->prepare(
                    "SELECT SUM(clicks) FROM $table_name WHERE banner_id = %d",
                    $post_id
                ));
                echo '<strong>' . number_format_i18n($clicks ? $clicks : 0) . '</strong>';
                break;
                
            case 'ctr':
                $table_name = $wpdb->prefix . 'dm_ads_stats';
                $stats = $wpdb->get_row($wpdb->prepare(
                    "SELECT SUM(views) as total_views, SUM(clicks) as total_clicks FROM $table_name WHERE banner_id = %d",
                    $post_id
                ));
                
                if ($stats && $stats->total_views > 0) {
                    $ctr = ($stats->total_clicks / $stats->total_views) * 100;
                    $color = '#999';
                    if ($ctr >= 5) $color = '#46b450';
                    elseif ($ctr >= 2) $color = '#00a0d2';
                    elseif ($ctr >= 1) $color = '#f56e28';
                    
                    echo '<span style="color:' . $color . ';font-weight:600;">' . number_format_i18n($ctr, 2) . '%</span>';
                } else {
                    echo '<span style="color:#999;">0%</span>';
                }
                break;
        }
    }
    
    /**
     * Make columns sortable
     */
    public function sortable_columns($columns) {
        $columns['priority'] = 'priority';
        $columns['banner_status'] = 'banner_status';
        $columns['views'] = 'views';
        $columns['clicks'] = 'clicks';
        $columns['ctr'] = 'ctr';
        
        return $columns;
    }
}
