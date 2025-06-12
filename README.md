# 🧹 WordPress Menu Cleaner Plugin

[![WordPress](https://img.shields.io/badge/WordPress-4.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-5.6%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

A lightweight WordPress plugin that helps maintain clean navigation menus by identifying and removing invalid menu items automatically.

---

## ✨ Features

### 🔍 **Smart Scanning**
Automatically detects invalid menu items across all your WordPress navigation menus

### 🎯 **Selective Cleaning** 
Choose which menus to scan and clean with an intuitive checkbox interface

### 🛠️ **Multiple Validation Types**
Identifies various types of broken menu items:
- 📄 Deleted or trashed posts/pages
- 🏷️ Missing categories and tags
- 🔗 Empty or invalid custom URLs
- ❌ Menu items with missing titles

### 🛡️ **Safe Operations**
Scan first, then decide what to clean with clear confirmation prompts

### 📊 **Detailed Reporting**
Shows exactly what was found and provides clear reasons for each invalid item

### 💻 **User-Friendly Interface**
Clean, responsive admin interface integrated into WordPress Tools menu

---

## 📦 Installation

1. Upload the plugin files to `/wp-content/plugins/menu-cleaner/`
2. Activate the plugin through the **'Plugins'** screen in WordPress
3. Navigate to **Tools → Menu Cleaner** in your WordPress admin

---

## 🚀 Usage

| Step | Action | Description |
|------|--------|-------------|
| 1️⃣ | **Select Menus** | Choose which navigation menus you want to scan |
| 2️⃣ | **Scan** | Click "Scan Selected Menus" to identify invalid items |
| 3️⃣ | **Review** | Check the detailed report of found issues |
| 4️⃣ | **Clean** | Remove invalid items with the "Clean Invalid Items" button |

---

## 🗑️ What Gets Cleaned

| Issue Type | Description |
|------------|-------------|
| 💀 **Broken Post/Page Links** | Menu items pointing to deleted or trashed content |
| 🏷️ **Missing Taxonomy Links** | Categories or tags that no longer exist |
| 🔗 **Invalid Custom URLs** | Empty URLs or placeholder links (`#`) |
| ❓ **Empty Menu Items** | Items without titles or proper configuration |

---

## ⚙️ Requirements

```
WordPress: 4.0+
PHP: 5.6+
Permissions: Administrator privileges
```

---

## 🔒 Security Features

- ✅ Nonce verification for all AJAX operations
- ✅ Capability checks to ensure only administrators can use the tool
- ✅ Sanitized input handling

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This plugin is licensed under the **GPL v2 or later**.

---

## ⚠️ Important Note

> **Always backup your website before making bulk changes to your menus.** While this plugin is designed to be safe, it's always better to be cautious with automated cleanup tools.

---

## 📈 Stats

- 🎯 **Zero Dependencies** - Pure WordPress implementation
- ⚡ **Lightweight** - Minimal resource usage
- 🔧 **Easy Setup** - Works out of the box
- 🛡️ **Secure** - Multiple security layers

---

<div align="center">

**Made with ❤️ for the WordPress community**

[Report Bug](../../issues) • [Request Feature](../../issues) • [Documentation](../../wiki)

</div>
