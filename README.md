# 100video — Multi-Site Video Portal (Based on PHP-Fusion)

**100video** is a customized PHP script designed to create and manage **multiple video websites** from a **single codebase**, with each site connected to its **own database** depending on the domain. It is a fork of the **PHP-Fusion CMS** and tailored specifically for video content portals.

## 📽 What is it for?

With **100video**, you can deploy **multiple independent video sites** (e.g., `videosite1.com`, `videosite2.com`, etc.) using just **one shared script**, saving resources and simplifying maintenance.

Each video site runs independently, has its own content, users, and settings — yet all are managed through a shared core structure.

---

## 🌐 Key Features

- ✅ **Multi-Site Support**: Host multiple video portals from a single codebase.
- 🎞 **Video-Centric Design**: Built specifically for video publishing, categories, and playback.
- 🌐 **Domain-Based Routing**: Automatically selects the correct database by domain name.
- 🔐 **Separate Admin Panels**: Each site has its own admin area and content.
- 💡 **Based on PHP-Fusion**: Leverages the lightweight PHP-Fusion CMS core.

---

## 🧩 Folder and File Overview

- `config.php` – Stores domain-based DB configs.
- `maincore.php` – Core logic of PHP-Fusion and video enhancements.
- `video/` – Custom modules for uploading and managing videos.
- `themes/` – Includes portal-specific themes and layouts.
- `administration/` – Admin panel for managing each individual portal.

---

## 🚀 How to Set Up

### 1. Upload the script to your server

Place all files in your web root (`public_html/`, `www/`, etc.).

### 2. Configure domain databases

Edit `config.php` to define DB credentials for each video domain:

```php
if ($_SERVER['HTTP_HOST']=="100video.loc") {
	// database settings
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "100video";
	$db_prefix = "c2y_";
	define("DB_PREFIX", "c2y_");
	define("COOKIE_PREFIX", "mz4_");
	define("SITE", "site1");
	define("ROBOTS", "Disallow");
}



3. Import video portal database schema

Use the provided SQL file (or PHP-Fusion schema) to initialize the databases.

4. Point each domain to the same codebase

Ensure video1.com, video2.com, etc. all point to the same script directory.

⸻

🎛 Admin Panel

Each domain has an independent admin panel via video1.com/login, video2.com/login, etc. You can manage videos, users, and categories for each portal separately.

⸻

📌 Use Cases
	•	Media companies managing multiple niche video websites.
	•	Hosting regionalized or language-specific video portals.
	•	Centralized video CMS for clients from a single deployment.

⸻

⚠️ Security & Compatibility
	•	Tested with PHP 7.0+.
	•	Add config.php to .gitignore to protect DB credentials.
	•	Migration to PDO or MySQLi is recommended for modern security.


✍ Author

Azad Lezgi
📧 Email: [azadlezgi@yandex.ru]
🔗 GitHub: github.com/azadlezgi
