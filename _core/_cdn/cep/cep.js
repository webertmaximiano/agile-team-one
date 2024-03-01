$(document).ready(function() {

    $("input[name=endereco_cep]").keyup(function() {

        var cep = $(this).val().replace(/\D/g, '');

        if (cep != "") {

            var validacep = /^[0-9]{8}$/;

            if(validacep.test(cep)) {

                $("input[name=endereco_rua]").val("...");
                $("input[name=endereco_bairro]").val("...");

                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        
                        $("input[name=endereco_rua]").val(dados.logradouro);
                        $("input[name=endereco_bairro]").val(dados.bairro);

                    }
                    else {
                        $("input[name=endereco_rua]").val("");
                        $("input[name=endereco_bairro]").val("");
                    }
                });
            }
            else {
            }

        }
        else {
        }
        
    });

});