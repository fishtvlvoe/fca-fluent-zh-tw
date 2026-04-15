/**
 * FCA & Fluent 繁體中文翻譯包 — 智慧型 JS DOM 翻譯引擎
 */
(function() {
    var translations = window.FCA_ZH_TW_DOM_TRANSLATIONS || {};
    
    var patterns = [
        { regex: /^Install (.*)$/, replace: '安裝 $1' },
        { regex: /^Add (.*) to your device$/, replace: '將 $1 新增至您的裝置' },
        { regex: /Look for "(.*)" or "(.*)"/, replace: '尋找「$1」或「$2」' },
        { regex: /Follow the prompts to install/, replace: '依照提示進行安裝' },
        { regex: /Open the browser menu/, replace: '開啟瀏覽器選單' }
    ];

    function translateText(text) {
        text = text.trim();
        if (!text) return null;
        if (translations[text]) return translations[text];
        for (var i = 0; i < patterns.length; i++) {
            if (patterns[i].regex.test(text)) {
                return text.replace(patterns[i].regex, patterns[i].replace);
            }
        }
        return null;
    }

    function walk(node) {
        if (node.nodeType === 3) {
            var translated = translateText(node.nodeValue);
            if (translated) node.nodeValue = translated;
        } else if (node.nodeType === 1 && node.childNodes) {
            for (var i = 0; i < node.childNodes.length; i++) {
                walk(node.childNodes[i]);
            }
        }
    }

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) { walk(node); });
        });
    });

    function init() {
        if (!document.body) return;
        walk(document.body);
        observer.observe(document.body, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
