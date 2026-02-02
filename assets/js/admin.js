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

        // Media Uploader
        var mediaUploader;
        
        $('.dm-upload-image-button').on('click', function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: dmAdsAdmin.selectImage,
                button: {
                    text: dmAdsAdmin.useImage
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#dm_banner_image_id').val(attachment.id);
                $('.dm-banner-image-preview').html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; background: #f9f9f9;">');
                
                if ($('.dm-remove-image-button').length === 0) {
                    $('.dm-upload-image-button').after('<button type="button" class="button dm-remove-image-button"><span class="dashicons dashicons-no" style="margin-top: 3px;"></span> ' + dmAdsAdmin.removeImage + '</button>');
                }
            });
            
            mediaUploader.open();
        });
        
        $(document).on('click', '.dm-remove-image-button', function(e) {
            e.preventDefault();
            $('#dm_banner_image_id').val('');
            $('.dm-banner-image-preview').html('<div style="border: 2px dashed #ddd; padding: 40px; text-align: center; background: #f9f9f9; color: #666;"><span class="dashicons dashicons-format-image" style="font-size: 48px; width: 48px; height: 48px;"></span><p>' + dmAdsAdmin.noImage + '</p></div>');
            $(this).remove();
        });

    });

})(jQuery);
