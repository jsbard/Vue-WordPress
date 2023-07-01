jQuery.ajaxSetup(
	{
		crossDomain: true
	}
);
jQuery( document ).ready(
	function ($) {
		$( document ).on(
			'click',
			'#enable-cors-notice > button',
			function () {
				$.ajax(
					{
						url: ajaxurl,
						data: {
							action: 'enable_cors_noticed',
							notice: 'settings-notice',
							nonce: $( '#enable-cors-notice' ).data( 'nonce' )
						}
					}
				);
			}
		);
	}
);
