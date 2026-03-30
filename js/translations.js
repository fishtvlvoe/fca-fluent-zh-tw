/**
 * FCA & Fluent 繁體中文翻譯包 — JS DOM 翻譯字典
 *
 * 針對 Vue.js / React 直接渲染在 DOM 的英文字串，
 * 這些字串無法透過 WordPress gettext 系統或 wp_localize_script 覆蓋。
 *
 * 格式：{ '英文字串': '繁體中文翻譯' }
 * 匹配方式：完全比對文字節點內容（trimmed）
 */
window.FCA_ZH_TW_DOM_TRANSLATIONS = (function() {

    // ── FluentPlayer Pro（設定頁面）─────────────────────────────────────
    var fluentPlayer = {
        'Settings':                 '設定',
        'General Settings':         '一般設定',
        'General':                  '一般',
        'Branding':                 '品牌',
        'Integrations':             '整合',
        'Storage':                  '儲存空間',
        'YouTube':                  'YouTube',
        'Analytics':                '分析',
        'Export':                   '匯出',
        'Default Preset':           '預設樣式',
        'Select the default preset to use for new videos':
                                    '選擇用於新影片的預設樣式',
        'Default':                  '預設',
        'Modern':                   '現代',
        'Simple':                   '簡潔',
        'Standard':                 '標準',
        'Floating':                 '浮動',
        'Minimal':                  '極簡',
        'Ambient':                  '環境',
        'Default Aspect Ratio':     '預設畫面比例',
        'Default aspect ratio for all players (can be overridden per media)':
                                    '所有播放器的預設畫面比例（可針對個別媒體覆蓋）',
        'Original':                 '原始',
        'Auto Resume Playback':     '自動續播',
        'Let your users resume watching from where they left off':
                                    '讓使用者從上次中斷的地方繼續觀看',
        'Enable Auto Resume Playback':
                                    '啟用自動續播',
        'Custom CSS':               '自訂 CSS',
        'Save Settings':            '儲存設定',
    };

    // ── FluentCRM（儀表板 + 設定）─────────────────────────────────────
    var fluentCRM = {
        'Email Performance':        '電子郵件績效',
        '30 Days':                  '30 天',
        'Delivered':                '已送達',
        'Bounced':                  '已退回',
        'Monday':                   '星期一',
        'Tuesday':                  '星期二',
        'Wednesday':                '星期三',
        'Thursday':                 '星期四',
        'Friday':                   '星期五',
        'Saturday':                 '星期六',
        'Sunday':                   '星期日',
        "Looks like you don't have any campaigns now.":
                                    '目前沒有任何行銷活動。',
        'Add a Campaign':           '新增行銷活動',
        "Looks like you don't have any active automations now.":
                                    '目前沒有任何啟用中的自動化。',
        'Create an Automation':     '建立自動化',
        'Share Your Feedback':      '分享您的意見',
        "You're using a beta version of FluentCRM!":
                                    '您正在使用 FluentCRM 的測試版！',
        'Business Setup':           '商業設定',
        'Official name shown on invoices and emails.':
                                    '顯示在發票和電子郵件上的正式名稱。',
        'Logo':                     '標誌',
        'PNG/JPEG, min 400×400px. Shown on branded content.':
                                    'PNG/JPEG，最小 400×400px。顯示在品牌內容中。',
        'Admin Email Addresses':    '管理員電子郵件地址',
        'SMS Setting':              '簡訊設定',
        'AI Writing':               'AI 寫作',
        'System Admin Tools':       '系統管理工具',
    };

    // ── FCA Widgets（FluentCommunity Add-ons）─────────────────────────
    var fcaWidgets = {
        'FluentCommunity Add-ons':  'FluentCommunity 附加功能',
        'Welcome to FluentCommunity Add-ons!':
                                    '歡迎使用 FluentCommunity 附加功能！',
        'Enhance your FluentCommunity experience with these powerful add-ons.':
                                    '使用這些強大的附加功能來增強您的 FluentCommunity 體驗。',
        'Widget Manager':           '小工具管理器',
        'Create and manage custom widgets for your FluentCommunity portal.':
                                    '為您的 FluentCommunity 入口建立和管理自訂小工具。',
        'Sidebar Menu':             '側邊欄選單',
        'Customize the sidebar menu in your FluentCommunity portal.':
                                    '自訂 FluentCommunity 入口的側邊欄選單。',
        'Need Help?':               '需要協助？',
        'Visit our website for documentation and support.':
                                    '請造訪我們的網站取得文件和支援。',
        'Manage Widgets':           '管理小工具',
        'Manage Sidebar Menu':      '管理側邊欄選單',
        'Visit Website':            '造訪網站',
        'Migration Info':           '遷移資訊',
    };

    // ── FCE Shortcodes Settings ────────────────────────────────────────
    var fceShortcodes = {
        'FCE Shortcodes Settings':  'FCE 短代碼設定',
        // 'General Settings' 已在 fluentPlayer 定義，共用
        'Configure the general settings for FCE Shortcodes.':
                                    '設定 FCE 短代碼的一般選項。',
        'Enable Course Search':     '啟用課程搜尋',
        'Enable the [fce_course_search] shortcode':
                                    '啟用 [fce_course_search] 短代碼',
        'Courses Per Page':         '每頁課程數',
        'Number of courses to display per page':
                                    '每頁顯示的課程數量',
        'Show Course Description':  '顯示課程說明',
        'Show Course Image':        '顯示課程圖片',
        'Show Course Price':        '顯示課程價格',
        'Show Course Author':       '顯示課程作者',
        'Show Course Categories':   '顯示課程分類',
        'Portal URL':               '入口網址',
        'Portal Slug':              '入口網址代稱',
        'Import Demo Content':      '匯入範例內容',
    };

    // ── FCA Boards（看板管理）─────────────────────────────────────────
    var fcaBoards = {
        'Kanban-style boards for your Fluent Community':
                                    'Fluent Community 的看板式管理工具',
        'Total Boards':             '看板總數',
        'Total Ideas':              '點子總數',
        'Total Votes':              '投票總數',
        '+ New Board':              '+ 新增看板',
        'IDEAS':                    '點子',
        'VOTES':                    '投票',
        'COLUMNS':                  '欄位',
        'Edit':                     '編輯',
        'View':                     '查看',
        'DEFAULT':                  '預設',
        'ACTIVE':                   '啟用中',
        'PUBLIC':                   '公開',
    };

    // ── FCA Display Name Override Settings ──────────────────────────────
    var fcaDisplayName = {
        'FCA Display Name Override Settings':
                                    'FCA 顯示名稱覆蓋設定',
        'Enable Display Name Override':
                                    '啟用顯示名稱覆蓋',
        'Enable custom display name pattern':
                                    '啟用自訂顯示名稱模式',
        'When enabled, all user display names will use the pattern you select below.':
                                    '啟用後，所有使用者的顯示名稱將使用您在下方選擇的模式。',
        'Display Name Pattern':     '顯示名稱模式',
        'Username':                 '使用者名稱',
        'First Name Only':          '僅名字',
        'First Name + Last Name':   '名字 + 姓氏',
        'Custom Format':            '自訂格式',
        'Define your own pattern using tokens below':
                                    '使用下方的代碼自訂您的模式',
        'Debug Mode':               '除錯模式',
        'Enable debug logging':     '啟用除錯日誌',
        'When enabled, plugin activity will be logged to the WordPress debug log':
                                    '啟用後，外掛活動將記錄到 WordPress 除錯日誌',
        'Token Library':            '代碼庫',
        'Click a token to insert it into the custom format field.':
                                    '點擊代碼將其插入自訂格式欄位。',
        'STANDARD':                 '標準',
        'INITIALS':                 '縮寫',
        'RANDOM NUMBERS':           '隨機數字',
        'WORDPRESS USER META':      'WordPress 使用者中繼資料',
        'Actions':                  '操作',
        'Test My Display Name':     '測試我的顯示名稱',
        'Updates your own display name to test the pattern.':
                                    '更新您自己的顯示名稱以測試模式。',
        'Update All Existing Users':'更新所有現有使用者',
        'Apply the current pattern to every user in the community.':
                                    '將目前的模式套用到社群中的所有使用者。',
        'How It Works':             '運作方式',
        'Intercepts profile data before saving':
                                    '在儲存前攔截個人資料',
        'First Name':               '名字',
        'Last Name':                '姓氏',
        'Nickname':                 '暱稱',
        'Bio':                      '簡介',
    };

    // ── FCA PWA（Analytics + Service Worker UI）────────────────────────
    var fcaPwa = {
        'PWA Analytics':            'PWA 分析',
        'Event tracked':            '事件已追蹤',
        'Event type is required':   '事件類型為必填',
        'Failed to track event':    '追蹤事件失敗',
        'Add to Home Screen':       '加到主畫面',
        'Tap the Share button':     '點擊分享按鈕',
        'Scroll down and tap Add to Home Screen':
                                    '向下滑動並點擊「加到主畫面」',
        'Tap Add to install':       '點擊「加入」以安裝',
        'The app will appear on your home screen just like a regular app.':
                                    '應用程式將會出現在您的主畫面上，就像一般的 App 一樣。',
        'Add to your device':       '加入您的裝置',
        'Open the browser menu':    '開啟瀏覽器選單',
        'Follow the prompts to install':
                                    '按照提示完成安裝',
        'You are back online — refreshing...':
                                    '您已重新上線 — 正在重新整理...',
        'You are offline':          '您目前離線',
        'A new version of this app is available.':
                                    '此應用程式有新版本可用。',
        'Update Now':               '立即更新',
    };

    // ── FCA Comments ──────────────────────────────────────────────────
    var fcaComments = {
        'Community Discussion':     '社群討論',
        'Join the Discussion':      '加入討論',
        'FCA Comments':             'FCA 留言',
    };

    // 合併所有字典（後面的同 key 會覆蓋前面）
    var merged = {};
    var sources = [fluentPlayer, fluentCRM, fcaWidgets, fceShortcodes, fcaBoards, fcaDisplayName, fcaPwa, fcaComments];
    for (var i = 0; i < sources.length; i++) {
        var src = sources[i];
        for (var k in src) {
            if (Object.prototype.hasOwnProperty.call(src, k)) {
                merged[k] = src[k];
            }
        }
    }

    return merged;
})();
