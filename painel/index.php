<?php include('../_core/_includes/config.php'); ?>

<?php

  if( user_info("logged") == "1" ) {

    header("Location: inicio/");
  
  } else {

    header("Location: ../login/");

  }
  
?>