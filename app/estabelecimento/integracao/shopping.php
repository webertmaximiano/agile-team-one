<?php
global $virtualpath;
header("Content-type: text/xml");
include('../../../_core/_includes/config.php'); 
include($virtualpath.'/_layout/define.php');
function xmlsafe($s,$intoQuotes=1) {
  if ($intoQuotes) {
       $thereturn = str_replace(array('&','>','<','"'), array('&amp;','&gt;','&lt;','&quot;'), $s);
  } else {
       $thereturn = str_replace(array('&','>','<'), array('&amp;','&gt;','&lt;'), html_entity_decode($s));
  }
  if( !$thereturn ) {
    $thereturn = "Confira no nosso catálogo!";
  }
  return $thereturn;
}
$app_id = $app['id'];
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">
  <title><?php echo $app['title']; ?></title>
  <link rel="self" href="<?php echo $app['url']; ?>"/>

  <?php
  $query = "";
  $query .= "SELECT * FROM produtos ";
  $query .= "WHERE 1=1 ";
  $query .= "AND status = '1' AND visible = '1' AND integrado = '1' ";
  $query .= "AND rel_estabelecimentos_id = '$app_id' ";
  $query .= "ORDER BY last_modified DESC";
  $sql = mysqli_query( $db_con, $query );

  while ( $data_produtos = mysqli_fetch_array( $sql ) ) {
  // Seta valor
  if( $data_produtos['oferta'] == "1" ) {
    $valor_final = $data_produtos['valor_promocional'];
  } else {
    $valor_final = $data_produtos['valor'];
  }
  
  ?>

    <entry>
      <g:id><?php echo xmlsafe( $data_produtos['id'] ); ?></g:id>
      <g:title><?php echo xmlsafe( $data_produtos['nome'] ); ?></g:title>
      <g:description><?php echo xmlsafe( $data_produtos['descricao'] ); ?></g:description>
      <g:link><?php echo $app['url']; ?>/produto/<?php echo $data_produtos['id']; ?></g:link>
      <g:image_link><?php echo $app['url']; ?>/_core/_uploads/<?php echo $data_produtos['destaque']; ?></g:image_link>
      <g:condition>new</g:condition>
      <g:availability>in stock</g:availability>
      <g:brand><?php echo xmlsafe( $app['title'] ); ?></g:brand>
      <g:gtin>1234567890123</g:gtin>
      <g:identifier_exists>true</g:identifier_exists>
      <g:price><?php echo dinheiro( $data_produtos['valor_promocional'], "BR" ); ?> BRL</g:price>
      <g:google_product_category>5863</g:google_product_category> 
    </entry>

  <?php } ?>

</feed>
