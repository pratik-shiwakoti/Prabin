CREATE DATABASE IF NOT EXISTS prabin_portfolio
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE prabin_portfolio;

CREATE TABLE admin_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  failed_login_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
  locked_until DATETIME NULL,
  last_login_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE profile (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  biography TEXT NULL,
  profile_image VARCHAR(255) NULL,
  cv_path VARCHAR(255) NULL,
  email VARCHAR(255) NULL,
  availability_status VARCHAR(100) NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE project_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(120) NOT NULL UNIQUE,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE projects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  short_description VARCHAR(500) NOT NULL,
  description TEXT NULL,
  problem TEXT NULL,
  solution TEXT NULL,
  features TEXT NULL,
  development_process TEXT NULL,
  challenges TEXT NULL,
  outcome TEXT NULL,
  cover_image VARCHAR(255) NULL,
  github_url VARCHAR(500) NULL,
  demo_url VARCHAR(500) NULL,
  python_version VARCHAR(30) NULL,
  libraries_frameworks TEXT NULL,
  database_apis TEXT NULL,
  automation_algorithms TEXT NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_projects_category
    FOREIGN KEY (category_id) REFERENCES project_categories(id)
    ON DELETE SET NULL,
  INDEX idx_projects_published_featured (is_published, is_featured),
  INDEX idx_projects_created_at (created_at)
) ENGINE=InnoDB;

CREATE TABLE project_images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  alt_text VARCHAR(255) NOT NULL,
  is_cover TINYINT(1) NOT NULL DEFAULT 0,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_project_images_project
    FOREIGN KEY (project_id) REFERENCES projects(id)
    ON DELETE CASCADE,
  INDEX idx_project_images_project (project_id, sort_order)
) ENGINE=InnoDB;

CREATE TABLE project_technologies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NOT NULL,
  technology_name VARCHAR(100) NOT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  CONSTRAINT fk_project_technologies_project
    FOREIGN KEY (project_id) REFERENCES projects(id)
    ON DELETE CASCADE,
  INDEX idx_project_technologies_project (project_id, sort_order)
) ENGINE=InnoDB;

CREATE TABLE skill_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  CONSTRAINT fk_skills_category
    FOREIGN KEY (category_id) REFERENCES skill_categories(id)
    ON DELETE CASCADE,
  INDEX idx_skills_category (category_id, sort_order)
) ENGINE=InnoDB;

CREATE TABLE education (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  degree VARCHAR(180) NOT NULL,
  institution VARCHAR(180) NOT NULL,
  start_date DATE NULL,
  end_date DATE NULL,
  description TEXT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE experience (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role VARCHAR(180) NOT NULL,
  organization VARCHAR(180) NULL,
  experience_type VARCHAR(60) NOT NULL,
  start_date DATE NULL,
  end_date DATE NULL,
  description TEXT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(255) NOT NULL,
  subject VARCHAR(180) NOT NULL,
  message TEXT NOT NULL,
  ip_hash CHAR(64) NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_contact_messages_read_created (is_read, created_at)
) ENGINE=InnoDB;

CREATE TABLE social_links (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  platform VARCHAR(80) NOT NULL,
  url VARCHAR(500) NOT NULL,
  label VARCHAR(100) NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  is_visible TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;
