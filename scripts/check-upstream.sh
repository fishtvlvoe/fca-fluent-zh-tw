#!/bin/bash

# check-upstream.sh - 透過 GitHub API 查詢各外掛最新版本
# Usage: ./check-upstream.sh [--quiet]

# Bash 4+ required (project scripts may use associative arrays)
if (( BASH_VERSINFO[0] < 4 )); then
    echo "Error: Bash >= 4.0 is required. Detected: ${BASH_VERSION}" >&2
    echo "macOS ships with Bash 3.2 by default. Install newer Bash (e.g. 'brew install bash') and rerun with:" >&2
    echo "  /opt/homebrew/bin/bash $0 $*" >&2
    echo "  /usr/local/bin/bash $0 $*" >&2
    exit 1
fi

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TRACKER_FILE="${SCRIPT_DIR}/version-tracker.json"

# Domain 到 GitHub repo 的對應表
get_repo_for_domain() {
    local domain="$1"
    case "$domain" in
        # FCA 系列
        fca-boards) echo "fluentcrm/fca-boards" ;;
        fca-comments) echo "fluentcrm/fca-comments" ;;
        fca-content-manager) echo "fluentcrm/fca-content-manager" ;;
        fca-course-blocks) echo "fluentcrm/fca-course-blocks" ;;
        fca-display-name) echo "fluentcrm/fca-display-name" ;;
        fca-events) echo "fluentcrm/fca-events" ;;
        fca-events-basic) echo "fluentcrm/fca-events-basic" ;;
        fca-events-spaces) echo "fluentcrm/fca-events-spaces" ;;
        fca-global-search) echo "fluentcrm/fca-global-search" ;;
        fca-hub) echo "fluentcrm/fca-hub" ;;
        fca-knowledgebase) echo "fluentcrm/fca-knowledgebase" ;;
        fca-multi-reactions) echo "fluentcrm/fca-multi-reactions" ;;
        fca-pages) echo "fluentcrm/fca-pages" ;;
        fca-push-notifications) echo "fluentcrm/fca-push-notifications" ;;
        fca-pwa) echo "fluentcrm/fca-pwa" ;;
        fca-widgets) echo "fluentcrm/fca-widgets" ;;
        fce-quick-fixes) echo "fluentcrm/fce-quick-fixes" ;;
        fce-shortcodes) echo "fluentcrm/fce-shortcodes" ;;
        fc-partner) echo "" ;; # 手動維護（非 GitHub fluentcrm repo）
        # Fluent 系列
        fluent-booking) echo "fluentcrm/fluent-booking" ;;
        fluent-booking-pro) echo "fluentcrm/fluent-booking-pro" ;;
        fluent-cart) echo "fluentcrm/fluent-cart" ;;
        fluent-cart-elementor-blocks) echo "fluentcrm/fluent-cart-elementor-blocks" ;;
        fluent-cart-pro) echo "fluentcrm/fluent-cart-pro" ;;
        fluent-community) echo "fluentcrm/fluent-community" ;;
        fluent-community-pro) echo "fluentcrm/fluent-community-pro" ;;
        fluent-crm) echo "fluentcrm/fluent-crm" ;;
        fluent-messaging) echo "fluentcrm/fluent-messaging" ;;
        fluent-player) echo "fluentcrm/fluent-player" ;;
        fluent-player-pro) echo "fluentcrm/fluent-player-pro" ;;
        fluent-security) echo "fluentcrm/fluent-security" ;;
        fluent-smtp) echo "fluentcrm/fluent-smtp" ;;
        fluent-toolkit) echo "fluentcrm/fluent-toolkit" ;;
        fluentcampaign-pro) echo "fluentcrm/fluentcampaign-pro" ;;
        fluentform) echo "fluentcrm/fluentform" ;;
        fluentform-block) echo "fluentcrm/fluentform-block" ;;
        fluentformpro) echo "fluentcrm/fluentformpro" ;;
        # FCHub 系列
        fchub-multi-currency) echo "fluentcrm/fchub-multi-currency" ;;
        *) echo "" ;;
    esac
}

# 所有 domain 清單
DOMAINS=(
    fca-boards fca-comments fca-content-manager fca-course-blocks
    fca-display-name fca-events fca-events-basic fca-events-spaces
    fca-global-search fca-hub fca-knowledgebase fca-multi-reactions
    fca-pages fca-push-notifications fca-pwa fca-widgets
    fce-quick-fixes fce-shortcodes fc-partner
    fluent-booking fluent-booking-pro fluent-cart fluent-cart-elementor-blocks
    fluent-cart-pro fluent-community fluent-community-pro fluent-crm
    fluent-messaging fluent-player fluent-player-pro fluent-security
    fluent-smtp fluent-toolkit fluentcampaign-pro fluentform
    fluentform-block fluentformpro fchub-multi-currency
)

# 若 version-tracker.json 不存在，建立並初始化全部為 "0.0.0"
if [[ ! -f "$TRACKER_FILE" ]]; then
    echo "Creating $TRACKER_FILE with initial versions..." >&2
    temp_json="{"
    first=true
    for domain in "${DOMAINS[@]}"; do
        if [[ "$first" == true ]]; then
            temp_json+=$'\n'
            first=false
        else
            temp_json+=",$'\n'"
        fi
        temp_json+="  \"$domain\": \"0.0.0\""
    done
    temp_json+=$'\n'"}"
    echo "$temp_json" > "$TRACKER_FILE"
fi

QUIET_MODE="${1:-}"
UPDATE_COUNT=0
UPDATED_DOMAINS=()

# 逐個 domain 查詢版本
for domain in "${DOMAINS[@]}"; do
    repo=$(get_repo_for_domain "$domain")
    
    if [[ -z "$repo" ]]; then
        continue
    fi
    
    # 讀取本地記錄版本
    local_version=$(jq -r ".[\"$domain\"]" "$TRACKER_FILE" 2>/dev/null || echo "0.0.0")
    
    # 查詢 GitHub API
    api_url="https://api.github.com/repos/${repo}/releases/latest"
    response=$(curl -s -w "\n%{http_code}" "$api_url" 2>/dev/null || echo -e "\n000")
    http_code=$(echo "$response" | tail -1)
    body=$(echo "$response" | sed '$d')
    
    if [[ "$http_code" != "200" ]]; then
        echo "[WARN] $domain: API error ($http_code), skipped" >&2
        continue
    fi
    
    # 提取版本號（tag_name 通常是 "v1.2.3" 或 "1.2.3"）
    remote_version=$(echo "$body" | jq -r '.tag_name // .version // "0.0.0"' 2>/dev/null | sed 's/^v//')
    
    if [[ -z "$remote_version" ]] || [[ "$remote_version" == "null" ]] || [[ "$remote_version" == "0.0.0" ]]; then
        remote_version="0.0.0"
    fi
    
    # 比對版本
    if [[ "$remote_version" != "$local_version" ]]; then
        echo "[UPDATE] $domain: $local_version → $remote_version"
        UPDATE_COUNT=$((UPDATE_COUNT + 1))
        UPDATED_DOMAINS+=("$domain:$remote_version")
    fi
done

# 輸出 Summary
if [[ $UPDATE_COUNT -eq 0 ]]; then
    echo "No updates found"
    exit 0
else
    echo "$UPDATE_COUNT plugin(s) have updates"
    exit 1
fi
