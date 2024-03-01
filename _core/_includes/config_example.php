<?php

set_time_limit(90);

ob_start();

// ignore_user_abort(0);

// Debug

//error_reporting(0); descomentar em producao
error_reporting(E_ALL | E_STRICT);//comentar em producao

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Time mmmmm

date_default_timezone_set('America/Sao_Paulo');

// Url
$httprotocol = "https://";

if( !$_SERVER['HTTPS'] ) {
	$fixprotocol = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header("Location: ".$fixprotocol);
}

$simple_url = "seu-dominio";

$suport_url = $httprotocol."conheca.seu-dominio/#contato";
$system_url = $httprotocol."seu-dominio/administracao";
$panel_url = $httprotocol."seu-dominio/painel";
$admin_url = $httprotocol."seu-dominio/administracao";
$just_url = $httprotocol."seu-dominio";
$app_url = $httprotocol."seu-dominio/app";
$simple_url = "seu-dominio";

// Title

$seo_title = "Estou On";
$seo_description = "Compre sem sair de casa!";
//$titulo_topo = "Sua<strong> Logo.</strong>"; //TITULO DA LOGO PARA USAR TITULO INVES DE IMAGEM TIRAR OS // DO COMEÇO E COLOCAR NO DE BAIXO 
$titulo_topo = '<img src="/_core/_cdn/img/logo.png">'; //US4R LOGO INVES DE TITUL5
$titulo_rodape ="Estou On";
$sub_titulo_rodape ="O SISTEMA DE VENDAS DESCOMPLICADO!"; //Endereço ou Slogan
$titulo_rodape_marketplace ="EstouOn, Compre sem sair de casa!"; //Endereço ou Slogan

// Redes/Whatsapp/Email troque pelos seus
$whatsapp = "21981760591";
$usrtelefone = "21981760591";
$email ="sac@redewe2m.com.br";
$youtube ="#";
$instagram="https://www.instagram.com/estouon.app/";
$facebook ="https://www.facebook.com/redewe2m";


$db_host = "localhost";
$db_user = "seu_usuario";
$db_pass = "sua_senha";
$db_name = "seu_banco";

// SMTP

$smtp_name = "host";
$smtp_user = "email";
$smtp_pass = "senha";

// Manuntenção

$manutencao = false;

if( $manutencao ) {

	include("manutencao.php");
	die;

}

// Includes

include("functions.php");

// Tokens gere um aleatorio com o mesmo tamanho // implementar uma API
$external_token = "ABCDEcUT9Bt3A1HHAALxKYfylrPMhFFg35IskTG4R7jYw112345";

// MERCADO PAGO

$mp_sandbox = false;

if ($mp_sandbox == true) {
	$mp_public_key = "key";
	$mp_acess_token = "chave";
} else {
	$mp_public_key = "m";
	$mp_acess_token = "m";
	$mp_client_id = "m";
	$mp_client_secret = "m";
}

// Plano default

$plano_default = "5";

// Root path

$rootpath = $_SERVER["DOCUMENT_ROOT"];

// Images

$image_max_width = 1000;
$image_max_height = 1000;
$gallery_max_files  = 10;

// Global header and footer

$system_header = "";
$system_footer = "";


// Keep Alive

if( $_SESSION['user']['logged'] == "1" && mb_strlen( $_SESSION['user']['keepalive'] ) >= 10 && $_SESSION['user']['keepalive'] != $_COOKIE['keepalive'] ) {
	setcookie( 'keepalive', "kill", time() - 3600 );
	if( mb_strlen( $_SESSION['user']['keepalive'] ) >= 10 ) {
		setcookie( 'keepalive', $_SESSION['user']['keepalive'], (time() + (120 * 24 * 3600)) );
	}
}

$keepalive = $_COOKIE['keepalive'];

if( $_SESSION['user']['logged'] != "1" && strlen( $keepalive ) >= 10 ) {

	make_login($keepalive,"","keepalive","2");

}
