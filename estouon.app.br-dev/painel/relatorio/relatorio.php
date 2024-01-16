<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
restrict_expirado();
// SEO
$seo_subtitle = "Editar pedido";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../../_layout/head.php');
?>

<?php

$eid = mysqli_real_escape_string( $db_con, $_GET['estabelecimento_id'] );

$data_inicial = mysqli_real_escape_string( $db_con, $_GET['data_inicial'] );
if( !$data_inicial ) { $data_inicial = date("d/m/").(date(Y)-1); }
$data_inicial_sql = datausa_min( $data_inicial );
$data_inicial_sql = $data_inicial_sql." 00:00:00";

$data_final = mysqli_real_escape_string( $db_con, $_GET['data_final'] );
if( !$data_final ) { $data_final = date("d/m/Y"); }
$data_final_sql = datausa_min( $data_final );
$data_final_sql = $data_final_sql." 23:59:59";


// FINALIZADOS

$sql1 = mysqli_query( $db_con, "SELECT SUM(v_pedido) AS soma1 FROM pedidos WHERE 1=1 AND status = '2' AND (data_hora > '$data_inicial_sql' AND data_hora < '$data_final_sql') AND rel_estabelecimentos_id = '$eid'" );
$define_data1 = mysqli_fetch_array( $sql1 );

$sql1x = mysqli_query( $db_con, "SELECT id FROM pedidos WHERE 1=1 AND status = '2' AND (data_hora > '$data_inicial_sql' AND data_hora < '$data_final_sql') AND rel_estabelecimentos_id = '$eid'" );
$total_results1 = mysqli_num_rows( $sql1x );

 ?>








 
							
							
<style type="text/css">
<!--
.style4 {font-family: "Courier New", Courier, monospace; font-size: 14px; }
.style5 {font-family: "Courier New", Courier, monospace; font-size: 18px; }
.style7 {font-size: 16px}
-->
</style>

<h4><center><code>RELATÓRIO FINANCEIRO<br/>SINTÉTICO POR DATA</code></center></h4>

<div style="margin-left:10px;">
    
<p><code>De: <?php print $data_inicial; ?></code><br/>
<code>Até: <?php print $data_final; ?></code></p>
<hr />
<p>
  <code><b>(<?php print $total_results1; ?>) - Pedidos Finalizados:</b></code><br />
  <code>R$: <?php echo number_format($define_data1['soma1'], 2, ',', '.');?></code>
</p>

<?php

// CANCELADOS

$sql2 = mysqli_query( $db_con, "SELECT SUM(v_pedido) AS soma2 FROM pedidos WHERE 1=1 AND status = '3' AND (data_hora > '$data_inicial_sql' AND data_hora < '$data_final_sql') AND rel_estabelecimentos_id = '$eid'" );
$define_data2 = mysqli_fetch_array( $sql2 );

$sql2x = mysqli_query( $db_con, "SELECT id FROM pedidos WHERE 1=1 AND status = '3' AND (data_hora > '$data_inicial_sql' AND data_hora < '$data_final_sql') AND rel_estabelecimentos_id = '$eid'" );
$total_results2 = mysqli_num_rows( $sql2x );

?>



<p>
  <code><b>(<?php print $total_results2; ?>) - Pedidos Cancelados:</b></code><br />
  <code>R$: <?php echo number_format($define_data2['soma2'], 2, ',', '.');?></code>
</p>

<?php
// NAO FINALIZADOS
$sql3 = mysqli_query( $db_con, "SELECT SUM(v_pedido) AS soma3 FROM pedidos WHERE 1=1 AND (status = '1' OR status = '4' OR status = '5' OR status = '6') AND (data_hora > '$data_inicial_sql' AND data_hora < '$data_final_sql') AND rel_estabelecimentos_id = '$eid'" );
$define_data3 = mysqli_fetch_array( $sql3 );

$sql3x = mysqli_query( $db_con, "SELECT id FROM pedidos WHERE 1=1 AND (status = '1' OR status = '4' OR status = '5' OR status = '6') AND (data_hora > '$data_inicial_sql' AND data_hora < '$data_final_sql') AND rel_estabelecimentos_id = '$eid'" );
$total_results3 = mysqli_num_rows( $sql3x );
?>
<p class="style4">
  <code><b>(<?php print $total_results3; ?>) - Pedidos não Finalizados:</b></code><br />
  <code>R$: <?php echo number_format($define_data3['soma3'], 2, ',', '.');?></code>
</p>

<?php
// TOTAL GERAL
$sql4 = mysqli_query( $db_con, "SELECT SUM(v_pedido) AS soma4 FROM pedidos WHERE 1=1 AND (data_hora > '$data_inicial_sql' AND data_hora < '$data_final_sql') AND rel_estabelecimentos_id = '$eid'" );
$define_data4 = mysqli_fetch_array( $sql4 );

$sql4x = mysqli_query( $db_con, "SELECT id FROM pedidos WHERE 1=1 AND (data_hora > '$data_inicial_sql' AND data_hora < '$data_final_sql') AND rel_estabelecimentos_id = '$eid'" );
$total_results4 = mysqli_num_rows( $sql4x );
?>

<p class="style4">
    <code><b>Total de Pedidos:</b></code><br /> 
    <code><?php print $total_results4; ?></code>
</p>


<p class="style4">
    <code><b>Total Geral do Período:</b></code><br />
    <code>R$: <?php echo number_format($define_data4['soma4'], 2, ',', '.');?></code>
</p>
						

</div>




















 

 

<script>

  window.print();

</script>