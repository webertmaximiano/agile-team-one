$( document ).ready(function() {

	// FITS
		function minfit() {
			var screen_height = $(window).height();
			// MINFIT
			var header_height = $(".header").height();
			var footer_height = $(".footer").height();
			var minfit = screen_height - ( header_height + footer_height );
			$(".minfit").css("min-height",minfit);
		}
		minfit();
		// FULL
		function fullfit() {
			var screen_height = $(window).height();
			$(".fullfit").css("min-height",screen_height).animate(500);
		}
		fullfit();
		$(window).on('resize', function(){
			minfit();
			fullfit();
		});

	// SIDEBARS

	if( $.isFunction( $.sidr ) ) {

		$('.sidrLeft').sidr({
			side: 'left',
			name: 'sidrLeft',
			source: '#sidebarLeft',
			renaming: false
	    });

	    $('.sidrRight').sidr({
			side: 'right',
			name: 'sidrRight',
			source: '#sidebarRight',
			renaming: false
	    });

	    $( "body > *:not(.sidr)" ).click(function() {
		    $.sidr('close', 'sidrLeft')
		    $.sidr('close', 'sidrRight');
	    });

		// $('body').swipe( {
	 //        swipeLeft: function () {
	 //            $.sidr('close', 'sidrLeft');
	 //        },
	 //        swipeRight: function () {
	 //            $.sidr('close', 'sidrRight');
	 //        },
	 //        threshold: 45
	 //    });

	}

});

// MASK

$( document ).ready(function() {

	$(".maskdate").mask("99/99/9999",{placeholder:""});
	$(".maskrg").mask("99999999-99",{placeholder:""});
	$(".maskcpf").mask("999.999.999-99",{placeholder:""});
	$(".maskcnpj").mask("99.999.999/9999-99",{placeholder:""});
	$(".maskcel").mask("(99) 99999-9999");
	$(".maskcep").mask("99999-999");
	$(".dater").mask("99/99/9999");
	$(".masktime").mask("99:99:99");
	$(".masktimemin").mask("99:99");
	$(".masknumber").mask("99");
	$(".maskmoney").maskMoney({
	    prefix: "R$ ",
	    decimal: ",",
	    thousands: "."
	});

});

// Clean pass autofill

$('#the_form input[type=password]').val('')

// Call colorpicker

$('.thecolorpicker').spectrum({
	type: "text",
	showPalette: "false",
	showInitial: "true",
	showAlpha: "false",
	cancelText: "Cancelar",
	chooseText: "Escolher"
});
  
// Campos dependentes

$( ".elemento-dependente" ).fadeOut(0);

function campo_dependente(dependente) {

	$(".elemento-dependente[dependente="+dependente+"]").fadeOut(0);
	var thisvalue = $( dependente ).val();
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value="+thisvalue+"]").fadeIn(0);
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value_2="+thisvalue+"]").fadeIn(0);
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value_3="+thisvalue+"]").fadeIn(0);
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value_4="+thisvalue+"]").fadeIn(0);
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value_5="+thisvalue+"]").fadeIn(0);

}

$( ".campo-dependente" ).trigger("change");

$(".autocompleter").keydown(function(e) {

	var consulta = $(this).val();
	var url = $(this).attr("completer_url");
	var bindfield = $(this).attr("completer_field");
	$( this ).autocomplete({
	  serviceUrl: url+'?consulta='+consulta,
	  matchContains: true,
	  lookupLimit: 10,
	  tabDisabled: false,
	  autoSelectFirst: true,
	  onSelect: function (suggestion) {
	    $("input[name="+bindfield+"]").attr("value",suggestion.data);
	    $( "input[name="+bindfield+"]" ).trigger("change");
	    $( this ).addClass("autocomplete-selected");
	  }
	});
	var code = e.keyCode || e.which;
	if (code != 9 && code != 13) { 
	  if ( $( this ).hasClass( "autocomplete-selected" ) ) {
	    $( this ).val("");
	    $( this ).removeClass("autocomplete-selected");
	    $("input[name="+bindfield+"]").attr("value","");
	  }
	}

});

function subdomain(element) {
	var re = /[^a-zA-Z0-9\-]/;
    var strreplacer = $(element).val();
    strreplacer = strreplacer.replace(re, '');
    $(element).val( strreplacer );
}

$(".subdomain").keyup(function(e) {
	var re = /[^a-zA-Z0-9\-]/;
    var strreplacer = $(this).val();
    strreplacer = strreplacer.replace(re, '');
    $(this).val( strreplacer );
});

$(".form-field-radio").click(function() {
	$(this).children('input').prop('checked',true);
});

$( ".elemento-oculto" ).fadeOut(0);

$(".form-field-radio").click(function() {
	var showlement = $(this).children('input').attr("element-show");
	var hidelement = $(this).children('input').attr("element-hide");
	$( showlement ).fadeIn(100);
	$( hidelement ).fadeOut(100);
	$(this).children('input').prop('checked',true);
});

$(document).on('submit','#the_form',function(){
   $(".processing").show();
});

$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    language: "br"
});

InstantClick.init();

function imprimir() {

	$(".comprovante").printThis();

}