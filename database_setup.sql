-- ============================================================
-- Lost & Found System - Database Setup
-- Run this in phpMyAdmin > SQL tab
-- ============================================================

CREATE DATABASE IF NOT EXISTS lost_found_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lost_found_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Items table
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    type ENUM('lost', 'found') NOT NULL,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    location VARCHAR(150),
    date_occurred DATE,
    image VARCHAR(255),
    status ENUM('open', 'matched', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Matches table (rule-based auto-matching)
CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lost_id INT,
    found_id INT,
    score INT DEFAULT 0,
    status ENUM('pending', 'confirmed', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lost_id) REFERENCES items(id) ON DELETE CASCADE,
    FOREIGN KEY (found_id) REFERENCES items(id) ON DELETE CASCADE
);

-- Default admin account
-- Email: admin@lostandfound.com
-- Password: password
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@lostandfound.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Sample data (optional)
INSERT INTO users (name, email, password, role) VALUES
('Alice Smith', 'alice@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Bob Jones', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO items (user_id, type, title, category, description, location, date_occurred) VALUES
(2, 'lost', 'Black Leather Wallet', 'Wallet/Purse', 'Black bifold wallet with student ID inside', 'Library, 2nd floor', '2026-03-01'),
(3, 'found', 'Black Wallet', 'Wallet/Purse', 'Found a black wallet near the library entrance', 'Library', '2026-03-02'),
(2, 'lost', 'Blue Nokia Phone', 'Phone', 'Blue Nokia phone with cracked screen protector', 'Canteen', '2026-03-05'),
(3, 'found', 'Keys with keychain', 'Keys', 'Set of 3 keys with a blue star keychain', 'Parking lot', '2026-03-06');
