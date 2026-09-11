# ITG WP Plugin Starter

[![CI](https://github.com/TomGalay/itg-wp-plugin-starter/actions/workflows/ci.yml/badge.svg)](https://github.com/TomGalay/itg-wp-plugin-starter/actions/workflows/ci.yml)
[![License: GPL-2.0-or-later](https://img.shields.io/badge/license-GPL--2.0--or--later-blue.svg)](LICENSE)
[![PHP 8.0+](https://img.shields.io/badge/php-8.0%2B-777bb4.svg)](https://www.php.net/)
[![WordPress 6.6+](https://img.shields.io/badge/wordpress-6.6%2B-21759b.svg)](https://wordpress.org/)

A reusable WordPress **plugin starter** with:

- A **pluggable integrations layer** — add a service by implementing one interface.
- **WordPress Coding Standards** enforced (`WordPress-Extra` + `PHPCompatibilityWP`).
- A modern **`@wordpress/scripts`** JS/CSS build with multiple entries.
- **PHPUnit** (unit + optional WordPress integration) and **Jest** test suites.
- **GitHub Actions** CI, nightly integration tests and tagged releases.
- A **one-command scaffolder** that renames the starter into a new plugin.

The repository ships a fully working example plugin, `itg-plugin-setup`, with two
worked integrations: **Firebase** (Auth, Storage, Messaging, Functions, Remote
Config) and **Email** (SendGrid, Mailgun).

---

## Requirements

| Tool | Version |
|------|---------|
| PHP | 8.0+ |
| WordPress | 6.6+ |
| Composer | 2.2+ |
| Node.js | 20+ |
| npm | 10+ |

## Create a plugin from this starter

1. Click **Use this template** on GitHub, or clone/copy this repository.
2. Rename the folder to your plugin slug (e.g. `acme-widgets`).
3. Install dependencies and run the scaffolder:

   ```bash
   composer install
   npm install
   composer scaffold        # or: php bin/scaffold.php
   ```

   Preview the changes first with `composer scaffold -- --dry-run`.

   The scaffolder prompts for the plugin name, slug, prefixes, author and URIs,
   then rewrites every file and renames the main plugin file and class files.

4. Build and verify:

   ```bash
   npm run build
   composer lint
   composer test
   ```

5. Drop the folder into `wp-content/plugins/`, activate, and open
   **Settings → <Plugin Name>**.

### Scaffolder options

| Option | Description |
|--------|-------------|
| `--name` | Plugin display name, e.g. `"Acme Widgets"`. |
| `--slug` | Plugin slug / text domain, e.g. `acme-widgets`. |
| `--prefix` | PHP function/variable prefix (defaults to the slug with underscores). |
| `--const-prefix` | Constant prefix (defaults to the upper-cased prefix). |
| `--class-prefix` | Class prefix, e.g. `Acme_Widgets`. |
| `--author`, `--author-uri`, `--plugin-uri`, `--description` | Metadata. |
| `--dry-run` | Show what would change without writing anything. |
| `--remove-template` | Delete the scaffolder (and its composer script) after success. |
| `--verify` | Run `composer lint` and `npm run build` when finished. |

## Scripts

| Command | Purpose |
|---------|---------|
| `composer scaffold` | Rename the starter into a new plugin. |
| `composer lint` / `composer lint:fix` | PHPCS check / autofix. |
| `composer test` | PHPUnit unit tests. |
| `composer test:integration` | WordPress integration tests (needs the WP test library). |
| `npm run build` / `npm run start` | Build / watch assets. |
| `npm run lint:js` / `npm run lint:css` | Lint JS / styles. |
| `npm run format` | Format JS/CSS with Prettier. |
| `npm test` | Jest unit tests. |

## Project structure

```
itg-plugin-setup/
├── itg-plugin-setup.php          # Bootstrap: header, constants, requires, hooks
├── uninstall.php                 # Cleanup on delete
├── includes/                     # Core lifecycle (coordinator, cron, i18n, helpers)
├── admin/                        # Admin settings screen + views
├── public/                       # Front-end controller
├── integrations/                 # Pluggable services (contract + Firebase + Email)
├── rest/                         # REST controllers (itg-plugin-setup/v1)
├── cli/                          # WP-CLI commands
├── assets/src/{js,scss}          # Source assets (built into build/)
├── templates/  languages/  build/
├── tests/                        # PHPUnit + Jest
├── bin/                          # Scaffolder + install-wp-tests.sh
└── docs/                         # Guides
```

## How the plugin boots

1. `itg-plugin-setup.php` defines constants and requires the lifecycle classes.
2. On `plugins_loaded`, `ITG_Plugin_Setup::init()` builds the integration manager
   and registers the sub-controllers.
3. On `init` (priority 5) the manager boots every **configured** integration.
4. On `rest_api_init` the REST controllers register their routes.
5. Activation/deactivation manage options and the daily cron event.

## Integrations

Every service implements `ITG_Plugin_Setup_Integration`; the manager boots the
configured ones. The admin screen and `/integrations` REST route discover them
automatically. See **[docs/integrations.md](docs/integrations.md)** for the
contract, a step-by-step "add an integration" guide, and the Firebase transport
notes.

## REST API

Namespace: `itg-plugin-setup/v1`

| Method | Route | Auth | Description |
|--------|-------|------|-------------|
| `GET` | `/integrations` | `manage_options` | List integrations and their status. |

## Configuration & secrets

- Settings live in the `itg_plugin_setup_integrations` option and are sanitized
  per field type (`select`, `email`, `text`, `password`).
- **Never commit** service-account JSON or API keys. Store them in
  `wp-config.php` constants or environment variables.

## Testing

- **Unit (PHP):** `composer test` — runs with a lightweight WordPress stub
  (`tests/php/bootstrap.php`), no database required.
- **Integration (PHP):** `composer test:integration` — runs against a real
  WordPress install via `bin/install-wp-tests.sh`
  (`tests/php/bootstrap-integration.php`).
- **JS:** `npm test` (Jest via `@wordpress/scripts`).

## Coding standards

- PHP: `WordPress-Extra` + `PHPCompatibilityWP` (`testVersion 8.0-`).
- JS/CSS: ESLint, Prettier, stylelint via `@wordpress/scripts`.
- One prefix everywhere: `itg_plugin_setup` / `ITG_PLUGIN_SETUP_` /
  `ITG_Plugin_Setup`; text domain `itg-plugin-setup`.

## CI & releases

- **`.github/workflows/ci.yml`** — PHPCS (PHP 8.0–8.3), PHPUnit (8.2–8.3), and
  JS lint/test/build (Node 20/22).
- **`.github/workflows/integration.yml`** — WordPress integration tests on a
  nightly schedule and manual dispatch (MySQL service).
- **`.github/workflows/release.yml`** — pushing a `v*` tag builds a production
  zip and publishes a GitHub Release.
- Dependabot keeps Composer, npm and Actions dependencies up to date.

### Distribution artifacts

`.distignore` is the single source of truth for what ships. The release workflow
copies the tree minus the ignored paths, so the **working copy is the source
repository, not the distributable**.

| Path | In git | In release zip | Why |
|------|--------|----------------|-----|
| `build/` | ignored | **included** | Required at runtime for `wp_enqueue_*`. |
| `vendor/` | ignored | excluded | The runtime uses explicit `require_once`; nothing loads `vendor/autoload.php`. |
| `node_modules/` | ignored | excluded | Build-time only. |
| `tests/`, `docs/`, `bin/`, `*.dist`, `composer.*`, `package*` | tracked | excluded | Development-only. |

Do not ship the working copy wholesale (i.e. "everything except `.git`"): it
bundles hundreds of MB of `node_modules/` and exposes test/config files. To
produce a distributable locally, run the same steps as `release.yml`:
`composer install --no-dev`, `npm ci`, `npm run build`, then copy with
`rsync --exclude-from=.distignore`.

## Troubleshooting

- **`@jsonjoy.com/fs-fsa@4.75.0` 404 during `npm install`.** A broken publish on
  the npm registry. The `overrides` block pins `memfs` to `4.74.0`, whose
  dependency family installs cleanly. You may remove the override once upstream
  is fixed.
- **`ext-grpc` missing.** Expected — the starter intentionally avoids the gRPC
  dependency by not using Firestore. See the Firebase section for trade-offs.
- **Assets not loading.** Run `npm run build`; the enqueue methods return early
  when `build/*.asset.php` is missing.

## Documentation

- [Getting started](docs/getting-started.md)
- [Architecture](docs/architecture.md)
- [Integrations](docs/integrations.md)

## License

[GPL-2.0-or-later](LICENSE).
