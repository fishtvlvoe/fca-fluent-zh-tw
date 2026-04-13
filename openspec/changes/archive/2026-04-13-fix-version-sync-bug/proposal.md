## Why

本機翻譯已修復「電子電子電子郵件」重複問題（commit 287ede6），但版本號未遞增至 1.6.4，導致線上用戶看不到修正。GitHub Actions 三層防守機制已完整實現，但 Layer 1（pre-commit hook）因 husky 未安裝而失效，無法在開發階段提前攔截版本號遺漏。

## What Changes

- 版本號 1.6.3 → 1.6.4（語義版本 patch +0.0.1）
- Commit 並 push，觸發 GitHub Actions 自動發佈 Release
- 安裝 npm/husky 依賴，啟動 Layer 1 pre-commit hook，防止未來再次遺漏版本號

## Non-Goals

- 不修改現有翻譯內容（已在前次 commit 修正）
- 不改動 GitHub Actions / build.sh / updater.php 邏輯（三層防守機制架構已完整）
- 不重構版本控管流程（只是啟動既有的自動化防守）

## Capabilities

### New Capabilities

- `npm-husky-setup`: npm 環境初始化與 pre-commit hook 安裝，確保本機開發時版本號變更被自動攔截

### Modified Capabilities

(none)

## Impact

- 受影響代碼：fca-fluent-zh-tw.php（版本號）、package.json（新增）、.husky/pre-commit（安裝）
- 受影響用戶：所有安裝 1.6.3 版的 WordPress 用戶，將能看到 1.6.4 更新提示
- 受影響流程：未來翻譯修正必須附帶版本號遞增，否則 commit 被攔截
