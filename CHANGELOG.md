# Changelog

All notable changes to this project are documented here. The format is based on
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project
adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.1] - 2026-09-11

### Fixed

- REST route `GET /itg-plugin-setup/v1/integrations` no longer throws a
  `TypeError` on every request. The base controller's `permissions_check()` was
  `protected` but registered as the route's `permission_callback`, which
  WordPress invokes via `call_user_func()` from outside class scope; it is now
  `public`.

### Added

- Unit guard asserting `permissions_check()` is callable from outside class
  scope.
- WordPress integration test that dispatches real REST requests (guest `401`,
  administrator `200` with the Firebase and Email integrations).
- Local integration test workflow using the bundled `wp-phpunit` library and a
  `tests/wp-tests-config-sample.php` template, so no SVN checkout is required.

### Changed

- Documented the distribution artifact policy in the README (`.distignore` is
  the source of truth; `build/` ships, `vendor/`/`node_modules/`/`tests/`/`docs/`
  do not).
- Excluded `.phpunit.result.cache` from distribution builds.

## [0.1.0] - 2026-09-11

### Added

- Reusable WordPress plugin starter with a pluggable integrations layer.
- `bin/scaffold.php` to rename the starter into a new plugin.
- Firebase integration example (Authentication, Cloud Storage, Cloud Messaging,
  Cloud Functions, Analytics/Remote Config) using `wp_remote_request()`.
- Email integration example (SendGrid, Mailgun).
- Admin settings screen, REST controller (`itg-plugin-setup/v1`) and WP-CLI
  command.
- `@wordpress/scripts` build with admin and public entries.
- PHPUnit unit + WordPress integration test scaffolds, and Jest tests.
- GitHub Actions CI, nightly integration tests, tagged releases, Dependabot and
  issue/PR templates.

[Unreleased]: https://github.com/TomGalay/itg-wp-plugin-starter/compare/v0.1.1...HEAD
[0.1.1]: https://github.com/TomGalay/itg-wp-plugin-starter/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/TomGalay/itg-wp-plugin-starter/releases/tag/v0.1.0
