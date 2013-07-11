<?php class UJI_countdown extends WP_Widget {

 function UJI_countdown(){
  $widget_ops = array('description' => 'Uji Countdown widget');
  $control_ops = array('width' => 300, 'height' => 400);
  parent::WP_Widget('UJI_countdown_widget', $name='..:: Uji Countdown ::..', $widget_ops, $control_ops);
 }

 function widget($args, $instance) {
 		extract($args);
		$UJI_title = $instance['UJI_title'];
 		$UJI_style = $instance['UJI_style'];
		$UJI_date = $instance['UJI_date'];
		$UJI_exp = $instance['UJI_exp'];
 		$UJI_link = $instance['UJI_link'];
 		
		$shtval = '';
		$shtval .= (!empty($UJI_style)) ? ' id="'.$UJI_style.'"' : $shtval;
		$shtval .= (!empty($UJI_date)) ? ' expire="'.$UJI_date.'"' : $shtval;
		$shtval .= (!empty($UJI_exp)) ? ' hide = "true"' : $shtval;
		$shtval .= (empty($UJI_exp) && !empty($UJI_link)) ? ' url = "'.$UJI_link.'"' : $shtval;
		$shortcode = (!empty($shtval)) ? '[ujicountdown'.$shtval.']' : '';
		
 		if(!empty($shortcode)) echo '<h3 class="widget-title">'.$UJI_title.'</h3>'.do_shortcode($shortcode);

 } // widget

 function update($new_instance, $old_instance){
 		$instance = $old_instance;
		$instance['UJI_title'] = stripslashes($new_instance['UJI_title']);
 		$instance['UJI_style'] = stripslashes($new_instance['UJI_style']);
		$instance['UJI_date'] = stripslashes($new_instance['UJI_date']);
		$instance['UJI_exp'] = stripslashes($new_instance['UJI_exp']);
 		$instance['UJI_link'] = stripslashes($new_instance['UJI_link']);
 		return $instance;
 	} // save
 
 function form($instance){
  	$instance = wp_parse_args( (array) $instance, array('UJI_title'=>'', 'UJI_style'=>'', 'UJI_date'=>'', 'UJI_exp'=>'', 'UJI_link'=>'') );
 		$UJI_title = htmlspecialchars($instance['UJI_title']);
		$UJI_style = htmlspecialchars($instance['UJI_style']);
		$UJI_date = htmlspecialchars($instance['UJI_date']);
		$UJI_exp = htmlspecialchars($instance['UJI_exp']);
 		$UJI_link = (isset($UJI_link)) ? htmlspecialchars($instance['UJI_link']) : '';
		
		
		global $wp_version;
		if ( $wp_version >= 3.5 ) {
			wp_enqueue_script ( 'ujic_jquery_date_js' ,  UJI_PLUGIN_URL . '/inc/js/jquery-ui.min.js' , array ( 'jquery' ) , '1.10.3' , true );
			wp_enqueue_script ( 'ujic_date_js' ,  UJI_PLUGIN_URL . '/inc/js/jquery-ui-timepicker.js' , array ( 'ujic_jquery_date_js' ) , '1.3' , true );
		}else{
			wp_enqueue_script ( 'ujic_jquery_date_js' ,  UJI_PLUGIN_URL . '/inc/js/jquery-ui-old.min.js' , array ( 'jquery' ) , '1.8' , true );
			wp_enqueue_script ( 'ujic_date_js' ,  UJI_PLUGIN_URL . '/inc/js/jquery-ui-timepicker-old.js' , array ( 'ujic_jquery_date_js' ) , '1.0.1' , true );
		}
		wp_enqueue_script ( 'ujic_widget_js' ,  UJI_PLUGIN_URL . '/inc/js/widget-js.js' , array ( 'jquery' ) , '1.0');
		wp_register_style('ujic-jqueryui-css', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css");
		wp_enqueue_style( 'ujic-jqueryui-css');	
		
			  
		echo '<p style="font-size:11px">Just one counter on page is allowed. <br>Check <a href="http://wpmanage.com/uji-countdown" target="_blank">Premium version</a> for multiple countdown timers on the same page. <p>';
		
		echo '<p><label>' . 'Title:' . '</label>
				<input type="text" value="'. $UJI_title .'" width="200px" id="'.$this->get_field_id('UJI_title').'" class="widefat" name="' . $this->get_field_name('UJI_title') . '" />
			  </p>';
		
		echo '<p><label>' . 'Select your style:' . '</label>
				 <select class="widefat" id="'.$this->get_field_id('UJI_style').'" name="'.$this->get_field_name('UJI_style').'">
                        <option value=""> Select a Style </option>'
						.ujic_forms($UJI_style).
						'</select></p>';
								
 		echo '<p><label>' . 'Countdown Expire In:' . '</label>
				<input type="text" value="'. $UJI_date .'" width="200px" id="'.$this->get_field_id('UJI_date').'" class="UJI_date widefat" name="' . $this->get_field_name('UJI_date') . '" />
			  </p>';
			  
		echo '<p><h3>After expiry:</h3>
				<label>' . 'Hide countdown:' . '</label>
				<input type="checkbox" ', ($UJI_exp==1) ? ' checked="checked"' : '' ,' value="1" id="'.$this->get_field_id('UJI_exp').'" class="ujic_exp" name="' . $this->get_field_name('UJI_exp') . '" />
			  </p>';	  
 		
		echo '<p><label>' . 'Or go to link:' . '</label>
				<input type="text" value="'. $UJI_link .'" width="200px" id="'.$this->get_field_id('UJI_link').'" class="ujic_link widefat" name="' . $this->get_field_name('UJI_link') . '" />
			  </p>';
		?>
		<script language="javascript">
			jQuery(function() {
				
				jQuery('.UJI_date').each(function() {
					jQuery(this).datetimepicker({
					timeFormat: 'HH:mm',
					dateFormat: 'yy/mm/dd'
					});
				});		
				
				jQuery('.ujic_link').each(function() {
					jQuery(this).keyup(function () {
						var value = jQuery(this).val();
						var cname = jQuery(this).parent().parent().find('.ujic_exp').attr("name");
						jQuery(this).parent().parent().find('.ujic_exp').css('border', 'red');
						//alert("ccc");
						
						if(value){	
							jQuery('input[name="'+cname+'"]').attr('checked', false);
						}else{
					
							jQuery('input[name="'+cname+'"]').attr('checked', true);
						}
						
					});
				});

				});

        </script>
        <?php
 } // form

} // class

function UJI_countdownWidget() {
  register_widget('UJI_countdown');
}

add_action('widgets_init', 'UJI_countdownWidget');
?>
