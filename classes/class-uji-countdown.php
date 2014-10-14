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

class Uji_Countdown extends Uji_Countdown_Admin {

    /**
     * Uji Countdown
     *
     * @since   2.0
     *
     * @var     string
     */
    protected $pname = UJIC_NAME;

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   2.0
     *
     * @var     string
     */
    protected $version = UJIC_VERS;

    /**
     * Unique identifier for your plugin.
     *
     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since    2.0
     *
     * @var      string
     */
    protected $plugin_slug = 'uji-countdown';

    /**
     * Counter Options.
     *
     * @since    2.0
     *
     * @var      array
     */
    private $options = array();

    /**
     * Instance of this class.
     *
     * @since    2.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    2.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Init.
     *
     * @since    2.0
     *
     * @var      string
     */
    public $uji;

    /**
     * Initialize the plugin by setting localization, filters, and administration functions.
     *
     * @since     2.0
     */
    public function __construct() {
       
       // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
       register_activation_hook( UJICOUNTDOWN_FILE, array( $this, 'activate' ) );
       register_deactivation_hook( UJICOUNTDOWN_FILE, array( $this, 'deactivate' ) );
       
        //Show on front
        if ( !is_admin() ) {
            $ujic = new UjiCountdown();
        }
        // Load plugin text domain
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        // Load public-facing style sheet and JavaScript.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        // HOOKs Widget		
        add_action( 'widgets_init', array( $this, 'ujic_register_widgets' ) );	
        
        if ( is_admin() ) {

           // Add the options page and menu item.
           add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

           // Load admin style sheet and JavaScript.
           add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
           add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

           // Load admin shortcode style sheet and JavaScript.
           add_action( 'admin_enqueue_scripts', array( &$this, 'ujic_shortcode_scripts' ) );
           
           // Add the Link from plugins
           add_filter('plugin_action_links', array($this, 'plugin_settings_link'),10,2);
           
        }
    }

    /**
     * Counter Options.
     *
     * @since     2.0
     */
    public function ujic_option( $id = NULL, $name = NULL ) {
        //Default values
        $sz = ( isset( $_GET['style'] ) && $_GET['style'] == 'modern' ) ? 50 : 32;
        $col = ( isset( $_GET['style'] ) && $_GET['style'] == 'modern' ) ? '#efefef' : '#c368c3';
        $txtc = ( isset( $_GET['style'] ) && $_GET['style'] == 'modern' ) ? '#000000' : '#ffffff';
        $options = array(
            "ujic_name" => "", //Name
            "ujic_style" => "classic", //Style
            "ujic_size" => $sz, //Timer Size
            "ujic_thick" => 10, //Thickness
            "ujic_goof" => 'none', //Google Font
            "ujic_col_dw" => '#a61ba6', //Select Box Color Down
            "ujic_col_up" => $col, //Select Box Color Up
            "ujic_col_txt" => $txtc, //Text Color Number
            "ujic_col_sw" => '#000000', //Text Color Shadow
            "ujic_col_lab" => '#000000', //LAbel Text Color
            "ujic_lab_sz" => 13, //Label size
            "ujic_pos" => 'none', //Alignment
            "ujic_d" => "true", //Main format: Days
            "ujic_h" => "true", //Main format: Hours
            "ujic_m" => "true", //Main format: Minutes
            "ujic_s" => "true", //Main format: Seconds
            "ujic_y" => 0, //Secondary format: Years
            "ujic_o" => 0, //Secondary format: Months
            "ujic_w" => 0, //Secondary format: Weeks
            "ujic_txt" => "true", //Show Label Text
            "ujic_ani" => 0 //Animation for seconds	
        );

        //Return default values	
        if ( (!isset( $name ) || empty( $name ) ) && (!isset( $id ) || empty( $id ) ) ) {
            if ( $this->cform_is_create() ) {
                foreach ( $options as $nm => $val ) {
                    $new_options[$nm] = ( isset( $_POST[$nm] ) && !empty( $_POST[$nm] ) ) ? $_POST[$nm] : '';
                }
                return $new_options;
            } else {
                return $options;
            }
        }

        //Return all saved values	
        if ( !isset( $name ) && isset( $id ) ) {
            $get_option = $this->sel_ujic_db( $id );

            foreach ( $options as $nm => $val ) {
                $val = ( isset( $_GET['edit'] ) && !empty( $_GET['edit'] ) ) ? '' : $val;
                $new_options[$nm] = ( isset( $get_option[$nm] ) && !empty( $get_option[$nm] ) ) ? $get_option[$nm] : $val;
            }
            return $new_options;
        }

        //Return one saved value
        if ( isset( $name ) && isset( $id ) && !empty( $name ) && !empty( $id ) ) {
            $one_option = $this->sel_ujic_db( $id, $name );
            $new_option = (!empty( $one_option ) ) ? $one_option : $options[$name];
        }
    }
    
    /**
     * Short Link
     *
     * @since     2.0
     */
    public function plugin_settings_link( $links, $file ) {
        if ( $file != UJICOUNTDOWN_BASE)
            return $links;

        array_unshift($links, '<a href="options-general.php?page=uji-countdown">' . __( 'Settings', $this->plugin_slug ) . '</a>');

        return $links;
    }

    /**
     * Table Name.
     *
     * @since     2.0
     */
    public static function ujic_tab_name() {
        global $wpdb;
        return $wpdb->prefix . "uji_counter";
    }

    /**
     * Get Database.
     *
     * @since     2.0
     */
    public function sel_ujic_db( $id, $name = NULL ) {
        global $wpdb;
        $options = array();

        if ( is_numeric( $id ) )
            $ujic_data = $wpdb->get_row( "SELECT * FROM " . self::ujic_tab_name() . " WHERE id = $id" );
        else
            $ujic_data = $wpdb->get_row( "SELECT * FROM " . self::ujic_tab_name() . " WHERE title = '" . $id . "'" );

        $var['id'] = $ujic_data->id;
        $var['time'] = $ujic_data->time;
        $var['ujic_name'] = $ujic_data->title;
        $var['ujic_style'] = $ujic_data->style;


        if ( !empty( $ujic_data->options ) ) {
            $options = maybe_unserialize( $ujic_data->options );
            foreach ( $options as $option => $val ) {
                $var[$option] = $val;
            }
        }
        if ( $name )
            return isset( $var[$name] ) ? $var[$name] : null;
        else
            return $var;
    }

    /**
     * Insert Database.
     *
     * @since     2.0
     */
    public function ins_ujic_db( $posts ) {
        global $wpdb;

        $options = array();
        $default = $this->ujic_option();
        foreach ( $posts as $name => $val ) {
            $options[$name] = (!empty( $val ) ) ? $val : $default[$name];
        }
        $title = $options['ujic_name'];
        $style = $options['ujic_style'];
        unset( $options['ujic_name'], $options['ujic_style'], $options['submit_ujic'] );
        //insert to DB
        $wpdb->query( $wpdb->prepare( "INSERT INTO " . self::ujic_tab_name() . "(time, title, style, options )
		    						 VALUES (utc_timestamp(), %s, %s, %s)", trim( $title ), trim( $style ), maybe_serialize( $options )
        ) );
    }

    /**
     * Update Database.
     *
     * @since     2.0
     */
    public function upd_ujic_db( $posts, $id ) {
        global $wpdb;

        $options = array();
        $default = $this->ujic_option();
        foreach ( $posts as $name => $val ) {
            $options[$name] = (!empty( $val ) ) ? $val : $default[$name];
        }
        $title = $options['ujic_name'];
        $style = $options['ujic_style'];
        unset( $options['ujic_name'], $options['ujic_style'], $options['submit_ujic'], $options['cancel_ujic'] );
        //update DB
        $wpdb->query( $wpdb->prepare( "UPDATE " . self::ujic_tab_name() . " SET title=%s, style=%s, options=%s  WHERE id=%d", trim( $title ), trim( $style ), maybe_serialize( $options ), $id
        ) );
    }

    /**
     * Delete Database.
     *
     * @since     2.0
     */
    public function del_ujic_db( $id ) {
        global $wpdb;
        $wpdb->query( "DELETE FROM " . self::ujic_tab_name() . " WHERE id = '" . $id . "'" );
    }

    /**
     * Create Database.
     *
     * @since     2.0
     */
    private static function create_ujic_db() {
        global $wpdb;
        
        $wpdb->hide_errors();
        $collate = '';

         if ( $wpdb->has_cap( 'collation' ) ) {
            if ( ! empty($wpdb->charset ) ) {
               $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }
            if ( ! empty($wpdb->collate ) ) {
               $collate .= " COLLATE $wpdb->collate";
            }
         }
        

        $sql = "CREATE TABLE " . self::ujic_tab_name() . " ( 
		  id int(9) unsigned NOT NULL AUTO_INCREMENT,
		  time datetime not null,
		  title varchar(128) not null,
		  style varchar(8) not null,
		  options longtext,
		  PRIMARY KEY  (id),
        KEY id (id)
	) $collate";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    
    /**
     * Create/Upgrade Database.
     *
     * @since     2.0
     */
    private function upgrade_db() {
        global $wpdb;
        
       if ( ! $wpdb->get_var( "SHOW COLUMNS FROM " . self::ujic_tab_name() . " LIKE 'options'" ) ) {
          
         // Create DB
          self::create_ujic_db();
          
         $old_data = $wpdb->get_results("SELECT * FROM " . self::ujic_tab_name());

         foreach ( $old_data as $data ) {
            
            $posts["ujic_name"] = $data->title; //Timer Title
            $posts["ujic_style"] = $data->style; //Timer Style
            $posts["ujic_size"] = $data->size; //Timer Size
            if( $data->ujic_thick ) $posts["ujic_thick"] = $data->ujic_thick; //Thickness
            $posts["ujic_col_dw"] = $data->col_dw; //Select Box Color Down
            $posts["ujic_col_up"] = $data->col_up; //Select Box Color Up
            if( $data->col_txt ) $posts["ujic_col_txt"] = $data->col_txt; //Text Color Number
            if( $data->col_sw ) $posts["ujic_col_sw"] = $data->col_sw; //Text Color Shadow
            $posts["ujic_pos"] = $data->ujic_pos; //Alignment
            $posts["ujic_goof"] = 'none'; //Google Font
            $posts["ujic_txt"] = ($data->ujic_txt) ? "true" : 0; //Show Label Text
            $posts["ujic_col_lab"] = '#000000'; //LAbel Text Color
            $posts["ujic_lab_sz"] = 13; //Label size
            $posts["ujic_ani"] = ($data->ujic_ani) ? "true" : 0; //Animation for seconds
            $posts["ujic_d"] = "true"; //Main format: Days
            $posts["ujic_h"] = "true"; //Main format: Hours
            $posts["ujic_m"] = "true"; //Main format: Minutes
            $posts["ujic_s"] = "true"; //Main format: Seconds
            $posts["ujic_y"] = 0; //Secondary format: Years
            $posts["ujic_o"] = 0; //Secondary format: Months
            $posts["ujic_w"] = 0; //Secondary format: Weeks
            
            $this->upd_ujic_db( $posts, $data->id );
            
            $wpdb->query( "ALTER TABLE " . self::ujic_tab_name() . " ENGINE = INNODB;");
            $wpdb->query( "ALTER TABLE " . self::ujic_tab_name() . " DROP `size`, DROP `col_dw`, DROP `col_up`, DROP `ujic_pos`, DROP `col_txt`, DROP `col_sw`, DROP `ujic_ani`, DROP `ujic_txt`, DROP `ujic_thick`;");

         }
          
       }else{
            // Create DB
            self::create_ujic_db();
       }

    }

    /**
     * Return an instance of this class.
     *
     * @since     2.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    2.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public function activate( $network_wide ) {
        //Upgrade DB
        $this->upgrade_db();
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    2.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
     */
    public static function deactivate( $network_wide ) {
        // TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    2.0
     */
    public function load_plugin_textdomain() {
 
        $domain = $this->plugin_slug;
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' ); 
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     2.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {

        if ( !isset( $this->plugin_screen_hook_suffix ) ) {
            return;
        }

        $screen = get_current_screen();
        if ( $screen->id == $this->plugin_screen_hook_suffix ) {
           if(isset($_GET['tab']) && (isset($_GET['tab']) && $_GET['tab'] != "tab_ujic_list") ){
               wp_enqueue_style( 'wp-color-picker' );
               wp_enqueue_style( $this->plugin_slug . '-admin-jqueryui', UJICOUNTDOWN_URL . 'assets/css/jquery-ui-custom.css', array(), $this->version );
               wp_enqueue_style( $this->plugin_slug . '-admin-icheck', UJICOUNTDOWN_URL . 'assets/css/pink.css', array(), $this->version );
           }
            wp_enqueue_style( $this->plugin_slug . '-admin-styles', UJICOUNTDOWN_URL . 'assets/css/admin.css', array(), $this->version );
        }
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     2.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {

        if ( !isset( $this->plugin_screen_hook_suffix ) ) {
            return;
        }
        wp_enqueue_style( 'dashicons' );       
        $screen = get_current_screen();
        if ( $screen->id == $this->plugin_screen_hook_suffix && (isset($_GET['tab']) && $_GET['tab'] != "tab_ujic_list")) {
            wp_enqueue_script( 'dashboard' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-slider' );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( $this->plugin_slug . '-admin-icheck', UJICOUNTDOWN_URL . 'assets/js/jquery.icheck.min.js' );
            wp_enqueue_script( $this->plugin_slug . '-admin-script', UJICOUNTDOWN_URL . 'assets/js/admin.js', array( 'wp-color-picker' ), $this->version );
        }
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    2.0
     */
    public function enqueue_scripts() {
        wp_register_style( $this->plugin_slug . '-uji-countdown', UJICOUNTDOWN_URL . 'css/uji-countdown.css', array(), $this->version );
        wp_register_script( $this->plugin_slug . '-core', UJICOUNTDOWN_URL . 'js/jquery.countdown.js', array( 'jquery' ), $this->version );
        wp_register_script( $this->plugin_slug . '-init', UJICOUNTDOWN_URL . 'js/uji-countdown.js', array( 'jquery' ), $this->version );
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    2.0
     */
    public function add_plugin_admin_menu() {
        $this->plugin_screen_hook_suffix = add_submenu_page(
                'options-general.php', $this->pname . " " . $this->version, $this->pname, 'manage_options', $this->plugin_slug, array( $this, 'display_plugin_admin_page' )
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    2.0
     */
    public function display_plugin_admin_page() {
        include_once( UJICOUNTDOWN . 'views/admin.php' );
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Shortcode Admin Init
     *
     * @since    2.0
     */
    public function ujic_shortcode_scripts() {
        $screen = get_current_screen();
        if ( $screen->base == 'post' ):
            // css
            wp_enqueue_style( 'ujic-jui', UJICOUNTDOWN_URL . 'assets/tinymce/css/jquery-ui.min.css' );
            wp_enqueue_style( 'ujic-popup', UJICOUNTDOWN_URL . 'assets/tinymce/css/popup.css', false, '1.0', 'all' );

            // js
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( 'jquery-livequery', UJICOUNTDOWN_URL . 'assets/tinymce/js/jquery.livequery.js', false, '1.1.1', false );
            wp_enqueue_script( 'jquery-appendo', UJICOUNTDOWN_URL . 'assets/tinymce/js/jquery.appendo.js', false, '1.0', false );
            wp_enqueue_script( 'base64', UJICOUNTDOWN_URL . 'assets/tinymce/js/base64.js', false, '1.0', false );
            if ( floatval( get_bloginfo( 'version' ) ) >= 3.9 ) {
                wp_enqueue_script( 'ujic-popup', UJICOUNTDOWN_URL . 'assets/tinymce/js/popup.js', false, '1.0', false );
            } else {
                wp_enqueue_script( 'ujic-popup', UJICOUNTDOWN_URL . 'assets/tinymce/js/popup.old.js', false, '1.0', false );
                //For older versions of WP
            }

            wp_localize_script( 'jquery', 'UjicShortcodes', array( 'plugin_folder' => UJICOUNTDOWN_URL ) );

            if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) )
                return;

            if ( get_user_option( 'rich_editing' ) == 'true' ) {
                add_filter( 'mce_external_plugins', array( &$this, 'add_rich_plugins' ) );
                add_filter( 'mce_buttons', array( &$this, 'register_rich_buttons' ) );
            }
        endif;
    }

    /**
     * Defins TinyMCE rich editor js plugin
     *
     * @return	void
     */
    public function add_rich_plugins( $plugin_array ) {
        if ( floatval( get_bloginfo( 'version' ) ) >= 3.9 ) {
            $plugin_array['ujicShortcodes'] = UJICOUNTDOWN_URL . 'assets/tinymce/plugin.js';
        } else {
            $plugin_array['ujicShortcodes'] = UJICOUNTDOWN_URL . 'assets/tinymce/plugin.old.js'; // For old versions of WP
        }
        return $plugin_array;
    }

    /**
     * Adds TinyMCE rich editor buttons
     *
     * @return	void
     */
    public function register_rich_buttons( $buttons ) {
        array_push( $buttons, "|", 'ujic_button' );
        return $buttons;
    }
    
   /**
    * Register widget
    *
    * @return	void
    */
    public function ujic_register_widgets() {
        // Include - no need to use autoload as WP loads them anyway
        include_once( 'class-uji-widget.php' );

        // Register widgets
        register_widget( 'ujic_Widget' );
    }
    
   /**
    * Get options/option
    *
    * @since    2.0
    */
   public function ujic_get_option( $name, $opt = 'ujic_set' ) {
      $vars = get_option($opt);
      if($name && isset($vars[$name]) && !empty($vars[$name]) )
         return $vars[$name];
      else
         return false;      
   }   

}