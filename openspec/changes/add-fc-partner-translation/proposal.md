## Why

`fc-partner`（Partner for FluentCommunity v0.9.141，WP Plugin Magic）是 BuyGo 站點上已安裝且會面向前台用戶的外掛，目前沒有繁體中文翻譯。雖然它不在本翻譯包的自動涵蓋範圍（非 `fca-` / `fce-` / `fchub-` / `fluent-` 開頭），但使用者介面長期顯示英文會影響終端用戶體驗，因此手動納入。

## What Changes

- 新增 `languages/fc-partner-zh_TW.po`（597 條 msgid，來源：外掛內附 `fc-partner.pot`）
- 產出 `languages/fc-partner-zh_TW.mo`（`msgfmt` 編譯）
- `fca-fluent-zh-tw.php` 的 `$domains` 陣列新增 `'fc-partner'`
- `scripts/version-tracker.json` 新增 `fc-partner` 紀錄版本 `0.9.141`
- `scripts/check-upstream.sh` 的 `get_repo_for_domain()` + `DOMAINS` 陣列補上 `fc-partner`（標記為手動維護，避免 API 查詢失敗）
- 外掛版本號 `1.6.55` → `1.6.56`

## Non-Goals

- 不處理 `fc-partner` 外掛本身的程式碼修改（僅提供翻譯）
- 不自動化後續版本比對（該外掛 repo 可能非公開，版本追蹤需手動）
- 不調整現有其他 domain 的翻譯

## Capabilities

### New Capabilities

（無 — 本 change 屬資料新增，沿用既有 `plugin-loader` 與 `translation-coverage` 機制，不引入新能力）

### Modified Capabilities

- `plugin-loader`: 擴充 `$domains` 註冊清單的涵蓋規則，允許手動納入非 `fca-` / `fce-` / `fchub-` / `fluent-` 前綴的第三方外掛（本次為 `fc-partner`）

## Impact

- Affected specs: 無（純資料 + 翻譯新增，不影響既有 spec 行為）
- Affected code:
  - `fca-fluent-zh-tw/fca-fluent-zh-tw.php`（版本號 + `$domains` 陣列）
  - `fca-fluent-zh-tw/languages/fc-partner-zh_TW.po`（新增）
  - `fca-fluent-zh-tw/languages/fc-partner-zh_TW.mo`（新增）
  - `fca-fluent-zh-tw/scripts/version-tracker.json`（新增 `fc-partner` 項目）
  - `fca-fluent-zh-tw/scripts/check-upstream.sh`（新增 domain 對應）
- 版本變更：`1.6.55` → `1.6.56`（patch）
