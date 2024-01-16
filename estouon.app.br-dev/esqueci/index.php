<?php
// CORE
include('../_core/_includes/config.php');
// SEO
$seo_subtitle = "Esqueci minha senha";
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

$email = mysqli_real_escape_string( $db_con, $_POST['email'] );

// Checar se formulário foi executado

$formdata = $_POST['formdata'];

if( $formdata ) {

  $key = random_key(120);
  $title = "Recuperação de senha";
  $msg = "";
  $msg .= "Você solicitou uma alteração de senha, para confirma-lá clique no link abaixo e altere a sua senha:<br/><br/>";
  $msg .= "<a style='color: ##004f2a;' href='".get_just_url()."/novasenha/?key=".$key."'>".get_just_url()."/novasenha/?key=$key</a><br/><br/>";
  $msg .= "Atenciosamente!";
  
  include("../_core/_email/default.php");

  $msg = $fullmsg;

  if( recover_password_generate( $email,$key,$msg ) ) {

    header("Location: index.php?msg=enviada");

  } else {

    header("Location: index.php?msg=erro");

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

		<link rel="stylesheet" href="<?php just_url(); ?>/_core/_cdn/sidr/css/jquery.sidr.light.css">

		<?php system_header(); ?>
	</head>
	<body>

		<div class="bg-gray fullfit">

			<div class="container">

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
															<img src="<?php just_url(); ?>/_core/_cdn/img/logo.png"/>
														</div>

													</div>

												</div>

												<?php if( !$_GET['msg'] ) { ?>

												<div class="row">

													<div class="col-md-12">

														<div class="msg bg-gray mt-10">
															<span>
															Digite seu e-mail de cadastro abaixo<br class="hidden-xs hidden-sm"/>
															para recuperar seu acesso!
															</span>
														</div>

													</div>

												</div>

												<?php } ?>


												<?php if( $_GET['msg'] == "enviada" ) { ?>

												<div class="row">

													<div class="col-md-12">

														<div class="msg msg-done bg-gray mt-10">
															<i class="lni lni-checkmark"></i>
															<span>
															Enviamos um link para recuperar sua senha.<br class="hidden-xs hidden-sm"/>
															Verifique seu email na caixa de entrada ou span!
															</span>
														</div>

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

													</div>

												</div>

												<?php } ?>

												<div class="row">

													<div class="col-md-12">

														<div class="form-field form-field-icon form-field-text">

															<i class="form-icon lni lni-user"></i>
															<input type="text" name="email" placeholder="E-mail" value="<?php echo htmlclean( $_POST['email'] ); ?>"/>

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
																<span>Recuperar</span>
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

			</div>

		</div>

	</body>

</html>

<script src="<?php just_url(); ?>/_core/_cdn/jquery/js/jquery.min.js"></script>
<script src="<?php just_url(); ?>/_core/_cdn/panel/js/template.js"></script>
<script src="<?php just_url(); ?>/_core/_cdn/validate/js/jquery.validate.js"></script>

<script>

$(document).ready( function() {
          
  // Globais

  $("#the_form").validate({

      /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

      rules:{

        email:{
        required: true,
        minlength: 5,
        maxlength: 100,
        email: true,
          remote: "<?php just_url(); ?>/_core/_ajax/check_email.php?metodo=reverse"
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        email:{
          email: "Por favor digite um e-mail!",
          remote: "E-mail não encontrado no sistema!",
          required: "Este campo é obrigatório!",
          minlength: "Mínimo de 5 caracteres",
          maxlength: "Maximo de 40 caracteres"
        }

      }

    });

  });

</script>
