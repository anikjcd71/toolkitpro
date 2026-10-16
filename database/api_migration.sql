-- ToolkitPro API access migration. Run after database/schema.sql.
CREATE TABLE IF NOT EXISTS api_keys (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(120) NOT NULL,
  key_prefix VARCHAR(16) NOT NULL,
  key_hash CHAR(64) NOT NULL UNIQUE,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_used_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS api_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  api_key_id BIGINT UNSIGNED NOT NULL,
  endpoint VARCHAR(190) NOT NULL,
  http_status SMALLINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_api_requests_key_time (api_key_id, created_at),
  CONSTRAINT fk_api_requests_key FOREIGN KEY (api_key_id) REFERENCES api_keys(id) ON DELETE CASCADE
) ENGINE=InnoDB;
