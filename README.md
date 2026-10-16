# ToolkitPro

ToolkitPro is a PHP 8.3 + PDO tools directory with a protected admin area and API starter. It supports Render PostgreSQL and standard MySQL hosting.

## Render deployment (PostgreSQL)

1. Deploy the `main` branch to Render using the included Dockerfile.
2. In the web service Environment, set:
   - `DB_DRIVER=pgsql`
   - `DATABASE_URL` to the database's **Internal Database URL** (keep it secret; never commit or paste it publicly).
   - `AUTO_DB_MIGRATE=1` (default; initializes missing tables idempotently on first DB connection).
3. Confirm the service health endpoint: `/health.php` should return JSON with `status: ok`, `database: connected`, and `schema: ready`.
4. For the first admin account, set a strong, private `ADMIN_SETUP_TOKEN` environment variable (at least 24 characters), deploy/restart, then visit `/admin/setup.php`. Enter that token, admin email, and a password of at least 12 characters.
5. After the admin account is created, **remove `ADMIN_SETUP_TOKEN` from Render Environment immediately**. The setup page also closes itself once an admin exists. Then sign in at `/admin/login.php`.

The schema auto-installer applies `database/schema.pgsql.sql` and `database/api_migration.pgsql.sql` in order, under a PostgreSQL advisory lock. It uses idempotent SQL; it does not drop existing data. You can also run `php database/install.php` from a secure CLI shell or execute the SQL files manually through Render PSQL.

## MySQL / cPanel

Set `DB_DRIVER=mysql`, then configure `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASS`. Use `database/schema.sql` and `database/api_migration.sql` with your MySQL database. PostgreSQL auto-migration is intentionally only enabled for PostgreSQL.

## API

`GET /api/v1/tools` requires an API key in `Authorization: Bearer YOUR_KEY` and supports optional `q`, `limit`, and `offset` parameters.

## Security notes

- Keep database URLs, setup tokens, admin passwords, and API keys out of GitHub and chat.
- Remove `ADMIN_SETUP_TOKEN` after creating the initial admin.
- Use HTTPS in production.
