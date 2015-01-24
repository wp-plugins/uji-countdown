(function($) {
"use strict";   
 

			
 			//Shortcodes
            tinymce.PluginManager.add( 'ujicShortcodes', function( editor, url ) {
				
				editor.addCommand("ujicPopup", function ( a, params )
				{
					var popup = params.identifier;
					tb_show("Insert Uji Countdown", url + "/popup.php?popup=" + popup + "&width=" + 800);
				});
     
                editor.addButton( 'ujic_button', {
                    type: 'button',
                    text: false,
			        icon: 'icon dashicons-clock',
					onclick : function(e) {
                        editor.execCommand("ujicPopup", false, {title: 'Uji Countdown',identifier: 'countdown'})
                    }
					
                
					
                
        	  });
         
          });
         

 
})(jQuery);        