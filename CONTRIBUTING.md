# Contributing

Thanks for improving the ITG WP Plugin Starter.

## Getting set up

```bash
composer install
npm install
npm run build
```

## Before opening a pull request

Run the full check suite locally:

```bash
composer lint
composer test
npm run lint:js
npm run lint:css
npm test
npm run build
```

All checks must pass. The same commands run in CI.

## Guidelines

- Follow the WordPress Coding Standards enforced by `composer lint`.
- Keep one prefix across functions, constants, classes, handles and the text
  domain (see `AGENTS.md`).
- Add or update tests for behavioural changes.
- Update documentation (`README.md`, `docs/`) when behaviour changes.
- Keep pull requests focused; one logical change per PR.

## Commit messages

Use short, imperative summaries (`Add Stripe integration scaffold`). Reference
issues where relevant (`Closes #12`).

## Reporting security issues

Do **not** open a public issue for security problems. See `SECURITY.md`.
