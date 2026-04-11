#!/bin/bash

# diff-domains.sh - 比對新舊 .po 檔，產出待翻清單
# Usage: ./diff-domains.sh <domain> <new-po-path> [--output <output-file>]

set -euo pipefail

DOMAIN="${1:-}"
NEW_PO="${2:-}"
OUTPUT_FILE=""

if [[ -z "$DOMAIN" ]] || [[ -z "$NEW_PO" ]]; then
    echo "Usage: $0 <domain> <new-po-path> [--output <output-file>]" >&2
    exit 1
fi

# 解析 --output 參數
if [[ "${3:-}" == "--output" ]] && [[ -n "${4:-}" ]]; then
    OUTPUT_FILE="$4"
fi

# 檢查新 .po 檔存在
if [[ ! -f "$NEW_PO" ]]; then
    echo "Error: New .po file not found: $NEW_PO" >&2
    exit 1
fi

# 本地 .po 檔路徑
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
LOCAL_PO="${REPO_ROOT}/languages/${DOMAIN}-zh_TW.po"

# 若本地 .po 不存在，當作全部都是新的
if [[ ! -f "$LOCAL_PO" ]]; then
    # 新增所有 msgid
    NEW_MSGIDS=$(grep "^msgid " "$NEW_PO" | sed 's/^msgid "\(.*\)"$/\1/' | sort)
    OBSOLETE_MSGIDS=""
    NEW_COUNT=$(echo "$NEW_MSGIDS" | grep -c . || echo 0)
    OBSOLETE_COUNT=0
else
    # 提取本地 msgid 清單
    LOCAL_MSGIDS=$(grep "^msgid " "$LOCAL_PO" | sed 's/^msgid "\(.*\)"$/\1/' | sort > /tmp/local_msgids.txt; cat /tmp/local_msgids.txt)
    
    # 提取新 msgid 清單
    NEW_MSGIDS_FULL=$(grep "^msgid " "$NEW_PO" | sed 's/^msgid "\(.*\)"$/\1/' | sort > /tmp/new_msgids.txt; cat /tmp/new_msgids.txt)
    
    # 使用 comm 比對差異
    # comm -23: 僅在第一個檔出現的行
    # comm -13: 僅在第二個檔出現的行
    NEW_MSGIDS=$(comm -23 /tmp/new_msgids.txt /tmp/local_msgids.txt || echo "")
    OBSOLETE_MSGIDS=$(comm -13 /tmp/new_msgids.txt /tmp/local_msgids.txt || echo "")
    
    NEW_COUNT=$(echo "$NEW_MSGIDS" | grep -c . || echo 0)
    OBSOLETE_COUNT=$(echo "$OBSOLETE_MSGIDS" | grep -c . || echo 0)
    
    rm -f /tmp/local_msgids.txt /tmp/new_msgids.txt
fi

# 組合輸出
OUTPUT=""

# 輸出新增字串
if [[ -n "$NEW_MSGIDS" ]]; then
    while IFS= read -r msgid; do
        if [[ -n "$msgid" ]]; then
            OUTPUT+="[NEW] $msgid"$'\n'
        fi
    done <<< "$NEW_MSGIDS"
fi

# 輸出刪除字串
if [[ -n "$OBSOLETE_MSGIDS" ]]; then
    while IFS= read -r msgid; do
        if [[ -n "$msgid" ]]; then
            OUTPUT+="[OBSOLETE] $msgid"$'\n'
        fi
    done <<< "$OBSOLETE_MSGIDS"
fi

# Summary
SUMMARY="$NEW_COUNT new, $OBSOLETE_COUNT obsolete strings"
OUTPUT+="$SUMMARY"

# 輸出到 stdout
echo -n "$OUTPUT"

# 若指定 --output，同時寫入檔案
if [[ -n "$OUTPUT_FILE" ]]; then
    echo -n "$OUTPUT" > "$OUTPUT_FILE"
fi

# 若無新增和刪除，輸出訊息並 exit 0；否則 exit 1（有變更）
if [[ $NEW_COUNT -eq 0 ]] && [[ $OBSOLETE_COUNT -eq 0 ]]; then
    echo "No new strings for $DOMAIN"
    exit 0
else
    exit 1
fi
