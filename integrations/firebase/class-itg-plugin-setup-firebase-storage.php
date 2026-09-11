<?php
/**
 * Firebase Cloud Storage helper.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wraps the Google Cloud Storage JSON API for Firebase Storage buckets.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Firebase_Storage {

	/**
	 * Firebase client.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Firebase_Client
	 */
	private $client;

	/**
	 * Storage bucket name.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $bucket;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param ITG_Plugin_Setup_Firebase_Client $client Firebase client.
	 * @param string              $bucket Storage bucket name.
	 */
	public function __construct( ITG_Plugin_Setup_Firebase_Client $client, $bucket = '' ) {
		$this->client = $client;
		$this->bucket = (string) $bucket;
	}

	/**
	 * Whether a bucket has been configured.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_configured() {
		return '' !== $this->bucket;
	}

	/**
	 * Download an object's metadata.
	 *
	 * @since 0.1.0
	 *
	 * @param string $path Object path within the bucket.
	 * @return array<string, mixed>|WP_Error Metadata or an error.
	 */
	public function get_metadata( $path ) {
		$url = sprintf(
			'https://storage.googleapis.com/storage/v1/b/%s/o/%s',
			rawurlencode( $this->bucket ),
			rawurlencode( $path )
		);

		return $this->client->request( 'GET', $url );
	}

	/**
	 * Delete an object.
	 *
	 * @since 0.1.0
	 *
	 * @param string $path Object path within the bucket.
	 * @return array<string, mixed>|WP_Error Response or an error.
	 */
	public function delete( $path ) {
		$url = sprintf(
			'https://storage.googleapis.com/storage/v1/b/%s/o/%s',
			rawurlencode( $this->bucket ),
			rawurlencode( $path )
		);

		return $this->client->request( 'DELETE', $url );
	}

	/**
	 * Generate a signed URL for temporary access.
	 *
	 * TODO: Build a V4 signed URL with the service account private key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $path Object path within the bucket.
	 * @param int    $ttl  Time to live in seconds.
	 * @return string|WP_Error Signed URL or an error.
	 */
	public function get_signed_url( $path, $ttl = 3600 ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		return new WP_Error(
			'itg_firebase_not_implemented',
			__( 'Signed URL generation is not implemented yet.', 'itg-plugin-setup' )
		);
	}
}
