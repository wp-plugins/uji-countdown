(function() {

    var ujic_styles = ujic_short_vars.ujic_style;
    var ujic_min = ujic_short_vars.ujic_min;
    var ujic_hou = ujic_short_vars.ujic_hou;
    var ujic_reclab = ujic_short_vars.ujic_reclab;
    
    tinymce.PluginManager.add('ujic_tc_button', function( editor, url ) {
        editor.addButton( 'ujic_tc_button', {
            title: 'Uji Countdown',
            type: 'button',
            icon: 'mce-ico mce-i-icon dashicons-clock',
            text: '',
                onclick: function() {
                        editor.windowManager.open( {
                            id: 'uji-contdown-pop',
                            title: 'Insert Uji Countdown',
                            body:[{
                                type: 'listbox', 
                                name: 'ujic_style', 
                                label: 'Select Style:', 
                                tooltip: 'Select saved style',
                                values: ujic_styles
                            },
                            {
                                type:'container',
                                html: '<div class="uji_spacer"></div>'
                            },
                            {
                                type: 'textbox',
                                name: 'ujic_date',
                                label: 'Expire Date:',
                                tooltip: 'Select the date to expire',
                                id : 'ujic-datapick'
                            },
                            {
                                type:'container',
                                label: 'Select Time:',
                                items:[{
                                    type: 'listbox', 
                                    name: 'ujic_hou', 
                                    tooltip: 'Select hour',
                                    id: 'ujic_hou',
                                    values: ujic_hou
                                },
                                {
                                    type: 'label',
                                    id: 'ujic_time_space',
                                    text: ' : '
                                },
                                {
                                    type: 'listbox', 
                                    name: 'ujic_min', 
                                    tooltip: 'Select minute',
                                    id: 'ujic_min',
                                    values: ujic_min
                                }]
                            },
                            {
                                type:'container',
                                html: '<div class="uji_spacer"></div>'
                            },      
                            {
                                type:'container',
                                label: 'After expiration:',
                                items:[{
                                    type: 'checkbox',
                                    name: 'ujic_hide',
                                    checked: true,
                                    id: 'ujic_hide'
                                },
                                {
                                    type: 'label',
                                    id: 'ujic_hide_txt',
                                    text: ' Hide the countdown'
                                }],
                            },
                            {
                                type: 'textbox',
                                name: 'ujic_url',
                                id: 'ujic_url',
                                label: 'Or go to URL',
                                text: 'http://',
                                tooltip: 'Redirect page to above link'
                            },
                            {
                                type:'container',
                                html: '<div class="uji_spacer"></div>'
                            },
                            {
                                type:'container',
                                label: 'Recurring Time:',
                                items:[{
                                    type: 'label',
                                    id: 'ujic_rev_txt',
                                    text: 'Every:'
                                },
                                {
                                    type: 'textbox',
                                    name: 'ujic_rev',
                                    id: 'ujic_rev',
                                    tooltip: 'Number of Unit'
                                },
                                {
                                    type: 'listbox', 
                                    name: 'ujic_revlab', 
                                    tooltip: 'Select unit of time',
                                    id: 'ujic_revlab',
                                    values: ujic_reclab
                                }]
                            },
                            {
                                type:'container',
                                label: ' ',
                                items:[{
                                    type: 'label',
                                    id: 'ujic_rep_txt',
                                    text: 'Repeats:'
                                },
                                {
                                    type: 'textbox',
                                    name: 'ujic_rep',
                                    id: 'ujic_rep',
                                    tooltip: 'Unit of time and number of repeats'
                                },
                                {
                                    type: 'label',
                                    id: 'ujic_rep_des',
                                    text: 'leave it empty for unlimited'
                                }]
                            },
                            {
                                type:'container',
                                html: '<div class="uji_spacer"></div>'
                            },
                            {
                                type: 'textbox',
                                label: 'Campaign Name',
                                name: 'ujic_camp',
                                id: 'ujic_camp',
                                tooltip: 'Enter your campaign name'
                            }],
                            onsubmit: function( e ) {
                                if(e.data.ujic_date === '') {
                                    var window_id = this._id;
                                    var inputs = jQuery('#' + window_id + '-body').find('.mce-formitem input');

                                    editor.windowManager.alert('Please fill Expire Date field.');

                                    if(e.data.ujic_date === '') {
                                        jQuery(inputs.get(0)).css('border-color', 'red');
                                    }
                                    
                                    return false;
                                }
                                
                                //console.log(e);
                                editor.insertContent( '[ujicountdown id="' + e.data.ujic_style + '" expire="' + e.data.ujic_date + ' ' + e.data.ujic_hou + ':' + e.data.ujic_min + '" hide="' + e.data.ujic_hide + '" url="' + e.data.ujic_url + '" subscr="' + e.data.ujic_camp + '" recurring="' + e.data.ujic_rev + '" rectype="' + e.data.ujic_revlab + '" repeats="' + e.data.ujic_rep + '"]');
                                
                                
                            }
                        });
                        //Button name change
                        jQuery("#uji-contdown-pop .mce-foot").find('button').first().html('Insert');
                    
                        //Datapicker Unfocus
                        jQuery("#uji-contdown-pop").on( "click", function() { 
                            jQuery('#ujic-datapick').blur();
                         });
                        //Datapicker Initiate
                        jQuery('#ujic-datapick').datepicker({
                               dateFormat: 'yy/mm/dd'
                         });
                        //URL http:// placeholder
                        jQuery('#uji-contdown-pop #ujic_url').attr('placeholder', 'http://');
                    
                        //Check based on URL. Hide if empty
                        jQuery('#ujic_url').focusin(function() {
                                jQuery('#ujic_hide').attr('aria-checked', false);
                                jQuery('#ujic_hide').removeClass('mce-checked');
                        });
                        jQuery('#ujic_url').focusout(function() {
                            if( jQuery(this).val() === ''){
                                console.log(jQuery(this).val());
                                jQuery('#ujic_hide').attr('aria-checked', true);
                                jQuery('#ujic_hide').addClass('mce-checked');
                            }
                        });
                    
                    }
            
        });
        
        
    });
    
    
})();