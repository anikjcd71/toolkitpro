-- ToolkitPro PostgreSQL schema. Connect to the Render database first.
CREATE TABLE IF NOT EXISTS admins (
  id BIGSERIAL PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS categories (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tools (
  id BIGSERIAL PRIMARY KEY,
  category_id BIGINT NULL REFERENCES categories(id) ON DELETE SET NULL,
  name VARCHAR(160) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  tool_url VARCHAR(500),
  status VARCHAR(12) NOT NULL DEFAULT 'published' CHECK (status IN ('published','draft')),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_tools_category_id ON tools(category_id);
INSERT INTO categories(name,slug) VALUES
 ('Text Tools','text-tools'),('Developer Tools','developer-tools'),('Website Tools','website-tools')
ON CONFLICT (slug) DO NOTHING;
INSERT INTO tools(category_id,name,slug,description,status)
SELECT c.id,v.name,v.slug,v.description,'published'
FROM (VALUES
 ('text-tools','Word Counter','word-counter','Count words and characters in text.'),
 ('developer-tools','JSON Formatter','json-formatter','Format and validate JSON data.'),
 ('website-tools','Website Health Check','website-health-check','Starter listing for website diagnostics.')
) AS v(category_slug,name,slug,description)
JOIN categories c ON c.slug=v.category_slug
ON CONFLICT (slug) DO NOTHING;
