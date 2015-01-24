<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   goodByeCaptcha
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */
?>
<div class="wrap">

	<?php screen_icon(); ?>
   <h2<?php if ( floatval( get_bloginfo( 'version' ) ) >= 3.9 ) { echo ' class="ujc-admin-tit"'; }  ?>><?php echo esc_html( get_admin_page_title() ); ?></h2>
    
    <?php
		$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'tab_ujic_list';
		if(isset($_GET['tab']))
			$active_tab = $_GET['tab'];
		?>
        
		<h2 class="nav-tab-wrapper">
         <a href="?page=uji-countdown&amp;tab=tab_ujic_list" class="nav-tab <?php echo $active_tab == 'tab_ujic_list' ? 'nav-tab-active' : ''; ?>"><i class="dashicons dashicons-menu ujic-mico"></i><?php _e('My Countdowns', 'uji-countdown'); ?></a>
         <a href="?page=uji-countdown&amp;tab=tab_ujic_new" class="nav-tab <?php echo $active_tab == 'tab_ujic_new' ? 'nav-tab-active' : ''; ?>"><i class="dashicons dashicons-clock ujic-mico"></i><?php _e('Add/Edit Countdown', 'uji-countdown'); ?></a>
         <a href="?page=uji-countdown&amp;tab=tab_ujic_set" class="nav-tab <?php echo $active_tab == 'tab_ujic_set' ? 'nav-tab-active' : ''; ?>"><i class="dashicons dashicons-admin-tools ujic-mico"></i><?php _e('Settings', 'uji-countdown'); ?></a>
        <a href="http://wpmanage.com/uji-countdown" target="_blank" class="nav-tab nav-tab-pro"><i class="dashicons dashicons-plus ujic-mico"></i><?php _e('Upgrade to PRO', 'uji-countdown'); ?></a>
		</h2>
        
    <?php $ujicount = new Uji_Countdown(); ?>    

	<?php 
		if($active_tab == 'tab_ujic_list') { 	
			$ujicount -> admin_tablelist();
      }
      
		if($active_tab == 'tab_ujic_new') { 	
			$ujicount -> admin_countdown();
		}
      
      if($active_tab == 'tab_ujic_set') { 	
			$ujicount -> admin_timerset();
		}
	?>

</div>