window.FCA_ZH_TW_DOM_TRANSLATIONS = (function() {
    var fchubMemberships = {
        'Dashboard': '儀表板',
        'Plans': '方案',
        'Members': '成員',
        'Contents': '內容',
        'Content': '內容',
        'Drip': '漸進式開放',
        'Report': '報告',
        'Active Members': '有效會員',
        'New This Month': '本月新增',
        'Churned This Month': '本月流失',
        'Churn Rate': '流失率',
        'Members Over Time': '會員趨勢',
        'Plan Distribution': '方案分布',
        'Expiring Soon': '即將到期',
        'Recent Activity': '近期活動',
        'Status': '狀態',
        'Expires': '到期日',
        'Granted': '授權於',
        'No Data': '暫無資料',
        'Search plans...': '搜尋方案...',
        'Search by plan title or slug': '依方案標題或代稱搜尋',
        'Title': '標題',
        'Slug': '代稱',
        'Duration': '時長',
        'Created': '建立於',
        'No plans found': '找不到方案',
        'Search by name, email or user ID': '依名稱、電子郵件或使用者 ID 搜尋',
        'No members found': '找不到會員',
        'Content Protection': '內容保護',
        'Control what content your members can access': '控制您的會員可以存取的內容',
        'Posts & Pages': '文章與頁面',
        'Categories & Tags': '分類與標籤',
        'Custom Post Types': '自訂文章類型',
        'Menu Items': '選單項目',
        'URL Restrictions': '網址限制',
        'Special Pages': '特殊頁面',
        'Comments': '留言',
        'All': '全部',
        'Search protected content...': '搜尋受保護的內容...',
        'Resource': '資源',
        'Type': '類型',
        'Teaser': '前導文字',
        'Protected Since': '受保護於',
        'No protected content yet': '尚無受保護的內容',
        'Start protecting your content to restrict access for members only.': '開始保護您的內容，僅限會員存取。',
        'Drip Content': '漸進式開放內容',
        'Total Drip Rules': '漸進式開放規則總數',
        'Pending Notifications': '待處理通知',
        'Sent Today': '今日已發送',
        'Failed': '失敗',
        'Notifications Queue': '通知排程隊列',
        'User': '使用者',
        'Scheduled': '已排程',
        'Start date': '開始日期',
        'End date': '結束日期',
        'to': '至',
        'Churn': '流失',
        'Revenue': '營收',
        'Renewals': '續約',
        'Trials': '試用',
        'Retention': '留存',
        'Churned': '已流失',
        'Retention Rate': '留存率',
        'General Settings': '一般設定',
        'Restriction Mode': '限制模式',
        'Membership Rules': '會員規則'
    };

    var merged = {};
    var sources = [fchubMemberships];
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
