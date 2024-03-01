<?php
global $insubdominioid;
$id = $insubdominioid;
global $simple_url;
include("../../../_core/_includes/config.php");
$define_query = mysqli_query( $db_con, "SELECT nome,perfil,subdominio,cor FROM estabelecimentos WHERE id = '$id' LIMIT 1");
$define_data = mysqli_fetch_array( $define_query );
header('Content-Type: application/json');
$url = $define_data['subdominio'].".".$simple_url;
?>
{
  "background_color": "<?php echo $define_data['cor']; ?>",
  "description": "<?php echo $define_data['nome']; ?>",
  "display": "fullscreen",
  "icons": [
    {
      "src": "favicon.png",
      "sizes": "192x192",
      "type": "image/png"
    }
  ],
  "name": "<?php echo $define_data['nome']; ?>",
  "short_name": "<?php echo $define_data['nome']; ?>",
  "start_url": "/"
}
