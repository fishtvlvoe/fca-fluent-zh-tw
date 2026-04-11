## ADDED Requirements

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

### Requirement: Batch fix via sed

The fix process SHALL use the following sed command applied to each affected `.po` file:

```
sed -i '' 's/電子電子電子郵件/電子郵件/g; s/電子電子郵件/電子郵件/g'
```

The triple pattern SHALL be substituted before the double pattern to avoid partial matches.

#### Scenario: Fix applied and verified

- **WHEN** the sed command is applied to a `.po` file
- **THEN** `grep -c "電子電子" {file}` SHALL return `0`

### Requirement: Post-fix compilation

After any batch fix, every modified `.po` file SHALL be recompiled to `.mo` using `msgfmt`. A compilation failure SHALL block the commit.

#### Scenario: Successful recompile after fix

- **WHEN** a `.po` file passes the `電子電子` zero-count check
- **THEN** `msgfmt {file}.po -o {file}.mo` SHALL exit with code `0`
