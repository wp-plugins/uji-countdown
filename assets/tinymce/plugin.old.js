(function ()
{
	// create ujicShortcodes plugin
	tinymce.create("tinymce.plugins.ujicShortcodes",
	{
		init: function ( ed, url )
		{
			ed.addCommand("ujicPopup", function ( a, params )
			{
				var popup = params.identifier;
				
				// load thickbox
				tb_show("Insert Countdown Shortcode", url + "/popup.php?popup=" + popup + "&width=" + 800);
			});
		},
		createControl: function ( btn, e )
		{
			if ( btn == "ujic_button" )
			{	
				var a = this;
				
				var btn = e.createButton('ujic_button', {
                    title: "Insert Ujic Shortcode",
					image: UjicShortcodes.plugin_folder +"assets/tinymce/images/icon.png",
					icons: false,
					onclick : function() {  
                 		tinyMCE.activeEditor.execCommand("ujicPopup", false, {
						title: 'Uji Countdown',
						identifier: 'countdown'
						})
            		}  
                });
                
                return btn;
			}
			
			return null;
		},
		addImmediate: function ( ed, title, sc) {
			ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand( "mceInsertContent", false, sc )
				}
			})
		}
	});
	
	// add ujicShortcodes plugin
	tinymce.PluginManager.add("ujicShortcodes", tinymce.plugins.ujicShortcodes);
})();