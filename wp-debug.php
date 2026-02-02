<?php
/**
 * Debug WordPress Plugin Activation
 * Coloque este arquivo na pasta wp-content/plugins/designmaster-ads/
 * e acesse: http://seusite.com/wp-content/plugins/designmaster-ads/wp-debug.php
 */

// Ativar debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

echo "<h1>Debug do Plugin DesignMaster Ads</h1>";
echo "<pre>";

// Carregar WordPress
echo "1. Carregando WordPress...\n";
$wp_load = dirname(dirname(dirname(__DIR__))) . '/wp-load.php';
if (file_exists($wp_load)) {
    require_once($wp_load);
    echo "   ✓ WordPress carregado\n\n";
} else {
    die("   ✗ wp-load.php não encontrado em: $wp_load\n");
}

echo "2. Verificando constantes do plugin...\n";
$constants = ['DM_ADS_VERSION', 'DM_ADS_PLUGIN_FILE', 'DM_ADS_PLUGIN_DIR', 'DM_ADS_PLUGIN_URL'];
foreach ($constants as $const) {
    if (defined($const)) {
        echo "   ✓ $const = " . constant($const) . "\n";
    } else {
        echo "   ✗ $const não definida\n";
    }
}

echo "\n3. Verificando arquivos do plugin...\n";
$files = [
    'designmaster-ads.php',
    'includes/class-dm-ads.php',
    'includes/class-dm-ads-activator.php',
    'includes/class-dm-banner.php',
    'includes/class-dm-zone.php',
    'includes/class-dm-analytics.php',
];
foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "   ✓ $file\n";
    } else {
        echo "   ✗ $file FALTANDO\n";
    }
}

echo "\n4. Verificando tabela do banco de dados...\n";
global $wpdb;
$table = $wpdb->prefix . 'dm_ads_stats';
$exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");
if ($exists == $table) {
    echo "   ✓ Tabela $table existe\n";
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
    echo "   - Registros na tabela: $count\n";
} else {
    echo "   ✗ Tabela $table NÃO existe\n";
}

echo "\n5. Tentando carregar as classes...\n";
try {
    if (!class_exists('DM_Ads')) {
        require_once __DIR__ . '/includes/class-dm-ads.php';
    }
    echo "   ✓ Classe DM_Ads carregada\n";
    
    $plugin = new DM_Ads();
    echo "   ✓ Instância DM_Ads criada\n";
    
    echo "\n6. Verificando métodos da classe...\n";
    $methods = ['run', 'get_plugin_name', 'get_version'];
    foreach ($methods as $method) {
        if (method_exists($plugin, $method)) {
            echo "   ✓ Método $method() existe\n";
        } else {
            echo "   ✗ Método $method() NÃO existe\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ✗ ERRO: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
} catch (Error $e) {
    echo "   ✗ ERRO FATAL: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n7. Verificando Custom Post Type 'dm_banner'...\n";
if (post_type_exists('dm_banner')) {
    echo "   ✓ Custom Post Type 'dm_banner' registrado\n";
} else {
    echo "   ✗ Custom Post Type 'dm_banner' NÃO registrado\n";
}

echo "\n8. Verificando opções do plugin...\n";
$options = [
    'dm_ads_version',
    'dm_ads_track_views',
    'dm_ads_track_clicks',
    'dm_ads_anonymize_ip',
];
foreach ($options as $option) {
    $value = get_option($option);
    if ($value !== false) {
        echo "   ✓ $option = " . var_export($value, true) . "\n";
    } else {
        echo "   - $option não definida\n";
    }
}

echo "\n9. Log de erros PHP (últimas 20 linhas):\n";
$error_log = __DIR__ . '/error_log.txt';
if (file_exists($error_log)) {
    $lines = file($error_log);
    $last_lines = array_slice($lines, -20);
    foreach ($last_lines as $line) {
        echo "   " . htmlspecialchars($line);
    }
} else {
    echo "   - Nenhum erro registrado\n";
}

echo "\n=== Debug Concluído ===\n";
echo "</pre>";
