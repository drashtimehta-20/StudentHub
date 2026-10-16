# Security Checklist
- [ ] All SQL uses prepared statements (Practicals 8-14)
- [ ] Passwords hashed with password_hash() and checked with password_verify() (9, 10, 12, 14)
- [ ] Session ID regenerated at login, HttpOnly + SameSite cookies (auth, 12, 14)
- [ ] Sessions destroyed fully on logout, plus a timeout (10, 14)
- [ ] CSRF token on every state-changing form (7, 9-12, 14)
- [ ] Output escaped with htmlspecialchars() / textContent (all modules)
- [ ] Server-side validation as well as client-side (5, 7, 9, 11, 13)
- [ ] Uploads: type, size, real content check, random names (12)
- [ ] Upload folder blocks PHP and directory listing (events-crud/uploads/.htaccess)
- [ ] Role-based access enforced on the server (10, 12, 14)
- [ ] Generic error messages, details only in the log (all catch blocks)
- [ ] No secrets, seed files or uploads in the repository (.gitignore)

# Accessibility Checklist
- [ ] lang attribute and descriptive title on every page
- [ ] Semantic landmarks: header, nav, main, footer
- [ ] Headings in logical order, one h1 per page
- [ ] Every input has a label for=...
- [ ] Images have meaningful alt text
- [ ] Everything works with the Tab key, with visible focus
- [ ] Colour contrast is readable
- [ ] Status messages are announced (aria-live)
- [ ] Skip link and responsive viewport meta tag