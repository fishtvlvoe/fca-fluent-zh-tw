#!/usr/bin/env python3
"""
版本同步監控 — 偵測「代碼已更新但版本號未變」

用途：
1. 在 CI/CD 中運行（GitHub Actions）
2. 本機開發時手動運行：python3 scripts/version-sync-check.py
3. 定期檢查（cron：每月 15 日）

邏輯：
- 比對 git 歷史：最後修改日期 vs 最後發版日期
- 計算「代碼距上次發版」的 commit 數
- 若距離超過閾值 + 版本號未變 → 警告
"""

import subprocess
import sys
import json
from datetime import datetime
from pathlib import Path

# ════════════════════════════════════════════════════════════════════
# 設定
# ════════════════════════════════════════════════════════════════════

PLUGIN_FILE = "fca-fluent-zh-tw.php"
THRESHOLD_COMMITS = 5  # 超過 5 個 commit 未發版就警告
THRESHOLD_DAYS = 14    # 或超過 14 天未發版就警告

# ════════════════════════════════════════════════════════════════════
# 工具函數
# ════════════════════════════════════════════════════════════════════


def run_cmd(cmd):
    """執行 shell 命令並返回結果"""
    try:
        result = subprocess.run(cmd, shell=True, capture_output=True, text=True, check=True)
        return result.stdout.strip()
    except subprocess.CalledProcessError as e:
        print(f"❌ 命令失敗: {cmd}")
        print(f"   錯誤: {e.stderr}")
        sys.exit(1)


def get_current_version():
    """從 PHP 檔案取得版本號"""
    if not Path(PLUGIN_FILE).exists():
        print(f"❌ 找不到 {PLUGIN_FILE}")
        sys.exit(1)

    with open(PLUGIN_FILE, 'r', encoding='utf-8') as f:
        for line in f:
            if 'Version:' in line:
                # 格式: * Version: 1.6.3
                version = line.split('Version:')[1].strip().split()[0]
                return version
    return None


def get_latest_tag():
    """取得最新 git tag（版本號）"""
    try:
        tag = run_cmd("git tag -l 'v*' --sort=-version:refname | head -1")
        if tag:
            return tag.lstrip('v')
        return None
    except:
        return None


def get_commits_since_tag(tag):
    """計算最新 tag 以來的 commit 數"""
    if not tag:
        return run_cmd("git rev-list --count HEAD")

    count = run_cmd(f"git rev-list --count v{tag}..HEAD")
    return int(count)


def get_last_commit_date(tag=None):
    """取得最新 commit 的日期"""
    if tag:
        date = run_cmd(f"git log -1 --format=%ci v{tag}")
    else:
        date = run_cmd("git log -1 --format=%ci")

    # 格式: 2026-04-13 17:02:34 +0800 → datetime
    return datetime.fromisoformat(date.split('+')[0].split(' ')[0])


def get_days_since_release(tag):
    """計算自上次發版以來經過的天數"""
    last_tag_date = get_last_commit_date(tag)
    today = datetime.now()
    delta = today - last_tag_date
    return delta.days


def check_file_modified_since_tag(tag, filepath=PLUGIN_FILE):
    """檢查特定檔案在 tag 之後是否有修改"""
    if not tag:
        return True  # 無 tag 表示有修改

    try:
        # 檢查在 v{tag}..HEAD 之間是否有修改過此檔案
        result = subprocess.run(
            f"git diff v{tag}..HEAD --name-only | grep -q '{filepath}'",
            shell=True,
            capture_output=True
        )
        return result.returncode == 0
    except:
        return True


# ════════════════════════════════════════════════════════════════════
# 主檢查邏輯
# ════════════════════════════════════════════════════════════════════


def main():
    print("\n" + "=" * 70)
    print("版本同步檢查 (Version Sync Check)")
    print("=" * 70 + "\n")

    current_version = get_current_version()
    latest_tag = get_latest_tag()
    commits_count = get_commits_since_tag(latest_tag)

    print(f"📝 當前版本（PHP）: {current_version}")
    print(f"📦 最新標籤（Git）: v{latest_tag if latest_tag else 'N/A'}")
    print(f"📊 自上次發版以來: {commits_count} 個 commit")

    # ────────────────────────────────────────────────────────────────
    # 檢查 1：版本號是否已更新但未發版
    # ────────────────────────────────────────────────────────────────

    if latest_tag and current_version == latest_tag:
        print(f"\n✅ 版本同步正常（v{current_version}）")
        return 0

    elif latest_tag and current_version != latest_tag:
        print(f"\n⏳ 新版本已設定但尚未發版")
        print(f"   舊版本: v{latest_tag}")
        print(f"   新版本: v{current_version}")
        print(f"   待發佈: {commits_count} 個 commit")

        if commits_count == 0:
            print(f"\n⚠️  警告：版本號已更新（v{current_version}）但沒有新 commit")
            print(f"   原因：可能是手動修改了版本號但沒有實際改動代碼")
            return 1
        else:
            print(f"\n✅ 正常狀態：等待 GitHub Actions 自動發版...")
            return 0

    # ────────────────────────────────────────────────────────────────
    # 檢查 2：代碼已更新但版本號未變（最常見的疏漏）
    # ────────────────────────────────────────────────────────────────

    if not latest_tag:
        print(f"\n⚠️  尚無發版歷史，首次準備發版")
        print(f"   將建立: v{current_version}")
        return 0

    # 超過閾值？
    days_since = get_days_since_release(latest_tag)
    exceeds_commits = commits_count > THRESHOLD_COMMITS
    exceeds_days = days_since > THRESHOLD_DAYS

    if exceeds_commits or exceeds_days:
        print(f"\n⏰ 距上次發版: {days_since} 天")
        print(f"\n❌ 警告：代碼已更新但版本號未變！")
        print(f"   - 新 commits: {commits_count} 個（閾值: {THRESHOLD_COMMITS}）")
        print(f"   - 距上次發版: {days_since} 天（閾值: {THRESHOLD_DAYS}）")
        print(f"\n   建議操作:")
        print(f"   1. 編輯 fca-fluent-zh-tw.php")
        print(f"   2. 更新 Version: {current_version} → {increment_version(current_version)}")
        print(f"   3. git add fca-fluent-zh-tw.php")
        print(f"   4. git commit -m 'chore: bump version to {increment_version(current_version)}'")
        print(f"   5. git push origin main")
        print(f"   6. GitHub Actions 會自動建立 Release")
        return 1

    print(f"\n✅ 版本狀態正常")
    print(f"   距上次發版: {days_since} 天")
    print(f"   待發版 commit: {commits_count} 個")
    return 0


def increment_version(version):
    """遞增版本號（patch +0.0.1）"""
    parts = version.split('.')
    if len(parts) >= 3:
        parts[2] = str(int(parts[2]) + 1)
    return '.'.join(parts)


# ════════════════════════════════════════════════════════════════════
# 執行
# ════════════════════════════════════════════════════════════════════

if __name__ == "__main__":
    try:
        exit_code = main()
        sys.exit(exit_code)
    except KeyboardInterrupt:
        print("\n⏸ 已中止")
        sys.exit(130)
    except Exception as e:
        print(f"\n❌ 未預期的錯誤: {e}")
        import traceback
        traceback.print_exc()
        sys.exit(1)
