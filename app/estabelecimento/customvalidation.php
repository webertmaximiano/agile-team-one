  <?php
  form.validate({
      focusInvalid: true,
      invalidHandler: function() {
      },
      errorPlacement: function errorPlacement(error, element) { element.after(error); },
      rules:{

		<?php
		$variacao = json_decode( $data_content['variacao'], TRUE );
		$validacao = "";
		for ( $x=0; $x < count( $variacao ); $x++ ){
			$validacao .= "variacao[".$x."]: {\n";
			if( $variacao[$x]['escolha_minima'] >= "1" ) {
				$validacao .= "required: true,\n";
			}
			$validacao .= "minlength: ".htmljson( $variacao[$x]['escolha_minima'] ).",\n";
			$validacao .= "maxlength: ".htmljson( $variacao[$x]['escolha_maxima'] )."\n";
			$validacao .= "}\n\n,";
		}
		$validacao = trim( $validacao, "," );
		echo $validacao;
		?>

      },
      messages:{

		<?php
		$variacao = json_decode( $data_content['variacao'], TRUE );
		$validacao = "";
		for ( $x=0; $x < count( $variacao ); $x++ ){
			$validacao .= "variacao[".$x."]: {\n";
			if( $variacao[$x]['escolha_minima'] >= "1" ) {
				$validacao .= "required: 'Esse campo é obrigatório',\n";
			}
			$validacao .= "minlength: 'Você deve escolher ao menos ".htmljson( $variacao[$x]['escolha_minima'] )." itens',\n";
			$validacao .= "maxlength: 'Você deve escolher no máximo ".htmljson( $variacao[$x]['escolha_maxima'] )." itens'\n";
			$validacao .= "}\n\n,";
		}
		$validacao = trim( $validacao, "," );
		echo $validacao;
		?>

      }
  });