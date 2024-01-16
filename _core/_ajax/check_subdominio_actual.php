<?php
require_once('../_includes/config.php');
header('Content-type: application/json');

  $connecDB = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ('could not connect to database');
 
  $metodo =  mysqli_real_escape_string( $db_con, $_REQUEST["metodo"] );
  $subdominio =  mysqli_real_escape_string( $db_con, $_REQUEST["subdominio"] );
  $actual = mysqli_real_escape_string( $db_con, $_REQUEST['actual'] );

  $has_subdominio = 0;

  // Subdominios
  $subdominios = mysqli_query($connecDB,"SELECT * FROM subdominios WHERE subdominio = '$subdominio'");
  $has_subdominios = mysqli_num_rows($subdominios);
  if( $has_subdominios ) {
    $has_subdominio++;
  }
  // Cidades
  $cidades = mysqli_query($connecDB,"SELECT * FROM cidades WHERE subdominio = '$subdominio'");
  $has_cidades = mysqli_num_rows($cidades);
  if( $has_cidades ) {
    $has_subdominio++;
  }
  // Estabelecimentos
  $estabelecimentos = mysqli_query($connecDB,"SELECT * FROM estabelecimentos WHERE subdominio = '$subdominio'");
  $has_estabelecimentos = mysqli_num_rows($estabelecimentos);
  if( $has_estabelecimentos ) {
    $has_subdominio++;
  }

  if( $has_subdominio ) {
    $output = false;
  } else {
    $output = true;
  }

  if( $actual && $subdominio == $actual ) {
    $output = true;
  }

  echo json_encode( $output );

?>