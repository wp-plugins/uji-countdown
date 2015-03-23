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

class Uji_Countdown extends Uji_Countdown_Admin
{

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

       global $wpdb;

        if(version_compare(self::getPluginSavedVersion(), '2.0.2') < 0)
        {
            $createTableStatement = "CREATE TABLE " . self::getSubscriptionsTableName() . "(
						  Id int unsigned NOT NULL auto_increment,
						  EmailAddress varchar(64) NOT NULL,
						  ProviderId tinyint unsigned DEFAULT '0',
						  List varchar(128) DEFAULT NULL,
						  ListGroup varchar(32) DEFAULT NULL,
						  CreatedDate datetime NOT NULL,
						  IsSynchronized tinyint unsigned NOT NULL DEFAULT '0',
						  PRIMARY KEY  (Id)
						)";

            $createTableStatement .= !empty($wpdb->charset) ? " DEFAULT CHARACTER SET $wpdb->charset" : '';
            $createTableStatement .= !empty($wpdb->collate) ? " COLLATE $wpdb->collate"               : '';

            if($wpdb->get_var($wpdb->prepare("show tables like %s", self::getSubscriptionsTableName())) !== self::getSubscriptionsTableName())
            {
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($createTableStatement);
            }

        }

        self::savePluginVersion();

        self::$isGoodByeCaptchaActivated = class_exists('GoodByeCaptcha', false);

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
        
        if ( is_admin() )
        {

            if(!empty($_GET['page']) && $_GET['page'] === 'uji-countdown' && !empty($_GET['action']) && $_GET['action'] === 'cvs-export')
            {
                new UjiSubscriptionsTable();
                exit;
            }

           // Add the options page and menu item.
           add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

           // Load admin style sheet and JavaScript.
           add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
           add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

           // Load admin shortcode style sheet and JavaScript.
           add_action( 'admin_enqueue_scripts', array( &$this, 'ujic_shortcode_scripts' ) );
           
           // Add the Link from plugins
           add_filter('plugin_action_links', array($this, 'plugin_settings_link'),10,2);

           //Subscription
           add_action('wp_ajax_nopriv_ujiSubscribe', array( $this, 'handleSubscription' ) );
           add_action('wp_ajax_ujiSubscribe',        array( $this, 'handleSubscription' ) );

           !self::$isGoodByeCaptchaActivated ? add_action('admin_notices', array($this, 'showAntiSpamAdminNotice')) : null;
        }

    }
    
    /**
     * Subscription
     *
     * @since     2.0.3
     */
    protected static function getSubscriptionsTableName()
    {
        global $wpdb;
        return $wpdb->prefix . 'uji_subscriptions';
    }

    protected static function getPluginSavedVersion()
    {
        return get_option('uji-countdown-version', null);
    }

    protected static function savePluginVersion()
    {
        return update_option('uji-countdown-version', UJIC_VERS);
    }

    public function handleSubscription()
    {
        global $wpdb;
        $_POST = array_map('trim', $_POST);

        $_POST['txtEmail'] = strtolower(sanitize_email($_POST['txtEmail']));
        $showErrorMessage   = empty($_POST['txtEmail']) || (false === filter_var($_POST['txtEmail'], FILTER_VALIDATE_EMAIL));
        (!$showErrorMessage) ? $showErrorMessage = !check_ajax_referer('uji-subscribe-newsletter', 'uji-subscribe-nonce', false) : null;
        (!$showErrorMessage && self::$isGoodByeCaptchaActivated) ? $showErrorMessage = !GdbcRequest::isValid(array("module" => GdbcModulesController::MODULE_UJI_COUNTDOWN)) : null;

        $showErrorMessage ? wp_send_json_error() : null;

        $arrFields = array(
                        'EmailAddress' => $_POST['txtEmail'],
                        'CreatedDate' => current_time('mysql'),
                    );
        if(!empty($_POST['txtList']))
        {
            $_POST['txtList'] = sanitize_text_field($_POST['txtList']);
            !empty($_POST['txtList']) ? $arrFields['List'] = $_POST['txtList'] : null;
        }

        false !== $wpdb->insert(
                                    self::getSubscriptionsTableName(),
                                    $arrFields,
                                    array_fill(0, count($arrFields), '%s')
                                ) ? wp_send_json_success() : wp_send_json_error();

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
            "ujic_ani" => 0, //Animation for seconds

	        "ujic_subscrFrmIsEnabled" => "true", // Enable subscribe form
	        "ujic_subscrFrmAboveText" => "",//__("Join Our Newsletter"), // Subscription form text above
	        "ujic_subscrFrmInputText" => "",//__("Enter your email here"), // Subscription form input text placeholder
	        "ujic_subscrFrmSubmitText" => "",//__("Subscribe"), // Subscription form input text placeholder
            "ujic_subscrFrmWidth" => 100, // Subscription form width(%) relative to holder
            "ujic_subscrFrmSubmitColor" => '#ab02b2', //Submit Button Color
            "ujic_subscrFrmThanksMessage" => "",//__("Thanks for subscribing"), //Thank you message
            "ujic_subscrFrmErrorMessage"  => ""//__("Invalid email address"), //Error message

        );

        //Return default values	
        if ( (!isset( $name ) || empty( $name ) ) && (!isset( $id ) || empty( $id ) ) )
        {
            if ( $this->cform_is_create() )
            {
                foreach ( $options as $nm => $val )
                {
                    $new_options[$nm] = ( isset( $_POST[$nm] ) && !empty( $_POST[$nm] ) ) ? $_POST[$nm] : '';
                }

                return $new_options;
            }
            else
            {
                return $options;
            }
        }

        //Return all saved values	
        if ( !isset( $name ) && isset( $id ) )
        {
            $get_option = $this->sel_ujic_db( $id );

            foreach ( $options as $nm => $val )
            {
                $val = ( isset( $_GET['edit'] ) && !empty( $_GET['edit'] ) ) ? '' : $val;
                //$new_options[$nm] = ( isset( $get_option[$nm] ) && !empty( $get_option[$nm] ) ) ? $get_option[$nm] : $val;

                $new_options[$nm] = !empty( $get_option[$nm]) ? $get_option[$nm] : $val;
            }
            return $new_options;
        }

        //Return one saved value
        if ( isset( $name ) && isset( $id ) && !empty( $name ) && !empty( $id ) )
        {
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
		    						 VALUES (utc_timestamp(), %s, %s, %s)",$title, trim( $style ), maybe_serialize( $options )
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
        $wpdb->query( $wpdb->prepare( "UPDATE " . self::ujic_tab_name() . " SET title=%s, style=%s, options=%s  WHERE id=%d", $title, trim( $style ), maybe_serialize( $options ), $id
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
        
       if( $wpdb->get_var("SHOW TABLES LIKE '" . self::ujic_tab_name()."'") === $wpdb->prefix . self::ujic_tab_name() ) {
          
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
            
            $wpdb->query( "ALTER TABLE " . self::ujic_tab_name());
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
           if( isset($_GET['tab']) && $_GET['tab'] != "tab_ujic_list"){
               wp_enqueue_style( 'wp-color-picker' );
               wp_enqueue_style( $this->plugin_slug . '-admin-jqueryui', UJICOUNTDOWN_URL . 'assets/css/jquery-ui-custom.css', array(), $this->version );
               wp_enqueue_style( $this->plugin_slug . '-admin-icheck', UJICOUNTDOWN_URL . 'assets/css/pink.css', array(), $this->version );
           }
            wp_enqueue_style( $this->plugin_slug . '-admin-styles', UJICOUNTDOWN_URL . 'assets/css/admin.css', array(), $this->version );
        }
    }
    
    /**
     * Anti Spam Notice
     *
     * @since     2.0.3
     *
     */
    public function showAntiSpamAdminNotice()
    {
        $screen = get_current_screen();
        if($screen->id != $this->plugin_screen_hook_suffix)
            return;

        if(empty($_GET['tab']) ||  $_GET['tab'] !== 'tab_ujic_news')
            return;
        
        if(!isset($_GET['edit']))
             echo '<div class="error"><p>' . _('In order to avoid fake subscriptions we suggest to install <a target="_blank" href = "https://wordpress.org/plugins/goodbye-captcha/">GoodBye Captcha Plugin</a>') . '</p></div>';
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

            if($_GET['tab'] != "tab_ujic_news")
            {
                wp_enqueue_script($this->plugin_slug . '-admin-icheck', UJICOUNTDOWN_URL . 'assets/js/jquery.icheck.min.js');
                wp_enqueue_script($this->plugin_slug . '-admin-script', UJICOUNTDOWN_URL . 'assets/js/admin.js', array('wp-color-picker'), $this->version);
            }
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
        wp_register_script( $this->plugin_slug . '-uji-countdown-newsletter', UJICOUNTDOWN_URL . 'js/uji-newsletter.js', array( 'jquery' ), $this->version );

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

    /**
     * Shortcode Admin Init
     *
     * @since    2.0.4
     */
    public function ujic_shortcode_scripts() {
        $screen = get_current_screen();
        if ( $screen->base == 'post' ):
            // css
            wp_enqueue_style( 'ujic-popup-ui', UJICOUNTDOWN_URL . 'assets/css/jquery-ui.min.css', false, '1.0', 'all' );
            wp_enqueue_style( 'ujic-popup', UJICOUNTDOWN_URL . 'assets/css/ujic-style.css', false, '1.0', 'all' );

            // js
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'jquery-ui-datepicker' );

            wp_localize_script( 'jquery', 'UjicShortcodes', array( 'plugin_folder' => UJICOUNTDOWN_URL ) );

            if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) )
                return;

            if ( get_user_option( 'rich_editing' ) == 'true' ) {
                 wp_localize_script('editor', 'ujic_short_vars', array(
                    'ujic_style' => $this->ujic_styles_get(),
                    'ujic_hou' => $this->ujic_datetime_get(23),
                    'ujic_min' => $this->ujic_datetime_get(59),
                    'ujic_reclab' => $this->ujic_reclab_get()
                ));
                
                add_filter( 'mce_external_plugins', array( &$this, 'ujic_add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( &$this, 'ujic_register_my_tc_button' ) );
            }
        endif;
    }

   /**
    * get All Styles
    *
    * @since    2.0.4
    */
    public function ujic_styles_get() {
        global $wpdb;
        $ujic_styles = $wpdb->get_results("SELECT style, title FROM " . $wpdb->prefix . "uji_counter ORDER BY `time` DESC");
        $ujic_sel = array();
        if ( !empty($ujic_styles) ) {
            $i = 0;
            foreach ( $ujic_styles as $ujic ) {
                $ujic_sel[$i]['text'] =  $ujic->title . ' - ' . $ujic->style;
                $ujic_sel[$i]['value'] = $ujic->title;
                $i++;
            }

         return $ujic_sel;
        }
    }

   /**
    * TinyMCE get Data/Time
    *
    * @since    2.0.4
    */
    public function ujic_datetime_get($nr) {
        $ujic_sel = array();
        for ( $i = 0; $i <= $nr; $i++ ) {
             $ujic_sel[$i]['text'] = $num[sprintf("%02s", $i)] = sprintf("%02s", $i);
             $ujic_sel[$i]['value'] = $num[sprintf("%02s", $i)] = sprintf("%02s", $i);
        }

        return $ujic_sel;
    }

   /**
    * TinyMCE get Unit Time labels
    *
    * @since    2.0.4
    */
    public function ujic_reclab_get() {
        $tlab = array('second'=> 'Second(s)', 'minute'=> 'Minute(s)', 'hour'=> 'Hour(s)', 'day'=> 'Day(s)', 'week'=> 'Week(s)', 'month'=> 'Month(s)');
        $i=0;
        foreach ( $tlab as $v => $n ) {
            $ujic_sel[$i]['text'] = $n;
            $ujic_sel[$i]['value'] = $v;
            $i++;
        }

        return $ujic_sel;
    }

   /**
    * TinyMCE Plugin JS
    *
    * @since    2.0.4
    */
    public function ujic_add_tinymce_plugin( $plugin_array ) {
        $plugin_array['ujic_tc_button'] = UJICOUNTDOWN_URL . 'assets/js/ujic-popup-button.js';
        return $plugin_array;
    }
    
   /**
    * Add TyniMCE Button
    *
    * @since    2.0.4
    */
    public function ujic_register_my_tc_button( $buttons ) {
        array_push( $buttons, "ujic_tc_button" );
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