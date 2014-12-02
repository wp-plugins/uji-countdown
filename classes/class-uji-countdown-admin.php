<?php

/**
 *
 * Plugin Name: Uji Countdown Premium Plus 2.0 
 * Plugin URI: http://www.wpmanage.com/uji-countdown/
 * Description: HTML5 Countdown.
 * Version: 2.0
 * Author: WPmanage <info@wpmanage.com>
 * Author URI: http://www.wpmanage.com
 */
if ( !defined( 'ABSPATH' ) )
   exit; // Exit if accessed directly

class Uji_Countdown_Admin {

   /**
    * Style
    *
    * @since   2.0
    *
    * @var     string
    */
   protected $styles = array( 'classic' );
   
   /**
    * Init label vars
    *
    * @since     2.0
    */
   public static function ujic_labels() {
        return array(   'ujic_years' => 'Years',
                        'ujic_year' => 'Year',
                        'ujic_months' => 'Months',
                        'ujic_month' => 'Month',
                        'ujic_weeks' => 'Weeks',
                        'ujic_week' => 'Week',
                        'ujic_days' => 'Days',
                        'ujic_day' => 'Day',
                        'ujic_hours' => 'Hours',
                        'ujic_hour' => 'Hour',
                        'ujic_minutes' => 'Minutes',
                        'ujic_minute' => 'Minute',
                        'ujic_seconds' => 'Seconds',
                        'ujic_second' => 'Second',
                     );
    }

   /**
    * Print template of table counters.
    *
    * @since    2.0
    */
   public function admin_tablelist() {

      $this->cform_delete();

      if ( $this->saved_db_style() ) {

         $table_headers = '
            	<th class="manage-column" scope="col"><span>' . __( 'Date', $this->plugin_slug ) . '</span></th>
            	<th class="manage-column" scope="col"><span>' . __( 'Name', $this->plugin_slug ) . '</span></th>
          		<th class="manage-column" scope="col"><span>' . __( 'Style', $this->plugin_slug ) . '</span></th>
				<th class="manage-column" scope="col"><span>' . __( 'Change', $this->plugin_slug ) . '</span></th>';

         $tab = '<div id="ujic_table" class="list">
				<a href="?page=uji-countdown&tab=tab_ujic_new" class="button button-primary" id="ujic_table_new">' . __( 'Create a new timer style', $this->plugin_slug ) . '</a>
	            <table cellspacing="0" class="widefat fixed">
                    <thead>
                        <tr>
							' . $table_headers . '
						</tr>
                    </thead>
                    <tfoot>
                        <tr>
                            ' . $table_headers . '
                        </tr>
                    </tfoot>

                    <tbody>
						' . $this->ujic_tabs_values() . '
					<tbody>
				</table>
				</div>';

         echo $tab;
         
          $this->pro_metaboxes();
       
         
      } else {
         //echo '<div class="ujic-create"><a href="?page=uji-countdown&tab=tab_ujic_new" class="button button-primary" id="ujic_table_new">' . __( 'Create a new timer style', $this->plugin_slug ) . '</a></div>';
         echo '<div id="ujic_new"><h1>Uji Countdown 2.0</h1><h4>The most customizable countdown plugin for Wordpress</h4>';
         echo '<a href="?page=uji-countdown&tab=tab_ujic_new" class="ujic_butnew" id="ujic_table_new">' . __( 'Add New Style', $this->plugin_slug ) . '</a>';
         echo '<div class="ujic_new_cnt"><h2>WHAT\'S NEW</h2>';
         echo '<ul>
                  <li>
                     <img alt="security shield" src="'.UJICOUNTDOWN_URL.'assets/images/icon-custom.png">
                     <h3>More Customization</h3>
                     <p>Option to enable/disable the units of the time</p>
				   <p>Option to change the label color and size</p>	 
                  </li>
                  <li>
                     <img alt="security shield" src="'.UJICOUNTDOWN_URL.'assets/images/icon-glob.png">
                     <h3>Multilanguage</h3>
                     <p>This plugins come with translation capability. That means can be translated (aka localized) to other languages </p>
                     <p>Quick translation for the units of time </p>
                  </li>
                  <li>
                     <img alt="security shield" src="'.UJICOUNTDOWN_URL.'assets/images/icon-font.png">
                     <h3>Google Fonts</h3>
                     <p>Now support google fonts inclusion</p>
                  </li>
                  <li>
                     <img alt="security shield" src="'.UJICOUNTDOWN_URL.'assets/images/icon-rtl.png">
                     <h3>Right-To-Left (RTL)</h3>
                     <p>Support “Left to Right” to Arabic “Right to Left” </p>
                  </li>
                  <li>
                     <img alt="security shield" src="'.UJICOUNTDOWN_URL.'assets/images/icon-wp.png">
                     <h3>WordPress 4.0</h3>
                     <p>Fully supports WordPress 4.0, while maintaining compatibility through version 3.5+</p>
                  </li>
               </ul>';
         echo '</div></div>';
         
         $this->pro_metaboxes();
      }
     
      //Left Metaboxes
      if( isset($_GET['tab']) && $_GET['tab'] == 'tab_ujic_new' )
          $this->left_metaboxes();
      
   }

   /**
    * Print template new/edit countdown.
    *
    * @since    2.0
    */
   public function admin_countdown() {

      //Save/Edit in database 
      $this->cform_save_db();

      //ID
      $cur_id = ( $this->cform_is_edit() ) ? $_GET['edit'] : '';

      //Get vars
      $vars = $this->ujic_option( $cur_id );

      //Curent style
      $cur_style = ( $this->cform_is_edit() ) ? $vars['ujic_style'] : ( ( isset( $_GET['style'] ) && !empty( $_GET['style'] ) ) ? $_GET['style'] : 'classic' );


      //Build Forms
      $cnt = '<form method="post" action="page=uji-countdown&tab=tab_ujic_new&style=' . $cur_style . '&save=true">';
      $cnt = $this->cform_ftype( $cur_style, $cur_id );
      //$cnt .= $this->cform_style( $cur_style );
            $cnt .= '<input name="ujic_style" id="ujic-style" type="hidden" class="normal-text" value="' . $cur_style . '"/>';
      $cnt .= $this->cform_input( __( 'Timer Title:', $this->plugin_slug ), 'ujic_name', $vars['ujic_name'] );
      $cnt .= $this->cform_select( __( 'Google Font:', $this->plugin_slug ), 'ujic_goof', ujic_googlefonts(), $vars['ujic_goof'] );
      $cnt .= $this->cform_radiobox( __( 'Alignment:', $this->plugin_slug ), 'ujic_pos', array( __( 'None', $this->plugin_slug ), __( 'Left', $this->plugin_slug ), __( 'Center', $this->plugin_slug ), __( 'Right', $this->plugin_slug ) ), array( 'none', 'left', 'center', 'right' ), $vars['ujic_pos'] );
      $cnt .= $this->cform_checkbox( __( 'Main format:', $this->plugin_slug ), array( 'ujic_d', 'ujic_h', 'ujic_m', 'ujic_s' ), array( __( 'Days', $this->plugin_slug ), __( 'Hours', $this->plugin_slug ), __( 'Minutes', $this->plugin_slug ), __( 'Seconds', $this->plugin_slug ) ), array( $vars['ujic_d'], $vars['ujic_h'], $vars['ujic_m'], $vars['ujic_s'] ) );
      $cnt .= $this->cform_checkbox( __( 'Secondary format:', $this->plugin_slug ), array( 'ujic_y', 'ujic_o', 'ujic_w' ), array( __( 'Years', $this->plugin_slug ), __( 'Months', $this->plugin_slug ), __( 'Weeks', $this->plugin_slug ) ), array( $vars['ujic_y'], $vars['ujic_o'], $vars['ujic_w'] ) );
      if ( $cur_style == 'classic' )
         $cnt .= $this->cform_checkbox( __( 'Animation for seconds:', $this->plugin_slug ), array( 'ujic_ani' ), array( '' ), array( $vars['ujic_ani'] ) );
      $cnt .= $this->cform_checkbox( __( 'Display time label text:', $this->plugin_slug ), array( 'ujic_txt' ), array( '' ), array( $vars['ujic_txt'] ) );
      if ( $cur_style == 'classic' )
         $cnt .= $this->cform_sliderui( __( 'Timer Size:', $this->plugin_slug ), 'ujic_size', $vars['ujic_size'], 10, 80, 1 );
      if ( $cur_style == 'classic' )
         $cnt .= $this->cform_color( __( 'Select Box Color:', $this->plugin_slug ), array( 'ujic_col_dw', 'ujic_col_up' ), array( __( 'Bottom', $this->plugin_slug ), __( 'Up', $this->plugin_slug ) ), array( $vars['ujic_col_dw'], $vars['ujic_col_up'] ) );
      if ( $cur_style == 'classic' )
         $cnt .= $this->cform_color( __( 'Text Color:', $this->plugin_slug ), array( 'ujic_col_txt', 'ujic_col_sw' ), array( __( 'Number Color', $this->plugin_slug ), __( 'Shadow Color', $this->plugin_slug ) ), array( $vars['ujic_col_txt'], $vars['ujic_col_sw'] ) );
      
      $cnt .= $this->cform_color( __( 'Label Color:', $this->plugin_slug ), array( 'ujic_col_lab' ), array( __( 'Label Text Color', $this->plugin_slug ) ), array( $vars['ujic_col_lab'] ) );
      $cnt .= $this->cform_sliderui( __( 'Label Size:', $this->plugin_slug ), 'ujic_lab_sz', $vars['ujic_lab_sz'], 8, 25, 1 );
      $cnt .= $this->cform_buttons();
      $cnt .= '</form>';
      //Build Metabox
      if ( $cur_id )
         echo $this->custom_metabox( __( 'Edit Timer Style', $this->plugin_slug ), $cnt, 'ujic-create uji-fedit' );
      else
         echo $this->custom_metabox( __( 'Create New Timer Style', $this->plugin_slug ), $cnt, 'ujic-create' );

      //Left Metaboxes
      $this->left_metaboxes();

      //Preview Metaboxes
      $this->prev_metaboxes( $cur_style );
      
   }

   /**
    * Custom Metabox template.
    *
    * @since    2.0
    */
   private function custom_metabox( $name, $cnt, $class = NULL ) {
      $meta = '<div class="metabox-holder' . ( ( isset( $class ) && !empty( $class ) ) ? ' ' . $class : '' ) . '">';
      $meta .= '<div class="postbox">';
      $meta .= '<div class="handlediv" title="Click to toggle"><br/></div>';
      $meta .= '<h3 class="hndle"><span>' . $name . '</span></h3>';
      $meta .= '<div class="inside">';
      $meta .= $cnt;
      $meta .= '</div>';
      $meta .= '</div>';
      $meta .= '</div>';

      return $meta;
   }

   /**
    * Multi Custom Metabox template.
    *
    * @since    2.0
    */
   private function multi_custom_metabox( $name, $cnt, $class = NULL ) {
      $meta = '<div class="metabox-holder' . ( ( isset( $class ) && !empty( $class ) ) ? ' ' . $class : '' ) . '">';
      $i = 0;
      foreach ( $cnt as $content ) {
         $meta .= '<div class="postbox">';
         $meta .= '<div class="handlediv" title="Click to toggle"><br/></div>';
         $meta .= '<h3 class="hndle"><span>' . $name[$i] . '</span></h3>';
         $meta .= '<div class="inside">';
         $meta .= $content;
         $meta .= '</div>';
         $meta .= '</div>';
         $i++;
      }
      $meta .= '</div>';

      return $meta;
   }

   /**
    * Tutorial metaboxes.
    *
    * @since    2.0
    */
   private function prev_metaboxes( $style ) {
      if ( $style == 'classic' ) {
         $prw = '<div class="ujic-' . $style . ' hasCountdown" id="ujiCountdown">
                  <span class="countdown_row">
                     <span class="countdown_section ujic_y">
                        <span class="countdown_amount">0</span>
                        <span class="countdown_amount">1</span>
                        <span class="countdown_txt">' . __( 'Years', $this->plugin_slug ) . '</span>
                     </span>
                     <span class="countdown_section ujic_o">
                        <span class="countdown_amount">1</span>
                        <span class="countdown_amount">1</span>
                        <span class="countdown_txt">' . __( 'Months', $this->plugin_slug ) . '</span>
                     </span>
                     <span class="countdown_section ujic_w">
                        <span class="countdown_amount">0</span>
                        <span class="countdown_amount">2</span>
                        <span class="countdown_txt">' . __( 'Weeks', $this->plugin_slug ) . '</span>
                     </span>
                     <span class="countdown_section ujic_d">
                        <span class="countdown_amount">2</span>
                        <span class="countdown_amount">9</span>
                        <span class="countdown_txt">' . __( 'Days', $this->plugin_slug ) . '</span>
                     </span>
                     <span class="countdown_section ujic_h">
                        <span class="countdown_amount">0</span>
                        <span class="countdown_amount">9</span>
                        <span class="countdown_txt">' . __( 'Hours', $this->plugin_slug ) . '</span>
                     </span>
                     <span class="countdown_section ujic_m">
                        <span class="countdown_amount">3</span>
                        <span class="countdown_amount">1</span>
                        <span class="countdown_txt">' . __( 'Minutes', $this->plugin_slug ) . '</span>
                     </span>
                     <span class="countdown_section ujic_s">
                        <span class="countdown_amount">5</span>
                        <span class="countdown_amount">3</span>
                        <span class="countdown_txt">' . __( 'Seconds', $this->plugin_slug ) . '</span>
                     </span>
                  </span>
               </div>';
      }
      
      echo $this->custom_metabox( __( 'Preview Timer Style', $this->plugin_slug ), $prw, 'ujic-preview' );
   }

   /**
    * Tutorial metaboxes.
    *
    * @since    2.0
    */
   private function left_metaboxes() {
      $tut_sho = '<img src="' . UJICOUNTDOWN_URL . 'assets/images/ujic-ps.jpg">';
      $tut_wid = '<img src="' . UJICOUNTDOWN_URL . 'assets/images/ujic-ps2.jpg">';
      echo $this->multi_custom_metabox( array( __( 'How To Add Countdown Shortcode', $this->plugin_slug ), __( 'Add New Countdown <br>from the Widget Areas', $this->plugin_slug ) ), array( $tut_sho, $tut_wid ), 'ujic-tut' );
   }
   
   /**
    * Premium metaboxes.
    *
    * @since    2.0
    */
   private function pro_metaboxes() {
      $pro_sho = '<a href="http://wpmanage.com/uji-countdown" target="_blank"><img src="' . UJICOUNTDOWN_URL . 'assets/images/ujic-ps3.png"></a>';
      echo $this->multi_custom_metabox( array( __( 'Upgrade to PRO Version', $this->plugin_slug ) ), array( $pro_sho ), 'ujic-tut' );
   }

   /**
    * Print form style.
    *
    * @since    2.0
    */
   private function cform_style( $val ) {
      $styles = $this->styles;
      $form = '<div class="ujic-box">';
      if ( $this->cform_is_edit() ) {
         $form .= '<div class="label">' . __( "Style Type:", $this->plugin_slug ) . '</div>';
         $form .= '<span id="ujic-style-' . $val . '" class="ujic-types ujic-types-sel">' . $val . '</span>';
      } else {
         $form .= '<div class="label">' . __( "Select Style:", $this->plugin_slug ) . '</div>';
         foreach ( $styles as $style ) {
            $sel = ( $style == ( isset( $_GET['style'] ) && !empty( $_GET['style'] ) ? $_GET['style'] : 'classic' ) ) ? ' ujic-types-sel' : '';
            $form .= '<a href="#" onclick="sel_style(\'' . $style . '\')" id="ujic-style-' . $style . '" class="ujic-types' . $sel . '">' . $style . '</a>';
         }
      }
      $form .= '<input name="ujic_style" id="ujic-style" type="hidden" class="normal-text" value="' . $val . '"/>';
      $form .= '</div>';
      return $form;
   }

   /**
    * Print title.
    *
    * @since    2.0
    */
   private function cform_title( $title ) {
      $form  = '<div class="ujic-box">';
      $form .= '<h3 style="padding-left: 0">' . $title . '</h3>';
      $form .= '</div>';
      return $form;
   }
   
   /**
    * Print input field.
    *
    * @since    2.0
    */
   private function cform_input( $label, $name, $val, $cls = null ) {
      $form = '<div class="ujic-box">';
      $form .= '<div class="label">' . $label . '</div>';
      $form .= '<input type="text" value="' . $val . '" name="' . $name . '" id="' . $name . '" class="' . ($cls ? $cls : 'regular-text') . '">';
      $form .= '</div>';
      return $form;
   }

   /**
    * Print checkbox field.
    *
    * @since    2.0
    */
   private function cform_checkbox( $label, $names, $name_val, $val ) {
      $form = '<div class="ujic-box">';
      $form .= '<div class="label">' . $label . '</div>';
      $form .= '<div class="ujic-chkbtn">';
      $i = 0;
      foreach ( $names as $name ) {
         $form .= '<input id="' . $name . '" type="checkbox" value="true" class="icheckbox_flat-pink" name="' . $name . '" ' . checked( $val[$i], "true", false ) . '>';
         $form .= '<label for="' . $name . '">' . $name_val[$i] . '</label>';
         $i++;
      }
      $form .= '</div>';
      $form .= '</div>';
      return $form;
   }

   /**
    * Print radio field.
    *
    * @since    2.0
    */
   private function cform_radiobox( $label, $name, $name_val, $types, $val ) {
      $form = '<div class="ujic-box">';
      $form .= '<div class="label">' . $label . '</div>';
      $form .= '<div class="ujic-radbtn">';
      $i = 0;
      foreach ( $types as $type ) {
         $form .= '<input id="' . $type . '" type="radio" value="' . $type . '" class="iradio_flat-pink" name="' . $name . '" ' . checked( $val, $type, false ) . '>';
         $form .= '<label for="' . $name . '" id="img-' . $type . '">' . $name_val[$i] . '</label>';
         $i++;
      }
      $form .= '</div>';
      $form .= '</div>';
      return $form;
   }

   /**
    * Print select field.
    *
    * @since    2.0
    */
   private function cform_select( $label, $name, $types, $val ) {
      $form = '<div class="ujic-box">';
      $form .= '<div class="label">' . $label . '</div>';
      $form .= '<div class="ujic-select">';
      $form .= '<select class="select of-input" name="' . $name . '" id="' . $name . '">';
      foreach ( $types as $type => $option ) {
         $form .= '<option id="' . sanitize_text_field( $type ) . '" value="' . $type . '" ' . selected( $type, $val, false ) . ' />' . $option . '</option>';
      }
      $form .= '</select></div>';
      $form .= '</div>';
      return $form;
   }

   /**
    * Print slider-ui field.
    *
    * @since    2.0
    */
   private function cform_sliderui( $label, $name, $val, $min, $max, $step ) {
      $form = '<div class="ujic-box ujic_slider">';
      $form .= '<div class="label">' . $label . '</div>';
      //values
      $val = ($val == '') ? 32 : $val;
      $data = 'data-id="' . $name . '" data-val="' . $val . '" data-min="' . $min . '" data-max="' . $max . '" data-step="' . $step . '"';
      //html output
      $form .= '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $val . '" class="mini" readonly="readonly" />';
      $form .= '<div id="' . $name . '-slider" class="ujic_sliderui" style="margin-left: 7px;" ' . $data . '></div>';
      $form .= '</div>';
      return $form;
   }

   /**
    * Color picker field.
    *
    * @since    2.0
    */
   private function cform_color( $label, $names, $clabels, $vals ) {
      $form = '<div class="ujic-box ujic-color">';
      $form .= '<div class="label">' . $label . '</div>';
      $form .= '<div class="ujic-color-box">';
      $i = 0;
      foreach ( $names as $name ) {
         //values
         $default_color = ' data-default-color="' . $vals[$i] . '" ';

         $form .= '<div class="ujic-color-hold">';
         $form .= '<span> ' . $clabels[$i] . ' :</span>';
         $form .= '<input name="' . $name . '" id="' . $name . '" class="ujic_colorpick"  type="text" value="' . $vals[$i] . '"' . $default_color . ' />';
         $form .= '</div>';
         $i++;
      }
      $form .= '</div>';
      $form .= '</div>';
      return $form;
   }

   /**
    * Add buttons.
    *
    * @since    2.0
    */
   private function cform_buttons() {
      $type = ( isset( $_GET['edit'] ) && !empty( $_GET['edit'] ) ) ? $_GET['edit'] : '';
      $form = '<div class="ujic-submit-hold">';
      if ( !empty( $type ) && is_numeric( $type ) ) {
         $form .= get_submit_button( __( 'Update Style', $this->plugin_slug ), 'primary', 'submit_ujic', true );
         //$form .= get_submit_button(__( 'Cancel Add New', $this->plugin_slug ), 'secondary', 'cancel_ujic', true);
         $form .= '<a href="?page=uji-countdown&tab=tab_ujic_new" class="button button-secondary" id="ujic_table_new">' . __( 'Cancel Add New', $this->plugin_slug ) . '</a>';
      } else {
         $form .= get_submit_button( __( 'Save Style', $this->plugin_slug ), 'primary', 'submit_ujic', true );
      }
      $form .= '</div>';

      return $form;
   }

   /**
    * Form Type.
    *
    * @since    2.0
    */
   private function cform_ftype( $cur_style, $id = NULL ) {
      $type = ( isset( $_GET['edit'] ) && !empty( $_GET['edit'] ) ) ? $_GET['edit'] : '';

      if ( !empty( $type ) && is_numeric( $type ) && !empty( $id ) ) {
         $form = '<form method="post" action="options-general.php?page=uji-countdown&tab=tab_ujic_new&edit=' . $id . '">';
      } else {
         $form = '<form method="post" action="options-general.php?page=uji-countdown&tab=tab_ujic_new&style=' . $cur_style . '&save=true">';
      }

      return $form;
   }

   /**
    * Insert/Edit database values.
    *
    * @since    2.0
    */
   private function cform_save_db() {
      if ( $this->cform_is_create() ) {
         if ( $this->cform_errors() ) {
            $this->ins_ujic_db( $_POST );
            $this->ujic_message( __( "Your Timer Style Was Created", $this->plugin_slug ) );
            echo '<script type="text/javascript"> ujic_admin_home(); </script>';
         }
      }
      if ( isset( $_POST ) && !empty( $_POST ) && $this->cform_is_edit() ) {
         if ( $this->cform_errors() ) {
            $this->upd_ujic_db( $_POST, $_GET['edit'] );
            $this->ujic_message( __( "Your Timer Style Was Updated", $this->plugin_slug ) );
         }
      }
   }

   /**
    * Errors check.
    *
    * @since    2.0
    */
   private function cform_errors() {
      global $wpdb;
      $ujic_form_err = '';

      //name not empty
      if ( empty( $_POST['ujic_name'] ) ) {
         $ujic_form_err .= __( "Please Complete Timer Title", $this->plugin_slug ) . '<br/>';
      }

      //check format
      if ( !isset( $_POST['ujic_d'] ) && !isset( $_POST['ujic_h'] ) && !isset( $_POST['ujic_m'] ) && !isset( $_POST['ujic_s'] ) && !isset( $_POST['ujic_y'] ) && !isset( $_POST['ujic_o'] ) && !isset( $_POST['ujic_w'] ) ) {
         $ujic_form_err .= __( "Please Select Timer Format", $this->plugin_slug ) . '<br/>';
      }

      //check name exist
      if ( !empty( $_POST['ujic_name'] ) && !$this->cform_is_edit() ) {
         $cname = $wpdb->get_var( "SELECT title FROM " . $this->ujic_tab_name() . " WHERE title = '" . $_POST['ujic_name'] . "'" );
         if ( !empty( $cname ) ) {
            $ujic_form_err .= __("This Name Already Exist. Please Change Timer Name.  <br/>", $this->plugin_slug);
         }
      }

      if ( empty( $ujic_form_err ) ) {
         return true;
      } else if ( !empty( $ujic_form_err ) ) {
         $this->ujic_message( $ujic_form_err, true );
         return false;
      }
   }

   /**
    * Check if have saved style.
    *
    * @since    2.0
    */
   private function saved_db_style() {
      global $wpdb;
      $cname = $wpdb->get_var( "SELECT title FROM " . $this->ujic_tab_name() . " LIMIT 1" );
      if ( !empty( $cname ) ) {
         return true;
      } else {
         return false;
      }
   }

   /**
    * Return If Create Form.
    *
    * @since    2.0
    */
   public function cform_is_create() {
      if ( isset( $_POST ) && !empty( $_POST ) && isset( $_GET['save'] ) && !empty( $_GET['save'] ) && $_GET['save'] == 'true' )
         return true;
      else
         return false;
   }

   /**
    * Return Edit Form.
    *
    * @since    2.0
    */
   private function cform_is_edit() {
      if ( isset( $_GET['edit'] ) && (!empty( $_GET['edit'] ) && is_numeric( $_GET['edit'] ) ) )
         return true;
      else
         return false;
   }

   /**
    * Return Delete Form.
    *
    * @since    2.0
    */
   private function cform_delete() {
      if ( isset( $_GET['del'] ) && (!empty( $_GET['del'] ) && is_numeric( $_GET['del'] ) ) ) {
         $this->del_ujic_db( trim( $_GET['del'] ) );
         $this->ujic_message( __("Your countdown style was deleted", $this->plugin_slug) );
      }
   }

   /**
    * Creating The Tabs.
    *
    * @since    2.0
    */
   private function ujic_tabs_values() {
      global $wpdb;
      $ujictab = '';
      $table_name = $wpdb->prefix . "uji_counter";
      $ujic_datas = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY `time` DESC" );
      if ( !empty( $ujic_datas ) ) {
         foreach ( $ujic_datas as $ujic ) {
            $ujic_style = !empty( $ujic->style ) ? $ujic->style : 'classic';
            $ujic_ico = '<span id="ujic-style-' . $ujic_style . '" class="ujic-types">' . $ujic_style . '</span>';
            $ujictab .='<tr>
								<td>' . $ujic->time . '</td>
								<td>' . $ujic->title . '</td>
								<td>' . $ujic_ico . '</td>
								<td>
                           <a href="?page=uji-countdown&tab=tab_ujic_new&edit=' . $ujic->id . '"><i class="dashicons dashicons-welcome-write-blog"></i>Edit</a> | <a href="options-general.php?page=uji-countdown&del=' . $ujic->id . '"><i class="dashicons dashicons-trash"></i> Delete</a>
                       </td>
							</tr>';
         }
      }

      return $ujictab;
   }

   /**
    * Message Notification.
    *
    * @since    2.0
    */
   private function ujic_message( $message, $errormsg = false ) {
      if ( $errormsg ) {
         echo '<div id="message" class="error">';
      } else {
         echo '<div id="message" class="updated fade">';
      }

      echo "<p><strong>$message</strong></p></div>";
   }

   /**
    * Timer settings
    *
    * @since    2.0
    */
   public function admin_timerset() {
      //Save data
      $this->save_timerset();
      //Get data
      $vars = $this->get_timerset();

      //Build Forms
      $cnt  = '<form method="post" action="options-general.php?page=uji-countdown&tab=tab_ujic_set&saveset=true">';
      
      $cnt .= $this->cform_checkbox( __( "Right-To-Left (RTL):", $this->plugin_slug ), array( 'ujic_rtl' ), array( __("Writing starts from the right of the page and continues to the left.", $this->plugin_slug ) ), array( ( isset($vars['ujic_rtl']) ? $vars['ujic_rtl'] : false ) ) );
      $cnt .=  $this->cform_title( __( "Quick Translation", $this->plugin_slug ) );
      
      $labels = self::ujic_labels();
      
      foreach ( $labels as $v => $n ){
         $cnt .= $this->cform_input( __( $n.':', $this->plugin_slug ), $v, $vars[$v], 'default-text' );
      }
      $cnt .= get_submit_button( __( "Save Changes", $this->plugin_slug ), 'primary', 'submit_ujic', true );
      
      $cnt .= '</form>';
      
      echo $this->custom_metabox( __( "Timer Settings", $this->plugin_slug ), $cnt, 'ujic-create ujic-settings' );
   }
   
   /**
    * Save timer settings
    *
    * @since    2.0
    */
   public function save_timerset() {
      if ( isset( $_POST ) && !empty( $_POST ) && isset( $_GET['saveset'] ) && !empty( $_GET['saveset'] ) && $_GET['saveset'] == 'true' ){
         $settings =  $_POST;
         unset($settings['submit_ujic']); 
         update_option('ujic_set', $settings);
         $this->ujic_message( __("Settings saved.", $this->plugin_slug ) );
      }elseif( isset( $_POST ) && !empty( $_POST ) ){
         $this->ujic_message( __("Some error occured. Please try again.", $this->plugin_slug ) );
      }   
   }
   
   /**
    * Get timer settings
    *
    * @since    2.0
    */
   public function get_timerset( $name = null ) {
      $vars = get_option('ujic_set');
      if($name)
         return $vars[$name];
      else
         return $vars;      
   }   

}