<?php
global $insubdominioid;
global $insubdominiourl;
global $httprotocol;
global $db_con;
global $simple_url;

$define_query = mysqli_query( $db_con, "SELECT * FROM cidades WHERE id = '$insubdominioid' LIMIT 1");
$define_data = mysqli_fetch_array( $define_query );

$app['url'] = $httprotocol.$insubdominiourl.".".$simple_url;

$app['type'] = "2";
$app['id'] = $define_data['id'];

$app['title'] = htmlclean( $define_data['nome'] );
$app['uf'] = htmlclean( ucfirst( strtolower( data_info( "estados", $define_data['estado'], "uf" ) ) ) );
if( strlen( $app['title'] ) < 10 ) {
	$app['cidade_message'] = "Você está em ";
} else {
	$app['cidade_message'] = "";
}
$app['estado'] = htmlclean( data_info( "estados", $define_data['estado'], "nome" ) );
$app['avatar'] = $app['url']."/_core/_cdn/img/favicon.png";
$app['logo'] = $app['url']."/_core/_cdn/img/logo.png";

$app['contato_whatsapp'] = "https://api.whatsapp.com/send?phone=55$whatsapp&text=Como%20posso%20te%20ajudar%20%3B%20";
$app['contato_email'] = "$email";
$app['contato_facebook'] = "$facebook";
$app['contato_instagram'] = "$instagram";
$app['contato_youtube'] = "$youtube";
$app['endereco_completo'] = "$titulo_rodape_marketplace";

$app['categoria'] = mysqli_real_escape_string( $db_con, $_GET['categoria'] );

setcookie( 'cidade', $app['id'], (time() + (120 * 24 * 3600)), "/", ".".$simple_url);

?>