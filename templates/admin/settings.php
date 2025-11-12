<?php
/**
 * Admin Template: Settings
 */

if (!defined('ABSPATH')) exit;

// Settings are passed from the admin class
settings_errors('dm_ads_settings');
?>

<div class="wrap">
    <div class="dm-ads-admin-header">
        <h1><?php esc_html_e('DesignMaster Ads Settings', 'designmaster-ads'); ?></h1>
        <p><?php esc_html_e('Configure plugin behavior and tracking options', 'designmaster-ads'); ?></p>
    </div>

    <div class="dm-ads-chart-container">
        <form method="post" action="">
            <?php wp_nonce_field('dm_ads_settings_action', 'dm_ads_settings_nonce'); ?>
            
            <h2><?php esc_html_e('Tracking Settings', 'designmaster-ads'); ?></h2>
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e('Track Banner Views', 'designmaster-ads'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="track_views" value="1" <?php checked($settings['track_views'], true); ?>>
                            <?php esc_html_e('Enable view tracking', 'designmaster-ads'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Track how many times each banner is displayed', 'designmaster-ads'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Track Banner Clicks', 'designmaster-ads'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="track_clicks" value="1" <?php checked($settings['track_clicks'], true); ?>>
                            <?php esc_html_e('Enable click tracking', 'designmaster-ads'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Track when users click on banners', 'designmaster-ads'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Anonymize IP Addresses', 'designmaster-ads'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="anonymize_ip" value="1" <?php checked($settings['anonymize_ip'], true); ?>>
                            <?php esc_html_e('Anonymize user IP addresses', 'designmaster-ads'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Recommended for GDPR compliance. Last octet of IP will be removed.', 'designmaster-ads'); ?></p>
                    </td>
                </tr>
            </table>

            <h2><?php _e('Performance Settings', 'designmaster-ads'); ?></h2>
            <table class="form-table">
                <tr>
                    <th><label for="cache_time"><?php _e('Cache Time', 'designmaster-ads'); ?></label></th>
                    <td>
                        <input type="number" id="cache_time" name="cache_time" value="<?php echo esc_attr($settings['cache_time']); ?>" min="0" class="small-text">
                        <span><?php _e('seconds', 'designmaster-ads'); ?></span>
                        <p class="description"><?php _e('How long to cache banner queries. Set to 0 to disable caching.', 'designmaster-ads'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="default_rotation_time"><?php _e('Default Rotation Time', 'designmaster-ads'); ?></label></th>
                    <td>
                        <input type="number" id="default_rotation_time" name="default_rotation_time" value="<?php echo esc_attr($settings['default_rotation_time']); ?>" min="1" max="60" class="small-text">
                        <span><?php _e('seconds', 'designmaster-ads'); ?></span>
                        <p class="description"><?php _e('Default interval for timed banner rotation', 'designmaster-ads'); ?></p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <button type="submit" name="dm_ads_save_settings" class="button button-primary">
                    <?php _e('Save Settings', 'designmaster-ads'); ?>
                </button>
            </p>
        </form>
    </div>

    <!-- Plugin Info -->
    <div class="dm-ads-chart-container">
        <h2><?php _e('Plugin Information', 'designmaster-ads'); ?></h2>
        <p>
            <strong><?php _e('Version:', 'designmaster-ads'); ?></strong> <?php echo DM_ADS_VERSION; ?><br>
            <strong><?php _e('Database Table:', 'designmaster-ads'); ?></strong> <?php echo $GLOBALS['wpdb']->prefix; ?>dm_ads_stats
        </p>
    </div>
</div>
