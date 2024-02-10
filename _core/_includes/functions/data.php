<?php

	function html_mail($to,$subject,$msg) {

		global $smtp_name;
		global $smtp_user;
		global $smtp_pass;

		// Parametros

		$destinatarios = $to;
		$nomeDestinatario = $smtp_name;
		$usuario = $smtp_user;
		$senha = $smtp_pass;
		$nomeRemetente = $smtp_user;

		/*********************************** A PARTIR DAQUI NAO ALTERAR ************************************/

		$To = $destinatarios;
		$Subject = $subject;
		$Message = $msg;

		$Host = 'mail.'.substr(strstr($usuario, '@'), 1);
		// $Host = 'smtp.titan.email';
		$Username = $usuario;
		$Password = $senha;
		$Port = "587";

		$mail = new PHPMailer();
		$body = $Message;
		// $mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host = $Host; // SMTP server
		$mail->SMTPDebug = 3; // enables SMTP debug information (for testing)
		// 1 = errors and messages
		// 2 = messages only
		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->Port = $Port; // set the SMTP port for the service server
		$mail->Username = $Username; // account username
		$mail->Password = $Password; // account password
		$mail->CharSet = 'UTF-8';
		$mail->Timeout = 60;
    	$mail->IsHTML(true);

		$mail->SetFrom($usuario, $nomeDestinatario);
		$mail->Subject = $Subject;
		$mail->MsgHTML($body);
		$mail->AddAddress($To, "");

		/* Enviar o email */

		if( $mail->Send() ) {

			return true;

		} else {

			return false;

		}

	}

	function template_mail($to,$subject,$msg) {

		if( html_mail( $to,$subject,$msg ) ) {

			return true;

		} else {

			return false;

		}

	}

	// Numeric Data

		// tipo

		$numeric_data['usuario_nivel'][] = array( "value" => "1", "name" => "Administrador" );
		$numeric_data['usuario_nivel'][] = array( "value" => "2", "name" => "Estabelecimento" );

		// Status

		$numeric_data['status'][] = array( "value" => "1", "name" => "Ativo" );
		$numeric_data['status'][] = array( "value" => "2", "name" => "Inativo" );

		// Status pedido

		$numeric_data['status_pedido'][] = array( "value" => "1", "name" => "Pendente" );
		$numeric_data['status_pedido'][] = array( "value" => "4", "name" => "Aceito" );
		$numeric_data['status_pedido'][] = array( "value" => "7", "name" => "Aceito/Impresso" );
		$numeric_data['status_pedido'][] = array( "value" => "5", "name" => "Saiu para Entrega" );
		$numeric_data['status_pedido'][] = array( "value" => "6", "name" => "Disponível para Retirada" );
		$numeric_data['status_pedido'][] = array( "value" => "2", "name" => "Concluído" );
		$numeric_data['status_pedido'][] = array( "value" => "3", "name" => "Cancelado" );
		
		// Tipo de documento

		$numeric_data['documento_tipo'][] = array( "value" => "1", "name" => "CPF" );
		$numeric_data['documento_tipo'][] = array( "value" => "2", "name" => "CNPJ" );

		// Subdominio_tipo

		$numeric_data['subdominio_tipo'][] = array( "value" => "1", "name" => "Estabelecimento" );
		$numeric_data['subdominio_tipo'][] = array( "value" => "2", "name" => "Cidade" );
		// $numeric_data['subdominio_tipo'][] = array( "value" => "3", "name" => "Iframe" );
		// $numeric_data['subdominio_tipo'][] = array( "value" => "4", "name" => "Redirecionamento" );
		$numeric_data['subdominio_tipo'][] = array( "value" => "5", "name" => "Blacklist" );

		// Status venda

		$numeric_data['status_venda'][] = array( "value" => "Aguardando pagamento", "name" => "1" );
		$numeric_data['status_venda'][] = array( "value" => "Finalizada", "name" => "2" );
		$numeric_data['status_venda'][] = array( "value" => "Cancelada", "name" => "3" );
		$numeric_data['status_venda'][] = array( "value" => "Devolvida", "name" => "4" );
		$numeric_data['status_venda'][] = array( "value" => "Bloqueada", "name" => "5" );
		$numeric_data['status_venda'][] = array( "value" => "Completa", "name" => "6" );

		// Status assinatura

		$numeric_data['assinatura_status'][] = array( "value" => "0", "name" => "Aguardando pagamento" );
		$numeric_data['assinatura_status'][] = array( "value" => "1", "name" => "Disponível" );
		$numeric_data['assinatura_status'][] = array( "value" => "3", "name" => "Cancelada" );

		// Uso da assinatura

		$numeric_data['assinatura_use'][] = array( "value" => "0", "name" => "Aguardando uso" );
		$numeric_data['assinatura_use'][] = array( "value" => "1", "name" => "Em uso" );
		$numeric_data['assinatura_use'][] = array( "value" => "3", "name" => "Expirada" );

		// Status

		$numeric_data['status_voucher'][] = array( "value" => "1", "name" => "Disponível" );
		$numeric_data['status_voucher'][] = array( "value" => "2", "name" => "Resgatado" );

		// Ação do banner

		$numeric_data['banner_acao'][] = array( "value" => "1", "name" => "Categoria" );
		$numeric_data['banner_acao'][] = array( "value" => "2", "name" => "Produto" );
		$numeric_data['banner_acao'][] = array( "value" => "3", "name" => "Link" );

		// Visibilidade

		$numeric_data['visibilidade'][] = array( "value" => "1", "name" => "Visível" );
		$numeric_data['visibilidade'][] = array( "value" => "2", "name" => "Invisível" );

	function numeric_find($value, $array,$return) {
	   foreach ($array as $key => $val) {
	       if ( $val['value'] == $value ) {
	           return $val['name'];
	       }
	   }
	   return null;
	}

	function numeric_data($type,$value) {

		global $numeric_data;
		$result = numeric_find($value, $numeric_data[$type],"name");
		return $result;

	}

	function data_info( $table, $id, $info ) {

		global $db_con;

		$edit = mysqli_query( $db_con, "SELECT $info FROM $table WHERE id = '$id' LIMIT 1");
		$data = mysqli_fetch_array( $edit );
		
		return $data[$info];

	}

	function new_user( $level,$nome,$nascimento,$documento_tipo,$documento,$estado,$cidade,$telefone,$email,$pass ) {

		global $db_con;
		global $_SESSION;

		$status = "1";
		$password = md5($pass);
		$created = date('Y-m-d H:i:s');

		if( mysqli_query( $db_con, "INSERT INTO users (nome,email,password,level,status,created) VALUES ('$nome','$email','$password','$level','$status','$created');") ) {

			$uid = mysqli_insert_id($db_con);

			if( mysqli_query( $db_con, "INSERT INTO users_data (rel_users_id,nascimento,documento_tipo,documento,estado,cidade,telefone) VALUES ('$uid','$nascimento','$documento_tipo','$documento','$estado','$cidade','$telefone');") ) {
				
				// SALVA LOG

					$log_uid = $_SESSION['user']['id'];
					$log_nome = $_SESSION['user']['nome'];
					$log_lid = "";
					// Tipos
					if( $_SESSION['user']['level'] == "1" ) {
						$log_user_tipo = "O Administrador";
					}
					if( $_SESSION['user']['level'] == "2" ) {
						$log_user_tipo = "A Loja";
					}
					log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou o usuário ".$nome." (".$email.") às ".databr( date('Y-m-d H:i:s') ) );

				// / SALVA LOG

				return true;

			} else {

				return false;

			}
		
		} else {

			return false;

		}

	}

	function edit_user( $id,$level,$nome,$nascimento,$documento_tipo,$documento,$estado,$cidade,$telefone,$email,$pass ) {

		global $db_con;

		if( $pass ) {
			$password = md5($pass);
			$updatedquery = "UPDATE users SET nome = '$nome',email = '$email',password = '$password',level='$level' WHERE id = '$id'";
		} else {
			$updatedquery = "UPDATE users SET nome = '$nome',email = '$email',level='$level' WHERE id = '$id'";
		}

		if( mysqli_query( $db_con, $updatedquery ) ) {

			$check = mysqli_query( $db_con, "SELECT * FROM users_data WHERE rel_users_id = '$id' LIMIT 1");
			$hasdata = mysqli_num_rows( $check );

			if( $hasdata <= 0 ) {

				mysqli_query( $db_con, "INSERT INTO users_data (rel_users_id) VALUES ($id);");

			}

			if( mysqli_query( $db_con, "UPDATE users_data SET nascimento = '$nascimento',documento_tipo = '$documento_tipo',documento = '$documento',estado = '$estado',cidade = '$cidade',telefone = '$telefone' WHERE rel_users_id = '$id'") ) {

				// SALVA LOG

					$log_uid = $_SESSION['user']['id'];
					$log_nome = $_SESSION['user']['nome'];
					$log_lid = "";
					// Tipos
					if( $_SESSION['user']['level'] == "1" ) {
						$log_user_tipo = "O Administrador";
					}
					if( $_SESSION['user']['level'] == "2" ) {
						$log_user_tipo = "A Loja";
					}
					log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou o usuário ".$nome." (".$email.") às ".databr( date('Y-m-d H:i:s') ) );

				// / SALVA LOG

				return true;

			} else {

				return false;

			}
		
		} else {

			return false;

		}

	}

	function delete_user( $id ) {

		global $db_con;

		$nome = user_info($id,"nome");

		if( mysqli_query( $db_con, "DELETE FROM users WHERE id = '$id'") ) {

			mysqli_query( $db_con, "DELETE FROM users_data WHERE rel_users_id = '$id'");

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o usuário ".$nome." (".$email.") às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function new_estado( $nome ) {

		global $db_con;
		global $_SESSION;

		if( mysqli_query( $db_con, "INSERT INTO estados (nome) VALUES ('$nome');") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou o estado ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function edit_estado( $id,$nome ) {

		global $db_con;

		$updatedquery = "UPDATE estados SET nome = '$nome' WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou o estado ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function delete_estado( $id ) {

		global $db_con;

		$nome = data_info("estados",$id,"nome");

		if( mysqli_query( $db_con, "DELETE FROM estados WHERE id = '$id'") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o estado ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function new_cidade( $estado,$nome ) {

		global $db_con;
		global $_SESSION;
		$estado_nome = data_info("estados",$estado,"uf");
		$subdominio = slugify($nome.$estado_nome);

		if( mysqli_query( $db_con, "INSERT INTO cidades (estado,nome,subdominio) VALUES ('$estado','$nome','$subdominio');") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou a cidade ".$nome." (".data_info("estados",$estado,"nome").") às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function edit_cidade( $id,$estado,$nome ) {

		global $db_con;
		$estado_nome = data_info("estados",$estado,"uf");
		$subdominio = slugify($nome.$estado_nome);

		$updatedquery = "UPDATE cidades SET estado = '$estado', nome = '$nome', subdominio = '$subdominio' WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou a cidade ".$nome." (".data_info("estados",$estado,"nome").") às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function delete_cidade( $id ) {

		global $db_con;

		$nome = data_info("cidades",$id,"nome");

		if( mysqli_query( $db_con, "DELETE FROM cidades WHERE id = '$id'") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu a cidade ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function new_segmento( $nome,$icone,$censura ) {

		global $db_con;
		global $_SESSION;

		if( mysqli_query( $db_con, "INSERT INTO segmentos (nome,icone,censura) VALUES ('$nome','$icone','$censura');") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou o segmento ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function edit_segmento( $id,$nome,$icone,$censura ) {

		global $db_con;

		$updatedquery = "UPDATE segmentos SET nome = '$nome',censura = '$censura' WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			if( $icone ) {
				$icone_antigo = data_info("segmentos",$id,"icone");
				if( $icone_antigo ) {
					unlink( $rootpath."/_core/_uploads/".$icone_antigo );
				}
				$updatedquery = "UPDATE segmentos SET icone = '$icone' WHERE id = '$id'";
				mysqli_query( $db_con, $updatedquery );
			}

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou o segmento ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function delete_segmento( $id ) {

		global $db_con;

		$nome = data_info("segmentos",$id,"nome");

		if( mysqli_query( $db_con, "DELETE FROM segmentos WHERE id = '$id'") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o segmento ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function new_subdominio( $subdominio,$tipo,$rel_id,$url ) {

		global $db_con;
		global $_SESSION;
		global $simple_url;

		if( mysqli_query( $db_con, "INSERT INTO subdominios (subdominio,tipo,rel_id,url) VALUES ('$subdominio','$tipo','$rel_id','$url');") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou o subdominio ".$subdominio.".".$simple_url." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function edit_subdominio( $id,$subdominio,$tipo,$rel_id,$url ) {

		global $db_con;
		global $simple_url;
		$subdominio_antigo = data_info("subdominios",$id,"subdominio");
		$updatedquery = "UPDATE subdominios SET subdominio = '$subdominio',tipo = '$tipo',rel_id = '$rel_id',url = '$url' WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou o subdominio ".$subdominio_antigo.".".$simple_url." para ".$subdominio.".".$simple_url." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function delete_subdominio( $id ) {

		global $db_con;
		global $simple_url;
		$subdominio = data_info("subdominios",$id,"subdominio");

		if( mysqli_query( $db_con, "DELETE FROM subdominios WHERE id = '$id'") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o subdominio ".$subdominio.".".$simple_url." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function welcome_mail($email,$pass) {

		global $rootpath;

		$title = "Bem vindo!";
		$msg = "";
		$msg .= "Bem vindo!<br/>";
		$msg .= "Você já pode utilizar o seu sistema!:<br/><br/>";
		$msg .= "Painel: <a style='color: ##004f2a;' href='".get_just_url()."/login'>".get_just_url()."/login</a><br/>";
		$msg .= "Login: ".$email;
		$msg .= "<br/>";
		$msg .= "Senha: ".$pass;
		$msg .= "<br/><br/>";
		$msg .= "Atenciosamente";
		$msg .= "<br/>";
		$msg .= "Equipe SeuSite!";

		include($rootpath."/_core/_email/default.php");

		if( $fullmsg ) {
			$msg = $fullmsg;
		}

		if( html_mail( $email,"Bem vindo ao SeuSite!",$msg ) ) {

			return true;

		} else {

			return false;

		}

	}

	function new_estabelecimento( 
		$afiliado,
	    $nome,
	    $descricao,
	    $segmento,
	    $estado,
	    $cidade,
	    $subdominio,
	    $perfil,
	    $capa,
	    $cor,
	    $pedido_minimo,
	    $pagamento_dinheiro,
	    $pagamento_cartao_debito,
	    $pagamento_cartao_debito_bandeiras,
	    $pagamento_cartao_credito,
	    $pagamento_cartao_credito_bandeiras,
	    $pagamento_cartao_alimentacao,
	    $pagamento_cartao_alimentacao_bandeiras,
	    $pagamento_outros,
	    $pagamento_outros_descricao,
	    $endereco_cep,
	    $endereco_numero,
	    $endereco_bairro,
	    $endereco_rua,
	    $endereco_complemento,
	    $endereco_referencia,
	    $horario_funcionamento,
	    $entrega_retirada,
	    $entrega_entrega,
	    $entrega_entrega_tipo,
	    $entrega_entrega_valor,
	    $contato_whatsapp,
	    $contato_email,
	    $contato_instagram,
	    $contato_facebook,
	    $contato_youtube,
	    $responsavel_nome,
	    $responsavel_nascimento,
	    $responsavel_documento_tipo,
	    $responsavel_documento,
	    $email,
	    $pass
	 ) {

		global $db_con;
		global $_SESSION;
		global $simple_url;
		global $plano_default;

		$status = "1";
		$status_force = "2";
		$password = md5($pass);
		$level = "2";
		$funcionamento = "1";
		$excluded = "2";

		$datetime = date('Y-m-d H:i:s');

		if( mysqli_query( $db_con, "INSERT INTO users (nome,email,password,level,status,created) VALUES ('$nome','$email','$password','$level','$status','$datetime');") ) {

			$uid = mysqli_insert_id($db_con);

			if( mysqli_query( $db_con, 
				"INSERT INTO estabelecimentos (
					rel_users_id,
					afiliado,
					nome,
					descricao,
					segmento,
					estado,
					cidade,
					subdominio,
					perfil,
					capa,
					cor,
					pedido_minimo,
					pagamento_dinheiro,
					pagamento_cartao_debito,
					pagamento_cartao_debito_bandeiras,
					pagamento_cartao_credito,
					pagamento_cartao_credito_bandeiras,
					pagamento_cartao_alimentacao,
					pagamento_cartao_alimentacao_bandeiras,
					pagamento_outros,
					pagamento_outros_descricao,
					endereco_cep,
					endereco_numero,
					endereco_bairro,
					endereco_rua,
					endereco_complemento,
					endereco_referencia,
					horario_funcionamento,
					entrega_retirada,
					entrega_entrega,
					entrega_entrega_tipo,
					entrega_entrega_valor,
					contato_whatsapp,
					contato_email,
					contato_instagram,
					contato_facebook,
					contato_youtube,
					responsavel_nome,
					responsavel_nascimento,
					responsavel_documento_tipo,
					responsavel_documento,
					email,
					created,
					last_modified,
					last_login,
					status,
					status_force,
					funcionamento,
					excluded
				) VALUES (
					'$uid',
					'$afiliado',
					'$nome',
					'$descricao',
					'$segmento',
					'$estado',
					'$cidade',
					'$subdominio',
					'$perfil',
					'$capa',
					'$cor',
					'$pedido_minimo',
					'$pagamento_dinheiro',
					'$pagamento_cartao_debito',
					'$pagamento_cartao_debito_bandeiras',
					'$pagamento_cartao_credito',
					'$pagamento_cartao_credito_bandeiras',
					'$pagamento_cartao_alimentacao',
					'$pagamento_cartao_alimentacao_bandeiras',
					'$pagamento_outros',
					'$pagamento_outros_descricao',
					'$endereco_cep',
					'$endereco_numero',
					'$endereco_bairro',
					'$endereco_rua',
					'$endereco_complemento',
					'$endereco_referencia',
					'$horario_funcionamento',
					'$entrega_retirada',
					'$entrega_entrega',
					'$entrega_entrega_tipo',
					'$entrega_entrega_valor',
					'$contato_whatsapp',
					'$contato_email',
					'$contato_instagram',
					'$contato_facebook',
					'$contato_youtube',
					'$responsavel_nome',
					'$responsavel_nascimento',
					'$responsavel_documento_tipo',
					'$responsavel_documento',
					'$email',
					'$datetime',
					'$datetime',
					'$datetime',
					'$status',
					'$status_force',
					'$funcionamento',
					'$excluded'
				);") 
			) {

				$eid = mysqli_insert_id($db_con);

				aplicar_plano( $eid,$plano_default );

				$sid = mysqli_insert_id($db_con);
			
				// SALVA LOG

					$log_uid = $_SESSION['user']['id'];
					$log_nome = $_SESSION['user']['nome'];
					$log_lid = "";
					// Tipos
					if( $_SESSION['user']['level'] == "1" ) {
						$log_user_tipo = "O Administrador";
					}
					if( $_SESSION['user']['level'] == "2" ) {
						$log_user_tipo = "A Loja";
					}
					log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou o estabelecimento ".$subdominio.".".$simple_url." às ".databr( date('Y-m-d H:i:s') ) );

				// / SALVA LOG

				welcome_mail($email,$pass);

				return true;

			} else {

				return false;

			}
		
		} else {

			return false;

		}

	}

	function edit_estabelecimento( 
        $id,
        $nome,
        $descricao,
        $segmento,
        $estado,
        $cidade,
        $subdominio,
        $perfil,
        $capa,
        $cor,
		$exibicao,
        $pedido_minimo,
        $pagamento_dinheiro,
        $pagamento_cartao_debito,
        $pagamento_cartao_debito_bandeiras,
        $pagamento_cartao_credito,
        $pagamento_cartao_credito_bandeiras,
        $pagamento_cartao_alimentacao,
        $pagamento_cartao_alimentacao_bandeiras,
        $pagamento_outros,
        $pagamento_outros_descricao,
        $pagamento_pix,
        $pagamento_pix_chave,
		$pagamento_pix_beneficiario,
        $endereco_cep,
        $endereco_numero,
        $endereco_bairro,
        $endereco_rua,
        $endereco_complemento,
        $endereco_referencia,
        $horario_funcionamento,
        $entrega_retirada,
        $entrega_entrega,
        $entrega_entrega_tipo,
        $entrega_entrega_valor,
        $entrega_delivery,
		$entrega_balcao,
		$entrega_outros,
		$entrega_outros_nome,
        $contato_whatsapp,
        $contato_email,
        $contato_instagram,
        $contato_facebook,
        $contato_youtube,
        $estatisticas_analytics,
        $estatisticas_pixel,
        $html,
        $responsavel_nome,
        $responsavel_nascimento,
        $responsavel_documento_tipo,
        $responsavel_documento,
        $email,
        $pass,
        $status_force,
        $excluded
	 ) {

		global $db_con;
		global $simple_url;
		global $rootpath;

		$datetime = date('Y-m-d H:i:s');

		$updatedquery = "UPDATE estabelecimentos SET 
			nome = '$nome', 
			descricao = '$descricao', 
			segmento = '$segmento', 
			estado = '$estado', 
			cidade = '$cidade', 
			subdominio = '$subdominio', 
			cor = '$cor', 
			exibicao = '$exibicao', 
			pedido_minimo = '$pedido_minimo', 
			pagamento_dinheiro = '$pagamento_dinheiro', 
			pagamento_cartao_debito = '$pagamento_cartao_debito', 
			pagamento_cartao_debito_bandeiras = '$pagamento_cartao_debito_bandeiras', 
			pagamento_cartao_credito = '$pagamento_cartao_credito', 
			pagamento_cartao_credito_bandeiras = '$pagamento_cartao_credito_bandeiras', 
			pagamento_cartao_alimentacao = '$pagamento_cartao_alimentacao', 
			pagamento_cartao_alimentacao_bandeiras = '$pagamento_cartao_alimentacao_bandeiras', 
			pagamento_outros = '$pagamento_outros', 
			pagamento_outros_descricao = '$pagamento_outros_descricao',
			pagamento_pix = '$pagamento_pix', 
			chave_pix = '$pagamento_pix_chave', 
			beneficiario_pix = '$pagamento_pix_beneficiario', 
			endereco_cep = '$endereco_cep', 
			endereco_numero = '$endereco_numero', 
			endereco_bairro = '$endereco_bairro', 
			endereco_rua = '$endereco_rua', 
			endereco_complemento = '$endereco_complemento', 
			endereco_referencia = '$endereco_referencia', 
			horario_funcionamento = '$horario_funcionamento', 
			entrega_retirada = '$entrega_retirada', 
			entrega_entrega = '$entrega_entrega', 
			entrega_entrega_tipo = '$entrega_entrega_tipo', 
			entrega_entrega_valor = '$entrega_entrega_valor', 
			delivery = '$entrega_delivery', 
			balcao = '$entrega_balcao', 
			outros = '$entrega_outros', 
			nomeoutros = '$entrega_outros_nome', 
			contato_whatsapp = '$contato_whatsapp', 
			contato_email = '$contato_email', 
			contato_instagram = '$contato_instagram', 
			contato_facebook = '$contato_facebook', 
			contato_youtube = '$contato_youtube',
			estatisticas_analytics = '$estatisticas_analytics', 
			estatisticas_pixel = '$estatisticas_pixel', 
			html = '$html', 
			responsavel_nome = '$responsavel_nome', 
			responsavel_nascimento = '$responsavel_nascimento', 
			responsavel_documento_tipo = '$responsavel_documento_tipo', 
			responsavel_documento = '$responsavel_documento', 
			email = '$email',
			last_modified = '$datetime',
			status_force = '$status_force',
			excluded = '$excluded' 
		WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			$uid = data_info("estabelecimentos",$id,"rel_users_id");

			$updatedquery = "UPDATE users SET email = '$email' WHERE id = '$uid'";
			mysqli_query( $db_con, $updatedquery );

			if( $pass ) {
				$password = md5($pass);
				$updatedquery = "UPDATE users SET password = '$password' WHERE id = '$uid'";
				mysqli_query( $db_con, $updatedquery );
			}

			if( $perfil ) {
				$perfilantigo = data_info("estabelecimentos",$id,"perfil");
				if( $perfilantigo ) {
					unlink( $rootpath."/_core/_uploads/".$perfilantigo );
				}
				$updatedquery = "UPDATE estabelecimentos SET perfil = '$perfil' WHERE id = '$id'";
				mysqli_query( $db_con, $updatedquery );
			}

			if( $capa ) {
				$capaantiga = data_info("estabelecimentos",$id,"capa");
				if( $capaantiga ) {
					unlink( $rootpath."/_core/_uploads/".$capaantiga );
				}
				$updatedquery = "UPDATE estabelecimentos SET capa = '$capa' WHERE id = '$id'";
				mysqli_query( $db_con, $updatedquery );
			}

			$subdominio_antigo = data_info("subdominios",$sid,"subdominio");

			if( $subdominio != $subdominio_antigo ) {

				$updatedquery = "UPDATE subdominios SET subdominio = '$subdominio' WHERE rel_id = '$id'";
				mysqli_query( $db_con, $updatedquery );
			}

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou o estabelecimento ".$subdominio.".".$simple_url." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function semidelete_estabelecimento( $id ) {

		global $db_con;
		global $simple_url;
		global $rootpath;
		$datetime = date('Y-m-d H:i:s');

		if( mysqli_query( $db_con, "UPDATE estabelecimentos SET excluded = '1',excluded_date = '$datetime' WHERE id = '$id'") ) {

		// SALVA LOG

			$log_uid = $_SESSION['user']['id'];
			$log_nome = $_SESSION['user']['nome'];
			$log_lid = "";
			// Tipos
			if( $_SESSION['user']['level'] == "1" ) {
				$log_user_tipo = "O Administrador";
			}
			if( $_SESSION['user']['level'] == "2" ) {
				$log_user_tipo = "A Loja";
			}
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o estabelecimento ".$subdominio.".".$simple_url." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;

		} else {

			return false;

		}

	}

	function delete_estabelecimento( $id ) {

		global $db_con;
		global $simple_url;
		global $rootpath;

		$uid = data_info("estabelecimentos",$id,"rel_users_id");
		$perfilantigo = data_info("estabelecimentos",$id,"perfil");
		$capaantiga = data_info("estabelecimentos",$id,"capa");


		if( mysqli_query( $db_con, "DELETE FROM estabelecimentos WHERE id = '$id'") ) {

			if( $perfilantigo ) {
				unlink( $rootpath."/_core/_uploads/".$perfilantigo );
			}
			
			if( $capaantiga ) {
				unlink( $rootpath."/_core/_uploads/".$capaantiga );
			}

			deletedir( $rootpath."/_core/_uploads/".$id );
			mysqli_query( $db_con, "DELETE FROM users WHERE id = '$uid'");
			mysqli_query( $db_con, "DELETE FROM users_data WHERE rel_users_id = '$uid'");
			mysqli_query( $db_con, "DELETE FROM subdominios WHERE rel_id = '$id' AND tipo = '1'");
			mysqli_query( $db_con, "DELETE FROM categorias WHERE rel_estabelecimentos_id = '$id'");
			mysqli_query( $db_con, "DELETE FROM produtos WHERE rel_estabelecimentos_id = '$id'");
			mysqli_query( $db_con, "DELETE FROM pedidos WHERE rel_estabelecimentos_id = '$id'");
			mysqli_query( $db_con, "DELETE FROM banners WHERE rel_estabelecimentos_id = '$id'");
			mysqli_query( $db_con, "DELETE FROM assinaturas WHERE rel_estabelecimentos_id = '$id'");

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o estabelecimento ".$subdominio.".".$simple_url." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function new_categoria( $estabelecimento,$nome,$ordem,$visible,$status ) {

		global $db_con;
		global $_SESSION;

		if( mysqli_query( $db_con, "INSERT INTO categorias (rel_estabelecimentos_id,nome,ordem,visible,status) VALUES ('$estabelecimento','$nome','$ordem','$visible','$status');") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_ordem = $_SESSION['user']['ordem'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou a categoria ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function edit_categoria( $id,$estabelecimento,$nome,$ordem,$visible,$status ) {

		global $db_con;

		$updatedquery = "UPDATE categorias SET rel_estabelecimentos_id = '$estabelecimento', nome = '$nome', ordem = '$ordem', visible = '$visible',status = '$status' WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_ordem = $_SESSION['user']['ordem'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou a categoria ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function delete_categoria( $id ) {

		global $db_con;

		$nome = data_info("categorias",$id,"nome");

		if( mysqli_query( $db_con, "DELETE FROM categorias WHERE id = '$id'") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu a categoria ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function new_produto( $estabelecimento,$categoria,$destaque,$ref,$nome,$descricao,$valor,$oferta,$valor_promocional,$variacao,$status,$visible,$integrado ) {

		global $db_con;
		global $_SESSION;

		if( $oferta == "2" ) {
			$valor_promocional = $valor;
		}

		$datetime = date('Y-m-d H:i:s');

		if( mysqli_query( $db_con, "INSERT INTO produtos (rel_estabelecimentos_id,rel_categorias_id,destaque,ref,nome,descricao,valor,oferta,valor_promocional,variacao,status,visible,created,last_modified,integrado) VALUES ('$estabelecimento','$categoria','$destaque','$ref','$nome','$descricao','$valor','$oferta','$valor_promocional','$variacao','$status','$visible','$datetime','$datetime','$integrado');") ) {

			$pid = mysqli_insert_id($db_con);
			if( !$ref ) {
				$ref = "REF-".$pid;
				mysqli_query( $db_con, "UPDATE produtos SET ref = '$ref' WHERE id = '$pid'");
			}

			if( $categoria ) {
				mysqli_query( $db_con, "UPDATE categorias SET last_modified = '$datetime' WHERE id = '$categoria'");
			}

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou o produto ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return $pid;
		
		} else {

			return false;

		}

	}

	function edit_produto( $id,$estabelecimento,$categoria,$destaque,$ref,$nome,$descricao,$valor,$oferta,$valor_promocional,$variacao,$status,$visible,$integrado ) {

		global $db_con;

		if( $oferta == "2" ) {
			$valor_promocional = $valor;
		}

		$datetime = date('Y-m-d H:i:s');

		$updatedquery = "UPDATE produtos SET rel_estabelecimentos_id = '$estabelecimento',rel_categorias_id = '$categoria',ref = '$ref',nome = '$nome',descricao = '$descricao',valor = '$valor',oferta = '$oferta',valor_promocional = '$valor_promocional',variacao = '$variacao',status = '$status',visible = '$visible',last_modified = '$datetime',integrado = '$integrado' WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			mysqli_query( $db_con, "UPDATE categorias SET last_modified = '$datetime' WHERE id = '$categoria'");

			if( $destaque ) {
				$destaqueantigo = data_info("produtos",$id,"destaque");
				if( $destaqueantigo ) {
					unlink( $rootpath."/_core/_uploads/".$destaqueantigo );
				}
				$updatedquery = "UPDATE produtos SET destaque = '$destaque' WHERE id = '$id'";
				mysqli_query( $db_con, $updatedquery );
			}

			if( !$ref ) {
				$ref = "REF-".$id;
				mysqli_query( $db_con, "UPDATE produtos SET ref = '$ref' WHERE id = '$id'");
			}

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou a produto ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function delete_produto( $id ) {

		global $db_con;
		global $rootpath;

		$nome = data_info("produtos",$id,"nome");

		if( mysqli_query( $db_con, "DELETE FROM produtos WHERE id = '$id'") ) {

          	$quicksql = mysqli_query( $db_con, "SELECT * FROM midia WHERE rel_id = '$id' ORDER BY id DESC LIMIT 999" );
          	while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
          		
          		$mid = $quickdata['id'];
          		$midia = $quickdata['url'];

				unlink( $rootpath."/_core/_uploads/".$midia );
				mysqli_query( $db_con, "DELETE FROM midia WHERE id = '$mid'");

			}	

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o produto ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function new_banner( $estabelecimento,$titulo,$desktop,$mobile,$link,$status ) {

		global $db_con;
		global $_SESSION;

		$datetime = date('Y-m-d H:i:s');

		if( mysqli_query( $db_con, "INSERT INTO banners (rel_estabelecimentos_id,titulo,desktop,mobile,link,status,created,last_modified) VALUES ('$estabelecimento','$titulo','$desktop','$mobile','$link','$status','$datetime','$datetime');") ) {

			$pid = mysqli_insert_id($db_con);

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." cadastrou o banner ".$titulo." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return $pid;
		
		} else {

			return false;

		}

	}

	function edit_banner( $id,$estabelecimento,$titulo,$desktop,$mobile,$link,$status ) {

		global $db_con;
		global $rootpath;
		$desktop_antigo = data_info("banners",$id,"desktop");
		$mobile_antigo = data_info("banners",$id,"mobile");
		if( !$desktop ) {
			$desktop = $desktop_antigo;
		} else {
			unlink( $rootpath."/_core/_uploads/".$desktop_antigo );
		}
		if( !$mobile ) {
			$mobile = $mobile_antigo;
		} else {
			unlink( $rootpath."/_core/_uploads/".$mobile_antigo );
		}

		$updatedquery = "UPDATE banners SET rel_estabelecimentos_id = '$estabelecimento',titulo = '$titulo',desktop = '$desktop',mobile = '$mobile',link = '$link',status = '$status' WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou a produto ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;
		
		} else {

			return false;

		}

	}

	function delete_banner( $id ) {

		global $db_con;
		global $rootpath;

		$titulo = data_info("banners",$id,"titulo");
		$desktop_antigo = data_info("banners",$id,"desktop");
		$mobile_antigo = data_info("banners",$id,"mobile");
		
		if( $desktop ) {
			if( $desktop_antigo ) {
				unlink( $rootpath."/_core/_uploads/".$desktop_antigo );
			}
		}
		if( $mobile ) {
			if( $mobile_antigo ) {
				unlink( $rootpath."/_core/_uploads/".$mobile_antigo );
			}
		}

		if( mysqli_query( $db_con, "DELETE FROM banners WHERE id = '$id'") ) {

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";
				// Tipos
				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}
				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o banner ".$titulo." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

	function has_variacao( $pid ) {

		$contaitem = 0;
		$variacao = data_info("produtos",$pid,"variacao");
		$variacao = json_decode( $variacao, TRUE );
		for ( $x=0; $x < count( $variacao ); $x++ ){
			for( $y=0; $y < count( $variacao[$x]['item'] ); $y++ ){
				$contaitem++;
			}
		}
		return $contaitem;

	}

	function variacao_info( $pid,$id,$info ) {

		$contaitem = 0;
		$variacao = data_info("produtos",$pid,"variacao");
		$variacao = json_decode( $variacao, TRUE );
		$data = $variacao[$id][$info];
		return htmljson( $data );

	}

	function variacao_item_info( $pid,$id,$item,$info ) {

		$contaitem = 0;
		$variacao = data_info("produtos",$pid,"variacao");
		$variacao = json_decode( $variacao, TRUE );
		$data = $variacao[$id]['item'][$item][$info];
		return htmljson( $data );

	}

	function variacao_opcao_ativa( $pid,$id,$compare ) {

		global $_SESSION;
		$eid = data_info("produtos",$pid,"rel_estabelecimentos_id");
		$contaitem = 0;

		if( strlen( $_SESSION['sacola'][$eid][$pid]['variacoes'][$id] ) >= 1 ) {
			$items = explode( ",", $_SESSION['sacola'][$eid][$pid]['variacoes'][$id] );
			foreach ($items as $item) {
				if( $item == $compare ) {
					$contaitem++;
				}
			}
			if( $_SESSION['sacola'][$eid][$pid]['variacoes'][$id] == $compare ) {
				$contaitem++;
			}
		}

		return $contaitem;

	}


?>