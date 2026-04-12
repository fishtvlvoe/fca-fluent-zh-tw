<?php
/**
 * GitHub Releases 自動更新器
 *
 * 透過 GitHub API 檢查最新版本，讓 WordPress 後台顯示更新提示。
 */

if (!defined('ABSPATH')) {
    exit;
}

class FCA_Fluent_ZhTW_Updater {

    private $plugin_slug;
    private $plugin_file;
    private $github_user  = 'fishtvlvoe';
    private $github_repo  = 'fca-fluent-zh-tw';
    private $version;
    private $cache_key    = 'fca_fluent_zhTW_update';
    private $cache_ttl    = 43200; // 12 小時

    /**
     * PHP 7.4 相容：取代 PHP 8+ 的 str_ends_with()
     */
    private function ends_with($haystack, $needle) {
        $haystack = (string) $haystack;
        $needle   = (string) $needle;

        if ($needle === '') {
            return true;
        }

        $len = strlen($needle);
        return substr($haystack, -$len) === $needle;
    }

    public function __construct($plugin_file, $version) {
        $this->plugin_file = $plugin_file;
        $this->plugin_slug = plugin_basename($plugin_file);
        $this->version     = $version;

        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_update']);
        add_filter('plugins_api', [$this, 'plugin_info'], 10, 3);
        add_filter('upgrader_post_install', [$this, 'after_install'], 10, 3);
    }

    /**
     * 從 GitHub 取得最新 Release 資訊
     */
    private function get_release() {
        $cached = get_transient($this->cache_key);
        if ($cached !== false) {
            return $cached;
        }

        $url      = "https://api.github.com/repos/{$this->github_user}/{$this->github_repo}/releases/latest";
        $response = wp_remote_get($url, [
            'timeout' => 10,
            'headers' => ['Accept' => 'application/vnd.github.v3+json'],
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }

        $release = json_decode(wp_remote_retrieve_body($response));
        if (empty($release->tag_name)) {
            return false;
        }

        set_transient($this->cache_key, $release, $this->cache_ttl);
        return $release;
    }

    /**
     * 注入更新資訊到 WordPress 更新機制
     */
    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $release = $this->get_release();
        if (!$release) {
            return $transient;
        }

        $latest_version = ltrim($release->tag_name, 'v');

        if (version_compare($latest_version, $this->version, '>')) {
            // 找到 .zip asset
            $zip_url = '';
            if (!empty($release->assets)) {
                foreach ($release->assets as $asset) {
                    if ($this->ends_with($asset->name, '.zip')) {
                        $zip_url = $asset->browser_download_url;
                        break;
                    }
                }
            }
            // 若無 asset，用 zipball
            if (!$zip_url) {
                $zip_url = $release->zipball_url;
            }

            $transient->response[$this->plugin_slug] = (object) [
                'slug'        => dirname($this->plugin_slug),
                'plugin'      => $this->plugin_slug,
                'new_version' => $latest_version,
                'url'         => "https://github.com/{$this->github_user}/{$this->github_repo}",
                'package'     => $zip_url,
            ];
        }

        return $transient;
    }

    /**
     * 提供外掛詳細資訊（點「查看版本詳情」時顯示）
     */
    public function plugin_info($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return $result;
        }
        if (!isset($args->slug) || $args->slug !== dirname($this->plugin_slug)) {
            return $result;
        }

        $release = $this->get_release();
        if (!$release) {
            return $result;
        }

        return (object) [
            'name'          => 'FCA & Fluent 繁體中文翻譯包',
            'slug'          => dirname($this->plugin_slug),
            'version'       => ltrim($release->tag_name, 'v'),
            'author'        => 'BuyGo',
            'homepage'      => "https://github.com/{$this->github_user}/{$this->github_repo}",
            'sections'      => [
                'description' => '為所有 FCA 系列與 Fluent 系列外掛提供繁體中文翻譯。',
                'changelog'   => nl2br($release->body ?? ''),
            ],
            'download_link' => $release->zipball_url,
        ];
    }

    /**
     * 安裝完成後修正資料夾名稱
     * GitHub zipball 解壓後資料夾名稱含 commit hash，需重新命名
     */
    public function after_install($response, $hook_extra, $result) {
        if (!isset($hook_extra['plugin']) || $hook_extra['plugin'] !== $this->plugin_slug) {
            return $response;
        }

        global $wp_filesystem;
        $plugin_dir = WP_PLUGIN_DIR . '/' . dirname($this->plugin_slug);

        $wp_filesystem->move($result['destination'], $plugin_dir, true);
        $result['destination'] = $plugin_dir;

        // 重新啟用外掛
        activate_plugin($this->plugin_slug);

        return $result;
    }
}
