## 1. 規格文件建立（已完成）

- [x] 1.1 建立 openspec/changes/fca-fluent-zh-tw-translation-plugin/proposal.md — 定義外掛整體涵蓋範圍與四個 capabilities
- [x] 1.2 建立 specs/plugin-loader/spec.md — 定義 Domain registration、Priority loading via init hook、Auto-updater integration 需求
- [x] 1.3 建立 specs/translation-quality/spec.md — 定義 Consistent terminology、Format specifier preservation、Technical term passthrough 需求
- [x] 1.4 建立 specs/translation-coverage/spec.md — 定義 Auto-detection of new plugins、Translation file creation flow、.mo compilation requirement 需求
- [x] 1.5 建立 specs/translation-bugfix/spec.md — 定義 Email duplication detection、Batch fix via sed、Post-fix compilation 需求
- [x] 1.6 建立 design.md — 記錄 init hook priority=1、靜態 $domains 陣列、sed 批次替換三項技術決策

## 2. 翻譯 Bug 修復（已完成）

- [x] 2.1 修復 fluent-cart-zh_TW.po — Email duplication detection（86 處電子郵件重複疊加）並重新編譯 .mo
- [x] 2.2 批次修復 14 個外掛 .po 檔 — Batch fix via sed（fca-events-basic、fca-events、fluent-booking-pro、fluent-booking、fluent-community-pro、fluent-community、fluent-crm、fluent-player-pro、fluent-player、fluent-security、fluent-smtp、fluentcampaign-pro、fluentform、fluentformpro）共 1131 處
- [x] 2.3 執行 Post-fix compilation — 14 個檔案全部 msgfmt 編譯成功，殘留驗證 = 0

## 3. 翻譯品質標準落地

- [ ] 3.1 在 CLAUDE.md 補充 Consistent terminology 術語表（幣別/結帳/儲存設定/電子郵件），作為未來 PR review 基準
- [ ] 3.2 建立 `scripts/check-quality.sh` — 自動掃描 languages/*.po 檢查禁用術語與 Format specifier preservation，輸出違規行號
- [ ] 3.3 測試 check-quality.sh 對 fluentform-zh_TW.po 執行，確認無誤報

## 4. 翻譯涵蓋範圍管理

- [x] 4.1 確認 $domains 陣列與 languages/ 目錄的 .po 檔一一對應（Auto-detection of new plugins 驗收）
- [ ] 4.2 在 CLAUDE.md 的「新增翻譯流程」補上 .mo compilation requirement 步驟：每次新增後執行 `msgfmt` 並確認 exit code 0
- [ ] 4.3 建立 `scripts/check-coverage.sh` — 掃描 $domains 陣列，確認每個 domain 都有對應 .po/.mo，回報缺漏
- [x] 4.4 驗證 fca-fluent-zh-tw.php 的 init hook 使用 priority=1（使用 init hook priority=1 進行優先載入），確認早於原始外掛的 priority=10 載入
- [ ] 4.5 確認 $domains 陣列採用靜態 $domains 陣列手動維護策略，不自動掃描，並在 CLAUDE.md 記錄新增 domain 的 checklist
- [ ] 4.6 將 sed 批次替換作為標準修復工具的指令寫入 `scripts/fix-email-duplication.sh`，供未來維護使用

## 5. 驗收

- [ ] 5.1 在測試站重新整理 FluentCart 電子郵件設定頁，確認「電子郵件設定」顯示正確（不再出現「電子電子電子郵件」）
- [x] 5.2 執行 `grep -rc "電子電子" languages/` 確認全庫殘留 = 0
- [x] 5.3 執行 spectra validate 確認所有 spec 需求都有對應任務覆蓋
