-- --------------------------------------------------------
-- قاعدة البيانات: car_parts (زيوت وقطع غيار السيارات)
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS car_parts;
USE car_parts;

-- --------------------------------------------------------
-- جدول المستخدمين
-- --------------------------------------------------------
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    avatar VARCHAR(255) DEFAULT 'default.png',
    role ENUM('admin', 'customer') DEFAULT 'customer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول التصنيفات
-- --------------------------------------------------------
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    description TEXT NULL,
    icon VARCHAR(50) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول العلامات التجارية
-- --------------------------------------------------------
CREATE TABLE brands (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    logo VARCHAR(255) NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول المنتجات
-- --------------------------------------------------------
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    brand_id INT UNSIGNED NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) UNIQUE NOT NULL,
    sku VARCHAR(50) UNIQUE NOT NULL,
    description TEXT NULL,
    short_description VARCHAR(255) NULL,
    price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) NULL,
    stock INT NOT NULL DEFAULT 0,
    rating_avg DECIMAL(2,1) DEFAULT 0,
    rating_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول صور المنتجات
-- --------------------------------------------------------
CREATE TABLE product_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    image VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول التقييمات
-- --------------------------------------------------------
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول الطلبات
-- --------------------------------------------------------
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    grand_total DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'bank') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول تفاصيل الطلبات
-- --------------------------------------------------------
CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول سلة التسوق
-- --------------------------------------------------------
CREATE TABLE cart_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    session_id VARCHAR(100) NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول المفضلة
-- --------------------------------------------------------
CREATE TABLE wishlist (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- جدول رسائل التواصل
-- --------------------------------------------------------
CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- إدراج البيانات الأولية
-- --------------------------------------------------------
INSERT INTO categories (name, slug, description, icon) VALUES
('زيوت محركات', 'engine-oils', 'جميع أنواع زيوت المحركات', 'fa-oil-can'),
('فلاتر', 'filters', 'فلاتر هواء، زيت، وقود', 'fa-filter'),
('بطاريات', 'batteries', 'بطاريات سيارات بجميع الأنواع', 'fa-battery-full'),
('فرامل', 'brakes', 'أقراص وتيل فرامل', 'fa-stop'),
('إطارات', 'tires', 'إطارات بجميع المقاسات', 'fa-circle'),
('محركات وقطع', 'engines-parts', 'محركات كاملة وقطع غيار', 'fa-car'),
('إكسسوارات', 'accessories', 'إكسسوارات داخلية وخارجية', 'fa-paint-brush');

INSERT INTO brands (name, slug, description) VALUES
('تويوتا', 'toyota', 'العلامة اليابانية الشهيرة'),
('هوندا', 'honda', 'العلامة اليابانية المتطورة'),
('نيسان', 'nissan', 'العلامة اليابانية الموثوقة'),
('فورد', 'ford', 'العلامة الأمريكية العريقة'),
('بي إم دبليو', 'bmw', 'العلامة الألمانية الفاخرة'),
('مرسيدس', 'mercedes', 'العلامة الألمانية الفاخرة'),
('شيفروليه', 'chevrolet', 'العلامة الأمريكية'),
('كيا', 'kia', 'العلامة الكورية');

INSERT INTO products (category_id, brand_id, name, slug, sku, description, short_description, price, discount_price, stock, is_featured) VALUES
((SELECT id FROM categories WHERE slug = 'engine-oils'), (SELECT id FROM brands WHERE slug = 'toyota'), 'زيت تويوتا 5W-30 أصلي', 'toyota-5w30', 'OIL-001', 'زيت محرك أصلي 5W-30 مناسب لجميع سيارات تويوتا', 'زيت محرك 5W-30', 55000, 49900, 50, 1),
((SELECT id FROM categories WHERE slug = 'engine-oils'), (SELECT id FROM brands WHERE slug = 'honda'), 'زيت هوندا 10W-40', 'honda-10w40', 'OIL-002', 'زيت محرك 10W-40 عالي الجودة لسيارات هوندا', 'زيت محرك 10W-40', 48000, 45000, 40, 1),
((SELECT id FROM categories WHERE slug = 'filters'), (SELECT id FROM brands WHERE slug = 'toyota'), 'فلتر هواء تويوتا أصلي', 'toyota-air-filter', 'FIL-001', 'فلتر هواء أصلي لسيارات تويوتا', 'فلتر هواء أصلي', 15000, 12000, 100, 0),
((SELECT id FROM categories WHERE slug = 'filters'), (SELECT id FROM brands WHERE slug = 'nissan'), 'فلتر زيت نيسان', 'nissan-oil-filter', 'FIL-002', 'فلتر زيت أصلي لسيارات نيسان', 'فلتر زيت', 12000, 10000, 80, 0),
((SELECT id FROM categories WHERE slug = 'batteries'), (SELECT id FROM brands WHERE slug = 'toyota'), 'بطارية تويوتا 60 أمبير', 'toyota-battery-60ah', 'BAT-001', 'بطارية 60 أمبير/ساعة أصلية', 'بطارية 60 أمبير', 250000, 230000, 20, 1),
((SELECT id FROM categories WHERE slug = 'batteries'), (SELECT id FROM brands WHERE slug = 'mercedes'), 'بطارية مرسيدس 70 أمبير', 'mercedes-battery-70ah', 'BAT-002', 'بطارية 70 أمبير عالية الجودة', 'بطارية 70 أمبير', 350000, 320000, 15, 1),
((SELECT id FROM categories WHERE slug = 'brakes'), (SELECT id FROM brands WHERE slug = 'toyota'), 'أقراص فرامل تويوتا أمامية', 'toyota-front-brakes', 'BRK-001', 'أقراص فرامل أمامية أصلية', 'أقراص فرامل أمامية', 180000, 160000, 30, 0),
((SELECT id FROM categories WHERE slug = 'brakes'), (SELECT id FROM brands WHERE slug = 'ford'), 'تيل فرامل فورد خلفي', 'ford-rear-brake-pads', 'BRK-002', 'تيل فرامل خلفي عالي الجودة', 'تيل فرامل خلفي', 85000, 75000, 40, 0),
((SELECT id FROM categories WHERE slug = 'tires'), (SELECT id FROM brands WHERE slug = 'toyota'), 'إطار تويوتا 215/55R17', 'toyota-tire-215-55-17', 'TIR-001', 'إطار صيفي 215/55R17 مناسب لسيارات تويوتا', 'إطار صيفي 215/55R17', 350000, 320000, 25, 1),
((SELECT id FROM categories WHERE slug = 'tires'), (SELECT id FROM brands WHERE slug = 'bmw'), 'إطار بي إم دبليو 225/45R18', 'bmw-tire-225-45-18', 'TIR-002', 'إطار رياضي 225/45R18', 'إطار رياضي', 450000, 420000, 15, 1),
((SELECT id FROM categories WHERE slug = 'engines-parts'), (SELECT id FROM brands WHERE slug = 'toyota'), 'محرك تويوتا 2.0 لتر مستعمل', 'toyota-engine-2.0', 'ENG-001', 'محرك 2.0 لتر بحالة ممتازة', 'محرك 2.0 لتر', 2500000, 2300000, 3, 0),
((SELECT id FROM categories WHERE slug = 'accessories'), (SELECT id FROM brands WHERE slug = 'toyota'), 'غطاء مقعد تويوتا جلد', 'toyota-seat-cover', 'ACC-001', 'غطاء مقعد جلد فاخر', 'غطاء مقعد جلد', 150000, 130000, 50, 0);

INSERT INTO product_images (product_id, image, is_primary) VALUES
(1, 'toyota-5w30.jpg', 1),
(2, 'honda-10w40.jpg', 1),
(3, 'toyota-air-filter.jpg', 1),
(4, 'nissan-oil-filter.jpg', 1),
(5, 'toyota-battery-60ah.jpg', 1),
(6, 'mercedes-battery-70ah.jpg', 1),
(7, 'toyota-front-brakes.jpg', 1),
(8, 'ford-rear-brake-pads.jpg', 1),
(9, 'toyota-tire-215-55-17.jpg', 1),
(10, 'bmw-tire-225-45-18.jpg', 1),
(11, 'toyota-engine-2.0.jpg', 1),
(12, 'toyota-seat-cover.jpg', 1);

-- مستخدم تجريبي (admin@carparts.com / 123456)
INSERT INTO users (fullname, email, password, role) VALUES
('مدير الموقع', 'admin@carparts.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO reviews (product_id, user_id, rating, comment, is_approved) VALUES
(1, 1, 5, 'زيت ممتاز، السيارة أصبحت أكثر سلاسة', 1),
(5, 1, 5, 'بطارية قوية وتعمل بشكل رائع', 1),
(9, 1, 5, 'إطار ممتاز، ثبات على الطريق', 1);

INSERT INTO contact_messages (fullname, email, phone, subject, message) VALUES
('أحمد السياراتي', 'ahmed@example.com', '+249 12 345 6789', 'استفسار عن الزيوت', 'هل يتوفر زيت 5W-30 بكميات كبيرة؟');