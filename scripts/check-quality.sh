#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

total_violations=0

check_msgstr_block() {
  local block="$1"
  local file="$2"
  local line_base="$3"

  if [[ "$block" == *"貨幣"* ]]; then
    echo "$file:$line_base: 禁用術語：貨幣"
    total_violations=$((total_violations + 1))
  fi

  if [[ "$block" == *"保存"* ]]; then
    echo "$file:$line_base: 禁用術語：保存"
    total_violations=$((total_violations + 1))
  fi

  if [[ "$block" == *"存儲"* ]]; then
    echo "$file:$line_base: 禁用術語：存儲"
    total_violations=$((total_violations + 1))
  fi

  if [[ "$block" == *"電子電子"* ]]; then
    echo "$file:$line_base: 禁用術語：電子電子（重複）"
    total_violations=$((total_violations + 1))
  fi

  # 「付款」為 Payment 的正確翻譯，不列為違規
}

for po in languages/*.po; do
  [[ -e "$po" ]] || continue

  buf=""
  start_line=0

  flush_block() {
    if [[ -n "$buf" ]]; then
      check_msgstr_block "$buf" "$po" "$start_line"
    fi
    buf=""
    start_line=0
  }

  line_no=0
  while IFS= read -r line || [[ -n "$line" ]]; do
    line_no=$((line_no + 1))

    if [[ "$line" =~ ^msgstr ]]; then
      flush_block
      buf="$line"
      start_line=$line_no
      continue
    fi

    if [[ -n "$buf" ]]; then
      if [[ "$line" =~ ^msgid\  ]] || [[ "$line" =~ ^msgctxt\  ]] || [[ "$line" =~ ^#: ]]; then
        flush_block
      elif [[ -z "$line" ]]; then
        flush_block
      else
        buf+=$'\n'"$line"
      fi
    fi
  done <"$po"

  flush_block
done

if [[ "$total_violations" -eq 0 ]]; then
  echo "無違規"
else
  echo "總違規數：$total_violations"
  exit 1
fi
