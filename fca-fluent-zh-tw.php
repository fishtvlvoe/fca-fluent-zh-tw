<?php
/**
 * Plugin Name: FCA & Fluent 繁體中文翻譯包
 * Description: 提供 FCA 與 Fluent 系列外掛的完整繁體中文翻譯，包含傳統 Gettext 與 JS DOM 實時翻譯。
 * Version: 1.6.55
 * Author: Fish
 */

if (!defined('ABSPATH')) exit;

class FCA_Fluent_ZhTW {
    private static $lang_dir;
    private static $domains = [
        'fca-boards', 'fca-comments', 'fca-content-manager', 'fca-course-blocks', 
        'fca-display-name', 'fca-events', 'fca-global-search', 'fca-hub', 
        'fca-knowledgebase', 'fca-multi-reactions', 'fca-pages', 
        'fca-push-notifications', 'fca-pwa', 'fca-real-time', 'fca-widgets', 
        'fce-quick-fixes', 'fce-shortcodes', 'fchub-memberships', 'fchub-multi-currency', 
        'fchub-portal-extender', 'fchub-wishlist', 'fluent-booking', 'fluent-booking-pro', 
        'fluent-cart', 'fluent-cart-pro', 'fluent-community', 'fluent-crm', 
        'fluent-messaging', 'fluent-player', 'fluent-player-pro', 'fluent-security', 
        'fluent-smtp', 'fluent-toolkit', 'fluentform', 'fluentformpro'
    ];

    public static function init() {
        self::$lang_dir = plugin_dir_path(__FILE__) . 'languages';
        add_action('plugins_loaded', [__CLASS__, 'load_translations']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_dom_translator']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_dom_translator']);
        
        require_once plugin_dir_path(__FILE__) . 'updater.php';
        new ZhTW_Updater(__FILE__, '1.6.55');
    }

    public static function load_translations() {
        foreach (self::$domains as $domain) {
            load_textdomain($domain, self::$lang_dir . '/' . $domain . '-zh_TW.mo');
        }
    }

    public static function enqueue_dom_translator() {
        if (strpos(determine_locale(), 'zh_TW') === false) return;

        $plugin_url = plugin_dir_url(__FILE__);
        $v = '1.6.55';

        wp_enqueue_script('fca-zh-tw-trans', $plugin_url . 'js/translations.js', [], $v, true);
        wp_enqueue_script('fca-zh-tw-engine', $plugin_url . 'js/translator.js', ['fca-zh-tw-trans'], $v, true);
    }
}
FCA_Fluent_ZhTW::init();
