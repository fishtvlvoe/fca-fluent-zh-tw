## 1. Version Tracker 初始化

- [x] 1.1 建立 `scripts/version-tracker.json` 作為「Version tracker file」，所有 domain 初始值設 `"0.0.0"`，實作「Upstream version tracking per domain」（version-tracker.json 記錄本地已知版本）
- [x] 1.2 手動填入各外掛目前實際的上游版本號（首次執行後對照 GitHub 補填）

## 2. check-upstream.sh

- [x] 2.1 建立 `scripts/check-upstream.sh`，內含 domain→GitHub repo 對應表，實作「GitHub API version query」採用 GitHub Releases API 追蹤上游版本（associative array 維護於腳本內，非外部 config）
- [x] 2.2 實作「GitHub API version query」邏輯，每個 domain 查詢 `https://api.github.com/repos/{owner}/{repo}/releases/latest`（用 GitHub Releases API 追蹤上游版本）
- [x] 2.3 實作版本比對：讀取 `version-tracker.json`，比對 API 回傳版本，輸出 `[UPDATE] {domain}: {old} → {new}`（New msgid detection 前置）
- [x] 2.4 實作 API 錯誤處理：非 200 回應輸出 `[WARN]` 並繼續（GitHub API rate limit or network error）
- [x] 2.5 實作 Summary output：無更新輸出 `No updates found` exit 0，有更新 exit 1
- [x] 2.6 `chmod +x scripts/check-upstream.sh`，實測對 fluent-cart 執行確認輸出格式正確

## 3. diff-domains.sh

- [x] 3.1 建立 `scripts/diff-domains.sh`，接受參數：domain 名稱、上游新 .po 路徑（New msgid detection）
- [x] 3.2 實作新增 msgid 偵測：`grep "^msgid "` + `comm` 比對新舊 .po（diff-domains.sh 使用 msggrep 比對 msgid，採用 grep+comm 確保 macOS bash 3.2 相容），輸出 `[NEW]` 清單
- [x] 3.3 實作刪除 msgid 偵測：輸出 `[OBSOLETE]` 清單（Removed msgid detection）
- [x] 3.4 實作 Summary count：輸出 `{N} new, {M} obsolete strings`
- [x] 3.5 實作 `--output {path}` 選項，同時寫入檔案（Output to file）
- [x] 3.6 `chmod +x scripts/diff-domains.sh`，實測對 fluent-cart 新舊版本執行確認輸出正確

## 4. monthly-release.sh

- [x] 4.1 建立 `scripts/monthly-release.sh` 作為唯一入口，整合 check-upstream.sh 和 diff-domains.sh，實作「Monthly cadence on the 20th」（monthly-release.sh 作為唯一入口，維護者不需記住各腳本呼叫順序）
- [x] 4.2 實作「Monthly cadence on the 20th」主流程：執行 check-upstream → 有更新則對每個 updated domain 執行 diff-domains → 提示翻譯
- [x] 4.3 實作無更新短路：輸出 `本月無更新，無需發版` 並 exit 0
- [x] 4.4 實作 `--urgent` flag，繞過日期限制立即執行並加 `[URGENT RELEASE]` 前綴（Emergency release support）
- [x] 4.5 實作 `--update-tracker` flag，翻譯完成後更新 version-tracker.json 的版本號
- [x] 4.6 `chmod +x scripts/monthly-release.sh`，端對端測試：模擬有更新情境確認完整流程

## 5. Monthly Update 模板與 SOP

- [x] 5.1 建立 `openspec/templates/monthly-update.md`，內含月份佔位符和標準 10 步 tasks（Monthly change template）
- [x] 5.2 在 `CLAUDE.md` 新增 `## 每月維護 SOP` 區塊，說明：20 日觸發、執行 monthly-release.sh、翻譯、品質驗證、發版步驟（CLAUDE.md SOP entry）

## 6. 驗收

- [x] 6.1 執行 `bash -n scripts/check-upstream.sh scripts/diff-domains.sh scripts/monthly-release.sh` 語法檢查全部通過
- [x] 6.2 執行 `scripts/check-upstream.sh`，確認至少能正確輸出一個外掛的版本資訊
- [x] 6.3 確認 `openspec/templates/monthly-update.md` 存在且包含完整 10 步任務
- [x] 6.4 確認 `CLAUDE.md` 包含 `## 每月維護 SOP` 區塊
