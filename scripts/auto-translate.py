#!/usr/bin/env python3
"""
自動翻譯更新腳本
Usage:
  # 使用 .pot 檔更新現有 .po
  python3 scripts/auto-translate.py <domain> <pot-path>

  # 從外掛目錄掃描並建立全新的 .po
  python3 scripts/auto-translate.py <domain> --scan <plugin-dir>

Example:
  python3 scripts/auto-translate.py fluent-cart /path/to/fluent-cart.pot
  python3 scripts/auto-translate.py fchub-memberships --scan /path/to/fchub-memberships
"""

import sys
import os
import re
import datetime
import json
import polib
from collections import OrderedDict

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
REPO_ROOT = os.path.join(SCRIPT_DIR, '..')


def load_translations(domain):
    """從 JSON 檔載入翻譯字典，若不存在則回傳空字典"""
    json_path = os.path.join(SCRIPT_DIR, 'translations', f'{domain}.json')
    if os.path.exists(json_path):
        with open(json_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    return {}


def extract_strings_from_plugin(plugin_dir, domain):
    """掃描外掛目錄提取指定 domain 的所有 msgid"""
    strings = OrderedDict()
    patterns = [
        r'__\s*\(\s*(["\'])(.*?)\1\s*,\s*(["\'])(.*?)\3\s*\)',
        r'_e\s*\(\s*(["\'])(.*?)\1\s*,\s*(["\'])(.*?)\3\s*\)',
        r'_x\s*\(\s*(["\'])(.*?)\1\s*,\s*(["\'])(.*?)\3\s*,\s*(["\'])(.*?)\5\s*\)',
        r'esc_html__\s*\(\s*(["\'])(.*?)\1\s*,\s*(["\'])(.*?)\3\s*\)',
        r'esc_attr__\s*\(\s*(["\'])(.*?)\1\s*,\s*(["\'])(.*?)\3\s*\)',
        r'esc_html_e\s*\(\s*(["\'])(.*?)\1\s*,\s*(["\'])(.*?)\3\s*\)',
        r'esc_attr_e\s*\(\s*(["\'])(.*?)\1\s*,\s*(["\'])(.*?)\3\s*\)',
    ]
    for root, dirs, files in os.walk(plugin_dir):
        dirs[:] = [d for d in dirs if d not in ['node_modules', 'vendor', '.git', 'assets', 'dist', 'build']]
        for f in files:
            if not f.endswith('.php'):
                continue
            filepath = os.path.join(root, f)
            try:
                with open(filepath, 'r', encoding='utf-8', errors='ignore') as fh:
                    content = fh.read()
            except Exception:
                continue
            for pattern in patterns:
                for m in re.finditer(pattern, content):
                    text = m.group(2)
                    d = m.group(4)
                    if d == domain:
                        # Unescape PHP single-quoted string escapes
                        text = text.replace("\\\\", "\\").replace("\\'", "'")
                        if text not in strings:
                            strings[text] = []
                        strings[text].append(os.path.relpath(filepath, plugin_dir))
    return strings


def build_pot_from_strings(strings, domain):
    """從提取的字串建立 polib.POFile (當作 pot 使用)"""
    pot = polib.POFile()
    pot.metadata = {
        'Project-Id-Version': domain,
        'Report-Msgid-Bugs-To': '',
        'POT-Creation-Date': datetime.datetime.now().strftime('%Y-%m-%d %H:%M+0000'),
        'MIME-Version': '1.0',
        'Content-Type': 'text/plain; charset=UTF-8',
        'Content-Transfer-Encoding': '8bit',
        'Plural-Forms': 'nplurals=INTEGER; plural=EXPRESSION;',
    }
    for text, paths in strings.items():
        entry = polib.POEntry(
            msgid=text,
            occurrences=[(p, 0) for p in paths[:5]],  # 最多保留 5 個出現位置
        )
        pot.append(entry)
    return pot


def update_po(domain, source):
    """
    source 可以是 .pot 檔路徑，或是外掛目錄路徑（掃描模式）
    """
    po_path = os.path.join(REPO_ROOT, 'languages', f'{domain}-zh_TW.po')

    # 決定來源：pot 檔或掃描目錄
    if os.path.isdir(source):
        scan_mode = True
        strings = extract_strings_from_plugin(source, domain)
        pot = build_pot_from_strings(strings, domain)
        print(f"📁 掃描模式：{source}")
        print(f"   提取到 {len(strings)} 個唯一字串")
    else:
        scan_mode = False
        if not os.path.exists(source):
            print(f"❌ 找不到 {source}")
            sys.exit(1)
        pot = polib.pofile(source)

    # 載入舊 po（若存在）
    old_entries = OrderedDict()
    if os.path.exists(po_path):
        old_po = polib.pofile(po_path)
        for entry in old_po:
            if entry.msgid == '':
                continue
            old_entries[entry.msgid] = entry
    else:
        old_po = None

    pot_ids = {e.msgid for e in pot if e.msgid != ''}
    trans_map = load_translations(domain)

    new_count = 0
    obsolete_count = 0
    updated_count = 0
    fuzzy_count = 0

    def set_translation(e, text):
        if e.msgid_plural:
            e.msgstr_plural = {0: text}
        else:
            e.msgstr = text

    new_po_entries = []
    for entry in pot:
        if entry.msgid == '':
            new_po_entries.append(entry)
            continue

        if entry.msgid in old_entries:
            old_entry = old_entries[entry.msgid]
            old_empty = not old_entry.msgstr and (not old_entry.msgid_plural or all(not v for v in old_entry.msgstr_plural.values()))
            if old_empty and entry.msgid in trans_map:
                set_translation(entry, trans_map[entry.msgid])
                updated_count += 1
            else:
                if old_entry.msgid_plural:
                    entry.msgstr_plural = dict(old_entry.msgstr_plural)
                else:
                    entry.msgstr = old_entry.msgstr
                entry.flags = old_entry.flags
                entry.comment = old_entry.comment
                entry.tcomment = old_entry.tcomment
        else:
            if entry.msgid in trans_map:
                set_translation(entry, trans_map[entry.msgid])
                new_count += 1
            else:
                set_translation(entry, '')
                entry.flags.append('fuzzy')
                fuzzy_count += 1
                new_count += 1

        new_po_entries.append(entry)

    # 計算刪除的過時條目
    for msgid in old_entries:
        if msgid not in pot_ids:
            obsolete_count += 1

    # 重建 po 檔
    new_po = polib.POFile()
    if old_po:
        new_po.header = old_po.header
        new_po.metadata = dict(old_po.metadata)
    else:
        new_po.metadata = {
            'Project-Id-Version': domain,
            'Report-Msgid-Bugs-To': '',
            'POT-Creation-Date': datetime.datetime.now().strftime('%Y-%m-%d %H:%M+0000'),
            'PO-Revision-Date': datetime.datetime.now().strftime('%Y-%m-%d %H:%M+0800'),
            'Last-Translator': '',
            'Language-Team': '繁體中文',
            'Language': 'zh_TW',
            'MIME-Version': '1.0',
            'Content-Type': 'text/plain; charset=UTF-8',
            'Content-Transfer-Encoding': '8bit',
            'Plural-Forms': 'nplurals=1; plural=0;',
            'X-Generator': 'auto-translate.py (polib)',
        }

    new_po.metadata['PO-Revision-Date'] = datetime.datetime.now().strftime('%Y-%m-%d %H:%M+0800')
    new_po.metadata['X-Generator'] = 'auto-translate.py (polib)'

    for entry in new_po_entries:
        new_po.append(entry)

    # 備份舊檔
    if os.path.exists(po_path):
        backup_path = po_path + '.backup'
        os.rename(po_path, backup_path)
    else:
        backup_path = None

    new_po.save(po_path)
    print(f"✅ 更新完成：{domain}")
    print(f"   新增翻譯：{new_count}")
    if updated_count:
        print(f"   補上舊空翻譯：{updated_count}")
    if fuzzy_count:
        print(f"   待翻譯 (fuzzy)：{fuzzy_count}")
    print(f"   移除過時：{obsolete_count}")
    if backup_path:
        print(f"   備份檔案：{backup_path}")
    return po_path


if __name__ == '__main__':
    if len(sys.argv) < 3:
        print("Usage: python3 scripts/auto-translate.py <domain> <pot-path>")
        print("       python3 scripts/auto-translate.py <domain> --scan <plugin-dir>")
        sys.exit(1)

    domain = sys.argv[1]
    if sys.argv[2] == '--scan':
        source = sys.argv[3]
    else:
        source = sys.argv[2]

    update_po(domain, source)
