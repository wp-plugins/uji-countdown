<?php
/**
 *
 * Plugin Name: Uji Countdown 
 * Plugin URI: http://www.wpmanage.com/uji-countdown/
 * Description: HTML5 Countdown.
 * Version: 2.0
 * Author: WPmanage <info@wpmanage.com>
 * Author URI: http://www.wpmanage.com
 */
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

class UjiCountdown extends Uji_Countdown {
    
    /**
     * Init vars
     *
     * @since     2.0
     */
    public static function uji_vars() {
        return array(   'class' => 'ujic_pos',
                        'ujic_style' => 'ujic_style',
                        'ujic_txt_size' => 'ujic_size',
                        'ujic_col_dw' => 'ujic_col_dw',
                        'ujic_col_up' => 'ujic_col_up',
                        'ujic_col_txt' => 'ujic_col_txt',
                        'ujic_col_sw' => 'ujic_col_sw',
                        'ujic_col_lab' => 'ujic_col_lab',
                        'ujic_lab_sz' => 'ujic_lab_sz',
                        'ujic_thick' => 'ujic_thick',
                        'ujic_txt' => 'ujic_txt',
                        'ujic_ani' => 'ujic_ani',
                        'ujic_d' => 'ujic_d',
                        'ujic_h' => 'ujic_h',
                        'ujic_m' => 'ujic_m',
                        'ujic_s' => 'ujic_s',
                        'ujic_y' => 'ujic_y',
                        'ujic_o' => 'ujic_o',
                        'ujic_w' => 'ujic_w',
                        'ujic_goof' => 'ujic_goof',
                        'ujic_post' => 'time');
    }

    /**
     * Initialize the plugin frontend.
     *
     * @since     2.0
     */
    public function __construct() {
        //add the shortcode
        add_shortcode( 'ujicountdown', array( $this, 'ujic_shortcode' ) );

    }

    /**
     * The shortcode
     *
     * @since    2.0
     */
    public function ujic_shortcode( $atts, $content = null ) {
        extract( shortcode_atts( array(
                       // 'style' => "classic",
                        'id' => "",
                        'expire' => "",
                        'hide' => "",
                        'url' => ""
                        ), $atts ) );
        //Increment counters
        static $ujic_count = 0;

        $unx_time = strtotime( $expire . ":00" );
        $now_time = (int) current_time( 'timestamp' );

        if ( ($hide == "true" && $now_time > $unx_time) || $ujic_count > 0 ) {
            return $content;
        } else {

            //get all vars
            $get_vars = self::uji_vars();
            
            foreach ($get_vars as $nm => $var){
                 ${$nm} = $this->sel_ujic_db( $id, $var );
            }
                    
            $ujic_id = 'ujiCountdown';
            $classh = !empty( $ujic_style ) ? ' ujic-' . $ujic_style : '';
            $hclass =!empty( $class ) ? ' ujic_' . $class . '' : '';
            
            //Days Cicle
            $exp_time = strtotime($expire);
            $post_time = strtotime($ujic_post);
            $difference =  $exp_time - $post_time;
            $difference = ($difference < 0) ? $difference = 0 : $difference;
            $exp_d =  floor($difference/60/60/24);
            $exp_days = !empty($exp_d) ? $exp_d : "2000";

            //enqueue
            wp_enqueue_style( $this->plugin_slug . '-uji-countdown' );
            wp_enqueue_script( $this->plugin_slug . '-core' );
            wp_localize_script( $this->plugin_slug . '-init', 'ujiCount', array(
                'uji_plugin' => plugins_url(),
                'uji_style' => $ujic_style,
                'expire' => $expire,
                'exp_days'=> $exp_days,
                'Years' => ( $this->ujic_get_option('ujic_years') ) ? $this->ujic_get_option('ujic_years')  : __( "Years", $this->plugin_slug ),
                'Year' => ( $this->ujic_get_option('ujic_year') ) ? $this->ujic_get_option('ujic_year')  : __( "Year", $this->plugin_slug ),
                'Months' => ( $this->ujic_get_option('ujic_months') ) ? $this->ujic_get_option('ujic_months')  : __( "Months", $this->plugin_slug ),
                'Month' => ( $this->ujic_get_option('ujic_month') ) ? $this->ujic_get_option('ujic_month')  : __( "Month", $this->plugin_slug ),
                'Weeks' => ( $this->ujic_get_option('ujic_weeks') ) ? $this->ujic_get_option('ujic_weeks')  : __( "Weeks", $this->plugin_slug ),
                'Week' => ( $this->ujic_get_option('ujic_week') ) ? $this->ujic_get_option('ujic_week')  : __( "Week", $this->plugin_slug ),
                'Days' => ( $this->ujic_get_option('ujic_days') ) ? $this->ujic_get_option('ujic_days')  : __( "Days", $this->plugin_slug ),
                'Day' => ( $this->ujic_get_option('ujic_day') ) ? $this->ujic_get_option('ujic_day')  : __( "Day", $this->plugin_slug ),
                'Hours' => ( $this->ujic_get_option('ujic_hours') ) ? $this->ujic_get_option('ujic_hours')  :  __( "Hours", $this->plugin_slug ),
                'Hour' => ( $this->ujic_get_option('ujic_hour') ) ? $this->ujic_get_option('ujic_hour')  :  __( "Hour", $this->plugin_slug ),
                'Minutes' => ( $this->ujic_get_option('ujic_minutes') ) ? $this->ujic_get_option('ujic_minutes')  : __( "Minutes", $this->plugin_slug ),
                'Minute' => ( $this->ujic_get_option('ujic_minute') ) ? $this->ujic_get_option('ujic_minute')  : __( "Minute", $this->plugin_slug ),
                'Seconds' => ( $this->ujic_get_option('ujic_seconds') ) ? $this->ujic_get_option('ujic_seconds')  : __( "Seconds", $this->plugin_slug ),
                'Second' => ( $this->ujic_get_option('ujic_second') ) ? $this->ujic_get_option('ujic_second')  : __( "Second", $this->plugin_slug ),
                'ujic_txt_size' => $ujic_txt_size,
                'ujic_thick' => $ujic_thick,
                'ujic_col_dw' => $ujic_col_dw,
                'ujic_col_up' => $ujic_col_up,
                'ujic_col_txt' => $ujic_col_txt,
                'ujic_col_sw' => $ujic_col_sw,
                'ujic_col_lab' => $ujic_col_lab,
                'ujic_lab_sz' => $ujic_lab_sz,
                'ujic_txt' => $ujic_txt,
                'ujic_ani' => $ujic_ani,
                'ujic_url' => $url,
                'ujic_goof' => $ujic_goof,
                'uji_center' => $classh,
                'ujic_d' => $ujic_d, //Main format: Days
                'ujic_h' => $ujic_h, //Main format: Hours
                'ujic_m' => $ujic_m, //Main format: Minutes
                'ujic_s' => $ujic_s, //Main format: Seconds
                'ujic_y' => $ujic_y, //Secondary format: Years
                'ujic_o' => $ujic_o, //Secondary format: Months
                'ujic_w' => $ujic_w, //Secondary format: Weeks
                'uji_time' => date_i18n( 'M j, Y H:i:s ' ) . "+0000",
                'uji_hide' => ($hide == "true") ? 'true' : 'false',
                'ujic_rtl' => ( $this->ujic_get_option('ujic_rtl') ) ? $this->ujic_get_option('ujic_rtl')  : false
            ) );

            wp_enqueue_script( $this->plugin_slug . '-init' );
            $ujic_count ++;
            return strip_shortcodes( '<div class="ujic-hold' . $hclass . '"> <div class="ujiCountdown' . $classh . '" id="' . $ujic_id . '"></div></div>' . $content );
            
        }
    }


}

?>