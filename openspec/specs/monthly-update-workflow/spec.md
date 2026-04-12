# monthly-update-workflow Specification

## Purpose

TBD - created by archiving change 'translation-maintenance-workflow'. Update Purpose after archive.

## Requirements

### Requirement: Monthly cadence on the 20th

The maintenance workflow SHALL be executed on the 20th of each month. The `monthly-release.sh` script SHALL be the single entry point for the full update cycle.

#### Scenario: Run on the 20th with updates available

- **WHEN** `monthly-release.sh` is run and `check-upstream.sh` finds updates
- **THEN** the script SHALL automatically invoke `diff-domains.sh` for each updated domain and output pending translation lists

#### Scenario: Run on the 20th with no updates

- **WHEN** `monthly-release.sh` is run and no upstream updates are found
- **THEN** the script SHALL print `本月無更新，無需發版` and exit with code 0


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
### Requirement: Emergency release support

For critical upstream updates (affecting checkout, invoicing, or payment flows), the maintainer MAY run `monthly-release.sh --urgent` to bypass the monthly cadence and trigger an immediate update cycle.

#### Scenario: Urgent flag used

- **WHEN** `monthly-release.sh --urgent` is invoked
- **THEN** the script SHALL run the full update cycle regardless of the current date and prefix the output with `[URGENT RELEASE]`


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
### Requirement: Monthly change template

A template file at `openspec/templates/monthly-update.md` SHALL define the standard tasks for each monthly update cycle. The template SHALL include the following tasks in order:

1. Run `check-upstream.sh` and record which plugins have updates
2. For each updated plugin, run `diff-domains.sh --output pending-{domain}.txt`
3. Translate all `[NEW]` strings in each pending file
4. Remove all `[OBSOLETE]` msgid entries from local `.po` files
5. Run `check-quality.sh` and fix any violations
6. Compile all modified `.po` files with `msgfmt`
7. Update version number in `fca-fluent-zh-tw.php` (patch +0.0.1)
8. Update `version-tracker.json` with new upstream versions
9. Commit and push to GitHub
10. Create GitHub Release with changelog listing updated plugins and string counts

#### Scenario: Template used to open monthly change

- **WHEN** the maintainer opens a new monthly change
- **THEN** they SHALL copy `openspec/templates/monthly-update.md` as the `tasks.md` for the new change, substituting the month/year in the title


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
### Requirement: CLAUDE.md SOP entry

The `CLAUDE.md` file SHALL contain a section titled `## 每月維護 SOP` documenting the monthly-20th workflow trigger, the scripts to run, and the release checklist.

#### Scenario: New session starts near the 20th

- **WHEN** a new Claude Code session is started
- **THEN** the SOP section in `CLAUDE.md` SHALL be visible as part of project instructions, serving as a reminder of the maintenance cadence

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