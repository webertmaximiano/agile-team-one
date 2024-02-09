<?php

session_start();

function log_register( $rel_users_id,$rel_lojas_id,$info ) {

	global $db_con;

	$time = date('Y-m-d H:i:s');

	mysqli_query( $db_con, " INSERT INTO logs(rel_users_id,rel_lojas_id,info,date_time) VALUES ('$rel_users_id','$rel_lojas_id','$info','$time') ");

}

function user_info( $info ) {

	global $_SESSION;
	return $_SESSION['user'][$info];

	if( $info == "password" ) {

		global $db_con;
		$id = $_SESSION['user']['id'];
		$edit = mysqli_query( $db_con, "SELECT * FROM users WHERE id = '$id' LIMIT 1");
		$data = mysqli_fetch_array( $edit );
		return $data['password'];

	}

}

function user_info_out( $id, $info ) {

	global $db_con;
	$edit = mysqli_query( $db_con, "SELECT * FROM users WHERE id = '$id' LIMIT 1");
	$data = mysqli_fetch_array( $edit );
	return $data[$info];

}

function make_login( $email, $pass, $method, $keepalive ) {

	session_destroy();
	session_start();

	$pass = md5( $pass );

	global $db_con;
	
	if( $method == "login" ) {
		$query = mysqli_query( $db_con, "SELECT * FROM users WHERE(email='$email' AND password='$pass') LIMIT 1");
	}
	if( $method == "keepalive" ) {
		$query = mysqli_query( $db_con, "SELECT * FROM users WHERE(keepalive='$email') LIMIT 1");
	}
	$logged = mysqli_num_rows( $query );

	if( $logged > 0 ) {

		$data = mysqli_fetch_array( $query );

		$_SESSION['user']['logged'] = "1";
		$_SESSION['user']['id'] = $data['id'];
		$_SESSION['user']['nome'] = $data['nome'];
		$_SESSION['user']['email'] = $data['email'];
		$_SESSION['user']['level'] = $data['level'];
		$_SESSION['user']['status'] = $data['status'];
		$_SESSION['user']['operacao'] = $data['operacao'];

		$uid = $data['id'];
		
		// Dados complementares

			// Administrador

			if( $data['level'] == "1" ) {

				$query2 = mysqli_query( $db_con, "SELECT * FROM users_data WHERE( rel_users_id = '$uid' ) LIMIT 1");
				$data2 = mysqli_fetch_array( $query2 );

				$_SESSION['user']['estado'] = $data2['estado'];
				$_SESSION['user']['cidade'] = $data2['cidade'];
				$_SESSION['user']['telefone'] = $data2['telefone'];

			}

			// Loja

			if( $data['level'] == "2" ) {

				// SETAR DADOS NORMAIS

				$query2 = mysqli_query( $db_con, "SELECT * FROM estabelecimentos WHERE( rel_users_id = '$uid' ) LIMIT 1");
				$data2 = mysqli_fetch_array( $query2 );
				
				$_SESSION['estabelecimento']['id'] = $data2['id'];
				$_SESSION['estabelecimento']['avatar'] = $data2['avatar'];
				$_SESSION['estabelecimento']['perfil'] = $data2['perfil'];
				$_SESSION['estabelecimento']['nome'] = $data2['nome'];
				$_SESSION['estabelecimento']['subdominio'] = $data2['subdominio'];
				$_SESSION['estabelecimento']['logged'] = 1;
				$_SESSION['estabelecimento']['level'] = $data['level'];
				$_SESSION['estabelecimento']['funcionalidade_marketplace'] = $data2['funcionalidade_marketplace'];
				$_SESSION['estabelecimento']['funcionalidade_banners'] = $data2['funcionalidade_banners'];
				$_SESSION['estabelecimento']['funcionalidade_variacao'] = $data2['funcionalidade_variacao'];
				$_SESSION['estabelecimento']['status'] = $data2['status'];
				$_SESSION['estabelecimento']['status_force'] = $data2['status_force'];
				$_SESSION['estabelecimento']['excluded'] = $data2['excluded'];
				$_SESSION['estabelecimento']['expiracao'] = $data2['expiracao'];
				atualiza_estabelecimento( $data2['id'], "online" );

			}

		// Ultimo login

		$last_login = date('Y-m-d H:i:s');
		$nome = $data['nome'];
		$lid = "";
		if( $data2['rel_lojas_id'] ) {
			$lid = $data2['rel_lojas_id'];
		}

		// Tipos
		if( $data['level'] == "1" ) {
			$tipo = "O Administrador";
		}
		if( $data['level'] == "2" ) {
			$tipo = "A Loja";
		}

		// Keep Alive

		$keepalive_key = "";

		if( $keepalive == "1" ) {
			$keepalive_key = md5( $uid.date('dmYhis').random_key(30) );
		}

		if( $keepalive == "2" ) {
			$keepalive_key = $data['keepalive'];
		}

		$_SESSION['user']['keepalive'] = $keepalive_key;

		// Atualizar
		mysqli_query( $db_con, "UPDATE users SET keepalive='$keepalive_key',last_login='$last_login' WHERE id = '$uid'");
		
		// Registrar
		log_register( $uid,$lid, $tipo." ".$nome." fez login às ".databr( date('Y-m-d H:i:s') ) );
		return true;

	} else {

		return false;

	}

}

function restrict($level) {

	global $_SESSION;

	$actualurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	if( $_SESSION['user']['logged'] != "1" ) {

		header("Location: ".get_just_url()."/login?msg=restrict&redirect=".$actualurl);

	}

	if( $_SESSION['user']['level'] != $level AND $_SESSION['user']['level'] != "1" ) {

		header("Location: ".get_just_url()."/login?msg=restrict&redirect=".$actualurl);

	}

}

function restrict_estabelecimento() {

	global $_SESSION;

	$actualurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	if( $_SESSION['user']['logged'] != "1" ) {

		header("Location: ".get_just_url()."/login?msg=restrict&redirect=".$actualurl);

	}

	if( $_SESSION['estabelecimento']['logged'] != "1" ) {

		header("Location: ".get_just_url()."/login?msg=restrict&redirect=".$actualurl);

	}

	if( $_SESSION['estabelecimento']['status_force'] == "1" ) {

		header("Location: ".get_just_url()."/painel/inativo");

	}

	if( $_SESSION['estabelecimento']['excluded'] == "1" ) {

		header("Location: ".get_just_url()."/painel/configuracoes/reativacao");

	}

}

function is_active( $eid ) {

	global $app;

	$status_force = data_info( "estabelecimentos", $eid, "status_force" );
	$status = data_info( "estabelecimentos", $eid, "status" );
	if( $status_force == "1" OR $status != "1" ) {
		header("Location: ".$app['url']."/desativado");
	}


}

function restrict_funcionalidade( $funcionalidade ) {

	global $_SESSION;

	if( $_SESSION['estabelecimento'][$funcionalidade] != "1" ) {
		header("Location: ".get_panel_url()."/inicio?msg=funcaodesativada");
	}

}

function restrict_expirado() {

	global $_SESSION;

	if( $_SESSION['estabelecimento']['status'] != "1" ) {

		header("Location: ".get_panel_url()."/inicio?msg=inativo");

	}

}

function recover_password_generate($email,$key,$msg) {

	global $db_con;

	$email = strtolower( mysqli_real_escape_string( $db_con, $email ) );

	$subject = "Recuperação de senha";

	$sql = "SELECT * FROM users WHERE (email = '$email') LIMIT 1";

	$query = mysqli_query( $db_con, $sql );

	$has = mysqli_num_rows( $query );
		
	if ( !$has ) {

		return false;
	
	} else {

		if ( mysqli_query( $db_con," UPDATE users SET recover_key='$key' WHERE email = '$email' ") ) {

			if( html_mail( $email,$subject,$msg ) ) {

				return true;

			} else {

				return false;

			}

		}

	}

}

function recover_password_save($key,$password) {

	global $db_con;
	$password = md5( $password );
	$sql = "SELECT * FROM users WHERE (recover_key = '$key') LIMIT 1";
	$query = mysqli_query( $db_con,$sql );
		
	if ( mysqli_num_rows( $query ) > 0 ) {

		if( mysqli_query( $db_con," UPDATE users SET password='$password',recover_key='' WHERE recover_key = '$key' ") ) {

			return true;

		} else {

			return false;

		}

	} else {

		return false;

	}

}

function user_token_generate( $uid ) {

	global $_SESSION;
	global $db_con;

	$edit = mysqli_query( $db_con, "SELECT * FROM users WHERE id = '$uid' LIMIT 1");
	$data = mysqli_fetch_array( $edit );
	$user_pass = strrev( $data['password'] );

	$token = $uid.":".$user_pass;
	$token = base64_encode( $token );

	return $token;

}

function user_token_info( $id,$pass,$theinfo ) {

	global $_SESSION;
	global $db_con;

	$edit = mysqli_query( $db_con, "SELECT * FROM users WHERE id = '$id' AND password = '$pass' LIMIT 1");
	$hasdata = mysqli_num_rows( $edit );
	$data = mysqli_fetch_array( $edit );
	$info = "";

	if( $hasdata ) {

		$info['nome'] = $data['nome'];
		$info['email'] = $data['email'];
		$info['level'] = $data['level'];
		$info['status'] = $data['status'];

		// Estabelecimento

		if( $data['level'] == "2" ) {

			$query2 = mysqli_query( $db_con, "SELECT * FROM estabelecimentos WHERE( rel_users_id = '$uid' ) LIMIT 1");
			$data2 = mysqli_fetch_array( $query2 );

			$info['subdominio'] = $data2['subdominio'];
			$info['rel_estabelecimentos_id'] = $data2['rel_estabelecimentos_id'];

		}

	}

	return $info[$theinfo];

}

function sacola_criar( $eid ) {

	global $db_con;
	session_start();
	unset( $_SESSION['sacola'][$eid] );
	$_SESSION['sacola'][$eid] = "";
	return $eid;

}

function sacola_adicionar( $eid,$pid,$quantidade,$observacoes,$variacoes ) {

	global $db_con;
	session_start();

	$newpid = rand(0,9999);

	while( $variacoes[$newpid] ) {
		$newpid = rand(0,9999);
	}

	// CUSTOM DATA

	$variacao_texto = "";
	$valor_adicional_soma = array();

	for ( $x=0; $x < count( $variacoes ); $x++ ){

		$variacao_texto_titulo = "";
		$variacao_texto_conteudo = "";

		if( strlen( $variacoes[$x] ) >= 1 ) {
			$variacao_texto_titulo = "*".variacao_info( $pid,$x,"nome" )."*: ";
		}

		$items = explode( ",", $variacoes[$x] );
		foreach ($items as $item) {
			$valor_adicional = "";
			
		
			// Funçao para calcular o maior valor da pizza 
			
			
			if( variacao_item_info( $pid,$x,$item,"valor" ) > $valor_max && variacao_info( $pid,$x,"nome" ) == "Escolha o sabor da pizza abaixo" ) {
			    
			$valor_max = variacao_item_info( $pid,$x,$item,"valor");    
			$valor_adicional_soma = variacao_item_info( $pid,$x,$item,"valor" );
			$valor_adicional = " (+ R$ ".dinheiro( variacao_item_info( $pid,$x,$item,"valor" ), "BR").")";
			
			}
			
			if( variacao_item_info( $pid,$x,$item,"valor" ) > 0  && variacao_info( $pid,$x,"nome" ) !== "Escolha o sabor da pizza abaixo"  ) {
			    
			    
			$valor_adicional_diferente = $valor_adicional_diferente + variacao_item_info( $pid,$x,$item,"valor" );
			$valor_adicional = " (+ R$ ".dinheiro( variacao_item_info( $pid,$x,$item,"valor" ), "BR").")";
			
			}
			
			$valor_adicional_max = max($valor_adicional_soma);
			
			
			
			
			$variacao_texto_conteudo .= variacao_item_info( $pid,$x,$item,"nome" ).$valor_adicional.", ";
		}

		$variacao_texto_conteudo = trim( $variacao_texto_conteudo, ", " );
		if( $variacao_texto_titulo OR $variacao_texto_conteudo ) {
			$variacao_texto_conteudo .= ".\n";
			$variacao_texto .= $variacao_texto_titulo." ".$variacao_texto_conteudo;
		}

	}

	if( $observacoes ) {
		$variacao_texto .= "*Observações:* ".$observacoes;
	}
	
	
	
	
      
	
     $valor_adicional_soma = array_sum($valor_adicional_soma) + $valor_max + $valor_adicional_diferente ;
    
    
   

	

	// / CUSTOM DATA

	$_SESSION['sacola'][$eid][$newpid]['id'] = $pid;
	$_SESSION['sacola'][$eid][$newpid]['quantidade'] = $quantidade;
	$_SESSION['sacola'][$eid][$newpid]['observacoes'] = $observacoes;
	$_SESSION['sacola'][$eid][$newpid]['variacoes'] = $variacoes;
	$_SESSION['sacola'][$eid][$newpid]['variacoes_texto'] = $variacao_texto;
	$_SESSION['sacola'][$eid][$newpid]['valor_adicional'] = $valor_adicional_soma;

}

function sacola_alterar( $eid,$pid,$quantidade ) {

	global $db_con;
	session_start();

	$_SESSION['sacola'][$eid][$pid]['quantidade'] = $quantidade;

}

function sacola_remover( $eid,$pid ) {

	global $db_con;
	session_start();

	unset( $_SESSION['sacola'][$eid][$pid] );

}

function checkout_salvar( $nome,$whatsapp,$forma_entrega,$estado,$cidade,$endereco_cep,$endereco_numero,$endereco_bairro,$endereco_rua,$endereco_complemento,$endereco_referencia,$forma_pagamento,$forma_pagamento_informacao,$cupom ) {

	global $db_con;
	session_start();

	$_SESSION['checkout']['nome'] = $nome;
	$_SESSION['checkout']['whatsapp'] = $whatsapp;
	$_SESSION['checkout']['forma_entrega'] = $forma_entrega;
	if( is_numeric( $estado ) ) {
		$_SESSION['checkout']['estado'] = $estado;
	}
	if( is_numeric( $cidade ) ) {
		$_SESSION['checkout']['cidade'] = $cidade;
	}
	$_SESSION['checkout']['endereco_cep'] = $endereco_cep;
	$_SESSION['checkout']['endereco_numero'] = $endereco_numero;
	$_SESSION['checkout']['endereco_bairro'] = $endereco_bairro;
	$_SESSION['checkout']['endereco_rua'] = $endereco_rua;
	$_SESSION['checkout']['endereco_complemento'] = $endereco_complemento;
	$_SESSION['checkout']['endereco_referencia'] = $endereco_referencia;
	$_SESSION['checkout']['forma_pagamento'] = $forma_pagamento;
	$_SESSION['checkout']['forma_pagamento_informacao'] = $forma_pagamento_informacao;
	$_SESSION['checkout']['cupom'] = $cupom;

}

function gera_comprovante($eid,$modo,$tamanho,$numero) {

	global $_SESSION;
	global $db_con;

	$estabelecimento = data_info("estabelecimentos",$eid,"nome");
	$endereco_rua = data_info("estabelecimentos",$eid,"endereco_rua");
	$endereco_numero = data_info("estabelecimentos",$eid,"endereco_numero");
	$endereco_bairro = data_info("estabelecimentos",$eid,"endereco_bairro");
	$contato_whatsapp = data_info("estabelecimentos",$eid,"contato_whatsapp");
	$subdominiox = data_info("estabelecimentos",$eid,"subdominio");
	$horario = date('d/m/Y \à\s H:i');

	$subtotal = array();
	$comprovante = "";
	$comprovante .= strtoupper( "*".$estabelecimento."*\n" );
	$comprovante .= "".$endereco_rua." ".$endereco_numero.", ".$endereco_bairro."\n";
	$comprovante .= "".$contato_whatsapp."\n";
	$comprovante .= "------\n";
	$comprovante .= "*Pedido ".$numero."*\n";
	$comprovante .= "------\n\n";


	if( $tamanho == "1" ) {

		$comprovante = trim( $comprovante, "\n" );
		$comprovante .= "\n".$horario."\n";
		$comprovante .= "------\n\n";

		$comprovante .= "*Nome:* \n";
		$comprovante .= $_SESSION['checkout']['nome']." \n\n";
		$comprovante .= "*Whatsapp:* \n";
		$comprovante .= $_SESSION['checkout']['whatsapp']." \n\n";

		if( $_SESSION['checkout']['forma_entrega'] == "retirada" ) {
			$forma_entrega = "Retirar na loja";
		}
		if( $_SESSION['checkout']['forma_entrega'] != "retirada" ) {
			$forma_entrega = "Entregar no endereço";
		}
		
		//$comprovante .= "*Forma de entrega:* ".$forma_entrega."\n\n";

		if( $_SESSION['checkout']['forma_entrega'] != "retirada" ) {

			$comprovante .= "*Endereços:* \n";

			if( $_SESSION['checkout']['endereco_cep'] ) {
				$comprovante .= "CEP: ".$_SESSION['checkout']['endereco_cep'].", ";
			}
			if( $_SESSION['checkout']['endereco_numero'] ) {
				$comprovante .= " Nº: ".$_SESSION['checkout']['endereco_numero'].", ";
			}
			if( $_SESSION['checkout']['endereco_bairro'] ) {
				$comprovante .= " Bairro: ".$_SESSION['checkout']['endereco_bairro'].", ";
			}
			if( $_SESSION['checkout']['endereco_rua'] ) {
				$comprovante .= " Rua: ".$_SESSION['checkout']['endereco_rua'].", ";
			}
			if( $_SESSION['checkout']['endereco_complemento'] ) {
				$comprovante .= " Complemento: ".$_SESSION['checkout']['endereco_complemento'].",";
			}
			if( $_SESSION['checkout']['cidade'] ) {
				$comprovante .= " Cidade: ".data_info("cidades",$_SESSION['checkout']['cidade'],"nome")."/".data_info("estados",$_SESSION['checkout']['estado'],"nome").",";
			}
			if( $_SESSION['checkout']['endereco_referencia'] ) {
				$comprovante .= " Referência: ".$_SESSION['checkout']['endereco_referencia'];
			}
			$comprovante .= "\n\n";

		}

		if( $_SESSION['checkout']['forma_pagamento'] == "1" ) {
			$forma_pagamento = "Dinheiro - Troco para: ";
		}
		if( $_SESSION['checkout']['forma_pagamento'] == "2" ) {
			$forma_pagamento = "Cartão de débito - Bandeira: ";
		}
		if( $_SESSION['checkout']['forma_pagamento'] == "3" ) {
			$forma_pagamento = "Cartão de crédito - Bandeira: ";
		}
		if( $_SESSION['checkout']['forma_pagamento'] == "4" ) {
			$forma_pagamento = "Ticket alimentação - Bandeira: ";
		}
		if( $_SESSION['checkout']['forma_pagamento'] == "5" ) {
			$forma_pagamento = "Outros - Forma: ";
		}
		if( $_SESSION['checkout']['forma_pagamento'] == "6" ) {
			$forma_pagamento = "PIX";
		}

		$comprovante .= "*Forma de pagamento:* \n";
		$comprovante .= $forma_pagamento.$_SESSION['checkout']['forma_pagamento_informacao']." \n\n";
		$comprovante .= "------\n";
		$comprovante .= "*PRODUTOS* \n";
		$comprovante .= "------\n\n";

	}

	foreach( $_SESSION['sacola'][$eid] AS $key => $value ) {
		$pid = $value['id'];
		$query_content = mysqli_query( $db_con, "SELECT * FROM produtos WHERE id = '$pid' AND status = '1' ORDER BY id ASC LIMIT 1" );
		$data_content = mysqli_fetch_array( $query_content );
		$valor_final = $data_content['valor_promocional'];
		$comprovante .= "*".$_SESSION['sacola'][$eid][$key]['quantidade']." x* #".$data_content['ref']." ".$data_content['nome']."\n";
		if( $_SESSION['sacola'][$eid][$key]['variacoes_texto'] ) { 
			$variacoes_texto = trim( $_SESSION['sacola'][$eid][$key]['variacoes_texto'], "\n" );
			$variacoes_texto = html_entity_decode( $variacoes_texto );
			$comprovante .= $variacoes_texto."\n";
		}
		$comprovante .= "*Valor:* R$ ".dinheiro( ( $valor_final + $_SESSION['sacola'][$eid][$key]['valor_adicional'] ) * $_SESSION['sacola'][$eid][$key]['quantidade'], "BR" );
		$comprovante .= "\n\n";
		$subtotal[] .= ( ( $valor_final + $_SESSION['sacola'][$eid][$key]['valor_adicional'] ) * $_SESSION['sacola'][$eid][$key]['quantidade'] );
	}

	$subtotal_valor = array_sum( $subtotal );
	$subtotal = "R$ ".dinheiro( $subtotal_valor, "BR" );

	// Cupom

  	$datetime = date("Y-m-d h:i:s");
	$cupom = $_SESSION['checkout']['cupom'];
	$checkcupom = mysqli_query( $db_con, "SELECT * FROM cupons WHERE codigo = '$cupom' AND rel_estabelecimentos_id = '$eid' LIMIT 1");
	$hascupom = mysqli_num_rows( $checkcupom );
	$datacupom = mysqli_fetch_array( $checkcupom );

	if( $hascupom ) {
		if( $datacupom['quantidade'] <= 0 OR $datetime >= $datacupom['validade'] ) {
			// Cupom inválido
			$cupom = "";
		} else {
			if( $datacupom['tipo'] == "1" ) {
				$cupom_porcentagem = $datacupom['desconto_porcentagem'];
				$cupom_descontado = ( $subtotal_valor / 100 * $cupom_porcentagem );
				if( $cupom_descontado >= $datacupom['valor_maximo'] && $datacupom['valor_maximo'] > 0 ) {
					$cupom_descontado = $datacupom['valor_maximo'];
				}
				$cupom_ativo = "1";
				$cupom_desc = $cupom." (- R$".dinheiro( $cupom_descontado, "BR" ).")";
			}
			if( $datacupom['tipo'] == "2" ) {
				$cupom_fixo = $datacupom['desconto_fixo'];
				$cupom_descontado = $cupom_fixo;
				if( $cupom_descontado >= $datacupom['valor_maximo'] && $datacupom['valor_maximo'] > 0 ) {
					$cupom_descontado = $datacupom['valor_maximo'];
				}
				$cupom_ativo = "1";
				$cupom_desc = $cupom." (- R$".dinheiro( $cupom_descontado, "BR" ).")";
			}
		}
	}

	// Frete

	$fid = $_SESSION['checkout']['forma_entrega'];
	$query_content = mysqli_query( $db_con, "SELECT * FROM frete WHERE id = '$fid' ORDER BY id ASC LIMIT 1" );
	$data_content = mysqli_fetch_array( $query_content );
	$frete_valor = $data_content['valor'];
	$frete = "R$ ".dinheiro( $frete_valor, "BR" );
	$frete_desc = htmlclean( $data_content['nome'] );
	if( $frete_valor >= 0.01 ) {
		$frete_desc .= " (+ R$".dinheiro( $frete_valor, "BR" ).")";
	}
	if( $fid == "retirada" ) {
		$frete_desc = "Retirar no Balcão";
	}

	$total = "R$ ".( dinheiro( $subtotal_valor + $frete_valor - $cupom_descontado , "BR") );

	$comprovante .= "------\n";
	$comprovante .= "*Subtotal:* ".$subtotal."\n";
	if( $cupom_ativo ) {
		$comprovante .= "*Cupom:* ".$cupom_desc."\n";
	}
	$comprovante .= "*Entrega:* ".$frete_desc."\n";
	$comprovante .= "------\n\n";
	$comprovante .= "*Total:* ".$total."\n";
	$comprovante .= "------\n\n";
	$comprovante .= "https://".$subdominiox.".estouon.app.br\n";

	if( $modo == "html" ) {
		$comprovante = str_replace("'", "", $comprovante);
		$comprovante = htmlcleanbb( $comprovante );
		$comprovante = bbzap( $comprovante );
		$comprovante = nl2br( $comprovante );
		return $comprovante;
	}
	if( $modo == "texto" ) {
		$comprovante = str_replace("'", "", $comprovante);
		$comprovante = htmlcleanbb( $comprovante );
		return $comprovante;
	}

}

function whatsapp_link( $pedido, $modo = "1" ) {

	global $db_con;
	$edit = mysqli_query( $db_con, "SELECT * FROM pedidos WHERE id = '$pedido' LIMIT 1");
	$data = mysqli_fetch_array( $edit );
	$numero = "55".clean_str( data_info( "estabelecimentos",$data['rel_estabelecimentos_id'],"contato_whatsapp" ) );
	$text = urlencode( $data['comprovante'] );
	if( $modo == "1" ) {
		$link = "https://wa.me/".$numero."?text=".$text;
	} else {
		$link = "https://wa.me/?text=".$text;
	}
	return $link;

}

function new_pedido( 
  	$token,
	$rel_segmentos_id,
	$rel_estabelecimentos_id,
	$nome,
	$whatsapp,
	$forma_entrega,
	$estado,
	$cidade,
	$endereco_cep,
	$endereco_numero,
	$endereco_bairro,
	$endereco_rua,
	$endereco_complemento,
	$endereco_referencia,
	$forma_pagamento,
	$forma_pagamento_informacao,
	$data_hora,
	$cupom,
	$vpedido
) {

	global $db_con;
	global $_SESSION;
	session_id( $token );
	$status = "1";

	if( mysqli_query( $db_con, "INSERT INTO pedidos (
		rel_segmentos_id,
		rel_estabelecimentos_id,
		nome,
		whatsapp,
		forma_entrega,
		estado,
		cidade,
		endereco_cep,
		endereco_numero,
		endereco_bairro,
		endereco_rua,
		endereco_complemento,
		endereco_referencia,
		forma_pagamento,
		forma_pagamento_informacao,
		status,
		data_hora,
		cupom,
		v_pedido
	) VALUES (
		'$rel_segmentos_id',
		'$rel_estabelecimentos_id',
		'$nome',
		'$whatsapp',
		'$forma_entrega',
		'$estado',
		'$cidade',
		'$endereco_cep',
		'$endereco_numero',
		'$endereco_bairro',
		'$endereco_rua',
		'$endereco_complemento',
		'$endereco_referencia',
		'$forma_pagamento',
		'$forma_pagamento_informacao',
		'$status',
		'$data_hora',
		'$cupom',
		'$vpedido'
	);") ) {

		$peid = mysqli_insert_id($db_con);
		$comprovante = gera_comprovante($rel_estabelecimentos_id,"texto","1",$peid);

		mysqli_query( $db_con, "UPDATE pedidos SET comprovante = '$comprovante' WHERE id = '$peid'" );

		// CUPOM
		$checkcupom = mysqli_query( $db_con, "SELECT * FROM cupons WHERE codigo = '$cupom' AND rel_estabelecimentos_id = '$rel_estabelecimentos_id' LIMIT 1");
		$hascupom = mysqli_num_rows( $checkcupom );
		$datacupom = mysqli_fetch_array( $checkcupom );

		if( $hascupom ) {
			$newquantidade = $datacupom['quantidade'] - 1;
			if( $newquantidade <= 0 ) {
				$newquantidade = 0;
			}
			mysqli_query( $db_con, "UPDATE cupons SET quantidade = '$newquantidade' WHERE codigo = '$cupom' AND rel_estabelecimentos_id = '$rel_estabelecimentos_id'" );
		}

		// SALVA LOG

			$log_uid = "";
			$log_nome = $nome;
			$log_lid = "";
			$log_user_tipo = "O cliente";
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." efetuou o pedido ".$peid." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return $peid;
	
	} else {

		return false;

	}

}

function edit_pedido( $id,$status ) {

	global $db_con;

	$updatedquery = "UPDATE pedidos SET status = '$status' WHERE id = '$id'";

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." mudou o status do pedido #".$id." para ".numeric_data("status_pedido",$status)." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;
	
	} else {

		return false;

	}

}

function edit_pedido_admin( $id,$status,$estabelecimento ) {

	global $db_con;

	$updatedquery = "UPDATE pedidos SET status = '$status',rel_estabelecimentos_id = '$estabelecimento' WHERE id = '$id'";

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." mudou o status do pedido #".$id." para ".numeric_data("status_pedido",$status)." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;
	
	} else {

		return false;

	}

}

function delete_pedido( $id ) {

	global $db_con;

	$nome = data_info("cidades",$id,"nome");

	if( mysqli_query( $db_con, "DELETE FROM pedidos WHERE id = '$id'") ) {

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

function new_plano( $destaque,$nome,$descricao,$duracao_meses,$duracao_dias,$valor_total,$valor_mensal,$link,$termos,$funcionalidade_marketplace,$funcionalidade_variacao,$funcionalidade_banners,$visible,$status,$ordem,$limite_produtos ) {

	global $db_con;
	global $_SESSION;
	session_id( $token );
	$status = "1";

	if( mysqli_query( $db_con, "INSERT INTO planos (destaque,nome,descricao,duracao_meses,duracao_dias,valor_total,valor_mensal,link,termos,funcionalidade_marketplace,funcionalidade_variacao,funcionalidade_banners,visible,status,ordem,limite_produtos) VALUES ('$destaque','$nome','$descricao','$duracao_meses','$duracao_dias','$valor_total','$valor_mensal','$link','$termos','$funcionalidade_marketplace','$funcionalidade_variacao','$funcionalidade_banners','$visible','$status','$ordem','$limite_produtos');") ) {

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." criou o plano ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;
	
	} else {

		return false;

	}

}

function edit_plano( $id,$destaque,$nome,$descricao,$duracao_meses,$duracao_dias,$valor_total,$valor_mensal,$link,$termos,$funcionalidade_marketplace,$funcionalidade_variacao,$funcionalidade_banners,$visible,$status,$ordem,$limite_produtos ) {

	global $db_con;

	$updatedquery = "UPDATE planos SET nome = '$nome',descricao = '$descricao',duracao_meses = '$duracao_meses',duracao_dias = '$duracao_dias',valor_total = '$valor_total',valor_mensal = '$valor_mensal',link = '$link',termos = '$termos',funcionalidade_marketplace = '$funcionalidade_marketplace',funcionalidade_variacao = '$funcionalidade_variacao',funcionalidade_banners = '$funcionalidade_banners',visible = '$visible',status = '$status',visible = '$visible',ordem = '$ordem',limite_produtos = '$limite_produtos' WHERE id = '$id'";

	if( mysqli_query( $db_con, $updatedquery ) ) {

		if( $destaque ) {
			mysqli_query( $db_con, "UPDATE planos SET destaque = '$destaque' WHERE id = '$id'");
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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." alterou o plano ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;
	
	} else {

		return false;

	}

}

function delete_plano( $id ) {

	global $db_con;
	global $rootpath;

	$nome = data_info("planos", $id, "nome");

	if( mysqli_query( $db_con, "DELETE FROM planos WHERE id = '$id'") ) {

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o plano ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;

	} else {

		return false;

	}

}

function contratar_plano( $eid,$plano,$gateway_transaction,$gateway_ref,$gateway_link ) {

	global $db_con;
	global $_SESSION;

	// Dados

	$rel_planos_id = $plano;

	$afiliado = data_info( "planos", $plano, "afiliado" );
	$rel_estabelecimentos_id = $eid;
	$rel_estabelecimentos_nome = data_info( "estabelecimentos", $eid, "nome" );
	$rel_estabelecimentos_subdominio = data_info( "estabelecimentos", $eid, "subdominio" );
	$nome = data_info( "planos", $plano, "nome" );
	$descricao = data_info( "planos", $plano, "descricao" );
	$duracao_meses = data_info( "planos", $plano, "duracao_meses" );
	$duracao_dias = data_info( "planos", $plano, "duracao_dias" );
	$valor_total = data_info( "planos", $plano, "valor_total" );
	$valor_mensal = data_info( "planos", $plano, "valor_mensal" );
	$link = data_info( "planos", $plano, "link" );
	$termos = mysqli_real_escape_string( $db_con, data_info( "planos", $plano, "termos" ) );

	$funcionalidade_marketplace = data_info( "planos", $plano, "funcionalidade_marketplace" );
	$funcionalidade_variacao = data_info( "planos", $plano, "funcionalidade_variacao" );
	$funcionalidade_banners = data_info( "planos", $plano, "funcionalidade_banners" );
	$limite_produtos = mysqli_real_escape_string( $db_con, data_info( "planos", $plano, "limite_produtos" ) );

	$gateway_payable = date('y-m-d', strtotime('+4 days'));
	$gateway_expiration = date('y-m-d', strtotime('+8 days'));
	$gateway_payment = "0";
	$mode = "1";
	$status = "0";
	$used = "0";
	$excluded = "0";
	$created = date('Y-m-d H:i:s');

	if( mysqli_query( $db_con, "INSERT INTO assinaturas (
		rel_planos_id,
		rel_estabelecimentos_id,
		rel_estabelecimentos_nome,
		rel_estabelecimentos_subdominio,
		afiliado,nome,
		descricao,
		duracao_meses,
		duracao_dias,
		valor_total,
		valor_mensal
		,termos,
		funcionalidade_marketplace,
		funcionalidade_variacao,
		funcionalidade_banners,
		gateway_ref,
		gateway_link,
		gateway_transaction,
		gateway_payable,
		gateway_expiration,
		gateway_payment,
		mode,
		status,
		used,
		created,
		excluded,
		limite_produtos
	) VALUES (
		'$plano',
		'$rel_estabelecimentos_id',
		'$rel_estabelecimentos_nome',
		'$rel_estabelecimentos_subdominio',
		'$afiliado',
		'$nome',
		'$descricao',
		'$duracao_meses',
		'$duracao_dias',
		'$valor_total',
		'$valor_mensal',
		'$termos',
		'$funcionalidade_marketplace',
		'$funcionalidade_variacao',
		'$funcionalidade_banners',
		'$gateway_ref',
		'$gateway_link',
		'$gateway_transaction',
		'$gateway_payable',
		'$gateway_expiration',
		'$gateway_payment',
		'$mode',
		'$status',
		'$used',
		'$created',
		'$excluded',
		'$limite_produtos'
	);") ) {

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." contratou o plano ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return $gateway_link;
	
	} else {

		return false;

	}

}

function update_assinatura( $id,$gateway_sync,$gateway_transaction,$gateway_email,$gateway_expiration,$gateway_payment,$valor_recebido,$status ) {

	global $db_con;

	if( !$valor_recebido ) {
		$valor_recebido = "0.00";
	}

	$updatedquery = "UPDATE assinaturas SET gateway_sync = '$gateway_sync',gateway_transaction = '$gateway_transaction',gateway_email = '$gateway_email',gateway_payment = '$gateway_payment',valor_recebido = '$valor_recebido',status = '$status' WHERE id = '$id'";

	if( mysqli_query( $db_con, $updatedquery ) ) {

		return true;
	
	} else {

		return false;

	}

}

function delete_assinatura( $id ) {

	global $db_con;


	$nome = data_info("assinaturas",$id,"rel_estabelecimentos_id");

	if( mysqli_query( $db_con, "DELETE FROM assinaturas WHERE id = '$id'") ) {

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu a assinatura ".$nome." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;

	} else {

		return false;

	}

}



function remover_assinatura( $id ) {

	global $db_con;

	if( !$valor_recebido ) {
		$valor_recebido = "0.00";
	}

	$updatedquery = "UPDATE assinaturas SET excluded = '1' WHERE id = '$id'";

	if( mysqli_query( $db_con, $updatedquery ) ) {

		return true;
	
	} else {

		return false;

	}

}


function change_assinatura_status( $reference,$status ) {

	global $db_con;

	// Aguardando
	if( $status == "payment_required" OR $status == "payment_in_process" ) {
		$status_pagamento = "0";
	}	

	// Paga
	if( $status == "paid" OR $status == "partially_paid" ) {
		$status_pagamento = "1";
	}	

	// Cancelada
	if( $status == "reverted" OR $status == "partially_reverted" OR $status == "partially_paid" OR $status == "undefined" OR  $status == "expired" ) {
		$status_pagamento = "3";
	}

	if( $status ){

		$edit = mysqli_query( $db_con, "SELECT * FROM assinaturas WHERE gateway_ref = '$reference' LIMIT 1");
		$hasdata = mysqli_num_rows( $edit );
		$data = mysqli_fetch_array( $edit );
		if( $hasdata ) {
			$eid = $data['rel_estabelecimentos_id'];
			mysqli_query( $db_con," UPDATE assinaturas SET gateway_payment='$status',status='$status_pagamento',gateway_transaction = '$transaction' WHERE gateway_ref = '$reference' ");
			atualiza_estabelecimento( $eid, "offline" );
		}

	}

}

function atualiza_estabelecimento( $eid,$mode ) {

	global $db_con;
	global $external_token;
	global $just_url;
	$hoje = date("Y-m-d");
	$remoter = $just_url."/cron.php?acao=sync&token=".$external_token."&eid=".$eid;

	// Remote assinaturas
	if( $mode == "online" ) {
		remoter($remoter);
	}

	// Cancela assinaturas expiradas e define nova assinatura
	mysqli_query( $db_con, "UPDATE assinaturas SET status = '3',used = '3' WHERE expiration < '$hoje' AND status = '1' AND used = '1' AND rel_estabelecimentos_id = '$eid'" );

	// Define assinatura caso não tenha
	$query_semassinatura = mysqli_query( $db_con, "SELECT id,rel_estabelecimentos_id,duracao_dias FROM assinaturas WHERE status = '1' AND used = '1' AND rel_estabelecimentos_id = '$eid' ORDER BY id ASC LIMIT 1" );
	$total_semassinatura = mysqli_num_rows($query_semassinatura);
	if( !$total_semassinatura ) {
		// Procura nova
		$query_novas = mysqli_query( $db_con, "SELECT id,rel_estabelecimentos_id,duracao_dias FROM assinaturas WHERE rel_estabelecimentos_id = '$eid' AND status = '1' AND used = '0' ORDER BY id ASC LIMIT 1" );
		$total_novas = mysqli_num_rows($query_novas);
		while ( $data_novas = mysqli_fetch_array( $query_novas ) ) {
			$naid = $data_novas['id'];
			$days = $data_novas['duracao_dias'];
			$expiration = date('y-m-d', strtotime('+'.$days.' days'));
			mysqli_query( $db_con, "UPDATE assinaturas SET used = '1',expiration = '$expiration' WHERE id = '$naid'" );
		}	
	}

	// Status
	$query_status = mysqli_query( $db_con, "SELECT id,expiration FROM assinaturas WHERE used = '1' AND status = '1' AND rel_estabelecimentos_id = '$eid' LIMIT 1" );
	$data = mysqli_fetch_array( $query_status );
	$has_status = mysqli_num_rows( $query_status );
	if( $has_status ) {
		$status = "1";
	} else {
		$status = "2";
	}
	$funcionalidade_marketplace = "2";
	$funcionalidade_variacao = "2";
	$funcionalidade_banners = "2";
	$expiracao = array();
	if( $has_status ) {

		// Expiração
		$hoje = date("Y-m-d");
		$ultimodia = $data['expiration'];
		$expiracao[] = ceil( (strtotime($ultimodia) - strtotime($hoje)) / (60 * 60 * 24) );
		$query_planos = mysqli_query( $db_con, "SELECT id,duracao_dias FROM assinaturas WHERE status = '1' AND used = '0' AND rel_estabelecimentos_id = '$eid' ORDER BY id DESC" );
		while ( $data_planos = mysqli_fetch_array( $query_planos ) ) {
			$expiracao[] = $data_planos['duracao_dias'];
		}
		$expiracao = array_sum( $expiracao );
		// Funcionalidade marketplace
		$query_status = mysqli_query( $db_con, "SELECT id FROM assinaturas WHERE (used = '0' OR used = '1') AND status = '1' AND rel_estabelecimentos_id = '$eid' AND funcionalidade_marketplace = '1' LIMIT 1" );
		$has_status = mysqli_num_rows($query_status);
		if( $has_status ) {
			$funcionalidade_marketplace = "1";
		}
		// Funcionalidade variação
		$query_status = mysqli_query( $db_con, "SELECT id FROM assinaturas WHERE (used = '0' OR used = '1') AND status = '1' AND rel_estabelecimentos_id = '$eid' AND funcionalidade_variacao = '1' LIMIT 1" );
		$has_status = mysqli_num_rows($query_status);
		if( $has_status ) {
			$funcionalidade_variacao = "1";
		}
		// Funcionalidade banners
		$query_status = mysqli_query( $db_con, "SELECT id FROM assinaturas WHERE (used = '0' OR used = '1') AND status = '1' AND rel_estabelecimentos_id = '$eid' AND funcionalidade_banners = '1' LIMIT 1" );
		$has_status = mysqli_num_rows($query_status);
		if( $has_status ) {
			$funcionalidade_banners = "1";
		}
		// limite de produtos
		$limite_produtos = 0;
		$query_limite = mysqli_query( $db_con, "SELECT limite_produtos FROM assinaturas WHERE (used = '0' OR used = '1') AND status = '1' AND rel_estabelecimentos_id = '$eid'" );
		while ( $data_limite = mysqli_fetch_array( $query_limite ) ) {
			if( $data_limite['limite_produtos'] >= $limite_produtos ) {
				$limite_produtos = $data_limite['limite_produtos'];
			}
		}
		if( $limite_produtos == "" ) {
			$limite_produtos = 0;
		}
	} else {

		$expiracao = "0";

	}
	
	$limite_produtos = 0;
	mysqli_query( $db_con, "UPDATE estabelecimentos SET 
		status = '$status',
		funcionalidade_marketplace = '$funcionalidade_marketplace', 
		funcionalidade_variacao = '$funcionalidade_variacao', 
		funcionalidade_banners = '$funcionalidade_banners', 
		expiracao = '$expiracao',
		limite_produtos = '$limite_produtos' 
		WHERE id = '$eid'
	");

	if( $_SESSION['estabelecimento']['id'] == $eid ) {

		$_SESSION['estabelecimento']['funcionalidade_marketplace'] = $funcionalidade_marketplace;
		$_SESSION['estabelecimento']['funcionalidade_variacao'] = $funcionalidade_variacao;
		$_SESSION['estabelecimento']['funcionalidade_banners'] = $funcionalidade_banners;
		$_SESSION['estabelecimento']['status'] = $status;
		$_SESSION['estabelecimento']['expiracao'] = $expiracao;

	}

}

function sync_assinaturas() {

	global $db_con;

}

function new_voucher( $plano,$descricao ) {

	global $db_con;
	global $_SESSION;

	for(  $x=0; $x<999; $x++ ) {
		$codigo = strtoupper( random_key(4)."-".random_key(4)."-".random_key(4)."-".random_key(4) );
		$naotem = mysqli_num_rows( mysqli_query( $db_con, "SELECT codigo FROM vouchers WHERE codigo = '$codigo'" ) );
		if( $naotem == 0 ) {
			break;
		}
	}

	$status = "1";
	
	if( mysqli_query( $db_con, "INSERT INTO vouchers (rel_planos_id,descricao,codigo,status) VALUES ('$plano','$descricao','$codigo','$status');") ) {

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." criou o voucher ".$descricao." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;
	
	} else {

		return false;

	}

}

function edit_voucher( $id,$plano,$descricao ) {

	global $db_con;

	$updatedquery = "UPDATE vouchers SET rel_planos_id = '$plano',descricao = '$descricao' WHERE id = '$id'";

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou a voucher ".$descricao." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;
	
	} else {

		return false;

	}

}

function delete_voucher( $id ) {

	global $db_con;

	$descricao = data_info("vouchers",$id,"descricao");
	$assinatura = data_info("vouchers",$id,"rel_assinaturas_id");

	if( mysqli_query( $db_con, "DELETE FROM vouchers WHERE id = '$id'") ) {

		mysqli_query( $db_con, "UPDATE assinaturas SET status = '3',used = '2' WHERE id = '$assinatura'");

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." removeu o voucher ".$descricao." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;

	} else {

		return false;

	}

}

function aplicar_voucher( $eid,$voucher ) {

	global $db_con;
	global $_SESSION;

	// Dados

	$voucher_query = mysqli_query( $db_con, "SELECT * FROM vouchers WHERE codigo = '$voucher' AND status = '1' LIMIT 1");
	$has_voucher = mysqli_num_rows( $voucher_query );
	$data_voucher = mysqli_fetch_array( $voucher_query );

	if( $has_voucher && $data_voucher['status'] == "1" ) {

		// Informações
		$plano = $data_voucher['rel_planos_id'];
		$afiliado = data_info( "planos", $plano, "afiliado" );
		$rel_estabelecimentos_id = $eid;
		$nome = data_info( "planos", $plano, "nome" );
		$descricao = data_info( "planos", $plano, "descricao" );
		$duracao_meses = data_info( "planos", $plano, "duracao_meses" );
		$duracao_dias = data_info( "planos", $plano, "duracao_dias" );
		$valor_total = data_info( "planos", $plano, "valor_total" );
		$valor_mensal = data_info( "planos", $plano, "valor_mensal" );
		$link = data_info( "planos", $plano, "link" );
		$termos = mysqli_real_escape_string( $db_con, data_info( "planos", $plano, "termos" ) );
		$limite_produtos = data_info( "planos", $plano, "limite_produtos" );

		// Funcionalidades
		$funcionalidade_marketplace = data_info( "planos", $plano, "funcionalidade_marketplace" );
		$funcionalidade_variacao = data_info( "planos", $plano, "funcionalidade_variacao" );
		$funcionalidade_banners = data_info( "planos", $plano, "funcionalidade_banners" );

		// Status
		$mode = "2";
		$status = "1";
		$used = "0";
		$created = date('Y-m-d H:i:s');

		if( mysqli_query( $db_con, "INSERT INTO assinaturas (rel_planos_id,rel_estabelecimentos_id,afiliado,nome,descricao,duracao_meses,duracao_dias,valor_total,valor_mensal,termos,funcionalidade_marketplace,funcionalidade_variacao,funcionalidade_banners,mode,voucher,status,used,created,limite_produtos) VALUES ('$plano','$rel_estabelecimentos_id','$afiliado','$nome','$descricao','$duracao_meses','$duracao_dias','$valor_total','$valor_mensal','$termos','$funcionalidade_marketplace','$funcionalidade_variacao','$funcionalidade_banners','$mode','$voucher','$status','$used','$created','$limite_produtos');") ) {

			$aid = mysqli_insert_id($db_con);
			mysqli_query( $db_con, "UPDATE vouchers SET rel_assinaturas_id = '$aid',status = '2' WHERE codigo = '$voucher'");

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
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." resgatou o voucher ".$voucher." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			atualiza_estabelecimento($eid,"offline");

			return true;

		} else {

			return false;

		}

	} else {

		return false;

	}

}

function aplicar_plano( $eid,$plano ) {

	global $db_con;
	global $_SESSION;

	// Dados

	$plano_query = mysqli_query( $db_con, "SELECT * FROM planos WHERE id = '$plano' LIMIT 1");
	$has_plano = mysqli_num_rows( $plano_query );
	$data_plano = mysqli_fetch_array( $plano_query );

	if( $has_plano ) {

		// Informações
		$afiliado = data_info( "planos", $plano, "afiliado" );
		$rel_estabelecimentos_id = $eid;
		$nome = data_info( "planos", $plano, "nome" );
		$descricao = data_info( "planos", $plano, "descricao" );
		$duracao_meses = data_info( "planos", $plano, "duracao_meses" );
		$duracao_dias = data_info( "planos", $plano, "duracao_dias" );
		$valor_total = data_info( "planos", $plano, "valor_total" );
		$valor_mensal = data_info( "planos", $plano, "valor_mensal" );
		$link = data_info( "planos", $plano, "link" );
		$termos = mysqli_real_escape_string( $db_con, data_info( "planos", $plano, "termos" ) );
		$limite_produtos = data_info( "planos", $plano, "limite_produtos" );

		// Funcionalidades
		$funcionalidade_marketplace = data_info( "planos", $plano, "funcionalidade_marketplace" );
		$funcionalidade_variacao = data_info( "planos", $plano, "funcionalidade_variacao" );
		$funcionalidade_banners = data_info( "planos", $plano, "funcionalidade_banners" );

		// Status
		$mode = "1";
		$status = "1";
		$gateway_payment = "2";
		$used = "0";
		$created = date('Y-m-d H:i:s');

		if( mysqli_query( $db_con, "INSERT INTO assinaturas (rel_planos_id,rel_estabelecimentos_id,afiliado,nome,descricao,duracao_meses,duracao_dias,valor_total,valor_mensal,termos,funcionalidade_marketplace,funcionalidade_variacao,funcionalidade_banners,mode,status,used,created,limite_produtos) VALUES ('$plano','$rel_estabelecimentos_id','$afiliado','$nome','$descricao','$duracao_meses','$duracao_dias','$valor_total','$valor_mensal','$termos','$funcionalidade_marketplace','$funcionalidade_variacao','$funcionalidade_banners','$mode','$status','$used','$created','$limite_produtos');") ) {

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
				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." resgatou o plano ".$plano." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			atualiza_estabelecimento($eid,"offline");

			return true;

		} else {

			return false;

		}

	} else {

		return false;

	}

}

function edit_assinatura( $id,$funcionalidade_marketplace,$funcionalidade_variacao,$funcionalidade_banners,$expiration,$limite_produtos ) {

	global $db_con;
	$eid = data_info("assinaturas",$id,"rel_estabelecimentos_id");

	$updatedquery = "UPDATE assinaturas SET funcionalidade_marketplace = '$funcionalidade_marketplace', funcionalidade_variacao = '$funcionalidade_variacao', funcionalidade_banners = '$funcionalidade_banners',limite_produtos = '$limite_produtos' WHERE id = '$id'";

	if( mysqli_query( $db_con, $updatedquery ) ) {

		if( $expiration ) {
			mysqli_query( $db_con,"UPDATE assinaturas SET expiration = '$expiration' WHERE id = '$id'" );
		}

		atualiza_estabelecimento($eid,"offline");

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
			log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou a assinatura #".$id." às ".databr( date('Y-m-d H:i:s') ) );

		// / SALVA LOG

		return true;
	
	} else {

		return false;

	}

}

function consulta_pagamento( $gateway_ref ) {

	global $mp_acess_token;

	$url = "https://api.mercadopago.com/merchant_orders?";
	$url .= "access_token=".$mp_acess_token;
	$url .= "&external_reference=".$gateway_ref;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$res = curl_exec($ch);
	$dados = json_decode($res,1);
	// print("<pre>".print_r($dados,true)."</pre>");

	if( isset($dados['elements'][0]) ) {
		$consulta = $dados['elements'][0];
		$retorno['gateway_ref'] = $consulta['external_reference'];
		$retorno['status'] = $consulta['order_status'];
		return $retorno;
	} else {
		return false;
	}

}

function restrict_limite( $eid ) {

	global $db_con;

	$limite = data_info( "estabelecimentos",$eid,"limite_produtos" );
	$query = mysqli_query( $db_con, "SELECT id FROM produtos WHERE rel_estabelecimentos_id = '$eid'");
	$totalprodutos = mysqli_num_rows( $query );

	if( $totalprodutos >= $limite ) {
		header("Location: ".get_just_url()."/painel/produtos/limitado");
	}

}

?>