if ( jQuery().ColorPicker && jQuery( '#ujic_col_dw, #ujic_col_up' ).length ) {

	var color = jQuery('#ujic_col_dw' ).val();
	 jQuery( '#ujic_col_dw, #colorSelector' ).ColorPicker({
					color: color,					
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
						jQuery( '#ujic_col_dw' ).attr( 'value', '#' + hex );
						var color_up = jQuery( '#ujic_col_up' ).val();
			
						jQuery( '.countdown_amount').css("background-image", "linear-gradient(bottom, #"+hex+" 50%, "+color_up+" 50%)");
						jQuery( '.countdown_amount').css("background-image", "-o-linear-gradient(bottom, #"+hex+" 50%, "+color_up+" 50%)");
						jQuery( '.countdown_amount').css("background-image", "-moz-linear-gradient(bottom, #"+hex+" 50%, "+color_up+" 50%)");
						jQuery( '.countdown_amount').css("background-image", "-webkit-linear-gradient(bottom, #"+hex+" 50%, "+color_up+" 50%)");
						jQuery( '.countdown_amount').css("background-image", "-ms-linear-gradient(bottom, #"+hex+" 50%, "+color_up+" 50%)");
						jQuery( '.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.Gradient(startColorstr='#"+hex+"', endColorstr='"+color_up+"')");					
						
					}
	 });
	 var color2 = jQuery('#ujic_col_up' ).val();
	 jQuery( '#ujic_col_up, #colorSelector2' ).ColorPicker({
					color: color2,					
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#colorSelector2 div').css('backgroundColor', '#' + hex);
						jQuery( '#ujic_col_up' ).attr( 'value', '#' + hex );
						var color_down = jQuery( '#ujic_col_dw' ).val();
			
						jQuery( '.countdown_amount').css("background-image", "linear-gradient(bottom, "+color_down+" 50%, #"+hex+" 50%)");
						jQuery( '.countdown_amount').css("background-image", "-o-linear-gradient(bottom, "+color_down+" 50%, #"+hex+" 50%)");
						jQuery( '.countdown_amount').css("background-image", "-moz-linear-gradient(bottom, "+color_down+" 50%, #"+hex+" 50%)");
						jQuery( '.countdown_amount').css("background-image", "-webkit-linear-gradient(bottom, "+color_down+" 50%, #"+hex+" 50%)");
						jQuery( '.countdown_amount').css("background-image", "-ms-linear-gradient(bottom, "+color_down+" 50%, #"+hex+" 50%)");
						jQuery( '.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.Gradient(startColorstr='"+color_down+"', endColorstr='#"+hex+"')");
					}
	 });
	 var color3 = jQuery('#ujic_col_txt' ).val();
	 jQuery( '#ujic_col_txt, #colorSelector3' ).ColorPicker({
					color: color2,					
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#colorSelector3 div').css('backgroundColor', '#' + hex);
						jQuery( '#ujic_col_txt' ).attr( 'value', '#' + hex );
						jQuery( '.countdown_amount').css("color",'#' + hex);
					}
	 });
	  var color4 = jQuery('#ujic_col_sw' ).val();
	 jQuery( '#ujic_col_sw, #colorSelector4' ).ColorPicker({
					color: color2,					
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#colorSelector4 div').css('backgroundColor', '#' + hex);
						jQuery( '#ujic_col_sw' ).attr( 'value', '#' + hex );
						jQuery( '.countdown_amount').css("text-shadow",'1px 1px 1px #' + hex);
					}
	 });

}