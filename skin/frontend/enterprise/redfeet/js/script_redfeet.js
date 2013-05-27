jQuery(document).ready(function(){
    /*Início das máscaras*/
    initMasks();

    /*Alteração de título do onestepcheckout*/
    //jQuery('.onestepcheckout-numbers-1').replaceWith("<p class='onestepcheckout-numbers onestepcheckout-numbers-1'>Endereço de Cobrança</p>");

    /*Alterar tipo cadastro pelo grupo onestepcheckout*/
    jQuery('input[id="billing:group_id"]').click(function(){
		changeCustomer(this);
		adaptNameLastnameBilling(this);
    });

    /*Alterar tipo cadastro pelo grupo register*/
    jQuery('input[id="group_id"]').click(function(){
	changeCustomerRegister(this);
	adaptNameLastnameRegister(this);
	
	jQuery('.fields').each(function(){
	    jQuery(this).find('.field').removeClass('first last');
	    decorateGeneric(jQuery(this).find('.field'), ['first','last']);
	});
    });

    /*Set group_id for onestepcheckout*/
    var radios = jQuery('input:radio[name="billing[group_id]"]');
    if(radios.is(':checked') === false) {
        radios.filter('[value=5]').attr('checked', true);
    }
    
    /*Set group_id for register*/
    var radios = jQuery('input:radio[name="group_id"]');
    if(radios.is(':checked') === false) {
        radios.filter('[value=5]').attr('checked', true);
    }

    /*Set last name for wholesale*/
    jQuery('.firstname-wholesale').blur(function(){
        splitting = this.value.split(" ");
        lastname = '';

        jQuery.each(splitting, function(cont, val) {
            if(cont >= 1){
                lastname += val + ' ';
            }
        });
        jQuery('.lastname-wholesale').val(lastname);

    });
    
    jQuery("#onestepcheckout-form").validate({
        onkeyup:false,
        rules: {
           billing_taxvat: {billing_taxvat: true}
        },
        messages: {
           billing_taxvat: { billing_taxvat: 'Documento inválido.'}
        },
        submitHandler:function(form) {
           //alert('ok');
        }
    });
    
    jQuery("#form-validate-register").validate({
        onkeyup:false,
        rules: {
           taxvat: {cpf_cnpj_validate: true}
        },
        messages: {
           taxvat: { cpf_cnpj_validate: 'Documento inválido.'}
        },
        submitHandler:function(form) {
           //alert('ok');
        }
    });
    
    jQuery("#form-validate-edit").validate({
        onkeyup:false,
        rules: {
           taxvat: {cpf_cnpj_validate: true}
        },
        messages: {
           taxvat: { cpf_cnpj_validate: 'Documento inválido.'}
        },
        submitHandler:function(form) {
           //alert('ok');
        }
    });
    
    jQuery('#isento').click(function(){
	    if(jQuery(this).is(':checked')){
		    jQuery('#inscricao_estadual').val("ISENTO");
		    jQuery('#inscricao_estadual').attr('readonly','readonly');
	    }else{
		    jQuery('#inscricao_estadual').val("");
		    jQuery('#inscricao_estadual').removeAttr('readonly','readonly');
	    }
    });
    
    jQuery('input[id="billing:isento"]').click(function(){
	    if(jQuery(this).is(':checked')){
		    jQuery('input[id="billing:inscricao_estadual"]').val("ISENTO");
		    jQuery('input[id="billing:inscricao_estadual"]').attr('readonly','readonly');
	    }else{
		    jQuery('input[id="billing:inscricao_estadual"]').val("");
		    jQuery('input[id="billing:inscricao_estadual"]').removeAttr('readonly','readonly');
	    }
    });

    initRoundedCornersForIE();
});

function initMasks(){

    //jQuery(".mask-postcode").mask("99999-999");
    //jQuery(".mask-telephone").mask("(99) 9999-9999");
    jQuery(".mask-telephone").phoneMask(false);
    jQuery(".mask-postcode").postcodeMask();
    jQuery(".mask-cnpj").mask("99.999.999/9999-99");
    jQuery(".mask-cpf").mask("999.999.999-99");

    jQuery('.register-taxvat').mask("999.999.999-99");
    jQuery('.billing-taxvat').mask("999.999.999-99");

    jQuery('#day').mask('99',   { completed: function(){ jQuery('#month').focus(); } });
    jQuery('#month').mask('99', { completed: function(){ jQuery('#year').focus(); } });
    jQuery('#year').mask("9999");

    jQuery('.dob-day input').mask('99',   { completed: function(){ jQuery('.dob-month input').focus(); } });
    jQuery('.dob-month input').mask('99', { completed: function(){ jQuery('.dob-year input').focus(); } });
    jQuery('.dob-year input').mask("9999");

}

function initRoundedCornersForIE(){
    if(BrowserDetect.browser == 'Explorer') {
	jQuery('.account-create .socourus-btn-submit').append('<div class="ie-button-rounded-corner ie-button-corner-tl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-bl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
	jQuery('.product-view .socourus-button-submit').append('<div class="ie-button-rounded-corner ie-button-corner-tl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-bl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
	jQuery('.cart .socourus-btn-continue').append('<div class="ie-button-rounded-corner ie-button-corner-tl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-bl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
	jQuery('.cart .socourus-btn-checkout').append('<div class="ie-button-rounded-corner ie-button-corner-tl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-bl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
	jQuery('.cart .socourus-btn-postcode').append('<div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
	jQuery('.cart .socourus-btn-discount').append('<div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
	
	jQuery('#onestepcheckout-login-link').append('<div class="ie-button-rounded-corner ie-button-corner-tl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-bl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
	jQuery('.socourus-cart-btn-back').append('<div class="ie-button-rounded-corner ie-button-corner-tl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-bl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
	jQuery('#onestepcheckout-place-order').append('<div class="ie-button-rounded-corner ie-button-corner-tl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-tr">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-bl">&nbsp;</div><div class="ie-button-rounded-corner ie-button-corner-br">&nbsp;</div>');
    }
    jQuery('.gan-top.socourus-menu-bot .gan-plain-list li.level0.first').append('<div class="ie-navigation-rounded-corner ie-navigation-bl-border">&nbsp;</div>');
}

/*Insere o valor de taxvat para o post*/
function adaptTaxvat(field){
    /*Check type of taxvat*/
    jQuery('input[id="billing:taxvat"]').val(field.value);
}

/*Insere o sobrenome do comprador no campo lastname do billing_fields.phtml*/
function adaptWholesaleName(field){
    if(jQuery("input[name='billing[group_id]']:checked").val() == 4){
        splitting = field.value.split(" ");
        lastname = '';
        firstname = splitting[0];

        jQuery.each(splitting, function(cont, val) {
            if(cont >= 1){
                lastname += val + ' ';
            }
        });        
        jQuery('.lastname-wholesale').val(lastname);
    }
}

/*Insere o sobrenome do comprador no campo lastname do register.phtml*/
function adaptWholesaleNameRegister(field){
    if(jQuery("input[name='group_id']:checked").val() == 4){
        splitting = field.value.split(" ");
        lastname = '';
        firstname = splitting[0];

        jQuery.each(splitting, function(cont, val) {
            if(cont >= 1){
                lastname += val + ' ';
            }
        });        
        jQuery('.lastname-wholesale').val(lastname);
    }
}

/*Altera os campos de acordo com o grupo de usuário no billing_fields.phtml*/
function changeCustomer(group){

    if(group.value == 4){

        jQuery('.wholesale').css('display', 'block');

        jQuery('.lastname-wholesale-checkout').css('display', 'none');
        jQuery('.retailer-dob').css('display', 'none');
        jQuery('.retailer-gender').css('display', 'none');
		jQuery('.retailer-rg').css('display', 'none');
        jQuery('.firstname').replaceWith('<label for="billing:firstname" class="firstname">Nome completo comprador<span class="required">*</span></label>');

        jQuery('.billing-taxvat').mask('99.999.999/9999-99');
        jQuery('.billing-taxvat').val('');

        jQuery('.taxvat-label').replaceWith('<label class="required taxvat-label" for="billing_taxvat">CNPJ<span class="required">*</span></label>');
	
	// adicionando classe para estilização conforme o grupo selecionado
	jQuery('.retailer').addClass('wholesale-fields');

        //jQuery('.onestepcheckout-numbers-1').replaceWith("<p class='onestepcheckout-numbers onestepcheckout-numbers-1'>Cadastro Pessoa Jurídica</p>");

    }else{

        jQuery('.wholesale').css('display', 'none');

        jQuery('.lastname-wholesale-checkout').css('display', 'block');
        jQuery('.retailer-dob').css('display', 'block');
        jQuery('.retailer-gender').css('display', 'block');
	jQuery('.retailer-rg').css('display', 'block');
        jQuery('.firstname').replaceWith('<label for="billing:firstname" class="firstname">Nome<span class="required">*</span></label>');

        jQuery('.billing-taxvat').mask('999.999.999-99');
        jQuery('.billing-taxvat').val('');

        jQuery('.taxvat-label').replaceWith('<label class="required taxvat-label" for="billing_taxvat">CPF<span class="required">*</span></label>');
	
	// removendo classe para estilização conforme o grupo selecionado
	jQuery('.retailer').removeClass('wholesale-fields');

        //jQuery('.onestepcheckout-numbers-1').replaceWith("<p class='onestepcheckout-numbers onestepcheckout-numbers-1'>Cadastro Pessoa Física</p>");

    }

}

/*Altera os campos de acordo com o grupo de usuário no register.phtml*/
function changeCustomerRegister(group){
	
    if(group.value == 4){
		
		jQuery('.wholesale').css('display', 'block');
		jQuery('.register-taxvat').mask('99.999.999/9999-99');
	
		jQuery('.field-dob-register').css('display', 'none');
		jQuery('.field-gender-register').css('display', 'none');
		jQuery('.field-rg-register').css('display', 'none');
		jQuery('.taxvat-register-label').replaceWith('<label class="required taxvat-register-label" for="taxvat"><em>*</em>CNPJ</label>');
		
		jQuery('.li-fields-hide-marker').css('display', 'none');
		jQuery('.field-cpf-cnpj-register').prependTo(jQuery('.li-fields-relocate-marker'));
		
		jQuery("#firstname, #lastname, #taxvat, #rg, #day, #month, #year, #gender").val('');

    }else{

		jQuery('.wholesale').css('display', 'none');
		jQuery('.register-taxvat').mask('999.999.999-99');
	
		jQuery('.field-dob-register').css('display', 'block');
		jQuery('.field-gender-register').css('display', 'block');
		jQuery('.field-rg-register').css('display', 'block');
		jQuery('.taxvat-register-label').replaceWith('<label class="required taxvat-register-label" for="taxvat"><em>*</em>CPF</label>');
		
		jQuery('.field-cpf-cnpj-register').prependTo(jQuery('.li-fields-cpf'));
		jQuery('.li-fields-hide-marker').css('display', 'block');
		
		jQuery("#razao_social, #nome_fantasia, #taxvat, #inscricao_estadual").val('');
    }

}

/*Altera os campos para nome e sobrenome de acordo com o grupo de usuário no billing_fields.phtml*/
function adaptNameLastnameBilling(group){
    if(group.value == 4){

        jQuery('.billing-name').empty();

        html = '<div class="input-box input-firstname firstname-wholesale-checkout">';
        html += '<label class="firstname" for="billing:firstname">Nome comprador<span class="required">*</span></label><br>';
        html += '<input type="text" onblur="adaptWholesaleName(this)" id="billing:firstname" name="billing[firstname]" class="required-entry input-text firstname-wholesale">';
        html += '</div><div class="input-box input-lastname lastname-wholesale-checkout"><input type="text" id="billing:lastname" name="billing[lastname]" class="required-entry input-text lastname-wholesale"></div>';

        jQuery('.billing-name').append(html);

    }else{

        jQuery('.billing-name').empty();

        html = '<div class="input-box input-firstname">';
        html += '<label class="firstname" for="billing:firstname">Nome<span class="required">*</span></label><br>';
        html += '<input type="text" id="billing:firstname" name="billing[firstname]" class="required-entry input-text">';
        html += '</div>';

        html += '<div class="input-box input-lastname">';
        html += '<label class="lastname" for="billing:lastname">Sobrenome <span class="required">*</span></label><br>';
        html += '<input type="text" id="billing:lastname" name="billing[lastname]" class="required-entry input-text">';
        html += '</div>';

        jQuery('.billing-name').append(html);

    }
}

/*Altera os campos para nome e sobrenome de acordo com o grupo de usuário no register.phtml*/
function adaptNameLastnameRegister(group){
    if(group.value == 4){

        jQuery('.register-name').empty();

	html = '<div class="customer-name customer-name-wholesale">';
    html += '   <div class="field name-firstname">';
    html += '       <label class="required" for="firstname"><em>*</em>Nome Comprador</label>';
	html += '      <div class="input-box input-box-firstname">';
	html += '          <input type="text" class="input-text required-entry" onblur="adaptWholesaleNameRegister(this)" title="Nome Comprador" value="" name="firstname" id="firstname" autocomplete="off" />';
	html += '      </div>';
    html += '   </div>';

    html += '   <div class="field name-lastname">';
    html += '       <label class="required" for="lastname"><em>*</em>Sobrenome</label>';
    html += '       <div class="input-box input-box-lastname">';
    html +='            <input type="text" class="input-text required-entry lastname-wholesale" title="Último nome" value="" name="lastname" id="lastname" autocomplete="off" />';
    html += '       </div>';
    html += '   </div>';
	html += '</div>';
	
	//jQuery('.register-taxvat').mask('99.999.999/9999-99');
    }else{

        jQuery('.register-name').empty();

	html = '<div class="customer-name">';

	html += '<div class="field name-firstname">';
        html += '<label class="required" for="firstname"><em>*</em>Primeiro nome</label>';
        html += '<div class="input-box">';
        html += '<input type="text" class="input-text required-entry" title="Primeiro nome" value="" name="firstname" id="firstname" autocomplete="off">';
	html += '</div>';
	html += '</div>';

        html += '<div class="field name-lastname">';
        html += '<label class="required" for="lastname"><em>*</em>Último nome</label>';
        html += '<div class="input-box">';
	html += '<input type="text" class="input-text required-entry" title="Último nome" value="" name="lastname" id="lastname" autocomplete="off">';
        html += '</div>';
	html += '</div>';

	html += '</div>';
	//jQuery('.register-taxvat').mask('999.999.999-99');

    }
    jQuery('.register-name').append(html);
    jQuery('.register-name #firstname').val(jQuery('#firstname-value').val());
    jQuery('.register-name #lastname').val(jQuery('#lastname-value').val());
    jQuery('.register-taxvat').val(jQuery('#taxvat-value').val());
    jQuery('.mask-rg').val(jQuery('#rg-value').val());
    
    
    
    
}

/*Limpar form*/
function resetForm(id) {
     jQuery(':input','#'+id)
     .not(':button, :submit, :reset, :hidden')
     .val('')
     .removeAttr('checked')
     .removeAttr('selected');
}

function recuperaEstadoPelaSigla(siglaEstado) {
    var estado = '';
    switch(siglaEstado) {
	case 'AC': estado = 'Acre'; break;
	case 'AL': estado = 'Alagoas'; break;
	case 'AP': estado = 'Amapá'; break;
	case 'AM': estado = 'Amazonas'; break;
	case 'BA': estado = 'Bahia'; break;
	case 'CE': estado = 'Ceará'; break;
	case 'DF': estado = 'Distrito Federal'; break;
	case 'ES': estado = 'Espírito Santo'; break;
	case 'GO': estado = 'Goiás'; break;
	case 'MA': estado = 'Maranhão'; break;
	case 'MT': estado = 'Mato Grosso'; break;
	case 'MS': estado = 'Mato Grosso do Sul'; break;
	case 'MG': estado = 'Minas Gerais'; break;
	case 'PA': estado = 'Pará'; break;
	case 'PB': estado = 'Paraíba'; break;
	case 'PR': estado = 'Paraná'; break;
	case 'PE': estado = 'Pernambuco'; break;
	case 'PI': estado = 'Piauí'; break;
	case 'RJ': estado = 'Rio de Janeiro'; break;
	case 'RN': estado = 'Rio Grande do Norte'; break;
	case 'RS': estado = 'Rio Grande do Sul'; break;
	case 'RO': estado = 'Rondônia'; break;
	case 'RR': estado = 'Roraima'; break;
	case 'SC': estado = 'Santa Catarina'; break;
	case 'SP': estado = 'São paulo'; break;
	case 'SE': estado = 'Sergipe'; break;
	case 'TO': estado = 'Tocantins'; break;
    }
    return estado;
}

/*Validação de campo documento para onestepcheckout*/
  
jQuery.validator.addMethod("billing_taxvat", function(value, element) {

  // remove pontuações
  value = value.replace('.','');
  value = value.replace('.','');
  value = value.replace('-','');
  value = value.replace('/','');

  if (value.length <= 11) {
    if(jQuery.validator.methods.cpf.call(this, value, element)){
      return true;
    } else {
        element.value = '';
      this.settings.messages.billing_taxvat.billing_taxvat = "Informe um CPF válido.";
    }

  }
  else if (value.length <= 14) {
    if(jQuery.validator.methods.cnpj.call(this, value, element)){
      return true;
    } else {
        element.value = '';
      this.settings.messages.billing_taxvat.billing_taxvat = "Informe um CNPJ válido.";
    }

  }

  return false;

}, "Informe um documento válido.");

/*Validação de campo documento para register e edit*/

jQuery.validator.addMethod("cpf_cnpj_validate", function(value, element) {

  // remove pontuações
  value = value.replace('.','');
  value = value.replace('.','');
  value = value.replace('-','');
  value = value.replace('/','');

  if (value.length <= 11) {
    if(jQuery.validator.methods.cpf.call(this, value, element)){
      return true;
    } else {
        element.value = '';
      this.settings.messages.taxvat.cpf_cnpj_validate = "Informe um CPF válido.";
    }

  }
  else if (value.length <= 14) {
    if(jQuery.validator.methods.cnpj.call(this, value, element)){
      return true;
    } else {
        element.value = '';
      this.settings.messages.taxvat.cpf_cnpj_validate = "Informe um CNPJ válido.";
    }

  }

  return false;

}, "Informe um documento válido.");

// validação do CPF
jQuery.validator.addMethod("cpf", function(value, element) {
   value = jQuery.trim(value);
	
	value = value.replace('.','');
	value = value.replace('.','');
	cpf = value.replace('-','');
	while(cpf.length < 11) cpf = "0"+ cpf;
	var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
	var a = [];
	var b =0;
	var c = 11;
	for (i=0; i<11; i++){
		a[i] = cpf.charAt(i);
		if (i < 9) b += (a[i] * --c);
	}
        if ((x = b % 11) < 2) { a[9] = 0; } else { a[9] = 11-x; }          
	b = 0;
	c = 11;
	for (y=0; y<10; y++) b += (a[y] * c--);
	if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }
	
	var retorno = true;
	if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) retorno = false;
	
	return this.optional(element) || retorno;

}, "Informe um CPF válido."); 


// validação do CNPJ
jQuery.validator.addMethod("cnpj", function(cnpj, element) {
   cnpj = jQuery.trim(cnpj);// retira espaços em branco
   // DEIXA APENAS OS NÚMEROS
   cnpj = cnpj.replace('/','');
   cnpj = cnpj.replace('.','');
   cnpj = cnpj.replace('.','');
   cnpj = cnpj.replace('-','');
 
   var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
   digitos_iguais = 1;
 
   if (cnpj.length < 14 && cnpj.length < 15){
      return false;
   }
   for (i = 0; i < cnpj.length - 1; i++){
      if (cnpj.charAt(i) != cnpj.charAt(i + 1)){
         digitos_iguais = 0;
         break;
      }
   }
 
   if (!digitos_iguais){
      tamanho = cnpj.length - 2;
      numeros = cnpj.substring(0,tamanho);
      digitos = cnpj.substring(tamanho);
      soma = 0;
      pos = tamanho - 7;
 
      for (i = tamanho; i >= 1; i--){
         soma += numeros.charAt(tamanho - i) * pos--;
         if (pos < 2){
            pos = 9;
         }
      }
      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
      if (resultado != digitos.charAt(0)){
         return false;
      }
      tamanho = tamanho + 1;
      numeros = cnpj.substring(0,tamanho);
      soma = 0;
      pos = tamanho - 7;
      for (i = tamanho; i >= 1; i--){
         soma += numeros.charAt(tamanho - i) * pos--;
         if (pos < 2){
            pos = 9;
         }
      }
      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
      if (resultado != digitos.charAt(1)){
         return false;
      }
      return true;
   }else{
      return false;
   }
}, "Informe um CNPJ válido."); // Mensagem padrão