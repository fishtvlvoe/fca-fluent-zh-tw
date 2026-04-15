<?php
/**
 * Plugin Name: FCA & Fluent 繁體中文翻譯包
 * Plugin URI: https://aiver.me
 * Description: 為所有 FCA 系列與 Fluent 系列外掛提供繁體中文翻譯，不修改原始外掛檔案，更新外掛不受影響。
 * Version: 1.6.6
 * Author: BuyGo
 * License: GPL v2 or later
 * Text Domain: fca-fluent-zh-tw
 */

if (!defined('ABSPATH')) {
    exit;
}

// GitHub 自動更新器
require_once __DIR__ . '/updater.php';
new FCA_Fluent_ZhTW_Updater(__FILE__, '1.6.6');

/**
 * 載入翻譯檔
 *
 * 原理：WordPress 的 load_textdomain() 是「先載入的優先」，
 * 我們在原始外掛載入自己的翻譯之前，先把我們的版本載入，
 * 這樣即使原始外掛更新覆蓋了它自己的翻譯檔，我們的版本仍然生效。
 */
class FCA_Fluent_ZhTW {

    /**
     * 所有支援的 text domain 清單
     */
    private static $domains = [
        // FCA 系列
        'fca-boards',
        'fca-comments',
        'fca-content-manager',
        'fca-course-blocks',
        'fca-display-name',
        'fca-events',
        'fca-events-basic',
        'fca-events-spaces',
        'fca-global-search',
        'fca-hub',
        'fca-knowledgebase',
        'fca-multi-reactions',
        'fca-pages',
        'fca-push-notifications',
        'fca-pwa',
        'fca-widgets',
        'fce-quick-fixes',
        'fce-shortcodes',
        // Fluent 系列
        'fluent-booking',
        'fluent-booking-pro',
        'fluent-cart',
        'fluent-cart-elementor-blocks',
        'fluent-cart-pro',
        'fluent-community',
        'fluent-community-pro',
        'fluent-crm',
        'fluent-messaging',
        'fluent-player',
        'fluent-player-pro',
        'fluent-security',
        'fluent-smtp',
        'fluent-toolkit',
        'fluentcampaign-pro',
        'fluentform',
        'fluentform-block',
        'fluentformpro',
        'fluentforms-pdf',
        'fluentform-pdf',
        // FCHub 系列
        'fchub-memberships',
        'fchub-multi-currency',
        'fchub-portal-extender',
        'fchub-wishlist',
    ];

    /**
     * 翻譯檔目錄
     */
    private static $lang_dir;

    public static function init() {
        self::$lang_dir = plugin_dir_path(__FILE__) . 'languages';

        // 在外掛載入前搶先載入翻譯（priority 1，比一般外掛的 init 更早）
        add_action('plugins_loaded', [__CLASS__, 'load_translations'], 1);

        // fca-events JS i18n 修復
        // fca-events 在 fluent_community/portal_head priority 10 注入 fcaEventsI18n
        // 我們用 priority 99 在它之後補上缺失字串
        add_action('fluent_community/portal_head', [__CLASS__, 'fix_fca_events_js_i18n'], 99);

        // fca-push-notifications portal 路徑缺漏修復（inject_portal_scripts 少了 3 個 key）
        add_action('fluent_community/portal_head', [__CLASS__, 'fix_fca_push_notifications_js_i18n'], 100);

        // 前台 JS i18n 修復：fluent-community（2 條）、fluent-booking 前台（3 條）
        add_action('wp_footer', [__CLASS__, 'fix_frontend_js_i18n'], 100);

        // 後台 JS i18n 修復：fluent-booking（45 條）、fluent-cart（8 條）、
        //                    fluent-crm（8 條）、fluent-player（5 條）、fluent-cart-pro（1 條）
        // priority 100 確保在所有 wp_localize_script 輸出之後才執行
        add_action('admin_footer', [__CLASS__, 'fix_admin_js_i18n'], 100);

        // DOM 文字替換注入器：處理 Vue/React 直接渲染的後台 UI 字串
        // 涵蓋：fluent-player-pro、fluent-crm、fca-widgets、fce-shortcodes、fca-boards、
        //        fca-hub、fca-global-search、fca-content-manager、fca-multi-reactions、
        //        fce-quick-fixes、fca-course-blocks
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_dom_translator']);
    }

    /**
     * 為每個 domain 載入翻譯
     */
    public static function load_translations() {
        $locale = determine_locale();

        // 只處理繁體中文
        if (strpos($locale, 'zh_TW') === false) {
            return;
        }

        foreach (self::$domains as $domain) {
            $mofile = self::$lang_dir . "/{$domain}-zh_TW.mo";
            if (file_exists($mofile)) {
                load_textdomain($domain, $mofile);
            }
        }
    }

    /**
     * 修復 fca-events 設定頁 JS i18n 缺失字串
     *
     * fca-events 的設定頁（fca-events-options.php）使用 Vue 模板 {{ i18n['key'] || 'fallback' }}
     * 但部分 key 沒有在 Trans_Strings PHP class 中註冊，導致永遠顯示英文 fallback。
     * 這裡在原始 i18n 物件載入後，注入缺失的翻譯。
     */
    public static function fix_fca_events_js_i18n() {
        // 只在繁體中文環境下執行
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) {
            return;
        }

        $extra_strings = [
            // ── 設定頁標題與按鈕 ──────────────────────────────────────
            'Events Options'                    => '活動選項',
            'Save Options'                      => '儲存選項',
            'Saving...'                         => '儲存中...',
            'Reset to Defaults'                 => '重設為預設值',
            'Loading options...'                => '載入選項中...',
            'Options saved successfully'        => '選項已成功儲存',
            'Failed to save options'            => '儲存選項失敗',
            'Options Reset'                     => '選項已重設',
            'Options have been reset to default values. Click Save to apply changes.'
                                                => '選項已重設為預設值。點擊儲存以套用變更。',
            'Are you sure you want to reset all options to their default values? This action cannot be undone.'
                                                => '確定要將所有選項重設為預設值嗎？此操作無法復原。',
            'Success'                           => '成功',
            'Error'                             => '錯誤',
            'Access Denied'                     => '存取被拒',
            'You do not have permission to manage event options.'
                                                => '您沒有管理活動選項的權限。',

            // ── 顯示設定 ───────────────────────────────────────────────
            'Display Settings'                  => '顯示設定',
            'General Settings'                  => '一般設定',
            'Configure general event display and behavior'
                                                => '設定活動顯示與行為',
            'Default Events List View'          => '預設活動列表檢視',
            'Choose the default view for the Events List page'
                                                => '選擇活動列表頁面的預設檢視方式',
            'List View'                         => '列表檢視',
            'Card View'                         => '卡片檢視',
            'Calendar View'                     => '日曆檢視',
            'Compact list with event details'   => '精簡列表含活動詳情',
            'Visual cards with images'          => '含圖片的視覺卡片',
            'Monthly calendar layout'           => '月曆排版',
            'Events Per Page'                   => '每頁活動數',
            'Number of events to display per page'
                                                => '每頁顯示的活動數量',
            'Between 5 and 50 events'           => '介於 5 至 50 個活動',
            'events'                            => '個活動',
            'Show Past Events'                  => '顯示過去的活動',
            'Display past events in the main events list'
                                                => '在主要活動列表中顯示已過去的活動',
            'Show past events by default'       => '預設顯示過去的活動',
            'Show Categories'                   => '顯示分類',
            'Display event categories in the list'
                                                => '在列表中顯示活動分類',
            'Show category filters'             => '顯示分類篩選器',
            'Enable Search'                     => '啟用搜尋',
            'Enable search and filtering'       => '啟用搜尋和篩選功能',
            'Show search functionality on events list'
                                                => '在活動列表顯示搜尋功能',
            'Show Events Menu'                  => '顯示活動選單',
            'Show Events menu in community navigation'
                                                => '在社群導覽中顯示活動選單',
            'Show or hide the Events menu in the frontend community navigation'
                                                => '在前台社群導覽中顯示或隱藏活動選單',

            // ── 日期時間設定 ────────────────────────────────────────────
            'Date and Time Settings'            => '日期與時間設定',
            'Configure how dates, times, and timezones are displayed'
                                                => '設定日期、時間和時區的顯示方式',
            'Date Format'                       => '日期格式',
            'Choose how dates are displayed throughout the events system'
                                                => '選擇活動系統中日期的顯示方式',
            'MM/DD/YYYY'                        => 'MM/DD/YYYY',
            'DD/MM/YYYY'                        => 'DD/MM/YYYY',
            'YYYY-MM-DD'                        => 'YYYY-MM-DD',
            'Month Day, Year'                   => '月 日, 年',
            'Day Month Year'                    => '日 月 年',
            'Time Format'                       => '時間格式',
            'Choose how times are displayed throughout the events system'
                                                => '選擇活動系統中時間的顯示方式',
            '12-hour format'                    => '12 小時制',
            '24-hour format'                    => '24 小時制',
            'Default Timezone'                  => '預設時區',
            'Set the default timezone for new events'
                                                => '設定新活動的預設時區',
            'Show Timezone on Event Times'      => '在活動時間顯示時區',
            'Show timezone codes (e.g., GMT, EST)'
                                                => '顯示時區代碼（例如 GMT、EST）',

            // ── 使用者本地時區 ──────────────────────────────────────────
            'Show User Local Timezone'          => '顯示使用者本地時區',
            "Display events in user's local timezone"
                                                => '以使用者的本地時區顯示活動',
            "Show user's local timezone for events"
                                                => '為活動顯示使用者的本地時區',
            'Local timezone only'               => '僅本地時區',
            'Event timezone + local in brackets'=> '活動時區 + 括號內本地時間',
            'Event timezone + time difference'  => '活動時區 + 時差',
            'Example: 13:00 - 14:00 ET'        => '範例：13:00 - 14:00 ET',
            'Example: 17:00 - 18:00 GMT'       => '範例：17:00 - 18:00 GMT',
            'Example: 17:00 - 18:00 GMT (13:00 - 14:00 ET)'
                                                => '範例：17:00 - 18:00 GMT（13:00 - 14:00 ET）',
            'Example: 17:00 - 18:00 GMT (ET+4)'=> '範例：17:00 - 18:00 GMT（ET+4）',

            // ── 電郵設定 ────────────────────────────────────────────────
            'Email Settings'                    => '電子郵件設定',
            'Email Notifications'               => '電子郵件通知',
            'Configure email notification preferences'
                                                => '設定電子郵件通知偏好',
            'Default email notification settings'
                                                => '預設電子郵件通知設定',
            'Enable email notifications by default'
                                                => '預設啟用電子郵件通知',
            'Reminder Timing'                   => '提醒時間',
            'Default reminder timing for events'=> '活動的預設提醒時間',
            '1 hour before'                     => '1 小時前',
            '1 day before'                      => '1 天前',
            '3 days before'                     => '3 天前',
            '1 week before'                     => '1 週前',

            // ── 月份縮寫 ────────────────────────────────────────────────
            'Jan'   => '1月',
            'Feb'   => '2月',
            'Mar'   => '3月',
            'Apr'   => '4月',
            'May'   => '5月',
            'Jun'   => '6月',
            'Jul'   => '7月',
            'Aug'   => '8月',
            'Sep'   => '9月',
            'Oct'   => '10月',
            'Nov'   => '11月',
            'Dec'   => '12月',

            // ── 星期縮寫 ────────────────────────────────────────────────
            'Mon'   => '一',
            'Tue'   => '二',
            'Wed'   => '三',
            'Thu'   => '四',
            'Fri'   => '五',
            'Sat'   => '六',
            'Sun'   => '日',
            'First' => '第一',
            'Last'  => '最後',
            'Every' => '每',

            // ── 存取控制 ────────────────────────────────────────────────
            'Access Restricted'                 => '存取受限',
            'Access Rules'                      => '存取規則',
            'Add Rule'                          => '新增規則',
            'Enable access control for this event'
                                                => '啟用此活動的存取控制',
            'Enable registration restrictions'  => '啟用報名限制',
            'Enable viewing restrictions'       => '啟用查看限制',
            'Restrict who can view and register for this event based on user status, roles, or other criteria'
                                                => '根據使用者狀態、角色或其他條件限制誰可以查看和報名此活動',
            'Restricted Access Message'         => '存取受限訊息',
            'Message shown to users who cannot access this event'
                                                => '向無法存取此活動的使用者顯示的訊息',
            'No access rules defined. Add at least one rule to restrict access.'
                                                => '尚未定義存取規則。請新增至少一條規則以限制存取。',
            'Select role...'                    => '選擇角色...',
            'Viewing Restrictions'              => '查看限制',
            'Visibility & Access'               => '可見度與存取',
            'Control who can register for this event'
                                                => '控制誰可以報名此活動',
            'Control who can see this event in listings and on the event page'
                                                => '控制誰可以在列表和活動頁面看到此活動',
            'Hide from public event listings'   => '從公開活動列表中隱藏',
            'Show restricted content to non-authorized users'
                                                => '向未授權使用者顯示受限內容',
            'If enabled, users will see the event content but with a restricted access notice'
                                                => '啟用後，使用者可看到活動內容，但會顯示存取受限提示',
            'Registration Restrictions'         => '報名限制',

            // ── 分類管理 ────────────────────────────────────────────────
            'Category Name'                     => '分類名稱',
            'Brief description of this category'=> '此分類的簡短說明',
            'Create Category'                   => '建立分類',
            'Create New Category'               => '建立新分類',
            'Creating...'                       => '建立中...',
            'Enter category name'               => '輸入分類名稱',

            // ── 活動狀態與錯誤 ──────────────────────────────────────────
            'Date & Time TBD'                   => '日期與時間待定',
            'Event Full'                        => '活動已額滿',
            'Event Timezone'                    => '活動時區',
            'Failed to load event'              => '載入活動失敗',
            'Failed to register for event'      => '報名活動失敗',
            'Registration failed'               => '報名失敗',
            'Unregistration failed'             => '取消報名失敗',
            'This event has already passed.'    => '此活動已結束。',
            'This event has reached its capacity limit.'
                                                => '此活動已達人數上限。',
            'This event has reached its capacity limit, but you can join the waitlist.'
                                                => '此活動已達人數上限，但您可以加入候補名單。',
            'This event is restricted. Please contact the event organizer for access.'
                                                => '此活動有存取限制。請聯絡活動主辦人以取得存取權限。',
            'You do not have permission to manage events.'
                                                => '您沒有管理活動的權限。',
            'You do not have permission to view this event.'
                                                => '您沒有查看此活動的權限。',

            // ── 候補名單 ────────────────────────────────────────────────
            'Remove from Waitlist'              => '從候補名單移除',
            'Waitlist Notification'             => '候補名單通知',

            // ── 電郵模板 ────────────────────────────────────────────────
            'Email Templates'                   => '電子郵件模板',
            'Manage Email Templates'            => '管理電子郵件模板',
            'Use Default Template'              => '使用預設模板',
            'Adhoc Notifications'               => '臨時通知',
            'Customize email templates for this event. Leave blank to use default templates.'
                                                => '自訂此活動的電子郵件模板。留空則使用預設模板。',
            'Set up custom email notifications with specific triggers for this event'
                                                => '為此活動設定具有特定觸發條件的自訂電子郵件通知',
            'Email sent as reminder before the event'
                                                => '活動前的提醒電子郵件',
            'Email sent when event details are updated'
                                                => '活動詳情更新時的電子郵件',
            'Email sent when someone cancels their registration'
                                                => '有人取消報名時的電子郵件',
            'Email sent when someone is added to waitlist'
                                                => '有人加入候補名單時的電子郵件',
            'Email sent when someone registers for this event'
                                                => '有人報名此活動時的電子郵件',
            'Registration Cancellation'         => '報名取消通知',
            'Registration Confirmation'         => '報名確認通知',

            // ── 時區詳細設定 ────────────────────────────────────────────
            'Display timezone codes next to event times'
                                                => '在活動時間旁顯示時區代碼',
            'Select the timezone where this event takes place. If different from system timezone, times will be displayed in both timezones.'
                                                => '選擇此活動的時區。若與系統時區不同，時間將以兩種時區顯示。',

            // ── 登入相關 ────────────────────────────────────────────────
            'Log In'                            => '登入',
            'Login Redirect URL'                => '登入後重新導向網址',
            'Where to redirect users after login (optional)'
                                                => '登入後重新導向使用者的位置（選填）',
            'Please log in to access this event, or contact the event organizer for more information.'
                                                => '請登入以存取此活動，或聯絡活動主辦人取得更多資訊。',
            'https://example.com/login'         => 'https://example.com/login',

            // ── 其他 ────────────────────────────────────────────────────
            'Optional'                          => '選填',
        ];

        // 直接輸出 <script>，因為這是在 fluent_community/portal_head hook 裡
        // 此時 fcaEventsI18n 已經由 fca-events 在 priority 10 設定好了
        ?>
        <script>
        (function() {
            var _extraI18n = <?php echo wp_json_encode($extra_strings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
            if (typeof window.fcaEventsI18n !== 'undefined') {
                for (var k in _extraI18n) {
                    if (!window.fcaEventsI18n[k]) {
                        window.fcaEventsI18n[k] = _extraI18n[k];
                    }
                }
            } else {
                window.fcaEventsI18n = _extraI18n;
            }
        })();
        </script>
        <?php
    }

    /**
     * 修復 fca-push-notifications 在 Fluent Community portal 路徑的 JS i18n 缺失
     *
     * inject_portal_scripts() 方法注入的 JSON 少了 3 個 key，
     * 而 wp_localize_script 路徑（一般頁面）已完整定義。
     * 這裡補齊 portal 情境下的缺漏。
     */
    public static function fix_fca_push_notifications_js_i18n() {
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) {
            return;
        }
        ?>
        <script>
        (function() {
            if (typeof window.fca_push_vars === 'undefined') return;
            if (!window.fca_push_vars['testNotificationTitle']) {
                window.fca_push_vars['testNotificationTitle'] = '測試通知';
            }
            if (!window.fca_push_vars['testNotificationBody']) {
                window.fca_push_vars['testNotificationBody'] = '這是來自 FCA 推播通知的測試通知！若您看到此訊息，表示通知功能正常運作。';
            }
            if (!window.fca_push_vars['testingNotifications']) {
                window.fca_push_vars['testingNotifications'] = '正在測試通知...';
            }
        })();
        </script>
        <?php
    }

    /**
     * 修復前台 JS i18n 缺失字串
     *
     * 包含：
     * - fluent-community：2 條（fluentComAdmin.i18n）
     * - fluent-booking 前台：3 條（fluentCalendarPublicVars.i18）
     *
     * 掛在 wp_footer priority 100，確保在 wp_localize_script 輸出之後執行。
     */
    public static function fix_frontend_js_i18n() {
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) {
            return;
        }

        $fluent_community = [
            "You haven't finished your comment yet. Do you want to leave without finishing?"
                => '您還沒完成留言，確定要離開嗎？',
            "You haven't finished your post yet. Do you want to leave without finishing?"
                => '您還沒完成貼文，確定要離開嗎？',
        ];

        $fluent_booking_public = [
            'File size should be less than'             => '檔案大小不得超過',
            'Go to previous page'                       => '前往上一頁',
            'You must accept the terms and conditions.' => '您必須接受條款與條件。',
        ];
        ?>
        <script>
        (function() {
            var _set = function(obj, key, val) {
                if (obj && !Object.prototype.hasOwnProperty.call(obj, key)) { obj[key] = val; }
            };

            // fluent-community（fluentComAdmin.i18n）
            var _fci = window.fluentComAdmin && window.fluentComAdmin.i18n;
            var _fcTr = <?php echo wp_json_encode($fluent_community, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
            if (_fci) {
                for (var k in _fcTr) { _set(_fci, k, _fcTr[k]); }
            }

            // fluent-booking 前台（fluentCalendarPublicVars.i18）
            var _fbi = window.fluentCalendarPublicVars && window.fluentCalendarPublicVars.i18;
            var _fbTr = <?php echo wp_json_encode($fluent_booking_public, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
            if (_fbi) {
                for (var k in _fbTr) { _set(_fbi, k, _fbTr[k]); }
            }
        })();
        </script>
        <?php
    }

    /**
     * 修復管理後台 JS i18n 缺失字串
     *
     * 包含：
     * - fluent-booking 後台：45 條（fluentFrameworkAdmin.trans）
     * - fluent-player 後台：5 條（fluentFrameworkAdmin.trans，與 booking 共用物件）
     * - fluent-cart 後台：6 條（fluentCartAdminApp.trans）
     * - fluent-cart Block Editor：2 條（fluent_cart_block_translation）
     * - fluent-cart-pro：1 條（fct_authorize_dot_net_data.translations）
     * - fluent-crm 後台：8 條（fcAdmin.trans）
     *
     * 掛在 admin_footer，確保在 wp_localize_script 輸出之後執行。
     */
    public static function fix_admin_js_i18n() {
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) {
            return;
        }

        // fluent-booking 後台（也包含 fluent-player 後台，共用 fluentFrameworkAdmin.trans）
        $fluent_framework_admin = [
            // fluent-booking 後台缺漏
            'Are you sure you want to delete this coupon?'
                => '確定要刪除這個優惠券？',
            'Coupon Code is required'           => '優惠券代碼為必填',
            'Coupon Module (Pro Required)'      => '優惠券模組（需要 Pro）',
            'Coupon Module is enabled'          => '優惠券模組已啟用',
            'Default Duration is required'      => '預設時長為必填',
            'Delete Coupon'                     => '刪除優惠券',
            'Discount is required'              => '折扣為必填',
            "Don't Reject"                      => '不拒絕',
            "Don't have a license key?"         => '沒有授權金鑰？',
            'Enable Coupon Module'              => '啟用優惠券模組',
            'Event Name'                        => '活動名稱',
            'Export Guests'                     => '匯出訪客',
            'Frontend Portal (Pro Required)'    => '前台入口（需要 Pro）',
            'Group (Pro Required)'              => '群組（需要 Pro）',
            'If no date is set, last date of current year will be used as the maximum date.'
                => '若未設定日期，將以當年最後一天作為最大日期。',
            'Image'                             => '圖片',
            'Link Copied'                       => '連結已複製',
            'Marketing Automation with FluentCRM'
                => '以 FluentCRM 進行行銷自動化',
            'Meeting link is required'          => '會議連結為必填',
            'Monetize your booking with FluentCart'
                => '以 FluentCart 將預約商業化',
            'No events found'                   => '找不到活動',
            "No, Don't cancel"                  => '否，不取消',
            "No, Don't delete"                  => '否，不刪除',
            'Offline'                           => '離線',
            'Only JSON files are allowed'       => '只允許 JSON 檔案',
            'Please enter username and password'=> '請輸入帳號與密碼',
            'Please fill all the required fields'
                => '請填寫所有必填欄位',
            'Please select at least one available time'
                => '請至少選擇一個可用時段',
            'Please wait while we save your settings'
                => '儲存設定中，請稍候',
            'Reliable Email delivery with FluentSMTP'
                => '使用 FluentSMTP 確保可靠的電子郵件傳送',
            'Seamlessly integrate FluentCart to sell paid bookings and manage products directly from your appointments.'
                => '無縫整合 FluentCart，直接在預約中銷售付費預約並管理產品。',
            'Search Guest'                      => '搜尋訪客',
            'Segment your guests, send bulk emails, run automations using FluentCRM.'
                => '使用 FluentCRM 細分訪客、發送大量電子郵件、執行自動化流程。',
            "Select remote calendar in where to add new events to when you're booked."
                => '選擇在您被預約時要新增活動的遠端日曆。',
            'Setup Recommended Features'        => '設定推薦功能',
            'Start Time'                        => '開始時間',
            'This feature'                      => '此功能',
            'Title is required'                 => '標題為必填',
            'To monetize your booking with FluentCart'
                => '若要以 FluentCart 將預約商業化',
            'Upload failed'                     => '上傳失敗',
            'We highly recommend this for monetization.'
                => '強烈建議啟用此功能以利商業化。',
            'We highly recommend to enable this feature.'
                => '強烈建議啟用此功能。',
            'We recommend this for reliable email delivery.'
                => '建議啟用此功能以確保可靠的電子郵件傳送。',
            "You don't have any feeds configured. Let's go"
                => '您尚未設定任何 Feed，立即前往',
            'to install and activate FluentCart.'
                => '以安裝並啟用 FluentCart。',

            // fluent-player 後台缺漏（共用同一 fluentFrameworkAdmin.trans 物件）
            'Back to analytics'                 => '返回分析',
            'Duration'                          => '時長',
            "Let's Go Home"                     => '返回首頁',
            'User not found'                    => '找不到使用者',
            'Video not found'                   => '找不到影片',
        ];

        // fluent-cart 後台（fluentCartAdminApp.trans）
        $fluent_cart_admin = [
            'Filename too long. Maximum 160 characters allowed'
                => '檔案名稱過長，最多允許 160 個字元',
            "If you don't select any country, all countries will be available for tax calculation. Are you sure you want to proceed?"
                => '如果您不選擇任何國家，所有國家都可進行稅金計算。確定要繼續嗎？',
            "Oops! That page can't be found."   => '糟糕！找不到該頁面。',
            "Status controls the product's visibility on the public page and its purchasability. 'Publish' indicates that it is live and can be purchased, 'Draft' signifies that it is in private editing, and 'Schedule' means it will be publish on a specified date. 'Private' means,  Only visible to adminstrators but can be purchased via direct purchase link. The status can only be changed once pricing is set."
                => "狀態控制產品在公開頁面的可見性及可購買性。「發布」表示已上線且可購買，「草稿」表示正在私下編輯，「排程」表示將在指定日期發布，「私密」表示只有管理員可見但可透過直接購買連結購買。狀態只能在設定定價後變更。",
            "Switching to 'Simple' will permanently delete all variations except the first one."
                => '切換為「簡單」商品將永久刪除除第一個以外的所有變體。',
            "The night's still young!"          => '夜還長著呢！',
        ];

        // fluent-cart Block Editor（fluent_cart_block_translation）
        $fluent_cart_block = [
            "Automatically use the current page's product context."
                => '自動使用目前頁面的產品內容。',
            "We couldn't find any products matching your search"
                => '找不到符合您搜尋條件的產品',
        ];

        // fluent-crm 後台（fcAdmin.trans）
        $fluent_crm_admin = [
            'Date Of Birth'         => '生日',
            'Evening vibes!'        => '傍晚好！',
            'Good afternoon'        => '午安！',
            'Good evening'          => '晚安！',
            'Good morning'          => '早安！',
            'SMS Status'            => '簡訊狀態',
            'Step into the moonlight!'
                                    => '月光下見！',
            "The night's still young!"
                                    => '夜還長著呢！',
        ];

        // fluent-security 後台（fluentAuthAdmin.i18n）
        $fluent_security_admin = [
            'All the file changes are marked as ignored previously. You can review the files and check if you want to keep them on ignored lists or not.'
                => '所有檔案變更皆已在先前標記為忽略。您可以查看這些檔案，並檢查是否要將它們保留在忽略清單中。',
            'Custimize your default system emails sent by WordPress. Make it beautiful, use your own contents.'
                => '自訂由 WordPress 發送的預設系統電子郵件。讓它更美觀，使用您自己的內容。',
            'Disable Magic Login for specific user roles (Leave blank to enable magic login for all users)'
                => '針對特定使用者角色停用 Magic Login（留白以為所有使用者啟用 Magic Login）',
            'Disable REST Endpoint for wp users query for public (Recommended: Disable)'
                => '停用針對公眾的 wp 使用者查詢 REST 端點（建議：停用）',
            'Enable auto-scanning of your Core WordPress files and get emails if there has any un-authorized file changes.'
                => '啟用您的 WordPress 核心檔案自動掃描，若發現任何未經授權的檔案變更，您將收到電子郵件。',
            'Ensure your WordPress core files remain secure by detecting any unauthorized changes or tampering'
                => '透過偵測任何未經授權的變更或竄改，確保您的 WordPress 核心檔案保持安全。',
            'FluentAuth found some file changes but you marked them as ignored them previously'
                => 'FluentAuth 發現一些檔案變更，但您已先前將它們標記為忽略',
            'FluentAuth has scanned your site and found no unauthorized changes in WordPress core files.'
                => 'FluentAuth 已掃描您的網站，且在 WordPress 核心檔案中未發現任何未經授權的變更。',
            'For more information how to setup Facebook app for social authentication please'
                => '有關如何為社交認證設定 Facebook 應用程式的更多資訊，請',
            'For more information how to setup Github app for social authentication please'
                => '有關如何為社交認證設定 Github 應用程式的更多資訊，請',
            'For more information how to setup google app for social authentication please'
                => '有關如何為社交認證設定 Google 應用程式的更多資訊，請',
            'Full Authentication Flow ShortCode (includes Login Form, Registration Form and Password Reset Form)'
                => '完整認證流程短代碼（包含登入表單、註冊表單和密碼重設表單）',
            'If a user fails to log in %1s times within %2s minutes minutes, the system will block the user for %3s minutes.'
                => '如果使用者在 %2s 分鐘內登入失敗 %1s 次，系統將封鎖該使用者 %3s 分鐘。',
            'If you want to change the notification email address or disable scanning service,'
                => '如果您想變更通知電子郵件地址或停用掃描服務，',
            'Look like there has some file changes has been detected. Please review individual files and take necesarry actions.'
                => '看起來偵測到一些檔案變更。請查看個別檔案並採取必要措施。',
            'Please add the following code in your wp-config.php file (please replace the *** with your app values)'
                => '請在您的 wp-config.php 檔案中加入以下程式碼（請將 *** 取代為您的應用程式值）',
            'Please fill up the form and get a free API key to enable Security Scan and Automatted Notification. This API key will be used to send you email from FluentAuth Service. (You need the free API key just once)'
                => '請填寫表單以取得免費的 API 金鑰，來啟用安全掃描與自動通知。此 API 金鑰將用於由 FluentAuth 服務向您發送電子郵件。（您只需要取得一次免費的 API 金鑰）',
            'Please get a free API key to enable Scheduled Scanning and get notified when FluentAuth detects file changes.'
                => '請取得免費的 API 金鑰以啟用定期掃描，並在 FluentAuth 偵測到檔案變更時收到通知。',
            'Please note, If there has redirect_to query parameter in the Login Page URL, it will be used for redirection instead of these set rule.'
                => '請注意，如果登入頁面 URL 中有 redirect_to 查詢參數，它將被用於重新導向，而不是這些設定的規則。',
            'Replace Default Signup Form with Secure form with Email Verfication (Recommended: Enable)'
                => '使用帶有電子郵件驗證的安全表單取代預設註冊表單（建議：啟用）',
            'This email notification is disabled. So no email notification will be sent for this event.'
                => '此電子郵件通知已停用。因此不會為此事件發送電子郵件通知。',
            'This email will use the system default content. If you want to customize the email subject and body please switch to Custimized Content.'
                => '此電子郵件將使用系統預設內容。如果您想自訂電子郵件主旨和內文，請切換到「自訂內容」。',
            'To use Google One-Tap feature, you need to add your website domain in the Authorized JavaScript origins section of your Google app settings.'
                => '若要使用 Google One-Tap 功能，您需要在您的 Google 應用程式設定的「已授權的 JavaScript 來源」區段中加入您的網站網域。',
            'You can scan your site to detect unauthorized changes in WordPress core files. After scanning, FluentAuth will display any security issues found.'
                => '您可以掃描您的網站以偵測 WordPress 核心檔案中未經授權的變更。掃描後，FluentAuth 將顯示發現的任何安全性問題。',
            'You may remove the h3 content or change it. If you want to define customized redirect URL then use shortcode:'
                => '您可以移除或變更 h3 的內容。如果您想定義自訂的重新導向 URL，請使用短代碼：',
        ];

        // fluent-smtp 後台（FluentMailAdmin.trans）
        $fluent_smtp_admin = [
            ' connection.'                              => ' 連線。',
            ' in the '                                  => ' 在 ',
            ' option in the Google Cloud Project.'      => ' 選項在 Google Cloud Project 中。',
            '*** It is very important to put '          => '*** 非常重要的是要把 ',
            'Connection Name '                          => '連線名稱 ',
            'Discord Channel Details: '                 => 'Discord 頻道詳細資訊：',
            'FluentSMTP does not store your email notifications data. '
                => 'FluentSMTP 不會儲存您的電子郵件通知資料。',
            'Follow this link to get an API Key from ElasticEmail: '
                => '點擊此連結從 ElasticEmail 取得 API 金鑰：',
            'If you find an issue or have a suggestion please '
                => '若您發現任何問題或有任何建議，請 ',
            'If you have a minute, consider '           => '如果您有空，請考慮 ',
            'Meet '                                     => '認識 ',
            'Please '                                   => '請 ',
            'Please authenticate with Google to get '   => '請向 Google 驗證以取得 ',
            'Please authenticate with Office365 to get '=> '請向 Office365 驗證以取得 ',
            'Region '                                   => '區域 ',
            'Sender Email '                             => '寄件者電子郵件 ',
            'Slack Channel Details: '                   => 'Slack 頻道詳細資訊：',
        ];

        // fluent-cart-pro（fct_authorize_dot_net_data.translations）
        $fluent_cart_pro_authnet = [
            'Tokenization failed. Please verify the details.'
                => '代幣化失敗，請確認相關資訊。',
        ];
        ?>
        <script>
        (function() {
            var _set = function(obj, key, val) {
                if (obj && !Object.prototype.hasOwnProperty.call(obj, key)) { obj[key] = val; }
            };
            var _merge = function(target, src) {
                if (!target || !src) return;
                for (var k in src) { _set(target, k, src[k]); }
            };

            // fluent-booking 後台 + fluent-player 後台（共用 fluentFrameworkAdmin.trans）
            var _ffa = window.fluentFrameworkAdmin && window.fluentFrameworkAdmin.trans;
            _merge(_ffa, <?php echo wp_json_encode($fluent_framework_admin, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);

            // fluent-cart 後台（fluentCartAdminApp.trans）
            var _fca = window.fluentCartAdminApp && window.fluentCartAdminApp.trans;
            _merge(_fca, <?php echo wp_json_encode($fluent_cart_admin, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);

            // fluent-cart Block Editor（fluent_cart_block_translation）
            _merge(window.fluent_cart_block_translation, <?php echo wp_json_encode($fluent_cart_block, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);

            // fluent-crm 後台（fcAdmin.trans）
            var _fcAdm = window.fcAdmin && window.fcAdmin.trans;
            _merge(_fcAdm, <?php echo wp_json_encode($fluent_crm_admin, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);

            // fluent-cart-pro Authorize.Net（fct_authorize_dot_net_data.translations）
            var _authnet = window.fct_authorize_dot_net_data && window.fct_authorize_dot_net_data.translations;
            _merge(_authnet, <?php echo wp_json_encode($fluent_cart_pro_authnet, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);

            // fluent-security 後台（fluentAuthAdmin.i18n）
            var _fsecI18n = window.fluentAuthAdmin && window.fluentAuthAdmin.i18n;
            _merge(_fsecI18n, <?php echo wp_json_encode($fluent_security_admin, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);

            // fluent-smtp 後台（FluentMailAdmin.trans）
            var _fsmtpTr = window.FluentMailAdmin && window.FluentMailAdmin.trans;
            _merge(_fsmtpTr, <?php echo wp_json_encode($fluent_smtp_admin, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        })();
        </script>
        <?php
    }
    /**
     * 載入 DOM 文字替換注入器（translations.js + translator.js）
     *
     * 針對使用 Vue.js / React 渲染後台 UI 的外掛，
     * gettext / wp_localize_script 無法覆蓋直接硬編碼在 JS 元件中的文字，
     * 因此改用 MutationObserver 在 DOM 渲染後替換對應的文字節點。
     *
     * 只在以下外掛的後台頁面載入，避免影響其他頁面：
     * - fluent-player-pro：  page=fluent-player*
     * - fluent-crm：         page=fluentcrm*
     * - fca-widgets：        page=fca-widgets*
     * - fce-shortcodes：     page=fce-shortcodes*
     * - fca-boards：         page=fca-boards*
     */
    public static function enqueue_dom_translator() {
        // 只在繁體中文環境下執行
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) {
            return;
        }

        // 判斷目前是否在相關外掛的後台頁面
        $screen_id = '';
        $page      = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

        $relevant_pages = [
            'fluent-player',        // fluent-player-pro 設定頁
            'fluentcrm',            // fluent-crm（fluentcrm-admin 等）
            'fca-widgets',          // fca-widgets
            'fce-shortcodes',       // fce-shortcodes 設定頁
            'fca-boards',           // fca-boards
            'fca-display-name',     // fca-display-name 設定頁
            'fca-pwa',              // FCA PWA 設定頁 + Analytics
            'fca-comments',         // FCA Comments 設定頁
            'fca-hub-settings',     // FCA Hub 附加元件管理器
            'fca-content-manager',  // FCA Content Manager（及子頁面）
            'fca-cm-',              // FCA Content Manager 子頁面前綴
            'fca-multi-reactions',  // FCA Multi-Reactions 設定頁
            'fce-quick-fixes',      // FCE Quick Fixes 設定頁
            'fluent-cart',          // FluentCart 後台（含 fchub-multi-currency 設定頁）
        ];

        $is_relevant = false;
        foreach ($relevant_pages as $prefix) {
            if (strpos($page, $prefix) === 0) {
                $is_relevant = true;
                break;
            }
        }

        if (!$is_relevant) {
            return;
        }

        $plugin_url     = plugin_dir_url(__FILE__);
        $plugin_version = '1.6.3';

        // 先載入翻譯字典
        wp_enqueue_script(
            'fca-zh-tw-translations',
            $plugin_url . 'js/translations.js',
            [],
            $plugin_version,
            true // 放在 footer
        );

        // 再載入注入器（依賴字典）
        wp_enqueue_script(
            'fca-zh-tw-translator',
            $plugin_url . 'js/translator.js',
            ['fca-zh-tw-translations'],
            $plugin_version,
            true // 放在 footer
        );

        // 注入頁面識別符，方便未來擴充依頁面過濾翻譯
        wp_add_inline_script(
            'fca-zh-tw-translations',
            'window.FCA_ZH_TW_PAGE_CONTEXT = ' . wp_json_encode($page) . ';',
            'before'
        );
    }
}

FCA_Fluent_ZhTW::init();
