# Security Policy

## Supported versions

The latest release on the `main` branch receives security fixes.

## Reporting a vulnerability

Please **do not** report security vulnerabilities through public GitHub issues.

Instead, use GitHub's private vulnerability reporting:

1. Go to the **Security** tab of the repository.
2. Click **Report a vulnerability**.
3. Provide a description, reproduction steps, and affected versions.

We will acknowledge your report as quickly as possible and keep you informed of
progress towards a fix.

## Handling secrets

- Never commit API keys, service-account JSON or other credentials.
- Store secrets in `wp-config.php` constants or environment variables.
- Rotate any secret that may have been exposed.
