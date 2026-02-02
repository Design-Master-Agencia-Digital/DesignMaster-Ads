<?php
/**
 * Admin Template: Analytics
 */

if (!defined('ABSPATH')) exit;

// Get filter values
$date_range = isset($_GET['range']) ? sanitize_text_field($_GET['range']) : '7';
$banner_id = isset($_GET['banner_id']) ? absint($_GET['banner_id']) : null;
$start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';

// Calculate days based on custom date range or preset
if ($date_range === 'custom' && $start_date && $end_date) {
    $start_timestamp = strtotime($start_date);
    $end_timestamp = strtotime($end_date);
    $days = ceil(($end_timestamp - $start_timestamp) / (60 * 60 * 24));
    if ($days < 1) $days = 1;
} else {
    $days = intval($date_range);
}

// Get stats
$stats_by_date = DM_Ads_Analytics::get_stats_by_date($banner_id, $days, $start_date, $end_date);
$device_stats = DM_Ads_Analytics::get_stats_by_device($banner_id, $days, $start_date, $end_date);
$hourly_stats = DM_Ads_Analytics::get_hourly_stats($banner_id, $days, $start_date, $end_date);
$top_banners = DM_Ads_Analytics::get_top_banners(10, $days, $start_date, $end_date);

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
        <h1><?php esc_html_e('Analytics & Statistics', 'designmaster-ads'); ?></h1>
        <p><?php esc_html_e('Detailed performance metrics and insights', 'designmaster-ads'); ?></p>
    </div>

    <!-- Filters -->
    <div class="dm-ads-filters">
        <div class="dm-ads-filter-group">
            <label for="dm-ads-date-range"><?php esc_html_e('Date Range:', 'designmaster-ads'); ?></label>
            <select id="dm-ads-date-range" name="date_range">
                <option value="7" <?php selected($date_range, '7'); ?>><?php esc_html_e('Last 7 Days', 'designmaster-ads'); ?></option>
                <option value="30" <?php selected($date_range, '30'); ?>><?php esc_html_e('Last 30 Days', 'designmaster-ads'); ?></option>
                <option value="90" <?php selected($date_range, '90'); ?>><?php esc_html_e('Last 90 Days', 'designmaster-ads'); ?></option>
                <option value="custom" <?php selected($date_range, 'custom'); ?>><?php esc_html_e('Custom Period', 'designmaster-ads'); ?></option>
            </select>
        </div>

        <div class="dm-ads-filter-group dm-ads-custom-dates" style="display: <?php echo $date_range === 'custom' ? 'flex' : 'none'; ?>;">
            <div>
                <label for="dm-ads-start-date"><?php esc_html_e('Start Date:', 'designmaster-ads'); ?></label>
                <input type="date" id="dm-ads-start-date" name="start_date" value="<?php echo esc_attr($start_date); ?>">
            </div>
            <div>
                <label for="dm-ads-end-date"><?php esc_html_e('End Date:', 'designmaster-ads'); ?></label>
                <input type="date" id="dm-ads-end-date" name="end_date" value="<?php echo esc_attr($end_date); ?>">
            </div>
        </div>

        <div class="dm-ads-filter-group">
            <label for="dm-ads-banner-filter"><?php esc_html_e('Banner:', 'designmaster-ads'); ?></label>
            <select id="dm-ads-banner-filter" name="banner_id">
                <option value=""><?php esc_html_e('All Banners', 'designmaster-ads'); ?></option>
                <?php
                $banners = get_posts(array('post_type' => 'dm_banner', 'posts_per_page' => -1));
                foreach ($banners as $b) {
                    echo '<option value="' . $b->ID . '" ' . selected($banner_id, $b->ID, false) . '>' . esc_html($b->post_title) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="dm-ads-filter-group">
            <a href="#" id="dm-ads-export-csv" class="button"><?php esc_html_e('Export to CSV', 'designmaster-ads'); ?></a>
        </div>
    </div>

    <!-- Views vs Clicks Chart -->
    <div class="dm-ads-chart-container">
        <h2><?php esc_html_e('Views & Clicks Trend', 'designmaster-ads'); ?></h2>
        <div class="dm-ads-chart-wrapper">
            <canvas id="dm-chart-views-clicks"></canvas>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Device Distribution -->
        <div class="dm-ads-chart-container">
            <h2><?php esc_html_e('Device Distribution', 'designmaster-ads'); ?></h2>
            <div class="dm-ads-chart-wrapper" style="height: 300px;">
                <canvas id="dm-chart-devices"></canvas>
            </div>
        </div>

        <!-- Top Banners -->
        <div class="dm-ads-chart-container">
            <h2><?php esc_html_e('Top Banners by Clicks', 'designmaster-ads'); ?></h2>
            <div class="dm-ads-chart-wrapper" style="height: 300px;">
                <canvas id="dm-chart-top-banners"></canvas>
            </div>
        </div>
    </div>

    <!-- Hourly Performance -->
    <div class="dm-ads-chart-container">
        <h2><?php esc_html_e('Performance by Hour', 'designmaster-ads'); ?></h2>
        <div class="dm-ads-chart-wrapper">
            <canvas id="dm-chart-hourly"></canvas>
        </div>
    </div>
</div>

<?php
$analytics_data = array(
    'viewsClicks' => array(
        'labels' => $chart_labels,
        'views' => $chart_views,
        'clicks' => $chart_clicks
    ),
    'devices' => $device_views,
    'hourly' => array(
        'hours' => $hourly_hours,
        'views' => $hourly_views,
        'clicks' => $hourly_clicks
    ),
    'topBanners' => array(
        'labels' => $top_banner_labels,
        'clicks' => $top_banner_clicks
    )
);
wp_add_inline_script('designmaster-ads-analytics', 'window.dmAnalyticsData = ' . wp_json_encode($analytics_data) . ';');
?>
