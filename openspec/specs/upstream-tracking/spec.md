# upstream-tracking Specification

## Purpose

TBD - created by archiving change 'translation-maintenance-workflow'. Update Purpose after archive.

## Requirements

### Requirement: Version tracker file

The system SHALL maintain a `scripts/version-tracker.json` file that records the last-known version for each text domain in `$domains`. The file SHALL use the format:

```json
{
  "fluent-cart": "1.2.3",
  "fluent-crm": "4.5.6"
}
```

#### Scenario: First run with no tracker file

- **WHEN** `check-upstream.sh` is run and `version-tracker.json` does not exist
- **THEN** the script SHALL create the file with all domains set to `"0.0.0"` and treat all domains as needing an update check

#### Scenario: Tracker file exists

- **WHEN** `check-upstream.sh` is run and `version-tracker.json` exists
- **THEN** the script SHALL read the recorded versions and compare against GitHub API results


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

---
### Requirement: GitHub API version query

The script `check-upstream.sh` SHALL query the GitHub Releases API for each plugin repository to retrieve the latest release tag. The domain-to-repo mapping SHALL be maintained inside the script.

#### Scenario: Plugin has a newer GitHub release

- **WHEN** the GitHub API returns a tag newer than the version in `version-tracker.json`
- **THEN** the script SHALL output one line per updated plugin: `[UPDATE] {domain}: {old} → {new}`

#### Scenario: Plugin is up to date

- **WHEN** the GitHub API returns the same version as `version-tracker.json`
- **THEN** the script SHALL output nothing for that domain

#### Scenario: GitHub API rate limit or network error

- **WHEN** the GitHub API returns a non-200 status code
- **THEN** the script SHALL print a warning `[WARN] {domain}: API error, skipped` and continue processing other domains


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

---
### Requirement: Summary output

After checking all domains, `check-upstream.sh` SHALL print a summary line: `{N} plugin(s) have updates` or `No updates found` if N is 0.

#### Scenario: No updates found

- **WHEN** all domains are at their latest version
- **THEN** the script SHALL exit with code 0 and print `No updates found`

#### Scenario: Updates found

- **WHEN** one or more domains have a newer version available
- **THEN** the script SHALL exit with code 1 to signal the caller that action is needed

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