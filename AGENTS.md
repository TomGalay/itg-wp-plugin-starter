# AGENTS.md

Guidance for working in this WordPress plugin / starter repository.

## Stack

- **CMS**: WordPress 6.6+
- **PHP**: 8.0+ (developed on 8.3)
- **Database**: MySQL
- **Build**: `@wordpress/scripts` (webpack)
- **Standards**: WordPress-Extra + PHPCompatibilityWP

## Commands

Run from the plugin root:

- **Scaffold**: `composer scaffold` (or `php bin/scaffold.php`)
- **Lint PHP**: `composer lint`; autofix with `composer lint:fix`
- **Test PHP**: `composer test` (unit), `composer test:integration` (WordPress)
- **Lint JS/CSS**: `npm run lint:js`, `npm run lint:css`
- **Test JS**: `npm test`
- **Build**: `npm run build` (watch: `npm run start`)

## Conventions

- `snake_case` for PHP functions and variables; `camelCase` for JavaScript.
- One prefix everywhere: `itg_plugin_setup` (functions/vars),
  `ITG_PLUGIN_SETUP_` (constants), `ITG_Plugin_Setup` (classes); text domain
  `itg-plugin-setup`.
- Every PHP file starts with `if ( ! defined( 'ABSPATH' ) ) { exit; }`.
- Class filename matches the class: `class-itg-plugin-setup-example.php`.
- Sanitize input, escape output as late as possible.
- Verify nonces and capabilities on privileged actions.
- Use `$wpdb->prepare()` for all query values; prefix tables with `$wpdb->prefix`.
- Register shortcodes on `init` and return output.
- Register REST routes on `rest_api_init` with a `permission_callback`.
- Enqueue assets with explicit dependencies and versions.
- Internationalize user-facing strings with the `itg-plugin-setup` text domain.
- Use `WP_Error` for failures; keep logging behind a development check.

## Architecture

- `includes/class-itg-plugin-setup.php` — main coordinator (singleton).
- `integrations/` — one interface, an abstract base, a manager, and service
  implementations. See `docs/integrations.md`.
- `admin/` and `public/` — environment-specific controllers.
- `rest/` — REST controllers under `itg-plugin-setup/v1`.
- `assets/src/` — JS/SCSS source compiled into `build/`.

## Shell environment

- **OS**: Windows (PowerShell 5.1) and CI on Linux. Chain with `cmd1; if ($?) { cmd2 }`,
  not `&&`.
- Use the full path to `vendor\bin\phpcs.bat` on Windows when `vendor/bin` is not on
  `PATH`.
