/**
 * Thin wrapper around the plugin REST API.
 */

const NAMESPACE = 'itg-plugin-setup/v1';

/**
 * Perform a request against the plugin REST namespace.
 *
 * @param {string} path    Path relative to the namespace, e.g. '/integrations'.
 * @param {Object} options Fetch options.
 * @return {Promise<*>} Parsed JSON response.
 */
export async function request( path, options = {} ) {
	const config = window.itgPluginSetup || {};
	const url = `${ config.restUrl || '/wp-json/' }${ NAMESPACE }${ path }`;

	const response = await fetch( url, {
		...options,
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': config.nonce || '',
			...( options.headers || {} ),
		},
	} );

	if ( ! response.ok ) {
		throw new Error( `Request failed with status ${ response.status }` );
	}

	return response.json();
}

/**
 * Retrieve the list of integrations.
 *
 * @return {Promise<Array>} Integration status list.
 */
export function getIntegrations() {
	return request( '/integrations' );
}
