CREATE DATABASE IF NOT EXISTS gmail_store;
USE gmail_store;

-- Tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    balance DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel gmail_accounts
CREATE TABLE gmail_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    recovery_email VARCHAR(100),
    status ENUM('available', 'sold', 'pending') DEFAULT 'available',
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sold_at TIMESTAMP NULL,
    buyer_id INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (buyer_id) REFERENCES users(id)
);

-- Tabel transactions
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    type ENUM('deposit', 'purchase', 'withdrawal') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel payments
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10,2) NOT NULL,
    method ENUM('bank_transfer', 'e_wallet', 'credit_card') NOT NULL,
    proof_image VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert admin user (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@gmaillstore.com', '$2y$10$YourHashedPasswordHere', 'admin');

-- Insert sample gmail accounts
INSERT INTO gmail_accounts (email, password, price, status) VALUES
('account1@gmail.com', 'encryptedpass1', 50000, 'available'),
('account2@gmail.com', 'encryptedpass2', 75000, 'available'),
('account3@gmail.com', 'encryptedpass3', 100000, 'available'),
('account4@gmail.com', 'encryptedpass4', 60000, 'sold'),
('account5@gmail.com', 'encryptedpass5', 90000, 'available');