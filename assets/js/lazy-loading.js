/**
 * DesignMaster Ads - Lazy Loading
 */
(function() {
    'use strict';

    // Check for native lazy loading support
    const supportsLazyLoading = 'loading' in HTMLImageElement.prototype;

    // Initialize lazy loading
    function initLazyLoading() {
        const bannerImages = document.querySelectorAll('.dm-ads-banner-image');
        
        if (!bannerImages.length) return;

        if (supportsLazyLoading) {
            // Native lazy loading is supported
            bannerImages.forEach(img => {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
                
                // If image is already loaded (from cache)
                if (img.complete) {
                    img.classList.add('loaded');
                }
            });
        } else {
            // Fallback to Intersection Observer
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            
                            // Load the image
                            img.addEventListener('load', function() {
                                this.classList.add('loaded');
                            });
                            
                            // Stop observing this image
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                });

                bannerImages.forEach(img => {
                    imageObserver.observe(img);
                });
            } else {
                // No support for lazy loading, load all images immediately
                bannerImages.forEach(img => {
                    img.classList.add('loaded');
                });
            }
        }
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLazyLoading);
    } else {
        initLazyLoading();
    }

    // Re-initialize on dynamic content load
    window.dmAdsInitLazyLoad = initLazyLoading;
})();
