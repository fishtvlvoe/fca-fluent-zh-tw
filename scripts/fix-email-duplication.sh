#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

shopt -s nullglob

for po in languages/*.po; do
  triple_before=$(grep -oF '電子電子電子郵件' "$po" 2>/dev/null | wc -l | tr -d ' ')

  perl -i -CS -pe 's/電子電子電子郵件/電子郵件/g' "$po"

  double_before=$(grep -oF '電子電子郵件' "$po" 2>/dev/null | wc -l | tr -d ' ')

  perl -i -CS -pe 's/電子電子郵件/電子郵件/g' "$po"

  fixes=$((triple_before + double_before))

  if grep -q '電子電子' "$po" 2>/dev/null; then
    dup_count=$(grep -o '電子電子' "$po" | wc -l | tr -d ' ')
    echo "$po：驗證失敗，仍含「電子電子」共 ${dup_count} 處" >&2
    exit 1
  fi

  base=$(basename "$po" .po)
  mo="languages/${base}.mo"

  if msgfmt "$po" -o "$mo"; then
    echo "$po：修復 $fixes 處（三重 $triple_before、雙重 $double_before），msgfmt 成功 → $mo"
  else
    echo "$po：msgfmt 失敗" >&2
    exit 1
  fi
done
