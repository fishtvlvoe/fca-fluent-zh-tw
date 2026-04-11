# translation-bugfix Specification

## Purpose

TBD - created by archiving change 'fca-fluent-zh-tw-translation-plugin'. Update Purpose after archive.

## Requirements

### Requirement: Email duplication detection

The plugin's maintenance process SHALL detect any `msgstr` containing `電子電子` (duplicated 電子 prefix) as a translation bug. All such occurrences SHALL be corrected to use `電子郵件` exactly once.

#### Scenario: Triple duplication in msgstr

- **WHEN** a `msgstr` contains `電子電子電子郵件`
- **THEN** it SHALL be replaced with `電子郵件`

#### Scenario: Double duplication in msgstr

- **WHEN** a `msgstr` contains `電子電子郵件`
- **THEN** it SHALL be replaced with `電子郵件`

#### Scenario: Cross-line duplication

- **WHEN** a multi-line `msgstr` string ends a line with `電子電子郵` and continues on the next line with `件`
- **THEN** both lines SHALL be corrected so the combined string reads `電子郵件`


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
### Requirement: Batch fix via sed

The fix process SHALL use the following sed command applied to each affected `.po` file:

```
sed -i '' 's/電子電子電子郵件/電子郵件/g; s/電子電子郵件/電子郵件/g'
```

The triple pattern SHALL be substituted before the double pattern to avoid partial matches.

#### Scenario: Fix applied and verified

- **WHEN** the sed command is applied to a `.po` file
- **THEN** `grep -c "電子電子" {file}` SHALL return `0`


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
### Requirement: Post-fix compilation

After any batch fix, every modified `.po` file SHALL be recompiled to `.mo` using `msgfmt`. A compilation failure SHALL block the commit.

#### Scenario: Successful recompile after fix

- **WHEN** a `.po` file passes the `電子電子` zero-count check
- **THEN** `msgfmt {file}.po -o {file}.mo` SHALL exit with code `0`

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