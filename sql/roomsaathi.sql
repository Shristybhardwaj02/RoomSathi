-- RoomSaathi Database Schema
-- Created: January 30, 2026

-- Create Database
CREATE DATABASE IF NOT EXISTS roomsaathi;
USE roomsaathi;

-- ========================================
-- TABLE 1: USERS
-- Stores basic account information
-- ========================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255) DEFAULT 'default.jpg',
    age INT,
    gender ENUM('male', 'female', 'other'),
    occupation VARCHAR(100),
    city VARCHAR(100),
    user_type ENUM('seeking', 'offering', 'both') DEFAULT 'seeking',
    bio TEXT,
    is_verified TINYINT(1) DEFAULT 0,
    otp_code VARCHAR(6),
    otp_expiry DATETIME,
    profile_complete TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========================================
-- TABLE 2: USER_PREFERENCES
-- Stores 8 lifestyle factors for matching
-- ========================================
CREATE TABLE IF NOT EXISTS user_preferences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    smoking ENUM('yes', 'no', 'occasionally') DEFAULT 'no',
    drinking ENUM('yes', 'no', 'socially') DEFAULT 'no',
    food_preference ENUM('vegetarian', 'non-vegetarian', 'any') DEFAULT 'any',
    pets ENUM('have', 'love', 'no') DEFAULT 'no',
    sleep_schedule ENUM('early_bird', 'night_owl', 'flexible') DEFAULT 'flexible',
    cleanliness ENUM('very_clean', 'moderate', 'casual') DEFAULT 'moderate',
    guests ENUM('never', 'sometimes', 'often') DEFAULT 'sometimes',
    personality ENUM('introvert', 'ambivert', 'extrovert') DEFAULT 'ambivert',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ========================================
-- TABLE 3: LISTINGS
-- Stores room/property listings
-- ========================================
CREATE TABLE IF NOT EXISTS listings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    rent DECIMAL(10,2) NOT NULL,
    deposit DECIMAL(10,2),
    city VARCHAR(100) NOT NULL,
    locality VARCHAR(200),
    address TEXT,
    room_type ENUM('private', 'shared') DEFAULT 'private',
    furnishing ENUM('furnished', 'semi-furnished', 'unfurnished') DEFAULT 'semi-furnished',
    available_from DATE,
    gender_preference ENUM('male', 'female', 'any') DEFAULT 'any',
    occupancy INT DEFAULT 1,
    amenities TEXT,
    photos TEXT,
    status ENUM('active', 'rented', 'inactive') DEFAULT 'active',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ========================================
-- TABLE 4: LIKES (Interests)
-- Tracks who liked which listing
-- ========================================
CREATE TABLE IF NOT EXISTS likes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    listing_id INT NOT NULL,
    listing_owner_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    is_match TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_owner_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (user_id, listing_id)
);

-- ========================================
-- TABLE 5: MESSAGES
-- Stores chat messages between matched users
-- ========================================
CREATE TABLE IF NOT EXISTS messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    listing_id INT,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE SET NULL
);

-- ========================================
-- INDEXES FOR BETTER PERFORMANCE
-- ========================================
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_phone ON users(phone);
CREATE INDEX idx_users_city ON users(city);
CREATE INDEX idx_listings_city ON listings(city);
CREATE INDEX idx_listings_status ON listings(status);
CREATE INDEX idx_likes_user ON likes(user_id);
CREATE INDEX idx_likes_listing ON likes(listing_id);
CREATE INDEX idx_messages_sender ON messages(sender_id);
CREATE INDEX idx_messages_receiver ON messages(receiver_id);
