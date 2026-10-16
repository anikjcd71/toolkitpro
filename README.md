# ToolkitPro

PHP 8.3 + PDO-powered online tools directory and admin/API starter.

## Database engines

ToolkitPro supports **PostgreSQL (recommended on Render)** and **MySQL** via PDO. Set `DB_DRIVER=pgsql` or `DB_DRIVER=mysql`.

### Render PostgreSQL

The Render service blueprint wires the existing Render database named `toolkit` to `DATABASE_URL`. Ensure the web service and database are in the same Render workspace/region.

1. Deploy the latest `main` branch.
2. Connect to the Render Postgres database using its dashboard-provided PSQL command or an SQL client.
3. Run `php database/install.php` from a secure CLI/shell in the deployed project. This CLI-only installer applies both PostgreSQL SQL files in order.
   - Alternatively, execute `database/schema.pgsql.sql` and then `database/api_migration.pgsql.sql` manually in Render PSQL.
5. Create an admin using the project's admin setup script; configure its required environment variables securely.

Do not paste database passwords or connection strings into chat, source control, or public issue reports.

### MySQL alternative

For cPanel/other MySQL hosting, configure these service environment variables:

- `DB_DRIVER=mysql`
- `DB_HOST`, `DB_PORT` (normally 3306), `DB_NAME`, `DB_USER`, `DB_PASS`

Use `database/schema.sql` and `database/api_migration.sql` with your MySQL database. For Render, remove the `DATABASE_URL` database mapping in `render.yaml` and set the MySQL variables in the Render service environment.

## API

`GET /api/v1/tools` accepts a Bearer API key and optional `q`, `limit`, and `offset` parameters.
