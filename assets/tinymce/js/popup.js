// start the popup specefic scripts
// safe to use $
jQuery(document).ready(function($) {
    var ujics = {
    	loadVals: function()
    	{
    		var shortcode = $('#_ujic_shortcode').text(),
    			uShortcode = shortcode;
    
    		// fill in the gaps eg {{param}}
    		$('.ujic-input').each(function() {
    			var input = $(this),
    				id = input.attr('id'),
    				id = id.replace('ujic_', ''),		// gets rid of the ujic_ prefix
    				re = new RegExp("{{"+id+"}}","g");
    			if ( !$(this).is(':checkbox') || ( $(this).is(':checkbox') && $(this).is(':checked') ) )	
    			     uShortcode = uShortcode.replace(re, input.val());
                else uShortcode = uShortcode.replace(re, '');
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_ujic_ushortcode').remove();
    		$('#ujic-sc-form-table').prepend('<div id="_ujic_ushortcode" class="hidden">' + uShortcode + '</div>');
    	},
    	cLoadVals: function()
    	{
    		var shortcode = $('#_ujic_cshortcode').text(),
    			pShortcode = '';
    			shortcodes = '';
    		
    		// fill in the gaps eg {{param}}
    		$('.child-clone-row').each(function() {
    			var row = $(this),
    				rShortcode = shortcode;
    			
    			$('.ujic-cinput', this).each(function() {
    				var input = $(this),
    					id = input.attr('id'),
    					id = id.replace('ujic_', '')		// gets rid of the ujic_ prefix
    					re = new RegExp("{{"+id+"}}","g");
    				if ( !$(this).is(':checkbox') || ( $(this).is(':checkbox') && $(this).is(':checked') ) )	
    				        rShortcode = rShortcode.replace(re, input.val());
                    else rShortcode = rShortcode.replace(re, '');
    			});
    	
    			shortcodes = shortcodes + rShortcode + "\n";
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_ujic_cshortcodes').remove();
    		$('.child-clone-rows').prepend('<div id="_ujic_cshortcodes" class="hidden">' + shortcodes + '</div>');
    		
    		// add to parent shortcode
    		this.loadVals();
    		pShortcode = $('#_ujic_ushortcode').text().replace('{{child_shortcode}}', shortcodes);
    		
    		// add updated parent shortcode
    		$('#_ujic_ushortcode').remove();
    		$('#ujic-sc-form-table').prepend('<div id="_ujic_ushortcode" class="hidden">' + pShortcode + '</div>');
    	},
    	children: function()
    	{
    		// assign the cloning plugin
    		$('.child-clone-rows').appendo({
    			subSelect: '> div.child-clone-row:last-child',
    			allowDelete: false,
    			focusFirst: false
    		});
    		
    		// remove button
    		$('.child-clone-row-remove').live('click', function() {
    			var	btn = $(this),
    				row = btn.parent();
    			
    			if( $('.child-clone-row').size() > 1 )
    			{
    				row.remove();
    			}
    			else
    			{
    				alert('You need a minimum of one row');
    			}
    			
    			return false;
    		});
    		
    		// assign jUI sortable
    		$( ".child-clone-rows" ).sortable({
				placeholder: "sortable-placeholder",
				items: '.child-clone-row'
				
			});
    	},
    	resizeTB: function()
    	{
			var	ajaxCont = $('#TB_ajaxContent'),
				tbWindow = $('#TB_window'),
				ujicPopup = $('#ujic-popup');

            tbWindow.css({
                height: ujicPopup.outerHeight() + 50,
                width: ujicPopup.outerWidth(),
                marginLeft: -(ujicPopup.outerWidth()/2)
            });

			ajaxCont.css({
				paddingTop: 0,
				paddingLeft: 0,
				paddingRight: 0,
				height: (tbWindow.outerHeight()-47),
				overflow: 'auto', // IMPORTANT
				width: ujicPopup.outerWidth()
			});
			
			$('#ujic-popup').addClass('no_preview');
    	},
    	load: function()
    	{
    		var	ujics = this,
    			popup = $('#ujic-popup'),
    			form = $('#ujic-sc-form', popup),
    			shortcode = $('#_ujic_shortcode', form).text(),
    			popupType = $('#_ujic_popup', form).text(),
    			uShortcode = '';
    		
    		// resize TB
    		ujics.resizeTB();
    		$(window).resize(function() { ujics.resizeTB() });
    		
    		// initialise
    		ujics.loadVals();
    		ujics.children();
    		ujics.cLoadVals();
    		
    		// update on children value change
    		$('.ujic-cinput', form).live('change', function() {
    			ujics.cLoadVals();
    		});
    		
    		// update on value change
    		$('.ujic-input', form).change(function() {
    			ujics.loadVals();
    		});
            
            $('.ujic-datapick').datepicker({
					dateFormat: 'yy/mm/dd'
			});
    		
    		// when insert is clicked
    		$('.ujic-insert', form).click(function() {   
                var errors = false;
                var style_id = jQuery("#ujic_style").val();
                var date_id = jQuery("#ujic_thedate").val();
                var url_id = jQuery('#ujic_thexpi_').val();
                if(style_id == ""){
                    alert("Please select a style");
                    errors = true;
                    return;
                }
                if(date_id == ""){
                    alert("Please select countdown date");
                    errors = true;
                    return;
                }
                if(url_id != "" &&  !isUrl(url_id)){
                    alert("Please insert valid link");
                    errors = true;
                    return;
                }
    
            if(parent.tinymce && !errors)
            {
               parent.tinymce.activeEditor.execCommand('mceInsertContent',false,$('#_ujic_ushortcode', form).html());
               tb_remove();
            }
            
    		});
    	}
	}
    
    // run
    $('#ujic-popup').livequery( function() { ujics.load(); } );
});

function isUrl(url) {
				var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
				if(RegExp.test(url)){
					return true;
				}else{
					return false;
				}
			}