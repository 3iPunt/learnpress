<?php

/**
 * Class LP_Admin_Ajax
 *
 * @author  ThimPress
 * @package LearnPress/Classes
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'LP_Admin_Ajax' ) ) {

	/**
	 * Class LP_Admin_Ajax
	 */
	class LP_Admin_Ajax {

		/**
		 * Add action ajax
		 */
		public static function init() {

			if ( ! is_user_logged_in() ) {
				return;
			}

			$ajax_events = array(
				'create_page'            => false, // Use create new page on Settings
				// 'plugin_action'           => false,
				// 'modal_search_items'      => false,
				//'dismiss_notice'         => false,
				//'search_users'           => false,
				'load_chart'             => false,
				'search_course_category' => false,
				'custom_stats'           => false,
				//'ignore_setting_up'      => false,
				'get_page_permalink'     => false,
				//'dummy_image'            => false,
				// 'update_add_on_status'    => false,
				// 'plugin_install'          => false,
				//'bundle_activate_add_ons' => false,
				//'install_sample_data'     => false,

				// Remove Notice
				//'remove_notice_popup'    => false,
				// Update order status
				// 'update_order_status'     => false,
				'update_order_exports'   => false,
			);

			foreach ( $ajax_events as $ajax_event => $nopriv ) {
				add_action( 'wp_ajax_learnpress_' . $ajax_event, array( __CLASS__, $ajax_event ) );

				// enable for non-logged in users
				if ( $nopriv ) {
					add_action( 'wp_ajax_nopriv_learnpress_' . $ajax_event, array( __CLASS__, $ajax_event ) );
				}
			}

			do_action( 'learn-press/ajax/admin-load', __CLASS__ );

			$ajax_events = array(
				'search_items' => 'modal_search_items',
				'update-payment-order', // Update ordering of payments when user changing.
				'update-payment-status', // Enable type payment
				//'toggle_item_preview',

				// admin editor
				'admin_course_editor',
				'admin_quiz_editor',
				'admin_question_editor',
				// duplicator
				'duplicator', // Duplicate course, lesson, quiz, question.

				//'add_item_to_order',
				//'remove_order_item',

				'modal_search_items', // Used to search courses on LP Order
				'modal_search_users', // Used to search users on LP Order
				'add_items_to_order', // Used to add courses on LP Order
				'remove_items_from_order', // Used to remove items from LP Order
				'update_email_status', // Use for enable email on LP Settings
				//'create-pages',
				'search-authors', // Used to search username on input some page (list courses, lp orders, quizzes, questions... on the Backend
				'skip-notice-install',
				//'join_newsletter',
				//'dashboard-order-status',
				//'dashboard-plugin-status',
				//'dismiss-notice',
				//'sync-user-orders',
				//'sync-course-final-quiz',
				//'sync-remove-older-data',
				//'sync-calculate-course-results',
				//'create-question-type',
				// 'sync-user-courses',
			);

			foreach ( $ajax_events as $action => $callback ) {
				if ( is_numeric( $action ) ) {
					$action = $callback;
				}

				$actions = LP_Request::parse_action( $action );
				$method  = $actions['action'];

				if ( ! is_callable( $callback ) ) {
					$method   = preg_replace( '/-/', '_', $method );
					$callback = array( __CLASS__, $method );
				}

				LP_Request::register_ajax( $action, $callback );
			}
		}

		/**
		 * @editor tungnx
		 * @model 4.1.4 comment - not use
		 */
		/*public static function sync_calculate_course_results() {
			if ( ! isset( $_REQUEST['sync'] ) ) {
				return;
			}

			$sync = LP_Helper::sanitize_params_submitted( $_REQUEST['sync'] );

			if ( empty( $sync ) ) {
				die();
			}

			global $wpdb;
			$api = LP_Repair_Database::instance();

			if ( $sync === 'get-users' ) {
				$query = $wpdb->prepare(
					"
					SELECT ID
					FROM {$wpdb->users}
					WHERE 1
				",
					1
				);

				$users = $wpdb->get_col( $query );

				learn_press_send_json( array( 'users' => $users ) );
			}

			$api->calculate_course_results( $sync );
			learn_press_send_json( array( 'result' => 'success' ) );

			die();
		}*/

		/**
		 * Sync orders for each course
		 *
		 * @since 3.1.0
		 * @editor tungnx
		 * @modify 4.1.4 comment - not use
		 */
		/*public static function sync_course_orders() {
			if ( empty( $_REQUEST['sync'] ) ) {
				die();
			}

			global $wpdb;
			$api  = LP_Repair_Database::instance();
			$sync = $_REQUEST['sync'];

			if ( $sync === 'get-courses' ) {
				learn_press_send_json( array( 'courses' => $api->get_all_courses() ) );
			}

			$api->sync_course_orders( $sync );
			learn_press_send_json( array( 'result' => 'success' ) );

			die();
		}*/

		/**
		 * Sync orders for each user
		 *
		 * @since 3.1.0
		 * @editor tungnx
		 * @modify 4.1.4 comment - not use
		 */
		/*public static function sync_user_orders() {
			if ( empty( $_REQUEST['sync'] ) ) {
				die();
			}

			global $wpdb;
			$api  = LP_Repair_Database::instance();
			$sync = $_REQUEST['sync'];

			if ( $sync === 'get-users' ) {
				$query = $wpdb->prepare(
					"
					SELECT ID
					FROM {$wpdb->users}
					WHERE 1
				",
					1
				);

				$users = $wpdb->get_col( $query );

				learn_press_send_json( array( 'users' => $users ) );
			}

			$api->sync_user_orders( $sync );
			learn_press_send_json( array( 'result' => 'success' ) );

			die();
		}*/

		/**
		 * Remap final quiz for each course
		 *
		 * @since 3.1.0
		 */
		public static function sync_course_final_quiz() {
			if ( empty( $_REQUEST['sync'] ) ) {
				die();
			}

			global $wpdb;
			$api  = LP_Repair_Database::instance();
			$sync = $_REQUEST['sync'];

			if ( $sync === 'get-courses' ) {
				learn_press_send_json( array( 'courses' => $api->get_all_courses() ) );
			}

			$api->sync_course_final_quiz( $sync );
			learn_press_send_json( array( 'result' => 'success' ) );

			die();
		}

		/**
		 * @editor tungnx
		 * @comment 4.1.4 comment - not use
		 */
		/*public static function sync_remove_older_data() {
			$api = LP_Repair_Database::instance();
			$api->remove_older_post_meta();
			learn_press_send_json( array( 'result' => 'success' ) );
			die();
		}*/

		/**
		 * Get html of order status to display in WP Dashboad
		 * @editor tungnx
		 * @modify 4.1.4 comment - not use
		 */
		/*public static function dashboard_order_status() {
			learn_press_admin_view( 'dashboard/order-status' );
			die();
		}*/

		/**
		 * @editor tungnx
		 * @modify 4.1.4 comment - not use
		 */
		/*public static function dashboard_plugin_status() {
			$dashboard   = new LP_Admin_Dashboard();
			$plugin_data = $dashboard->get_data();
			if ( ! $plugin_data || is_wp_error( $plugin_data ) ) {
				learn_press_admin_view( 'dashboard/plugin-status/html-no-data' );
			} else {
				learn_press_admin_view( 'dashboard/plugin-status/html-results', array( 'plugin_data' => $plugin_data ) );
			}
			die();
		}*/

		/**
		 * Search user on some pages on the Backend
		 */
		public static function search_authors() {
			$args  = array(
				'orderby'        => 'name',
				'order'          => 'ASC',
				'search'         => sprintf( '*%s*', esc_attr( LP_Request::get_string( 'term' ) ) ),
				'search_columns' => array( 'user_login', 'user_email' ),
			);
			$q     = new WP_User_Query( $args );
			$users = array();

			$results = $q->get_results();

			if ( $results ) {
				foreach ( $results as $result ) {
					$users[] = array(
						'id'   => $result->ID,
						'text' => learn_press_get_profile_display_name( $result->ID ),
					);
				}
			}
			echo json_encode(
				array(
					'results' => $users,
				)
			);
			die();
		}

		/**
		 * Hide notice install
		 */
		public static function skip_notice_install() {
			delete_option( 'learn_press_install' );
		}

		/**
		 * Handle ajax admin course editor.
		 *
		 * @since 3.0.0
		 */
		public static function admin_course_editor() {
			$editor = LP_Admin_Editor::get_editor_course();
			self::admin_editor( $editor );
		}

		/**
		 * Handle ajax admin question editor.
		 *
		 * @since 3.0.0
		 */
		public static function admin_question_editor() {
			$editor = LP_Admin_Editor::get_editor_question();
			self::admin_editor( $editor );
		}

		/**
		 * Handle ajax admin quiz editor.
		 *
		 * @since 3.0.0
		 */
		public static function admin_quiz_editor() {
			$editor = LP_Admin_Editor::get_editor_quiz();
			self::admin_editor( $editor );
		}

		/**
		 * @param LP_Admin_Editor $editor
		 *
		 * @since 3.0.2
		 */
		public static function admin_editor( &$editor ) {
			$result = $editor->dispatch();

			if ( is_wp_error( $result ) ) {
				learn_press_send_json_error( $result->get_error_message() );
			} elseif ( ! $result ) {
				learn_press_send_json_error();
			}

			learn_press_send_json_success( $result );
		}

		/**
		 * Send data to join newsletter or dismiss.
		 *
		 * [
		 *  This function has deprecated since 3.2.6 from this class.
		 *  Please check class LP_Admin and hook learn-press/dismissed-notice-response for more details.
		 *  Newsletter function be hooked to the hook above to send subscription when
		 *  notice has already dismissed.
		 * ]
		 *
		 * @deprecated
		 *
		 * @since 3.0.10
		 * @editable tungnx
		 * @model 4.1.4 comment - not use
		 */
		/*public static function join_newsletter() {
			$context = LP_Request::get_string( 'context' );
			if ( ! $context || $context != 'newsletter' ) {
				update_option( 'learn-press-dismissed-newsletter-button', 1 );
				learn_press_send_json_success( __( 'Dismissed!', 'learnpress' ) );
			}
			$user = learn_press_get_current_user();
			if ( ! $user || $user->get_email() == '' ) {
				learn_press_send_json_error( __( 'Fail while joining newsletter! Please try again!', 'learnpress' ) );
			}
			$url      = 'https://thimpress.com/mailster/subscribe';
			$response = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(
						'_referer' => 'extern',
						'_nonce'   => '4b266caf7b',
						'formid'   => '19',
						'email'    => $user->get_email(),
						'website'  => site_url(),
					),
					'cookies'     => array(),
				)
			);
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				learn_press_send_json_error( __( 'Something went wrong: ', 'learnpress' ) . $error_message );
			} else {
				update_option( 'learn-press-dismissed-newsletter-button', 1 );
				learn_press_send_json_success( __( 'Thank you for subscribing! Please check and click the confirmation link from the email we\'ve just sent to your mail box.', 'learnpress' ) );
			}
		}*/

		/**
		 * Duplicate course, lesson, quiz, question.
		 *
		 * @since 3.0.0
		 *
		 * @note tungnx checked has use
		 */
		public static function duplicator() {
			$post_id = LP_Request::get_string( 'id' );

			// get post type
			$post_type = learn_press_get_post_type( $post_id );

			if ( ! $post_id ) {
				learn_press_send_json_error( __( 'Ops! ID not found', 'learnpress' ) );
			} else {

				$new_item_id = '';

				$duplicate_args = apply_filters( 'learn-press/duplicate-post-args', array( 'post_status' => 'publish' ) );

				switch ( $post_type ) {
					case LP_COURSE_CPT:
						$curd        = new LP_Course_CURD();
						$new_item_id = $curd->duplicate(
							$post_id,
							array(
								'exclude_meta' => array(
									'order-pending',
									'order-processing',
									'order-completed',
									'order-cancelled',
									'order-failed',
									'count_enrolled_users',
									'_lp_sample_data',
									'_lp_retake_count',
								),
							)
						);
						break;
					case LP_LESSON_CPT:
						$curd        = new LP_Lesson_CURD();
						$new_item_id = $curd->duplicate( $post_id, $duplicate_args );
						break;
					case LP_QUIZ_CPT:
						$curd        = new LP_Quiz_CURD();
						$new_item_id = $curd->duplicate( $post_id, $duplicate_args );
						break;
					case LP_QUESTION_CPT:
						$curd        = new LP_Question_CURD();
						$new_item_id = $curd->duplicate( $post_id, $duplicate_args );
						break;
					default:
						break;
				}

				if ( is_wp_error( $new_item_id ) ) {
					learn_press_send_json_error( __( 'Duplicate post fail, please try again', 'learnpress' ) );
				} else {
					learn_press_send_json_success( admin_url( 'post.php?post=' . $new_item_id . '&action=edit' ) );
				}
			}
		}

		/**
		 * Update ordering of payments when user changing.
		 *
		 * @since 3.0.0
		 * @use for sorting by type payment gateway
		 * @note tungnx checked has use
		 */
		public static function update_payment_order() {
			$payment_order = learn_press_get_request( 'order' );
			update_option( 'learn_press_payment_order', $payment_order );
		}

		/**
		 * Enable type payment
		 *
		 * @since 3.0.0
		 * @use for enable type payment gateway
		 * @note tungnx checked has use
		 */
		public static function update_payment_status() {
			$payment_id = learn_press_get_request( 'id' );
			$status     = LP_Request::get_string( 'status' );
			$payment    = LP_Gateways::instance()->get_gateway( $payment_id );

			if ( ! $payment ) {
				return;
			}

			$response[ $payment->id ] = $payment->enable( $status == 'yes' );

			learn_press_send_json( $response );
		}

		/**
		 * nable email on LP Settings
		 *
		 * @since 3.0.0
		 * @note tungnnx checked has use
		 */
		public static function update_email_status() {

			$email_id = LP_Request::get_string( 'id' );
			$status   = LP_Request::get_string( 'status' );
			$response = array();

			if ( $email_id ) {

				$email = LP_Emails::get_email( $email_id );
				if ( ! $email ) {
					return;
				}

				$response[ $email->id ] = $email->enable( $status == 'yes' );
			} else {
				$emails = LP_Emails::instance()->emails;
				foreach ( $emails as $email ) {
					$response[ $email->id ] = $email->enable( $status == 'yes' );
				}
			}
			learn_press_send_json( $response );
		}

		/**
		 * Toggle lesson preview.
		 */
		/*public static function toggle_item_preview() {
			$id = learn_press_get_request( 'item_id' );
			if ( in_array(
				get_post_type( $id ),
				apply_filters(
					'learn-press/reviewable-post-types',
					array(
						'lp_lesson',
						'lp_quiz',
					)
				)
			) && wp_verify_nonce( learn_press_get_request( 'nonce' ), 'learn-press-toggle-item-preview' )
			) {
				$previewable = learn_press_get_request( 'previewable' );
				if ( is_null( $previewable ) ) {
					$previewable = '0';
				}
				update_post_meta( $id, '_lp_preview', $previewable );
			}
		}*/

		/**
		 * Search items by requesting params.
		 */
		public static function modal_search_items() {
			$term       = LP_Helper::sanitize_params_submitted( $_POST['term'] ?? '' );
			$type       = LP_Helper::sanitize_params_submitted( $_POST['type'] ?? '' );
			$context    = LP_Helper::sanitize_params_submitted( $_POST['context'] ?? '' );
			$context_id = LP_Helper::sanitize_params_submitted( $_POST['context_id'] ?? '' );
			$paged      = LP_Helper::sanitize_params_submitted( $_POST['paged'] ?? '' );
			$exclude    = LP_Request::get( 'exclude' );

			$search = new LP_Modal_Search_Items( compact( 'term', 'type', 'context', 'context_id', 'paged', 'exclude' ) );

			learn_press_send_json(
				array(
					'html'  => $search->get_html_items(),
					'nav'   => $search->get_pagination(),
					'items' => $search->get_items(),
				)
			);
		}

		/**
		 * Search items by requesting params.
		 *
		 * @note tungnx checked has use
		 */
		public static function modal_search_users() {
			$term        = LP_Helper::sanitize_params_submitted( $_POST['term'] ?? '' );
			$type        = LP_Helper::sanitize_params_submitted( $_POST['type'] ?? '' );
			$context     = LP_Helper::sanitize_params_submitted( $_POST['context'] ?? '' );
			$context_id  = LP_Helper::sanitize_params_submitted( $_POST['context_id'] ?? '' );
			$paged       = LP_Helper::sanitize_params_submitted( $_POST['paged'] ?? '' );
			$multiple    = LP_Helper::sanitize_params_submitted( $_POST['multiple'] ?? '' ) == 'yes';
			$text_format = LP_Helper::sanitize_params_submitted( $_POST['text_format'] ?? '' );
			$exclude     = LP_Request::get( 'exclude' );

			$search = new LP_Modal_Search_Users( compact( 'term', 'type', 'context', 'context_id', 'paged', 'multiple', 'text_format', 'exclude' ) );

			learn_press_send_json(
				array(
					'html'  => $search->get_html_items(),
					'nav'   => $search->get_pagination(),
					'users' => $search->get_items(),
				)
			);
		}

		/**
		 * Search course category.
		 */
		public static function search_course_category() {
			global $wpdb;
			$sql   = 'SELECT `t`.`term_id` as `id`, '
					 . ' `t`.`name` `text` '
					 . " FROM {$wpdb->terms} t "
					 . "		INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id AND taxonomy='course_category' "
					 . ' WHERE `t`.`name` LIKE %s';
			$s     = '%' . filter_input( INPUT_GET, 'q' ) . '%';
			$query = $wpdb->prepare( $sql, $s );
			$items = $wpdb->get_results( $query );
			$data  = array( 'items' => $items );
			echo json_encode( $data );
			exit();
		}

		/**
		 * Remove an item from lp order
		 *
		 * @note tungnx checked has use
		 */
		public static function remove_items_from_order() {
			// ensure that user has permission
			if ( ! current_user_can( 'edit_lp_orders' ) ) {
				die( __( 'Access denied', 'learnpress' ) );
			}

			// verify nonce
			$nonce = learn_press_get_request( 'remove_nonce' );
			if ( ! wp_verify_nonce( $nonce, 'remove_order_item' ) ) {
				die( __( 'Check nonce failed', 'learnpress' ) );
			}

			// validate order
			$order_id = learn_press_get_request( 'order_id' );
			if ( ! is_numeric( $order_id ) || learn_press_get_post_type( $order_id ) != 'lp_order' ) {
				die( __( 'Invalid order', 'learnpress' ) );
			}

			// validate item
			$items = learn_press_get_request( 'items' );

			$order = learn_press_get_order( $order_id );

			global $wpdb;

			foreach ( $items as $item_id ) {
				$order->remove_item( $item_id );
			}

			$order_data                  = learn_press_update_order_items( $order_id );
			$currency_symbol             = learn_press_get_currency_symbol( $order_data['currency'] );
			$order_data['subtotal_html'] = learn_press_format_price( $order_data['subtotal'], $currency_symbol );
			$order_data['total_html']    = learn_press_format_price( $order_data['total'], $currency_symbol );
			$order_items                 = $order->get_items();
			if ( $order_items ) {
				$html = '';
				foreach ( $order_items as $item ) {
					ob_start();
					include learn_press_get_admin_view( 'meta-boxes/order/order-item.php' );
					$html .= ob_get_clean();
				}
			}

			learn_press_send_json(
				array(
					'result'     => 'success',
					'item_html'  => $html,
					'order_data' => $order_data,
				)
			);
		}

		/**
		 * Add courses to order
		 *
		 * @note tungnx checked has use
		 */
		public static function add_items_to_order() {
			// ensure that user has permission
			if ( ! current_user_can( 'edit_lp_orders' ) ) {
				die( __( 'Permission denied', 'learnpress' ) );
			}

			// validate order
			$order_id = learn_press_get_request( 'order_id' );
			if ( ! is_numeric( $order_id ) || learn_press_get_post_type( $order_id ) != 'lp_order' ) {
				die( __( 'Invalid order', 'learnpress' ) );
			}

			// validate item
			$item_ids = learn_press_get_request( 'items' );
			$order    = learn_press_get_order( $order_id );

			$response = array(
				'result' => 'error',
			);

			$order_item_ids = $order->add_items( $item_ids );

			if ( $order_item_ids ) {
				$html        = '';
				$order_items = $order->get_items();

				$order_data                  = learn_press_update_order_items( $order_id );
				$currency_symbol             = learn_press_get_currency_symbol( $order_data['currency'] );
				$order_data['subtotal_html'] = learn_press_format_price( $order_data['subtotal'], $currency_symbol );
				$order_data['total_html']    = learn_press_format_price( $order_data['total'], $currency_symbol );

				if ( $order_items ) {
					foreach ( $order_items as $item ) {

						if ( ! in_array( $item['id'], $order_item_ids ) ) {
							continue;
						}

						ob_start();
						include learn_press_get_admin_view( 'meta-boxes/order/order-item.php' );
						$html .= ob_get_clean();
					}
				}

				$response = array(
					'result'     => 'success',
					'item_html'  => $html,
					'order_data' => $order_data,
				);
			}

			learn_press_send_json( $response );
		}

		/**
		 * Get content send via payload and parse to json.
		 *
		 * @param mixed $params (Optional) List of keys want to get from payload.
		 *
		 * @return array|bool|mixed|object
		 */
		public static function get_php_input( $params = '' ) {
			static $data = false;
			if ( false === $data ) {
				try {
					$data = json_decode( file_get_contents( 'php://input' ), true );
				} catch ( Exception $exception ) {
				}
			}

			if ( $data && func_num_args() > 0 ) {
				$params = is_array( func_get_arg( 0 ) ) ? func_get_arg( 0 ) : func_get_args();
				if ( $params ) {
					$request = array();
					foreach ( $params as $key ) {
						$request[] = array_key_exists( $key, $data ) ? $data[ $key ] : false;
					}

					return $request;
				}
			}

			return $data;
		}

		/**
		 * Parse request content into var.
		 * Normally, parse and assign to $_POST or $_GET.
		 *
		 * @param $var
		 */
		public static function parsePhpInput( &$var ) {
			$data = self::get_php_input();

			if ( $data ) {
				foreach ( $data as $k => $v ) {
					$var[ $k ] = $v;
				}
			}
		}

		public static function load_chart() {
			if ( ! class_exists( 'LP_Submenu_Statistics' ) ) {
				$statistic = include_once LP_PLUGIN_PATH . '/inc/admin/sub-menus/class-lp-submenu-statistics.php';
			} else {
				$statistic = new LP_Submenu_Statistics();
			}
			$statistic->load_chart();
		}

		/**
		 * @param $query
		 *
		 * @editor tungnx
		 * @model 4.1.4 comment - not use
		 */
		/*public static function search_users() {
			if ( ! current_user_can( 'edit_lp_orders' ) ) {
				die( - 1 );
			}

			$term = LP_Helper::sanitize_params_submitted( $_REQUEST['term'] );

			if ( empty( $term ) ) {
				die( __FILE__ . '::' . __FUNCTION__ );

			}

			$found_customers = array();

			add_action( 'pre_user_query', array( __CLASS__, 'json_search_customer_name' ) );

			$customers_query = new WP_User_Query(
				apply_filters(
					'learn_press_search_customers_query',
					array(
						'fields'         => 'all',
						'orderby'        => 'display_name',
						'search'         => '*' . $term . '*',
						'search_columns' => array( 'ID', 'user_login', 'user_email', 'user_nicename' ),
					)
				)
			);

			remove_action( 'pre_user_query', array( __CLASS__, 'json_search_customer_name' ) );

			$customers = $customers_query->get_results();

			if ( ! empty( $customers ) ) {
				foreach ( $customers as $customer ) {
					$found_customers[] = array(
						'label' => $customer->display_name . ' (#' . $customer->ID . ' &ndash; ' . sanitize_email( $customer->user_email ) . ')',
						'value' => $customer->ID,
					);
				}
			}

			echo json_encode( $found_customers );
			die();
		}*/

		public static function json_search_customer_name( $query ) {
			global $wpdb;

			$term = LP_Helper::sanitize_params_submitted( $_REQUEST['term'] );
			if ( method_exists( $wpdb, 'esc_like' ) ) {
				$term = $wpdb->esc_like( $term );
			} else {
				$term = like_escape( $term );
			}

			$query->query_from  .= " INNER JOIN {$wpdb->usermeta} AS user_name ON {$wpdb->users}.ID = user_name.user_id AND ( user_name.meta_key = 'first_name' OR user_name.meta_key = 'last_name' ) ";
			$query->query_where .= $wpdb->prepare( ' OR user_name.meta_value LIKE %s ', '%' . $term . '%' );
		}

		/**
		 * Dismiss notice
		 *
		 * @update 3.2.6
		 * @editor tungnx
		 * @comment 4.1.4 comment - not use
		 */
		/*public static function dismiss_notice() {
			$name    = learn_press_get_request( 'name' );
			$value   = learn_press_get_request( 'value' );
			$expired = learn_press_get_request( 'expired' );

			// LP_Admin_Notice::instance()->dismiss_notice_2( $name, $value, $expired );

			die();
		}*/

		/*
		 * comment by tungnnx
		 * @reason not uses - low security
		 * @since 3.2.6.8
		 */
		/*
		public static function plugin_action() {
			$url = learn_press_get_request( 'url' );
			ob_start();
			wp_remote_get( $url );
			ob_get_clean();
			echo wp_remote_get( admin_url( 'admin.php?page=learn-press-addons&tab=installed' ) );
			die();
		}*/

		/**
		 * create new page on LP Settings
		 *
		 * @note tungnnx checked use
		 */
		public static function create_page() {
			$response = array(
				'code'    => 0,
				'message' => '',
			);

			/**
			 * Check valid
			 *
			 * 1. Capability - user can edit pages (add\edit\delete)
			 * 2. Check nonce return true
			 * 3. param post page_name not empty
			 *
			 * @since  3.2.6.8
			 * @author tungnx
			 */
			if ( ! current_user_can( 'edit_pages' ) || empty( $_POST['page_name'] ) ) {
				$response['message'] = 'Request invalid';
				learn_press_send_json( $response );
			}

			$page_name = LP_Helper::sanitize_params_submitted( $_POST['page_name'] );

			if ( $page_name ) {
				$page_id = LP_Helper::create_page( $page_name );

				if ( $page_id ) {
					$response['code']    = 1;
					$response['message'] = 'create page success';
					$response['page']    = get_post( $page_id );
					$html                = learn_press_pages_dropdown( '', '', array( 'echo' => false ) );
					preg_match_all( '!value=\"([0-9]+)\"!', $html, $matches );
					$response['positions'] = $matches[1];
					$response['html']      = '<a href="' . get_edit_post_link( $page_id ) . '" target="_blank">' . __( 'Edit Page', 'learnpress' ) . '</a>&nbsp;';
					$response['html']     .= '<a href="' . get_permalink( $page_id ) . '" target="_blank">' . __( 'View Page', 'learnpress' ) . '</a>';
				} else {
					$response['error'] = __( 'Error! Page creation failed. Please try again.', 'learnpress' );
				}
			} else {
				$response['error'] = __( 'Empty page name!', 'learnpress' );
			}
			learn_press_send_json( $response );
		}

		/**
		 * Create LP static pages
		 *
		 * @editor tungnx
		 * @model 4.1.4 comment - not use
		 */
		/*public static function create_pages() {
			check_admin_referer( 'create-pages' );

			$pages      = LP_Request::get_list_array( 'pages' );
			$pages      = array_fill_keys( $pages, '' );
			$all_pages  = learn_press_static_page_ids();
			$page_names = learn_press_static_pages();

			if ( empty( $pages ) ) {
				$pages = $all_pages;
			}

			foreach ( $pages as $id => $page_id ) {
				if ( ! empty( $all_pages[ $id ] ) ) {
					continue;
				}

				$page_id = LP_Helper::create_page( isset( $page_names[ $id ] ) ? $page_names[ $id ] : ucfirst( $id ), $id );
			}

			LP()->flush_rewrite_rules();

			echo esc_html__( 'The required pages are successfully created.', 'learnpress' );

			die();
		}*/

		/**
		 * Install sample data or dismiss the notice depending on user's option
		 */
		/*public static function install_sample_data() {
			$yes            = ! empty( $_REQUEST['yes'] ) ? $_REQUEST['yes'] : '';
			$response       = array( 'result' => 'fail' );
			$retry_button   = sprintf( '<a href="" class="button yes" data-action="yes">%s</a>', __( 'Please try again.', 'learnpress' ) );
			$dismiss_button = sprintf( '<a href="" class="button disabled no" data-action="no">%s</a>', __( 'Cancel', 'learnpress' ) );
			$buttons        = sprintf( '<p>%s %s</p>', $retry_button, $dismiss_button );
			if ( 'no' == $yes ) {
				set_transient( 'learn_press_install_sample_data', 'off', 12 * HOUR_IN_SECONDS );
			} else {
				$result = array( 'status' => 'activate' );// learn_press_install_and_active_add_on( 'learnpress-import-export' );
				if ( 'activate' == $result['status'] ) {
					// copy dummy-data.xml to import folder of the add-on
					lpie_mkdir( lpie_import_path() );
					if ( file_exists( lpie_import_path() ) ) {
						$import_source = LP_PLUGIN_PATH . '/dummy-data/dummy-data.xml';
						$file          = 'dummy-data-' . time() . '.xml';
						$copy          = lpie_import_path() . '/' . $file;
						copy( $import_source, $copy );
						if ( file_exists( $copy ) ) {
							$url                 = admin_url( 'admin-ajax.php?page=learn_press_import_export&tab=import-course' );
							$postdata            = array(
								'step'        => 2,
								'action'      => 'learn_press_import',
								'import-file' => 'import/' . $file,
								'nonce'       => wp_create_nonce( 'lpie-import-file' ),
								'x'           => 1,
							);
							$response['url']     = $url = $url . '&' . http_build_query( $postdata ) . "\n";
							$response['result']  = 'success';
							$response['message'] = sprintf( '<p>%s <a href="edit.php?post_type=lp_course">%s</a> </p>', __( 'Successfully import sample data.', 'learnpress' ), __( 'View courses', 'learnpress' ) );
						}
					}
					if ( $response['result'] == 'fail' ) {
						$response['message'] = sprintf( '<p>%s</p>%s', __( 'Failed to import sample data. Please try again.', 'learnpress' ), $buttons );
					}
				} else {
					$response['result']  = 'fail';
					$response['message'] = sprintf( '<p>%s</p>', __( 'Unknown error when installing/activating Import/Export add-on. Please try again!', 'learnpress' ) ) . $buttons;
				}
			}
			learn_press_send_json( $response );
			die();
		}*/

		/**
		 * Activate a bundle of add-ons, if an add-on is not installed then install it first
		 *
		 * @editor tungnx
		 * @model 4.1.4 comment - not use
		 */
		/*public static function bundle_activate_add_ons() {
			global $learn_press_add_ons;
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // for plugins_api..
			$response = array( 'addons' => array() );

			if ( ! current_user_can( 'activate_plugins' ) ) {
				$response['error'] = __( 'You do not have the permission to deactivate plugins on this site.', 'learnpress' );
			} else {

				$add_ons = $learn_press_add_ons['bundle_activate'];

				if ( $add_ons ) {
					foreach ( $add_ons as $slug ) {
						$response['addons'][ $slug ] = learn_press_install_and_active_add_on( $slug );
					}
				}
			}
			learn_press_send_json( $response );
		}*/

		/**
		 * Activate a bundle of add-ons, if an add-on is not installed then install it first
		 *
		 * @editor tungnx
		 * @model 4.1.4 comment - not use
		 */
		/*public static function bundle_activate_add_on() {
			$response = array();
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // for plugins_api..
			if ( ! current_user_can( 'activate_plugins' ) ) {
				$response['error'] = __( 'You do not have the permission to deactivate plugins on this site.', 'learnpress' );
			} else {
				$slug              = ! empty( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : null;
				$response[ $slug ] = learn_press_install_and_active_add_on( $slug );
			}
			learn_press_send_json( $response );
		}*/

		/**
		 * @editor tungnx
		 * @reason not use
		 * @deprecated 4.0.0.
		 */
		/*
		public static function plugin_install() {
			$plugin_name = ! empty( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			$response    = learn_press_install_add_on( $plugin_name );
			learn_press_send_json( $response );
			die();
		}*/

		/*
		 * @editor tungnx
		 * @reason not use
		 * @since 3.2.6.8
		 */
		/*
		public static function update_add_on_status() {
			$plugin   = ! empty( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			$t        = ! empty( $_REQUEST['t'] ) ? $_REQUEST['t'] : '';
			$response = array();
			if ( ! current_user_can( 'activate_plugins' ) ) {
				$response['error'] = __( 'You do not have the permission to deactivate plugins on this site.', 'learnpress' );
			}
			if ( $plugin && $t ) {
				if ( $t == 'activate' ) {
					activate_plugin( $plugin, false, is_network_admin() );
				} else {
					deactivate_plugins( $plugin, false, is_network_admin() );
				}
				$is_activate        = is_plugin_active( $plugin );
				$response['status'] = $is_activate ? 'activate' : 'deactivate';

			}
			wp_send_json( $response );
			die();
		}*/

		/**
		 * Output the image to browser with text and params passed via $_GET
		 */
		/*public static function dummy_image() {
			$text = LP_Helper::sanitize_params_submitted( $_REQUEST['text'] ?? '' );
			learn_press_text_image( $text, $_GET );
			die();
		}*/

		/**
		 * Get edit|view link of a page
		 */
		public static function get_page_permalink() {
			$page_id = (int) $_REQUEST['page_id'] ?? 0;
			?>

			<a href="<?php echo get_edit_post_link( $page_id ); ?>"
			   target="_blank"><?php _e( 'Edit Page', 'learnpress' ); ?></a>
			<a href="<?php echo get_permalink( $page_id ); ?>"
			   target="_blank"><?php _e( 'View Page', 'learnpress' ); ?></a>

			<?php
			die();
		}

		/**
		 * Get date from, to for static chart
		 */
		public static function custom_stats() {
			$from      = LP_Helper::sanitize_params_submitted( $_REQUEST['from'] ?? 0 );
			$to        = LP_Helper::sanitize_params_submitted( $_REQUEST['to'] ?? 0 );
			$date_diff = strtotime( $to ) - strtotime( $from );
			if ( $date_diff <= 0 || $from == 0 || $to == 0 ) {
				die();
			}
			learn_press_process_chart( learn_press_get_chart_students( $to, 'days', floor( $date_diff / ( 60 * 60 * 24 ) ) + 1 ) );
			die();
		}

		/**
		 * @editor tungnx
		 * @model 4.1.4 comment - not use
		 */
		/*public static function ignore_setting_up() {
			update_option( '_lpr_ignore_setting_up', 1, true );
			die;
		}*/

		/**
		 * @editor tungnx
		 * @model 4.1.4 comment - not use
		 */
		/*public static function remove_notice_popup() {
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'learnpress_remove_notice_popup' && isset( $_POST['slug'] ) && ! empty( $_POST['slug'] ) && isset( $_POST['user'] ) && ! empty( $_POST['user'] ) ) {
				$slug = 'learnpress_notice_' . $_POST['slug'] . '_' . $_POST['user'];
				set_transient( $slug, true, 30 * DAY_IN_SECONDS );
			}

			wp_die();
		}*/

		/*
		public static function update_order_status() {

			$order_id = learn_press_get_request( 'order_id' );
			$value    = learn_press_get_request( 'value' );

			$order = array(
				'ID'          => $order_id,
				'post_status' => $value,
			);

			wp_update_post( $order ) ? $response['success'] = true : $response['success'] = false;

			learn_press_send_json( $response );

			die();
		}*/

		public static function upload_user_avatar() {
			$file       = $_FILES['lp-upload-avatar'];
			$upload_dir = learn_press_user_profile_picture_upload_dir();

			add_filter( 'upload_dir', array( __CLASS__, '_user_avatar_upload_dir' ), 10000 );

			$result = wp_handle_upload(
				$file,
				array(
					'test_form' => false,
				)
			);

			remove_filter( 'upload_dir', array( __CLASS__, '_user_avatar_upload_dir' ), 10000 );
			if ( is_array( $result ) ) {
				$result['name'] = $upload_dir['subdir'] . '/' . basename( $result['file'] );
				unset( $result['file'] );
			} else {
				$result = array(
					'error' => __( 'Profile picture upload failed', 'learnpress' ),
				);
			}
			learn_press_send_json( $result );
		}

		public static function _user_avatar_upload_dir( $dir ) {
			$dir = learn_press_user_profile_picture_upload_dir();

			return $dir;
		}

		/**
		 * Export Order invoice to PDF
		 *
		 * @since 3.2.7.8
		 * @author hungkv
		 */
		public static function update_order_exports() {
			$order_id = absint( $_POST['order_id'] );
			//$site_title      = LP_Helper::sanitize_params_submitted( $_POST['site_title'] );
			//$order_date      = LP_Helper::sanitize_params_submitted( $_POST['order_date'] );
			//$invoice_no      = LP_Helper::sanitize_params_submitted( $_POST['invoice_no'] );
			//$order_customer  = LP_Helper::sanitize_params_submitted( $_POST['order_customer'] );
			//$order_email     = LP_Helper::sanitize_params_submitted( $_POST['order_email'] );
			//$order_payment   = LP_Helper::sanitize_params_submitted( $_POST['order_payment'] );
			$order           = learn_press_get_order( $order_id );
			$currency_symbol = learn_press_get_currency_symbol( $order->get_currency() );

			ob_start();
			learn_press_admin_view(
				'meta-boxes/order/content-tab-preview-exports-invoice.php',
				array(
					'order'           => $order,
					'currency_symbol' => $currency_symbol,
				)
			);
			$html = ob_get_clean();
			echo $html;
			die();
		}
	}

	if ( defined( 'DOING_AJAX' ) ) {
		add_action( 'wp_ajax_learnpress_upload-user-avatar', array( 'LP_Admin_Ajax', 'upload_user_avatar' ) );
	}

	add_action( 'init', array( 'LP_Admin_Ajax', 'init' ) );
}
