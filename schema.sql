-- SQL schema for inventorysys
-- Run: create database and tables

CREATE DATABASE IF NOT EXISTS inventorysys CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventorysys;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(160) NOT NULL UNIQUE,
  username VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products table
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  sku VARCHAR(80) NOT NULL UNIQUE,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  quantity INT NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Customers table
CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(160) NOT NULL UNIQUE,
  username VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Sample data (optional)
INSERT INTO admins (email, username, password) VALUES
('admin1@example.com', 'admin1', '$2y$10$exampleexampleexampleexampleexampleexampleexampleexample');

INSERT INTO products (name, sku, price, quantity) VALUES
('Sample Product', 'SKU-001', 9.99, 10);

INSERT INTO customers (email, username, password) VALUES
('john@example.com', 'john', 'secret');
