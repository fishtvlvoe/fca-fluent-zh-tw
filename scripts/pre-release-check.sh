#!/bin/bash

# pre-release-check.sh - 發版前自動化檢查（相容性 + 安全性）
# Usage: ./pre-release-check.sh [--quiet]
# Bash 3.2+ 相容（避免 Bash 4+ 語法）

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
QUIET_MODE="$1"
EXIT_CODE=0

log() {
    if [ -z "$QUIET_MODE" ]; then
        echo "$@"
    fi
}

error() {
    echo "❌ $@" >&2
    EXIT_CODE=1
}

success() {
    log "✅ $@"
}

# ============================================================================
# Check 1: PHP 7.4 相容性 — 掃描 PHP 8.0+ 函數
# ============================================================================
log ""
log "🔍 Check 1: PHP 7.4 相容性（掃描 PHP 8.0+ 函數）"

found_php8=0

# PHP 8.0+ 函數檢查（避免在註解中匹配）
grep -rn "str_ends_with\|str_starts_with\|str_contains\|array_is_list" "$REPO_ROOT" --include="*.php" 2>/dev/null | grep -v "^\s*//" | grep -v "^\s*\*" | while read line; do
    if echo "$line" | grep -E "\b(str_ends_with|str_starts_with|str_contains|array_is_list)\s*\(" > /dev/null; then
        # 排除註解行
        if ! echo "$line" | grep -E "(//|/\*|\*)" > /dev/null; then
            error "發現 PHP 8.0+ 函數（改用 PHP 7.4 相容版本）"
            echo "  $line" >&2
            found_php8=1
        fi
    fi
done

if [ $found_php8 -eq 0 ]; then
    success "無 PHP 8.0+ 函數"
fi

# ============================================================================
# Check 2: WordPress 編碼安全 — 檢查 json_encode vs wp_json_encode
# ============================================================================
log ""
log "🔍 Check 2: WordPress 編碼安全（檢查 json_encode）"

# 允許清單：只允許 updater.php 內的 json_encode（不含 wp_json_encode）
found_json=$(grep -rn "\bjson_encode\s*(" "$REPO_ROOT" --include="*.php" 2>/dev/null | grep -v "wp_json_encode" | grep -v "updater.php" | wc -l)

if [ $found_json -gt 0 ]; then
    error "發現 json_encode（應改用 wp_json_encode，允許清單：updater.php）"
    grep -rn "json_encode\s*(" "$REPO_ROOT" --include="*.php" 2>/dev/null | grep -v "updater.php" >&2 || true
else
    success "json_encode 只在 updater.php（允許）"
fi

# ============================================================================
# Check 3: 語法檢查 — PHP
# ============================================================================
log ""
log "🔍 Check 3: PHP 語法檢查"

php -l "$REPO_ROOT/fca-fluent-zh-tw.php" > /dev/null 2>&1 && success "fca-fluent-zh-tw.php" || error "fca-fluent-zh-tw.php 語法錯誤"
php -l "$REPO_ROOT/updater.php" > /dev/null 2>&1 && success "updater.php" || error "updater.php 語法錯誤"

# ============================================================================
# Check 4: 語法檢查 — Bash
# ============================================================================
log ""
log "🔍 Check 4: Bash 語法檢查"

bash -n "$REPO_ROOT/scripts/check-upstream.sh" 2>/dev/null && success "check-upstream.sh" || error "check-upstream.sh 語法錯誤"
bash -n "$REPO_ROOT/scripts/check-quality.sh" 2>/dev/null && success "check-quality.sh" || error "check-quality.sh 語法錯誤"
bash -n "$REPO_ROOT/scripts/check-coverage.sh" 2>/dev/null && success "check-coverage.sh" || error "check-coverage.sh 語法錯誤"
bash -n "$REPO_ROOT/scripts/diff-domains.sh" 2>/dev/null && success "diff-domains.sh" || error "diff-domains.sh 語法錯誤"
bash -n "$REPO_ROOT/scripts/fix-email-duplication.sh" 2>/dev/null && success "fix-email-duplication.sh" || error "fix-email-duplication.sh 語法錯誤"
bash -n "$REPO_ROOT/scripts/monthly-release.sh" 2>/dev/null && success "monthly-release.sh" || error "monthly-release.sh 語法錯誤"
bash -n "$REPO_ROOT/scripts/monthly-translation.sh" 2>/dev/null && success "monthly-translation.sh" || error "monthly-translation.sh 語法錯誤"

# ============================================================================
# 最終結果
# ============================================================================
log ""
if [ $EXIT_CODE -eq 0 ]; then
    log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    log "🎉 所有檢查通過，可安全發版"
    log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
else
    log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "❌ 發現檢查失敗，禁止發版" >&2
    log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
fi

exit $EXIT_CODE
