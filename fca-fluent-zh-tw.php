<?php
/**
 * Plugin Name: FCA & Fluent 繁體中文翻譯包
 * Plugin URI:  https://buygo.me/
 * Description: 提供 FCA 系列與 Fluent 系列外掛的完整繁體中文翻譯（含 Gettext 與 JS DOM 翻譯）。
 * Version:     1.6.55
 * Author:      Fish
 * Text Domain: fca-fluent-zh-tw
 */

if (!defined('ABSPATH')) exit;

class FCA_Fluent_ZhTW {
    private static $lang_dir;
    private static $domains = [
        'fca-boards', 'fca-comments', 'fca-content-manager', 'fca-course-blocks', 
        'fca-display-name', 'fca-events', 'fca-events-basic', 'fca-events-spaces', 
        'fca-global-search', 'fca-hub', 'fca-knowledgebase', 'fca-multi-reactions', 
        'fca-pages', 'fca-push-notifications', 'fca-pwa', 'fca-real-time', 
        'fca-widgets', 'fce-quick-fixes', 'fce-shortcodes', 'fchub-fakturownia', 
        'fchub-memberships', 'fchub-multi-currency', 'fchub-p24', 'fchub-portal-extender', 
        'fchub-wishlist', 'fluent-booking', 'fluent-booking-pro', 'fluent-cart', 
        'fluent-cart-pro', 'fluent-community', 'fluent-community-pro', 'fluent-crm', 
        'fluent-messaging', 'fluent-player', 'fluent-player-pro', 'fluent-security', 
        'fluent-smtp', 'fluent-snippet-storage', 'fluent-toolkit', 'fluentcampaign-pro', 
        'fluentform', 'fluentform-block', 'fluentform-pdf', 'fluentformpro', 'fluentforms-pdf'
    ];

    public static function init() {
        self::$lang_dir = plugin_dir_path(__FILE__) . 'languages';
        add_action('plugins_loaded', [__CLASS__, 'load_translations']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_dom_translator']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_dom_translator']);
        
        // 載入自動更新器
        if (file_exists(plugin_dir_path(__FILE__) . 'updater.php')) {
            require_once plugin_dir_path(__FILE__) . 'updater.php';
            if (class_exists('ZhTW_Updater')) {
                new ZhTW_Updater(__FILE__, '1.6.55');
            }
        }
    }

    public static function load_translations() {
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) return;
        foreach (self::$domains as $domain) {
            $mofile = self::$lang_dir . "/{$domain}-zh_TW.mo";
            if (file_exists($mofile)) {
                load_textdomain($domain, $mofile);
            }
        }
    }

    public static function enqueue_dom_translator() {
        $locale = determine_locale();
        if (strpos($locale, 'zh_TW') === false) return;

        $plugin_url = plugin_dir_url(__FILE__);
        $version = '1.6.55';

        wp_enqueue_script('fca-zh-tw-translations', $plugin_url . 'js/translations.js', [], $version, true);
        wp_enqueue_script('fca-zh-tw-translator', $plugin_url . 'js/translator.js', ['fca-zh-tw-translations'], $version, true);
    }
}

FCA_Fluent_ZhTW::init();
