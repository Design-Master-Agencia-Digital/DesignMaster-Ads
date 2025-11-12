<?php
/**
 * Template: Reload Banner
 * Shows a random banner on each page reload
 */

if (!defined('ABSPATH')) exit;
?>

<div class="dm-ads-container dm-ads-reload" style="max-width: <?php echo esc_attr($zone['width']); ?>px;">
    <div class="dm-ads-banner" 
         data-banner-id="<?php echo esc_attr($banner['id']); ?>"
         data-zone-id="<?php echo esc_attr($zone['slug']); ?>">
        <a href="<?php echo esc_url($banner['url']); ?>" 
           target="_blank" 
           rel="noopener noreferrer">
            <img src="<?php echo esc_url($banner['image']); ?>" 
                 alt="<?php echo esc_attr($banner['title']); ?>"
                 width="<?php echo esc_attr($zone['width']); ?>"
                 height="<?php echo esc_attr($zone['height']); ?>"
                 loading="lazy"
                 class="dm-ads-banner-image">
        </a>
    </div>
</div>
