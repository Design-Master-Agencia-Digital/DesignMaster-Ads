<?php
/**
 * Admin Template: Zones Manager
 */

if (!defined('ABSPATH')) exit;

$zones = DM_Ads_Zone::get_all_zones();
$editing_zone = isset($_GET['edit']) ? DM_Ads_Zone::get_zone($_GET['edit']) : null;

settings_errors('dm_ads_zones');
?>

<div class="wrap">
    <div class="dm-ads-admin-header">
        <h1><?php esc_html_e('Banner Zones Management', 'designmaster-ads'); ?></h1>
        <p><?php esc_html_e('Create and manage your banner display zones', 'designmaster-ads'); ?></p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Zone Form -->
        <div class="dm-ads-chart-container">
            <h2><?php echo $editing_zone ? esc_html__('Edit Zone', 'designmaster-ads') : esc_html__('Add New Zone', 'designmaster-ads'); ?></h2>
            
            <form method="post" action="">
                <?php wp_nonce_field('dm_ads_zone_action', 'dm_ads_zone_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th><label for="zone_name"><?php esc_html_e('Zone Name', 'designmaster-ads'); ?></label></th>
                        <td>
                            <input type="text" id="zone_name" name="name" value="<?php echo $editing_zone ? esc_attr($editing_zone['name']) : ''; ?>" class="regular-text" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="zone_slug"><?php esc_html_e('Slug', 'designmaster-ads'); ?></label></th>
                        <td>
                            <input type="text" id="zone_slug" name="zone_slug" value="<?php echo $editing_zone ? esc_attr($editing_zone['slug']) : ''; ?>" class="regular-text" required>
                            <p class="description"><?php esc_html_e('Used in shortcode: [dm_ads zone="slug"]', 'designmaster-ads'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="zone_type"><?php esc_html_e('Type', 'designmaster-ads'); ?></label></th>
                        <td>
                            <select id="zone_type" name="type">
                                <option value="fixed" <?php echo ($editing_zone && $editing_zone['type'] === 'fixed') ? 'selected' : ''; ?>>
                                    <?php esc_html_e('Fixed (Always same banner)', 'designmaster-ads'); ?>
                                </option>
                                <option value="reload" <?php echo ($editing_zone && $editing_zone['type'] === 'reload') ? 'selected' : ''; ?>>
                                    <?php esc_html_e('Reload (Random per page load)', 'designmaster-ads'); ?>
                                </option>
                                <option value="timed" <?php echo ($editing_zone && $editing_zone['type'] === 'timed') ? 'selected' : ''; ?>>
                                    <?php esc_html_e('Timed (Auto rotate)', 'designmaster-ads'); ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr class="rotation-interval-field">
                        <th><label for="rotation_interval"><?php esc_html_e('Rotation Interval', 'designmaster-ads'); ?></label></th>
                        <td>
                            <input type="number" id="rotation_interval" name="rotation_interval" value="<?php echo $editing_zone ? esc_attr($editing_zone['rotation_interval']) : '5'; ?>" min="1" max="60" class="small-text">
                            <span><?php esc_html_e('seconds', 'designmaster-ads'); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="zone_width"><?php esc_html_e('Width', 'designmaster-ads'); ?></label></th>
                        <td>
                            <input type="number" id="zone_width" name="width" value="<?php echo $editing_zone ? esc_attr($editing_zone['width']) : '728'; ?>" class="small-text" required>
                            <span>px</span>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="zone_height"><?php esc_html_e('Height', 'designmaster-ads'); ?></label></th>
                        <td>
                            <input type="number" id="zone_height" name="height" value="<?php echo $editing_zone ? esc_attr($editing_zone['height']) : '90'; ?>" class="small-text" required>
                            <span>px</span>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" name="dm_ads_save_zone" class="button button-primary">
                        <?php echo $editing_zone ? esc_html__('Update Zone', 'designmaster-ads') : esc_html__('Create Zone', 'designmaster-ads'); ?>
                    </button>
                    <?php if ($editing_zone): ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=dm-ads-zones')); ?>" class="button">
                            <?php esc_html_e('Cancel', 'designmaster-ads'); ?>
                        </a>
                    <?php endif; ?>
                </p>
            </form>
        </div>

        <!-- Zones List -->
        <div class="dm-ads-chart-container">
            <h2><?php esc_html_e('Existing Zones', 'designmaster-ads'); ?></h2>
            
            <?php if (!empty($zones)): ?>
                <div class="dm-ads-zones-list">
                    <?php foreach ($zones as $zone): ?>
                        <div class="dm-ads-zone-item">
                            <div class="dm-ads-zone-info">
                                <h3><?php echo esc_html($zone['name']); ?></h3>
                                <div class="dm-ads-zone-meta">
                                    <strong><?php esc_html_e('Slug:', 'designmaster-ads'); ?></strong> <?php echo esc_html($zone['slug']); ?><br>
                                    <strong><?php esc_html_e('Type:', 'designmaster-ads'); ?></strong> <?php echo esc_html(ucfirst($zone['type'])); ?><br>
                                    <strong><?php esc_html_e('Size:', 'designmaster-ads'); ?></strong> <?php echo esc_html($zone['width']); ?>x<?php echo esc_html($zone['height']); ?>px<br>
                                    <strong><?php esc_html_e('Shortcode:', 'designmaster-ads'); ?></strong> 
                                    <code>[dm_ads zone="<?php echo esc_attr($zone['slug']); ?>"]</code>
                                </div>
                            </div>
                            <div class="dm-ads-zone-actions">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=dm-ads-zones&edit=' . $zone['slug'])); ?>" class="button">
                                    <?php esc_html_e('Edit', 'designmaster-ads'); ?>
                                </a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=dm-ads-zones&action=delete&zone=' . $zone['slug']), 'dm_ads_delete_zone_' . $zone['slug'])); ?>" 
                                   class="button dm-ads-delete-zone" 
                                   style="color: #b32d2e;">
                                    <?php esc_html_e('Delete', 'designmaster-ads'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p><?php esc_html_e('No zones created yet. Create your first zone to get started!', 'designmaster-ads'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
