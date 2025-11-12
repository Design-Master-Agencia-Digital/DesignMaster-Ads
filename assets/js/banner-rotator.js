/**
 * DesignMaster Ads - Banner Rotator (Frontend)
 */
(function($) {
    'use strict';

    // Track banner view
    function trackView(bannerId, zoneId) {
        if (!dmAds.trackViews) return;

        $.ajax({
            url: dmAds.ajaxUrl,
            type: 'POST',
            data: {
                action: 'dm_ads_track_view',
                nonce: dmAds.nonce,
                banner_id: bannerId,
                zone_id: zoneId
            }
        });
    }

    // Track banner click
    function trackClick(bannerId, zoneId) {
        if (!dmAds.trackClicks) return;

        $.ajax({
            url: dmAds.ajaxUrl,
            type: 'POST',
            data: {
                action: 'dm_ads_track_click',
                nonce: dmAds.nonce,
                banner_id: bannerId,
                zone_id: zoneId
            }
        });
    }

    // Initialize timed rotation banners
    function initTimedBanners() {
        $('.dm-ads-timed').each(function() {
            const $container = $(this);
            const $slides = $container.find('.dm-ads-slide');
            const $dots = $container.find('.dm-ads-dot');
            const interval = parseInt($container.data('interval')) || 5;
            let currentIndex = 0;

            if ($slides.length <= 1) return;

            // Show first slide
            $slides.eq(0).addClass('active');
            $dots.eq(0).addClass('active');

            // Auto rotate
            const rotateInterval = setInterval(function() {
                currentIndex = (currentIndex + 1) % $slides.length;
                showSlide(currentIndex);
            }, interval * 1000);

            // Dot navigation
            $dots.on('click', function() {
                clearInterval(rotateInterval);
                currentIndex = $(this).index();
                showSlide(currentIndex);
            });

            function showSlide(index) {
                $slides.removeClass('active').eq(index).addClass('active');
                $dots.removeClass('active').eq(index).addClass('active');

                // Track view for new slide
                const bannerId = $slides.eq(index).data('banner-id');
                const zoneId = $container.data('zone-id');
                trackView(bannerId, zoneId);
            }

            // Track initial view
            const initialBannerId = $slides.eq(0).data('banner-id');
            const zoneId = $container.data('zone-id');
            trackView(initialBannerId, zoneId);
        });
    }

    // Track views for fixed and reload banners
    function initBannerTracking() {
        $('.dm-ads-banner[data-banner-id]').each(function() {
            const $banner = $(this);
            const bannerId = $banner.data('banner-id');
            const zoneId = $banner.data('zone-id');

            // Track view
            trackView(bannerId, zoneId);

            // Track click
            $banner.find('a').on('click', function() {
                trackClick(bannerId, zoneId);
            });
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        initTimedBanners();
        initBannerTracking();
    });

})(jQuery);
