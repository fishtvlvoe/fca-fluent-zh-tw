#!/usr/bin/env bash
set -eo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

PHP_FILE="fca-fluent-zh-tw.php"

if [[ ! -f "$PHP_FILE" ]]; then
  echo "找不到 $PHP_FILE" >&2
  exit 1
fi

domains=()
while IFS= read -r line; do
  domains+=("$line")
done < <(
  awk '
    /private static \$domains = \[/ { inside=1; next }
    inside && /\];/ { exit }
    inside && /^\s*'"'"'[^'"'"']+'"'"',/ {
      gsub(/^[[:space:]]*'"'"'|'"'"',?$/, "", $0)
      print
    }
  ' "$PHP_FILE"
)

missing=0

for domain in "${domains[@]}"; do
  po="languages/${domain}-zh_TW.po"
  mo="languages/${domain}-zh_TW.mo"

  if [[ ! -f "$po" ]]; then
    echo "缺少：$po"
    missing=$((missing + 1))
  fi

  if [[ ! -f "$mo" ]]; then
    echo "缺少：$mo"
    missing=$((missing + 1))
  fi
done

if [[ "$missing" -eq 0 ]]; then
  echo "所有 domain 都有對應 .po/.mo"
else
  echo "缺漏項目數：$missing" >&2
  exit 1
fi
