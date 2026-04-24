<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * FCA Hub 更新伺服器回傳的 name / description 無法走 .po，僅能在此以 slug 對照覆寫。
 * 若 slug 與官方 registry 不一致，該列會維持英文（不影響運作）。
 */
return [
    'fca-boards' => [
        'name' => '看板',
        'description' => '在 FluentCommunity 內使用看板式整理與協作（Kanban）。',
    ],
    'fca-comments' => [
        'name' => '社群留言',
        'description' => '以 Fluent Community 討論串取代 WordPress 預設留言。',
    ],
    'fca-content-manager' => [
        'name' => '內容管理',
        'description' => '集中管理社群資料庫、媒體與多語系字串等內容。',
    ],
    'fca-course-blocks' => [
        'name' => '課程區塊',
        'description' => '為 FluentCommunity 課程單元設計的 Gutenberg 區塊。',
    ],
    'fca-display-name' => [
        'name' => '自訂顯示名稱',
        'description' => '覆寫 Fluent Community 顯示名稱規則，改為自訂格式。',
    ],
    'fca-events' => [
        'name' => 'Events（舊版）',
        'description' => '建立與管理社群活動、報名與日曆檢視（舊版，未來由 Events 基礎模組取代）。',
    ],
    'fca-events-basic' => [
        'name' => 'Events（基礎）',
        'description' => '社群活動核心模組；其他 Events 延伸功能需依此運作。',
    ],
    'fca-events-spaces' => [
        'name' => 'Events（空間）',
        'description' => '在 FluentCommunity 空間中加入活動功能。',
    ],
    'fca-global-search' => [
        'name' => '全站搜尋',
        'description' => '整合搜尋 WordPress 與 FluentCommunity 內容。',
    ],
    'fca-knowledgebase' => [
        'name' => '知識庫',
        'description' => '在社群內提供知識庫／說明文件結構。',
    ],
    'fca-multi-reactions' => [
        'name' => '多重反應',
        'description' => '為貼文與留言新增多種反應類型。',
    ],
    'fca-pages' => [
        'name' => '頁面',
        'description' => '擴充 FluentCommunity 的頁面與版型相關能力。',
    ],
    'fca-push-notifications' => [
        'name' => '推播通知',
        'description' => '向使用者推播重要社群動態。',
    ],
    'fca-pwa' => [
        'name' => 'PWA',
        'description' => '為社群網站加入漸進式網頁應用程式（PWA）支援。',
    ],
    'fca-real-time' => [
        'name' => '即時更新',
        'description' => '即時顯示社群動態與更新。',
    ],
    'fca-widgets' => [
        'name' => '小工具',
        'description' => 'FluentCommunity 相關的小工具與側邊欄區塊。',
    ],
];
