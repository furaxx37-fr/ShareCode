-- ShareCode Database Schema
-- This file contains the database structure for ShareCode

CREATE TABLE IF NOT EXISTS pastes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paste_id VARCHAR(10) UNIQUE NOT NULL,
    title VARCHAR(255) DEFAULT 'Untitled',
    content TEXT NOT NULL,
    language VARCHAR(50) DEFAULT 'text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    views INT DEFAULT 0
);

-- Create indexes for better performance
CREATE INDEX idx_paste_id ON pastes(paste_id);
CREATE INDEX idx_created_at ON pastes(created_at);
CREATE INDEX idx_language ON pastes(language);
