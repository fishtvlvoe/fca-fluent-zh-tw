## Why

fca-fluent-zh-tw 是一個 WordPress 翻譯包外掛，為所有 FCA 系列與 Fluent 系列外掛提供繁體中文翻譯。目前外掛缺乏正式的規格文件，且翻譯品質存在系統性問題（電子郵件重複疊加、msgid 重複條目），需要建立 SDD 以規範外掛架構、翻譯品質標準，以及維護流程。

## What Changes

- 建立外掛核心架構的規格文件（text domain 載入機制、自動更新器）
- 建立翻譯品質規格（術語一致性、格式符保留規則、已知 bug 修復流程）
- 建立新外掛翻譯擴充規格（何時新增、如何新增、驗證流程）
- 建立翻譯品質修復規格（批次修復「電子郵件」重複疊加問題、msgid 重複條目清理）

## Non-Goals

- 不涵蓋個別外掛的功能規格（僅涵蓋翻譯層）
- 不修改原始外掛原始碼（翻譯包原則：不動原始外掛）
- 不建立翻譯自動生成工具（手動維護為主）

## Capabilities

### New Capabilities

- `plugin-loader`: 外掛核心載入機制 — text domain 優先載入原理、`$domains` 清單管理、`init` hook 時機
- `translation-quality`: 翻譯品質標準 — 術語一致性規則、格式符保留、禁用詞彙、已知 bug 類型定義
- `translation-coverage`: 翻譯涵蓋範圍管理 — 新外掛偵測規則（fca-/fce-/fchub-/fluent- 開頭）、新增翻譯流程、.po/.mo 編譯標準
- `translation-bugfix`: 翻譯 bug 批次修復流程 — 「電子郵件」重複疊加修復（1217 處）、msgid 重複條目清理（116 個）、msguniq 去重流程

### Modified Capabilities

（none）

## Impact

- 受影響的檔案：`fca-fluent-zh-tw.php`、`languages/*.po`、`languages/*.mo`
- 翻譯 bug 修復影響 14 個 .po 檔（fluent-crm、fluentform、fluent-smtp 等）
- 重複條目清理影響 6 個 .po 檔（fluent-cart、fluent-booking 等）
