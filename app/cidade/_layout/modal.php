<div id="modalalerta" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        
      </div>

    </div>

  </div>

</div>

<div id="modalcarrinho" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="lni lni-close"></i></button>
      </div>

      <div class="modal-body">
        
      </div>

    </div>

  </div>

</div>

<div id="modalalerta" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        
      </div>

    </div>

  </div>

</div>

<div id="modalcidade" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="lni lni-close"></i></button>
      </div>

      <div class="modal-body">
        
        <div class="cidade-box">

          <div class="row">

            <div class="col-md-12">

              <span class="text-escolha">
                Escolha a sua cidade
              </span>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="holder-noventa">

                <div class="form-field-default">

                    <div class="fake-select">
                      <i class="lni lni-chevron-down"></i>
                      <select id="input-estado-choose" name="estado">

                          <option value="">Estado</option>
                          <?php 
                          $query_estados = 
                          "
                          SELECT estados.id,estados.nome, count(*) as total, estados.nome as estado_nome, estados.id as estado_id
                          FROM estados AS estados 

                          INNER JOIN cidades AS cidades 
                          ON cidades.estado = estados.id 

                          INNER JOIN estabelecimentos AS estabelecimentos 
                          ON estabelecimentos.cidade = cidades.id 

                          WHERE 
                          estabelecimentos.funcionalidade_marketplace = '1' AND 
                          estabelecimentos.status = '1' AND 
                          estabelecimentos.status_force != '1' AND 
                          estabelecimentos.excluded != '1' 

                          GROUP BY estados.id 
                          ORDER BY estados.nome ASC
                          ";
                          $query_estados = mysqli_query( $db_con, $query_estados );
                          while ( $data_estado = mysqli_fetch_array( $query_estados ) ) {
                          ?>

                            <option <?php if( $_POST['estado'] == $data_estado['estado_id'] ) { echo "SELECTED"; }; ?> value="<?php echo $data_estado['estado_id']; ?>"><?php echo $data_estado['estado_nome']; ?></option>

                          <?php } ?>

                      </select>
                      <div class="clear"></div>
                  </div>

                </div>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="holder-noventa">

                <div class="form-field-default">

                    <div class="fake-select">
                      <i class="lni lni-chevron-down"></i>
                      <select id="input-cidade-choose" name="cidade">

                        <option value="">Cidade</option>

                      </select>
                      <div class="clear"></div>
                  </div>

                </div>

            </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field form-field-icon form-field-entrar">

                <button>
                  <span>Quero comprar</span>
                  <i class="icone icone-sacola"></i>
                </button>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>

<script>

$( "#input-estado-choose" ).change(function() {
  var estado = $("#input-estado-choose").children("option:selected").val();
  $("#input-cidade-choose").html("<option>-- Carregando cidades --</option>");
  $("#input-cidade-choose").load("<?php echo $app['url']; ?>/_core/_ajax/cidades_filled.php?estado="+estado);
});


$( ".form-field-entrar button" ).click(function() {
  var url = $("#input-cidade-choose").val();
  document.location.href = url;
});

</script>