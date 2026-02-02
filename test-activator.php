<?php
/**
 * Debug do Activator
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Debug do Activator ===\n\n";

// Definir constantes
define('DM_ADS_VERSION', '1.1.0');
define('ABSPATH', '/tmp/');

// Mock de funções WordPress necessárias
function flush_rewrite_rules() {
    echo "   - flush_rewrite_rules() chamado\n";
}

function add_option($key, $value) {
    echo "   - add_option('$key', " . var_export($value, true) . ")\n";
    return true;
}

function get_option($key) {
    return false; // Simula que não existe
}

// Mock do $wpdb
class Mock_WPDB {
    public $prefix = 'wp_';
    
    public function get_charset_collate() {
        return 'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
    }
    
    public function get_var($query) {
        echo "   - SQL: " . substr($query, 0, 50) . "...\n";
        return 'wp_dm_ads_stats'; // Simula que tabela existe
    }
}

$wpdb = new Mock_WPDB();

function dbDelta($sql) {
    echo "   - dbDelta() chamado para criar tabela\n";
    echo "     SQL: " . substr($sql, 0, 100) . "...\n";
}

echo "1. Carregando class-dm-ads-activator.php...\n";
require_once __DIR__ . '/includes/class-dm-ads-activator.php';
echo "   ✓ Carregado\n\n";

echo "2. Executando DM_Ads_Activator::activate()...\n";
try {
    DM_Ads_Activator::activate();
    echo "   ✓ Ativação concluída sem erros\n";
} catch (Exception $e) {
    echo "   ✗ ERRO: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n";
    echo "\n   Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "   ✗ ERRO FATAL: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n";
}

echo "\n=== Teste Concluído ===\n";
