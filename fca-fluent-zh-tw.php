<?php
/**
 * Plugin Name: FCA & Fluent 繁體中文翻譯包
 * Plugin URI: https://aiver.me
 * Description: 為所有 FCA 系列與 Fluent 系列外掛提供繁體中文翻譯，不修改原始外掛檔案，更新外掛不受影響。
 * Version: 1.0.0
 * Author: BuyGo
 * License: GPL v2 or later
 * Text Domain: fca-fluent-zh-tw
 */

if (!defined('ABSPATH')) {
    exit;
}

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
        'fca-content-manager',
        'fca-course-blocks',
        'fca-events',
        'fca-events-basic',
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
        'fluent-player',
        'fluent-player-pro',
        'fluent-security',
        'fluent-smtp',
        'fluent-toolkit',
        'fluentcampaign-pro',
        'fluentform',
        'fluentform-block',
        'fluentformpro',
        // 第三方相容
        'wpkj-alipay-gateway-for-fluentcart',
    ];

    /**
     * 翻譯檔目錄
     */
    private static $lang_dir;

    public static function init() {
        self::$lang_dir = plugin_dir_path(__FILE__) . 'languages';

        // 在外掛載入前搶先載入翻譯（priority 1，比一般外掛的 init 更早）
        add_action('plugins_loaded', [__CLASS__, 'load_translations'], 1);

        // 修復 fca-events 設定頁缺少的 JS i18n 字串
        // fca-events 在 fluent_community/portal_head priority 10 注入 fcaEventsI18n
        // 我們用 priority 99 在它之後補上缺失字串
        add_action('fluent_community/portal_head', [__CLASS__, 'fix_fca_events_js_i18n'], 99);
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
            // 設定頁標題與按鈕
            'Events Options' => '活動選項',
            'Save Options' => '儲存選項',
            'Saving...' => '儲存中...',
            'Reset to Defaults' => '重設為預設值',
            'Loading options...' => '載入選項中...',
            'Options saved successfully' => '選項已成功儲存',
            'Failed to save options' => '儲存選項失敗',
            'Options Reset' => '選項已重設',
            'Options have been reset to default values. Click Save to apply changes.' => '選項已重設為預設值。點擊儲存以套用變更。',
            'Are you sure you want to reset all options to their default values? This action cannot be undone.' => '確定要將所有選項重設為預設值嗎？此操作無法復原。',
            'Success' => '成功',
            'Error' => '錯誤',
            'Access Denied' => '存取被拒',
            'You do not have permission to manage event options.' => '您沒有管理活動選項的權限。',

            // 顯示設定
            'Display Settings' => '顯示設定',
            'General Settings' => '一般設定',
            'Configure general event display and behavior' => '設定活動顯示與行為',
            'Default Events List View' => '預設活動列表檢視',
            'Choose the default view for the Events List page' => '選擇活動列表頁面的預設檢視方式',
            'List View' => '列表檢視',
            'Card View' => '卡片檢視',
            'Calendar View' => '日曆檢視',
            'Compact list with event details' => '精簡列表含活動詳情',
            'Visual cards with images' => '含圖片的視覺卡片',
            'Monthly calendar layout' => '月曆排版',
            'Events Per Page' => '每頁活動數',
            'Number of events to display per page' => '每頁顯示的活動數量',
            'Between 5 and 50 events' => '介於 5 至 50 個活動',
            'events' => '個活動',
            'Show Past Events' => '顯示過去的活動',
            'Display past events in the main events list' => '在主要活動列表中顯示已過去的活動',
            'Show past events by default' => '預設顯示過去的活動',
            'Show Categories' => '顯示分類',
            'Display event categories in the list' => '在列表中顯示活動分類',
            'Show category filters' => '顯示分類篩選器',
            'Enable Search' => '啟用搜尋',
            'Enable search and filtering' => '啟用搜尋和篩選功能',
            'Show search functionality on events list' => '在活動列表顯示搜尋功能',
            'Show Events Menu' => '顯示活動選單',
            'Show Events menu in community navigation' => '在社群導覽中顯示活動選單',
            'Show or hide the Events menu in the frontend community navigation' => '在前台社群導覽中顯示或隱藏活動選單',

            // 日期時間設定
            'Date and Time Settings' => '日期與時間設定',
            'Configure how dates, times, and timezones are displayed' => '設定日期、時間和時區的顯示方式',
            'Date Format' => '日期格式',
            'Choose how dates are displayed throughout the events system' => '選擇活動系統中日期的顯示方式',
            'MM/DD/YYYY' => 'MM/DD/YYYY',
            'DD/MM/YYYY' => 'DD/MM/YYYY',
            'YYYY-MM-DD' => 'YYYY-MM-DD',
            'Month Day, Year' => '月 日, 年',
            'Day Month Year' => '日 月 年',
            'Time Format' => '時間格式',
            'Choose how times are displayed throughout the events system' => '選擇活動系統中時間的顯示方式',
            '12-hour format' => '12 小時制',
            '24-hour format' => '24 小時制',
            'Default Timezone' => '預設時區',
            'Set the default timezone for new events' => '設定新活動的預設時區',
            'Show Timezone on Event Times' => '在活動時間顯示時區',
            'Show timezone codes (e.g., GMT, EST)' => '顯示時區代碼（例如 GMT、EST）',

            // 使用者本地時區
            'Show User Local Timezone' => '顯示使用者本地時區',
            "Display events in user's local timezone" => '以使用者的本地時區顯示活動',
            "Show user's local timezone for events" => '為活動顯示使用者的本地時區',
            'Local timezone only' => '僅本地時區',
            'Event timezone + local in brackets' => '活動時區 + 括號內本地時間',
            'Event timezone + time difference' => '活動時區 + 時差',
            'Example: 13:00 - 14:00 ET' => '範例：13:00 - 14:00 ET',
            'Example: 17:00 - 18:00 GMT' => '範例：17:00 - 18:00 GMT',
            'Example: 17:00 - 18:00 GMT (13:00 - 14:00 ET)' => '範例：17:00 - 18:00 GMT（13:00 - 14:00 ET）',
            'Example: 17:00 - 18:00 GMT (ET+4)' => '範例：17:00 - 18:00 GMT（ET+4）',

            // 電郵設定
            'Email Settings' => '電子郵件設定',
            'Email Notifications' => '電子郵件通知',
            'Configure email notification preferences' => '設定電子郵件通知偏好',
            'Default email notification settings' => '預設電子郵件通知設定',
            'Enable email notifications by default' => '預設啟用電子郵件通知',
            'Reminder Timing' => '提醒時間',
            'Default reminder timing for events' => '活動的預設提醒時間',
            '1 hour before' => '1 小時前',
            '1 day before' => '1 天前',
            '3 days before' => '3 天前',
            '1 week before' => '1 週前',
        ];

        // 直接輸出 <script>，因為這是在 fluent_community/portal_head hook 裡
        // 此時 fcaEventsI18n 已經由 fca-events 在 priority 10 設定好了
        ?>
        <script>
        (function() {
            var _extraI18n = <?php echo json_encode($extra_strings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
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
}

FCA_Fluent_ZhTW::init();
