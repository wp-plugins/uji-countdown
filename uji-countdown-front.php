<?php

////////////////////////////////////SETTINGS////////////////////////////////////

function add_ujic_popup(){
	wp_enqueue_script ( 'ujic_jquery_date_js' ,  UJI_PLUGIN_URL . '/inc/js/jquery-ui.min.js' , array ( 'jquery' ) , '1.4' , true );
	wp_enqueue_script ( 'ujic_date_js' ,  UJI_PLUGIN_URL . '/inc/js/jquery-ui-timepicker.js' , array ( 'ujic_jquery_date_js' ) , '1.4' , true );
	wp_register_style('ujic-jqueryui-css', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/base/jquery-ui.css");
	wp_enqueue_style( 'ujic-jqueryui-css');	
	
	  ?>
        <script>
			jQuery(function() {
					jQuery('#dateujic').datetimepicker({
					timeFormat: 'hh:mm',
					dateFormat: 'yy/mm/dd'
					});
					
					jQuery('#urlujic').keyup(function () {
						var value = jQuery(this).val();

						if(value){			  
							jQuery('input[name=hideujic]').attr('checked', false);
						}else{
							jQuery('input[name=hideujic]').attr('checked', true);
						}
						
					}).keyup();
				});

			function isUrl(url) {
				var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
				if(RegExp.test(url)){
					return true;
				}else{
					return false;
				}
			}

	
            function InsertContdown(){
				
				
                var style_id = jQuery("#add_style").val();
                if(style_id == ""){
                    alert("Please select a style");
                    return;
                }
				var time_id = jQuery('#dateujic').val();
                if(time_id == ""){
                    alert("Please select time for countdown");
                    return;
                }
				var url = "";
				var url_id = jQuery('#urlujic').val();
                if(url_id != "" &&  !isUrl(url_id)){
                    alert("Please insert valid link");
                    return;
                }else if(url_id != ""){
					url = " url = \"" + url_id + "\"";
				}
				var hide = "";
				if( jQuery('input[name=hideujic]').is(':checked')){
					var hide = " hide = \"true\"";
				}
				

                var form_name = jQuery("#add_form_id option[value='" + style_id + "']").text().replace(/[\[\]]/g, '');
                window.send_to_editor("[ujicountdown id=\"" + style_id + "\" expire=\"" + time_id  + "\"" + hide + url +"]");
            }
        </script>

        <div id="select_countdown_form" style="display:none;">
            <div class="wrap">
                <div>
                    <div style="padding:15px 15px 0 15px;">
                        <h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;">Insert Countdown</h3>
                        <span>
                           Please select your style below to add it to your post or page.
                        </span>
                        <p style="font-size:11px">Just one counter on page is allowed. <br>Check <a href="http://wpmanage.com/uji-countdown" target="_blank">Premium version</a> for multiple countdown timers on the same page.
                        </p>
                    </div>
                    <div style="padding:15px 15px 0 15px;">
                    <h3 style="display:block; font-size:14px; margin-bottom:10px">Select style:</h3>
                        <select id="add_style">
                            <option value=""> Select a Style </option>
                            <?php
                               echo ujic_forms();
                            ?>
                        </select> 
                    </div>
                    <div style="padding:15px 15px 0 15px;">
                    	<h3 style="display:block; font-size:14px; margin-bottom:10px">Countdown Expire In:</h3>
                        <input type="text" value="" width="200px" id="dateujic"  />         
                    </div>
                    <div style="padding:15px 15px 0 15px;">
                    	<h3 style="display:block; font-size:14px; margin-bottom:10px">After expiry:</h3>
                        <span style="inline-block; font-size:14px; margin:0px 10px 0 10px">Hide countdown:</span>
                        <input name="hideujic" id="hideujic" type="checkbox" value="1" checked />
                        <div style="display:block; padding: 10px 0 10px 0">
                        <span style="display:inline-block; font-size:14px; margin:0px 35px 0 10px">Or go to link:</span>
                        <input type="text" value="" id="urlujic" name="urlujic" style="width:240px"  />    
                        </div>
                   	</div>
                    <div style="padding:15px;">
                        <input type="button" class="button-primary" value="Insert Countdown" onclick="InsertContdown();"/>&nbsp;&nbsp;&nbsp;
                    <a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
	
function ujic_forms($sel=NULL){
	global $wpdb;
	$table_name = $wpdb->prefix ."uji_counter";
	$ujic_datas = $wpdb->get_results("SELECT * FROM $table_name ORDER BY `time` DESC");
	if(!empty($ujic_datas)){
		$select = $ujictab = '';
	foreach($ujic_datas as $ujic)
		{
		$select = (isset($sel) && !empty($sel) && $sel == $ujic->title ) ? ' selected="selected"' : '';	
		$ujictab .='<option value="'.$ujic->title.'" '.$select.'> '.$ujic->title.' </option>';
		}
	return $ujictab;
	}
}

function ujic_code( $atts, $content = null ) { 
	global $wpdb;
    extract(shortcode_atts(array(
	      'id' 		=> "ujic-black",
		  'expire'	=> "2012/12/21 01:00:00",
		  'hide' 	=> "false",
		  'url'		=> ""
     ), $atts));
	//Timezone

	$utc = 2*(60*60);
	$now_time = strtotime(date_i18n("Y/m/d H:i:s"))-$utc;
	$unx_time = $expire.":00";
	$unx_time = strtotime($unx_time)-$utc;
	
	if($hide=="true" && $now_time > $unx_time){
		return 	$content;
	}else{
	wp_enqueue_style( 'ujiStyleCount');
	wp_enqueue_script('UJI_js_countdown');
	 
	$table_name = $wpdb->prefix ."uji_counter";
	$ujic_datas = $wpdb->get_results("SELECT * FROM $table_name WHERE title = '".$id."'");
	
	foreach($ujic_datas as $ujic)
		{ 
			$class = $ujic->ujic_pos;
			$ujic_txt_size = $ujic->size;
			$ujic_col_dw = $ujic->col_dw;
			$ujic_col_up = $ujic->col_up;
			$ujic_col_txt = $ujic->col_txt;
			$ujic_col_sw = $ujic->col_sw;	
			$ujic_txt = $ujic->ujic_txt;	
			$ujic_ani = $ujic->ujic_ani;		
		}

	$classh = !empty($class) ? ' class="ujic_'.$class.'"' : '';
	$ujic_txt_size = !empty($ujic_txt_size) ? $ujic_txt_size : 35;
	$ujic_col_dw = !empty($ujic_col_dw) ? $ujic_col_dw : '#3A3A3A';
	$ujic_col_up = !empty($ujic_col_up) ? $ujic_col_up : '#635b63';
	$ujic_col_txt = !empty($ujic_col_txt) ? $ujic_col_txt : '#FFFFFF';
	$ujic_col_sw = !empty($ujic_col_sw) ? $ujic_col_sw : '#000000';
	$ujic_txt = !empty($ujic_txt) ? 'true' : 'false';
	$ujic_ani = !empty($ujic_ani) ? 'true' : 'false';
	$ujic_url = !empty($url) ? '\''.$url.'\'' : 'false';

	if($class=='center'){ $center_script = 'jQuery("#ujiCountdown").css({"width": (jQuery("#ujiCountdown").width()+5)+"px", "padding-left": "10px", "display": "block"});';} else{$center_script = '';}
$script ='	<script type="text/javascript">
			<!--
						 jQuery(document).ready(function($){
							var austDay = new Date("'.$expire.'");
							jQuery.countdown.regional["uji"] = {
								labels: ["'.__("Years", "uji-countdown").'", "'.__("Months", "uji-countdown").'", "'.__("Weeks", "uji-countdown").'", "'.__("Days", "uji-countdown").'", "'.__("Hours", "uji-countdown").'", "'.__("Minutes", "uji-countdown").'", "'.__("Seconds", "uji-countdown").'"],
								labels1: ["'.__("Year", "uji-countdown").'", "'.__("Month", "uji-countdown").'", "'.__("Week", "uji-countdown").'", "'.__("Day", "uji-countdown").'", "'.__("Hour", "uji-countdown").'", "'.__("Minute", "uji-countdown").'", "'.__("Second", "uji-countdown").'"],
								compactLabels: ["A", "L", "S", "Z"],
								whichLabels: null,
								timeSeparator: \':\', isRTL: false};
							jQuery.countdown.setDefaults(jQuery.countdown.regional["uji"]);
							jQuery("#ujiCountdown").countdown({until: austDay, text_size: \''.$ujic_txt_size.'\', color_down: \''.$ujic_col_dw.'\', color_up: \''.$ujic_col_up.'\', color_txt:  \''.$ujic_col_txt.'\', color_sw:  \''.$ujic_col_sw.'\',  ujic_txt: '.$ujic_txt .', animate_sec: '.$ujic_ani.', ujic_url: '.$ujic_url.'});	
							'.$center_script.'
							});
							
			//-->
			</script>';

	return strip_shortcodes($script.'<div id="ujiCountdown" '.$classh.'></div>'.$content);
	}
}  
add_shortcode("ujicountdown", "ujic_code"); 

?>