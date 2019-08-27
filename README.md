# Magento 2 Email Attachments extension

[Magento 2 Email Attachments extension](http://www.mageplaza.com/magento-2-email-attachments/) enable the administrator to attach necessary documents such as PDF Invoice, Shipment, Credit Memo as well as add Terms and Conditions to sales emails. With this extension, customers are able to follow all information during the purchase process by checking emails from the store.

[![Latest Stable Version](https://poser.pugx.org/mageplaza/module-email-attachments/v/stable)](https://packagist.org/packages/mageplaza/module-email-attachments)
[![Total Downloads](https://poser.pugx.org/mageplaza/module-email-attachments/downloads)](https://packagist.org/packages/mageplaza/module-email-attachments)


## 1. Documentation

- [Installation guide](https://www.mageplaza.com/install-magento-2-extension/)
- [User guide](https://docs.mageplaza.com/email-attachments/index.html)
- [Introduction page](https://www.mageplaza.com/magento-2-email-attachments/)
- [Contribute on Github](https://github.com/mageplaza/magento-2-email-attachments)
- [Get Support](https://github.com/mageplaza/magento-2-email-attachments/issues)

## 2. FAQs

**Q: I got error: Mageplaza_Core has been already defined**

A: Read solution [here](https://github.com/mageplaza/module-core/issues/3).

**Q: Will a recipient see the recipients’ emails full list?**

A: There are 2 email sending options: CC and BCC. CC reveals the full list to all, while BCC conceals this list.

**Q: Does the Terms and Conditions attachment support other file formats rather than .doc?**

A: Absolutely yes, possible file formats are .pdf, .doc, .docx, .txt

**Q: Is this extension compatible with other extensions?**

A: Yes, Email Attachments are properly compatible with [Mageplaza SMTP](https://www.mageplaza.com/magento-2-smtp/) and [PDF Invoice](https://www.mageplaza.com/magento-2-pdf-invoice-extension/).  

**Q: Will the email reach recipients successfully?**

A: It is possible that emails sent from default Magento 2 server are judged as unregistered emails, which means the reputation for these emails is pretty low and they will be classified as spam trashes. Using Mageplaza SMTP module additionally will help you increase almost 99% possibility that your emails and attachments approach customers successfully.

## 3. How to install Magento 2 Email Attachment

Install via composer (recommend)

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-email-attachments
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## 4. Mageplaza Email Attachments Highlight Features

### Attack PDF billing documents to Invoice Email

Email Attachments from Mageplaza supports the store owner in attaching important PDF billing documents as Invoice, Credit Memo, and Shipment to the sale emails automatically. PDF files for these billing documents are created and attached systematically to emails sent to recipients.

![Magento 2 attach pdf to invoice email](https://i.imgur.com/0nGnkcb.png)

### Enable attaching Terms and Conditions

Magento 2 Email Attachments also enables Terms and Conditions to be attached in sent emails. In another word, after a customer creates an order, an order confirmation email along with Terms and Conditions file will be sent. This process keeps customers being noticed about the store’s policy concisely and evidently.

![Magento 2 send email with attachment](https://i.imgur.com/3I7X6bi.png)

### Various available format types

There are 4 Terms and Conditions file types, which are .pdf, .doc, .txt and .doc. The administrator can flexibly upload appropriate types for specific purposes or specific target audiences when attaching to emails. 

![magento 2 email attachments](https://i.imgur.com/HtETMdA.png)

### Multi-recipient attachments 

The extension includes CC and BCC, which assists the store owner to send copies of the emails to additional recipients. CC addresses are transparent, meanwhile, BCC conceals the recipients list to all. The store owner can make use of this feature in specific situations and requirements.

![magento 2 order email attachment](https://i.imgur.com/DUzL5sZ.png?1)

## 5. More features

#### Extension compatibility 

**Mageplaza Email Attachments Extension** is compatible and totally works well with **[Mageplaza PDF Invoice](https://www.mageplaza.com/magento-2-pdf-invoice-extension/)** and **[SMTP](https://github.com/mageplaza/magento-2-smtp)**. These three extensions support each others and help you reach customer efficiently, say goodbye to the spam box.

## 6. Full Features List 

### For store owners

- Enable/disable the module
- Optional email sending modes: CC/BCC
- Possible to select billing documents to attach PDF file: Invoice, Shipment, Credit Memo
- Enable/disable Terms and Conditions file attachment
- Optional billing documents to attach Terms and Conditions file: Order, Invoice, Shipment, Credit Memo
- 4 formats are possible to upload Terms and Conditions file: .pdf, .doc, .docx, .txt
- Compatible with Mageplaza PDF Invoice and SMTP.

### For customers

- Notify stores’ policy early via email when making purchases
- Get store’s order informations such as Invoice, Shipment, Credit Memo adequately and concisely 
- Possible to follow necessary purchasing information easily via emails

## 7. Email Attachment User Guide

Login to the `Magento Admin`, navigate to `Store > Configuration > Mageplaza > Email Attachments`

![Email Attachment User Guide](https://i.imgur.com/VXNwjRa.gif)

### General Configuration

![Magento 2 Email Attachment General Configuration](https://i.imgur.com/hPrIvIl.png)

- **Enable**: Select `Yes` to enable the module, and `No` to disable.
- **CC to emails**:
  - Insert email addresses to send Attachments file copy to your targeted recipients.
  - Possible to insert multiple emails, with this mode recipients will see other CC recipients list. These emails are separated by commas.
- **BCC to emails**: 
  - Insert email addresses to send Attachments file copy to your targeted recipients.
  - Possible to insert multiple emails, and recipients will not see other BCC recipients list. These emails are separated by commas.
- **Enable Attach PDF**: Select `Yes` to automatically attach PDF billing documents to emails. Following fields will be displayed:
  - **Attach PDF file for**:  Select the section which will contain Attach attach PDF file in emails. Terms and Conditions will be attached to the above billing document(s) as a file. Display in:
    - Invoice
    - Credit memo
    - Shipment

![Magento 2 Email Attachment General Configuration](https://i.imgur.com/n0g25LC.png)

- **Enable Attach Terms and Conditions**: Select `Yes` to display Attach Terms and Conditions in emails. Right here 2 more fields can be displayed:
  - **Attach Terms and Conditions**: Select the section which will contain Attach Terms and Conditions in emails. Terms and Conditions will be attached to the above billing document(s) as a file. Display in:
    - Order
    - Invoice
    - Credit memo
    - Shipment
  - **Terms and Conditions file**:
    - Click `Choose file` button to upload PDF file for Terms and Conditions.
    - Possible file formats: .pdf, .doc, .docx, .txt.


**People also search:**
- magento 2 email attachment
- magento 2 send email with attachment
- magento 2 add attachment to email
- magento 2.3 add attachment to email
- magento 2 order email attachment
- magento 2 order attachment
- magento 2.3 email attachment
- magento 2 attach pdf to invoice email
- magento 2 order attachment extension


**Other free Magento 2 extension on Github**
- [Magento 2 SEO extension](https://github.com/mageplaza/magento-2-seo)
- [Magento 2 Google Maps](https://github.com/mageplaza/magento-2-google-maps)
- [Magento 2 Same Order Number](https://github.com/mageplaza/magento-2-same-order-number)
- [Magento 2 Advanced Reporting](https://github.com/mageplaza/magento-2-reports)
- [Magento 2 social login](https://github.com/mageplaza/magento-2-social-login)
- [Magento 2 blog module](https://github.com/mageplaza/magento-2-blog)
- [Magento 2 Layered Navigation](https://github.com/mageplaza/magento-2-ajax-layered-navigation)
- [Magento 2 security module](https://github.com/mageplaza/magento-2-security)








