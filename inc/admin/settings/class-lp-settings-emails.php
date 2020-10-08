<?php
/**
 * Class LP_Settings_Emails
 *
 * @author  ThimPress
 * @package LearnPress/Classes
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

require_once 'email-groups/class-lp-settings-emails-group.php';

class LP_Settings_Emails extends LP_Abstract_Settings_Page {
	/**
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id   = 'emails';
		$this->text = esc_html__( 'Emails', 'learnpress' );

		parent::__construct();

		add_filter( 'learn-press/admin/submenu-section-title', array( $this, 'custom_section_title' ), 10, 2 );
	}

	/**
	 * Add tooltip to section title
	 *
	 * @param string $title
	 * @param string $slug
	 *
	 * @return string
	 */
	public function custom_section_title( $title, $slug ) {
		$sections = $this->get_sections();

		if ( ! empty( $sections[ $slug ] ) && $sections[ $slug ] instanceof LP_Email ) {
			if ( $sections[ $slug ]->description ) {
				$title = $title . sprintf( '<span class="learn-press-tooltip" data-tooltip="%s"></span>', esc_attr( $sections[ $slug ]->description ) );
			}
		}

		return $title;
	}

	/**
	 * Sections
	 *
	 * @return mixed
	 */
	public function get_sections() {
		static $sections = false;

		if ( ! $sections ) {
			$emails = LP_Emails::instance()->emails;

			$sections = array(
				'general' => esc_html__( 'General', 'learnpress' ),
			);

			if ( $emails ) {
				$groups = array(
					include 'email-groups/class-lp-settings-new-order-emails.php',
					include 'email-groups/class-lp-settings-processing-order-emails.php',
					include 'email-groups/class-lp-settings-completed-order-emails.php',
					include 'email-groups/class-lp-settings-cancelled-order-emails.php',
					include 'email-groups/class-lp-settings-enrolled-course-emails.php',
					include 'email-groups/class-lp-settings-finished-course-emails.php',
					// include "email-groups/class-lp-settings-course-review-emails.php",
					include 'email-groups/class-lp-settings-become-teacher-emails.php',
				);

				$groups = apply_filters( 'learn-press/email-section-classes', $groups );

				foreach ( $groups as $group ) {
					$sections[ $group->group_id ] = $group;
				}

				foreach ( $emails as $email ) {
					foreach ( $groups as $group ) {
						if ( ! empty( $group->items[ $email->id ] ) ) {
							continue 2;
						}
					}

					$sections[ $email->id ] = $email;
				}
			}
		}

		return apply_filters( 'learn-press/settings/section/' . $this->id, $sections );
	}

	/**
	 * Settings fields of general section.
	 *
	 * @return mixed
	 */
	public function get_settings_general() {
		return apply_filters(
			'learn-press/emails-settings/general',
			array(
				array(
					'title' => esc_html__( 'Sender Information', 'learnpress' ),
					'type'  => 'heading',
					'desc'  => esc_html__( 'For all outgoing LearnPress notification emails.', 'learnpress' ),
				),
				array(
					'title'   => esc_html__( 'Sender Name', 'learnpress' ),
					'id'      => 'emails_general[from_name]',
					'default' => get_option( 'blogname' ),
					'type'    => 'text',
				),
				array(
					'title'   => esc_html__( 'Sender Email', 'learnpress' ),
					'id'      => 'emails_general[from_email]',
					'default' => get_option( 'admin_email' ),
					'type'    => 'email',
				),
				array(
					'title'   => esc_html__( 'Background Email', 'learnpress' ),
					'id'      => 'emails_general[send_email_background]',
					'default' => 'no',
					'type'    => 'yes-no',
					'desc'    => esc_html__( 'Sending emails in the background.', 'learnpress' ),
				),
				array(
					'title' => esc_html__( 'Template', 'learnpress' ),
					'type'  => 'heading',
				),
				array(
					'title'   => esc_html__( 'Content Type', 'learnpress' ),
					'id'      => 'emails_general[default_email_content]',
					'default' => 'plain',
					'type'    => 'select',
					'options' => array(
						'plain' => esc_html__( 'Plain Text', 'learnpress' ),
						'html'  => esc_html__( 'HTML', 'learnpress' ),
					),
				),
				array(
					'title'            => esc_html__( 'Header Image', 'learnpress' ),
					'id'               => 'emails_general[header_image]',
					'default'          => '',
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
				),
				array(
					'title'   => esc_html__( 'Footer Text', 'learnpress' ),
					'id'      => 'emails_general[footer_text]',
					'default' => esc_html__( 'LearnPress', 'learnpress' ),
					'type'    => 'textarea',
				),
				array(
					'title'   => esc_html__( 'Emails', 'learnpress' ),
					'id'      => 'emails_general[list_emails]',
					'default' => '',
					'type'    => 'list-emails',
				),
			)
		);
	}

	/**
	 * Display admin page for payments settings tab.
	 *
	 * @param string $section
	 * @param string $tab
	 */
	public function admin_page( $section = null, $tab = null ) {
		$sections = array();
		$items    = LP_Admin_Menu::instance()->get_menu_items();

		if ( ! empty( $items['settings'] ) ) {
			$tab      = $items['settings']->get_active_tab();
			$section  = $items['settings']->get_active_section();
			$sections = $items['settings']->get_sections();
		}
		$section_data = ! empty( $sections[ $section ] ) ? $sections[ $section ] : false;

		// If current section is an instance of Settings just call to admin_options.
		if ( $section_data instanceof LP_Email ) {
			$section_data->admin_options();
		} elseif ( is_array( $section_data ) ) {
		} else {
			// If I have a function point to current section with prefix 'admin_options_'.
			// Then call to it.
			if ( is_callable( array( $this, 'admin_options_' . $section ) ) ) {
				call_user_func_array(
					array( $this, 'admin_options_' . $section ),
					array(
						$section,
						$tab,
					)
				);
			} else {
				// leave of all, do an action.
				do_action( 'learn-press/admin/setting-payments/admin-options-' . $section, $tab );
			}
		}
	}

	/**
	 * Output admin option of general page.
	 *
	 * @param string $section
	 * @param string $tab
	 */
	public function admin_options_general( $section, $tab ) {
		parent::admin_page( $section, $tab );
	}

	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

return LP_Settings_Emails::instance();
