/**
 * DesignMaster Ads - Analytics Dashboard
 */
(function($) {
    'use strict';

    let chartInstances = {};

    // Initialize charts
    function initCharts() {
        // Views vs Clicks Chart
        if ($('#dm-chart-views-clicks').length) {
            createViewsClicksChart();
        }

        // Device Stats Chart
        if ($('#dm-chart-devices').length) {
            createDeviceChart();
        }

        // Hourly Heatmap Chart
        if ($('#dm-chart-hourly').length) {
            createHourlyChart();
        }

        // Top Banners Chart
        if ($('#dm-chart-top-banners').length) {
            createTopBannersChart();
        }
    }

    // Views vs Clicks Line Chart
    function createViewsClicksChart() {
        const ctx = document.getElementById('dm-chart-views-clicks').getContext('2d');
        
        // This data would come from PHP via wp_localize_script
        const data = window.dmAnalyticsData?.viewsClicks || {
            labels: [],
            views: [],
            clicks: []
        };

        chartInstances.viewsClicks = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Views',
                        data: data.views,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Clicks',
                        data: data.clicks,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Views & Clicks Over Time'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Device Distribution Pie Chart
    function createDeviceChart() {
        const ctx = document.getElementById('dm-chart-devices').getContext('2d');
        
        const data = window.dmAnalyticsData?.devices || {
            desktop: 0,
            mobile: 0,
            tablet: 0
        };

        chartInstances.devices = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Desktop', 'Mobile', 'Tablet'],
                datasets: [{
                    data: [data.desktop, data.mobile, data.tablet],
                    backgroundColor: [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        'rgb(255, 205, 86)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Views by Device Type'
                    }
                }
            }
        });
    }

    // Hourly Performance Bar Chart
    function createHourlyChart() {
        const ctx = document.getElementById('dm-chart-hourly').getContext('2d');
        
        const data = window.dmAnalyticsData?.hourly || {
            hours: Array.from({length: 24}, (_, i) => i + 'h'),
            views: Array(24).fill(0),
            clicks: Array(24).fill(0)
        };

        chartInstances.hourly = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.hours,
                datasets: [
                    {
                        label: 'Views',
                        data: data.views,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    },
                    {
                        label: 'Clicks',
                        data: data.clicks,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Performance by Hour of Day'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Top Banners Horizontal Bar Chart
    function createTopBannersChart() {
        const ctx = document.getElementById('dm-chart-top-banners').getContext('2d');
        
        const data = window.dmAnalyticsData?.topBanners || {
            labels: [],
            clicks: []
        };

        chartInstances.topBanners = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Clicks',
                    data: data.clicks,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Top Performing Banners'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Export to CSV
    function exportToCSV() {
        window.location.href = dmAdsAdmin.ajaxUrl + '?action=dm_ads_export_csv&nonce=' + dmAdsAdmin.nonce;
    }

    // Initialize on document ready
    $(document).ready(function() {
        initCharts();

        // Export button handler
        $('#dm-ads-export-csv').on('click', function(e) {
            e.preventDefault();
            exportToCSV();
        });

        // Toggle custom date fields
        $('#dm-ads-date-range').on('change', function() {
            const $customDates = $('.dm-ads-custom-dates');
            if ($(this).val() === 'custom') {
                $customDates.show();
            } else {
                $customDates.hide();
                // If not custom, reload immediately
                reloadWithFilters();
            }
        });

        // Refresh charts on date range change
        $('#dm-ads-banner-filter').on('change', function() {
            reloadWithFilters();
        });

        // Apply custom date range
        $('#dm-ads-start-date, #dm-ads-end-date').on('change', function() {
            const startDate = $('#dm-ads-start-date').val();
            const endDate = $('#dm-ads-end-date').val();
            
            // Only reload if both dates are set
            if (startDate && endDate) {
                reloadWithFilters();
            }
        });
    });

    // Helper function to reload with filters
    function reloadWithFilters() {
        const dateRange = $('#dm-ads-date-range').val();
        const bannerId = $('#dm-ads-banner-filter').val();
        const startDate = $('#dm-ads-start-date').val();
        const endDate = $('#dm-ads-end-date').val();
        
        let url = window.location.pathname + '?page=dm-ads-analytics';
        if (dateRange) url += '&range=' + dateRange;
        if (bannerId) url += '&banner_id=' + bannerId;
        if (dateRange === 'custom' && startDate && endDate) {
            url += '&start_date=' + startDate;
            url += '&end_date=' + endDate;
        }
        
        window.location.href = url;
    }

})(jQuery);
