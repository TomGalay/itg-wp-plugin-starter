# Architecture

```
itg-plugin-setup/
├── itg-plugin-setup.php          # Bootstrap: header, constants, requires, hooks
├── uninstall.php                 # Cleanup on delete
│
├── includes/                     # Core lifecycle
│   ├── class-itg-plugin-setup.php            # Main coordinator (singleton)
│   ├── class-itg-plugin-setup-activator.php  # Activation
│   ├── class-itg-plugin-setup-deactivator.php# Deactivation
│   ├── class-itg-plugin-setup-i18n.php       # Text domain
│   ├── class-itg-plugin-setup-cron.php       # Scheduled tasks
│   └── itg-plugin-setup-functions.php        # Helper functions
│
├── admin/                        # Admin screen + views
├── public/                       # Front-end controller
│
├── integrations/                 # Pluggable third-party services
│   ├── interface-itg-plugin-setup-integration.php   # The contract
│   ├── class-itg-plugin-setup-integration-abstract.php
│   ├── class-itg-plugin-setup-integration-manager.php
│   ├── firebase/
│   └── email/
│
├── rest/                         # REST controllers (itg-plugin-setup/v1)
├── cli/                          # WP-CLI commands
│
├── assets/src/
│   ├── js/admin/                 # Admin entry
│   ├── js/public/                # Front-end entry
│   ├── js/shared/                # Shared JS modules
│   └── scss/                     # Source styles
│
├── templates/                    # Overridable templates
├── languages/                    # Translations
├── build/                        # Compiled assets (generated)
├── tests/                        # PHPUnit + Jest
└── docs/                         # This documentation
```

## Boot flow

1. `itg-plugin-setup.php` defines constants and requires the lifecycle classes.
2. On `plugins_loaded`, `ITG_Plugin_Setup::init()` builds the integration manager
   and registers the sub-controllers (i18n, admin, public, cron).
3. On `init` (priority 5) the manager boots every **configured** integration.
4. On `rest_api_init` the REST controllers register their routes.
5. On activation/deactivation the activator/deactivator manage options and the
   cron event.

## Conventions

- One prefix everywhere: `itg_plugin_setup` (functions/vars),
  `ITG_PLUGIN_SETUP_` (constants), `ITG_Plugin_Setup` (classes).
- Every PHP file starts with an `ABSPATH` guard.
- Output is escaped as late as possible; input is sanitized on entry.
- REST routes always declare a `permission_callback`.
- User-facing strings use the `itg-plugin-setup` text domain.
- Class filename matches the class: `class-itg-plugin-setup-example.php`.
