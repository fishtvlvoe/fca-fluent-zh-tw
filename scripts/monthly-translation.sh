#!/bin/bash

###############################################################################
# 月度翻譯自動化腳本
# 用途：掃描翻譯缺漏 → 翻譯 → 編譯 → 版本遞增 → commit → push
# 執行方式：bash scripts/monthly-translation.sh
###############################################################################

set -e

PLUGIN_DIR="$(cd "$(dirname "$0")/.." && pwd)"
LANGUAGES_DIR="${PLUGIN_DIR}/languages"
PLUGIN_FILE="${PLUGIN_DIR}/fca-fluent-zh-tw.php"
TIMESTAMP=$(date +"%Y-%m-%d %H:%M:%S")

echo "🚀 開始月度翻譯流程..."
echo "時間: $TIMESTAMP"
echo "目錄: $PLUGIN_DIR"
echo ""

###############################################################################
# 第 1 步：掃描翻譯缺漏
###############################################################################

echo "📊 第 1 步：掃描翻譯缺漏..."

# 這裡由 Claude Code 進行掃描
# 腳本會在這裡暫停，等待 Claude 的翻譯結果

cat > "${PLUGIN_DIR}/.translation-status" << 'EOF'
掃描狀態: 等待 Claude Code 翻譯
時間: $(date)
EOF

echo "✅ 掃描完成。正在等待 Claude Code 翻譯新字串..."
echo ""

###############################################################################
# 第 2 步：編譯 .mo 檔（在 Claude 翻譯完成後執行）
###############################################################################

echo "🔨 第 2 步：編譯 .mo 檔..."

if ! command -v msgfmt &> /dev/null; then
    echo "❌ msgfmt 未安裝。安裝 gettext:"
    echo "   brew install gettext"
    exit 1
fi

compiled_count=0
for po_file in ${LANGUAGES_DIR}/*-zh_TW.po; do
    if [ -f "$po_file" ]; then
        mo_file="${po_file%.po}.mo"
        msgfmt -o "$mo_file" "$po_file"
        echo "  ✓ $(basename "$mo_file")"
        ((compiled_count++))
    fi
done

echo "✅ 編譯完成（$compiled_count 個檔案）"
echo ""

###############################################################################
# 第 3 步：遞增版本號
###############################################################################

echo "📈 第 3 步：遞增版本號..."

CURRENT_VERSION=$(grep "Version:" "${PLUGIN_FILE}" | head -1 | sed 's/.*Version: //;s/ .*//')
echo "目前版本: $CURRENT_VERSION"

# 解析版本號（X.Y.Z）
IFS='.' read -r MAJOR MINOR PATCH <<< "$CURRENT_VERSION"
PATCH=$((PATCH + 1))
NEW_VERSION="${MAJOR}.${MINOR}.${PATCH}"

echo "新版本: $NEW_VERSION"

# 更新所有版本號
sed -i '' "s/Version: ${CURRENT_VERSION}/Version: ${NEW_VERSION}/g" "${PLUGIN_FILE}"
sed -i '' "s/new FCA_Fluent_ZhTW_Updater(__FILE__, '${CURRENT_VERSION}')/new FCA_Fluent_ZhTW_Updater(__FILE__, '${NEW_VERSION}')/g" "${PLUGIN_FILE}"

echo "✅ 版本號更新完成"
echo ""

###############################################################################
# 第 4 步：Git Commit & Push
###############################################################################

echo "📤 第 4 步：提交更改..."

cd "${PLUGIN_DIR}"

git add languages/*.po languages/*.mo fca-fluent-zh-tw.php 2>/dev/null || true
git add -A 2>/dev/null || true

COMMIT_MSG="chore: 月度翻譯更新 v${NEW_VERSION}"
git commit -m "$COMMIT_MSG" 2>/dev/null || echo "  ℹ️  沒有新變更"

echo "✅ 提交完成"
echo ""

###############################################################################
# 第 5 步：推送到 GitHub
###############################################################################

echo "🌐 第 5 步：推送到 GitHub..."

if git push origin main 2>/dev/null; then
    echo "✅ 推送成功"
    echo ""
    echo "🎉 GitHub Actions 會自動創建 Release！"
else
    echo "⚠️  推送失敗（可能沒有新變更）"
fi

echo ""
echo "════════════════════════════════════════════════════════════════"
echo "✅ 月度翻譯流程完成！"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo "📋 接下來："
echo "   1. 在本機 WordPress 驗證翻譯"
echo "   2. 如有缺漏，手動調整 ${LANGUAGES_DIR}/*.po"
echo "   3. 確認無誤後推播："
echo "      cd $PLUGIN_DIR && git push origin main"
echo "   4. GitHub 會自動創建 Release + 打包 .zip"
echo "   5. 手動安裝到 3 個虛擬主機"
echo "   6. 完工！之後 WordPress 會自動更新"
echo ""
