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
4. 在 `fca-fluent-zh-tw.php` 的 `$domains` 陣列加入新 domain
5. 更新版本號（patch +0.0.1）

## 翻譯原則

- 用詞一致：幣別（不用「貨幣」）、結帳（不用「付款」）、儲存設定（不用「保存」）
- 保留技術名詞原文：FluentCRM、FluentCart、ISO
- 格式符 `%s`、`%1$s`、`%d` 等保持原位不翻譯
