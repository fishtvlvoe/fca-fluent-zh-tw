## Why

fca-fluent-zh-tw 翻譯包隨著 BGo/Paygo 發給多個客戶站台，這些客戶依賴 FluentCart 及 FCA 系列外掛。原始外掛每月更新時會新增字串，若翻譯包沒有跟進，客戶會看到英文字串。目前沒有自動化工具偵測上游更新或產出待翻清單，維護全靠手動，容易遺漏。

## What Changes

- 新增 `scripts/check-upstream.sh`：透過 GitHub API 查詢各外掛最新版本，對比本地記錄，輸出有更新的外掛清單
- 新增 `scripts/diff-domains.sh`：比對新舊 `.po` 檔，輸出新增/刪除的 `msgid` 待翻清單
- 新增 `scripts/monthly-release.sh`：整合上述兩個腳本，一鍵執行完整更新前置作業
- 新增 `scripts/version-tracker.json`：記錄各外掛目前追蹤的版本號
- 新增 `openspec/templates/monthly-update.md`：標準化每月 change 的 tasks 模板
- 定義每月 20 日例行維護 SOP（寫入 CLAUDE.md）

## Non-Goals

- 不自動翻譯（機器翻譯草稿）：品質風險高，維護成本反而更大
- 不自動發 GitHub Release：發版前需人工確認翻譯品質
- 不監控客戶站台的外掛版本：僅追蹤上游 GitHub，不介入客戶環境

## Capabilities

### New Capabilities

- `upstream-tracking`: 偵測上游外掛 GitHub 最新版本，對比本地追蹤版本，輸出需要更新的外掛清單
- `translation-diff`: 比對新舊 `.po` 檔，產出新增/刪除 `msgid` 的待翻清單，格式化輸出方便翻譯作業
- `monthly-update-workflow`: 標準化每月維護週期——每月 20 日執行，整合偵測、diff、品質驗證、編譯、發版各步驟

### Modified Capabilities

- `translation-coverage`: 新增版本追蹤概念（各 domain 對應的上游版本號）

## Impact

- 新增檔案：`scripts/check-upstream.sh`、`scripts/diff-domains.sh`、`scripts/monthly-release.sh`、`scripts/version-tracker.json`、`openspec/templates/monthly-update.md`
- 修改檔案：`CLAUDE.md`（新增每月 20 日 SOP）
- 相關 specs：`translation-coverage`（新增版本追蹤需求）
