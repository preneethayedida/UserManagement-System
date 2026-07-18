# Secure User Management System (PHP MVC)

A modern, highly secure, and robust **User Management System** built from scratch using a custom PHP Model-View-Controller (MVC) architecture. This application provides secure user authentication, role-based access control, profile management, and an interactive admin dashboard.

---

## 🚀 Key Features

### 1. Robust Security Model
*   **SQL Injection Prevention**: Built entirely on top of PHP PDO using prepared statements and parameterized queries.
*   **CSRF Protection**: Multi-layered Cross-Site Request Forgery prevention using cryptographically secure tokens (`random_bytes`) generated per session and verified on state-changing requests.
*   **XSS Protection**: Secure escaping of all output via HTML Entities with UTF-8 character encoding.
*   **Secure Session Management**: Strictly configured session cookies (HttpOnly, SameSite=Lax, Secure flags enabled where appropriate) alongside active session timeout handlers and ID regeneration on login to prevent session hijacking.
*   **Safe Password Hashing**: Implements the industry-standard `bcrypt` algorithm via PHP's `password_hash()` for user passwords.

### 2. Modern MVC Architecture
*   **Custom Router**: Clean route definitions mapped directly to Controller actions with dynamic URI pattern matching and middleware integration.
*   **Middleware Pipeline**: Includes standard request filters:
    *   `Authenticate` (for authenticated zones)
    *   `RedirectIfAuthenticated` (redirects logged-in users away from guest pages)
    *   `AdminOnly` (gates administrative panels)
*   **Single Entrypoint**: Unified request lifecycle routed through `index.php` with Apache `.htaccess` rewrites.

### 3. Comprehensive Administrative Controls (AJAX-Driven)
*   **Admin Control Panel**: Manage all users from a central interface.
*   **AJAX Actions**: Users can be created, updated, or deleted seamlessly without full-page reloads.
*   **Server-Side Sorting, Filtering & Search**: Efficient pagination, search queries on user fields, and filtering by account status (`active`, `inactive`, `suspended`).
*   **Role Management**: Role associations linked strictly with foreign key constraints.

### 4. Interactive Profile & Dashboard Analytics
*   **User Statistics**: Widgets showing user distributions (Total Users, Admins, Regular Users, Status breakdowns).
*   **Analytics Charts**: Graphical insights into user registration trends over the last 7 days powered by **Chart.js**.
*   **Profile Completion Tracker**: Computes profile completion progress based on active user attributes.
*   **Custom Profile Management**: Users can edit personal information, upload custom avatars, and change passwords.
*   **Neon SaaS Dark/Light Theme**: A premium user interface equipped with a client-side theme switcher and LocalStorage state persistence.

---

## 📂 Directory Structure

```text
user-management-system/
├── assets/                  # Frontend assets (Custom CSS, JS, Avatars/Uploads)
│   ├── css/                 # Modern styling (Neon SaaS Theme)
│   ├── js/                  # App AJAX and interactive frontend scripts
│   └── uploads/             # User uploaded profile pictures
├── config/                  # App configurations (app settings, database credentials)
├── controllers/             # MVC Controllers (handling HTTP requests)
├── helpers/                 # Global utility helper functions (XSS, CSRF, Old inputs)
├── middleware/              # Router request filtering middleware
├── models/                  # MVC Data Models interfacing with PDO
├── routes/                  # Route definitions and custom Router class
├── storage/                 # Logs and file storage
│   └── logs/                # Stack traces and application error logs
├── views/                   # MVC template views (HTML layout, Partials, Auth, Admin)
├── .htaccess                # Apache URL rewriting configurations
├── bootstrap.php            # Core bootstrapper (autoloading, session settings, exception handling)
├── database.sql             # SQL DB schema representing relational structures
├── index.php                # Front controller entrypoint
├── install.php              # Automated database installation & seeding script
└── router.php               # Routing helper for PHP's built-in web server
```

---

## 🛠️ Requirements & Prerequisites

Ensure the following environments are met:
*   **PHP**: Version `8.0` or higher.
*   **Database**: MySQL/MariaDB `5.7` or higher.
*   **Server**: Apache with `mod_rewrite` enabled OR PHP's built-in CLI web server.

---

## ⚡ Installation & Setup

### Step 1: Configure Database Credentials
Open [config/constants.php](file:///c:/Users/mohan/OneDrive/Desktop/preneetha%20tasks/TASK-3/user-management-system/config/constants.php) and update database connection details to match your MySQL server:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'user_management_db');
define('DB_USER', 'root'); // Your MySQL username
define('DB_PASS', '');     // Your MySQL password
```

### Step 2: Automatic Database Setup
This application includes an interactive setup script to automate database creation, schema creation, and default seeding.

1.  Start your Apache and MySQL servers.
2.  Navigate to `install.php` using your local server url (e.g., `http://localhost/user-management-system/install.php`).
3.  Once the screen reports success, **delete `install.php`** from the workspace to secure your instance.

### Step 3: Run the Built-in Server (Alternative)
If you do not use Apache, you can launch PHP's built-in web server. Open your terminal at the project root and run:

```bash
php -S localhost:8000 router.php
```

Then, visit `http://localhost:8000/install.php` to initialize, and access the application at `http://localhost:8000/`.

---

## 🔑 Default Credentials

The installation script seeds the system with two default users:

| Role | Username | Email | Password |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `admin@example.com` | `Admin@123` |
| **Regular User** | `user` | `user@example.com` | `User@123` |

---

## 🛡️ Key Components & Libraries Used

*   **Styling**: [Bootstrap 5](https://getbootstrap.com/)
*   **Icons**: [Bootstrap Icons](https://icons.getbootstrap.com/)
*   **Data Visualization**: [Chart.js](https://www.chartjs.org/)
*   **Modals & Alerts**: [SweetAlert2](https://sweetalert2.github.io/)
