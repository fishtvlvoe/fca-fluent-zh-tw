## Context

fca-fluent-zh-tw 是一個 WordPress 翻譯覆蓋外掛，透過「先載入優先」機制，在原始外掛自己的翻譯被載入前，先把繁體中文翻譯注入 WordPress 的 textdomain 快取。目前外掛涵蓋 38 個 text domain（FCA 系列、FCHub 系列、Fluent 系列），無任何正式規格文件。

已發現的系統性翻譯品質問題：
- 電子郵件重複疊加（電子電子郵件）：15 個檔案，共 1217 處（已於 2026-04-11 修復）
- msgid 重複條目：msguniq 驗證後確認無實際重複

## Goals / Non-Goals

**Goals:**

- 為外掛的核心機制建立可追溯的規格與設計文件
- 定義翻譯品質標準，作為未來 PR review 的基準
- 定義新外掛翻譯擴充流程，降低人工錯誤率
- 定義翻譯 bug 批次修復流程，確保可重現

**Non-Goals:**

- 不自動抓取新外掛字串（手動維護為主，自動化可在後續版本考慮）
- 不涵蓋前端 i18n（JS 翻譯由各外掛自身處理）
- 不建立翻譯記憶庫或機器翻譯整合

## Decisions

### 使用 init hook priority=1 進行優先載入

**決策**：在 `add_action('init', [$this, 'load_translations'], 1)` 使用 priority=1。

**理由**：WordPress 的 `load_textdomain()` 採用「先載入優先」策略。大多數外掛在 `plugins_loaded`（priority 10）或 `init`（priority 10）載入翻譯，使用 priority=1 確保本外掛的翻譯永遠先到。

**替代方案**：使用 `plugins_loaded` hook。但部分外掛在 `init` 前的時機載入翻譯，priority=1 的 `init` 更可靠。

### 靜態 $domains 陣列手動維護

**決策**：`$domains` 陣列在 `fca-fluent-zh-tw.php` 中手動維護，不自動掃描。

**理由**：自動掃描需要知道哪些外掛已安裝，這在多站台環境下不一致。手動維護讓版本控制更清晰，每次新增 domain 都有對應 commit 紀錄。

**替代方案**：動態掃描 `languages/` 目錄的 `.mo` 檔。風險是萬一有孤立的 `.mo` 檔（無對應外掛）也會被載入，產生不必要的開銷。

### sed 批次替換作為標準修復工具

**決策**：翻譯 bug 批次修復使用 `sed -i '' 's/電子電子電子郵件/電子郵件/g; s/電子電子郵件/電子郵件/g'`，三重優先於雙重處理。

**理由**：直接、可重現、零依賴。若先替換雙重再替換三重，三重的第一次替換會產生雙重殘留，造成漏修。

**替代方案**：Python 腳本處理跨行情境。用於處理 `fluent-security` 類型的跨行字串斷行邊緣案例，但增加依賴複雜度，在大多數情況下不必要。

## Risks / Trade-offs

- **[Risk] 跨行斷行殘留** → 緩解：修復後必須執行 `grep -c "電子電子"` 驗證，回傳非 0 則人工檢查跨行案例
- **[Risk] 新外掛更新後翻譯被覆蓋** → 緩解：priority=1 的 init hook 設計本身就是為此而設，原始外掛更新不影響本外掛翻譯
- **[Risk] $domains 忘記更新** → 緩解：CLAUDE.md 新增翻譯規則已定義觸發條件與流程，作為強制提醒
- **[Risk] .po 和 .mo 不同步** → 緩解：spec 強制要求每次修改 .po 後必須重新編譯 .mo，且 git 兩者一起 commit

## Migration Plan

1. 現有翻譯品質問題已於 2026-04-11 批次修復（commit 287ede6）
2. 本 SDD 文件為新增文件，不影響現有功能
3. 無需 rollback 策略（純文件變更 + 已完成的修復）
