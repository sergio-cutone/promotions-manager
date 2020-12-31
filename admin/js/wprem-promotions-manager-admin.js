(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

$(document).on("click","#promo-insert", function(){
	promo_container();
});

function promo_container() {
	// let's obtain the values of the fields
	var random = '';
	var expiry = '';
	var id = '';
	if ($('#wp_promo_rnd').is(':checked')){
		random = ' rnd=1';
	}
	if ($('#wp_promo_exp').is(':checked')){
		expiry = ' exp=1';
	}
	if ($("#wp_promo_id").val()){
		id = ' id="'+$("#wp_promo_id").val()+'"';
	}
	window.send_to_editor("[wp_promos"+random+""+expiry+""+id+"]");
}
$( document ).ready(function() {

$('.js-cuztom-colorpicker').wpColorPicker({
    /**
     * @param {Event} event - standard jQuery event, produced by whichever
     * control was changed.
     * @param {Object} ui - standard jQuery UI object, with a color member
     * containing a Color.js object.
     */
    change: function (event, ui) {
        var element = event.target;
        var color = ui.color.toString();
        //console.log(element);
		if( element.id == '_data_text_headline_colour') {
			$('.wprem-h1').css('color', color);
		}
		if( element.id == '_data_text_secondary_colour') {
			$('.wprem-h2').css('color', color);
		}
		if ( element.id == '_data_content_colour') {
			$('.wprem-p').css('color', color);
		}
		if ( element.id == '_data_linktxt_text_colour') {
			$('.wprem-txt-link a').css('color', color);
		}
		if ( element.id == '_data_button_text_colour') {
			$('.wprem-txt-link a').css('color', color);
		}
		if ( element.id == '_data_button_bg') {
			$('.wprem-btn a').css('color', color);
		}
		if ( element.id == '_data_bg_colour') {
			$('.wprem-inner').css('background-color', color);
		}
		if ( element.id == '_data_border_colour') {
			$('.wprem-inner').css('border-color', color);
		}
    }
});
/*
$('#_data_bg_image').wp.media.frame.on('all', function(e) { 
	console.log(e); 
});
*/
$('input#_data_text_headline_size, input#_data_text_secondary_size, input#_data_linktxt_text_size, input#_data_border_radius, input#_data_text_content_size').on('focusout change', function(){
	if ( this.value != '' ) {
		this.value = this.value.replace(/\D/g,'');
		this.value = this.value + 'px';
	}
}).trigger("focusout change");
$('input#_data_text_headline_margin, input#_data_text_headline_padding, input#_data_text_secondary_margin, input#_data_text_secondary_padding, input#_data_margin, input#_data_padding, input#_data_text_content_margin, input#_data_text_content_padding').on('focusout', function(){
	if ( this.value.length >= 3 ) {
		this.value = this.value + 'px';
		this.value = this.value.replace(/ /g,'px ');
		this.value = this.value.replace(/pxpx/g,'px');
	} else if ( this.value != '' ) {
		this.value = this.value.replace(/\D/g,'');
		this.value = this.value + 'px';
	}
}).trigger("focusout");

//Text Alignment
$('select#_data_select_text_position').on('change', function(){
	$('.wprem-content').css('text-align', this.value);
});

//Headline Text
$('input#_data_text_headline').on('input', function(){
	$('.wprem-h1').text( $(this).val() );
});
$('input#_data_text_headline_size').on('keyup change', function(){
	var value = parseFloat(this.value);
	$('.wprem-h1').css('font-size', value);
}).trigger("keyup change");
$('input#_data_text_headline_lineheight').on('keyup change', function(){
	$('.wprem-h1').css('line-height', this.value);
}).trigger("keyup change");
$('input#_data_text_headline_margin').on('keyup change', function(){
	$('.wprem-h1').css('margin', this.value);
}).trigger("keyup change");
$('input#_data_text_headline_padding').on('keyup change', function(){
	$('.wprem-h1').css('padding', this.value);
}).trigger("keyup change");
$('input#_data_text_headline_class').on('focusout change', function(){
	$('.wprem-h1').addClass( $(this).val() );
}).trigger("focusout change");

//Secondary Text
$('input#_data_text_secondary').on('input', function(){
	$('.wprem-h2').text( $(this).val() );
});
$('input#_data_text_secondary_size').on('keyup change', function(){
	var value = parseFloat(this.value);
	$('.wprem-h2').css('font-size', value);
}).trigger("keyup change");
$('input#_data_text_secondary_lineheight').on('keyup change', function(){
	$('.wprem-h2').css('line-height', this.value);
}).trigger("keyup change");
$('input#_data_text_secondary_margin').on('keyup change', function(){
	$('.wprem-h2').css('margin', this.value);
}).trigger("keyup change");
$('input#_data_text_secondary_padding').on('keyup change', function(){
	$('.wprem-h2').css('padding', this.value);
}).trigger("keyup change");
$('input#_data_text_secondary_class').on('focusout change', function(){
	$('.wprem-h2').addClass( $(this).val() );
}).trigger("focusout change");

//Additional Text
$('textarea#_data_text_content').on('input', function(){
	$('.wprem-p').text( $(this).val() );
});
$('input#_data_text_content_size').on('keyup change', function(){
	var value = parseFloat(this.value);
	$('.wprem-p').css('font-size', value);
}).trigger("keyup change");
$('input#_data_text_content_lineheight').on('keyup change', function(){
	$('.wprem-p').css('line-height', this.value);
}).trigger("keyup change");
$('input#_data_text_content_margin').on('keyup change', function(){
	$('.wprem-p').css('margin', this.value);
}).trigger("keyup change");
$('input#_data_text_content_padding').on('keyup change', function(){
	$('.wprem-p').css('padding', this.value);
}).trigger("keyup change");
$('input#_data_text_content_class').on('focusout change', function(){
	$('.wprem-p').addClass( $(this).val() );
}).trigger("focusout change");

//Cover Image Settings
$('select#_data_select_cover_img_position').on('change', function(){
	if(this.value == 'center' ) {
		$('.wprem-inner .row').addClass('wprem-inner-vertical');
		$('.wprem-inner .row').removeClass('wprem-inner-horizontal');
		$('.wprem-inner .row').prepend( $('.wprem-inner .row .col-img') );
	} else if(this.value == 'right' ) {
		$('.wprem-inner .row').removeClass('wprem-inner-vertical');
		$('.wprem-inner .row').addClass('wprem-inner-horizontal');
		$('.wprem-inner .row').prepend( $('.wprem-inner .row .col-promo') );
	} else {
		$('.wprem-inner .row').removeClass('wprem-inner-vertical');
		$('.wprem-inner .row').addClass('wprem-inner-horizontal');
		$('.wprem-inner .row').prepend( $('.wprem-inner .row .col-img') );
	}
});
$('input#_thumbnail_id').on('change', function(){
	//console.log('Changed');
}).trigger("change");
$('select#_data_select_cover_img_align').on('change', function(){
	$('.wprem-feature-image').css('text-align', this.value);
});

//Text Link Settings
$('input#_data_linktxt_io_on').click(function() {
    $(".wprem-txt-link").toggle(this.checked);
});
$('input#_data_linktxt_text').on('input', function(){
	$('.wprem-txt-link a').text( $(this).val() );
});
$('input#_data_linktxt_text_size').on('keyup change', function(){
	var value = parseFloat(this.value);
	$('.wprem-txt-link a').css('font-size', value);
}).trigger("keyup change");
$('select#_data_linktxt_text_bold').on('focusout change', function(){
	$('.wprem-txt-link a').css('font-weight', this.value);
}).trigger("focusout change");
$('select#_data_linktxt_position').on('focusout change', function(){
	$('.wprem-txt-link').css('text-align', this.value);
}).trigger("focusout change");

//Button Settings
$('input#_data_button_io_on').click(function() {
    $(".wprem-btn").toggle(this.checked);
});
$('input#_data_linktxt_text').on('input', function(){
	$('.wprem-btn a').text( $(this).val() );
});
$('select#_data_button_size').on('focusout change', function(){
	$('.wprem-btn a').removeClass('btn-sm btn-md btn-lg');
	$('.wprem-btn a').addClass('btn-' + this.value);
}).trigger("focusout change");
$('select#_data_select_text_bold').on('focusout change', function(){
	$('.wprem-btn a').css('font-weight', this.value);
}).trigger("focusout change");
$('select#_data_select_position').on('focusout change', function(){
	$('.wprem-btn').css('text-align', this.value);
}).trigger("focusout change");
$('input#_data_button_radius_on').click(function() {
	if (this.checked) {
		$('.wprem-btn a').css('border-radius', '0');
	} else {
		$('.wprem-btn a').css('border-radius', '');
	}
});

//Box Style
////BG Image
$('select#_data_select_bg_position').on('focusout change', function(){
	$('.wprem-inner').css('background-position', this.value);
}).trigger("focusout change");
$('select#_data_select_bg_image').on('focusout change', function(){
	$('.wprem-inner').css('background-attachment', this.value);
}).trigger("focusout change");
$('select#_data_select_border_size').on('focusout change', function(){
	$('.wprem-inner').css('border-width', this.value).css('border-style', 'solid');
}).trigger("focusout change");
$('input#_data_border_radius').on('focusout change', function(){
	$('.wprem-inner').css('border-radius', this.value);
}).trigger("focusout change");
$('input#_data_margin').on('focusout change', function(){
	$('.wprem-inner').css('margin', this.value);
}).trigger("focusout change");
$('input#_data_padding').on('focusout change', function(){
	$('.wprem-inner').css('padding', this.value);
}).trigger("focusout change");
$('input#_data_class').on('focusout change', function(){
	$('.wprem-inner').addClass( $(this).val() );
}).trigger("focusout change");

});

})( jQuery );