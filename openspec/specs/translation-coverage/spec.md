# translation-coverage Specification

## Purpose

TBD - created by archiving change 'fca-fluent-zh-tw-translation-plugin'. Update Purpose after archive.

## Requirements

### Requirement: Auto-detection of new plugins

The plugin SHALL support any plugin whose text domain begins with `fca-`, `fce-`, `fchub-`, `fluent-`, `fluentform`, or `fluentcampaign`. When a new plugin matching these prefixes is found without a corresponding `.po` file, a new translation file SHALL be created following the standard flow.

#### Scenario: New fca- plugin detected

- **WHEN** a WordPress plugin with text domain starting with `fca-` is active
- **THEN** a `languages/{text-domain}-zh_TW.po` file SHALL exist in this plugin

#### Scenario: New fluent- plugin detected

- **WHEN** a WordPress plugin with text domain starting with `fluent-` is active
- **THEN** a `languages/{text-domain}-zh_TW.po` file SHALL exist in this plugin


<!-- @trace
source: fca-fluent-zh-tw-translation-plugin
updated: 2026-04-11
code:
  - languages/fluent-crm-zh_TW.po
  - languages/wpkj-alipay-gateway-for-fluentcart-zh_TW.po
  - scripts/check-quality.sh
  - .github/prompts/spectra-debug.prompt.md
  - .github/skills/spectra-apply/SKILL.md
  - .github/prompts/spectra-propose.prompt.md
  - languages/fluent-cart-zh_TW.po
  - languages/fluent-booking-pro-zh_TW.po
  - languages/fluentform-zh_TW.po
  - .github/skills/spectra-archive/SKILL.md
  - .cursorrules
  - .github/prompts/spectra-ingest.prompt.md
  - .github/skills/spectra-discuss/SKILL.md
  - .github/skills/spectra-audit/SKILL.md
  - .spectra.yaml
  - AGENTS.md
  - languages/fca-pages-zh_TW.po
  - .github/skills/spectra-propose/SKILL.md
  - languages/fluentform-zh_TW.mo
  - scripts/check-coverage.sh
  - .DS_Store
  - languages/fluent-security-zh_TW.mo
  - languages/fluent-booking-zh_TW.mo
  - .github/prompts/spectra-audit.prompt.md
  - languages/fluent-crm-zh_TW.mo
  - languages/fluentcampaign-pro-zh_TW.mo
  - .github/skills/spectra-ask/SKILL.md
  - .github/skills/spectra-ingest/SKILL.md
  - languages/fluent-player-zh_TW.po
  - GEMINI.md
  - languages/fluent-community-zh_TW.mo
  - languages/fluent-security-zh_TW.po
  - languages/fluentformpro-zh_TW.po
  - languages/wpkj-alipay-gateway-for-fluentcart-zh_TW.mo
  - languages/fluentcampaign-pro-zh_TW.po
  - .github/prompts/spectra-ask.prompt.md
  - .github/prompts/spectra-discuss.prompt.md
  - CLAUDE.md
  - .github/skills/spectra-debug/SKILL.md
  - .github/prompts/spectra-archive.prompt.md
  - fca-fluent-zh-tw.php
  - languages/fluent-booking-pro-zh_TW.mo
  - languages/fca-pages-zh_TW.mo
  - languages/fluent-booking-zh_TW.po
  - languages/fluent-player-zh_TW.mo
  - .github/prompts/spectra-apply.prompt.md
  - languages/fluentformpro-zh_TW.mo
  - scripts/fix-email-duplication.sh
  - languages/fluent-cart-zh_TW.mo
  - languages/fluent-community-zh_TW.po
-->

---
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


<!-- @trace
source: fca-fluent-zh-tw-translation-plugin
updated: 2026-04-11
code:
  - languages/fluent-crm-zh_TW.po
  - languages/wpkj-alipay-gateway-for-fluentcart-zh_TW.po
  - scripts/check-quality.sh
  - .github/prompts/spectra-debug.prompt.md
  - .github/skills/spectra-apply/SKILL.md
  - .github/prompts/spectra-propose.prompt.md
  - languages/fluent-cart-zh_TW.po
  - languages/fluent-booking-pro-zh_TW.po
  - languages/fluentform-zh_TW.po
  - .github/skills/spectra-archive/SKILL.md
  - .cursorrules
  - .github/prompts/spectra-ingest.prompt.md
  - .github/skills/spectra-discuss/SKILL.md
  - .github/skills/spectra-audit/SKILL.md
  - .spectra.yaml
  - AGENTS.md
  - languages/fca-pages-zh_TW.po
  - .github/skills/spectra-propose/SKILL.md
  - languages/fluentform-zh_TW.mo
  - scripts/check-coverage.sh
  - .DS_Store
  - languages/fluent-security-zh_TW.mo
  - languages/fluent-booking-zh_TW.mo
  - .github/prompts/spectra-audit.prompt.md
  - languages/fluent-crm-zh_TW.mo
  - languages/fluentcampaign-pro-zh_TW.mo
  - .github/skills/spectra-ask/SKILL.md
  - .github/skills/spectra-ingest/SKILL.md
  - languages/fluent-player-zh_TW.po
  - GEMINI.md
  - languages/fluent-community-zh_TW.mo
  - languages/fluent-security-zh_TW.po
  - languages/fluentformpro-zh_TW.po
  - languages/wpkj-alipay-gateway-for-fluentcart-zh_TW.mo
  - languages/fluentcampaign-pro-zh_TW.po
  - .github/prompts/spectra-ask.prompt.md
  - .github/prompts/spectra-discuss.prompt.md
  - CLAUDE.md
  - .github/skills/spectra-debug/SKILL.md
  - .github/prompts/spectra-archive.prompt.md
  - fca-fluent-zh-tw.php
  - languages/fluent-booking-pro-zh_TW.mo
  - languages/fca-pages-zh_TW.mo
  - languages/fluent-booking-zh_TW.po
  - languages/fluent-player-zh_TW.mo
  - .github/prompts/spectra-apply.prompt.md
  - languages/fluentformpro-zh_TW.mo
  - scripts/fix-email-duplication.sh
  - languages/fluent-cart-zh_TW.mo
  - languages/fluent-community-zh_TW.po
-->

---
### Requirement: .mo compilation requirement

Every `.po` file in `languages/` SHALL have a corresponding compiled `.mo` file. The `.mo` file SHA SHALL be updated whenever the `.po` file changes.

#### Scenario: .po updated without recompiling .mo

- **WHEN** a `.po` file is modified
- **THEN** `msgfmt {file}.po -o {file}.mo` SHALL be run before committing

<!-- @trace
source: fca-fluent-zh-tw-translation-plugin
updated: 2026-04-11
code:
  - languages/fluent-crm-zh_TW.po
  - languages/wpkj-alipay-gateway-for-fluentcart-zh_TW.po
  - scripts/check-quality.sh
  - .github/prompts/spectra-debug.prompt.md
  - .github/skills/spectra-apply/SKILL.md
  - .github/prompts/spectra-propose.prompt.md
  - languages/fluent-cart-zh_TW.po
  - languages/fluent-booking-pro-zh_TW.po
  - languages/fluentform-zh_TW.po
  - .github/skills/spectra-archive/SKILL.md
  - .cursorrules
  - .github/prompts/spectra-ingest.prompt.md
  - .github/skills/spectra-discuss/SKILL.md
  - .github/skills/spectra-audit/SKILL.md
  - .spectra.yaml
  - AGENTS.md
  - languages/fca-pages-zh_TW.po
  - .github/skills/spectra-propose/SKILL.md
  - languages/fluentform-zh_TW.mo
  - scripts/check-coverage.sh
  - .DS_Store
  - languages/fluent-security-zh_TW.mo
  - languages/fluent-booking-zh_TW.mo
  - .github/prompts/spectra-audit.prompt.md
  - languages/fluent-crm-zh_TW.mo
  - languages/fluentcampaign-pro-zh_TW.mo
  - .github/skills/spectra-ask/SKILL.md
  - .github/skills/spectra-ingest/SKILL.md
  - languages/fluent-player-zh_TW.po
  - GEMINI.md
  - languages/fluent-community-zh_TW.mo
  - languages/fluent-security-zh_TW.po
  - languages/fluentformpro-zh_TW.po
  - languages/wpkj-alipay-gateway-for-fluentcart-zh_TW.mo
  - languages/fluentcampaign-pro-zh_TW.po
  - .github/prompts/spectra-ask.prompt.md
  - .github/prompts/spectra-discuss.prompt.md
  - CLAUDE.md
  - .github/skills/spectra-debug/SKILL.md
  - .github/prompts/spectra-archive.prompt.md
  - fca-fluent-zh-tw.php
  - languages/fluent-booking-pro-zh_TW.mo
  - languages/fca-pages-zh_TW.mo
  - languages/fluent-booking-zh_TW.po
  - languages/fluent-player-zh_TW.mo
  - .github/prompts/spectra-apply.prompt.md
  - languages/fluentformpro-zh_TW.mo
  - scripts/fix-email-duplication.sh
  - languages/fluent-cart-zh_TW.mo
  - languages/fluent-community-zh_TW.po
-->

---
### Requirement: Upstream version tracking per domain

Each text domain in `$domains` SHALL have a corresponding entry in `scripts/version-tracker.json` recording the last-known upstream version. This version SHALL be updated only after a successful translation update cycle (translation complete, `msgfmt` compiled, committed).

#### Scenario: Domain added to $domains without tracker entry

- **WHEN** a new domain is added to `$domains` but has no entry in `version-tracker.json`
- **THEN** `check-upstream.sh` SHALL treat the missing entry as version `"0.0.0"` and flag the domain as needing an update check

#### Scenario: Tracker version matches upstream

- **WHEN** `version-tracker.json` records the same version as the GitHub latest release
- **THEN** `check-upstream.sh` SHALL consider that domain up to date and skip it

<!-- @trace
source: translation-maintenance-workflow
updated: 2026-04-11
code:
  - scripts/diff-domains.sh
  - scripts/version-tracker.json
  - scripts/check-upstream.sh
  - scripts/monthly-release.sh
  - CLAUDE.md
-->