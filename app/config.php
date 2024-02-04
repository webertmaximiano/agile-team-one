<?php
//PSR-12
namespace App\Config;

// iniciar o buffer de saída. O buffer de saída permite manipular os cabeçalhos HTTP antes que eles sejam enviados ao navegador.
ob_start();

// Carregue configurações sensíveis de um arquivo separado
require_once 'config_secret.php';

// Constantes do Sistema
const SYSTEM_NAME = "Ominichanel"; // Nome do sistema
const SYSTEM_VERSION = "1.0.0"; // Versão do sistema
const ENVIRONMENT = "TEST"; // Ambiente de execução (TEST ou PROD)
const URL_BASE = "https://ominichanel.dev"; // URL base do sistema
const CAMINHO_BASE = "/var/www/ominichanel"; // Caminho base no servidor para os arquivos do sistema
const APP_URL = 'https://ominichanel.dev';

if (ENVIRONMENT === "TEST") {
    // Configurações para ambiente de teste
    error_reporting(E_ALL);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
} else if (ENVIRONMENT === "PROD") {
    // Configurações para ambiente de produção
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
} else {
    throw new InvalidArgumentException("Ambiente inválido: " . ENVIRONMENT);
}

/**
 * Retorna a configuração do banco de dados.
 *
 * @return array
 */
function get_database_config(): array
{
    return [
        'host' => DB_HOST,
        'user' => DB_USER,
        'password' => DB_PASS,
        'name' => DB_NAME,
    ];
}
