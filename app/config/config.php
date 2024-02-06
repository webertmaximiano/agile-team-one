<?php
//PSR-12
namespace App\Config;
// Carregue configurações sensíveis de um arquivo separado
require_once 'config_secret.php';
// iniciar o buffer de saída. O buffer de saída permite manipular os cabeçalhos HTTP antes que eles sejam enviados ao navegador.
ob_start();

// Constantes do Sistema
const SYSTEM_NAME = "Ominichanel"; // Nome do sistema
const SYSTEM_VERSION = "1.0.0"; // Versão do sistema
const ENVIRONMENT = "TEST"; // Ambiente de execução (TEST ou PROD)
const CAMINHO_BASE = "/var/www/ominichanel"; // Caminho base no servidor para os arquivos do sistema
const APP_URL = 'https://ominichanel.dev';
const HTTP_PROTOCOL = "https://";
const SIMPLE_URL = "ominichanel.dev";
const HOME_PAGE = "conheca.ominichanel.dev";
const GOWWW = HTTP_PROTOCOL . SIMPLE_URL; //monta url com as 2 constantes.


//variaveis

$suport_url = HTTP_PROTOCOL . HOME_PAGE . "/#contato";
$system_url = HTTP_PROTOCOL . SIMPLE_URL ."/administracao";
$panel_url  = HTTP_PROTOCOL . SIMPLE_URL . "/painel";
$admin_url  = HTTP_PROTOCOL . SIMPLE_URL . "/administracao";
$just_url   = HTTP_PROTOCOL . SIMPLE_URL;
$app_url    = HTTP_PROTOCOL . SIMPLE_URL . "/app";

// Title
$seo_title = "Estou On";
$seo_description = "Compre sem sair de casa!";

//$titulo_topo = "Sua<strong> Logo.</strong>"; //TITULO DA LOGO PARA USAR TITULO INVES DE IMAGEM TIRAR OS // DO COMEÇO E COLOCAR NO DE BAIXO 
$titulo_topo = '<img src="/_core/_cdn/img/logo.png">'; //US4R LOGO INVES DE TITUL5
$titulo_rodape ="Estou On";
$sub_titulo_rodape ="O SISTEMA DE VENDAS DESCOMPLICADO!"; //Endereço ou Slogan
$titulo_rodape_marketplace ="EstouOn, Compre sem sair de casa!"; //Endereço ou Slogan

// Redes/Whatsapp/Email
$whatsapp = "21981760591";
$usrtelefone = "21981760591";
$email ="sac@redewe2m.com.br";
$youtube ="#";
$instagram="https://www.instagram.com/estouon.app/";
$facebook ="https://www.facebook.com/redewe2m";

// Root path
$rootpath = $_SERVER["DOCUMENT_ROOT"];

//forca uso do https pode ser removido e configurado direto no .htaccess
if( !$_SERVER['HTTPS'] ) {
	$fixprotocol = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header("Location: ".$fixprotocol);
}

// Manuntenção do Sistema
$manutencao = false;

if( $manutencao ) {
	include("manutencao.php");
	die;
}

//modo teste
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

//Pega as informacoes do Banco de Dados 
$config_banco = (ENVIRONMENT === "TEST") ? \App\Config\get_test_database_config() : \App\Config\get_database_config();
$db_host = $config_banco['host'];
$db_user = $config_banco['user'];
$db_pass = $config_banco['password'];
$db_name = $config_banco['name'];

// SMTP movido para config secrets
$config_smtp = \App\Config\get_smtp_config();
$smtp_name = $config_smtp['smtp_name'];
$smtp_user = $config_smtp['smtp_user'];
$smtp_pass = $config_smtp['smtp_pass'];

// Includes da funcoes separar para carregar so o necessario
include("_core/_includes/functions.php");

// Plano default descobrir qual e o 5
$plano_default = "5";

// Images
$image_max_width = 1000;
$image_max_height = 1000;
$gallery_max_files  = 10;

// Global header and footer
$system_header = "";
$system_footer = "";

// Keep Alive (com correções e comentários)
if (isset($_SESSION['user']) && isset($_SESSION['user']['logged'])) { // Verifica se a sessão do usuário existe
    if ($_SESSION['user']['logged'] == "1" && mb_strlen($_SESSION['user']['keepalive']) >= 10 && $_SESSION['user']['keepalive'] != $_COOKIE['keepalive']) {
        // Desativa o cookie keepalive se houver inconsistência
        setcookie('keepalive', "kill", time() - 3600);

        // Redefine o cookie keepalive se a sessão for válida
        if (mb_strlen($_SESSION['user']['keepalive']) >= 10) {
            setcookie('keepalive', $_SESSION['user']['keepalive'], (time() + (120 * 24 * 3600)));
        }
    }
} else {
    // Se a sessão do usuário não existir, defina valores padrão
    $_SESSION['user'] = ['logged' => 0, 'keepalive' => ''];
}

if (isset($_SESSION['user']) && isset($_SESSION['user']['keepalive'])) {
    $keepalive = isset($_COOKIE['keepalive']);  // <-- Aqui estava o erro

    if ($_SESSION['user']['logged'] != "1" && strlen($keepalive) >= 10) {
        make_login($keepalive, "", "keepalive", "2");
    }
}
// Fecha o buffer de saída e envia o conteúdo ao navegador
ob_end_flush();