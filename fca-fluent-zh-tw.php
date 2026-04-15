<?php
/**
 * Plugin Name: FCA & Fluent 繁體中文翻譯包
 * Description: 提供 FCA 系列與 Fluent 系列外掛的繁體中文翻譯，解決 JS 渲染文字無法翻譯的問題。
 * Version: 1.6.55
 * Author: Fish
 * Text Domain: fca-fluent-zh-tw
 */

if (!defined('ABSPATH')) exit;

class FCA_Fluent_ZhTW {
    private static $lang_dir;
    private static $domains = [
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
        'fca-real-time',
        'fca-widgets',
        'fce-quick-fixes',
        'fce-shortcodes',
        'fchub-fakturownia',
        'fchub-memberships',
        'fchub-multi-currency',
        'fchub-p24',
        'fchub-portal-extender',
        'fchub-wishlist',
        'fluent-booking',
        'fluent-booking-pro',
        'fluent-cart',
        'fluent-cart-pro',
        'fluent-community',
        'fluent-community-pro',
        'fluent-crm',
        'fluent-messaging',
        'fluent-player',
        'fluent-player-pro',
        'fluent-security',
        'fluent-smtp',
        'fluent-snippet-storage',
        'fluent-toolkit',
        'fluentcampaign-pro',
        'fluentform',
        'fluentform-block',
        'fluentform-pdf',
        'fluentformpro',
        'fluentforms-pdf'
    ];

    public static function init() {
        self::$lang_dir = plugin_dir_path(__FILE__) . 'languages';
        add_action('plugins_loaded', [__CLASS__, 'load_translations']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_dom_translator']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_dom_translator']);
        
        // 專門針對 Portal 的掛載
        add_action('fluent_community/portal_head', [__CLASS__, 'enqueue_portal_dom_translator']);
    }

    public static function load_translations() {
        foreach (self::$domains as $domain) {
            $mo_file = self::$lang_dir . '/' . $domain . '-zh_TW.mo';
            if (file_exists($mo_file)) {
                load_textdomain($domain, $mo_file);
            }
        }
    }

    public static function enqueue_dom_translator() {
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) return;

        $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        $plugin_url = plugin_dir_url(__FILE__);
        $version = '1.6.55';

        // 載入字典與翻譯器
        wp_enqueue_script('fca-zh-tw-translations', $plugin_url . 'js/translations.js', [], $version, true);
        wp_enqueue_script('fca-zh-tw-translator', $plugin_url . 'js/translator.js', ['fca-zh-tw-translations'], $version, true);
        wp_add_inline_script('fca-zh-tw-translations', 'window.FCA_ZH_TW_PAGE_CONTEXT = ' . wp_json_encode($page) . ';', 'before');
    }

    public static function enqueue_portal_dom_translator() {
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) return;
        if (!current_user_can('manage_options')) return;

        $plugin_url = plugin_dir_url(__FILE__);
        $version = '1.6.55';

        echo '<script src="' . esc_url($plugin_url . 'js/translations.js?v=' . $version) . '"></script>' . "\n";
        echo '<script src="' . esc_url($plugin_url . 'js/translator.js?v=' . $version) . '"></script>' . "\n";
    }
}

FCA_Fluent_ZhTW::init();
