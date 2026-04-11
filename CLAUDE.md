<!-- SPECTRA:START v1.0.1 -->

# Spectra Instructions

This project uses Spectra for Spec-Driven Development(SDD). Specs live in `openspec/specs/`, change proposals in `openspec/changes/`.

## Use `/spectra:*` skills when:

- A discussion needs structure before coding → `/spectra:discuss`
- User wants to plan, propose, or design a change → `/spectra:propose`
- Tasks are ready to implement → `/spectra:apply`
- There's an in-progress change to continue → `/spectra:ingest`
- User asks about specs or how something works → `/spectra:ask`
- Implementation is done → `/spectra:archive`

## Workflow

discuss? → propose → apply ⇄ ingest → archive

- `discuss` is optional — skip if requirements are clear
- Requirements change mid-work? Plan mode → `ingest` → resume `apply`

## Parked Changes

Changes can be parked（暫存）— temporarily moved out of `openspec/changes/`. Parked changes won't appear in `spectra list` but can be found with `spectra list --parked`. To restore: `spectra unpark <name>`. The `/spectra:apply` and `/spectra:ingest` skills handle parked changes automatically.

<!-- SPECTRA:END -->

# fca-fluent-zh-tw 外掛翻譯包

## 外掛涵蓋範圍

此翻譯包涵蓋所有 **FCA 系列**、**FCHub 系列**、**Fluent 系列**外掛。

## 新外掛翻譯規則（強制）

當發現以下開頭的外掛尚未有翻譯時，**主動新增**翻譯檔：

- `fca-` 開頭的外掛
- `fce-` 開頭的外掛
- `fchub-` 開頭的外掛（2026-04-08 新增）
- `fluent-` 開頭的外掛
- `fluentform` / `fluentcampaign` 開頭的外掛

## 新增翻譯流程

1. 掃描外掛目錄找出所有可翻譯字串（PHP `__()` 系列 + JS `wp.i18n.__()` 系列）
2. 建立 `languages/{text-domain}-zh_TW.po`
3. 編譯 `msgfmt ... -o languages/{text-domain}-zh_TW.mo`
3.5. 驗證編譯成功：`msgfmt languages/{text-domain}-zh_TW.po -o languages/{text-domain}-zh_TW.mo`，exit code 必須為 0
4. 在 `fca-fluent-zh-tw.php` 的 `$domains` 陣列加入新 domain
5. 更新版本號（patch +0.0.1）

### 新增 Domain Checklist（靜態 $domains 陣列手動維護）

1. 在 fca-fluent-zh-tw.php 的 $domains 陣列加入新 domain 字串
2. 確認 languages/{domain}-zh_TW.po 已建立
3. 執行 msgfmt languages/{domain}-zh_TW.po -o languages/{domain}-zh_TW.mo
4. 執行 bash scripts/check-coverage.sh 確認無缺漏
5. 更新版本號（patch +0.0.1）

## 翻譯原則

- 用詞一致：幣別（不用「貨幣」）、結帳（不用「付款」）、儲存設定（不用「保存」）

### 翻譯術語表（Consistent terminology）

| 概念 | 正確用詞 | 禁止用詞 |
|------|---------|---------|
| Currency | 幣別 | 貨幣 |
| Checkout | 結帳 | 付款 |
| Save settings | 儲存設定 | 保存、存儲 |
| Email | 電子郵件 | 電子電子郵件、電郵 |

- 保留技術名詞原文：FluentCRM、FluentCart、ISO
- 格式符 `%s`、`%1$s`、`%d` 等保持原位不翻譯

## 每月維護 SOP

Translation maintenance is automated via scripts triggered every 20th of the month. For urgent releases, use `--urgent` flag.

### 自動化流程（月度 20 日觸發）

1. **版本偵測**: `bash scripts/check-upstream.sh`
   - 透過 GitHub Releases API 查詢各外掛最新版本
   - 對比 `scripts/version-tracker.json`，輸出有更新的 domain 清單
   - 若無更新，自動短路（exit 0）

2. **新增字串偵測**: `bash scripts/diff-domains.sh <domain> <new.po>`
   - 比對新舊 .po 檔，輸出新增/刪除的 msgid 清單
   - 支援 `--output <file>` 選項，寫入待翻檔案供翻譯作業參考

3. **統一入口**: `bash scripts/monthly-release.sh [--urgent] [--update-tracker]`
   - `--urgent`: 繞過日期限制，立即執行（發版事件時使用）
   - `--update-tracker`: 翻譯完成後，自動更新 `version-tracker.json` 的版本號
   - 輸出摘要清單，指引翻譯步驟

### 每月發版步驟（Maintainer SOP）

**每月 20 日或緊急時刻執行：**

```bash
# 1. 觸發版本檢查和差異偵測
bash scripts/monthly-release.sh --urgent

# 2. 對各更新的 domain，下載新 .po 檔並執行 diff
bash scripts/diff-domains.sh fluent-cart /path/to/upstream/fluent-cart-zh_TW.po --output pending-fluent-cart.txt

# 3. 審核並翻譯 pending-*.txt 中的新字串

# 4. 更新本地 .po 檔並編譯
msgfmt languages/fluent-cart-zh_TW.po -o languages/fluent-cart-zh_TW.mo

# 5. 驗證涵蓋率
bash scripts/check-coverage.sh

# 6. 更新版本追蹤檔
bash scripts/monthly-release.sh --update-tracker

# 7. 提交 PR、發版
git add -A && git commit -m "chore: monthly translation update - {month}"
git tag v{version} && git push origin v{version}
```

### Domain 維護清單

- Domain-to-repo 對應表在 `scripts/check-upstream.sh` 內（case 語句）
- 新增 domain 時：
  1. 在 fca-fluent-zh-tw.php 的 `$domains` 陣列新增
  2. 在 scripts/check-upstream.sh 的 get_repo_for_domain() 新增對應 repo 路徑
  3. 在 DOMAINS 陣列新增 domain 名稱
  4. 執行 `bash scripts/version-tracker.json` 手動補填實際版本號（首次）或自動新增為 "0.0.0"

### 緊急發版（突發狀況）

```bash
# 不受月度 20 日限制，立即執行
bash scripts/monthly-release.sh --urgent

# 輸出會加上 [URGENT RELEASE] 前綴，方便追蹤
```

### 常見問題

**Q: 某個 domain 查詢失敗 (API 404 / 403)**  
A: 該 repo 可能是私有或已遷移。檢查 scripts/check-upstream.sh 內的對應表是否過時，更新後重新執行。

**Q: 如何手動更新單一 domain 的版本號？**  
A: 編輯 scripts/version-tracker.json，直接修改該 domain 的版本號，儲存後下次比對會使用新版本。

**Q: 翻譯完成後忘記執行 --update-tracker**  
A: 執行 `bash scripts/monthly-release.sh --update-tracker` 補上，會自動更新所有記錄版本。

