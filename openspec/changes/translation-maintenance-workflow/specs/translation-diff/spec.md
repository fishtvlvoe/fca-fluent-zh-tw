## ADDED Requirements

### Requirement: New msgid detection

The script `diff-domains.sh` SHALL accept a domain name and a path to a new `.po` file as arguments. It SHALL compare the new `.po` file against the existing `languages/{domain}-zh_TW.po` and output all `msgid` entries that are present in the new file but absent in the existing file.

#### Scenario: New strings found

- **WHEN** the upstream `.po` file contains `msgid` values not present in the local `.po`
- **THEN** `diff-domains.sh` SHALL output each new `msgid` as:
  ```
  [NEW] {msgid value}
  ```

#### Scenario: No new strings

- **WHEN** all `msgid` values in the upstream `.po` are already present locally
- **THEN** the script SHALL print `No new strings for {domain}` and exit with code 0

### Requirement: Removed msgid detection

The script SHALL also detect `msgid` entries present in the local `.po` but absent in the upstream file (obsolete strings).

#### Scenario: Obsolete strings found

- **WHEN** the local `.po` contains `msgid` values no longer present in the upstream `.po`
- **THEN** the script SHALL output each obsolete `msgid` as:
  ```
  [OBSOLETE] {msgid value}
  ```

### Requirement: Summary count

After listing all changes, `diff-domains.sh` SHALL print a summary:
`{N} new, {M} obsolete strings`

#### Scenario: Mixed new and obsolete

- **WHEN** both new and obsolete strings exist
- **THEN** the script SHALL list all `[NEW]` entries first, then all `[OBSOLETE]` entries, then the summary line

### Requirement: Output to file

The script SHALL support an optional `--output {path}` flag. When provided, the full output SHALL be written to the specified file in addition to stdout.

#### Scenario: Output flag provided

- **WHEN** `diff-domains.sh` is called with `--output pending-translations.txt`
- **THEN** the same content printed to stdout SHALL also be written to `pending-translations.txt`
