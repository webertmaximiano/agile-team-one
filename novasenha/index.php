<?php
// CORE
include('../_core/_includes/config.php');
// SEO
$seo_subtitle = "Redefinição de senha";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header = "";

if( $_SESSION['user']['logged'] == "1" ) {

	if( $_SESSION['user']['level'] == "1" ) {
		header("Location: ../administracao/inicio");
	}

	if( $_SESSION['user']['level'] == "2" ) {
		header("Location: ../painel/inicio");
	}

}

$key = mysqli_real_escape_string( $db_con, $_GET['key'] );
$pass = mysqli_real_escape_string( $db_con, $_POST['pass'] );
$repass = mysqli_real_escape_string( $db_con, $_POST['repass'] );

// Checar se formulário foi executado

$formdata = $_POST['formdata'];

if( $formdata && notnull( $pass ) && notnull( $repass ) && $pass == $repass ) {

  if( recover_password_save( $key,$pass ) ) {

    header("Location: ../login/index.php?msg=alterada");

  } else {

    header("Location: index.php?msg=erro&key=".$key);

  }

}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php seo( "title" ); ?></title>
		<meta name="description" content="<?php seo( "description" ); ?>">
		<meta name="keywords" content="<?php seo( "keywords" ); ?>">
		<link rel="shortcut icon" href="<?php just_url(); ?>/_core/_cdn/img/favicon.png"/>
		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/panel/css/class.css">
		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/panel/css/forms.css">
		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/panel/css/typography.css">
		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/panel/css/template.css">
		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/panel/css/theme.css">
		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/panel/css/default.css">
		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/lineicons/css/LineIcons.css">
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

		<?php system_header(); ?>
	</head>
	<body>

		<div class="bg-gray fullfit">

			<div class="container">

                <?php
                $sql = "SELECT * FROM users WHERE (recover_key = '$key') LIMIT 1";
                $query = mysqli_query( $db_con,$sql );
                if ( mysqli_num_rows( $query ) == 1 ) {
                $coluna = mysqli_fetch_array( $db_con,$query );
                ?>

				<div class="row">

					<div class="col-md-12">

						<div class="login">

							<form id="the_form" method="POST">

								<div class="container">

									<div class="row align-center">

										<!-- Login Box -->

											<div class="login-box login-box-nova box-white">

												<div class="row">

													<div class="col-md-12">

														<div class="brand brand-login text-center">
															<img src="<?php just_url(); ?>/_core/_cdn/img/logo.png"/>
														</div>

													</div>

												</div>

												<?php if( !$_GET['msg'] ) { ?>

												<div class="row">

													<div class="col-md-12">

														<div class="msg bg-gray mt-10">
															<span>Digite sua nova senha</span>
														</div>
														<br/>

													</div>

												</div>

												<?php } ?>

												<?php if( $_GET['msg'] == "erro" ) { ?>

												<div class="row">

													<div class="col-md-12">

														<div class="msg msg-error bg-gray mt-10">
															<i class="lni lni-close"></i>
															<span>Erro, tente novamente!</span>
														</div>
														<br/>

													</div>

												</div>

												<?php } ?>

								                <div class="row">

								                  <div class="col-md-6">

								                    <div class="form-field-default">

								                        <label>Senha</label>
								                        <input type="password" name="pass" placeholder="******">

								                    </div>

								                  </div>

								                  <div class="col-md-6">

								                    <div class="form-field-default">

								                        <label>Redigitar senha</label>
								                        <input type="password" name="repass" placeholder="******">

								                    </div>

								                  </div>

								                </div>

												<div class="row">

													<div class="col-md-6 col-sm-6 col-xs-6">

														<div class="form-field form-field-icon form-field-submit">
															<a href="<?php just_url(); ?>/login" class="backbutton">
																<i class="lni lni-chevron-left"></i>
																<span>Voltar</span>
															</a>
														</div>

													</div>

													<div class="col-md-6 col-sm-6 col-xs-6">

														<div class="form-field form-field-icon form-field-submit">
															<input type="hidden" name="formdata" value="1"/>
															<button>
																<span>Alterar</span>
																<i class="lni lni-chevron-right"></i>
															</button>
														</div>

													</div>

												</div>

											</div>

										<!-- / Login Box -->

									</div>

								</div>

							</form>

						</div>

					</div>

				</div>

                <?php } else { ?>

				<div class="row">

					<div class="col-md-12">

						<div class="login">                	

							<form id="the_form" method="POST" action="<?php just_url(); ?>/esqueci/index.php">

								<div class="container">

									<div class="row align-center">

										<!-- Login Box -->

											<div class="login-box box-white">

												<div class="row">

													<div class="col-md-12">

														<div class="brand brand-login text-center">
															<img src="<?php just_url(); ?>/_core/_cdn/panel/img/logo.png"/>
														</div>

													</div>

												</div>

												<div class="row">

													<div class="col-md-12">
														<br/>
														<div class="msg msg-error bg-gray mt-10">
															<i class="lni lni-close"></i>
															<span>Chave de recuperação inválida ou não encontrada!</span>
														</div>

													</div>

												</div>

											</div>

										<!-- / Login Box -->

									</div>

								</div>

							</form>

                 		</div>

                 	</div>

                 </div>

                <?php } ?>

			</div>

		</div>

	</body>

</html>

<script src="<?php just_url(); ?>/_core/_cdn/jquery/js/jquery.min.js"></script>
<script src="<?php just_url(); ?>/_core/_cdn/validate/js/jquery.validate.js"></script>

<script>

$(document).ready( function() {
          
  // Globais

  $("#the_form").validate({

      /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

      rules:{

        pass:{
        required: true,
        minlength: 6,
        maxlength: 40
        },

        repass:{
        required: true,
        minlength: 6,
        maxlength: 40,
        equalTo: "input[name=pass]"
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        pass:{
          required: "Este campo é obrigatório!",
          minlength: "Mínimo 6 caracteres",
          maxlength: "Maximo 40 caracteres"
        },

        repass:{
          required: "Este campo é obrigatório!",
          minlength: "Mínimo 6 caracteres",
          maxlength: "Maximo 40 caracteres",
          equalTo: "Senhas não coincidem!"
        }

      }

    });

  });

</script>