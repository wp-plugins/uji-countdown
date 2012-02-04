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
				});
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

                var form_name = jQuery("#add_form_id option[value='" + style_id + "']").text().replace(/[\[\]]/g, '');
                window.send_to_editor("[ujicountdown id=\"" + style_id + "\" expire=\"" + time_id  + "\"]");
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
                    </div>
                    <div style="padding:15px 15px 0 15px;">
                        <select id="add_style">
                            <option value=""> Select a Style </option>
                            <?php
                                ujic_forms();
                            ?>
                        </select> 
                    </div>
                    <div style="padding:15px 15px 0 15px;">
                    	<span style="display:block; font-size:14px; margin-bottom:10px">Countdown Expire In:</span>
                        <input type="text" value="" width="200px" id="dateujic"  />         
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
function ujic_forms(){
	global $wpdb;
	$table_name = $wpdb->prefix ."uji_counter";
	$ujic_datas = $wpdb->get_results("SELECT * FROM $table_name ORDER BY `time` DESC");
	if(!empty($ujic_datas)){
		$ujictab='';
	foreach($ujic_datas as $ujic)
		{
		$ujictab .='<option value="'.$ujic->title.'"> '.$ujic->title.' </option>';
		}
	echo $ujictab;
	}
}

function ujic_code( $atts, $content = null ) { 
	global $wpdb;
	 extract(shortcode_atts(array(
	      'id' 		=> "ujic-black",
		  'expire'	=>"2012/2/21 01:00:00"
     ), $atts));
	 
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

	if($class=='center'){ $center_script = 'jQuery("#ujiCountdown").css({"width": (jQuery("#ujiCountdown").width()-10)+"px", "padding-left": "30px"});';} else{$center_script = '';}
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
							jQuery("#ujiCountdown").countdown({until: austDay, text_size: \''.$ujic_txt_size.'\', color_down: \''.$ujic_col_dw.'\', color_up: \''.$ujic_col_up.'\', color_txt:  \''.$ujic_col_txt.'\', color_sw:  \''.$ujic_col_sw.'\',  ujic_txt: '.$ujic_txt .', animate_sec: '.$ujic_ani.'});	
							'.$center_script.'
							});
							
			//-->
			</script>';

	return strip_shortcodes($script.'<div id="ujiCountdown" '.$classh.'></div>'.$content);
}  
add_shortcode("ujicountdown", "ujic_code"); 

?>