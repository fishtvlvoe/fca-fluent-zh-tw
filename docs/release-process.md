# 版本發佈流程 & 三層防守機制

## 問題陳述

**舊問題**：修正已 commit，但沒有發佈新版本 → 雲端用戶永遠看不到修正。

**根本原因**：
1. ✅ 代碼修正了
2. ✅ Commit 進 git 了
3. ❌ **但版本號沒有遞增**
4. ❌ **所以沒有新的 Release 發佈**
5. ❌ **所以雲端用戶用自動更新，還是舊版**

## 解決方案：三層防守機制

```
┌─────────────────────────────────────────────────────────┐
│  Local Development                                       │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Layer 1: Pre-commit Hook  ← 攔截「漏掉版本號」的 commit
│  ├─ 檢查：若有程式碼改動，版本號是否有遞增？
│  ├─ 檢查：版本號是否遞增到新值（不能改成相同值）
│  ├─ 檢查：commit message 是否符合 conventional commits
│  └─ 結果：版本號不遞增 → ❌ 攔截，提示修復
│
│  若通過：
│  └─ git commit 成功
│     git push origin main
│
└─────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────┐
│  GitHub Actions (CI/CD)                                  │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Layer 2: Auto Release Workflow  ← 自動發佈新版本
│  ├─ 觸發：main push 時檢查 fca-fluent-zh-tw.php
│  ├─ 檢查：PHP Version 是否 > 最新 tag
│  ├─ 若版本變更：
│  │  ├─ 生成 changelog（列出新增 commits）
│  │  ├─ 建立 git tag v{version}
│  │  ├─ 建立 GitHub Release
│  │  ├─ 生成 zip 打包檔
│  │  └─ 發送 Slack 通知（可選）
│  └─ 若版本未變：跳過發佈
│
│  結果：1-5 分鐘內自動發佈
│
└─────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────┐
│  WordPress Site (Cloud)                                 │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  User's WP Backend                                       │
│  外掛 > 已安裝的外掛                                      │
│  ↓                                                       │
│  自動更新檢查 (updater.php)                              │
│  ├─ 每 12 小時檢查一次最新 release                       │
│  ├─ 查詢 GitHub API: v{version} release 是否存在        │
│  └─ 若有新版本 → 顯示「有可用的更新」提示
│                                                          │
│  ✅ 用戶點擊「更新」→ 下載最新版本
│
└─────────────────────────────────────────────────────────┘
                           ↓
             ✅ 雲端用戶終於看到修正！
```

---

## 三層防守詳細說明

### 層級 1：Pre-commit Hook（本機開發）

**檔案**：`.husky/pre-commit`

**功能**：commit 時自動檢查
- ✅ 有程式碼改動 → 必須遞增版本號
- ✅ 版本號必須遞增到新值（不能騙過，改成相同值不行）
- ✅ Commit message 必須符合 conventional commits (feat/fix/docs 等)

**觸發時機**：`git commit` 時

**失敗時**：
```bash
$ git commit -m "fix: email duplicate bug"

❌ Error: 有程式碼改動但沒有更新 Version
   修復方式：
   1. 編輯 fca-fluent-zh-tw.php
   2. 在第 6 行的 Version 欄位遞增（1.6.3 → 1.6.4）
   3. git add fca-fluent-zh-tw.php
   4. git commit 重新提交
```

**優點**：
- 🎯 最早期攔截，防止 commit 進 repo
- 🔒 無法繞過（除非 `git commit --no-verify`，但違反規範）
- ⚡ 立即反饋，開發時馬上知道問題

---

### 層級 2：GitHub Actions Auto-Release（CI/CD）

**檔案**：`.github/workflows/auto-release.yml`

**功能**：push main 後自動檢查並發佈
1. 比對 PHP Version vs 最新 git tag
2. 若版本變更：
   - 自動建立 git tag v{version}
   - 自動建立 GitHub Release
   - 自動打包成 zip
   - 自動推送 Slack 通知

3. 若版本未變：跳過發佈

**觸發時機**：
```bash
git push origin main
```

**執行時間**：1-5 分鐘內自動完成

**監控連結**：
https://github.com/fishtvlvoe/fca-fluent-zh-tw/actions

**優點**：
- 🤖 完全自動化，無需手動操作
- 🔗 與 git 工作流直接整合
- 📊 GitHub Actions dashboard 可查看執行狀態
- 🔔 支援 Slack 通知，團隊即時知道新版本發佈

---

### 層級 3：版本同步監控（Python 腳本）

**檔案**：
- `scripts/version-sync-check.py` — Python 完整檢查
- `scripts/version-check` — Bash 便捷工具

**功能**：
1. 檢查「代碼已更新但版本號未變」的情況
2. 計算「自上次發版以來」的 commit 數和天數
3. 若超過閾值（>5 commits 或 >14 天）→ 警告

**用途**：
- 定期巡檢（可設定 cron）
- CI/CD 檢查
- 開發時手動驗證

**用法**：

```bash
# 快速檢查
bash scripts/version-check --check

# 檢查 + 自動遞增版本（實驗功能）
bash scripts/version-check --auto-fix

# 強制建立 tag（恢復 CI 故障）
bash scripts/version-check --force-tag

# 深度 Python 檢查
python3 scripts/version-sync-check.py
```

**輸出範例**：

```
✅ 版本同步
   當前版本（PHP）: 1.6.3
   最新標籤（Git）: v1.6.3
   自上次發版: 0 commits / 0 天
```

**優點**：
- 🔍 檢測「漏掉版本號」的微妙問題
- 📈 計算距上次發版的「距離」（commits + days）
- 🚨 當距離超過閾值時主動提醒
- 🐍 Python 實現，易於擴展和整合

---

## 使用場景

### 場景 1：正常開發 → 發版（最常見）

```bash
# 1. 修改翻譯檔或代碼
vim languages/fluent-cart-zh_TW.po
git add languages/fluent-cart-zh_TW.po

# 2. 更新版本號
vim fca-fluent-zh-tw.php
# 修改：Version: 1.6.3 → Version: 1.6.4
git add fca-fluent-zh-tw.php

# 3. 提交
git commit -m "fix: update fluent-cart translation"
# ← Layer 1 hook 檢查：
#   ✅ 有代碼改動（.po 檔）
#   ✅ 版本號遞增（1.6.3 → 1.6.4）
#   ✅ Message 格式正確（fix:）
#   → Commit 通過！

# 4. 推送
git push origin main
# ← Layer 2 GitHub Actions 檢查：
#   ✅ PHP Version = 1.6.4
#   ✅ 最新 tag = v1.6.3
#   → 版本變更，自動發佈 Release v1.6.4！
#   → Zip 打包完成
#   → Slack 通知發佈

# 5. 驗證
bash scripts/version-check --check
# ✅ 版本同步
#    當前: 1.6.4
#    標籤: v1.6.4
```

**結果**：✅ Release 已自動發佈，1-5 分鐘內用戶可以看到更新提示

---

### 場景 2：漏掉版本號（被 Layer 1 攔截）

```bash
# 不小心漏掉版本號
vim languages/fluent-crm-zh_TW.po
git add languages/fluent-crm-zh_TW.po
git commit -m "fix: crm email typo"

❌ Error: 有程式碼改動但沒有更新 Version
   修復方式：
   1. 編輯 fca-fluent-zh-tw.php，更新 Version 號
   2. git add fca-fluent-zh-tw.php
   3. git commit 重新提交

# 按提示修復
vim fca-fluent-zh-tw.php
# 修改：Version: 1.6.4 → Version: 1.6.5
git add fca-fluent-zh-tw.php
git commit -m "fix: crm email typo"
# ← Layer 1 hook 再次檢查：
#   ✅ 有代碼改動（.po 檔）
#   ✅ 版本號遞增（1.6.4 → 1.6.5）
#   → Commit 通過！
```

**結果**：❌ 最初 commit 被攔截，迫使開發者立即修復

---

### 場景 3：CI 故障恢復（Layer 3 救援）

```bash
# 假設 GitHub Actions 故障，Release 未自動建立
# 手動驗證
bash scripts/version-check --check
# ⚠️ 版本已變（1.6.5）但無 tag

# 強制恢復
bash scripts/version-check --force-tag
# ✅ Tag v1.6.5 已強制推送
#    Release 將在 1-5 分鐘內建立

# 監控
# → 開啟 https://github.com/fishtvlvoe/fca-fluent-zh-tw/actions
# → 等待 GitHub Actions 重新執行
```

**結果**：✅ 即使 CI 故障也能手動恢復

---

## 集成到開發流程

### 開發者檢查清單

```
□ 修改代碼或翻譯
□ 執行 bash scripts/version-check --check（驗證狀態）
□ 編輯 fca-fluent-zh-tw.php，更新 Version 號
□ git add + git commit
  └─ Layer 1 hook 自動檢查
□ git push origin main
  └─ Layer 2 GitHub Actions 自動發佈
□ 檢查 GitHub Release：https://github.com/fishtvlvoe/fca-fluent-zh-tw/releases
□ 完成！
```

---

## 故障排除

### 問題 1：Pre-commit Hook 失敗

**症狀**：
```
❌ Error: commit message 不符合 conventional commits 格式
```

**解決**：
```bash
# 修正 commit message
git commit --amend -m "fix(fluent-crm): update translation"
```

### 問題 2：GitHub Actions 未執行

**症狀**：
- Push 後 5 分鐘仍無 Release

**檢查**：
1. 打開 https://github.com/fishtvlvoe/fca-fluent-zh-tw/actions
2. 看「Auto Release & Sync」workflow 是否執行
3. 若失敗，點進檢查錯誤訊息

**常見原因**：
- GitHub Actions 權限不足 → 檢查 repo settings > Actions
- `fca-fluent-zh-tw.php` 未修改 → 確認版本號已變更

### 問題 3：版本號寫錯了

**症狀**：
```bash
bash scripts/version-check --check
⚠️ 版本號更新了但沒有新 commit
```

**解決**：
```bash
# 編輯回正確的版本號
vim fca-fluent-zh-tw.php
# 重新 commit
git add fca-fluent-zh-tw.php
git commit --amend -m "chore: correct version number"
git push origin main -f  # 注意：force push，確保沒有其他人在用
```

---

## 相關檔案

| 檔案 | 用途 | 觸發時機 |
|------|------|---------|
| `.husky/pre-commit` | 本機 commit 檢查 | `git commit` 時 |
| `.github/workflows/auto-release.yml` | 自動發佈 Release | `git push origin main` 後 |
| `scripts/version-check` | 版本同步快速檢查 | 手動執行 |
| `scripts/version-sync-check.py` | 深度版本監控 | 定期巡檢或 CI |
| `updater.php` | WordPress 自動更新檢查 | 用戶 WordPress 後台每 12h |
| `.spectra.yaml` | Spectra SDD 設定 | 開發流程 |

---

## 最佳實踐

1. **每個功能修復** = 一個 commit + 一個版本號遞增
   - 不要多個 commit 後才一次遞增版本
   - 這樣每次 push 都自動發佈，用戶收到最新修正

2. **Commit message 規範**
   - `feat(domain): add new translation` — 新翻譯
   - `fix(fluent-cart): update email text` — 修復
   - `docs: update README` — 文檔
   - Hook 會檢查，不符合會被攔截

3. **定期檢查版本狀態**
   - 每月初執行 `bash scripts/version-check --check`
   - 確保未發佈的修正不會被遺忘

4. **版本號規則**
   - Patch +0.0.1（例 1.6.3 → 1.6.4）
   - 不需要考慮 major/minor，自動化負責 patch 遞增

---

## 總結

| 層級 | 防守點 | 工具 | 時機 |
|------|--------|------|------|
| **1** | 攔截「漏掉版本號的 commit」 | `.husky/pre-commit` | 本機 commit 時 |
| **2** | 自動發佈新版本 | `.github/workflows/auto-release.yml` | Push 後 1-5 分鐘 |
| **3** | 監控「代碼已更新但版本號未變」 | `scripts/version-sync-check.py` | 定期或 CI |

**結果**：修正永遠不會被遺漏 → 用戶永遠能看到最新修正 ✅
