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
                        'url' => "",
                        'subscr' => "",
                        'recurring' => "",
                        'rectype' => "",
                        'repeats' => ""
                        ), $atts ) );
        //Increment counters
        static $ujic_count = 0;

        $rectime = false;

        //2015/03/24 05:05
        $unx_time = strtotime( $expire . ":00" );
        $now_time = (int) current_time( 'timestamp' );
        
        //Reccuring time
        if( $rectype && $recurring && is_numeric( $recurring ) ){
            //add multiple hour -> hours
            $rectype = intval($recurring) > 1 ? $rectype.'s' : $rectype;
             
            //Repeats
            if( $repeats && intval($repeats) > 0 ){
                
                //add time
                for( $t=1; $t<=intval($repeats); $t++){
                    $ujictime = strtotime( '+' . ($recurring*$t) . ' ' . $rectype, $unx_time ); 
                    if( $now_time < $ujictime){
                        $rectime = true;
                        break;
                    }
                }
                
            }else{
                 //init time
                 $ujictime = strtotime( '+' . $recurring . ' ' . $rectype, $unx_time );
                 $t = 1;
                 //repeat unlimited times
                 while( $now_time > $ujictime){
                     $ujictime = strtotime( '+' . ($recurring*$t) . ' ' . $rectype, $unx_time );
                     $t++;
                 }
                 $rectime = true;
            }
            
        }
        //End Reccuring

        if ( ($hide == "true" && $now_time > $unx_time && !$rectime) || $ujic_count > 0 ) {
            
            return $content;
            
        } else {
            
            //reccuring time
            if($rectime)
                $expire = date('Y/m/d H:i', $ujictime); //2015/03/24 05:05

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
                'ajaxUrl' => admin_url('admin-ajax.php'),
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
                'uji_time' => date_i18n( 'M j, Y H:i:s' ) ."+0000",
                'uji_hide' => ($hide == "true") ? 'true' : 'false',
                'ujic_rtl' => ( $this->ujic_get_option('ujic_rtl') ) ? $this->ujic_get_option('ujic_rtl')  : false,
                'uji_utime' => ( $this->ujic_get_option('ujic_utime') ) ? $this->ujic_get_option('ujic_utime')  : false
            ) );

            wp_enqueue_script( $this->plugin_slug . '-init' );

            $ujic_count ++;

            $ujic_subscrFrmIsEnabled = filter_var($this->sel_ujic_db( $id, 'ujic_subscrFrmIsEnabled' ), FILTER_VALIDATE_BOOLEAN);

            if(!$ujic_subscrFrmIsEnabled)
            {
                return strip_shortcodes( '<div class="ujic-hold' . $hclass . '"> <div class="ujiCountdown' . $classh . '" id="' . $ujic_id . '"></div></div>' . $content );
            }
            
            wp_enqueue_script( $this->plugin_slug . '-uji-countdown-newsletter' );

            $ujic_subscrFrmWidth         = trim($this->sel_ujic_db( $id, 'ujic_subscrFrmWidth' ));
            $ujic_subscrFrmAboveText     = trim($this->sel_ujic_db( $id, 'ujic_subscrFrmAboveText' ));
            $ujic_subscrFrmInputText     = trim($this->sel_ujic_db( $id, 'ujic_subscrFrmInputText' ));
            $ujic_subscrFrmSubmitText    = trim($this->sel_ujic_db( $id, 'ujic_subscrFrmSubmitText' ));
            $ujic_subscrFrmSubmitColor   = trim($this->sel_ujic_db( $id, 'ujic_subscrFrmSubmitColor' ));
            $ujic_subscrFrmThanksMessage = trim($this->sel_ujic_db( $id, 'ujic_subscrFrmThanksMessage' ));
            $ujic_subscrFrmErrorMessage  = trim($this->sel_ujic_db( $id, 'ujic_subscrFrmErrorMessage' ));
            $ujic_subscrFrmName          = !empty($subscr) ? $subscr : __('Subscription', $this->plugin_slug);

            $formHtmlCode  = '<div class = "ujicf"></div>';
	        $formHtmlCode .= '<form class = "ujicf" style = "margin-top:' . (empty($ujic_subscrFrmAboveText) ? '10px' : 0) . '">';

            $formHtmlCode .= !empty($ujic_subscrFrmAboveText) ? '<span>' . esc_html__($ujic_subscrFrmAboveText) . '</span>' : '';
            
            $formHtmlCode .= '<div>
	                    <p style = "width:' . (empty($ujic_subscrFrmWidth)  ? '100%' : esc_attr("$ujic_subscrFrmWidth%")) . '">
	                        <input name = "txtEmail" type = "text" placeholder = "' . esc_attr($ujic_subscrFrmInputText) .'"/>
                                <input name = "txtList" type = "hidden"  value = "' . esc_attr($ujic_subscrFrmName) . '" />
	                        <input type = "submit" value = "' . esc_attr($ujic_subscrFrmSubmitText) . '" color-attr="' . esc_attr($ujic_subscrFrmSubmitColor) . '" />
                        </p>
                        <span class = "uji-msg-ok">' . esc_html__($ujic_subscrFrmThanksMessage, $this->plugin_slug) . '</span>
                        <span class = "uji-msg-err">' . esc_html__($ujic_subscrFrmErrorMessage, $this->plugin_slug) . '</span>
					</div>';

            $formHtmlCode .= self::$isGoodByeCaptchaActivated ? GdbcTokenController::getInstance()->getTokenInputField() : '';

            $formHtmlCode .= wp_nonce_field('uji-subscribe-newsletter', 'uji-subscribe-nonce', false, false);

            $formHtmlCode .= '</form>';

	    $htmlCode = strip_shortcodes('<div class="ujic-hold' . $hclass . '"> <div id = "uji-wrapper" class = "ujicf"> <div class="ujicf ujiCountdown' . $classh . '" id="' . $ujic_id . '"></div>'.$formHtmlCode.'</div></div>' . $content );

	    return $htmlCode;
        }
    }


}

?>