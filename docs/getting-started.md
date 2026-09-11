# Getting started

## Requirements

| Tool | Version |
|------|---------|
| PHP | 8.0+ |
| WordPress | 6.6+ |
| Composer | 2.2+ |
| Node.js | 20+ |
| npm | 10+ |

## Create a plugin from this starter

1. Click **Use this template** on GitHub (or clone the repository).
2. Rename the folder to your plugin slug, e.g. `acme-widgets`.
3. Install dependencies and scaffold:

   ```bash
   composer install
   npm install
   php bin/scaffold.php            # or: composer scaffold
   ```

   The scaffolder asks for a plugin name, slug, prefix, author and URIs, then
   rewrites every file and filename. Preview it first with `--dry-run`.

4. Build the assets and run the checks:

   ```bash
   npm run build
   composer lint
   composer test
   ```

5. Copy the folder into `wp-content/plugins/`, activate it, and open
   **Settings → <Plugin Name>**.

## Daily commands

| Command | Purpose |
|---------|---------|
| `composer scaffold` | Rename the starter into a new plugin. |
| `composer lint` | Run PHPCS (WordPress-Extra + PHPCompatibilityWP). |
| `composer lint:fix` | Auto-fix PHPCS issues. |
| `composer test` | Run the PHPUnit unit suite. |
| `composer test:integration` | Run the WordPress integration suite (needs the WP test library). |
| `npm run start` | Watch and rebuild assets. |
| `npm run build` | Production build. |
| `npm run lint:js` / `npm run lint:css` | Lint JavaScript / styles. |
| `npm run format` | Format JS/CSS with Prettier. |
| `npm test` | Run the Jest unit tests. |

## Running the integration tests locally

The WordPress test library ships with the `wp-phpunit/wp-phpunit` Composer
dependency, so a normal `composer install` is enough — no SVN checkout needed.

1. Create a throwaway database (its contents are wiped on every run):

   ```sql
   CREATE DATABASE wordpress_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Copy the sample config and set `ABSPATH` and the database credentials:

   ```bash
   cp tests/wp-tests-config-sample.php tests/wp-tests-config.php
   ```

3. Run the suite:

   ```bash
   composer test:integration
   ```

`tests/php/bootstrap-integration.php` auto-detects the bundled library and the
local config. Set `WP_TESTS_DIR` to override the library location.

CI instead installs a standalone test library and points `WP_TESTS_DIR` at it:

```bash
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
composer test:integration
```

Set `WP_TESTS_DIR` if the library is installed somewhere other than the default
`/tmp/wordpress-tests-lib`.
