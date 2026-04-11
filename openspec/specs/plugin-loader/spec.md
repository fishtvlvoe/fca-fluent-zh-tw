# plugin-loader Specification

## Purpose

TBD - created by archiving change 'fca-fluent-zh-tw-translation-plugin'. Update Purpose after archive.

## Requirements

### Requirement: Domain registration

The plugin SHALL maintain a static array `$domains` in `FCA_Fluent_ZhTW` class listing all supported text domains. Each domain SHALL correspond to an existing `.po`/`.mo` file pair in the `languages/` directory.

#### Scenario: Domain is loaded before original plugin

- **WHEN** WordPress fires the `init` hook
- **THEN** the plugin SHALL call `load_textdomain()` for every domain in `$domains` before the original plugin loads its own translation

#### Scenario: Missing translation file is skipped silently

- **WHEN** a domain's `.mo` file does not exist in `languages/`
- **THEN** the plugin SHALL skip that domain without triggering a PHP warning or fatal error


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
### Requirement: Priority loading via init hook

The plugin SHALL register its translation loader at `add_action('init', ...)` with a priority of `1` so it fires before default plugin loaders (priority 10).

#### Scenario: Translation overrides original

- **WHEN** the original plugin attempts to load its own `.mo` file via `load_textdomain()`
- **THEN** WordPress SHALL use the already-loaded translation from this plugin (first-loaded wins behavior)


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
### Requirement: Auto-updater integration

The plugin SHALL instantiate `FCA_Fluent_ZhTW_Updater` on load, passing the current plugin file path and version string, to enable GitHub-based auto-updates.

#### Scenario: Version string matches plugin header

- **WHEN** the updater is instantiated
- **THEN** the version string passed to `FCA_Fluent_ZhTW_Updater` SHALL match the `Version:` field in the plugin header comment exactly

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