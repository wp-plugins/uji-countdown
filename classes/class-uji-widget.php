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

class ujic_Widget extends WP_Widget {

   protected $plugin_slug = 'uji-countdown';
   
   /**
     * Uji Countdown Init
     *
     * @since   2.0
     *
     * @var     string
     */
   public function ujic_Widget() {
      $widget_ops = array(
          'classname' => 'uji_Widget',
          'description' => 'Uji Countdown widget.'
      );
      $this->WP_Widget( 'uji_Widget', 'Uji Countdown', $widget_ops );

      //actions
      add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );
      add_action( 'admin_enqueue_scripts', array( &$this, 'admin_widgets_scripts_styles' ) );
   }

   /**
     * Uji Countdown Admin Scripts
     *
     * @since   2.0
     *
     * @var     string
     */
   public function admin_widgets_scripts_styles( $page ) {
      if ( $page !== 'widgets.php' )
         return;

      wp_enqueue_script( 'jquery-ui-datepicker' );
      wp_enqueue_style( 'jquery-ui', UJICOUNTDOWN_URL . 'assets/tinymce/css/jquery-ui.min.css' );
      wp_enqueue_script( 'jquery-widget', UJICOUNTDOWN_URL . 'assets/js/widget.js' );
   }
   
   /**
     * Uji Countdown Form
     *
     * @since   2.0
     *
     * @var     string
     */
   public function ujic_forms( $sel = NULL ) {
      global $wpdb;
      $table_name = $wpdb->prefix . "uji_counter";
      $ujic_datas = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY `time` DESC" );
      if ( !empty( $ujic_datas ) ) {
         $ujictab = false;
         foreach ( $ujic_datas as $ujic ) {
            $type = !empty( $ujic->style ) ? $ujic->style : "classic";
            $select = (isset( $sel ) && !empty( $sel ) && $sel == $ujic->title ) ? ' selected="selected"' : '';
            $ujictab .='<option value="' . $ujic->title . '" data-type=' . $type . ' ' . $select . '> ' . $ujic->title . ' - ' . $type . ' </option>';
         }
         return $ujictab;
      } else {
         return false;
      }
   }

   /**
     * Uji Countdown Get Time/Date
     *
     * @since   2.0
     *
     * @var     string
     */
   public function ujic_sel_datetime( $nr, $sel = null ) {
      for ( $i = 0; $i <= $nr; $i++ ) {
         $num[sprintf( "%02s", $i )] = sprintf( "%02s", $i );
      }
      $numbers = false;
      foreach ( $num as $n ) {
         $select = (isset( $sel ) && !empty( $sel ) && $sel == $n) ? ' selected="selected"' : '';
         $numbers .='<option value="' . $n . '"' . $select . '> ' . $n . ' </option>';
      }

      return $numbers;
   }
   
   /**
     * Uji Countdown Widget
     *
     * @since   2.0
     *
     * @var     string
     */
   public function widget( $args, $instance ) {
      extract( $args, EXTR_SKIP );

      /* Our variables from the widget settings. */
      $title = apply_filters( 'widget_UJI_title', $instance['UJI_title'], $instance, $this->id_base );
      $name = isset( $instance['UJI_style'] ) ? $instance['UJI_style'] : false;
      $date = isset( $instance['UJI_date'] ) ? $instance['UJI_date'] : false;
      $hour = isset( $instance['UJI_hours'] ) ? $instance['UJI_hours'] : false;
      $minut = isset( $instance['UJI_minutes'] ) ? $instance['UJI_minutes'] : false;
      $hide = isset( $instance['UJI_hide'] ) ? $instance['UJI_hide'] : false;
      $url = isset( $instance['UJI_url'] ) ? $instance['UJI_url'] : false;

      $shtval = '';
      $shtval .= (!empty( $name ) ) ? ' id="' . $name . '"' : $shtval;
      $shtval .= (!empty( $date ) ) ? ' expire="' . $date . ' ' . $hour . ':' . $minut . '"' : $shtval;
      $shtval .= (!empty( $hide ) ) ? ' hide = "true"' : $shtval;
      $shtval .= ( empty( $hide ) && !empty( $url ) ) ? ' url = "' . $url . '"' : $shtval;
    
      $shortcode = (!empty( $shtval ) ) ? '[ujicountdown' . $shtval . ']' : '';

      if ( !empty( $shortcode ) ) {
         echo $before_widget;
         if ( $title )
            echo $before_title . $title . $after_title;
         echo do_shortcode( $shortcode );
         echo $after_widget;
      }
   }
   
   /**
     * Uji Countdown Update
     *
     * @since   2.0
     *
     * @var     string
     */
   public function update( $new_instance, $old_instance ) {
      $instance = $old_instance;
      $instance['UJI_title'] = strip_tags( $new_instance['UJI_title'] );
      $instance['UJI_style'] = strip_tags( $new_instance['UJI_style'] );
      $instance['UJI_date'] = strip_tags( $new_instance['UJI_date'] );
      $instance['UJI_hours'] = strip_tags( $new_instance['UJI_hours'] );
      $instance['UJI_minutes'] = strip_tags( $new_instance['UJI_minutes'] );
      $instance['UJI_hide'] = strip_tags( $new_instance['UJI_hide'] );
      $instance['UJI_url'] = strip_tags( $new_instance['UJI_url'] );

      return $instance;
   }
   
   /**
     * Uji Countdown Form
     *
     * @since   2.0
     *
     * @var     string
     */
   public function form( $instance ) {

      $defaults = array(
          'UJI_title' => '',
          'UJI_style' => false,
          'UJI_date' => '',
          'UJI_hours' => 23,
          'UJI_minutes' => 59,
          'UJI_hide' => '',
          'UJI_url' => ''
      );

      $instance = wp_parse_args( (array) $instance, $defaults );
      ?>
      <p style="font-size:11px">
         Only one timer on page is allowed. <br>Check the <a href="http://wpmanage.com/uji-countdown" target="_blank">Premium version</a> for multiple countdown timers on the same page. 
      <p>
      <!-- Widget Title: Text Input -->
      <p>
         <label for="<?php echo $this->get_field_id( 'UJI_title' ); ?>"><?php _e( 'Title (optional):', $this->plugin_slug ); ?></label>
         <input type="text" name="<?php echo $this->get_field_name( 'UJI_title' ); ?>"  value="<?php echo $instance['UJI_title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'UJI_title' ); ?>" />
      </p>

      <!-- Widget Select Style: Select Input -->
      <p>

      <?php if ( $this->ujic_forms() ): ?>

            <label for="<?php echo $this->get_field_id( 'UJI_style' ); ?>"><?php _e( 'Select a Style:', $this->plugin_slug ); ?></label>
            <select name="<?php echo $this->get_field_name( 'UJI_style' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'UJI_style' ); ?>">
         <?php
         echo $this->ujic_forms( $instance['UJI_style'] );
         ?>
            </select>

            <?php else: ?>        

         <h4 style="color: firebrick"><?php _e( 'Please create new timer style first.', $this->plugin_slug ); ?></h4>

      <?php endif; ?>      

      </p>

      <!-- Widget Date: Text Input -->
      <p>
         <label for="<?php echo $this->get_field_id( 'UJI_date' ); ?>"><?php _e( 'Expire Date:', $this->plugin_slug ); ?></label>
         <input type="text" name="<?php echo $this->get_field_name( 'UJI_date' ); ?>"  value="<?php echo $instance['UJI_date']; ?>"  style="background: url('<?php echo UJICOUNTDOWN_URL ?>/assets/tinymce/images/data-picker.png') no-repeat scroll right top; display:block; height: 26px; width: 180px;" class="widefat ujic_date" id="<?php echo $this->get_field_id( 'UJI_date' ); ?>" />
      </p>

      <!-- Widget Select Time: Select Input -->
      <p>
         <label><?php _e( 'Select the Time:', $this->plugin_slug ); ?></label>
      <div style="display: block;">
         <div style="display: inline-block; margin: 0 5px 0 5px"> 
            <h4 style="margin:0"><?php _e( 'Hour:', $this->plugin_slug ); ?> </h4>
            <select name="<?php echo $this->get_field_name( 'UJI_hours' ); ?>" style="width:50px;" id="<?php echo $this->get_field_id( 'UJI_hours' ); ?>">
      <?php
      echo $this->ujic_sel_datetime( 23, $instance['UJI_hours'] );
      ?>
            </select>
         </div>
         :
         <div style="display: inline-block; margin: 0 5px 0 5px"> 
            <h4 style="margin:0"><?php _e( 'Minute:', $this->plugin_slug ); ?> </h4>
            <select name="<?php echo $this->get_field_name( 'UJI_minutes' ); ?>" style="width:50px;" id="<?php echo $this->get_field_id( 'UJI_minutes' ); ?>">
      <?php
      echo $this->ujic_sel_datetime( 59, $instance['UJI_minutes'] );
      ?>
            </select>
         </div> 
      </div> 
      </p>
      <h4><?php _e( 'After Expiry:', $this->plugin_slug ); ?> </h4>
      <!-- Widget Hide: Checkbox Input -->
      <p>
         <label for="<?php echo $this->get_field_id( 'UJI_hide' ); ?>"><?php _e( 'Hide Countdown:', $this->plugin_slug ); ?></label>  
         <input class="ujic_exp" id="<?php echo $this->get_field_id( 'UJI_hide' ); ?>" name="<?php echo $this->get_field_name( 'UJI_hide' ); ?>" type="checkbox" value="hide" <?php checked( $instance['UJI_hide'], 'hide' ) ?> />
      </p>

      <!-- Widget Go to Link: Select Input -->
      <p>
         <label for="<?php echo $this->get_field_id( 'UJI_url' ); ?>"><?php _e( 'Or go to this link:', $this->plugin_slug ); ?></label><br />
         <small><?php _e( 'Select URL to send after expire', $this->plugin_slug ); ?></small>
         <input class="widefat ujic_link" id="<?php echo $this->get_field_id( 'UJI_url' ); ?>" name="<?php echo $this->get_field_name( 'UJI_url' ); ?>" type="text" value="<?php echo $instance['UJI_url']; ?>" />
      </p>

      <?php
   }

}
?>