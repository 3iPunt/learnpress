import lpModalOverlay from '../../../../utils/lp-modal-overlay';
import handleAjax from '../../../../utils/handle-ajax-api';

const cleanDatabases = () => {
	const elCleanDatabases = document.querySelector( '#lp-tool-clean-database' );

	if ( ! elCleanDatabases ) {
		return;
	}

	const elBtnCleanDatabases = elCleanDatabases.querySelector( '.lp-btn-clean-db' );
	elBtnCleanDatabases.addEventListener( 'click', function( e ) {
		e.preventDefault();
		const r = confirm( 'You must choose at least one table to take this action' );
		if ( r == false ) {
			return;
		}
		const elLoading = elCleanDatabases.querySelector( '.wrapper-lp-loading' );
		if ( ! lpModalOverlay.init() ) {
			return;
		}

		lpModalOverlay.elLPOverlay.show();
		lpModalOverlay.setContentModal( elLoading.innerHTML );
		lpModalOverlay.setTitleModal( elCleanDatabases.querySelector( 'h2' ).textContent );
		lpModalOverlay.elBtnYes[ 0 ].style.display = 'inline-block';
		lpModalOverlay.elBtnYes[ 0 ].textContent = 'Run';
		lpModalOverlay.elBtnNo[ 0 ].textContent = 'Close';

		// const tables = new Array();
		// const elToolsSelect = document.querySelector( '#tools-select__id' );
		// const ElToolSelectLi = elToolsSelect.querySelectorAll( 'ul li input' );
		// ElToolSelectLi.forEach( ( e ) => {
		// 	tables.push( e.value );
		// } );

		const tables = 'learnpress_sessions';
		const item = elLoading.querySelector( '.progressbar__item' );

		const itemtotal = item.getAttribute( 'data-total' );
		lpModalOverlay.callBackYes = () => {
			// warn user before doing
			const r = confirm( 'The modified data is impossible to be restored. Please backup your website before doing this.' );
			if ( r == false ) {
				return;
			}
			const notice = elLoading.querySelector( '.lp-modal-body .main-content .lp-tool__message' );
			console.log( notice );
			notice.textContent = 'ssdsdsds';
			const url = '/lp/v1/admin/tools/clean-tables';
			const params = { tables, itemtotal };

			lpModalOverlay.elBtnNo[ 0 ].style.display = 'none';
			lpModalOverlay.elBtnYes[ 0 ].style.display = 'none';

			const functions = {
				success: ( res ) => {
					const { status, message, data: { processed, percent } } = res;
					const modal = document.querySelector( '.lp-modal-body .main-content' );
					const modalItem = modal.querySelector( '.progressbar__item' );
					const progressBarRows = modalItem.querySelector( '.progressbar__rows' );
					const progressPercent = modalItem.querySelector( '.progressbar__percent' );
					const progressValue = modalItem.querySelector( '.progressbar__value' );
					if ( 'success' === status ) {
						setTimeout( () => {
							handleAjax( url, params, functions );
						}, 2000 );
						// update processed quantity
						progressBarRows.textContent = processed + ' / ' + itemtotal;
						// update percent
						progressPercent.textContent = '( ' + percent + '%' + ' )';
						// update percent width
						progressValue.style.width = percent + '%';
					} else if ( 'finished' === status ) {
						// Re-update indexs
						progressBarRows.textContent = itemtotal + ' / ' + itemtotal;
						progressPercent.textContent = '( 100% )';

						// Show finish button
						lpModalOverlay.elBtnNo[ 0 ].style.display = 'inline-block';
						lpModalOverlay.elBtnNo[ 0 ].textContent = 'Finish';
					} else {
						console.log( message );
					}
				},
				error: ( err ) => {
					console.log( err );
				},
				completed: () => {

				},
			};
			handleAjax( url, params, functions );
		};
	} );
};
export default cleanDatabases;
