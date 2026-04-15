#!/usr/bin/env python3
"""
自動提取 Vue/React bundle 中的硬編碼英文字串，並更新 js/translations.js

Usage:
  python3 scripts/extract-js-dom.py <domain> <plugin-dir> [--namespace <var_name>]

Example:
  python3 scripts/extract-js-dom.py fchub-wishlist /path/to/fchub-wishlist
  python3 scripts/extract-js-dom.py fchub-portal-extender /path/to/fchub-portal-extender
"""

import sys
import os
import re
import json
import argparse
from collections import OrderedDict, defaultdict

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
REPO_ROOT = os.path.join(SCRIPT_DIR, '..')
TRANSLATIONS_JS = os.path.join(REPO_ROOT, 'js', 'translations.js')
PHP_FILE = os.path.join(REPO_ROOT, 'fca-fluent-zh-tw.php')

# 明顯的第三方 chunk 檔名模式，直接跳過
VENDOR_PATTERNS = [
    r'_plugin-vue_export-helper',
    r'^css-',
    r'^message-',
    r'^overlay-',
    r'^portal-',
    r'^currency-',
    r'^refs-',
    r'^repeat-click-',
    r'^runtime-dom',
    r'^tag-',
    r'^use-ordered-children-',
    r'^wpDate-',
    r'^dist-',
]
VENDOR_RE = [re.compile(p) for p in VENDOR_PATTERNS]

# 第三方常見字串 denylist（Element Plus / Vue 通用字串）
THIRD_PARTY_DENYLIST = {
    'Cancel', 'Clear', 'Confirm', 'Delete', 'Edit', 'Search', 'Loading',
    'Please input', 'Please select', 'Success', 'Warning', 'Error', 'Info',
    'Close', 'Open', 'Submit', 'Reset', 'Refresh', 'Next', 'Previous',
    'Back', 'Forward', 'More', 'Less', 'All', 'None', 'Select', 'Selected',
    'Optional', 'Required', 'Default', 'Custom', 'Standard', 'Advanced',
    'Basic', 'General', 'Details', 'Summary', 'Name', 'Value', 'Type',
    'Status', 'Action', 'Actions', 'Date', 'Time', 'Description',
    'Title', 'Subtitle', 'Content', 'Message', 'Notes', 'Comments',
    'Label', 'Key', 'ID', 'Slug', 'Code', 'Color', 'Size', 'Width',
    'Height', 'Top', 'Left', 'Right', 'Bottom', 'Center', 'Inline',
    'Block', 'Flex', 'Grid', 'Table', 'List', 'Item', 'Items',
    'Page', 'Pages', 'Post', 'Posts', 'User', 'Users', 'Role', 'Roles',
    'Group', 'Groups', 'Tag', 'Tags', 'Category', 'Categories',
    'Menu', 'Menus', 'Link', 'Links', 'Button', 'Buttons', 'Icon', 'Icons',
    'Image', 'Images', 'File', 'Files', 'Folder', 'Folders',
    'True', 'False', 'On', 'Off', 'Yes', 'No',
    'Boolean', 'Map', 'Module', 'Set', 'Tree', 'Card', 'Dark', 'Light',
    'Expected a function', 'min should not be greater than max.',
    'label act as value', 'can not inject root menu', 'can not inject sub menu',
    've been compromised, never use user',
    'the header slot', 'the title slot',
    'pointer-events: auto;', 'scroll', 'plain', 'highlight-current',
    'input, textarea, select', 'border-card', 'suffix', 'validating',
    'vertical', 'primary', 'checked', 'close', 'error', 'group', 'default',
    'div', 'inline',
}

# 上下文模式：前面出現這些 token 時，更可能是 UI 文本
CONTEXT_BOOST = [
    r'label\s*:\s*["\']?$',
    r'title\s*:\s*["\']?$',
    r'placeholder\s*:\s*["\']?$',
    r'tooltip\s*:\s*["\']?$',
    r'confirmButtonText\s*:\s*["\']?$',
    r'cancelButtonText\s*:\s*["\']?$',
    r'header\s*:\s*["\']?$',
    r'description\s*:\s*["\']?$',
    r'message\s*:\s*["\']?$',
    r'content\s*:\s*["\']?$',
    r'text\s*:\s*["\']?$',
    r'\b[fF]\.success\(',
    r'\b[fF]\.error\(',
    r'\bElMessage\.(success|error|warning|info)\(',
    r'\bm\(',
    r'\b__\(',
]
CONTEXT_BOOST_RE = [re.compile(p) for p in CONTEXT_BOOST]


def load_translations_json(domain):
    json_path = os.path.join(SCRIPT_DIR, 'translations', f'{domain}.json')
    if os.path.exists(json_path):
        with open(json_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    return {}


def extract_js_strings_with_context(js_path):
    with open(js_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    results = []
    for m in re.finditer(r'"([^"\n]{1,200})"', content):
        start = max(0, m.start() - 100)
        ctx = content[start:m.start()]
        results.append((m.group(1), ctx))
    for m in re.finditer(r"'([^'\n]{1,200})'", content):
        start = max(0, m.start() - 100)
        ctx = content[start:m.start()]
        results.append((m.group(1), ctx))
    return results


def is_ui_candidate(s, ctx):
    s = s.strip()
    if len(s) < 2 or len(s) > 120:
        return False
    lower = s.lower()
    blacklist = {
        'use strict', 'null', 'undefined', 'true', 'false',
        'get', 'put', 'post', 'delete', 'patch', 'options', 'head',
        '[object object]', '[object map]', '[object set]', '[object dataview]', '[object date]',
    }
    if lower in blacklist:
        return False
    if s.startswith('./') or s.startswith('../') or s.startswith('http') or s.startswith('data:'):
        return False
    if s.startswith('onUpdate:') or s.startswith('onChange:'):
        return False
    if s.startswith('!') and len(s) < 20:
        return False
    if s.startswith('[') and '[object ' in s:
        return False
    if s.startswith('#/') or s.startswith(':/'):
        return False
    if re.match(r'^[A-Za-z0-9+/=]{40,}$', s) or re.match(r'^[0-9a-fA-F]{6,}$', s):
        return False
    if re.search(r'<[^>]+>', s):
        return False
    if 'data-v-' in s:
        return False
    if re.match(r'^\d+(\.\d+)?(px|em|rem|%|s|ms|deg|vh|vw|ex|ch)$', s):
        return False
    if re.match(r'^rgba?\(', s) or re.match(r'^#([0-9a-fA-F]{3,8})$', s):
        return False
    if not re.search(r'[A-Za-z]', s):
        return False
    if re.search(r'[\u4e00-\u9fff\u3040-\u309f\u30a0-\u30ff\uac00-\ud7af]', s):
        return False
    if s.startswith('/') and ' ' not in s:
        return False
    if re.match(r'^[^\s@]+@[^\s@]+\.[^\s@]+$', s):
        return False
    # 首字符必須是字母（排除以空格、逗號、冒號、% 開頭的代碼碎片）
    if not re.match(r'^[A-Za-z]', s):
        return False
    # CSS / selector / inline code fragments
    if 'fct-' in s or 'fchub-' in s or 'fc-' in s or '[data-' in s or ':scope' in s:
        return False
    if "'+" in s or "+'" in s or "`${" in s or " + " in s or '&&' in s or '||' in s:
        return False
    if 'void 0' in s or 'prefers-color-scheme' in s:
        return False
    # 含有反斜杠但非常见转义序列的，视为代码碎片
    if '\\' in s and not re.search(r'\\[nrt"]', s):
        return False
    if 'dashicons' in s or 'npm exec' in s or '127.0.0.1' in s:
        return False
    if 'text-align:' in s or 'max-width:' in s or 'font-size:' in s or 'margin:' in s or 'padding:' in s:
        return False
    # SVG path
    if re.match(r'^[MmLlHhVvCcSsQqTtAaZz\d\s.,-]+$', s) and len(s) > 15:
        return False
    if ' ' not in s and len(s) > 30:
        return False
    code_symbols = sum(1 for c in s if c in '(){}[]<>;=&|!+*/%?$@\\`~')
    if code_symbols / len(s) > 0.08:
        return False
    # CSS 值片段
    if 'px dashed' in s or 'px solid' in s or 'px dotted' in s:
        return False
    # 純 kebab-case 無大寫字母（幾乎不會是 UI 文案）
    if re.match(r'^[a-z][a-z0-9_-]*$', s) and '-' in s and ' ' not in s:
        return False
    # 純小寫無空格單字（如 label, disabled, card, tree）幾乎不會是 UI 文案
    if re.match(r'^[a-z]+$', s) and ' ' not in s:
        return False
    if s in THIRD_PARTY_DENYLIST:
        return False

    has_ui_context = any(rx.search(ctx) for rx in CONTEXT_BOOST_RE)
    if has_ui_context:
        return True
    if ' ' in s:
        return True
    if re.match(r'^[A-Z][a-z]+$', s) and len(s) <= 20:
        return True
    return False


def scan_plugin(plugin_dir):
    all_strings = defaultdict(int)
    for root, dirs, files in os.walk(plugin_dir):
        for f in files:
            if not f.endswith('.js'):
                continue
            basename = os.path.basename(f)
            if any(rx.search(basename) for rx in VENDOR_RE):
                continue
            path = os.path.join(root, f)
            for s, ctx in extract_js_strings_with_context(path):
                if is_ui_candidate(s, ctx):
                    all_strings[s] += 1
    return OrderedDict(sorted(all_strings.items(), key=lambda x: (-x[1], x[0])))


def parse_all_namespaces_from_translations_js():
    """解析 js/translations.js 中所有 namespace，返回 {namespace: {key: val}}"""
    if not os.path.exists(TRANSLATIONS_JS):
        return {}
    with open(TRANSLATIONS_JS, 'r', encoding='utf-8') as f:
        content = f.read()
    result = {}
    for m in re.finditer(r'var\s+(\w+)\s*=\s*\{(.*?)\n\s*\};', content, re.DOTALL):
        ns = m.group(1)
        block = '{' + m.group(2) + '}'
        d = {}
        for km in re.finditer(r"'((?:[^'\\]|\\.)*)'\s*:\s*'((?:[^'\\]|\\.)*)'", block):
            d[km.group(1)] = km.group(2)
        result[ns] = d
    return result


def parse_namespace_from_translations_js(namespace, all_namespaces=None):
    if all_namespaces is None:
        all_namespaces = parse_all_namespaces_from_translations_js()
    return all_namespaces.get(namespace, {})


def insert_namespace_into_translations_js(namespace, new_dict):
    with open(TRANSLATIONS_JS, 'r', encoding='utf-8') as f:
        content = f.read()

    lines = []
    max_key_len = max((len(k) for k in new_dict.keys()), default=0)
    for k, v in new_dict.items():
        k_escaped = k.replace("\\", "\\\\").replace("'", "\\'")
        v_escaped = v.replace("\\", "\\\\").replace("'", "\\'")
        pad = ' ' * (max_key_len - len(k) + 1)
        lines.append(f"        '{k_escaped}':{pad}'{v_escaped}',")
    if lines:
        lines[-1] = lines[-1].rstrip(',')
    block_body = '\n'.join(lines)

    # 檢查是否已存在該 namespace
    existing_pattern = re.compile(
        r'\s*var\s+' + re.escape(namespace) + r'\s*=\s*\{(.*?)\n\s*\};',
        re.DOTALL
    )

    if existing_pattern.search(content):
        replacement = f"\n    var {namespace} = {{\n{block_body}\n    }};"
        new_content = existing_pattern.sub(replacement, content)
        with open(TRANSLATIONS_JS, 'w', encoding='utf-8') as f:
            f.write(new_content)
        return 'updated'
    else:
        block = f"    // ── {namespace} ──────────────────────────────────────────────\n"
        block += f"    var {namespace} = {{\n{block_body}\n    }};\n"
        merge_comment = '// 合併所有字典'
        insert_pos = content.find(merge_comment)
        if insert_pos == -1:
            print(f"❌ 找不到 '{merge_comment}' 錨點，無法自動插入")
            return 'failed'
        new_content = content[:insert_pos] + block + '\n' + content[insert_pos:]

        sources_pattern = re.compile(r'(var sources = \[[^\]]*?)(\n?    \]);', re.DOTALL)
        m = sources_pattern.search(new_content)
        if m:
            prefix = m.group(1).rstrip()
            if not prefix.endswith(','):
                prefix += ','
            new_sources = prefix + f'\n        {namespace}\n    ];'
            new_content = new_content[:m.start()] + new_sources + new_content[m.end():]

        with open(TRANSLATIONS_JS, 'w', encoding='utf-8') as f:
            f.write(new_content)
        return 'inserted'


def update_php_relevant_pages(domain_slug):
    if not os.path.exists(PHP_FILE):
        return False
    with open(PHP_FILE, 'r', encoding='utf-8') as f:
        content = f.read()

    pattern = re.compile(r'(\$relevant_pages = \[[^\]]*?)(\n?        \]);', re.DOTALL)
    m = pattern.search(content)
    if not m:
        return False

    if f"'{domain_slug}'" in m.group(0):
        return False

    prefix = m.group(1).rstrip()
    last_quote_idx = prefix.rfind("'")
    if last_quote_idx != -1:
        tail = prefix[last_quote_idx + 1:].lstrip()
        if not tail.startswith(','):
            prefix = prefix[:last_quote_idx + 1] + ',' + prefix[last_quote_idx + 1:]
    indent = '            '
    new_entry = f"{indent}'{domain_slug}',    // {domain_slug} 設定頁"
    new_block = prefix + '\n' + new_entry + '\n        ];'
    new_content = content[:m.start()] + new_block + content[m.end():]
    with open(PHP_FILE, 'w', encoding='utf-8') as f:
        f.write(new_content)
    return True


def default_namespace(domain):
    parts = domain.split('-')
    return parts[0] + ''.join(p.capitalize() for p in parts[1:])


def main():
    parser = argparse.ArgumentParser(description='Extract JS DOM strings and update translations.js')
    parser.add_argument('domain', help='Text domain (e.g. fchub-wishlist)')
    parser.add_argument('plugin_dir', help='Plugin directory to scan')
    parser.add_argument('--namespace', help='JS variable name in translations.js (auto-generated if omitted)')
    parser.add_argument('--dry-run', action='store_true', help='Print results without writing files')
    args = parser.parse_args()

    namespace = args.namespace or default_namespace(args.domain)
    domain = args.domain
    plugin_dir = args.plugin_dir

    if not os.path.isdir(plugin_dir):
        print(f"❌ 找不到目錄 {plugin_dir}")
        sys.exit(1)

    print(f"🔍 掃描 {plugin_dir} ...")
    extracted = scan_plugin(plugin_dir)
    print(f"   提取到 {len(extracted)} 個候選字串")

    trans_json = load_translations_json(domain)
    all_namespaces = parse_all_namespaces_from_translations_js()
    existing_js = parse_namespace_from_translations_js(namespace, all_namespaces)

    fallback_map = {}
    for ns_dict in all_namespaces.values():
        for k, v in ns_dict.items():
            if k not in fallback_map and v != k:
                fallback_map[k] = v

    merged = OrderedDict()
    auto_filled = 0
    fallback_filled = 0
    already_exist = 0
    missing = 0

    for s in extracted.keys():
        if s in existing_js:
            merged[s] = existing_js[s]
            already_exist += 1
        elif s in trans_json:
            merged[s] = trans_json[s]
            auto_filled += 1
        elif s in fallback_map:
            merged[s] = fallback_map[s]
            fallback_filled += 1
        else:
            merged[s] = s
            missing += 1

    for s, v in existing_js.items():
        if s not in merged:
            merged[s] = v
            already_exist += 1

    print(f"   已存在於 translations.js: {already_exist}")
    print(f"   自動從 translations/{domain}.json 填入: {auto_filled}")
    print(f"   自動從其他 namespace fallback 填入: {fallback_filled}")
    print(f"   尚缺翻譯 (保留英文): {missing}")

    if missing > 0 and not args.dry_run:
        print("\n⚠️  以下字串尚無翻譯，已保留英文，請手動補上：")
        for s, v in merged.items():
            if v == s:
                print(f"   - {s}")

    if args.dry_run:
        print("\n--- Dry run output (first 20) ---")
        for s, v in list(merged.items())[:20]:
            print(f"  '{s}': '{v}'")
        return

    status = insert_namespace_into_translations_js(namespace, merged)
    if status == 'updated':
        print(f"✅ 已更新 js/translations.js 中的 {namespace}")
    elif status == 'inserted':
        print(f"✅ 已插入 {namespace} 到 js/translations.js")

    php_updated = update_php_relevant_pages(domain)
    if php_updated:
        print(f"✅ 已更新 fca-fluent-zh-tw.php 的 $relevant_pages")


if __name__ == '__main__':
    main()
