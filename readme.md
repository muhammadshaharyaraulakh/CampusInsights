survey_project/
│
├─ public/                     → All user-facing pages
│   ├─ index.php               → Survey/feedback form
│   ├─ submit.php              → Handles user form submission
│   ├─ success.php             → Thank-you page
│   ├─ assets/
│   │    ├─ css/
│   │    │    └─ style.css     → Styling
│   │    ├─ js/
│   │    │    └─ script.js     → Optional JS
│   │    └─ images/            → Logos, icons, banners
│   └─ includes/
│        └─ header.php         → Common header
│        └─ footer.php         → Common footer
│
├─ admin/                      → Admin panel (protected area)
│   ├─ login.php               → Admin login
│   ├─ logout.php              → Logs admin out
│   ├─ dashboard.php           → View all feedback
│   ├─ view.php                → View a single feedback record
│   ├─ delete.php              → Delete a response (optional)
│   ├─ export.php              → Download CSV (optional)
│   ├─ assets/                 → Admin-only CSS/JS
│   │    ├─ css/
│   │    │    └─ admin.css
│   │    └─ js/
│   │         └─ admin.js
│   └─ includes/
│        ├─ admin_header.php
│        ├─ admin_footer.php
│        └─ auth_check.php     → Checks if admin is logged in
│
├─ config/
│   └─ config.php              → Site settings (site name, admin user)
│
├─ resources/
│   ├─ sql/                    → SQL setup files
│   │    └─ create_tables.sql
│   └─ documentation/
│        └─ README.md          → Explain project functions

