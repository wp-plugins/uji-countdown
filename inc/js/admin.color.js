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
			            hex = "#"+hex;
						jQuery( '.countdown_amount').css("background", "-moz-linear-gradient(top,  "+color_up+" 50%, "+hex+" 50%)"); /* FF3.6+ */
                        jQuery( '.countdown_amount').css("background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,"+color_up+"), color-stop(50%,"+hex+"))"); /* Chrome,Safari4+ */
                        jQuery( '.countdown_amount').css("background", "-webkit-linear-gradient(top,  "+color_up+" 50%,"+hex+" 50%)"); /* Chrome10+,Safari5.1+ */
                        jQuery( '.countdown_amount').css("background", "-o-linear-gradient(top,  "+color_up+" 50%,"+hex+" 50%)"); /* Opera 11.10+ */
                        jQuery( '.countdown_amount').css("background", "-ms-linear-gradient(top,  "+color_up+" 50%,"+hex+" 50%)"); /* IE10+ */
                        jQuery( '.countdown_amount').css("background", "linear-gradient(to bottom,  "+color_up+" 50%,"+hex+" 50%)"); /* W3C */
                        jQuery( '.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+color_up+"', endColorstr='"+hex+"',GradientType=0 )"); /* IE6-9 */				
						
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
			            hex = "#"+hex;
						jQuery( '.countdown_amount').css("background", "-moz-linear-gradient(top,  "+hex+" 50%, "+color_down+" 50%)"); /* FF3.6+ */
                        jQuery( '.countdown_amount').css("background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,"+hex+"), color-stop(50%,"+color_down+"))"); /* Chrome,Safari4+ */
                        jQuery( '.countdown_amount').css("background", "-webkit-linear-gradient(top,  "+hex+" 50%,"+color_down+" 50%)"); /* Chrome10+,Safari5.1+ */
                        jQuery( '.countdown_amount').css("background", "-o-linear-gradient(top,  "+hex+" 50%,"+color_down+" 50%)"); /* Opera 11.10+ */
                        jQuery( '.countdown_amount').css("background", "-ms-linear-gradient(top,  "+hex+" 50%,"+color_down+" 50%)"); /* IE10+ */
                        jQuery( '.countdown_amount').css("background", "linear-gradient(to bottom,  "+hex+" 50%,"+color_down+" 50%)"); /* W3C */
                        jQuery( '.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='"+hex+"', endColorstr='"+color_down+"',GradientType=0 )"); /* IE6-9 */
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