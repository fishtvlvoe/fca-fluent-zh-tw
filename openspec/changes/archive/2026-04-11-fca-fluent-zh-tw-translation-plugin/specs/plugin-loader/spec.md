## ADDED Requirements

### Requirement: Domain registration

The plugin SHALL maintain a static array `$domains` in `FCA_Fluent_ZhTW` class listing all supported text domains. Each domain SHALL correspond to an existing `.po`/`.mo` file pair in the `languages/` directory.

#### Scenario: Domain is loaded before original plugin

- **WHEN** WordPress fires the `init` hook
- **THEN** the plugin SHALL call `load_textdomain()` for every domain in `$domains` before the original plugin loads its own translation

#### Scenario: Missing translation file is skipped silently

- **WHEN** a domain's `.mo` file does not exist in `languages/`
- **THEN** the plugin SHALL skip that domain without triggering a PHP warning or fatal error

### Requirement: Priority loading via init hook

The plugin SHALL register its translation loader at `add_action('init', ...)` with a priority of `1` so it fires before default plugin loaders (priority 10).

#### Scenario: Translation overrides original

- **WHEN** the original plugin attempts to load its own `.mo` file via `load_textdomain()`
- **THEN** WordPress SHALL use the already-loaded translation from this plugin (first-loaded wins behavior)

### Requirement: Auto-updater integration

The plugin SHALL instantiate `FCA_Fluent_ZhTW_Updater` on load, passing the current plugin file path and version string, to enable GitHub-based auto-updates.

#### Scenario: Version string matches plugin header

- **WHEN** the updater is instantiated
- **THEN** the version string passed to `FCA_Fluent_ZhTW_Updater` SHALL match the `Version:` field in the plugin header comment exactly
