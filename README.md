# StudentHub

Full-stack student and event management portal (HTML, CSS, Bootstrap, JavaScript, PHP, MySQL).
Built for the Web Development lab, Practicals 1-15.

## Modules
| Folder | Practical | What it does |
|---|---|---|
| static/ | 1-6 | Responsive static site, JS interactivity, form validation, Fetch + JSON |
| php-forms/ | 7 | PHP form with server-side validation, saves to CSV |
| database/ | 8 | MySQL schema, PDO connection, prepared statements, stored procedure |
| auth/ | 9-10 | Registration, login/logout, sessions, roles, remember-me |
| students-crud/ | 11 | Student CRUD, search, filter, pagination, soft delete, CSV export |
| events-crud/ | 12 | Event CRUD, poster upload with validation, audit log |
| ajax-api/ | 13 | PHP JSON API with Fetch-based CRUD, debounce, status codes |
| admin-panel/ | 14 | RBAC dashboard, dynamic menu, audit logs, chart |

## Requirements
- XAMPP (Apache + MySQL + PHP 8.x) with `mysqli`, `pdo_mysql` and `fileinfo` enabled
- Internet access (Bootstrap and Chart.js load from a CDN)

## Installation
1. Copy this folder to `C:\xampp\htdocs\studentHub`.
2. Start Apache and MySQL in XAMPP.
3. In phpMyAdmin, open the **Import** tab and import every file in `sql/`.
4. Copy each `*.example.php` file to the same name without `.example`:
   `database/db.php`, `auth/db_mysqli.php`, `students-crud/config.php`,
   `events-crud/config.php`, `admin-panel/config.php`
   (default XAMPP login is `root` with an empty password; edit if yours differs).
5. Open http://localhost/studentHub/

## Demo accounts
| Module | Username | Password | Role |
|---|---|---|---|
| auth | admin | Admin@1234 | admin |
| auth | student1 | Student@1234 | student |
| events-crud | admin | Admin@123 | admin |
| admin-panel | admin | Admin@123 | admin |
| admin-panel | rahul | Student@123 | student |

Demo data only. Never reuse these passwords on a real site.

## Local deployment with a virtual host
See `docs/virtual-host.md`. Result: http://studenthub.local/

## Testing
See `docs/test-report.md` and `docs/checklists.md`. Screenshots are in `docs/screenshots/`.

## Security features
Prepared statements, password hashing, CSRF tokens, session regeneration and timeout,
role-based access control, output escaping, validated and renamed uploads, audit logs.

## Known limitations
- Each module has its own database and session (no single sign-on across modules).
- GitHub Pages can host only the static part.
- Credentials are XAMPP defaults; production would use a limited MySQL user.

## Author
Your Name | Enrollment No. | Class / Section