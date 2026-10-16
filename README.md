# ToolkitPro (PHP + MySQL)

A starter foundation for a responsive online tools directory with a protected admin area.

## Requirements
- PHP 8.3 with PDO MySQL
- MySQL 8+ (or compatible MariaDB)
- Apache/Nginx

## Local setup
1. Create a MySQL database and import `database/schema.sql`.
2. Configure environment variables: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, and a long random `APP_KEY`.
3. Point your web server document root to `public/`.
4. Create the first administrator from CLI:
   `php scripts/create_admin.php admin@example.com 'Use-a-long-unique-password'`
5. Visit `/admin/login.php`.

The public directory is the web root. Do not expose `database/`, `scripts/`, or configuration files.

## Render deployment
This repository includes a Dockerfile and `render.yaml`. Push to GitHub; Render will build the Docker image. In the Render service environment, set the MySQL connection values for a separately hosted MySQL database. Render does not provision MySQL through this Blueprint. Never commit database passwords.

## Included
- Tool listing/search and category filtering
- Admin login using PHP password hashing, session regeneration, CSRF protection
- Admin tool create/edit/delete
- PDO prepared statements, HTML escaping, security headers
- MySQL schema and sample records

This is a starter MVP, not yet a full SaaS suite: tool execution engines, billing, user accounts, API integrations, and advanced analytics require separate implementation.
