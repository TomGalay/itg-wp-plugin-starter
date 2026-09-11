# Changelog

All notable changes to this project are documented here. The format is based on
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project
adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[Unreleased]: https://github.com/itg/itg-wp-plugin-starter/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/itg/itg-wp-plugin-starter/releases/tag/v0.1.0
