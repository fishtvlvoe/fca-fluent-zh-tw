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
