(function() {
    'use strict';
    // 1. 防止重複執行
    if (window.FCA_ZH_TW_TRANSLATOR_ACTIVE) return;
    window.FCA_ZH_TW_TRANSLATOR_ACTIVE = true;

    var translations = window.FCA_ZH_TW_DOM_TRANSLATIONS || {};
    var patterns = [
        { reg: /^Install (.*)$/i, rep: '安裝 $1' },
        { reg: /^Add (.*) to your device$/i, rep: '將 $1 新增至您的裝置' },
        { reg: /Look for "(.*)" or "(.*)"/i, rep: '尋找「$1」或「$2」' }
    ];

    function translate(text) {
        if (typeof text !== 'string') return null;
        var t = text.trim();
        if (!t || t.length < 2 || t.length > 300) return null;
        
        // 跳過 URL、路徑、代碼片段
        if (t.indexOf('/') === 0 || t.indexOf('http') === 0 || t.indexOf('{') === 0) return null;

        if (translations[t]) return translations[t];
        for (var i = 0; i < patterns.length; i++) {
            if (patterns[i].reg.test(t)) return t.replace(patterns[i].reg, patterns[i].rep);
        }
        return null;
    }

    function walk(node) {
        if (!node) return;
        if (node.nodeType === 3) {
            var result = translate(node.nodeValue);
            if (result) node.nodeValue = result;
        } else if (node.nodeType === 1) {
            var skip = ['SCRIPT', 'STYLE', 'TEXTAREA', 'CODE', 'PRE', 'SVG', 'CANVAS'];
            if (skip.indexOf(node.tagName) !== -1) return;
            var child = node.firstChild;
            while (child) {
                walk(child);
                child = child.nextSibling;
            }
        }
    }

    // 提高防抖延遲至 300ms，大幅降低執行頻率
    var timeout = null;
    var observer = new MutationObserver(function(mutations) {
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(function() {
            for (var i = 0; i < mutations.length; i++) {
                var m = mutations[i];
                if (m.addedNodes) {
                    for (var j = 0; j < m.addedNodes.length; j++) walk(m.addedNodes[j]);
                }
                if (m.type === 'characterData') walk(m.target);
            }
        }, 300);
    });

    function init() {
        if (!document.body) return;
        walk(document.body);
        observer.observe(document.body, { childList: true, subtree: true, characterData: true });
    }

    // 在頁面資源完全載入後才啟動 (最安全的方法)
    if (document.readyState === 'complete') {
        init();
    } else {
        window.addEventListener('load', init);
    }
})();
