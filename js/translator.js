/**
 * FCA & Fluent 繁體中文翻譯包 — JS DOM 文字替換注入器
 *
 * 解決 Vue.js / React 渲染的後台頁面英文字串無法透過 gettext 翻譯的問題。
 * 策略：
 *   1. 偵測目前頁面屬於哪個外掛後台
 *   2. 使用 MutationObserver 監聽 DOM 變化（Vue/React 動態渲染）
 *   3. 對完全匹配的文字節點執行替換
 *   4. 使用 debounce 避免過度觸發
 *
 * 注意：
 *   - 只替換完全匹配的文字節點，不碰 HTML 屬性
 *   - 不修改任何原始外掛的代碼
 *   - 只在相關外掛頁面載入（由 PHP 控制）
 */
(function() {
    'use strict';

    // 依賴 translations.js 中定義的字典
    var TRANSLATIONS = window.FCA_ZH_TW_DOM_TRANSLATIONS;
    if (!TRANSLATIONS || typeof TRANSLATIONS !== 'object') {
        return;
    }

    // ── 頁面偵測 ──────────────────────────────────────────────────────
    // PHP 端透過 wp_add_inline_script 注入目前頁面所屬的外掛識別符
    var PAGE_CONTEXT = window.FCA_ZH_TW_PAGE_CONTEXT || 'all';

    // ── 工具函式 ──────────────────────────────────────────────────────

    /**
     * debounce：在 delay 毫秒內若再次呼叫，重設計時器
     * @param {Function} fn
     * @param {number} delay
     */
    function debounce(fn, delay) {
        var timer = null;
        return function() {
            if (timer) clearTimeout(timer);
            timer = setTimeout(fn, delay);
        };
    }

    /**
     * 判斷節點是否應該跳過（script / style / input 等）
     * @param {Node} node
     */
    function shouldSkipNode(node) {
        if (node.nodeType !== Node.ELEMENT_NODE) return false;
        var tag = node.tagName ? node.tagName.toUpperCase() : '';
        var skipTags = ['SCRIPT', 'STYLE', 'TEXTAREA', 'INPUT', 'SELECT', 'NOSCRIPT', 'IFRAME'];
        return skipTags.indexOf(tag) !== -1;
    }

    /**
     * 對單一文字節點嘗試翻譯
     * 只比對 trimmed 後完全相等的文字，不做部分替換
     * @param {Text} textNode
     */
    function translateTextNode(textNode) {
        var raw = textNode.nodeValue;
        var trimmed = raw.trim();

        if (!trimmed) return;

        var translated = TRANSLATIONS[trimmed];
        if (translated && translated !== trimmed) {
            // 保留原始的前後空白（縮排、換行等）
            var leading  = raw.match(/^(\s*)/)[1];
            var trailing = raw.match(/(\s*)$/)[1];
            textNode.nodeValue = leading + translated + trailing;
        }
    }

    /**
     * 遞迴走訪 DOM 樹，對所有文字節點執行翻譯
     * @param {Node} root
     */
    function walkAndTranslate(root) {
        if (!root) return;

        // 跳過不需要翻譯的元素
        if (shouldSkipNode(root)) return;

        // 文字節點直接翻譯
        if (root.nodeType === Node.TEXT_NODE) {
            translateTextNode(root);
            return;
        }

        // 遞迴子節點
        var children = root.childNodes;
        for (var i = 0; i < children.length; i++) {
            walkAndTranslate(children[i]);
        }
    }

    // ── 主執行區域 ────────────────────────────────────────────────────

    // 初次掃描：頁面初始渲染完成後執行
    function initialScan() {
        var adminContent = document.getElementById('wpbody-content') || document.body;
        walkAndTranslate(adminContent);
    }

    // MutationObserver 回調（debounced，避免 Vue/React 頻繁重渲染時過度觸發）
    var debouncedScan = debounce(function() {
        var adminContent = document.getElementById('wpbody-content') || document.body;
        walkAndTranslate(adminContent);
    }, 150);

    // 設定 MutationObserver，監聽後台主要內容區的 DOM 變化
    function setupObserver() {
        var target = document.getElementById('wpbody-content') || document.body;

        var observer = new MutationObserver(function(mutations) {
            var needsScan = false;
            for (var i = 0; i < mutations.length; i++) {
                var mutation = mutations[i];
                // 新增節點，或文字內容直接變更
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    needsScan = true;
                    break;
                }
                if (mutation.type === 'characterData') {
                    translateTextNode(mutation.target);
                    // characterData 單獨處理，不需要全掃
                }
            }
            if (needsScan) {
                debouncedScan();
            }
        });

        observer.observe(target, {
            childList:  true,   // 監聽子節點的增刪
            subtree:    true,   // 監聽所有後代節點
            characterData: true // 監聽文字節點內容變更
        });
    }

    // DOM ready 後啟動
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initialScan();
            setupObserver();
        });
    } else {
        // 已經是 interactive / complete
        initialScan();
        setupObserver();
    }

})();
