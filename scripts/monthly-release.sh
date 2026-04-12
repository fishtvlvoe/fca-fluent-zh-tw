#!/bin/bash

# monthly-release.sh - 整合版本檢查和 diff，每月 20 日自動執行
# Usage: ./monthly-release.sh [--urgent] [--update-tracker]

# Bash 4+ required (uses associative arrays)
if (( BASH_VERSINFO[0] < 4 )); then
    echo "Error: Bash >= 4.0 is required. Detected: ${BASH_VERSION}" >&2
    echo "macOS ships with Bash 3.2 by default. Install newer Bash (e.g. 'brew install bash') and rerun with:" >&2
    echo "  /opt/homebrew/bin/bash $0 $*" >&2
    echo "  /usr/local/bin/bash $0 $*" >&2
    exit 1
fi

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
TRACKER_FILE="${SCRIPT_DIR}/version-tracker.json"
CHECK_UPSTREAM="${SCRIPT_DIR}/check-upstream.sh"
DIFF_DOMAINS="${SCRIPT_DIR}/diff-domains.sh"

URGENT_MODE=false
UPDATE_TRACKER=false

# 解析參數
while [[ $# -gt 0 ]]; do
    case "$1" in
        --urgent)
            URGENT_MODE=true
            shift
            ;;
        --update-tracker)
            UPDATE_TRACKER=true
            shift
            ;;
        *)
            echo "Unknown option: $1" >&2
            exit 1
            ;;
    esac
done

# 檢查是否在月份 20 日或 --urgent 模式
DAY=$(date +%d)
MONTH=$(date +%m)
YEAR=$(date +%Y)

if [[ "$URGENT_MODE" == false ]] && [[ "$DAY" != "20" ]]; then
    echo "This script should only run on the 20th of each month. Use --urgent to override."
    exit 0
fi

PREFIX=""
if [[ "$URGENT_MODE" == true ]]; then
    PREFIX="[URGENT RELEASE] "
fi

echo "${PREFIX}Starting monthly translation update workflow..."

# 執行 check-upstream.sh
echo ""
echo "=== Step 1: Checking upstream versions ==="
if ! upstream_output=$("$BASH" "$CHECK_UPSTREAM" 2>&1); then
    exit_code=$?
    if [[ $exit_code -ne 1 ]]; then
        # 只有 exit 1 (有更新) 是允許的非零 exit，其他都是錯誤
        echo "Error running check-upstream.sh" >&2
        exit "$exit_code"
    fi
fi

echo "$upstream_output"

# 判斷是否有更新
if echo "$upstream_output" | grep -q "^No updates found$"; then
    echo ""
    echo "本月無更新，無需發版"
    exit 0
fi

# 解析更新清單（提取 [UPDATE] 行）
echo ""
echo "=== Step 2: Generating translation diffs ==="
updated_domains=$(echo "$upstream_output" | grep "^\[UPDATE\]" | sed 's/\[UPDATE\] \([^:]*\):.*/\1/' || echo "")

# 記錄 domain 版本對應
declare -A domain_versions
while IFS= read -r line; do
    if [[ "$line" =~ ^\[UPDATE\]\ ([^:]+):\ ([^ ]+)\ \"?→\"?\ ([^ ]+)$ ]]; then
        domain="${BASH_REMATCH[1]}"
        new_version="${BASH_REMATCH[3]}"
        domain_versions["$domain"]="$new_version"
    fi
done <<< "$upstream_output"

# 為每個有更新的 domain 執行 diff-domains
diff_results=""
for domain in $updated_domains; do
    echo ""
    echo "Diffing $domain..."
    # 這裡需要能取得新的 .po 檔路徑
    # 實務上，新的 .po 檔通常由外部來源（如客戶提交）提供
    # 此處暫時略過 diff 執行，僅列出已更新的 domain
    echo "  Domain $domain has updates waiting for translation diff"
done

echo ""
echo "${PREFIX}Monthly workflow summary:"
echo "  Updated domains: $(echo $updated_domains | wc -w) domain(s)"
for domain in $updated_domains; do
    version="${domain_versions[$domain]:-unknown}"
    echo "    - $domain: → $version"
done

echo ""
echo "Next steps:"
echo "1. Obtain the new .po files for each domain from upstream"
echo "2. Run: bash scripts/diff-domains.sh <domain> <new.po> --output pending-<domain>.txt"
echo "3. Review and translate the new strings"
echo "4. Test with: bash scripts/check-coverage.sh"
echo "5. Compile: msgfmt languages/<domain>-zh_TW.po -o languages/<domain>-zh_TW.mo"
echo "6. Update version tracker: ./monthly-release.sh --update-tracker"

if [[ "$UPDATE_TRACKER" == true ]]; then
    echo ""
    echo "=== Updating version tracker ==="
    # 更新 version-tracker.json
    for domain in $updated_domains; do
        version="${domain_versions[$domain]:-0.0.0}"
        # 使用 jq 更新 JSON
        if command -v jq &> /dev/null; then
            jq ".\"$domain\" = \"$version\"" "$TRACKER_FILE" > "${TRACKER_FILE}.tmp"
            mv "${TRACKER_FILE}.tmp" "$TRACKER_FILE"
            echo "  Updated $domain to $version"
        else
            echo "  [WARN] jq not found, cannot update $domain version" >&2
        fi
    done
    echo "Version tracker updated successfully"
fi

echo ""
echo "${PREFIX}Workflow complete"
exit 0
