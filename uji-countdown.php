<?php
/*
Plugin Name: Uji Countdown
Plugin URI: http://www.wpmanage.com/uji-countdown/
Description: HTML5 Countdown.
Version: 1.3
Author: Ujog Raul
Author URI: http://www.wpmanage.com

	Copyright (c) 2012-2013
*/

if (!defined('UJI_PLUGIN_NAME'))
    define('UJI_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
	
if (!defined('UJI_PLUGIN_BASE'))
    define('UJI_PLUGIN_BASE', plugin_basename(__FILE__));

if (!defined('UJI_PLUGIN_URL'))
    define('UJI_PLUGIN_URL', WP_PLUGIN_URL . '/' . UJI_PLUGIN_NAME);
	
if (!defined('UJI_PLUGIN_DIR'))
    define('UJI_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . UJI_PLUGIN_NAME);
	
if(!defined("UJI_CURRENT_PAGE"))
    define("UJI_CURRENT_PAGE", basename($_SERVER['PHP_SELF']));
	
if (!defined('UJI_VERSION_KEY'))
    define('UJI_VERSION_KEY', 'UJI_version');

if (!defined('UJI_VERSION_NUM'))
    define('UJI_VERSION_NUM', '1.1');

///////////////////////////////////DB///////////////////////////////////////


function create_ujic_db(){
  global $wpdb;
  $sql = "CREATE TABLE " . $wpdb->prefix ."uji_counter ( 
	  id int(9) unsigned NOT NULL AUTO_INCREMENT,
	  time datetime not null,
	  title varchar(128) not null,
	  size int(2) not null,
	  col_dw varchar(7) not null,
	  col_up varchar(7) not null,
	  ujic_pos varchar(10) not null,
	  col_txt varchar(7) not null,
	  col_sw varchar(7) not null,
	  ujic_ani int(1) not null,
	  ujic_txt int(1) not null,
      PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=$wpdb->charset AUTO_INCREMENT=0;";
  require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
  dbDelta($sql);
  add_option("UJI_VERSION", UJI_VERSION_KEY);
}
register_activation_hook(__FILE__,'create_ujic_db');

////////////////////////////////////Add/Edit////////////////////////////////////

require_once(dirname( __FILE__ ) . "/uji-countdown-add.php");

////////////////////////////////////Front////////////////////////////////////

require_once(dirname( __FILE__ ) . "/uji-countdown-front.php");

////////////////////////////////////Widget//////////////////////////////////////

require_once(dirname( __FILE__ ) . "/uji-countdown-widget.php");

///////////////////////////////////CSS///////////////////////////////////////

function UJI__stylesheet() {
	$myStyleUrl = UJI_PLUGIN_URL . '/css/admin.countdown.css';
	$myStyleFile = UJI_PLUGIN_DIR . '/css/admin.countdown.css';
	if ( file_exists($myStyleFile) ) {
		wp_register_style('ujiStyleSheet', $myStyleUrl);
		wp_enqueue_style( 'ujiStyleSheet');
		wp_register_style('ujiStyleCount', UJI_PLUGIN_URL . '/css/jquery.countdown.css');
		wp_enqueue_style( 'ujiStyleCount');
		wp_enqueue_style('dashboard');
	}
}
function UJI__colopick() {
	$colUrl = UJI_PLUGIN_URL . '/inc/css/colorpicker.css';
	$colFile = UJI_PLUGIN_DIR . '/inc/css/colorpicker.css';
	if ( file_exists($colFile) ) {
		wp_register_style('ujiCol', $colUrl);
		wp_enqueue_style( 'ujiCol');
		wp_register_style('ujiColLay', UJI_PLUGIN_URL . '/inc/css/layout.css');
		wp_enqueue_style( 'ujiColLay');
	}
}

///////////////////////////////////JS////////////////////////////////////////

function UJI_js() {
	wp_enqueue_script ('UJI_js_count', UJI_PLUGIN_URL . '/js/prev.countdown.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'postbox' );
	wp_enqueue_script( 'dashboard' );
}
function UJI_color_js() {
	wp_enqueue_script ('UJI_color_js', UJI_PLUGIN_URL . '/inc/js/colorpicker.js', array('jquery'), '1.0', true);
	wp_enqueue_script ('UJI_acolor_js', UJI_PLUGIN_URL . '/inc/js/admin.color.js', array('jquery'), '1.0', true);
}

////////////////////////////////////LOCALIZATION////////////////////////////////

function ujic_init() {
  	load_plugin_textdomain( 'uji-countdown', false, '/uji-countdown/languages' ); 
}
add_action('init', 'ujic_init');

////////////////////////////////////MENU////////////////////////////////////

add_action('admin_menu', 'my_UJI_menu');
function my_UJI_menu() {
	
  $UJI_edit = add_submenu_page('options-general.php', 'Countdown', 'Countdown', 'manage_options', 'ujic-count', 'ujic_add_new');

  add_action('admin_print_styles-'. $UJI_edit, 'UJI__stylesheet');
  add_action('admin_print_styles-'. $UJI_edit, 'UJI__colopick');
  add_action('admin_print_scripts-'. $UJI_edit, 'UJI_js');	
  add_action('admin_print_scripts-'. $UJI_edit, 'UJI_color_js');	
}

////////////////////////////////////INIT////////////////////////////////////////

function UJI_set_links($links) {
   array_unshift($links, '<a class="edit" href="options-general.php?page=ujic-count">Settings</a>');
   return $links;
}

add_filter('plugin_action_links_'.UJI_PLUGIN_BASE, 'UJI_set_links', 10, 2 );

////////////////////////////////////POST BUTTON////////////////////////////////////////

function ujic_form_button($context){
        $image_btn = UJI_PLUGIN_URL. '/images/icon.png';
        $out = '<a href="#TB_inline?width=300&height=480&inlineId=select_countdown_form" class="thickbox" id="add_ujic" title="Add Countdown"><img src="'.$image_btn.'" alt="Add Counter" /></a>';
        return $context . $out;
    }
add_action('media_buttons_context', 'ujic_form_button');

if(in_array(UJI_CURRENT_PAGE, array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
        add_action('admin_footer', 'add_ujic_popup');
 }
 
////////////////////////////////////ENQUIRE SCRIPT////////////////////////////////////////

function ujic_scripts_register() {
	wp_register_style('ujiStyleCount', UJI_PLUGIN_URL . '/css/jquery.countdown.css');
	wp_enqueue_script('jquery');
	wp_register_script('UJI_js_countdown', UJI_PLUGIN_URL . '/js/jquery.countdown.js', array('jquery'), '1.0', true);
	wp_register_script('js_countdown', UJI_PLUGIN_URL . '/js/countdown.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'ujic_scripts_register');
 
?>