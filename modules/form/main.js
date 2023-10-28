/*!
 * Utilities for forms.
 *
 * @author Niklas Laxström
 */
'use strict';

$( function () {
	$( '#ainut-app-form select.mw-ainut-tags' ).select2( {
		tags: true,
		tokenSeparators: [ ',' ]
	} );

	$( '#ainut-app-form .oo-ui-fieldsetLayout-group > .oo-ui-labelElement' ).each( function () {
		$( this ).children( '.oo-ui-fieldLayout-messages' ).insertBefore(
			$( this ).find( '> .oo-ui-fieldLayout-body > .oo-ui-fieldLayout-field' )
		);
	} );

	$( '#ainut-app-form' ).css( 'display', 'block' );
} );

$( function () {

	$( '#ainut-app-form textarea' ).each( function () {
		// eslint-disable-next-line no-undef
		autosize( $( this ) );
	} );

	$( 'div.mw-ainut-len-1000 h3' ).each( function () {
		$( this ).append(
			' ',
			$( '<span>' )
				.text( mw.msg( 'ainut-app-len-short', 100, 1000 ) )
				.addClass( 'mw-ainut-length-short' )
		);
	} );

	$( 'div.mw-ainut-len-4000 h3' ).each( function () {
		$( this ).append(
			' ',
			$( '<span>' )
				.text( mw.msg( 'ainut-app-len-short', 500, 4000 ) )
				.addClass( 'mw-ainut-length-short' )
		);
	} );

	$( 'div.mw-ainut-len-5000 h3' ).each( function () {
		$( this ).append(
			' ',
			$( '<span>' )
				.text( mw.msg( 'ainut-app-len-short', 500, 5000 ) )
				.addClass( 'mw-ainut-length-short' )
		);
	} );

	$( '#ainut-app-form' ).on( 'focusin input propertychange', 'textarea', function ( e ) {
		let min, max;
		if ( $( this ).parent().is( '.mw-ainut-len-4000' ) ) {
			min = 500;
			max = 4000;
		} else if ( $( this ).parent().is( '.mw-ainut-len-5000' ) ) {
			min = 500;
			max = 5000;
		} else if ( $( this ).parent().is( '.mw-ainut-len-1000' ) ) {
			min = 100;
			max = 1000;
		}

		if ( !max ) {
			return;
		}

		const len = $( this ).val().length;
		let $info = $( '.mw-ainut-length-info' );

		if ( e.type === 'focusin' ) {
			$info.remove();
			$( 'mw-ainut-has-length-info' ).removeClass( 'mw-ainut-has-length-info' );
			$info = $( '<div>' ).addClass( 'mw-ainut-length-info mw-message-box' );
			$( this ).addClass( 'mw-ainut-has-length-info' ).after( $info );
		}

		$info.text( mw.msg( 'ainut-app-leninfo', len, min, max ) );
		$info.removeClass( 'mw-message-box-success mw-message-box-warning mw-message-box-error' );
		if ( len < min ) {
			$info.addClass( 'mw-message-box-warning' );
		} else if ( len <= max ) {
			$info.addClass( 'mw-message-box-success' );
		} else {
			$info.addClass( 'mw-message-box-error' );
		}

	} );
} );
