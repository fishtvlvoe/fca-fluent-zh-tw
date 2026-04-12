## ADDED Requirements

### Requirement: Upstream version tracking per domain

Each text domain in `$domains` SHALL have a corresponding entry in `scripts/version-tracker.json` recording the last-known upstream version. This version SHALL be updated only after a successful translation update cycle (translation complete, `msgfmt` compiled, committed).

#### Scenario: Domain added to $domains without tracker entry

- **WHEN** a new domain is added to `$domains` but has no entry in `version-tracker.json`
- **THEN** `check-upstream.sh` SHALL treat the missing entry as version `"0.0.0"` and flag the domain as needing an update check

#### Scenario: Tracker version matches upstream

- **WHEN** `version-tracker.json` records the same version as the GitHub latest release
- **THEN** `check-upstream.sh` SHALL consider that domain up to date and skip it
