<?php
/**
 * Class LP_Order_DB
 *
 * @author minhpd
 * @since 4.1.3
 */

defined( 'ABSPATH' ) || exit();

class LP_Order_DB extends LP_Database {
	private static $_instance;

	protected function __construct() {
		parent::__construct();
	}

	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * get total price order complete not included child-order
	 *
	 */
	public function learn_press_get_total_price_order_complete(){

		$query = $this->wpdb->prepare("SELECT SUM(meta_value) as order_total From `{$this->tb_postmeta}` as mt
		INNER JOIN `{$this->tb_posts}` as p ON p.id = mt.post_id
		WHERE p.post_type = %s
		AND p.post_parent = 0
		AND mt.meta_key = %s
		", LP_ORDER_CPT , '_order_total');

		$total = $this->wpdb->get_results($query)[0]->order_total;

		return learn_press_format_price( $total, true );

	}

}

LP_Order_DB::getInstance();

