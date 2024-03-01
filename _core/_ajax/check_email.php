<?php

require_once('../_includes/config.php');

header('Content-type: application/json');

  //connect to database

  $connecDB = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ('could not connect to database');
 
  //received username value from registration page

  $metodo =  mysqli_real_escape_string( $db_con, $_REQUEST["metodo"] );
  $email =  mysqli_real_escape_string( $db_con, $_REQUEST["email"] );

  //check username in db

  $results = mysqli_query($connecDB,"SELECT * FROM users WHERE email = '$email'");
 
  $username_exist = mysqli_num_rows($results); //records count
 
  //if returned value is more than 0, username is not available

  if( $metodo == "reverse" ) {

    if( !$username_exist ) {

        $output = false; // not free

    } else {

        $output = true; // is free

    }

  } else {

    if( $username_exist ) {

        $output = false; // not free

    } else {

        $output = true; // is free

    }

  }

echo json_encode($output);

?>