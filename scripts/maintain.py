#!/usr/bin/env python3
"""
FCA & Fluent 翻譯包 - 全自動維護腳本 (Maintainer Pro)
整合掃描、提取、補齊、編譯、版本管理。

Usage:
  python3 scripts/maintain.py <plugin-dir-path> <domain>

Example:
  python3 scripts/maintain.py ../fluent-crm fluent-crm
"""

import sys
import os
import subprocess
import datetime
import re

# 設定路徑
SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
REPO_ROOT = os.path.join(SCRIPT_DIR, '..')
AUTO_TRANSLATE = os.path.join(SCRIPT_DIR, 'auto-translate.py')
EXTRACT_JS = os.path.join(SCRIPT_DIR, 'extract-js-dom.py')
PHP_FILE = os.path.join(REPO_ROOT, 'fca-fluent-zh-tw.php')
MSGFMT = '/opt/homebrew/bin/msgfmt'

def run_command(cmd):
    print(f"🚀 執行: {' '.join(cmd)}")
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0:
        print(f"❌ 錯誤: {result.stderr}")
    else:
        print(result.stdout)
    return result.returncode == 0

def update_version():
    """自動增加 Patch 版本號"""
    with open(PHP_FILE, 'r', encoding='utf-8') as f:
        content = f.read()
    
    match = re.search(r'Version:\s*(\d+\.\d+\.)(\d+)', content)
    if match:
        base = match.group(1)
        patch = int(match.group(2))
        new_version = f"{base}{patch + 1}"
        print(f"📦 版本升級: {match.group(1)}{patch} -> {new_version}")
        
        # 更新 PHP 主檔
        new_content = re.sub(r'Version:\s*\d+\.\d+\.\d+', f'Version: {new_version}', content)
        new_content = re.sub(r"ZhTW_Updater\(\$file,\s*'\d+\.\d+\.\d+'\)", f"ZhTW_Updater($file, '{new_version}')", new_content)
        
        with open(PHP_FILE, 'w', encoding='utf-8') as f:
            f.write(new_content)
            
        # 更新 updater.php (如果有)
        updater_path = os.path.join(REPO_ROOT, 'updater.php')
        if os.path.exists(updater_path):
            with open(updater_path, 'r', encoding='utf-8') as f:
                u_content = f.read()
            u_content = re.sub(r"'\d+\.\d+\.\d+'", f"'{new_version}'", u_content)
            with open(updater_path, 'w', encoding='utf-8') as f:
                f.write(u_content)

def main():
    if len(sys.argv) < 3:
        print(__doc__)
        sys.exit(1)

    plugin_dir = sys.argv[1]
    domain = sys.argv[2]

    if not os.path.isdir(plugin_dir):
        print(f"❌ 找不到目錄: {plugin_dir}")
        sys.exit(1)

    print(f"🛠️ 開始維護 Domain: {domain}")
    print("=" * 40)

    # 1. 處理 PHP / Gettext 翻譯
    print("📥 [1/4] 掃描 PHP 字串並更新 .po...")
    run_command(['python3', AUTO_TRANSLATE, domain, '--scan', plugin_dir])

    # 2. 處理 JS / DOM 翻譯
    print("📥 [2/4] 掃描 JS 字串並更新 translations.js...")
    run_command(['python3', EXTRACT_JS, domain, plugin_dir])

    # 3. 編譯 .mo 檔
    print("⚙️ [3/4] 編譯 .mo 檔...")
    po_path = os.path.join(REPO_ROOT, 'languages', f'{domain}-zh_TW.po')
    mo_path = os.path.join(REPO_ROOT, 'languages', f'{domain}-zh_TW.mo')
    if os.path.exists(po_path):
        if not run_command([MSGFMT, po_path, '-o', mo_path]):
            # 嘗試後備路徑
            run_command(['msgfmt', po_path, '-o', mo_path])

    # 4. 版本更新
    print("🆙 [4/4] 正在更新外掛版本...")
    update_version()

    print("=" * 40)
    print(f"🎉 {domain} 維護完成！")
    print(f"💡 提醒: 若有新增翻譯，請記得 git commit 並發布新版本。")

if __name__ == '__main__':
    main()
