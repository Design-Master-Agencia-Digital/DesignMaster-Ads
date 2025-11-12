/**
 * DesignMaster Ads - Admin Scripts
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Zone type change handler
        $('#zone_type').on('change', function() {
            const type = $(this).val();
            if (type === 'timed') {
                $('.rotation-interval-field').show();
            } else {
                $('.rotation-interval-field').hide();
            }
        }).trigger('change');

        // Confirm zone deletion
        $('.dm-ads-delete-zone').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this zone?')) {
                e.preventDefault();
            }
        });

        // Date range selector
        $('#dm-ads-date-range').on('change', function() {
            const value = $(this).val();
            if (value === 'custom') {
                $('.custom-date-range').show();
            } else {
                $('.custom-date-range').hide();
            }
        });

    });

})(jQuery);
