<?php
/**
 * Admin question editor: question actions template.
 *
 * @since 3.0.0
 */
?>

<script type="text/x-template" id="tmpl-lp-question-actions">
	<div class="lp-box-data-head lp-row">
		<h3 class="heading">
			<?php esc_html_e( 'Details', 'learnpress' ); ?>
			<div class="section-item-counts"><span>{{typeLabel()}}</span></div>
		</h3>
		<div class="lp-box-data-actions lp-toolbar-buttons">
			<div class="lp-toolbar-btn question-actions">
				<div class="question-types">
					<a href="" class="lp-btn-icon dashicons dashicons-arrow-down-alt2"></a>
					<ul>
						<li v-for="(type, key) in types" :data-type="key" :class="active(key)">
							<a href="" @click.prevent="changeType(key)">{{type}}</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

</script>

<script type="text/javascript">

	jQuery( function( $ ) {
		var $store = window.LP_Question_Store;
		var pick = lodash.pick;

		window.$Vue = window.$Vue || Vue;

		$Vue.component( 'lp-question-actions', {
			template: '#tmpl-lp-question-actions',
			props: ['type'],
			computed: {
				types: function() {
					return $store.getters['types']
				}
			},
			methods: {
				typeLabel: function() {
					var types = this.types;
					return types[this.type];
				},
				active: function( type ) {
					var classes = [''];

					if ( this.type === type ) {
						classes.push('active');
					}

					var supportTypes = $store.getters['supportAnswerOptions'];

					if ( supportTypes.indexOf( type ) === -1 || supportTypes.indexOf( this.type ) === -1 ) {
						classes.push( 'disabled' )
					}

					return classes;
				},
				changeType: function( type ) {
					if ( this.type !== type ) {
						this.$emit( 'changeType', type );
					}
				},
				getQuestionsSupportAnswerOptions: function() {
					var supportTypes = $store.getters['supportAnswerOptions'];

					return supportTypes.indexOf( this.type ) !== -1 ? pick( this.types, supportTypes ) : false;
				}
			}
		});
	});
</script>
