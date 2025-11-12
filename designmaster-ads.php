<?php
/**
 * Plugin Name: DesignMaster Ads
 * Plugin URI: https://github.com/yourusername/designmaster-ads
 * Description: Sistema completo de gestão de banners com analytics avançado, gráficos visuais, lazy loading, rotação inteligente e estatísticas detalhadas
 * Version: 1.1.0
 * Author: Alan de Bortolo
 * Author URI: https://alandebortolo.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: designmaster-ads
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Plugin version
define('DM_ADS_VERSION', '1.1.0');

// Plugin root file
define('DM_ADS_PLUGIN_FILE', __FILE__);

// Plugin base name
define('DM_ADS_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Plugin directory path
define('DM_ADS_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Plugin directory URL
define('DM_ADS_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_designmaster_ads() {
    require_once DM_ADS_PLUGIN_DIR . 'includes/class-dm-ads-activator.php';
    DM_Ads_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_designmaster_ads() {
    require_once DM_ADS_PLUGIN_DIR . 'includes/class-dm-ads-deactivator.php';
    DM_Ads_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_designmaster_ads');
register_deactivation_hook(__FILE__, 'deactivate_designmaster_ads');

/**
 * The core plugin class
 */
require DM_ADS_PLUGIN_DIR . 'includes/class-dm-ads.php';

/**
 * Begins execution of the plugin.
 */
function run_designmaster_ads() {
    $plugin = new DM_Ads();
    $plugin->run();
}

run_designmaster_ads();
