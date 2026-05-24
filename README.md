# 🕉️ Bhagwan Vishwakarma Mandir

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Database](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Web Server](https://img.shields.io/badge/Apache-2.4%2B-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://httpd.apache.org/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Language](https://img.shields.io/badge/Bilingual-EN%20%7C%20HI-orange?style=for-the-badge)](https://github.com/sharmanish011-maker/Bhagwan-Vishwakarma-Mandir)

Welcome to the official developer documentation for the **Bhagwan Vishwakarma Mandir** web portal. This platform is a fully-featured, custom-built bilingual (English & Hindi) content management and booking application designed to serve the devotees of Lord Vishwakarma—the divine architect of the universe.

The application allows temple administrators to showcase visual galleries, announce spiritual events, process virtual donations, handle online Puja and Seva bookings, and publish articles, all wrapped in a responsive, modern interface.

---

## 🌟 Key Platform Features

- **🌐 Dynamic Bilingual Support**: A complete custom session-based translation dictionary (`en` & `hi`) that translates the UI instantly, including localized database inputs, event descriptions, and temple guidelines.
- **📅 Puja & Seva Booking Engine**: A seamless form wizard allowing devotees to schedule Pujas, specify their Gotras, pick convenient time slots, and register the number of attendees.
- **🖼️ Premium Media Showcase**: A smooth, GLightbox-powered media and video gallery loaded with high-resolution spiritual assets and architecture highlights.
- **📢 Events & Announcements**: A dynamic dashboard listing both ongoing, finished, and upcoming religious festivals (e.g., Vishwakarma Jayanti, Diwali celebrations) with localized short summaries.
- **🛡️ Secure Administrative Panel**: An interactive backend portal featuring access control, rate-limited login security, and real-time dashboard analytics.
- **❤️ Virtual Seva & Donations**: Clear online donation guidelines and options assisting devotees in contributing to community service and temple maintenance.

---

## 🛠️ Technology Stack

| Layer | Technology | Details |
| :--- | :--- | :--- |
| **Backend Core** | PHP 8.0+ | Lightweight procedural routing, strictly typed helpers, secure session handling. |
| **Database** | MySQL 5.7 / 8.0+ | Relational architecture, InnoDB engine, full `utf8mb4` character mapping. |
| **Server Engine** | Apache HTTP Server | Customized `.htaccess` rules for secure directories and Clean URL rewrites. |
| **Frontend Framework**| Bootstrap 5.3.x | Fully responsive grid layout with custom spiritual thematic variables. |
| **Typography** | Google Fonts | *Cinzel*, *Lora*, and *Rozha One* for classic and elegant spiritual aesthetic. |
| **Interactive UI** | Vanilla JS & GLightbox | Micro-animations, responsive dropdowns, secure lightboxes. |

---

## 📂 Directory Structure Walkthrough

```bash
Bhagwan-Vishwakarma-Mandir/
├── backend/               # Server-side controllers, configuration, and API logic
│   ├── admin/             # Admin Dashboard controllers, login, and dashboard panels
│   ├── api/               # Async API endpoints for dynamic features and contact forms
│   ├── config/            # Site configurations, db keys, path constants, and parameters
│   │   ├── config.php     # Core global parameters, uploads, and rate limits
│   │   ├── constants.php  # Path shortcuts (BVM_ROOT, PAGES_PATH, TEMPLATES_PATH)
│   │   └── payment.php    # Virtual booking currency & checkout helpers
│   └── functions/         # Core helper libraries (Security, Auth, DB, Mail, Upload, SEO)
├── database/              # SQL schemas and historical migrations
│   ├── migrations/        # Historical database update scripts and seed data
│   │   ├── seed_gallery.sql # Seeding parameters for visual gallery items
│   │   ├── update_events.sql # Event title migration queries
│   │   └── update_events_hi.sql # Hindi dynamic descriptions translations seed
│   └── schema.sql         # Base database setup, default parameters, and initial tables
├── frontend/              # Client-side assets, layout templates, and pages
│   ├── assets/            # Static resources (CSS stylesheets, Vanilla JS, images, icons)
│   │   ├── css/
│   │   │   └── style.css  # Core style variables, themes, responsive tweaks, and fonts
│   │   ├── js/
│   │   │   └── main.js    # Main initialization scripts, animations, and GLightbox rules
│   │   └── images/        # Standard UI logos, icons, and system banners
│   ├── lang/              # Localization Dictionaries
│   │   ├── en.php         # English words and translations mapping array
│   │   └── hi.php         # Hindi (Devanagari script) translations mapping array
│   ├── pages/             # Front-facing page controllers (Home, About, Book Puja, Darshan, etc.)
│   └── templates/         # Modular layout segments (Header, Navigation, Footer, Modals)
├── logs/                  # System log directory
├── uploads/               # Dynamic directory containing admin/user uploads
│   ├── events/            # Local high-res event banners
│   └── gallery/           # Gallery album photographs
├── .htaccess              # Apache clean routing, HTTPS forcing, and folder protections
├── index.php              # Front Controller & Single Entry Point Router
└── robots.txt             # Web Crawlers visibility settings
```

---

## 💻 Local Installation Guide

Follow these simple steps to set up the **Bhagwan Vishwakarma Mandir** application on your local server environment using XAMPP (or any WAMP/MAMP system).

### Step 1: Clone the Codebase
Navigate to your server's root folder (e.g., `C:\xampp\htdocs\`) and clone the repository:
```bash
git clone https://github.com/sharmanish011-maker/Bhagwan-Vishwakarma-Mandir.git
```
Make sure the folder is named exactly `Bhagwan-Vishwakarma-Mandir` so that the `.htaccess` rewrite rules map correctly.

### Step 2: Configure Apache Mod-Rewrite
1. Open your XAMPP Control Panel.
2. Ensure that `mod_rewrite` is enabled in your Apache `httpd.conf` file:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Verify that directory overrides are allowed for your folder:
   ```apache
   <Directory "C:/xampp/htdocs">
       AllowOverride All
       Require all granted
   </Directory>
   ```
4. Restart the Apache server.

### Step 3: Set up the Database

> [!IMPORTANT]
> The database utilizes complete Devanagari script for Hindi translations. To prevent character corruption (Mojibake errors like `ÓÑ¡` or question marks), you **MUST** configure and import the database schema using the `utf8mb4` encoding format.

1. Open **phpMyAdmin** (`http://localhost/phpmyadmin`) or your MySQL client.
2. Create a new database named `bvm_temple` with the collation `utf8mb4_unicode_ci`.
3. Open your terminal or use the phpMyAdmin console to import the base schema. If you are importing via terminal, run this command:
   ```bash
   mysql -u root -p --default-character-set=utf8mb4 bvm_temple < database/schema.sql
   ```
4. Import the subsequent gallery and event updates from the `migrations` folder to ensure the application starts with beautiful pre-populated assets:
   ```bash
   mysql -u root -p --default-character-set=utf8mb4 bvm_temple < database/migrations/seed_gallery.sql
   mysql -u root -p --default-character-set=utf8mb4 bvm_temple < database/migrations/update_events.sql
   mysql -u root -p --default-character-set=utf8mb4 bvm_temple < database/migrations/update_events_hi.sql
   ```

### Step 4: Verify Database Configurations
Open [backend/config/config.php](file:///c:/xampp/htdocs/Bhagwan-Vishwakarma-Mandir/backend/config/config.php) and check the MySQL credentials. By default, it is configured for local development:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bvm_temple');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
```

### Step 5: Run the Web App!
Open your web browser and navigate to:
```url
http://localhost/Bhagwan-Vishwakarma-Mandir/
```
The site will immediately load in a beautiful, high-performance responsive layout.

---

## 🔑 Administrative Access

To access the back-office management portal, navigate to:
```url
http://localhost/Bhagwan-Vishwakarma-Mandir/admin/
```

- **Default Administrator Username**: `admin`
- **Default Administrator Password**: `admin123`

*Note: In production environments, immediately modify the admin login password inside the `admins` database table using a `PASSWORD_HASH` check. Although the folder is located at `backend/admin/` inside the repository, the `.htaccess` configuration cleanly handles access transparently through `/admin/` in the browser.*

---

## 🛠️ Summary of Recent Technical Fixes

This codebase has undergone extensive styling, character-set, and structural optimizations to deliver a premium user experience:

1. **Character Set Corrective Migration (Mojibake Fix)**: Resolved issues where Devanagari Hindi characters were double-encoded (`ÓÑ¡...`) due to incorrect import charsets. Standardized all SQL schemas with a strict `utf8mb4` character mapping, ensuring correct rendering of spiritual scripts.
2. **Dynamic Bilingual Optimization**: Rectified missing dictionary keys across individual temple pages. Corrected `short_desc_hi` event descriptions to show correctly on localized subpages.
3. **Responsive Card Layout Refactoring**: Fixed layout alignment bugs on the Events page where text overlapped on smaller screens. Refactored the stylesheet to use flexible CSS columns (`flex-direction: column`) with relative/absolute object-fit parameters to prevent dynamic aspect ratio mismatches.
4. **Header Navigation Adjustments**: Fixed horizontal desktop navigation by adding `white-space: nowrap;` to key links (like "Contact Us"). This stops them from wrapping vertically on medium desktops.
5. **Aesthetic Gap Removal**: Eliminated the unwanted 16px white gap below the header navigation menu by conditionally rendering the Bootstrap session alert containers only when alert messages actually exist.
6. **Premium Visual Assets Integration**: Seeded high-definition custom graphics for OpenGraph standard shares (`og-image.jpg`), photo galleries (`uploads/gallery/`), and festival announcements (`uploads/events/`).
7. **Clean Architectural Directory Restructuring**: Restructured the repository into distinct `backend` (server-side controllers, configuration, and core helpers) and `frontend` (client-side template partials, page layouts, translation dictionaries, and styled sheets) to decouple concerns and support modular scalability.

---

## 📄 License & Terms

This web application is built for the sacred community of the **Bhagwan Vishwakarma Mandir**. All temple visual assets, text assets, and branding materials are properties of the temple committee. The underlying PHP-MySQL portal framework is open for developer study, modification, and maintenance under the standard MIT guidelines.

*May the divine architect Bhagwan Vishwakarma bless your coding and architectural endeavors!* 🙏
