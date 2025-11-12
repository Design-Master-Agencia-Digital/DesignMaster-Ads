<?php
/**
 * Admin Template: Analytics
 */

if (!defined('ABSPATH')) exit;

// Get filter values
$date_range = isset($_GET['range']) ? sanitize_text_field($_GET['range']) : '7';
$banner_id = isset($_GET['banner_id']) ? absint($_GET['banner_id']) : null;

// Get stats
$days = intval($date_range);
$stats_by_date = DM_Ads_Analytics::get_stats_by_date($banner_id, $days);
$device_stats = DM_Ads_Analytics::get_stats_by_device($banner_id, $days);
$hourly_stats = DM_Ads_Analytics::get_hourly_stats($banner_id, $days);
$top_banners = DM_Ads_Analytics::get_top_banners(10, $days);

// Prepare data for charts
$chart_labels = array_keys($stats_by_date);
$chart_views = array_column($stats_by_date, 'views');
$chart_clicks = array_column($stats_by_date, 'clicks');

$device_views = array(
    'desktop' => $device_stats['desktop']['views'],
    'mobile' => $device_stats['mobile']['views'],
    'tablet' => $device_stats['tablet']['views']
);

$hourly_hours = array_keys($hourly_stats);
$hourly_views = array_column($hourly_stats, 'views');
$hourly_clicks = array_column($hourly_stats, 'clicks');

$top_banner_labels = array_column($top_banners, 'title');
$top_banner_clicks = array_column($top_banners, 'clicks');
?>

<div class="wrap">
    <div class="dm-ads-admin-header">
        <h1><?php _e('Analytics & Statistics', 'designmaster-ads'); ?></h1>
        <p><?php _e('Detailed performance metrics and insights', 'designmaster-ads'); ?></p>
    </div>

    <!-- Filters -->
    <div class="dm-ads-filters">
        <div class="dm-ads-filter-group">
            <label for="dm-ads-date-range"><?php _e('Date Range:', 'designmaster-ads'); ?></label>
            <select id="dm-ads-date-range" name="date_range">
                <option value="7" <?php selected($date_range, '7'); ?>><?php _e('Last 7 Days', 'designmaster-ads'); ?></option>
                <option value="30" <?php selected($date_range, '30'); ?>><?php _e('Last 30 Days', 'designmaster-ads'); ?></option>
                <option value="90" <?php selected($date_range, '90'); ?>><?php _e('Last 90 Days', 'designmaster-ads'); ?></option>
            </select>
        </div>

        <div class="dm-ads-filter-group">
            <label for="dm-ads-banner-filter"><?php _e('Banner:', 'designmaster-ads'); ?></label>
            <select id="dm-ads-banner-filter" name="banner_id">
                <option value=""><?php _e('All Banners', 'designmaster-ads'); ?></option>
                <?php
                $banners = get_posts(array('post_type' => 'dm_banner', 'posts_per_page' => -1));
                foreach ($banners as $b) {
                    echo '<option value="' . $b->ID . '" ' . selected($banner_id, $b->ID, false) . '>' . esc_html($b->post_title) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="dm-ads-filter-group">
            <a href="#" id="dm-ads-export-csv" class="button"><?php _e('Export to CSV', 'designmaster-ads'); ?></a>
        </div>
    </div>

    <!-- Views vs Clicks Chart -->
    <div class="dm-ads-chart-container">
        <h2><?php _e('Views & Clicks Trend', 'designmaster-ads'); ?></h2>
        <div class="dm-ads-chart-wrapper">
            <canvas id="dm-chart-views-clicks"></canvas>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Device Distribution -->
        <div class="dm-ads-chart-container">
            <h2><?php _e('Device Distribution', 'designmaster-ads'); ?></h2>
            <div class="dm-ads-chart-wrapper" style="height: 300px;">
                <canvas id="dm-chart-devices"></canvas>
            </div>
        </div>

        <!-- Top Banners -->
        <div class="dm-ads-chart-container">
            <h2><?php _e('Top Banners by Clicks', 'designmaster-ads'); ?></h2>
            <div class="dm-ads-chart-wrapper" style="height: 300px;">
                <canvas id="dm-chart-top-banners"></canvas>
            </div>
        </div>
    </div>

    <!-- Hourly Performance -->
    <div class="dm-ads-chart-container">
        <h2><?php _e('Performance by Hour', 'designmaster-ads'); ?></h2>
        <div class="dm-ads-chart-wrapper">
            <canvas id="dm-chart-hourly"></canvas>
        </div>
    </div>
</div>

<script>
    // Pass data to JavaScript
    window.dmAnalyticsData = {
        viewsClicks: {
            labels: <?php echo json_encode($chart_labels); ?>,
            views: <?php echo json_encode($chart_views); ?>,
            clicks: <?php echo json_encode($chart_clicks); ?>
        },
        devices: <?php echo json_encode($device_views); ?>,
        hourly: {
            hours: <?php echo json_encode($hourly_hours); ?>,
            views: <?php echo json_encode($hourly_views); ?>,
            clicks: <?php echo json_encode($hourly_clicks); ?>
        },
        topBanners: {
            labels: <?php echo json_encode($top_banner_labels); ?>,
            clicks: <?php echo json_encode($top_banner_clicks); ?>
        }
    };
</script>
