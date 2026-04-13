## ADDED Requirements

### Requirement: npm package.json initialization

The system SHALL provide an npm package.json with husky and git-hooks dependencies, allowing developers to install pre-commit hooks via npm.

#### Scenario: First-time setup

- **WHEN** developer clones the repository and runs `npm install`
- **THEN** npm installs husky and configures git hooks in .git/hooks/

#### Scenario: Hook persistence

- **WHEN** npm install completes
- **THEN** developer can verify hook exists via `ls .git/hooks/pre-commit`

### Requirement: Pre-commit hook enforcement

The system SHALL enforce version number increment in fca-fluent-zh-tw.php whenever code changes are committed, preventing version sync bugs.

#### Scenario: Version already incremented

- **WHEN** developer modifies .po files and updates Version in fca-fluent-zh-tw.php
- **THEN** `git commit` succeeds and code is committed

#### Scenario: Version forgotten

- **WHEN** developer modifies .po files but forgets to increment Version
- **THEN** `git commit` fails with error message explaining the issue and how to fix it

#### Scenario: Commit message validation

- **WHEN** developer commits with message not matching conventional commits format
- **THEN** `git commit` fails with error showing allowed formats (feat/fix/docs/etc)

### Requirement: Manual version check command

The system SHALL provide a bash script to manually verify version sync status without committing.

#### Scenario: Version synchronized

- **WHEN** developer runs `bash scripts/version-check --check`
- **THEN** system outputs "✅ 版本同步" and exits with code 0

#### Scenario: Version mismatch detected

- **WHEN** developer runs `bash scripts/version-check --check` after code changes without version increment
- **THEN** system outputs warning showing commit count since last release

### Requirement: Version number increment enforcement on GitHub Actions

The system SHALL verify version number has incremented before creating a Release on GitHub, as final safeguard (Layer 2).

#### Scenario: Version incremented, ready to release

- **WHEN** developer pushes to main with new version number in fca-fluent-zh-tw.php
- **THEN** GitHub Actions detects version change and automatically creates Release tag and GitHub Release

#### Scenario: Version not incremented

- **WHEN** developer pushes to main without changing version number
- **THEN** GitHub Actions skips release creation and logs "版本未變更，跳過發佈"
