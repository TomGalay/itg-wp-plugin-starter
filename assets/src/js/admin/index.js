/**
 * Admin entry point.
 *
 * The settings form itself is rendered server-side. Use this entry for admin
 * interactivity (for example a React settings app using @wordpress/element).
 */

import '../../scss/admin.scss';

/**
 * Mark the admin root as ready once the script has loaded.
 */
function initAdmin() {
	const root = document.getElementById( 'itg-plugin-setup-admin-root' );

	if ( root ) {
		root.setAttribute( 'data-itg-ready', 'true' );
	}
}

if ( document.readyState !== 'loading' ) {
	initAdmin();
} else {
	document.addEventListener( 'DOMContentLoaded', initAdmin );
}
