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
        'Copied!':                  '已複製！',
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

    // ── FCA PWA（設定 + Analytics + Service Worker UI）─────────────────
    var fcaPwa = {
        // 設定頁面 tabs
        'Manifest':                 'Manifest',
        'Install Prompt':           '安裝提示',
        'Advanced':                 '進階',
        'Diagnostics':              '診斷',
        'ACTIVE':                   '啟用中',
        'Progressive Web App for Fluent Community':
                                    'Fluent Community 漸進式網頁應用程式',
        // 基本設定
        'Basic Configuration':      '基本設定',
        'Configure the basic settings for your Progressive Web App':
                                    '設定漸進式網頁應用程式的基本選項',
        'Setup Progress':           '設定進度',
        'Enable PWA functionality': '啟用 PWA 功能',
        'Set App Name':             '設定應用程式名稱',
        'Upload App Icon':          '上傳應用程式圖示',
        'Generate Manifest File':   '產生 Manifest 檔案',
        'Enable Progressive Web App functionality for your community':
                                    '為您的社群啟用漸進式網頁應用程式功能',
        'The full name of your app as it appears to users':
                                    '使用者看到的應用程式完整名稱',
        'Hide for me':              '為我隱藏',
        'Hide for all':             '為所有人隱藏',
        'Run Diagnostics':          '執行診斷',
        // Analytics 頁面
        'PWA Analytics':            'PWA 分析',
        'Track installation, usage, and engagement metrics for your Progressive Web App':
                                    '追蹤漸進式網頁應用程式的安裝、使用和互動指標',
        'Total Installs':           '總安裝數',
        'Unique Users':             '不重複使用者',
        'Total Events':             '總事件數',
        'Conversion Rate':          '轉換率',
        'Last 30 Days':             '最近 30 天',
        'Events by Type':           '依類型分類的事件',
        'Events Timeline':          '事件時間軸',
        'No event data yet':        '尚無事件資料',
        'No timeline data yet':     '尚無時間軸資料',
        'How Analytics Work':       '分析運作方式',
        'PWA Analytics automatically tracks user interactions including install prompt views, installation completions, dismissals, and app usage. Data is collected anonymously and stored locally in your WordPress database.':
                                    'PWA 分析會自動追蹤使用者互動，包括安裝提示瀏覽次數、安裝完成次數、關閉次數和應用程式使用情況。資料以匿名方式收集，並儲存在您的 WordPress 資料庫中。',
        // Service Worker UI
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
        'FCA Comments Settings':    'FCA 留言設定',
        'Connect WordPress content to Fluent Community spaces.':
                                    '將 WordPress 內容連結至 Fluent Community 空間。',
        'Assignment Rules':         '指派規則',
        'Appearance':               '外觀',
        'Legacy & Advanced':        '舊版與進階',
        'Use rules to automatically assign posts to specific spaces based on multiple conditions.':
                                    '使用規則根據多個條件自動將文章指派到特定空間。',
        'No rules created yet. Click the button below to add your first rule.':
                                    '尚未建立任何規則。點擊下方按鈕新增您的第一條規則。',
        'Add New Rule':             '新增規則',
        'Save All Settings':        '儲存所有設定',
        'Save Changes':             '儲存變更',
        'Community Discussion':     '社群討論',
        'Join the Discussion':      '加入討論',
        'FCA Comments':             'FCA 留言',
    };

    // ── FCA Hub（附加元件管理器）──────────────────────────────────────
    var fcaHub = {
        // hub-admin.js 硬編碼字串（按鈕狀態 + 訊息）
        'Please enter a license key':   '請輸入授權金鑰',
        'Activating...':                '啟用中...',
        'License activated successfully!':
                                        '授權已成功啟用！',
        'License activation failed':    '授權啟用失敗',
        'Request failed. Please try again.':
                                        '請求失敗，請再試一次。',
        'Activate License':             '啟用授權',
        'Are you sure you want to deactivate your license?':
                                        '確定要停用您的授權嗎？',
        'Deactivating...':              '停用中...',
        'License deactivation failed':  '授權停用失敗',
        'Deactivate License':           '停用授權',
        'License status checked successfully':
                                        '授權狀態已成功檢查',
        'Failed to check license status':
                                        '檢查授權狀態失敗',
        'Checking...':                  '檢查中...',
        'Check License Status':         '檢查授權狀態',
        'Installing...':                '安裝中...',
        'Installing addon...':          '安裝附加元件中...',
        'Addon installed successfully!':'附加元件已成功安裝！',
        'Installation failed':          '安裝失敗',
        'Install':                      '安裝',
        'Activating addon...':          '啟用附加元件中...',
        'Deactivating addon...':        '停用附加元件中...',
        'Addon status updated':         '附加元件狀態已更新',
        'Active':                       '已啟用',
        'Inactive':                     '未啟用',
        'Failed to update addon status':'更新附加元件狀態失敗',
        'Refreshing...':                '重新整理中...',
        'Registry refreshed successfully!':
                                        '登錄檔已成功重新整理！',
        'Failed to refresh registry':   '重新整理登錄檔失敗',
        'Checking for updates...':      '檢查更新中...',
        'All addons are up to date!':   '所有附加元件均已是最新版本！',
        'Failed to check for updates':  '檢查更新失敗',
        'Updating...':                  '更新中...',
        'Updating addon...':            '更新附加元件中...',
        'Update installed successfully!':
                                        '更新已成功安裝！',
        'Update failed':                '更新失敗',
        'Update Available':             '有可用更新',
        'Deleting addon...':            '刪除附加元件中...',
        'Addon deleted successfully!':  '附加元件已成功刪除！',
        'Deletion failed':              '刪除失敗',
        'Enabled':                      '已啟用',
        'Disabled':                     '已停用',
    };

    // ── FCA Global Search（社群搜尋頁面）─────────────────────────────
    var fcaGlobalSearch = {
        // search-page.js Vue template 硬編碼字串
        'Search':                       '搜尋',
        'Search posts, comments, spaces, courses, lessons, members, events, blogs, pages, and docs...':
                                        '搜尋貼文、留言、空間、課程、課節、成員、活動、部落格、頁面和文件...',
        'All Results':                  '全部結果',
        'Posts':                        '貼文',
        'Comments':                     '留言',
        'Spaces':                       '空間',
        'Courses':                      '課程',
        'Lessons':                      '課節',
        'Members':                      '成員',
        'Events':                       '活動',
        'Blogs':                        '部落格',
        'Pages':                        '頁面',
        'Docs':                         '文件',
        'Search filters will appear here':
                                        '搜尋篩選器將顯示於此',
        'Searching...':                 '搜尋中...',
        'Start searching':              '開始搜尋',
        'Search for posts, comments, spaces, courses, lessons, members, events, blogs, and pages':
                                        '搜尋貼文、留言、空間、課程、課節、成員、活動、部落格和頁面',
        'Public Space':                 '公開空間',
        'Private Space':                '私密空間',
        'No results found':             '找不到結果',
        'Try adjusting your search terms or check the spelling.':
                                        '請嘗試調整搜尋關鍵字或確認拼寫是否正確。',
        'result found':                 '個結果',
        'results found':                '個結果',
    };

    // ── FCA Content Manager（內容管理員）────────────────────────────
    var fcaContentManager = {
        // admin.js 硬編碼字串
        'Choose Thumbnail Image':       '選擇縮圖',
        'Use this image':               '使用此圖片',
        'Manage community members, handle enrollments, and control user access across spaces and courses.':
                                        '管理社群成員、處理報名，以及控制各空間和課程的使用者存取。',
        'View and edit all metadata entries across your community.':
                                        '查看並編輯整個社群的所有中繼資料條目。',
        'Manage spaces with full editing capabilities and bulk operations.':
                                        '以完整編輯功能和批次操作管理空間。',
        'Export your content for backup, migration, or analysis.':
                                        '匯出內容以進行備份、遷移或分析。',
        'Import content from CSV and JSON files with advanced mapping options.':
                                        '從 CSV 和 JSON 檔案匯入內容，提供進階對應選項。',
        'This feature requires a Pro license. Upgrade to edit courses!':
                                        '此功能需要 Pro 授權。請升級以編輯課程！',
        'Course editing requires a Pro license!':
                                        '課程編輯需要 Pro 授權！',
        'Please select a table for export':
                                        '請選擇要匯出的表格',
        'Show Logs':                    '顯示日誌',
        'Hide Logs':                    '隱藏日誌',
        'Import failed.':               '匯入失敗。',
        'AJAX import failed. Check console for details.':
                                        'AJAX 匯入失敗，請查看主控台以了解詳情。',
        'Uploading...':                 '上傳中...',
        'Import completed successfully!':
                                        '匯入已成功完成！',
        'Failed to load statistics':    '載入統計資料失敗',
        'Posts':                        '貼文',
        'Comments':                     '留言',
        'Courses':                      '課程',
        'Lessons':                      '課節',
        'Course lessons':               '課程課節',
        'Members':                      '成員',
        'Reactions':                    '反應',
        'Media Files':                  '媒體檔案',
        'Images, videos, and files':    '圖片、影片和檔案',
        'Spaces':                       '空間',
        'Are you sure you want to delete this item?':
                                        '確定要刪除此項目嗎？',
        'Are you sure you want to delete this media file?':
                                        '確定要刪除此媒體檔案嗎？',
        'Untitled':                     '未命名',
        'Untitled Course':              '未命名課程',
        'Unknown':                      '未知',
        'Are you sure you want to delete this metadata?':
                                        '確定要刪除此中繼資料嗎？',
        'No courses found':             '找不到課程',
        'No media files found':         '找不到媒體檔案',
        'Never':                        '從不',
        'Yes':                          '是',
        'No':                           '否',
        'Failed to load course structure':
                                        '載入課程結構失敗',
        'Order updated':                '順序已更新',
        'Item deleted':                 '項目已刪除',
        'Failed to delete item':        '刪除項目失敗',
        'Item duplicated':              '項目已複製',
        'Failed to duplicate item':     '複製項目失敗',
        'Move this section and all its lessons to another course.':
                                        '將此章節及其所有課節移至另一個課程。',
        'Move this lesson to another course or section.':
                                        '將此課節移至另一個課程或章節。',
        'Please select a target course':'請選擇目標課程',
        'Item moved successfully':      '項目已成功移動',
        'Failed to move item':          '移動項目失敗',
        'No course selected':           '未選擇課程',
        'Course updated successfully':  '課程已成功更新',
        'Failed to update metadata':    '更新中繼資料失敗',
        'Metadata not found':           '找不到中繼資料',
        'Invalid metadata ID':          '無效的中繼資料 ID',
        'Metadata updated successfully':'中繼資料已成功更新',
        'Failed to delete media':       '刪除媒體失敗',
        'Failed to delete media files': '刪除媒體檔案失敗',
        'Failed to load media details': '載入媒體詳情失敗',
        'Media deleted successfully':   '媒體已成功刪除',
        'Topic added':                  '主題已新增',
        'Failed to add topic':          '新增主題失敗',
        'Topic removed':                '主題已移除',
        'Failed to remove topic':       '移除主題失敗',
        'Failed to load comment':       '載入留言失敗',
        'Failed to update posts':       '更新貼文失敗',
        // import-export.js 字串
        'Please select a content type to export.':
                                        '請選擇要匯出的內容類型。',
        'None':                         '無',
        'Export failed.':               '匯出失敗。',
        'An error occurred: ':          '發生錯誤：',
        'Please upload a JSON or CSV file.':
                                        '請上傳 JSON 或 CSV 檔案。',
        'File size exceeds 50MB limit.':'檔案大小超過 50MB 限制。',
        'Please upload a file to continue.':
                                        '請上傳檔案以繼續。',
        'Export file created successfully.':
                                        '匯出檔案已成功建立。',
        'items exported.':              '個項目已匯出。',
    };

    // ── FCA Multi-Reactions（多表情反應設定）───────────────────────────
    var fcaMultiReactions = {
        // admin.js 硬編碼字串
        'Please enable at least one reaction type.':
                                        '請至少啟用一種反應類型。',
        'Multi-Reactions settings saved successfully!':
                                        '多反應設定已成功儲存！',
        'Are you sure you want to reset all Multi-Reactions settings to defaults?\n\nThis will:\n• Delete all current settings\n• Recreate the database option\n• Reset all reactions to default values\n• Disable multi-reactions\n\nThis action cannot be undone.':
                                        '確定要將所有多反應設定重設為預設值嗎？\n\n此操作將：\n• 刪除所有目前的設定\n• 重新建立資料庫選項\n• 將所有反應重設為預設值\n• 停用多反應功能\n\n此操作無法復原。',
        'Reaction Name':                '反應名稱',
        'Emoji':                        '表情符號',
        'Delete this reaction':         '刪除此反應',
    };

    // ── FCE Quick Fixes（快速修復設定頁，PHP echo 硬編碼）────────────
    var fceQuickFixes = {
        // class-settings-page.php create_admin_page() 中的字串
        'FCE Quick Fixes Settings':     'FCE 快速修復設定',
        'Available Quick Fixes':        '可用的快速修復',
        'Enable this fix':              '啟用此修復',
        'Custom Code':                  '自訂程式碼',
        'Header Code':                  '標頭程式碼',
        'Add custom code to the <head> section (perfect for Google Analytics, meta tags, etc.)':
                                        '在 <head> 區塊新增自訂程式碼（適合 Google Analytics、meta 標籤等）',
        'Custom CSS':                   '自訂 CSS',
        'Add custom CSS to be applied to the community portal.':
                                        '新增自訂 CSS 套用至社群入口網站。',
        'Custom JavaScript':            '自訂 JavaScript',
        'Add custom JavaScript to be applied to the community portal.':
                                        '新增自訂 JavaScript 套用至社群入口網站。',
    };

    // ── FCA Course Blocks（影片播放器）──────────────────────────────
    var fcaCourseBlocks = {
        // player.js 中 buildExternalChapters() 硬編碼字串
        'Chapters':                     '章節',
        'No video configured. Please add a video URL in the editor.':
                                        '尚未設定影片，請在編輯器中新增影片網址。',
    };

    // ── FCA Events Spaces（空間活動小工具）──────────────────────────
    var fcaEventsSpaces = {
        'Loading...':                   '載入中...',
        'Error loading events':         '載入活動時發生錯誤',
        'No upcoming events':           '沒有即將舉行的活動',
        'Upcoming Events':              '即將舉行的活動',
    };

    // ── FCHub Multi-Currency（後台設定頁）───────────────────────────────
    var fchubMultiCurrency = {
        // 頁籤
        'General':                          '一般',
        'Currencies':                       '幣別',
        'Exchange Rates':                   '匯率',
        'Switcher':                         '切換器',
        'Checkout':                         '結帳',
        'CRM':                              'CRM',
        'Diagnostics':                      '診斷',
        // 一般設定
        'Multi-Currency':                   '多幣別',
        'Multi-Currency Enabled':           '啟用多幣別',
        'Enable Multi-Currency':            '啟用多幣別',
        'Master switch for display-layer multi-currency across the store.':
                                            '全站顯示層多幣別的主開關。',
        'Base Currency':                    '基準幣別',
        'Base currency currently in use.':  '目前使用中的基準幣別。',
        'Default Display Currency':         '預設顯示幣別',
        'Currency shown to visitors before any preference is detected.':
                                            '在偵測到訪客偏好前所顯示的幣別。',
        'URL Parameter':                    'URL 參數',
        'Allow currency switching via URL (e.g. ?currency=EUR).':
                                            '允許透過 URL 切換幣別（例如 ?currency=EUR）。',
        'URL Parameter Key':                'URL 參數名稱',
        'Cookie Persistence':               'Cookie 持久化',
        'Remember visitor currency preference in browser cookies.':
                                            '在瀏覽器 Cookie 中記住訪客的幣別偏好。',
        'Cookie Lifetime (days)':           'Cookie 有效期（天）',
        'Account Persistence':              '帳號持久化',
        'Geo Detection':                    '地理位置偵測',
        'Stale Fallback':                   '匯率過期備援',
        'Uninstall':                        '解除安裝',
        'Keep data':                        '保留資料',
        'Delete all':                       '刪除所有資料',
        // 匯率
        'Provider':                         '供應商',
        'European Central Bank (free)':     '歐洲中央銀行（免費）',
        'ExchangeRate-API (free tier)':     'ExchangeRate-API（免費方案）',
        'Open Exchange Rates':              'Open Exchange Rates',
        'Manual rates':                     '手動匯率',
        'API Key':                          'API 金鑰',
        'Rate':                             '匯率',
        'Rate value':                       '匯率數值',
        'Rates refreshed.':                 '匯率已更新。',
        'Rate refresh failed.':             '匯率更新失敗。',
        'Rounding':                         '四捨五入',
        'Rounding mode':                    '進位模式',
        'Round half up (standard)':         '四捨五入（標準）',
        'Round half down':                  '五捨六入',
        'Always round up':                  '無條件進位',
        'Always round down':                '無條件捨去',
        'Freshness badge':                  '更新時效標記',
        'Stale threshold':                  '過期門檻',
        'Fetched':                          '已取得',
        'Quote':                            '報價',
        // 幣別清單操作
        'Drag to reorder':                  '拖曳以重新排序',
        'Select currency':                  '選擇幣別',
        'Comma (,)':                        '逗號（,）',
        'Dot (.)':                          '句點（.）',
        'Decimals':                         '小數位數',
        'Separator':                        '分隔符號',
        'Position':                         '位置',
        'Symbol':                           '符號',
        // 切換器設定
        'Preset':                           '預設樣式',
        'Pill':                             '膠囊形',
        'Minimal':                          '極簡',
        'Glass':                            '玻璃質感',
        'Contrast':                         '對比',
        'Size':                             '大小',
        'Small':                            '小',
        'Medium':                           '中',
        'Large':                            '大',
        'Auto':                             '自動',
        'Dropdown direction':               '下拉方向',
        'Auto direction':                   '自動方向',
        'Up':                               '向上',
        'Down':                             '向下',
        'Dropdown position':                '下拉位置',
        'Auto position':                    '自動位置',
        'Start':                            '開頭',
        'End':                              '結尾',
        'Label position':                   '標籤位置',
        'Before':                           '之前',
        'After':                            '之後',
        'Above':                            '上方',
        'Below':                            '下方',
        'Inline search':                    '內嵌搜尋',
        'Auto width':                       '自動寬度',
        'Show base currency':               '顯示基準幣別',
        'Show favorites first':             '優先顯示常用幣別',
        'Show active indicator':            '顯示啟用指示器',
        'Show context note':                '顯示說明文字',
        'Codes':                            '代碼',
        'Names':                            '名稱',
        'Flags':                            '國旗',
        // 結帳
        'Checkout context':                 '結帳說明',
        'Full note':                        '完整說明',
        // CRM
        'FluentCRM Sync':                   'FluentCRM 同步',
        'Tag contacts in FluentCRM based on their currency preference.':
                                            '依據幣別偏好在 FluentCRM 中為聯絡人加上標籤。',
        // 狀態 / 訊息
        'Enabled':                          '已啟用',
        'Disabled':                         '已停用',
        'Active':                           '啟用',
        'Active check':                     '狀態確認',
        'Save':                             '儲存',
        'Saving...':                        '儲存中...',
        'Settings saved.':                  '設定已儲存。',
        'Failed to load settings.':         '載入設定失敗。',
        'Failed to save settings.':         '儲存設定失敗。',
        'Failed to load exchange rates.':   '載入匯率失敗。',
        'Failed to load diagnostics.':      '載入診斷資料失敗。',
        'Network error. Please check your connection.':
                                            '網路錯誤，請確認您的連線狀態。',
        'Missing':                          '缺少',
        'Not found':                        '找不到',
        'On':                               '開',
        'Off':                              '關',
        'Yes':                              '是',
        'No':                               '否',
        'None':                             '無',
        'Base':                             '基準',
        'Flag':                             '國旗',
        'Name':                             '名稱',
    };

    // 合併所有字典（後面的同 key 會覆蓋前面）
    var merged = {};
    var sources = [
        fluentPlayer, fluentCRM, fcaWidgets, fceShortcodes, fcaBoards,
        fcaDisplayName, fcaPwa, fcaComments,
        fcaHub, fcaGlobalSearch, fcaContentManager, fcaMultiReactions,
        fceQuickFixes, fcaCourseBlocks, fcaEventsSpaces,
        fchubMultiCurrency
    ];
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
