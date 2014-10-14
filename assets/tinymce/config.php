<?php

/*-----------------------------------------------------------------------------------*/
/*	Countdown Config
/*-----------------------------------------------------------------------------------*/

// GET All Styles
function ujic_get_styles( $desc = false ){
	global $wpdb;
	$ujic_styles = $wpdb->get_results( "SELECT style, title FROM wp_uji_counter ORDER BY `time` DESC" );
	$ujic_sel = array();
	if( !empty($ujic_styles) ){
      if( $desc ){
         return '';
      }else{   
         foreach( $ujic_styles as $ujic ){
            $ujic_sel[$ujic->title] = $ujic->title .' - '. $ujic->style;
         }
         return $ujic_sel;
      }
	}else{
       if( $desc ){
         return '<span style="color:red">1. Go to Settings/Uji Countdown <br> 2. Click on Create a new timer style</span>';
      }else{ 
         return array( __('Please add a new style first', 'ujicountdown') );
      }   
	}
}

//GET Data/Time
function ujic_get_datetime($nr){
   for($i=0; $i<=$nr; $i++){
      $num[sprintf("%02s",$i)] = sprintf("%02s",$i);
   }
   
   return $num;
}


$ujic_shortcodes['countdown'] = array(
	'no_preview' => true,
	'params' => array(
		'style'      => array(
			'type'    => 'select',
			'label'   => __('Select Style', 'ujicountdown'),
			'desc'    => __('Select the style for your countdown', 'ujicountdown'),
			'options' => ujic_get_styles(),
         'desc'    => ujic_get_styles(true)
		),
		'thedate'    => array(
			'std'     => '',
			'type'    => 'datapick',
			'label'   => __('Expire Date', 'ujicountdown'),
			'desc'    => __('Select the date to expire', 'ujicountdown'),
		),
		'thetime'    => array(
			'type'    => 'timepick',
			'label'   => __('Time', 'ujicountdown'),
			'desc'    => __('Select the time to expire', 'ujicountdown'),
			'options_h' => ujic_get_datetime(23),
         'options_m' => ujic_get_datetime(59),
          
      ),
		'thexpi'     => array(
         'std'     => '',
         'std2'    => 'http://',
			'type'    => 'expiry',
         'fname'   => __('Hide the countdown: ', 'ujicountdown'),
         'fname2'  => __('Or go to URL: ', 'ujicountdown'),
			'label'   => __('After Expiry', 'ujicountdown'),
			'desc'    => __('Select option after expiry', 'ujicountdown')
      )
    ),
	'shortcode'     => '[ujicountdown id="{{style}}" expire="{{thedate}} {{thetime}}:{{thetime_}}" hide="{{thexpi}}" url="{{thexpi_}}"]',
	'popup_title'   => __('Insert Countdown Shortcode', 'ujicountdown')
    
);

?>