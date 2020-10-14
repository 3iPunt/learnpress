<?php
/**
 * Template for displaying course sidebar.
 *
 * @version 4.0.0
 * @author  ThimPress
 * @package LearnPress/Templates
 */

defined( 'ABSPATH' ) || exit;

ob_start();
dynamic_sidebar( 'course-sidebar' );
$output = ob_get_clean();

/**
 * Hide sidebar if there is no content
 */
if ( ! $output && ! LP()->template( 'course' )->has_sidebar() ) {
	return;
}
?>

<aside class="course-summary-sidebar">
	<div class="course-summary-sidebar__inner">
		<div class="course-sidebar-top">
			<?php

			/**
			 * LP Hook
			 *
			 * @since 4.0.0
			 */
			do_action( 'learn-press/before-course-summary-sidebar' );

			/**
			 * LP Hook
			 *
			 * @since 4.0.0
			 *
			 * @see   LP_Template_Course::course_sidebar_preview() - 10
			 * @see   LP_Template_Course::course_featured_review() - 20
			 */
			do_action( 'learn-press/course-summary-sidebar' );

			/**
			 * LP Hook
			 *
			 * @since 4.0.0
			 */
			do_action( 'learn-press/after-course-summary-sidebar' );

			?>
		</div>

		<div class="course-sidebar-secondary">
			<?php
			echo $output;
			?>
		</div>
	</div>
</aside>
