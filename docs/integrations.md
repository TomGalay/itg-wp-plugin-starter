# Integrations

The integration layer is the core of this starter. Every third-party service
implements one contract, and the manager boots the services that are configured.

## The contract

```php
interface ITG_Plugin_Setup_Integration {
	public function get_id();              // machine-readable id
	public function get_name();            // human-readable name
	public function is_configured();       // enough settings to run?
	public function register();            // add hooks (called only when configured)
	public function get_settings_fields(); // admin fields
}
```

`ITG_Plugin_Setup_Integration_Abstract` provides `get_setting()` helpers backed
by a single option (`itg_plugin_setup_integrations`) that stores settings per
integration.

`ITG_Plugin_Setup_Integration_Manager` collects the defaults, applies the
`itg_plugin_setup_integrations` filter, and boots the configured integrations.

## Adding a new integration

1. Create `integrations/<service>/class-<prefix>-integration-<service>.php`:

   ```php
   class ITG_Plugin_Setup_Integration_Acme extends ITG_Plugin_Setup_Integration_Abstract {
       const ID = 'acme';

       public function get_id() { return self::ID; }
       public function get_name() { return __( 'Acme', 'itg-plugin-setup' ); }
       public function is_configured() {
           return '' !== (string) $this->get_setting( 'api_key', '' );
       }
       public function register() {
           // add_action( ... );
       }
       public function get_settings_fields() {
           return array(
               'api_key' => array(
                   'label' => __( 'API key', 'itg-plugin-setup' ),
                   'type'  => 'password',
               ),
           );
       }
   }
   ```

2. Require the file in `ITG_Plugin_Setup::load_dependencies()`.
3. Add an instance to the defaults array in the integration manager.
4. Run `composer lint` and `npm run build`.

The admin screen and the `/integrations` REST route pick it up automatically.

## Firebase example

`ITG_Plugin_Setup_Integration_Firebase` wires five services:

| Service | Class | Typical use |
|---------|-------|-------------|
| Authentication | `ITG_Plugin_Setup_Firebase_Auth` | Verify ID tokens, look up users, set custom claims |
| Cloud Storage | `ITG_Plugin_Setup_Firebase_Storage` | Object metadata, delete, signed URLs |
| Cloud Messaging | `ITG_Plugin_Setup_Firebase_Messaging` | Send push notifications (HTTP v1) |
| Cloud Functions | `ITG_Plugin_Setup_Firebase_Functions` | Invoke HTTPS functions server-to-server |
| Analytics / Remote Config | `ITG_Plugin_Setup_Firebase_Remote_Config` | Read/publish Remote Config templates |

### Server-side transport

`ITG_Plugin_Setup_Firebase_Client` is deliberately SDK-free. It uses the
WordPress HTTP API (`wp_remote_request()`), so no native PHP extensions are
required, and requests respect WordPress HTTP filters, proxies and timeouts.

It resolves an OAuth2 access token from the
`itg_plugin_setup_firebase_access_token` filter. Drop in whichever library you
prefer:

```php
add_filter( 'itg_plugin_setup_firebase_access_token', function ( $token, $client ) {
	// e.g. google/auth or kreait/firebase-php
	return $token;
}, 10, 2 );
```

**Why not `kreait/firebase-php` by default?**

- `kreait/firebase-php` itself is dependency-light and works for Auth, Storage
  and Messaging.
- The **Firestore** component (`google/cloud-firestore`) hard-requires
  `ext-grpc`, which many local and shared hosts do not have. This starter
  therefore omits Firestore.
- To use the full SDK, add it as a **runtime** dependency and inject its token
  through the filter above. The production host must satisfy the SDK's platform
  requirements.

## Email example

`ITG_Plugin_Setup_Integration_Email` sends through a selected provider:

- **SendGrid** — `ITG_Plugin_Setup_Email_SendGrid` (v3 API)
- **Mailgun** — `ITG_Plugin_Setup_Email_Mailgun`

Both are dependency-free and use `wp_remote_post()`. Shared response handling
lives in `ITG_Plugin_Setup_Email_Provider::check_response()`.

```php
$email = ITG_Plugin_Setup::init()->get_integrations()->get( 'email' );
$email->send( 'user@example.com', 'Hello', '<p>Hi there</p>' );
```
