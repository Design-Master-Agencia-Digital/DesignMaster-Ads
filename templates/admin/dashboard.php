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

// Get total and unique stats
$total_stats = DM_Ads_Analytics::get_total_stats(30);

// Extract stats
$total_views = $total_stats['total_views'];
$total_clicks = $total_stats['total_clicks'];
$unique_views = $total_stats['unique_views'];
$unique_clicks = $total_stats['unique_clicks'];
$total_ctr = $total_stats['total_ctr'];
$unique_ctr = $total_stats['unique_ctr'];

// Count active banners
$active_banners = wp_count_posts('dm_banner')->publish;

// Prepare chart data for last 7 days
$chart_labels = array();
$chart_views = array();
$chart_clicks = array();

if (is_array($stats_7_days) && !empty($stats_7_days)) {
    foreach ($stats_7_days as $date => $stat) {
        $chart_labels[] = date_i18n('d/m', strtotime($date));
        $chart_views[] = isset($stat['views']) ? $stat['views'] : 0;
        $chart_clicks[] = isset($stat['clicks']) ? $stat['clicks'] : 0;
    }
} else {
    // Create dummy data for empty chart
    for ($i = 6; $i >= 0; $i--) {
        $chart_labels[] = date_i18n('d/m', strtotime("-$i days"));
        $chart_views[] = 0;
        $chart_clicks[] = 0;
    }
}

// Device stats for pie chart
$device_labels = array();
$device_data = array();
if (is_array($device_stats) && !empty($device_stats)) {
    foreach ($device_stats as $device => $stats) {
        $device_labels[] = ucfirst($device);
        $device_data[] = isset($stats['views']) ? $stats['views'] : 0;
    }
} else {
    // Default device data
    $device_labels = array('Desktop', 'Mobile', 'Tablet');
    $device_data = array(0, 0, 0);
}
?>

<div class="wrap dm-ads-dashboard">
    <div class="dm-ads-admin-header">
        <h1><?php esc_html_e('DesignMaster Ads Dashboard', 'designmaster-ads'); ?></h1>
        <p><?php esc_html_e('Overview of your banner performance and statistics', 'designmaster-ads'); ?></p>
    </div>

    <!-- Stats Cards -->
    <div class="dm-ads-stats-grid">
        <div class="dm-ads-stat-card info">
            <h3><?php esc_html_e('Total Views (30 days)', 'designmaster-ads'); ?></h3>
            <div class="dm-ads-stat-value"><?php echo esc_html(number_format($total_views)); ?></div>
            <div class="dm-ads-stat-label">
                <?php esc_html_e('Banner impressions', 'designmaster-ads'); ?>
                <small style="display: block; margin-top: 4px; opacity: 0.8;">
                    <?php echo esc_html(number_format($unique_views)); ?> <?php esc_html_e('unique visitors', 'designmaster-ads'); ?>
                </small>
            </div>
        </div>

        <div class="dm-ads-stat-card success">
            <h3><?php esc_html_e('Total Clicks (30 days)', 'designmaster-ads'); ?></h3>
            <div class="dm-ads-stat-value"><?php echo esc_html(number_format($total_clicks)); ?></div>
            <div class="dm-ads-stat-label">
                <?php esc_html_e('User interactions', 'designmaster-ads'); ?>
                <small style="display: block; margin-top: 4px; opacity: 0.8;">
                    <?php echo esc_html(number_format($unique_clicks)); ?> <?php esc_html_e('unique users', 'designmaster-ads'); ?>
                </small>
            </div>
        </div>

        <div class="dm-ads-stat-card warning">
            <h3><?php esc_html_e('Average CTR', 'designmaster-ads'); ?></h3>
            <div class="dm-ads-stat-value"><?php echo esc_html($total_ctr); ?>%</div>
            <div class="dm-ads-stat-label">
                <?php esc_html_e('Click-through rate', 'designmaster-ads'); ?>
                <small style="display: block; margin-top: 4px; opacity: 0.8;">
                    <?php echo esc_html($unique_ctr); ?>% <?php esc_html_e('unique CTR', 'designmaster-ads'); ?>
                </small>
            </div>
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

    <!-- Performance Charts -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 20px;">
        <!-- Views & Clicks Chart -->
        <div class="dm-ads-chart-container">
            <h2><?php esc_html_e('Performance Trend (Last 7 Days)', 'designmaster-ads'); ?></h2>
            <canvas id="dm-ads-performance-chart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Device Distribution Chart -->
        <div class="dm-ads-chart-container">
            <h2><?php esc_html_e('Views by Device', 'designmaster-ads'); ?></h2>
            <canvas id="dm-ads-device-chart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="dm-ads-chart-container">
        <h2><?php esc_html_e('Quick Actions', 'designmaster-ads'); ?></h2>
        <p>
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=dm_banner')); ?>" class="button button-primary">
                <span class="dashicons dashicons-plus-alt" style="margin-top: 3px;"></span>
                <?php esc_html_e('Add New Banner', 'designmaster-ads'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-ads-zones')); ?>" class="button">
                <span class="dashicons dashicons-admin-settings" style="margin-top: 3px;"></span>
                <?php esc_html_e('Manage Zones', 'designmaster-ads'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-ads-analytics')); ?>" class="button">
                <span class="dashicons dashicons-chart-bar" style="margin-top: 3px;"></span>
                <?php esc_html_e('View Detailed Analytics', 'designmaster-ads'); ?>
            </a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Trend Chart
    const performanceCtx = document.getElementById('dm-ads-performance-chart');
    if (performanceCtx) {
        new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: '<?php esc_html_e('Views', 'designmaster-ads'); ?>',
                    data: <?php echo json_encode($chart_views); ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: '<?php esc_html_e('Clicks', 'designmaster-ads'); ?>',
                    data: <?php echo json_encode($chart_clicks); ?>,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }

    // Device Distribution Chart
    const deviceCtx = document.getElementById('dm-ads-device-chart');
    if (deviceCtx && <?php echo json_encode($device_data); ?>.length > 0) {
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($device_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($device_data); ?>,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Animate stat cards on load
    document.querySelectorAll('.dm-ads-stat-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
