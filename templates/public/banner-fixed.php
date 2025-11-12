<?php
/**
 * Template: Fixed Banner
 * Always displays the same banner (highest priority)
 */

if (!defined('ABSPATH')) exit;
?>

<div class="dm-ads-container dm-ads-fixed" style="max-width: <?php echo esc_attr($zone['width']); ?>px;">
    <div class="dm-ads-banner" 
         data-banner-id="<?php echo esc_attr($banner['id']); ?>"
         data-zone-id="<?php echo esc_attr($zone['slug']); ?>">
        <a href="<?php echo esc_url($banner['url']); ?>" 
           target="_blank" 
           rel="noopener noreferrer">
            <img src="<?php echo esc_url($banner['image']); ?>" 
                 alt="<?php echo esc_attr($banner['title']); ?>"
                 width="<?php echo esc_attr($zone['width']); ?>"
                 height="<?php echo esc_attr($zone['height']); ?>">
        </a>
    </div>
</div>
