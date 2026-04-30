-- Run this file in phpMyAdmin to create the GlamAura database and tables

CREATE DATABASE IF NOT EXISTS glamaura DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE glamaura;

-- CUSTOMERS TABLE
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    shipping_address TEXT,
    city VARCHAR(50),
    country VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ADMIN TABLE
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- INSERT DEFAULT ADMIN ACCOUNT (use IGNORE so re-imports don't fail if admin already exists)
INSERT IGNORE INTO admin (id, full_name, email, password, role) VALUES
(1, 'Admin User', 'admin@glamaura.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36DH5G1C', 'admin');

-- PRODUCTS TABLE
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    base_price DECIMAL(10,2) NOT NULL
);

-- CLEAR EXISTING PRODUCTS (if any)
TRUNCATE TABLE products;

-- INSERT PRODUCTS DATA
INSERT INTO products (name, description, image, base_price) VALUES
('Brazilian Body Wave', 'Length: 12",16",20" | Lace Type: HD Lace | Hair Texture: Body Wave', 'images/Brazilian Body Wave Wig.jfif', 1500.00),
('Silky Straight Wig', 'Length: 14",18",22" | Lace Type: Transparent Lace | Hair Texture: Straight', 'images/Silky Straight Wig.jfif', 2000.00),
('Luxury Curly Wig', 'Length: 16",20",24" | Lace Type: HD Lace | Hair Texture: Curly', 'images/Curly Wig.jfif', 1900.00),
('Classic Bob Wig', 'Length: 10",12",14" | Lace Type: Lace Front | Hair Texture: Straight', 'images/Classic Bob Wig.jfif', 1400.00),
('Deep Wave Wig', 'Length: 16",20",24" | Lace Type: HD Lace | Hair Texture: Deep Wave', 'images/Deep Wave Wig.jfif', 2300.00),
('Highlight Wig', 'Length: 18",22",26" | Lace Type: Lace Front | Hair Texture: Body Wave', 'images/Highlight Wig.jfif', 3400.00),
('Blonde Lace Wig', 'Length: 16",20",24" | Lace Type: HD Lace | Hair Texture: Straight', 'images/Blonde Lace Wig.jfif', 4600.00),
('Burgundy Wig', 'Length: 18",22",26" | Lace Type: Transparent Lace | Hair Texture: Body Wave', 'images/Burgundy Wig.jfif', 3800.00),
('Headband Wig', 'Length: 14",18",22" | Lace Type: None | Hair Texture: Straight', 'images/Headband wig.jfif', 1500.00),
('Kinky Curly Wig', 'Length: 16",20",24" | Lace Type: Lace Front | Hair Texture: Kinky Curly', 'images/Kinky Curly Wig.jfif', 2200.00),
('Soft Wave Wig', 'Length: 14",18",22" | Lace Type: HD Lace | Hair Texture: Wave', 'images/Soft Wave Wig.jfif', 1950.00),
('Glueless Wig', 'Length: 16",20",24" | Lace Type: Glueless Lace | Hair Texture: Straight', 'images/Glueless Wig.webp', 1600.00),
('Loose Wave Wig', 'Length: 16",20",24" | Lace Type: HD Lace | Hair Texture: Body Wave', 'images/Loose Wave Wig.jfif', 1950.00),
('Chocolate Brown Wig', 'Length: 18",22",26" | Lace Type: Lace Front | Hair Texture: Body Wave', 'images/Chocolate Brown Wig.jfif', 2500.00),
('Layered Wig', 'Length: 16",20",24" | Lace Type: Transparent Lace | Hair Texture: Straight', 'images/Layered Wig.jfif', 1980.00),
('Silk Straight Wig', 'Length: 18",22",26" | Lace Type: HD Lace | Hair Texture: Straight', 'images/Silk Straight Wig 2.jfif', 2600.00),
('Curly Bob Wig', 'Length: 10",12",14" | Lace Type: Lace Front | Hair Texture: Curly', 'images/Curly Bob Wig.jfif', 1750.00),
('Deep Curly Wig', 'Length: 18",22",26" | Lace Type: HD Lace | Hair Texture: Curly', 'images/Deep Curly Wig.jfif', 2200.00),
('Ombre Wig', 'Length: 18",22",26" | Lace Type: Lace Front | Hair Texture: Body Wave', 'images/Ombre Wig.jfif', 3000.00),
('Platinum Blonde Wig', 'Length: 20",24",28" | Lace Type: HD Lace | Hair Texture: Straight', 'images/Platinum Blonde Wig.jfif', 5000.00),
('Extra Curly Wig', 'Length: 16",20",24" | Lace Type: Transparent Lace | Hair Texture: Curly', 'images/Extra Curly Wig.jfif', 3000.00),
('Wig Cap', 'Breathable nylon cap | Available in Nude/Black | Stretch fit for all head sizes', 'images/Wig Cap.jfif', 80.00),
('Lace Wig Glue', 'Waterproof adhesive for lace wigs | Provides 2-3 week hold | Easy to remove', 'images/Lace Wig Glue.jfif', 100.00),
('Wig Styling Comb', 'Wide tooth comb | Anti-static technology | Gentle on hair | Ergonomic handle', 'images/Wig Styling Comb.avif', 30.00);

-- ORDERS TABLE
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    full_name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    shipping_address TEXT,
    city VARCHAR(50),
    country VARCHAR(50),
    total DECIMAL(10,2) NOT NULL,
    vat DECIMAL(10,2) NOT NULL,
    shipping DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- ORDER ITEMS TABLE
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    quality VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- INVENTORY TABLE
CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    low_stock_alert INT NOT NULL DEFAULT 5,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- PAYMENTS TABLE
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    method VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- DELIVERIES TABLE
CREATE TABLE IF NOT EXISTS deliveries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    tracking_number VARCHAR(100),
    estimated_date DATE,
    actual_date DATE,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- MESSAGES TABLE (Contact Form)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- FEEDBACK TABLE
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    rating INT DEFAULT 5,
    status VARCHAR(50) DEFAULT 'Pending',
    admin_response TEXT,
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- SUPPLIERS TABLE
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    product_types VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PARTNERS TABLE
CREATE TABLE IF NOT EXISTS partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    partnership_type VARCHAR(100),
    status VARCHAR(50) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PURCHASE ORDERS TABLE
CREATE TABLE IF NOT EXISTS purchase_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    received_date TIMESTAMP NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Initialize inventory for all products
INSERT INTO inventory (product_id, stock_quantity, low_stock_alert)
SELECT id, 50, 5 FROM products;
