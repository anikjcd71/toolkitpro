CREATE DATABASE IF NOT EXISTS toolkitpro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE toolkitpro;

CREATE TABLE IF NOT EXISTS admins (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tools (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id BIGINT UNSIGNED NULL,
  name VARCHAR(160) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  tool_url VARCHAR(500) NULL,
  status ENUM('published','draft') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_tools_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO categories(name,slug) VALUES
('Text Tools','text-tools'),('Developer Tools','developer-tools'),('Website Tools','website-tools');

INSERT IGNORE INTO tools(category_id,name,slug,description,status) VALUES
((SELECT id FROM categories WHERE slug='text-tools'),'Word Counter','word-counter','Count words and characters in text.','published'),
((SELECT id FROM categories WHERE slug='developer-tools'),'JSON Formatter','json-formatter','Format and validate JSON data.','published'),
((SELECT id FROM categories WHERE slug='website-tools'),'Website Health Check','website-health-check','Starter listing for website diagnostics.','published');
