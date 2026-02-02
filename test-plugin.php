<?php
/**
 * Teste de carregamento do plugin
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Teste de Carregamento do Plugin ===\n\n";

// Simular ambiente WordPress mínimo
if (!defined('ABSPATH')) {
    define('ABSPATH', '/tmp/');
}
if (!defined('WPINC')) {
    define('WPINC', 'wp-includes');
}

// Definir constantes do plugin
define('DM_ADS_VERSION', '1.1.0');
define('DM_ADS_PLUGIN_FILE', __DIR__ . '/designmaster-ads.php');
define('DM_ADS_PLUGIN_BASENAME', 'designmaster-ads/designmaster-ads.php');
define('DM_ADS_PLUGIN_DIR', __DIR__ . '/');
define('DM_ADS_PLUGIN_URL', 'http://localhost/wp-content/plugins/designmaster-ads/');

echo "1. Constantes definidas ✓\n";

// Testar carregamento de cada classe
$classes = [
    'class-dm-banner.php',
    'class-dm-zone.php',
    'class-dm-tracker.php',
    'class-dm-analytics.php',
    'class-dm-display.php',
    'class-dm-admin.php',
    'class-dm-ads.php'
];

foreach ($classes as $class) {
    $file = __DIR__ . '/includes/' . $class;
    echo "2. Carregando $class... ";
    if (file_exists($file)) {
        require_once $file;
        echo "✓\n";
    } else {
        echo "✗ (não encontrado)\n";
    }
}

echo "\n3. Testando instanciação da classe principal...\n";
try {
    $plugin = new DM_Ads();
    echo "   Classe DM_Ads instanciada ✓\n";
    
    echo "\n4. Testando método run()...\n";
    // Não vamos executar run() pois precisa do WordPress completo
    echo "   Método run() existe: " . (method_exists($plugin, 'run') ? "✓" : "✗") . "\n";
    
} catch (Exception $e) {
    echo "   ✗ ERRO: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n";
}

echo "\n=== Teste Concluído ===\n";
