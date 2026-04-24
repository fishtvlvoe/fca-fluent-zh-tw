<?php
/**
 * Plugin Name: FCA & Fluent 繁體中文翻譯包
 * Description: 提供 FCA 系列與 Fluent 系列外掛的專業繁體中文翻譯，包含 JS DOM 實時翻譯與 PHP Gettext 補強。
 * Version: 1.6.62
 * Author: Fish
 */

if (!defined('ABSPATH')) {
    exit;
}

class FCA_Fluent_ZhTW {

    private static $lang_dir;
    
    private static $domains = [
        'fca-boards', 'fca-comments', 'fca-content-manager', 'fca-course-blocks', 
        'fca-display-name', 'fca-events', 'fca-events-basic', 'fca-events-spaces', 
        'fca-global-search', 'fca-hub', 'fca-knowledgebase', 'fca-multi-reactions', 
        'fca-pages', 'fca-push-notifications', 'fca-pwa', 'fca-real-time', 
        'fca-widgets', 'fce-quick-fixes', 'fce-shortcodes', 'fc-partner',
        'fchub-fakturownia', 
        'fchub-memberships', 'fchub-multi-currency', 'fchub-p24', 
        'fchub-portal-extender', 'fchub-wishlist', 'fluent-booking', 
        'fluent-booking-pro', 'fluent-cart', 'fluent-cart-pro', 
        'fluent-community', 'fluent-community-pro', 'fluent-crm', 
        'fluent-messaging', 'fluent-player', 'fluent-player-pro', 
        'fluent-security', 'fluent-smtp', 'fluent-toolkit', 
        'fluentcampaign-pro', 'fluentform', 'fluentform-block', 
        'fluentform-pdf', 'fluentformpro', 'fluentforms-pdf'
    ];

    public static function init() {
        self::$lang_dir = plugin_dir_path(__FILE__) . 'languages';
        
        // 1. 暴力修復：在 HTML 最上方直接印出 jQuery
        add_action('wp_head', [__CLASS__, 'force_jquery_to_top'], -1000);

        // 2. 翻譯載入
        add_action('init', [__CLASS__, 'load_translations'], 10);

        add_filter('fca_hub_addon_metadata', [__CLASS__, 'filter_fca_hub_addon_metadata'], 10, 2);
        
        // 3. JS 翻譯器載入
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_js'], 100);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_js'], 100);

        // 4. 更新器
        if (file_exists(plugin_dir_path(__FILE__) . 'updater.php')) {
            require_once plugin_dir_path(__FILE__) . 'updater.php';
            if (class_exists('ZhTW_Updater')) {
                new ZhTW_Updater(__FILE__, '1.6.55');
            }
        }
    }

    /**
     * 暴力強制 jQuery 到最頂端
     */
    public static function force_jquery_to_top() {
        if (is_admin()) return;
        $jquery_url = includes_url('js/jquery/jquery.min.js');
        echo '<script src="' . esc_url($jquery_url) . '" id="fca-zhtw-forced-jquery"></script>' . "\n";
    }

    public static function load_translations() {
        if (strpos(determine_locale(), 'zh_TW') === false) {
            return;
        }

        static $loaded = [];
        foreach (self::$domains as $domain) {
            if (isset($loaded[$domain])) continue;
            $mo_file = self::$lang_dir . '/' . $domain . '-zh_TW.mo';
            if (file_exists($mo_file)) {
                load_textdomain($domain, $mo_file);
                $loaded[$domain] = true;
            }
        }
    }

    /**
     * FCA Hub：更新伺服器 registry 的 name/description 僅能由此覆寫（不影響 slug/basename）。
     */
    public static function filter_fca_hub_addon_metadata($addon, $slug) {
        if (!is_array($addon)) {
            return $addon;
        }

        if (strpos(determine_locale(), 'zh_TW') === false) {
            return $addon;
        }

        if (!empty($addon['target']) && $addon['target'] === 'Coming Soon') {
            $addon['target'] = '即將推出';
        }

        $map_file = plugin_dir_path(__FILE__) . 'includes/fca-hub-registry-zh.php';
        if (!is_readable($map_file)) {
            return $addon;
        }

        $map = include $map_file;
        if (!is_array($map) || empty($map[$slug]) || !is_array($map[$slug])) {
            return $addon;
        }

        $row = $map[$slug];
        if (!empty($row['name'])) {
            $addon['name'] = $row['name'];
        }

        if (!empty($row['description'])) {
            $addon['description'] = $row['description'];
        }

        return $addon;
    }

    public static function enqueue_js() {
        if (strpos(determine_locale(), 'zh_TW') === false) {
            return;
        }

        $url = plugin_dir_url(__FILE__);
        $ver = '1.6.55';

        wp_enqueue_script('fca-zh-tw-translations', $url . 'js/translations.js', [], $ver, true);
        wp_enqueue_script('fca-zh-tw-translator', $url . 'js/translator.js', ['fca-zh-tw-translations'], $ver, true);
        
        $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        wp_add_inline_script('fca-zh-tw-translations', 'window.FCA_ZH_TW_PAGE_CONTEXT = ' . wp_json_encode($page) . ';', 'before');
    }
}

FCA_Fluent_ZhTW::init();
