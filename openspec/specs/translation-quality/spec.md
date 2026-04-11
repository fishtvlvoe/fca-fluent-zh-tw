# translation-quality Specification

## Purpose

TBD - created by archiving change 'fca-fluent-zh-tw-translation-plugin'. Update Purpose after archive.

## Requirements

### Requirement: Consistent terminology

All `.po` files SHALL use the following standardized terms. Deviations SHALL be treated as bugs:

| Concept | Correct term | Forbidden alternatives |
|---------|-------------|----------------------|
| Currency | 幣別 | 貨幣 |
| Checkout | 結帳 | 付款 |
| Save settings | 儲存設定 | 保存、存儲 |
| Email | 電子郵件 | 電子電子郵件、電郵 |

#### Scenario: Forbidden term detected in msgstr

- **WHEN** a `.po` file contains a forbidden term in any `msgstr` line
- **THEN** it SHALL be replaced with the correct term before the file is committed


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
### Requirement: Format specifier preservation

All format specifiers (`%s`, `%d`, `%1$s`, `%2$s`, etc.) in `msgid` SHALL appear in the corresponding `msgstr` in the same quantity. Order MAY differ for numbered specifiers.

#### Scenario: Missing format specifier

- **WHEN** a `msgstr` omits a format specifier present in `msgid`
- **THEN** the translation SHALL be corrected to include all specifiers before the `.mo` is compiled


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
### Requirement: Technical term passthrough

The following names SHALL remain untranslated in all `msgstr` values: `FluentCRM`, `FluentCart`, `FluentForm`, `FluentSMTP`, `FluentBooking`, `FluentCommunity`, `ISO`, `SMTP`, `API`.

#### Scenario: Brand name left in English

- **WHEN** a `msgid` contains `FluentCart`
- **THEN** the corresponding `msgstr` SHALL also contain `FluentCart` unchanged

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