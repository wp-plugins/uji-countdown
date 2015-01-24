<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 */
?>
<div class="wrap">

    <?php screen_icon(); ?>
    <h2<?php if ( floatval( get_bloginfo( 'version' ) ) >= 3.9 ) {
        echo ' class="ujc-admin-tit"';
    } ?>><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <?php
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'tab_ujic_list';
    !empty($_GET['_wp_http_referer']) && false !== strpos($_GET['_wp_http_referer'], 'tab_ujic_news') ? $active_tab = 'tab_ujic_news' : null;

    $add_tab = __( 'Add new style', 'uji-countdown' );

    if ( isset( $_GET['tab'] ) )
    {
        $active_tab = $_GET['tab'];
        $add_tab = ( 'tab_ujic_new' == $_GET['tab'] && isset( $_GET['edit'] ) ) ? __( 'Edit style', 'uji-countdown' ) : $add_tab;
    }
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=uji-countdown&amp;tab=tab_ujic_list" class="nav-tab <?php echo $active_tab == 'tab_ujic_list' ? 'nav-tab-active' : ''; ?>"><i class="dashicons dashicons-menu ujic-mico"></i><?php _e( 'My Styles', 'uji-countdown' ); ?></a>
        <a href="?page=uji-countdown&amp;tab=tab_ujic_new" class="nav-tab <?php echo $active_tab == 'tab_ujic_new' ? 'nav-tab-active' : ''; ?>"><i class="dashicons dashicons-clock ujic-mico"></i><?php echo $add_tab; ?></a>
        <a href="?<?php echo str_replace(is_ssl() ? 'https://' : 'http://', '', esc_url('page=uji-countdown&amp;tab=tab_ujic_news'));?>" class="nav-tab <?php echo $active_tab == 'tab_ujic_news' ? 'nav-tab-active' : ''; ?>"><i class="dashicons dashicons-email ujic-mico"></i> <?php _e( 'Subscribers', 'uji-countdown' ); ?></a>
        <a href="?page=uji-countdown&amp;tab=tab_ujic_set" class="nav-tab <?php echo $active_tab == 'tab_ujic_set' ? 'nav-tab-active' : ''; ?>"><i class="dashicons dashicons-admin-tools ujic-mico"></i><?php _e( 'Settings', 'uji-countdown' ); ?></a>
        <a href="http://www.wpmanage.com/uji-countdown" target="_blank" class="nav-tab nav-tab-pro"><i class="dashicons dashicons-plus ujic-mico"></i><?php _e( 'Upgrade', 'uji-countdown' ); ?></a>
    </h2>

    <?php


    $ujicount = new Uji_Countdown();

   // print_r($_GET);exit;

    if ( $active_tab == 'tab_ujic_list' ) {
        $ujicount->admin_tablelist();
    }

    if ( $active_tab == 'tab_ujic_new' ) {
        $ujicount->admin_countdown();
    }

    if ( $active_tab == 'tab_ujic_news' ) {
        $ujicount->admin_subscribers();
    }

    if ( $active_tab == 'tab_ujic_set' ) {
        $ujicount->admin_timerset();
    }
    ?>

</div>