=== ITG Plugin Setup ===
Contributors: itg
Tags: integrations, firebase, email
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin foundation with a pluggable integrations layer.

== Description ==

ITG Plugin Setup provides a reusable foundation for WordPress plugins that need
to talk to third-party services. It ships with a generic integration contract
and two worked examples: Firebase (Authentication, Cloud Storage, Cloud
Messaging, Cloud Functions and Analytics/Remote Config) and Email (SendGrid and
Mailgun).

Integration classes are dependency free by default. The bundled
`wp_remote_request()` transport can be swapped for `kreait/firebase-php` or
`google/auth` when server-side authentication is required.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the Plugins screen.
3. Configure integrations under Settings > ITG Plugin Setup.

== Development ==

See `README.md` for the full setup, architecture and scripting reference.
