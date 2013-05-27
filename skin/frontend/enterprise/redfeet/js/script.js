// SessionCart
function openPopupCart(add)
{
	var qty = jQuery('.carrinho .qty-items');
	var price = jQuery('.carrinho .price');
	var popup = jQuery('.carrinho .total-items').children('a').next('.popup');

	jQuery.ajax({
		url: '/checkout/cart/popupajaxcart/',
		success: function(request) {
			// Adicionando produtos na popup
			jQuery('.total-items .popup').html(request);
			
			var input_qty = jQuery('input[name="total_qty_cart"]').val();
			var input_price = jQuery('input[name="total_price_cart"]').val();
			
			qty.html(input_qty);
			price.html(input_price);
		}
	});

	if(add == true) {
		// Fechando popup caso estaja aberta, caso tenha sido adicionado um novo produto
		if(popup.is(':visible')) {
			closePopupCart(qty, popup);
		}
	}	
	
	// Abrindo a popup
	popup.slideDown('slow', function() {
		qty.removeClass('no-display');
	});
	
	// Fechando a popup ao retirar o mouse
	jQuery('.total-items').mouseleave(function() {			
		closePopupCart(qty, popup);
	});
}

function closePopupCart(qty, popup)
{
	// Fechando a popup
	popup.slideUp('slow', function() {
		qty.addClass('no-display');
	});
}

function startSlides() {
	jQuery("#slideshow").css("overflow", "hidden");
	
	jQuery("ul#slides").cycle({
		fx: 'fade',
		pause: 1,
		pager: '.home-cycle-nav'
	});	
	
	// Slide do historico de produtos ja visualizados
	jQuery('#js-products-grid').jcarousel({
		visible: 1,
		auto: 1,
		scroll:1,
		wrap: 'last',
		initCallback: initjCarouselCallback
    });
}

function initjCarouselCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });
 
    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });
 
    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};

function onepageNextStep(step)
{
	jQuery('.box-passos li').removeClass('active');
	jQuery('.box-passos li.step-' + step).addClass('active');
	return true;
}

function openBuyPopup(url, dialog)
{
	var popup = '#' + dialog;	
	var string = jQuery(popup).html().length;
	
	if(string > 0) {
	
		jQuery(popup).dialog({
			minWidth: 450,
			maxWidth: 450,
			minHight: 315,
			maxHight: 315,				
			create: function(event, ui) {
				jQuery(this).removeClass('no-display');
			}
		});
		
	} else {
		
		jQuery.ajax({
			url: url,
			success: function() {
				openPopupCart(true);
			}
		});		
	}
}

function ajaxLoadOverlay(){
       jQuery("#spinner").bind("ajaxSend", function() {
               jQuery(this).show();
               jQuery("#modal-overlay").css("opacity", 0.1);
               jQuery("#modal-overlay").fadeIn(150);
       }).bind("ajaxStop", function() {
               jQuery(this).hide();
               jQuery("#modal-overlay").fadeOut(150);
               
       }).bind("ajaxError", function() {
               jQuery(this).hide();
       });
}
/*
function loadPage(f) {
	var cor = f.cor.value;
	var tam = f.tam.value;
	var qty = f.qtyy.value;
	
	jQuery.ajax({
		type: "POST",
		url: 'sessioncart/loadfields/getFields',
		data: {
			cor : cor, tam : tam, qty : qty
		},
		beforeSend: function() {
			ajaxLoadOverlay();
			//html = '<img src="../../skin/frontend/default/redfeet/images/ajax-loader.gif" />';
			//html += 'Carregando...';
			//jQuery('#loader').html(html);
		},
		complete: function() {				
			html = '';
			jQuery('#loader').html(html);
		},
		success: function(resp) {
			jQuery('loadFields').html(resp);
		}
	});
}
*/

function loadMessage()
{	
	//jQuery('#id_btn').click(function() {
		jQuery.ajax({
			type: "POST",
			url: '/sessioncart/loadfields/getMessageError',
			success: function(response) {
				//var json = jQuery.parseJSON(response);
				//alert(json);
				if(response != "") {
					//	window.location.reload( true );
					html = "<ul class='messages'><li class='error-msg'>";
					html += response;
					html += "</li></ul>";
					jQuery('#messages_product_view').html (html);
				} else {
					html = "";
					jQuery('#messages_product_view').html (html);
				}
			}
		});
	//});
}