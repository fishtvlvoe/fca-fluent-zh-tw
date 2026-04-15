(function() {
    var TRANSLATIONS = window.FCA_ZH_TW_DOM_TRANSLATIONS || {};
    var patterns = [
        { reg: /^Install (.*)$/i, rep: '安裝 $1' },
        { reg: /^Add (.*) to your device$/i, rep: '將 $1 新增至您的裝置' },
        { reg: /Look for "(.*)" or "(.*)"/i, rep: '尋找「$1」或「$2」' },
        { reg: /Open the browser menu/i, rep: '開啟瀏覽器選單' },
        { reg: /Follow the prompts to install/i, rep: '依照提示進行安裝' }
    ];

    function translate(text) {
        var t = text.trim();
        if (!t) return null;
        if (TRANSLATIONS[t]) return TRANSLATIONS[t];
        for (var i = 0; i < patterns.length; i++) {
            if (patterns[i].reg.test(t)) return t.replace(patterns[i].reg, patterns[i].rep);
        }
        return null;
    }

    function walk(node) {
        if (node.nodeType === 3) {
            var res = translate(node.nodeValue);
            if (res) node.nodeValue = res;
        } else if (node.nodeType === 1 && !/^(SCRIPT|STYLE|TEXTAREA)$/.test(node.tagName)) {
            node.childNodes.forEach(walk);
        }
    }

    var observer = new MutationObserver(function(ms) {
        ms.forEach(function(m) { m.addedNodes.forEach(walk); });
    });

    function start() {
        walk(document.body);
        observer.observe(document.body, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
    else start();
})();
