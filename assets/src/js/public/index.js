/**
 * Front-end entry point.
 *
 * Add front-end behaviour here. The compiled bundle is enqueued by
 * ITG_Plugin_Setup_Public when the build exists.
 */

import '../../scss/public.scss';

/**
 * Mark the document as having the plugin script loaded.
 */
function initPublic() {
	document.documentElement.classList.add( 'itg-plugin-setup-js' );
}

if ( document.readyState !== 'loading' ) {
	initPublic();
} else {
	document.addEventListener( 'DOMContentLoaded', initPublic );
}
