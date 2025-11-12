<?php
/**
 * Admin Template: Dashboard
 */

if (!defined('ABSPATH')) exit;

// Get overall stats
$stats_7_days = DM_Ads_Analytics::get_stats_by_date(null, 7);
$stats_30_days = DM_Ads_Analytics::get_stats_by_date(null, 30);
$device_stats = DM_Ads_Analytics::get_stats_by_device(null, 30);
$top_banners = DM_Ads_Analytics::get_top_banners(5, 30);

// Calculate totals
$total_views = array_sum(array_column($stats_30_days, 'views'));
$total_clicks = array_sum(array_column($stats_30_days, 'clicks'));
$total_ctr = $total_views > 0 ? round(($total_clicks / $total_views) * 100, 2) : 0;

// Count active banners
$active_banners = wp_count_posts('dm_banner')->publish;
?>

<div class="wrap">
    <div class="dm-ads-admin-header">
        <h1><?php esc_html_e('DesignMaster Ads Dashboard', 'designmaster-ads'); ?></h1>
        <p><?php esc_html_e('Overview of your banner performance and statistics', 'designmaster-ads'); ?></p>
    </div>

    <!-- Stats Cards -->
    <div class="dm-ads-stats-grid">
        <div class="dm-ads-stat-card info">
            <h3><?php esc_html_e('Total Views (30 days)', 'designmaster-ads'); ?></h3>
            <div class="dm-ads-stat-value"><?php echo esc_html(number_format($total_views)); ?></div>
            <div class="dm-ads-stat-label"><?php esc_html_e('Banner impressions', 'designmaster-ads'); ?></div>
        </div>

        <div class="dm-ads-stat-card success">
            <h3><?php esc_html_e('Total Clicks (30 days)', 'designmaster-ads'); ?></h3>
            <div class="dm-ads-stat-value"><?php echo esc_html(number_format($total_clicks)); ?></div>
            <div class="dm-ads-stat-label"><?php esc_html_e('User interactions', 'designmaster-ads'); ?></div>
        </div>

        <div class="dm-ads-stat-card warning">
            <h3><?php esc_html_e('Average CTR', 'designmaster-ads'); ?></h3>
            <div class="dm-ads-stat-value"><?php echo esc_html($total_ctr); ?>%</div>
            <div class="dm-ads-stat-label"><?php esc_html_e('Click-through rate', 'designmaster-ads'); ?></div>
        </div>

        <div class="dm-ads-stat-card">
            <h3><?php esc_html_e('Active Banners', 'designmaster-ads'); ?></h3>
            <div class="dm-ads-stat-value"><?php echo esc_html($active_banners); ?></div>
            <div class="dm-ads-stat-label"><?php esc_html_e('Currently published', 'designmaster-ads'); ?></div>
        </div>
    </div>

    <!-- Top Performing Banners -->
    <div class="dm-ads-top-banners">
        <h2><?php esc_html_e('Top 5 Performing Banners (Last 30 Days)', 'designmaster-ads'); ?></h2>
        <table>
            <thead>
                <tr>
                    <th><?php esc_html_e('Banner', 'designmaster-ads'); ?></th>
                    <th><?php esc_html_e('Views', 'designmaster-ads'); ?></th>
                    <th><?php esc_html_e('Clicks', 'designmaster-ads'); ?></th>
                    <th><?php esc_html_e('CTR', 'designmaster-ads'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($top_banners)): ?>
                    <?php foreach ($top_banners as $banner): ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($banner['id'])); ?>">
                                    <?php echo esc_html($banner['title']); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html(number_format($banner['views'])); ?></td>
                            <td><?php echo esc_html(number_format($banner['clicks'])); ?></td>
                            <td><?php echo esc_html($banner['ctr']); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4"><?php esc_html_e('No data available yet', 'designmaster-ads'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Quick Links -->
    <div class="dm-ads-chart-container">
        <h2><?php esc_html_e('Quick Actions', 'designmaster-ads'); ?></h2>
        <p>
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=dm_banner')); ?>" class="button button-primary">
                <?php esc_html_e('Add New Banner', 'designmaster-ads'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-ads-zones')); ?>" class="button">
                <?php esc_html_e('Manage Zones', 'designmaster-ads'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-ads-analytics')); ?>" class="button">
                <?php esc_html_e('View Detailed Analytics', 'designmaster-ads'); ?>
            </a>
        </p>
    </div>
</div>
