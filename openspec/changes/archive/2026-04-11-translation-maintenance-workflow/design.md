## Context

fca-fluent-zh-tw 翻譯包隨 BGo/Paygo 發給多個客戶站台。原始外掛（FluentCart、FluentCRM 等）每月約更新一次，更新後會新增字串，若翻譯包未跟進，客戶會看到英文。目前無自動化工具偵測上游更新，維護全靠手動。

## Goals / Non-Goals

**Goals:**
- 每月 20 日一鍵執行，自動偵測哪些外掛有更新
- 自動產出待翻清單，讓翻譯作業聚焦在「新增字串」而非全量審閱
- 標準化每月發版流程，減少遺漏步驟的風險

**Non-Goals:**
- 不自動翻譯（機器翻譯草稿）
- 不自動發 GitHub Release
- 不監控客戶站台版本

## Decisions

### 用 GitHub Releases API 追蹤上游版本

**決策**：`check-upstream.sh` 透過 `https://api.github.com/repos/{owner}/{repo}/releases/latest` 取得最新版本號。

**理由**：FCA/Fluent 系列外掛都在 GitHub 上發 Release，tag 就是版本號，不需要解析 changelog。無需 token 即可查詢（每小時 60 次免費），以 37 個 domain 計算遠低於上限。

**替代方案**：WordPress.org API。但部分外掛是商業外掛，不在 wp.org 上架，所以 GitHub 是唯一通用的來源。

**domain → repo 對應表**：維護在 `check-upstream.sh` 內部的 associative array，不另外建 JSON config，避免多個 SSOT。

### version-tracker.json 記錄本地已知版本

**決策**：用 `scripts/version-tracker.json` 記錄各 domain 最後一次成功更新後的上游版本號。

**理由**：不能用 `.po` 檔本身的 `PO-Revision-Date` 來判斷，因為那是翻譯修改日期，不是上游版本。需要獨立記錄追蹤目標版本。

**更新時機**：翻譯完成、`msgfmt` 編譯成功後，由維護者手動執行 `monthly-release.sh --update-tracker` 更新，不自動更新（防止意外標記為已完成）。

### diff-domains.sh 使用 msggrep 比對 msgid

**決策**：用 `grep "^msgid " {new}.po` 提取所有 msgid，再用 `comm` 比對差異。

**理由**：不需要完整解析 PO 格式，只需 msgid 清單。`msggrep` 雖然更精確但依賴 gettext 套件版本，`grep + comm` 在 macOS bash 3.2 下完全相容。

### monthly-release.sh 作為唯一入口

**決策**：所有腳本透過 `monthly-release.sh` 串接，不要求維護者記住各腳本的呼叫順序。

**理由**：流程固定（check → diff → 提示翻譯 → quality → compile），用一個入口腳本讓步驟不可跳過。

## Risks / Trade-offs

- **[Risk] GitHub API 改版或限流** → 緩解：腳本遇到非 200 回應時跳過該 domain 並警告，不中斷整體流程
- **[Risk] domain 和 repo 對應表過時** → 緩解：對應表集中在 `check-upstream.sh` 一處，新增 domain 時同步更新（CLAUDE.md 的 checklist 已包含此步驟）
- **[Risk] 維護者忘記跑 20 日例行作業** → 緩解：CLAUDE.md 的 SOP 區塊每次 session 都會被讀取，作為被動提醒

## Migration Plan

1. 建立 `scripts/version-tracker.json`，初始值全部設 `"0.0.0"`（第一次跑會把所有外掛標示為有更新，需人工確認實際版本後補填）
2. 建立三個腳本（check-upstream、diff-domains、monthly-release）
3. 建立 `openspec/templates/monthly-update.md`
4. 在 `CLAUDE.md` 新增 `## 每月維護 SOP` 區塊
5. 首次執行 `monthly-release.sh` 確認各外掛實際版本，手動更新 `version-tracker.json`
