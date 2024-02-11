<?php

// Carregue configurações sensíveis de um arquivo separado
require_once __DIR__ . '/../app/config/config_secret.php';
require_once __DIR__ . '/../app/config/config.php';
use App\Config\ENVIRONMENT;

$config_banco = (\App\Config\ENVIRONMENT === "TEST") ? \App\Config\get_test_database_config() : \App\Config\get_database_config();
//$config_banco = \App\Config\get_database_config(); //recebe um array com as variaveis 1 consulta
echo($config_banco['host']);
//phpinfo();