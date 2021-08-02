
document.addEventListener( 'DOMContentLoaded', function( event ) {
	const elements = document.querySelector( 'ul.lp-order-statuses' );
	const eleChild = document.querySelector( 'li.counter-number' );
	const eleLoad = document.querySelector( 'ul.lp-skeleton-animation' );

	const getResponse = async ( ele ) => {
		if ( eleChild == null ) {
			try {
				const response = await wp.apiFetch( {
					path: wp.url.addQueryArgs( 'lp/v1/orders/statistic' ),
					method: 'GET',
				} );

				if ( response.status === 'success' && response.data ) {
					ele.insertAdjacentHTML( 'afterbegin', response.data );
					ele.removeChild( eleLoad );
				} else {
					ele.innerHTML = `<div class="lp-ajax-message error" style="display:block">${ response.message && response.message }</div>`;
				}
			} catch ( error ) {
				ele.innerHTML += `<div class="lp-ajax-message error" style="display:block">${ error.message && error.message }</div>`;
			}
		}
	};

	getResponse( elements );
} );
