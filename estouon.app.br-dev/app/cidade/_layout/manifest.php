<?php
require_once("../../../_core/_includes/config.php");
global $simple_url;
global $httprotocol;
$id = mysqli_real_escape_string( $db_con, $_GET['id'] );
$define_query = mysqli_query( $db_con, "SELECT nome,perfil,subdominio FROM estabelecimentos WHERE id = '$id' LIMIT 1");
$define_data = mysqli_fetch_array( $define_query );
$url = $httprotocol.$define_data['subdominio'].".".$simple_url;
header('Content-Type: application/json');
?>
{
  "short_name": "<?php echo $define_data['nome']; ?>",
  "name": "<?php echo $define_data['nome']; ?>",
  "icons": [
    {
      "src":"<?php echo thumber( $define_data['perfil'], 300 ); ?>",
      "sizes": "300x300",
      "type": "image/png"
    }
  ],
  "start_url": "<?php echo $url; ?>",
  "background_color": "#fff",
  "Theme_color": "#333",
  "display": "standalone"
}