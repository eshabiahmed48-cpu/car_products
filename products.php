<?php
// products.php - صفحة عرض المنتجات مع دعم AJAX
require_once 'config.php';
require_once 'session.php';
redirectIfNotLoggedIn();

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'categories') {
        $stmt = $pdo->query("SELECT id, name, slug FROM categories WHERE is_active = 1 ORDER BY name");
        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll());
        exit;
    }

    if ($action === 'products') {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE p.is_active = 1";
        $params = [];
        $where = [];

        if ($category && $category !== 'all') {
            $where[] = "c.slug = ?";
            $params[] = $category;
        }
        if ($search) {
            $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if (!empty($where)) {
            $sql .= " AND " . implode(' AND ', $where);
        }

        $allowedSort = ['name', 'price', 'rating_avg', 'created_at'];
        if (!in_array($sort, $allowedSort)) $sort = 'name';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $sql .= " ORDER BY p.$sort $order LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        $countSql = "SELECT COUNT(*) FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1";
        if (!empty($where)) {
            $countSql .= " AND " . implode(' AND ', $where);
        }
        $countStmt = $pdo->prepare($countSql);
        $countParams = array_slice($params, 0, -2);
        $countStmt->execute($countParams);
        $total = $countStmt->fetchColumn();

        header('Content-Type: application/json');
        echo json_encode(['products' => $products, 'total' => (int)$total]);
        exit;
    }

    if ($action === 'latest') {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 4;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll());
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المنتجات - <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="loading-screen">
        <div class="loader"><div class="loader-spinner"></div><div class="loader-text">جاري التحميل...</div></div>
    </div>

    <header class="header">
        <div class="container header-container">
            <div class="logo">
                <img src="<?= SITE_LOGO ?>" alt="<?= SITE_NAME ?>" style="height:40px;">
                <span class="logo-text"><?= SITE_NAME ?></span>
            </div>
            <nav class="navbar">
                <ul class="nav-list">
                    <li><a href="home.php">الرئيسية</a></li>
                    <li><a href="products.php" class="active">المنتجات</a></li>
                    <li><a href="about.php">من نحن</a></li>
                    <li><a href="contact.php">تواصل معنا</a></li>
                    <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a></li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <span></span><span></span><span></span>
                </div>
            </nav>
        </div>
    </header>

    <section class="products-page">
        <div class="container">
            <h1 class="page-title" style="font-size: 2.5rem; text-align: center; margin-bottom: 40px;">منتجاتنا</h1>
            <div class="products-toolbar">
                <div class="search-box">
                    <input type="text" id="search-input" placeholder="ابحث عن منتج...">
                    <button id="search-btn"><i class="fas fa-search"></i></button>
                </div>
                <div class="filter-sort">
                    <select id="category-filter">
                        <option value="all">جميع التصنيفات</option>
                    </select>
                    <select id="sort-select">
                        <option value="name">الترتيب حسب الاسم</option>
                        <option value="price">السعر (منخفض)</option>
                        <option value="price-desc">السعر (مرتفع)</option>
                        <option value="rating_avg">التقييم</option>
                    </select>
                </div>
            </div>
            <div class="product-grid" id="product-grid"></div>
            <div class="pagination" id="pagination"></div>
        </div>
    </section>

    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-col"><h4><?= SITE_NAME ?></h4><p>متجر زيوت وقطع غيار السيارات</p></div>
            <div class="footer-col"><h4>روابط</h4><ul><li><a href="home.php">الرئيسية</a></li><li><a href="products.php">المنتجات</a></li><li><a href="about.php">من نحن</a></li><li><a href="contact.php">تواصل معنا</a></li></ul></div>
            <div class="footer-col"><h4>تواصل</h4><p><i class="fas fa-phone"></i> <?= SITE_PHONE ?></p><p><i class="fas fa-envelope"></i> <?= SITE_EMAIL ?></p><p><i class="fas fa-map-marker-alt"></i> <?= SITE_ADDRESS ?></p></div>
        </div>
        <div class="footer-bottom"><p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. جميع الحقوق محفوظة</p></div>
    </footer>

    <button id="back-to-top"><i class="fas fa-chevron-up"></i></button>
    <script src="script.js"></script>
</body>
</html>