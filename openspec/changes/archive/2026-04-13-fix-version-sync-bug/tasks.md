## 1. npm package.json initialization

- [x] 1.1 Create package.json with husky and git-hooks dependencies for npm package.json initialization
- [x] 1.2 Run `npm install` to install dependencies and activate pre-commit hook via npm package.json initialization

## 2. Pre-commit hook enforcement setup

- [x] 2.1 Verify .husky/pre-commit hook enforces version increment requirement for Pre-commit hook enforcement
- [x] 2.2 Verify commit message validation in hook checks conventional commits format for Pre-commit hook enforcement

## 3. Manual version check command validation

- [x] 3.1 Test `bash scripts/version-check --check` command for Manual version check command functionality
- [x] 3.2 Confirm version mismatch detection in Manual version check command works correctly

## 4. Version number increment for release

- [x] 4.1 Update fca-fluent-zh-tw.php: Version 1.6.3 → 1.6.4
- [x] 4.2 Verify version check script recognizes new version via `bash scripts/version-check --check`

## 5. Commit and push (triggers GitHub Actions auto-release)

- [x] 5.1 Stage files: `git add fca-fluent-zh-tw.php package.json package-lock.json`
- [x] 5.2 Commit with conventional message: `git commit -m "fix(version): bump to 1.6.4 and activate npm/husky setup"`
- [x] 5.3 Push to main: `git push origin main`
- [x] 5.4 Monitor GitHub Actions for Version number increment enforcement on GitHub Actions to complete

## 6. Verify GitHub Actions release automation

- [x] 6.1 Confirm GitHub Release v1.6.4 created by Version number increment enforcement on GitHub Actions
- [x] 6.2 Verify zip file fca-fluent-zh-tw-1.6.4.zip is present in release asset
- [x] 6.3 Check WordPress plugin update check sees v1.6.4 as available (12-hour cache may delay)

## 7. Validation: Test complete Layer 1-2 protection

- [x] 7.1 Modify a .po file without changing version, attempt `git commit` → Pre-commit hook enforcement should fail with version error
- [x] 7.2 Update version number, retry `git commit` → Pre-commit hook enforcement should succeed
- [x] 7.3 Verify Version number increment enforcement on GitHub Actions detects version change on next push
