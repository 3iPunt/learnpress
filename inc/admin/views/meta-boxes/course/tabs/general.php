<?php
/**
 * Course general data panel.
 *
 * @author ThimPress <nhamdv>
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="general_course_data" class="lp-meta-box-course-panels">
	<?php
	do_action( 'learnpress/course-settings/before-general' );

	// Remove when Eduma theme and guest update 4.0.0
	if ( ! empty( apply_filters( 'learn_press_course_settings_meta_box_args', null ) ) ) {
		$meta_box = array(
			'id'       => 'course_settings',
			'title'    => __( 'General', 'learnpress' ),
			'pages'    => array( LP_COURSE_CPT ),
			'priority' => 'high',
			'icon'     => 'dashicons-admin-tools',
			'fields'   => array(
				array(
					'id'                => '_lp_duration',
					'label'             => esc_html__( 'Duration', 'learnpress' ),
					'description'       => esc_html__( 'Set 0 for lifetime access.', 'learnpress' ),
					'default_time'      => 'week',
					'default'           => '10',
					'custom_attributes' => array(
						'min'  => '0',
						'step' => '1',
					),
					'type'              => 'duration',
				),
				array(
					'id'          => '_lp_block_lesson_content',
					'label'       => esc_html__( 'Block content', 'learnpress' ),
					'description' => esc_html__( 'Block content item( lesson, quiz... ) of course when expiration time course.', 'learnpress' ),
					'default'     => 'no',
					'type'        => 'checkbox',
				),
				array(
					'id'          => '_lp_level',
					'label'       => esc_html__( 'Level', 'learnpress' ),
					'description' => esc_html__( 'Choose a difficulty level.', 'learnpress' ),
					'default'     => '',
					'options'     => array(
						''             => esc_html__( 'All levels', 'learnpress' ),
						'beginner'     => esc_html__( 'Beginner', 'learnpress' ),
						'intermediate' => esc_html__( 'Intermediate', 'learnpress' ),
						'expert'       => esc_html__( 'Expert', 'learnpress' ),
					),
					'type'        => 'select',
				),
				array(
					'id'                => '_lp_students',
					'label'             => esc_html__( 'Promote', 'learnpress' ),
					'description'       => esc_html__( 'Show a plus number of students for the course.', 'learnpress' ),
					'type'              => 'number',
					'default'           => '0',
					'custom_attributes' => array(
						'min'  => '0',
						'step' => '1',
					),
					'style'             => 'width: 60px;',
				),
				array(
					'id'                => '_lp_max_students',
					'label'             => esc_html__( 'Max student', 'learnpress' ),
					'description'       => esc_html__( 'Maximum students can join the course. Set 0 for unlimited.', 'learnpress' ),
					'type'              => 'number',
					'default'           => '0',
					'custom_attributes' => array(
						'min'  => '0',
						'step' => '1',
					),
					'style'             => 'width: 60px;',
				),
				array(
					'id'          => '_lp_retake_count',
					'label'       => esc_html__( 'Retry', 'learnpress' ),
					'description' => esc_html__( 'Allow students to try the course one more time.', 'learnpress' ),
					'default'     => 'no',
					'type'        => 'checkbox',
				),
				array(
					'id'          => '_lp_featured',
					'label'       => esc_html__( 'Featured list', 'learnpress' ),
					'description' => esc_html__( 'Add the course to Featured List.', 'learnpress' ),
					'default'     => 'no',
					'type'        => 'checkbox',
				),
				array(
					'id'          => '_lp_featured_review',
					'label'       => esc_html__( 'Featured review', 'learnpress' ),
					'description' => esc_html__( 'A good review to promote the course.', 'learnpress' ),
					'placeholder' => esc_html__( 'e.g. This course is so great and helpful. Thank you the best teacher to explain and show us what LearnPress LMS is all about.', 'learnpress' ),
					'default'     => '',
					'type'        => 'textarea',
				),
				array(
					'id'          => '_lp_external_link_buy_course',
					'label'       => esc_html__( 'External link', 'learnpress' ),
					'description' => esc_html__( 'Normally use for offline classes, e.g. link to a contact page.', 'learnpress' ),
					'placeholder' => 'https://',
					'default'     => '',
					'type'        => 'text',
				),
			),
		);

		$meta_boxes = apply_filters( 'learn_press_course_settings_meta_box_args', $meta_box );

		LP_Meta_Box_Course::eduma_child_metabox_v3( $meta_boxes );
	} else {
		lp_meta_box_duration_field(
			array(
				'id'                => '_lp_duration',
				'label'             => esc_html__( 'Duration', 'learnpress' ),
				'description'       => esc_html__( 'Set 0 for lifetime access.', 'learnpress' ),
				'default_time'      => 'week',
				'default'           => '10',
				'custom_attributes' => array(
					'min'  => '0',
					'step' => '1',
				),
			)
		);

		lp_meta_box_checkbox_field(
			array(
				'id'          => '_lp_block_lesson_content',
				'label'       => esc_html__( 'Block content', 'learnpress' ),
				'description' => esc_html__( 'Block content item( lesson, quiz... ) of course when expiration time course.', 'learnpress' ),
				'default'     => 'no',
			)
		);

		lp_meta_box_select_field(
			array(
				'id'          => '_lp_level',
				'label'       => esc_html__( 'Level', 'learnpress' ),
				'description' => esc_html__( 'Choose a difficulty level.', 'learnpress' ),
				'default'     => '',
				'options'     => array(
					''             => esc_html__( 'All levels', 'learnpress' ),
					'beginner'     => esc_html__( 'Beginner', 'learnpress' ),
					'intermediate' => esc_html__( 'Intermediate', 'learnpress' ),
					'expert'       => esc_html__( 'Expert', 'learnpress' ),
				),
			)
		);

		lp_meta_box_text_input_field(
			array(
				'id'                => '_lp_students',
				'label'             => esc_html__( 'Promote', 'learnpress' ),
				'description'       => esc_html__( 'Show a plus number of students for the course.', 'learnpress' ),
				'type'              => 'number',
				'default'           => '0',
				'custom_attributes' => array(
					'min'  => '0',
					'step' => '1',
				),
				'style'             => 'width: 70px;',
			)
		);

		lp_meta_box_text_input_field(
			array(
				'id'                => '_lp_max_students',
				'label'             => esc_html__( 'Max student', 'learnpress' ),
				'description'       => esc_html__( 'Maximum students can join the course. Set 0 for unlimited.', 'learnpress' ),
				'type'              => 'number',
				'default'           => '0',
				'custom_attributes' => array(
					'min'  => '0',
					'step' => '1',
				),
				'style'             => 'width: 70px;',
			)
		);

		lp_meta_box_checkbox_field(
			array(
				'id'          => '_lp_retake_count',
				'label'       => esc_html__( 'Retry', 'learnpress' ),
				'description' => esc_html__( 'Allow students to try the course one more time.', 'learnpress' ),
				'default'     => 'no',
			)
		);

		lp_meta_box_checkbox_field(
			array(
				'id'          => '_lp_featured',
				'label'       => esc_html__( 'Featured list', 'learnpress' ),
				'description' => esc_html__( 'Add the course to Featured List.', 'learnpress' ),
				'default'     => 'no',
			)
		);

		lp_meta_box_textarea_field(
			array(
				'id'          => '_lp_featured_review',
				'label'       => esc_html__( 'Featured review', 'learnpress' ),
				'description' => esc_html__( 'A good review to promote the course.', 'learnpress' ),
				'placeholder' => esc_html__( 'e.g. This course is so great and helpful. Thank you the best teacher to explain and show us what LearnPress LMS is all about.', 'learnpress' ),
				'default'     => '',
			)
		);

		lp_meta_box_text_input_field(
			array(
				'id'          => '_lp_external_link_buy_course',
				'label'       => esc_html__( 'External link', 'learnpress' ),
				'description' => esc_html__( 'Normally use for offline classes, e.g. link to a contact page.', 'learnpress' ),
				'placeholder' => 'https://',
				'default'     => '',
			)
		);
	}

	do_action( 'learnpress/course-settings/after-general' );
	?>
</div>
