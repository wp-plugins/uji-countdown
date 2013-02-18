jQuery(function() {		
			ujidatapick();
			ujilink();

});

function ujidatapick(){
	jQuery('.UJI_date').each(function() {
					jQuery(this).datetimepicker({
					timeFormat: 'hh:mm',
					dateFormat: 'yy/mm/dd'
					});
					//jQuery(this).trigger("click");	
				});
}

function ujilink(){
	jQuery('.ujic_link').each(function() {
					jQuery(this).keyup(function () {
						var value = jQuery(this).val();
						var cname = jQuery(this).parent().parent().find('.ujic_exp').attr("name");
						jQuery(this).parent().parent().find('.ujic_exp').css('border', 'red');
						//alert(cname);
						
						if(value){	
							jQuery('input[name="'+cname+'"]').attr('checked', false);
						}else{
					
							jQuery('input[name="'+cname+'"]').attr('checked', true);
						}
						
					});
				});
}
