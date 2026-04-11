## ADDED Requirements

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

### Requirement: Format specifier preservation

All format specifiers (`%s`, `%d`, `%1$s`, `%2$s`, etc.) in `msgid` SHALL appear in the corresponding `msgstr` in the same quantity. Order MAY differ for numbered specifiers.

#### Scenario: Missing format specifier

- **WHEN** a `msgstr` omits a format specifier present in `msgid`
- **THEN** the translation SHALL be corrected to include all specifiers before the `.mo` is compiled

### Requirement: Technical term passthrough

The following names SHALL remain untranslated in all `msgstr` values: `FluentCRM`, `FluentCart`, `FluentForm`, `FluentSMTP`, `FluentBooking`, `FluentCommunity`, `ISO`, `SMTP`, `API`.

#### Scenario: Brand name left in English

- **WHEN** a `msgid` contains `FluentCart`
- **THEN** the corresponding `msgstr` SHALL also contain `FluentCart` unchanged
