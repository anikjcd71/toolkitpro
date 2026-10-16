# ToolkitPro (PHP + MySQL)

Responsive online tools directory with an admin area and a basic authenticated API.

## Setup
1. Import `database/schema.sql` into MySQL.
2. Import `database/api_migration.sql` into the same database to enable API keys and request logs.
3. Configure `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASS` as Render environment variables.
4. Create the first admin using `php scripts/create_admin.php admin@example.com 'Use-a-long-unique-password'`.
5. Sign in at `/admin/login.php`.

## API v1
- Endpoint: `GET /api/v1/tools`
- Optional query parameters: `q`, `limit` (1–100; default 20), and `offset` (default 0).
- Authentication: `Authorization: Bearer YOUR_API_KEY`
- Rate limit: 60 requests per minute per API key.
- API keys must be created in the admin area after API-key management is installed. Store keys privately; do not put them in browser-side JavaScript.

Example:
```bash
curl 'https://YOUR-DOMAIN/api/v1/tools?limit=10' -H 'Authorization: Bearer YOUR_API_KEY'
```

## Render deployment
Push to GitHub; Render auto-deploys the Docker image. Configure a separately hosted MySQL database through Render environment variables. Never commit database passwords or provider API secrets.

## Scope
Current MVP includes the tool directory/search, protected admin CRUD, and the initial authenticated tools-list API. Tool execution engines, third-party AI integrations, user billing, and more API endpoints still need implementation.
