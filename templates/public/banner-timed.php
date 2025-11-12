<?php
/**
 * Template: Timed Banner
 * Rotates banners automatically every X seconds
 */

if (!defined('ABSPATH')) exit;

$interval = isset($zone['rotation_interval']) ? $zone['rotation_interval'] : 5;
?>

<div class="dm-ads-container dm-ads-timed" 
     style="max-width: <?php echo $zone['width']; ?>px;"
     data-interval="<?php echo esc_attr($interval); ?>"
     data-zone-id="<?php echo esc_attr($zone['slug']); ?>">
    
    <?php foreach ($banners as $banner): ?>
        <div class="dm-ads-slide dm-ads-banner" data-banner-id="<?php echo esc_attr($banner['id']); ?>">
            <a href="<?php echo esc_url($banner['url']); ?>" 
               target="_blank" 
               rel="noopener noreferrer">
                <img src="<?php echo esc_url($banner['image']); ?>" 
                     alt="<?php echo esc_attr($banner['title']); ?>"
                     width="<?php echo $zone['width']; ?>"
                     height="<?php echo $zone['height']; ?>">
            </a>
        </div>
    <?php endforeach; ?>
    
    <?php if (count($banners) > 1): ?>
        <div class="dm-ads-dots">
            <?php foreach ($banners as $index => $banner): ?>
                <span class="dm-ads-dot" data-index="<?php echo $index; ?>"></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
