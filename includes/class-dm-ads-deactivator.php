<?php
/**
 * Fired during plugin deactivation
 */
class DM_Ads_Deactivator {
    
    /**
     * Deactivation process
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Note: We don't delete data on deactivation
        // Data will only be deleted on uninstall
    }
}
