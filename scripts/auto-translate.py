#!/usr/bin/env python3
"""
自動翻譯更新腳本
Usage: python3 scripts/auto-translate.py <domain> <pot-path>
Example: python3 scripts/auto-translate.py fluent-cart /path/to/fluent-cart.pot
"""

import sys
import os
import datetime
import polib
from collections import OrderedDict

# ── 翻譯字典：domain -> {msgid: msgstr}
TRANSLATIONS = {
    "fluent-cart": {
        "A full refund has been processed for %s. Here are the details:": "%s 的全額退款已處理。詳情如下：",
        "A partial refund has been processed for %s. Here are the details:": "%s 的部分退款已處理。詳情如下：",
        "A subscription has been canceled. Review the details in this email or visit FluentCart Dashboard to manage the subscription.": "一筆訂閱已取消。請查看此電子郵件中的詳情，或前往 FluentCart 控制台管理訂閱。",
        "A subscription renewal reminder was sent to a customer. Review subscription details from FluentCart Dashboard.": "訂閱續訂提醒已發送給客戶。請前往 FluentCart 控制台查看訂閱詳情。",
        "A trial ending soon reminder was sent to a customer. Review subscription details from FluentCart Dashboard.": "試用期即將結束的提醒已發送給客戶。請前往 FluentCart 控制台查看訂閱詳情。",
        "Activate Plugin": "啟用外掛",
        "Add Custom Template": "新增自訂模板",
        "Add Template": "新增模板",
        "All countries except": "所有國家，除了",
        "All except: %s": "除了：%s",
        "Are you sure you want to delete all test orders? This action cannot be undone.": "確定要刪除所有測試訂單嗎？此操作無法復原。",
        "Are you sure you want to delete this view?": "確定要刪除此檢視嗎？",
        "Attach PDF Template": "附加 PDF 模板",
        "Available Quantity": "可用數量",
        "Bank Account Name": "銀行帳戶名稱",
        "Bank BIC": "銀行 BIC",
        "Bank IBAN": "銀行 IBAN",
        "Bank Identifier Code (SWIFT)": "銀行識別碼（SWIFT）",
        "Both Site Key and Secret Key are required.": "網站金鑰和密鑰皆為必填。",
        "Built-in": "內建",
        "%s Pricing is deleted, while product variation is changed to 'Simple'": "已刪除 %s 定價，因為商品變體已更改為「簡單」",
        "Company registration number (e.g. HRB 12345)": "公司登記號碼（例如 HRB 12345）",
        "cycle": "週期",
        "day": "天",
        "Configure ZUGFeRD / Factur-X e-invoice encoding and seller details for your PDFs.": "設定 ZUGFeRD / Factur-X 電子發票編碼以及 PDF 中的賣家資訊。",
        "Contact Email": "聯絡電子郵件",
        "Contact Name": "聯絡人姓名",
        "Contact Phone": "聯絡電話",
        "Could not connect to Cloudflare. Please try again.": "無法連線到 Cloudflare，請重試。",
        "Could not get a Turnstile token. Please check your Site Key and ensure this domain is allowed in Cloudflare.": "無法取得 Turnstile 權杖。請檢查您的網站金鑰，並確認此網域已在 Cloudflare 中允許。",
        "Could not verify PayPal payment from webhook. Charge ID: ": "無法透過 webhook 驗證 PayPal 付款。交易編號：",
        "Coverage": "涵蓋範圍",
        "Customizable PDF receipts, invoices, and e-invoices with ZUGFeRD support.": "可自訂的 PDF 收據、發票與電子發票，支援 ZUGFeRD。",
        "Customize PDF templates for email attachments. Select which template to attach in each email notification's settings.": "自訂電子郵件附件的 PDF 模板。在每個電子郵件通知設定中選擇要附加的模板。",
        "Default receipt template for paid orders": "已付款訂單的預設收據模板",
        "Delete Template": "刪除模板",
        "Delete Test Orders": "刪除測試訂單",
        "Delete this template?": "刪除此模板？",
        "Delete View": "刪除檢視",
        "Deleted": "已刪除",
        "Deleted %1$s of %2$s Test Orders": "已刪除 %1$s / %2$s 筆測試訂單",
        "Deleted %1$s test orders. %2$s test orders could not be deleted.": "已刪除 %1$s 筆測試訂單。%2$s 筆測試訂單無法刪除。",
        "Deleted %s test orders successfully": "已成功刪除 %s 筆測試訂單",
        "Deleting %1$s of %2$s Test Orders": "正在刪除 %1$s / %2$s 筆測試訂單",
        "Deleting Test Orders...": "正在刪除測試訂單...",
        "Deleting test orders...": "正在刪除測試訂單...",
        "Deleting... %s%": "正在刪除... %s%",
        "Downloading...": "下載中...",
        'Duplicate SKU "%s" within this product.': "此商品內有重複的 SKU「%s」。",
        "E-Invoice Configuration": "電子發票設定",
        "E-Invoice Settings": "電子發票設定",
        "e.g. Custom Invoice": "例如：自訂發票",
        "Edit Template": "編輯模板",
        "Electronic Address": "電子地址",
        "Electronic address for receiving e-invoices (e.g. PEPPOL ID)": "接收電子發票的電子地址（例如 PEPPOL ID）",
        "Enter a new name": "輸入新名稱",
        "Failed to activate plugin": "啟用外掛失敗",
        "Failed to delete order": "刪除訂單失敗",
        "Failed to delete test orders": "刪除測試訂單失敗",
        "Failed to delete view": "刪除檢視失敗",
        "Failed to generate PDF": "產生 PDF 失敗",
        "Failed to install plugin": "安裝外掛失敗",
        "Failed to load settings": "載入設定失敗",
        "Failed to rename view": "重新命名檢視失敗",
        "Failed to revert template": "還原模板失敗",
        "Failed to save view": "儲存檢視失敗",
        "Failed to update view": "更新檢視失敗",
        "Fees": "費用",
        "Fluent PDF": "Fluent PDF",
        "Fluent PDF Required": "需要 Fluent PDF",
        "Generate PDF receipts and attach them to email notifications.": "產生 PDF 收據並附加到電子郵件通知中。",
        "Go to Store Settings": "前往商店設定",
        "International Bank Account Number": "國際銀行帳戶號碼（IBAN）",
        "Invalid Turnstile keys. Please check your Site Key and Secret Key.": "Turnstile 金鑰無效。請檢查您的網站金鑰和密鑰。",
        "Invoice": "發票",
        "Invoice template for offline/pending orders": "離線/待處理訂單的發票模板",
        "ISO 6523 ICD code for the legal registration identifier": "法律登記識別碼的 ISO 6523 ICD 代碼",
        "License": "授權",
        "Legal Name": "法定名稱",
        "Legal Registration ID": "法定登記編號",
        "Legal Registration Scheme": "法定登記方案",
        "Make public (visible to all users)": "設為公開（所有使用者可見）",
        "Max Price": "最高價格",
        "month": "月",
        "Maximum of 20 saved views reached": "已達儲存檢視上限（最多 20 個）",
        "Min Price": "最低價格",
        "Name is required": "名稱為必填",
        "Name must be 50 characters or fewer": "名稱不得超過 50 個字元",
        "Name of the bank account holder": "銀行帳戶持有人姓名",
        "National tax identification number": "國家稅籍編號",
        "No orders were deleted": "沒有訂單被刪除",
        "No PDF attachment": "無 PDF 附件",
        "No test orders found to delete": "找不到可刪除的測試訂單",
        "Notice template for refund confirmations": "退款確認通知模板",
        "Only ships to the countries listed above.": "只運送至上方列出的國家。",
        "Optional description": "選填說明",
        "Payment amount mismatch detected. Expected: %1$s, Received: %2$s. This may indicate payment tampering.": "偵測到付款金額不符。預期：%1$s，收到：%2$s。這可能表示付款遭到竄改。",
        "Payment amount mismatch detected. Expected: %1$s, Received: %2$s. Transaction: %3$s. Order not confirmed.": "偵測到付款金額不符。預期：%1$s，收到：%2$s。交易：%3$s。訂單未確認。",
        "Payment currency does not match with transaction currency!": "付款貨幣與交易貨幣不符！",
        "Payment currency mismatch detected. Expected: %1$s, Received: %2$s. This may indicate payment tampering.": "偵測到付款貨幣不符。預期：%1$s，收到：%2$s。這可能表示付款遭到竄改。",
        "Payment currency mismatch detected. Expected: %1$s, Received: %2$s. Transaction: %3$s. Order not confirmed.": "偵測到付款貨幣不符。預期：%1$s，收到：%2$s。交易：%3$s。訂單未確認。",
        "PayPal Amount Mismatch Attempt": "PayPal 金額不符嘗試",
        "PayPal Currency Mismatch Attempt": "PayPal 貨幣不符嘗試",
        "PayPal Recurring Plan Mismatch": "PayPal 週期性方案不符",
        "PayPal Subscription Amount Mismatch": "PayPal 訂閱金額不符",
        "PayPal subscription billing amount mismatch. Expected: %1$s, Received: %2$s. Subscription not activated.": "PayPal 訂閱帳單金額不符。預期：%1$s，收到：%2$s。訂閱未啟用。",
        "PayPal subscription plan does not match the expected plan.": "PayPal 訂閱方案與預期方案不符。",
        "PayPal Subscription Plan Mismatch": "PayPal 訂閱方案不符",
        "PayPal subscription plan mismatch. Expected: %1$s, Received: %2$s. Subscription not activated.": "PayPal 訂閱方案不符。預期：%1$s，收到：%2$s。訂閱未啟用。",
        "PayPal Webhook Amount Mismatch": "PayPal Webhook 金額不符",
        "PayPal Webhook Currency Mismatch": "PayPal Webhook 貨幣不符",
        "PayPal Webhook Verification Failed": "PayPal Webhook 驗證失敗",
        "PDF downloaded": "PDF 已下載",
        "PDF Templates": "PDF 模板",
        "quarter": "季",
        "Please enter a template name": "請輸入模板名稱",
        "Please select at least one country.": "請至少選擇一個國家。",
        "Plugin activated successfully": "外掛已成功啟用",
        "Plugin installed successfully": "外掛已成功安裝",
        "Pro": "專業版",
        "Processed": "已處理",
        "Processed %1$s of %2$s test orders...": "已處理 %1$s / %2$s 筆測試訂單...",
        "Product image thumbnails": "商品圖片縮圖",
        "Receipt template for subscription renewal payments": "訂閱續訂付款的收據模板",
        "Recurring payment plan mismatch. Expected: %1$s, Received: %2$s. Subscription ID: %3$d. Payment not recorded.": "週期性付款方案不符。預期：%1$s，收到：%2$s。訂閱編號：%3$d。付款未記錄。",
        "Refund Notice": "退款通知",
        "Registered legal name of the seller": "賣家的法定註冊名稱",
        'Reminder emails must also be enabled in %1$sEmail Notification Settings%2$s under "Scheduler / Reminder Actions" to be delivered.': '提醒電子郵件也必須在 %1$s電子郵件通知設定%2$s 的「排程器 / 提醒動作」中啟用，才會送出。',
        "Rename": "重新命名",
        "Rename View": "重新命名檢視",
        "Renewal Receipt": "續訂收據",
        "Revert to Default": "還原為預設",
        "Reverted to default template": "已還原為預設模板",
        "Save as view": "儲存為檢視",
        "Save Template": "儲存模板",
        "Save View": "儲存檢視",
        "Saved Views": "已儲存的檢視",
        "Saved views is only available in pro version": "儲存檢視功能僅在專業版提供",
        "six month": "半年",
        "Security check failed. Please refresh the page and try again.": "安全檢查失敗。請重新整理頁面後再試。",
        "Security check failed. Please try again or refresh the page.": "安全檢查失敗。請重試或重新整理頁面。",
        "Select scheme": "選擇方案",
        "Select the e-invoice formatter used when generating the embedded XML.": "選擇產生內嵌 XML 時使用的電子發票格式化工具。",
        "Selected countries only": "僅選定的國家",
        "Seller Bank Details": "賣家銀行資訊",
        "Seller Contact": "賣家聯絡資訊",
        "Seller Tax Details": "賣家稅務資訊",
        "Seller Tax ID": "賣家稅籍編號",
        "Seller VAT ID": "賣家統一編號",
        "Settings saved": "設定已儲存",
        "Ship everywhere, excluding specific countries": "運送至所有國家，排除特定國家",
        "Ship exclusively to the countries you choose": "僅運送至您選擇的國家",
        "Ship to every country and territory": "運送至每個國家與地區",
        "Ships everywhere except the countries listed above.": "運送至所有國家，除了上方列出的國家。",
        'SKU "%s" is already in use by another product.': "SKU「%s」已被其他商品使用。",
        'SKU "%s" is already in use.': "SKU「%s」已被使用。",
        "Store country is not configured.": "尚未設定商店國家。",
        "Subscription activation failed.": "訂閱啟用失敗。",
        "Template created": "模板已建立",
        "Template deleted": "模板已刪除",
        "Template Name": "模板名稱",
        "Template saved": "模板已儲存",
        "Test Download": "測試下載",
        "Test order deletion completed.": "測試訂單刪除完成。",
        "The Secret Key is invalid. Please check it in your Cloudflare Dashboard.": "密鑰無效。請在您的 Cloudflare 控制台中檢查。",
        "This order has some due amount. Please complete the payment.": "此訂單尚有應付金額。請完成付款。",
        'This will permanently delete \\"%s\\" and its %s variant(s).': "這將永久刪除「%s」及其 %s 個變體。",
        'This will permanently delete \\"%s\\".': "這將永久刪除「%s」。",
        'This will remove \\"%s\\" and its %s variant(s).': "這將移除「%s」及其 %s 個變體。",
        'This will remove \\"%s\\".': "這將移除「%s」。",
        'This will remove the variant \\"%s\\".': "這將移除變體「%s」。",
        "To use PDF receipt templates, you need to install and activate the Fluent PDF plugin.": "若要使用 PDF 收據模板，您需要安裝並啟用 Fluent PDF 外掛。",
        "week": "週",
        "year": "年",
        "Turnstile keys are valid and working.": "Turnstile 金鑰有效且運作正常。",
        "Type to search countries...": "輸入以搜尋國家...",
        "Unable to cancel subscription at this time. Please try again later or contact support.": "目前無法取消訂閱。請稍後再試或聯絡客服。",
        "Unknown Variant": "未知變體",
        "until canceled": "直到取消為止",
        "Update filters": "更新篩選條件",
        'Use {percent} as placeholder. E.g., \\"-{percent}% OFF\\"': '使用 {percent} 作為佔位符。例如：「-{percent}% OFF」',
        "VAT identification number (e.g. DE123456789)": "加值稅識別號碼（例如 DE123456789）",
        "Verification failed. Please check your keys.": "驗證失敗。請檢查您的金鑰。",
        "Verify Keys": "驗證金鑰",
        "View name": "檢視名稱",
        "View Receipt": "查看收據",
        "Whole world": "全球",
        "You can close this — deletion will continue in the background": "您可以關閉此視窗——刪除將在背景繼續執行",
        "You got a new order on your shop. Congratulations! Checkout all the details in this email. You can also go to FluentCart Dashboard to view the order details and manage it. Thank you for using FluentCart.": "您的商店收到一筆新訂單。恭喜！請查看此電子郵件中的所有詳情。您也可以前往 FluentCart 控制台查看訂單詳情並進行管理。感謝您使用 FluentCart。",
        "You got a new Renewal on your shop. Congratulations! Checkout all the details in this email. You can also go to FluentCart Dashboard to view the order details and manage it. Thank you for using FluentCart.": "您的商店收到一筆續訂。恭喜！請查看此電子郵件中的所有詳情。您也可以前往 FluentCart 控制台查看訂單詳情並進行管理。感謝您使用 FluentCart。",
        "You have a new order on your shop placed with offline payment. Please review the order details in this email. You can also go to FluentCart Dashboard to view the order details and manage it. Thank you for using FluentCart.": "您的商店收到一筆離線付款的新訂單。請查看此電子郵件中的訂單詳情。您也可以前往 FluentCart 控制台查看訂單詳情並進行管理。感謝您使用 FluentCart。",
        "ZUGFeRD Profile": "ZUGFeRD 設定檔",
        "ZUGFeRD XML cannot be embedded in PDFs until your store country is set.": "在設定商店國家之前，無法在 PDF 中嵌入 ZUGFeRD XML。",
    }
}


def update_po(domain, pot_path):
    script_dir = os.path.dirname(os.path.abspath(__file__))
    repo_root = os.path.join(script_dir, '..')
    po_path = os.path.join(repo_root, 'languages', f'{domain}-zh_TW.po')

    if not os.path.exists(po_path):
        print(f"❌ 找不到 {po_path}")
        sys.exit(1)

    if not os.path.exists(pot_path):
        print(f"❌ 找不到 {pot_path}")
        sys.exit(1)

    pot = polib.pofile(pot_path)
    po = polib.pofile(po_path)

    # 建立舊翻譯對照表
    old_entries = OrderedDict()
    for entry in po:
        if entry.msgid == '':
            continue
        old_entries[entry.msgid] = entry

    pot_ids = {e.msgid for e in pot if e.msgid != ''}
    trans_map = TRANSLATIONS.get(domain, {})

    new_count = 0
    obsolete_count = 0
    updated_count = 0

    # 更新 po：以 pot 為基準
    new_po_entries = []
    for entry in pot:
        if entry.msgid == '':
            # header
            new_po_entries.append(entry)
            continue

        def set_translation(e, text):
            if e.msgid_plural:
                e.msgstr_plural = {0: text}
            else:
                e.msgstr = text

        if entry.msgid in old_entries:
            old_entry = old_entries[entry.msgid]
            # 若舊翻譯為空，且字典中有對應翻譯，則補上
            old_empty = not old_entry.msgstr and (not old_entry.msgid_plural or all(not v for v in old_entry.msgstr_plural.values()))
            if old_empty and entry.msgid in trans_map:
                set_translation(entry, trans_map[entry.msgid])
                updated_count += 1
            else:
                # 保留舊翻譯
                if old_entry.msgid_plural:
                    entry.msgstr_plural = dict(old_entry.msgstr_plural)
                else:
                    entry.msgstr = old_entry.msgstr
                entry.flags = old_entry.flags
                entry.comment = old_entry.comment
                entry.tcomment = old_entry.tcomment
        else:
            # 新增條目
            if entry.msgid in trans_map:
                set_translation(entry, trans_map[entry.msgid])
                new_count += 1
            else:
                set_translation(entry, '')
                entry.flags.append('fuzzy')
                new_count += 1

        new_po_entries.append(entry)

    # 計算刪除的過時條目
    for msgid in old_entries:
        if msgid not in pot_ids:
            obsolete_count += 1

    # 重建 po 檔
    new_po = polib.POFile()
    new_po.header = po.header
    new_po.metadata = dict(po.metadata)
    new_po.metadata['PO-Revision-Date'] = datetime.datetime.now().strftime('%Y-%m-%d %H:%M+0800')
    new_po.metadata['X-Generator'] = 'auto-translate.py (polib)'

    for entry in new_po_entries:
        new_po.append(entry)

    # 備份舊檔
    backup_path = po_path + '.backup'
    os.rename(po_path, backup_path)

    new_po.save(po_path)
    print(f"✅ 更新完成：{domain}")
    print(f"   新增翻譯：{new_count}")
    print(f"   移除過時：{obsolete_count}")
    print(f"   備份檔案：{backup_path}")
    return po_path


if __name__ == '__main__':
    if len(sys.argv) < 3:
        print("Usage: python3 scripts/auto-translate.py <domain> <pot-path>")
        sys.exit(1)

    domain = sys.argv[1]
    pot_path = sys.argv[2]
    update_po(domain, pot_path)
