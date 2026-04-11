# {YYYY-MM} Monthly Translation Update

## Overview

fca-fluent-zh-tw translation package monthly update for **{Month} {Year}**.

## Upstream Changes

- **Updated domains:** {N} plugins
- **New strings to translate:** {X} strings
- **Deprecated strings:** {Y} strings

## Task List

- [ ] 1. Run `bash scripts/check-upstream.sh` to detect upstream versions
- [ ] 2. Download new .po files from each updated plugin repository
- [ ] 3. Run `bash scripts/diff-domains.sh <domain> <new.po> --output pending-<domain>.txt` for each domain
- [ ] 4. Review and translate all new strings in pending-*.txt files
- [ ] 5. Update language files: copy translated entries from new .po to existing languages/{domain}-zh_TW.po
- [ ] 6. Compile MO files: `msgfmt languages/<domain>-zh_TW.po -o languages/<domain>-zh_TW.mo`
- [ ] 7. Verify coverage: `bash scripts/check-coverage.sh`
- [ ] 8. Update version tracker: `bash scripts/monthly-release.sh --update-tracker`
- [ ] 9. Create GitHub release with changelog
- [ ] 10. Announce update in CHANGELOG.md

## Notes

- Domain-to-repo mapping is maintained in `scripts/check-upstream.sh`
- All translations must be reviewed before release
- Version tracker file: `scripts/version-tracker.json`
- Test with existing FluentCart/FCA installations before release
