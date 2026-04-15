/**
 * FCA & Fluent 繁體中文翻譯包 — 強化版 JS DOM 翻譯引擎 (智慧匹配版)
 */
(function() {
    var translations = window.FCA_ZH_TW_DOM_TRANSLATIONS || {};
    
    // 智慧匹配規則 (對應動態字串)
    var smartRules = [
        { regex: /^Install (.*)$/i, replace: "安裝 $1" },
        { regex: /^Add (.*) to your device$/i, replace: "將 $1 新增至您的裝置" },
        { regex: /Look for "(.*)" or "(.*)"/i, replace: "尋找「$1」或「$2」" },
        { regex: /^Open the browser menu/i, replace: "開啟瀏覽器選單" },
        { regex: /^Follow the prompts to install/i, replace: "依照提示進行安裝" }
    ];

    function translateText(text) {
        var trimmed = text.trim();
        if (!trimmed) return null;

        // 1. 精確匹配
        if (translations[trimmed]) return translations[trimmed];

        // 2. 智慧匹配 (處理動態拼接字串)
        for (var i = 0; i < smartRules.length; i++) {
            if (smartRules[i].regex.test(trimmed)) {
                return trimmed.replace(smartRules[i].regex, smartRules[i].replace);
            }
        }
        return null;
    }

    function walk(node) {
        if (node.nodeType === 3) { // 文字節點
            var translated = translateText(node.nodeValue);
            if (translated) node.nodeValue = node.nodeValue.replace(node.nodeValue.trim(), translated);
        } else if (node.nodeType === 1 && node.childNodes && node.tagName !== "SCRIPT" && node.tagName !== "STYLE") {
            for (var i = 0; i < node.childNodes.length; i++) {
                walk(node.childNodes[i]);
            }
        }
    }

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                walk(node);
            });
        });
    });

    function init() {
        walk(document.body);
        observer.observe(document.body, { childList: true, subtree: true, characterData: true });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
