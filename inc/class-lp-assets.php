<?php
/**
 * Class LP_Assets
 *
 * @author  ThimPress
 * @package LearnPress/Classes
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

class LP_Assets extends LP_Abstract_Assets {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	}

	protected function get_bundle_css_url() {
		$url = false;
		if ( get_option( 'learn_press_exclude_frontend_libraries' ) ) {
			$upload_dir = wp_upload_dir();
			if ( file_exists( $upload_dir['basedir'] . '/learnpress/bundle.min.css' ) ) {
				$url = $upload_dir['baseurl'] . '/learnpress/bundle.min.css';
			}
		}

		return $url;
	}

	/**
	 * Get default styles in admin.
	 *
	 * @return mixed
	 */
	protected function _get_styles() {
		$custom_css = get_option( '_lp_custom_css' );
		if ( $custom_css ) {
			$upload   = wp_upload_dir();
			$main_css = $upload['baseurl'] . '/learnpress/' . $custom_css;
		} else {
			$main_css = self::url( 'css/learnpress.css' );
		}

		$url = $this->get_bundle_css_url();

		return apply_filters(
			'learn-press/frontend-default-styles',
			array(
				'font-awesome-5' => array(
					'url'     => self::url( 'css/vendor/font-awesome-5.min.css' ),
					'screens' => array( 'learnpress' ),
				),
				'lp-bundle'      => array(
					'url'     => $url ? $url : self::url( 'css/bundle.min.css' ),
					'screens' => array( 'learnpress' ),
				),
				'learnpress'     => array(
					'url'     => $main_css,
					'screens' => 'learnpress',
				),
			)
		);
	}

	public function _get_script_data() {
		return array(
			'lp-global'       => array(
				'url'         => learn_press_get_current_url(),
				'siteurl'     => site_url(),
				'ajax'        => admin_url( 'admin-ajax.php' ),
				'courses_url' => learn_press_get_page_link( 'courses' ),
				'post_id'     => get_the_ID(),
				'theme'       => get_stylesheet(),
				'localize'    => array(
					'button_ok'     => __( 'OK', 'learnpress' ),
					'button_cancel' => __( 'Cancel', 'learnpress' ),
					'button_yes'    => __( 'Yes', 'learnpress' ),
					'button_no'     => __( 'No', 'learnpress' ),
				),
				'lp_rest_url' => get_rest_url(),
			),
			'lp-checkout'     => array(
				'ajaxurl'              => home_url(),
				'user_waiting_payment' => LP()->checkout()->get_user_waiting_payment(),
				'user_checkout'        => LP()->checkout()->get_checkout_email(),
				'i18n_processing'      => __( 'Processing', 'learnpress' ),
				'i18n_redirecting'     => __( 'Redirecting', 'learnpress' ),
				'i18n_invalid_field'   => __( 'Invalid field', 'learnpress' ),
				'i18n_unknown_error'   => __( 'Unknown error', 'learnpress' ),
				'i18n_place_order'     => __( 'Place order', 'learnpress' ),
			),
			'lp-profile-user' => array(
				'processing'  => __( 'Processing', 'learnpress' ),
				'redirecting' => __( 'Redirecting', 'learnpress' ),
				'avatar_size' => learn_press_get_avatar_thumb_size(),
			),
			'lp-course'       => learn_press_single_course_args(),
			'lp-quiz'         => learn_press_single_quiz_args(),
		);

	}

	protected function get_all_plugins_url( $min = '' ) {
		$url = false;

		if ( get_option( 'learn_press_exclude_frontend_libraries' ) ) {
			$upload_dir = wp_upload_dir();

			if ( file_exists( $upload_dir['basedir'] . '/learnpress/plugins.all' . $min . '.js' ) ) {
				$url = $upload_dir['baseurl'] . '/learnpress/plugins.all' . $min . '.js';
			}
		}

		return $url;
	}

	public function _get_scripts() {
		$min   = learn_press_is_debug() ? '' : '.min';
		$wp_js = array(
			'jquery',
			'wp-element',
			'wp-compose',
			'wp-data',
			'wp-hooks',
			'wp-api-fetch',
			'lodash',
		);

		$url = $this->get_all_plugins_url( $min );

		return apply_filters(
			'learn-press/frontend-default-scripts',
			array(
				'lp-modal'            => array(
					'url'  => self::url( 'js/frontend/modal.js' ),
					'deps' => array(
						'jquery',
					),
				),
				'lp-plugins-all'      => array(
					'url' => $url ? $url : self::url( 'js/vendor/plugins.all' . $min . '.js' ),
				),
				'lp-global'           => array(
					'url'  => self::url( 'js/global' . $min . '.js' ),
					'deps' => array( 'jquery', 'underscore', 'utils' ),
				),
				'lp-utils'            => array(
					'url'     => self::url( 'js/utils' . $min . '.js' ),
					'deps'    => array( 'jquery' ),
					'screens' => 'learnpress',
				),
				'learnpress'          => array(
					'url'  => self::url( 'js/frontend/learnpress' . $min . '.js' ),
					'deps' => array( 'lp-global' ),
				),
				'lp-checkout'         => array(
					'url'     => self::url( 'js/frontend/checkout' . $min . '.js' ),
					'deps'    => array( 'lp-global' ),
					'screens' => learn_press_is_checkout() || learn_press_is_course() && ! learn_press_is_learning_course(),

				),
				'lp-data-controls'    => array(
					'url'  => self::url( 'js/frontend/data-controls' . $min . '.js' ),
					'deps' => array_merge( $wp_js, array( 'lp-global' ) ),
				),
				'lp-config'           => array(
					'url'  => self::url( 'js/frontend/config' . $min . '.js' ),
					'deps' => array_merge( $wp_js, array( 'lp-global' ) ),
				),
				'lp-lesson'           => array(
					'url'  => self::url( 'js/frontend/lesson' . $min . '.js' ),
					'deps' => array_merge( $wp_js, array( 'lp-global' ) ),
				),
				'lp-question-types'   => array(
					'url'  => self::url( 'js/frontend/question-types' . $min . '.js' ),
					'deps' => array_merge( $wp_js, array( 'lp-global' ) ),
				),
				'lp-quiz'             => array(
					'url'  => self::url( 'js/frontend/quiz' . $min . '.js' ),
					'deps' => array_merge( $wp_js, array( 'lp-global', 'lp-question-types', 'lp-modal' ) ),
				),
				'lp-single-course'    => array(
					'url'     => self::url( 'js/frontend/single-course' . $min . '.js' ),
					'deps'    => array(
						'lp-global',
						'lp-config',
						'lp-data-controls',
						'lp-quiz',
						'lp-lesson',
						'lp-custom',
					),
					'screens' => array( 'course' ),
				),
				'lp-courses'          => array(
					'url'     => self::url( 'js/frontend/courses' . $min . '.js' ),
					'deps'    => array( 'lp-global', 'lodash' ),
					'screens' => learn_press_is_courses(),
				),
				'lp-profile-user'     => array(
					'url'     => self::url( 'js/frontend/profile' . $min . '.js' ),
					'deps'    => array(
						'lp-global',
						'plupload',
						'backbone',
						'jquery-ui-slider',
						'jquery-ui-draggable',
						'jquery-touch-punch',
					),
					'screens' => learn_press_is_profile(),
				),
				'lp-become-a-teacher' => array(
					'url'     => self::url( 'js/frontend/become-teacher' . $min . '.js' ),
					'deps'    => array(
						'jquery',
					),
					'screens' => learn_press_is_page( 'become_a_teacher' ),
				),
				'lp-custom'           => array(
					'url'  => self::url( 'js/frontend/custom' . $min . '.js' ),
					'deps' => array(
						'jquery',
					),
				),
			)
		);

	}

	/**
	 * Load assets
	 */
	public function load_scripts() {

		$this->_register_scripts();

		/**
		 * Enqueue scripts
		 */
		$scripts = $this->_get_scripts();

		if ( $scripts ) {
			foreach ( $scripts as $handle => $data ) {
				$enqueue = false;

				do_action( 'learn-press/enqueue-script/' . $handle );

				if ( ! empty( $data['screens'] ) ) {
					$enqueue = $this->is_screen( $data['screens'] );
				}

				if ( $enqueue ) {
					wp_enqueue_script( $handle );
				}
			}
		}

		$styles = $this->_get_styles();

		if ( $styles ) {
			foreach ( $styles as $handle => $data ) {
				$enqueue = false;

				do_action( 'learn-press/enqueue-style/' . $handle );

				if ( ! empty( $data['screens'] ) ) {
					$enqueue = $this->is_screen( $data['screens'] );
				}

				if ( $enqueue ) {
					wp_enqueue_style( $handle );
				}
			}
		}

		do_action( 'learn-press/after-enqueue-scripts' );
	}

	/**
	 * Check is currently in a screen required.
	 *
	 * @param array $screens
	 *
	 * @return bool
	 * @since 3.3.0
	 */
	public function is_screen( $screens ) {
		$pages = array(
			'profile',
			'become_a_teacher',
			'term_conditions',
			'checkout',
			'courses',
		);

		$single_post_types                  = array();
		$single_post_types[ LP_COURSE_CPT ] = 'course';
		$is_screen                          = false;

		if ( $screens === true || $screens === '*' ) {
			$is_screen = true;
		} else {
			$screens = is_array( $screens ) ? $screens : array( $screens );
			if ( in_array( 'learnpress', $screens ) ) {
				foreach ( $pages as $page ) {
					if ( $page === 'courses' && learn_press_is_courses() ) {
						$is_screen = true;
						break;
					}

					if ( learn_press_is_page( $page ) ) {
						$is_screen = true;
						break;
					}

					foreach ( $single_post_types as $post_type => $alias ) {
						if ( is_singular( $post_type ) ) {
							$is_screen = true;
							break 2;
						}
					}
				}
			} else {
				foreach ( $pages as $page ) {
					if ( in_array( $page, $screens ) ) {
						if ( $page === 'courses' && learn_press_is_courses() ) {
							$is_screen = true;
							break;
						}

						if ( learn_press_is_page( $page ) ) {
							$is_screen = true;
							break;
						}
					}
				}
			}

			if ( ! $is_screen ) {
				foreach ( $single_post_types as $post_type => $alias ) {
					if ( is_singular( $post_type ) && in_array( $alias, $screens ) ) {
						$is_screen = true;
						break;
					}
				}
			}
		}

		return $is_screen;
	}
}

/**
 * Shortcut function to get instance of LP_Assets
 *
 * @return LP_Assets|null
 */
function learn_press_assets() {
	static $assets = null;
	if ( ! $assets ) {
		$assets = new LP_Assets();
	}

	return $assets;
}

/**
 * Load frontend asset
 */
if ( ! is_admin() ) {
	learn_press_assets();
}

