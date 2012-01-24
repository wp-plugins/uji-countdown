jQuery(function($) {
	init();
	jQuery('#ujic_txt').change(function() {
		if(jQuery('#ujic_txt').is(":checked")){
			jQuery('.countdown_txt').css("display","block");
		}else{
			jQuery('.countdown_txt').css("display","none");	
		}
	});
	
	$('#ujic_txt_sz').change(function() {
		var size = jQuery('#ujic_txt_sz').val();
		jQuery( '.countdown_amount').css("font", size+"px/1.5 'Open Sans Condensed',sans-serif");
	});	
	
});

function init(){
		/*-----box color---*/
		var color_down = jQuery( '#ujic_col_dw' ).val();
		var color_up = jQuery( '#ujic_col_up' ).val();			
		jQuery( '.countdown_amount').css("background-image", "linear-gradient(bottom, "+color_down+" 50%, "+color_up+" 50%)");
		jQuery( '.countdown_amount').css("background-image", "-o-linear-gradient(bottom, "+color_down+" 50%, "+color_up+" 50%)");
		jQuery( '.countdown_amount').css("background-image", "-moz-linear-gradient(bottom, "+color_down+" 50%, "+color_up+" 50%)");
		jQuery( '.countdown_amount').css("background-image", "-webkit-linear-gradient(bottom, "+color_down+" 50%, "+color_up+" 50%)");
		jQuery( '.countdown_amount').css("background-image", "-ms-linear-gradient(bottom, "+color_down+" 50%, "+color_up+" 50%)");
		jQuery( '.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.Gradient(startColorstr='"+color_down+"', endColorstr='"+color_up+"')");
		/*-----text color---*/
		var color_txt = jQuery( '#ujic_col_txt' ).val();
		var color_sw = jQuery( '#ujic_col_sw' ).val();	
		
		jQuery('.countdown_amount').css("color", color_txt);
		jQuery('.countdown_amount').css("text-shadow",'1px 1px 1px ' + color_sw);
		/*-----display time label text---*/
		if(jQuery('#ujic_txt').is(":checked")){
			
			jQuery('.countdown_txt').css("display","block");
		}else{
			jQuery('.countdown_txt').css("display","none");	
		}
		/*-----font size---*/
		var size = jQuery('#ujic_txt_sz').val();
		jQuery( '.countdown_amount').css("font", size+"px/1.5 'Open Sans Condensed',sans-serif");
}