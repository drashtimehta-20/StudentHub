# Integrated Test Report

| # | Area | Test | Expected result | Result | Screenshot |
|---|---|---|---|---|---|
| 1 | Navigation | Click every card on the portal | All 8 modules open, no 404 | | |
| 2 | Navigation | Browser Back and "Back to portal" links | Pages return correctly | | |
| 3 | Responsive | F12 device toolbar: mobile, tablet, desktop | Layout adapts, no sideways scroll | | |
| 4 | Static | Practical 6 page loads JSON, search and filter | Data renders without errors | | |
| 5 | Forms | Practical 7: valid, then invalid submit | CSV row saved / errors shown | | |
| 6 | Database | database/test_connection.php | "Connection successful" with tables | | |
| 7 | Register | auth/register_user.php with a valid user | Success, hashed password in users | | |
| 8 | Duplicate | Register the same email again | "This email is already registered." | | |
| 9 | Login admin | admin / Admin@1234 | Admin dashboard | | |
| 10 | Login student | student1 / Student@1234 | Student dashboard | | |
| 11 | Failed login | Wrong password | Generic error message | | |
| 12 | RBAC | As student, open admin_dashboard.php | 403 Access denied | | |
| 13 | Logout | Logout, then press Back | Cannot see the dashboard | | |
| 14 | Timeout | Set timeout to 20 s, wait, refresh | Session expired message | | |
| 15 | CRUD | Students: add, edit, search, filter | Success messages, table updates | | |
| 16 | Delete | Delete, Cancel, then OK | Cancel does nothing, OK soft-deletes | | |
| 17 | Upload | Events: valid JPG | Poster saved under a random name | | |
| 18 | Upload | fake.jpg and an oversized file | Both rejected with messages | | |
| 19 | AJAX | ajax-api: add a student | Row appears, no page reload | | |
| 20 | AJAX | Network tab: add, 422, 409 | Correct status codes and JSON | | |
| 21 | Debounce | Type quickly in search | One list request per pause | | |
| 22 | Dashboard | admin-panel statistics and chart | Values match the database | | |
| 23 | Menu | Admin vs student in admin-panel | Different menus | | |
| 24 | Audit | Admin Audit Logs | Login, failed login, toggle, 403 entries | | |
| 25 | Sessions | Log in to auth and admin-panel in one browser | Separate sessions, no mixing | | |
| 26 | Protection | Open events-crud/uploads/ | 403 Forbidden | | |
| 27 | XSS | Enter `<script>alert(1)</script>` as a name | Shown as text or stripped, no popup | | |
| 28 | SQL injection | Login with `' OR '1'='1` | Login fails | | |
| 29 | Virtual host | Open http://studenthub.local/ | Portal loads | | |
| 30 | Fresh install | Clone and set up from the README | Works from scratch | | |