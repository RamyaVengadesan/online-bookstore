-- Create Database
CREATE DATABASE IF NOT EXISTS bookstore_db;
USE bookstore_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Books Table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(20) NOT NULL,
    name VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    publisher VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    stock INT DEFAULT 0,
    rating DECIMAL(2, 1) DEFAULT 0.0,
    image TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status VARCHAR(20) DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Sample Books
INSERT INTO books (id, category, name, author, publisher, price, isbn, stock, rating, image) VALUES
(1, 'cse', 'Data Structures and Algorithms', 'Mark Allen Weiss', 'Pearson', 550.00, '978-0132847377', 15, 4.5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyrS1y1CFjWMCQrEWIGqxIMlUuoFKpxdZq-ETa6RNUnP6vahqvfYjIi3IHp30f5PcgYOs&usqp=CAU'),
(2, 'cse', 'Operating System Concepts', 'Abraham Silberschatz', 'Wiley', 600.00, '978-1118063330', 12, 4.7, 'https://www.wileyindia.com/pub/media/catalog/product/cache/20f980a1f90e8cec7a3c8f2cf40a32a8/9/7/9789357460569.png'),
(3, 'cse', 'Computer Networks', 'Andrew S. Tanenbaum', 'Pearson', 500.00, '978-0132126953', 20, 4.6, 'https://booksdelivery.com/image/cache/catalog/books/pearson/computer-networks-%205th-edition-550x550h.jpeg'),
(4, 'cse', 'Database System Concepts', 'Henry F. Korth', 'McGraw Hill', 650.00, '978-0073523323', 8, 4.8, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSJRTnR1YV0iR6o8QEOiBHhXkAXJgktJTRbQw&s'),
(5, 'cse', 'Introduction to Algorithms', 'CLRS', 'MIT Press', 700.00, '978-0262033848', 10, 5.0, 'https://images.bookoutlet.com/covers/large/isbn978026/9780262033848-l.jpg'),
(6, 'ece', 'Digital Signal Processing', 'John G. Proakis', 'McGraw Hill', 700.00, '978-0131873742', 14, 4.4, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQohApo0XfezA1flcP0T53YRSdHIyTKLySthA&s'),
(7, 'ece', 'Microelectronic Circuits', 'Sedra/Smith', 'Oxford', 680.00, '978-0199476299', 11, 4.7, 'https://india.oup.com/covers/pdp/9780199476299'),
(8, 'ece', 'VLSI Design', 'Sung-Mo Kang', 'Wiley', 800.00, '978-0073380629', 6, 4.3, 'https://m.media-amazon.com/images/I/61uVlZen4UL._UF1000,1000_QL80_.jpg'),
(9, 'ece', 'Communication Systems', 'Simon Haykin', 'Wiley', 720.00, '978-0471697909', 9, 4.6, 'https://images-eu.ssl-images-amazon.com/images/I/812kbysLO7L._AC_UL600_SR600,600_.jpg'),
(10, 'ece', 'Electronic Devices and Circuits', 'Robert L. Boylestad', 'Pearson', 660.00, '978-0132622264', 13, 4.5, 'https://m.media-amazon.com/images/I/71tIs-c488L._UF1000,1000_QL80_.jpg'),
(11, 'eee', 'Power Electronics', 'Ned Mohan', 'Wiley', 750.00, '978-1118074800', 10, 4.7, 'https://m.media-amazon.com/images/I/715FoXarqOL._UF1000,1000_QL80_.jpg'),
(12, 'eee', 'Electric Machinery Fundamentals', 'Stephen J. Chapman', 'McGraw Hill', 650.00, '978-0073380466', 12, 4.6, 'https://www.mheducation.com/cover-images/Webp_400-wide/0073380466.webp'),
(13, 'eee', 'Modern Control Systems', 'Katsuhiko Ogata', 'Prentice Hall', 680.00, '978-0136156734', 15, 4.8, 'https://m.media-amazon.com/images/I/71e5+rVAL5L._UF1000,1000_QL80_.jpg'),
(14, 'eee', 'Power System Analysis', 'William D. Stevenson', 'McGraw Hill', 700.00, '978-0070612938', 8, 4.5, 'https://pragationline.com/wp-content/uploads/2021/03/ELEMENTS-OF-POWER-SYSTEM-ANALYSIS-WILLIAM-D.-STEVENSON-JR.jpg'),
(15, 'eee', 'High Voltage Engineering', 'M.S. Naidu', 'Tata McGraw Hill', 740.00, '978-0074636343', 7, 4.4, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQH7I8QdWBEH6kFXztPH5m2a1IMBXJ1El10lQ&s'),
(16, 'civil', 'Structural Analysis', 'R.C. Hibbeler', 'Pearson', 600.00, '978-0134382593', 16, 4.6, 'https://m.media-amazon.com/images/I/51AXTSfNRfL._UF1000,1000_QL80_.jpg'),
(17, 'civil', 'Geotechnical Engineering', 'Braja M. Das', 'Cengage', 620.00, '978-1305635180', 11, 4.5, 'https://d9h54mr6do0h0.cloudfront.net/bookimage/9789355738103_tn.jpg'),
(18, 'civil', 'Properties of Concrete', 'A.M. Neville', 'Pearson', 550.00, '978-0273755807', 14, 4.7, 'https://m.media-amazon.com/images/I/81vZPHq89+L._UF1000,1000_QL80_.jpg'),
(19, 'civil', 'Surveying and Levelling', 'B.C. Punmia', 'Laxmi Publications', 580.00, '978-8170080558', 18, 4.3, 'https://m.media-amazon.com/images/I/51tADigREHL.jpg'),
(20, 'civil', 'Highway Engineering', 'S.K. Khanna', 'Nem Chand', 640.00, '978-8121903462', 10, 4.4, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQG0a83iyfffk_ykV_QMBtFZIzg5qvlMiyuYg&s');

-- Insert Sample User (password: password123)
INSERT INTO users (fullname, username, email, password) VALUES
('Test User', 'testuser', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');