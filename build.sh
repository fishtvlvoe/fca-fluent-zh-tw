#!/bin/bash
# 打包 WordPress 外掛 ZIP
# 用法：
#   bash build.sh            → 打包到外掛上層目錄
#   bash build.sh --desktop  → 打包並複製到桌面

PLUGIN_SLUG="fca-fluent-zh-tw"
PLUGIN_DIR="$(cd "$(dirname "$0")" && pwd)"
VERSION=$(grep "Version:" "${PLUGIN_DIR}/${PLUGIN_SLUG}.php" | awk '{print $NF}' | tr -d ' \r')
OUTPUT="${PLUGIN_SLUG}-${VERSION}.zip"
OUTPUT_PATH="${PLUGIN_DIR}/../${OUTPUT}"

echo "打包 ${PLUGIN_SLUG} v${VERSION}..."

# 移除舊的 zip
rm -f "${OUTPUT_PATH}"

# 從上層目錄打包，排除開發用與不必要的檔案
cd "${PLUGIN_DIR}/.."
zip -r "${OUTPUT}" "${PLUGIN_SLUG}/" \
    --exclude "*/.git*" \
    --exclude "*/.gitignore" \
    --exclude "*/.DS_Store" \
    --exclude "*/build.sh" \
    --exclude "*/__MACOSX*" \
    --exclude "*.zip" \
    --exclude "*/node_modules/*" \
    --exclude "*/vendor/*" \
    --exclude "*/.github/*" \
    --quiet

SIZE=$(ls -lh "${OUTPUT_PATH}" | awk '{print $5}')
echo "完成：${OUTPUT_PATH} (${SIZE})"

# --desktop 選項：複製到桌面
if [[ "$1" == "--desktop" ]]; then
    cp "${OUTPUT_PATH}" ~/Desktop/"${OUTPUT}"
    echo "✅ 已複製到桌面：~/Desktop/${OUTPUT}"
fi
