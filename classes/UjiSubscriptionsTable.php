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

require_once(ABSPATH . 'wp-admin/includes/template.php' );

class_exists('WP_Screen') || require_once( ABSPATH . 'wp-admin/includes/screen.php' );

class_exists( 'WP_List_Table', false) || require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class UjiSubscriptionsTable extends WP_List_Table
{
	private $itemsPerPage = 10;

	public function __construct()
	{

		parent::__construct( array(
			'singular' => __( 'subscriber', 'uji-countdown' ),
			'plural'   => __( 'subscribers', 'uji-countdown' ),
			'ajax'     => false,
			'screen'   => 'subscribers-list'
		) );

		$this->process_bulk_action();
		$this->prepare_items();

	}

	function process_bulk_action()
	{
		global $wpdb;
		if( 'delete' === $this->current_action())
		{
			if(!empty($_GET['subscriber']))
			foreach ($_GET['subscriber'] as $subscriberId) {
				if (empty($subscriberId)) continue;
				$wpdb->delete(self::getSubscriptionsTableName(), array('Id' => $subscriberId), array('%d'));
			}
			return;
		}

		if('cvs-export' === $this->current_action())
		{
			global $wpdb;
			$arrSubscribers = $wpdb->get_results('SELECT * FROM ' . self::getSubscriptionsTableName(), ARRAY_A);
			if(empty($arrSubscribers))
				return;

			header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
			header('Content-Description: File Transfer');
			header("Content-Transfer-Encoding: UTF-8");
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=subscribers.csv");
			header("Content-Transfer-Encoding: binary");
			header("Expires: 0");
			header("Pragma: no-cache");

			$fileHandler = @fopen('php://output', 'w');
			if(false === $fileHandler)
				return;

			$arrHeaderColumns = array('EmailAddress', 'List', 'CreatedDate');
			fputcsv($fileHandler, array('EmailAddress', 'Campaign', 'CreatedDate'));

			foreach ($arrSubscribers as $subscriberInfo)
			{
				foreach($subscriberInfo as $info => $value)
				{
					if(in_array($info, $arrHeaderColumns))
						continue;
					unset($subscriberInfo[$info]);
				}

				fputcsv($fileHandler, $subscriberInfo);
			}

			fclose($fileHandler);
			exit;
		}
	}


	private static function getSubscriptionsTableName()
	{
		global $wpdb;
		return $wpdb->prefix . 'uji_subscriptions';
	}

	public function prepare_items()
	{
		global $wpdb;

		$totalItems = $wpdb->get_var("Select count(*) from " . self::getSubscriptionsTableName());

		$pageNumber = (int)(!empty($_GET["paged"]) && is_numeric(sanitize_text_field($_GET["paged"])) ? sanitize_text_field($_GET["paged"]) : 1);

		$orderBy   = !empty($_GET['orderby']) && array_key_exists(trim($_GET['orderby']), $this->get_sortable_columns()) ? sanitize_text_field(trim($_GET['orderby'])) : 'Id';
		$orderType = !empty($orderBy) && !empty($_GET['order']) && in_array(strtolower($_GET['order']), array('asc', 'desc')) ? sanitize_text_field($_GET['order']) : 'DESC';

		$sqlStatement  = 'SELECT * FROM ' . self::getSubscriptionsTableName();
		$sqlStatement .= isset($orderBy) && isset($orderType) ? " ORDER BY $orderBy $orderType" : null;
		$sqlStatement .= " LIMIT " . (($pageNumber - 1) * $this->itemsPerPage) . ", $this->itemsPerPage";

		$this->set_pagination_args( array(
			"total_items" => $totalItems,
			"total_pages" => ceil($totalItems / $this->itemsPerPage),
			"per_page" => $this->itemsPerPage,
		) );


		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->items = $wpdb->get_results($sqlStatement);

	}

	public function get_sortable_columns()
	{
		return array(
			'CreatedDate'     => array('CreatedDate', false)
		);
	}

	public function get_columns()
	{
		return array(
			'cb'        => '<input type="checkbox" />',
			'Email' => __( 'Email Address', 'uji-countdown'),
			'Campaign' => __( "Campaign", 'uji-countdown'),
			'CreatedDate' => __( "Subscribed", 'uji-countdown')
		);

	}

	public function column_default($item, $column_name)
	{
		switch($column_name)
		{
			case 'Email'        : return is_array($item) ? $item['EmailAddress'] : $item->EmailAddress;
			case 'Campaign'     : return is_array($item) ? $item['List'] : $item->List;
			case 'CreatedDate'  : return date_format(new DateTime(is_array($item) ? $item['CreatedDate'] : $item->CreatedDate), 'F jS, Y g:i a') ;
		}

		return '';
	}

	public function column_cb($item)
	{
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item->Id
		);
	}


	public function get_bulk_actions()
	{
		return array(
			'delete'     => __('Delete'    , 'uji-countdown'),
			'cvs-export' => __('CSV Export', 'uji-countdown')
		);

	}
}