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
	
        
        jQuery( '.countdown_amount').css("background", "-moz-linear-gradient(top,  "+color_up+" 50%, "+color_down+" 50%)"); /* FF3.6+ */
        jQuery( '.countdown_amount').css("background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,"+color_up+"), color-stop(50%,"+color_down+"))"); /* Chrome,Safari4+ */
        jQuery( '.countdown_amount').css("background", "-webkit-linear-gradient(top,  "+color_up+" 50%,"+color_down+" 50%)"); /* Chrome10+,Safari5.1+ */
        jQuery( '.countdown_amount').css("background", "-o-linear-gradient(top,  "+color_up+" 50%,"+color_down+" 50%)"); /* Opera 11.10+ */
        jQuery( '.countdown_amount').css("background", "-ms-linear-gradient(top,  "+color_up+" 50%,"+color_down+" 50%)"); /* IE10+ */
        jQuery( '.countdown_amount').css("background", "linear-gradient(to bottom,  "+color_up+" 50%,"+color_down+" 50%)"); /* W3C */
        jQuery( '.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+color_up+"', endColorstr='"+color_down+"',GradientType=0 )"); /* IE6-9 */
    
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