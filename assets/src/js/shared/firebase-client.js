/**
 * Placeholder for the Firebase JavaScript SDK client.
 *
 * Client-side Firebase features (Auth, Analytics, Remote Config, callable
 * Functions, Cloud Messaging) are best used from the browser. Install the SDK
 * and initialise it here when the integration is needed:
 *
 *     npm install firebase
 *
 * Then build the admin/public bundles so the SDK is bundled and enqueued.
 *
 * @param {Object} config Firebase web configuration.
 * @return {boolean} Whether the minimum configuration is present.
 */
export function isFirebaseConfigured( config = {} ) {
	return Boolean( config.apiKey && config.projectId );
}
