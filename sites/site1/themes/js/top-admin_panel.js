$(document).ready(function() {
	
	// console.log( 'aaa', $.cookie( 'topadminpanel' ) );

	if ($.cookie( 'topadminpanel' )==1) {
		$( '#topadminpanel' ).addClass( 'active' );
	}
	$( '#topadminpanel #zakrepit' ).click(function() {
		if ( $( '#topadminpanel' ).hasClass( 'active' ) ) {
			$.cookie( 'topadminpanel', null);
			$( '#topadminpanel' ).removeClass( 'active' );
		} else {
			$.cookie( 'topadminpanel', 1);
			$( '#topadminpanel' ).addClass( 'active' );
		}
		return false;
	});
});