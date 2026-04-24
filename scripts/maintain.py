#!/usr/bin/env python3
"""
FCA & Fluent 繁體中文翻譯包 - 系統守護與維修腳本 (MAINTAINER)
老魚專屬版：記錄了今天所有的坑與最終解法。

踩過的坑 (TRAPS):
1. jQuery is not defined: 發生在頁面中段 (Line 10000+)，因為 jQuery 被排到頁尾了。
   解法: 強迫在 wp_head 最頂端 (Priority -1000) echo 出 script 標籤。
2. JS DOM 遍歷衝突 (codes.forEach): 與瀏覽器外掛 (如沈浸式翻譯) 衝突。
   解法: 排除 SCRIPT, STYLE, SVG, 且跳過以 / 或 http 開頭的路徑字串。
3. 503 伺服器過載: 頻繁的 file_exists 與 MutationObserver 導致。
   解法: PHP 靜態快取 + JS 250ms 防抖。
4. PWA Scope 錯誤: sw.js 權限不足。
   解法: 修改 fca-pwa 外掛設定 (關閉靜態服務) 或注入 Service-Worker-Allowed 標頭。
"""

import os
import re

PLUGIN_ROOT = os.path.dirname(os.path.abspath(__file__)) + "/.."
PHP_FILE = PLUGIN_ROOT + "/fca-fluent-zh-tw.php"
JS_TRANSLATOR = PLUGIN_ROOT + "/js/translator.js"

def check_jquery_fix():
    print("🔍 檢查 jQuery 暴力加載修復...")
    with open(PHP_FILE, 'r', encoding='utf-8') as f:
        content = f.read()
    if 'add_action(\'wp_head\', [__CLASS__, \'force_jquery_to_top\'], -1000)' in content:
        print("✅ jQuery 修復已在位 (wp_head 優先權 -1000)")
    else:
        print("❌ 警告: jQuery 載入優先權可能不足！")

def check_js_filters():
    print("🔍 檢查 JS 遍歷過濾清單...")
    with open(JS_TRANSLATOR, 'r', encoding='utf-8') as f:
        content = f.read()
    filters = ['SVG', 'URL', 'http', 'JSON', 'Service-Worker']
    for ft in filters:
        if ft in content:
            print(f"✅ JS 已包含 {ft} 過濾")
        else:
            print(f"❌ 警告: JS 遺漏了 {ft} 的安全過濾，可能造成當機！")

def check_performance_debounce():
    print("🔍 檢查 JS 效能防抖 (Debounce)...")
    with open(JS_TRANSLATOR, 'r', encoding='utf-8') as f:
        content = f.read()
    if '250);' in content or '300);' in content:
        print("✅ JS 防抖延遲已設定為 250ms+ (效能穩定)")
    else:
        print("❌ 警告: JS 防抖延遲過短，可能造成 503 過載！")

def main():
    print("--- FCA & Fluent 翻譯包系統診斷 ---")
    check_jquery_fix()
    check_js_filters()
    check_performance_debounce()
    print("--- 診斷結束 ---")

if __name__ == "__main__":
    main()
