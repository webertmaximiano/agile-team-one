<?php
// CORE
include('../../../_core/_includes/config.php');
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

  // Globals

  global $numeric_data;
  global $gallery_max_files;
  $eid = $_SESSION['estabelecimento']['id'];
  $id = mysqli_real_escape_string( $db_con, $_GET['id'] );
  $edit = mysqli_query( $db_con, "SELECT * FROM pedidos WHERE id = '$id' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $status = mysqli_real_escape_string( $db_con, $_POST['status'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // -- Statis

      if( !$status ) {
        $checkerrors++;
        $errormessage[] = "O status não pode ser nulo";
      }

      // -- Estabelecimento

      if( $data['rel_estabelecimentos_id'] != $eid ) {
        $checkerrors++;
        $errormessage[] = "Ação inválida";
      }

    // Executar registro

    if( !$checkerrors ) {

      if( edit_pedido( $id,$status ) ) {

        header("Location: index.php?msg=sucesso&id=".$id);

      } else {

        header("Location: index.php?msg=erro&id=".$id);

      }

    }

  }
  
?>

<div class="comprovante comprovante-print">
  <div class="content">
    <?php echo nl2br( bbzap( $data['comprovante'] ) ); ?>
  </div>
</div>

<?php 
// FOOTER
$system_footer .= "";
include('../../_layout/footer.php');
?>

<script>

  window.print();

</script>