# 🚀 Configurable Portfolio Website

A dynamic, fully-manageable portfolio website built with **PHP** and **MySQL**. This project is designed to be a "CMS-lite" solution for developers, allowing you to showcase your work, experience, and skills while managing all content through a secure, password-protected dashboard.

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000f?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

## 🏗️ System Architecture

The application follows a streamlined architecture where the frontend (Public Interface) and backend (Admin Dashboard) share a common data source. PHP PDO is used as the bridge to ensure secure data retrieval and updates.

### Data Flow
1.  **Client Side:** Visitors view the portfolio; Admins log in to manage content.
2.  **Server Side (PHP):** * `index.php`, `skills.php`, `experience.php` fetch data to display.
    * `dashboard.php` handles data updates and file uploads.
    * `login.php` manages authentication.
3.  **Data Layer:** * **MySQL Database:** Stores all text content (Bio, Skills, History).
    * **File System:** Stores uploaded assets like Images and PDF Resumes.

## ✨ Key Features

### 🔐 Secure Admin Dashboard
* **Authentication:** Custom login system using `password_hash()` and PHP Sessions.
* **Content Management:** Update your bio, job titles, and contact info in real-time.
* **Media Uploads:** Securely upload and replace profile images and CV/Resume PDFs directly to the server.

### 🌍 Localization (i18n)
* **Bilingual Support:** Native support for **English (LTR)** and **Arabic (RTL)**.
* **Instant Toggle:** Session-based language switching without page reloads affecting the user state.
* **Dynamic Translation:** All static text is managed via `includes/translations.php` for easy editing.

### 🎨 Dynamic Content Modules
* **Skills Grid:** Add/Remove technical skills using a pre-populated dropdown of **FontAwesome** icons.
* **Experience Timeline:** Add career milestones with dual-language support (English/Arabic roles and descriptions).
* **Social Links:** Manage your social media presence with circular, animated icon links.

### 📩 Contact System
* **Inquiry Logging:** Visitors can send messages via the contact form.
* **Admin Inbox:** View, mark as read, or delete messages directly from the dashboard.
* **CSRF Protection:** Forms are protected against Cross-Site Request Forgery attacks.

---

## 🗄️ Database Schema

The project relies on a relational MySQL database containing the following tables:

* **`site_settings`**: Stores global configuration (Logo, Bio, Contact Info, Admin Credentials).
* **`experience`**: Stores career history (Role, Company, Duration) in both languages.
* **`skills`**: Stores technical skills and their corresponding FontAwesome icon classes.
* **`social_links`**: Stores URLs and icons for social media profiles (GitHub, LinkedIn, etc.).
* **`contact_messages`**: Stores incoming messages from the contact form.

## 🚀 Installation & Setup

### 1. Clone the Repository
```bash
git clone [https://github.com/Faisal-Mohammed1/Portfolio-Website.git](https://github.com/Faisal-Mohammed1/Portfolio-Website.git)
```
### 2. Configure the Database
Create a new MySQL database named portfolio_db.

Import the provided SQL file located at /db/database.sql.

Open includes/db_connect.php and update your credentials:

```bash
$username = 'root'; // Your DB Username
$password = '';     // Your DB Password
```

### 3. Folder Permissions
Ensure the assets/ folder is writable so you can upload images and PDFs via the dashboard.

```bash
chmod -R 755 assets/
```

### 4. Admin Access
Navigate to /login.php to access the backend.

Default User: admin

Default Pass: 123456

⚠️ Change these immediately in the "Settings" tab!

### 🛡️ Security Measures
SQL Injection: All database queries use PDO Prepared Statements.

XSS: Output escaping is applied to all user-generated content using htmlspecialchars().

Session Hijacking: Sessions are regenerated upon login.

### 🤝 Contributing
Contributions, issues, and feature requests are welcome! Feel free to check the issues page.

Created by Faisal Mohammed.
