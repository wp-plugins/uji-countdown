<?php
/**
 * @package   Uji Countdown
 * @author    Wpmanage <info@wpmanage.com>
 * @license   Free
 * @link      http://wpmanage.com/uji-countdown
 * @copyright 2014 WPmanage.com
 *
 * @wordpress-plugin
 * Plugin Name: Uji Countdown
 * Plugin URI: http://wpmanage.com/uji-countdown
 * Description: HTML5 Countdown.
 * Version: 2.0.1
 * Author: Wpmanage
 * Author URI: http://wpmanage.com
 * Text Domain: uji-countdown
 * License: GPL-2.0+
 * Domain Path: /lang
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'UJIC_NAME', 'Uji Countdown' );
define( 'UJIC_VERS', '2.0' );
define( 'UJIC_FOLD', 'uji-countdown' );
define( 'UJICOUNTDOWN', trailingslashit( dirname(__FILE__) ) );
define( 'UJICOUNTDOWN_URL', plugin_dir_url( __FILE__ ) );
define( 'UJICOUNTDOWN_BASE', plugin_basename(__FILE__) );
define( 'UJICOUNTDOWN_FILE', __FILE__ );

//Google Fonts
require_once( plugin_dir_path( __FILE__ ) . 'assets/googlefonts.php' );

// Classes
require_once( plugin_dir_path( __FILE__ ) . 'classes/class-uji-countdown-admin.php' );
require_once( plugin_dir_path( __FILE__ ) . 'classes/class-uji-countdown.php' );
require_once( plugin_dir_path( __FILE__ ) . 'classes/class-uji-countdown-front.php' );
require_once( plugin_dir_path( __FILE__ ) . 'classes/class-uji-widget.php' );

// INIT
Uji_Countdown::get_instance();