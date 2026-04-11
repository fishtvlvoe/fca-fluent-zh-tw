## ADDED Requirements

### Requirement: Auto-detection of new plugins

The plugin SHALL support any plugin whose text domain begins with `fca-`, `fce-`, `fchub-`, `fluent-`, `fluentform`, or `fluentcampaign`. When a new plugin matching these prefixes is found without a corresponding `.po` file, a new translation file SHALL be created following the standard flow.

#### Scenario: New fca- plugin detected

- **WHEN** a WordPress plugin with text domain starting with `fca-` is active
- **THEN** a `languages/{text-domain}-zh_TW.po` file SHALL exist in this plugin

#### Scenario: New fluent- plugin detected

- **WHEN** a WordPress plugin with text domain starting with `fluent-` is active
- **THEN** a `languages/{text-domain}-zh_TW.po` file SHALL exist in this plugin

### Requirement: Translation file creation flow

Adding support for a new plugin SHALL follow this sequence:
1. Scan the target plugin's PHP files for `__()`, `_e()`, `_n()`, `_x()` calls and JS files for `wp.i18n.__()` calls
2. Create `languages/{text-domain}-zh_TW.po` with all extracted strings
3. Compile `languages/{text-domain}-zh_TW.mo` via `msgfmt`
4. Add the text domain string to `$domains` array in `fca-fluent-zh-tw.php`
5. Increment the plugin version by patch (+0.0.1)

#### Scenario: New domain added to $domains

- **WHEN** a new text domain is appended to `$domains`
- **THEN** a corresponding `languages/{text-domain}-zh_TW.mo` file SHALL exist and be loadable

### Requirement: .mo compilation requirement

Every `.po` file in `languages/` SHALL have a corresponding compiled `.mo` file. The `.mo` file SHA SHALL be updated whenever the `.po` file changes.

#### Scenario: .po updated without recompiling .mo

- **WHEN** a `.po` file is modified
- **THEN** `msgfmt {file}.po -o {file}.mo` SHALL be run before committing
