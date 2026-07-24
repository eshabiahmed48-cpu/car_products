<?php require_once 'session.php'; redirectIfNotLoggedIn(); $user = getCurrentUser(); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرئيسية - <?= SITE_NAME ?></title>
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
                    <li><a href="home.php" class="active">الرئيسية</a></li>
                    <li><a href="products.php">المنتجات</a></li>
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

    <section class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1>أفضل زيوت وقطع غيار للسيارات</h1>
                <p>نقدم لك منتجات أصلية بأسعار تنافسية، مع خدمة توصيل سريعة لجميع أنحاء السودان</p>
                <div class="hero-buttons">
                    <a href="products.php" class="btn btn-primary">تسوق الآن <i class="fas fa-arrow-left"></i></a>
                    <a href="about.php" class="btn btn-outline">تعرف علينا</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="images/hero-car.jpg" alt="سيارة" style="max-width:100%; border-radius:12px;">
            </div>
        </div>
    </section>

    <section class="services">
        <div class="container">
            <h2 class="section-title">خدماتنا</h2>
            <div class="services-grid">
                <div class="service-card"><i class="fas fa-oil-can"></i><h3>زيوت أصلية</h3><p>جميع أنواع الزيوت من أفضل الماركات</p></div>
                <div class="service-card"><i class="fas fa-filter"></i><h3>فلاتر عالية الجودة</h3><p>فلاتر هواء وزيت ووقود</p></div>
                <div class="service-card"><i class="fas fa-battery-full"></i><h3>بطاريات قوية</h3><p>بطاريات بضمان طويل الأمد</p></div>
                <div class="service-card"><i class="fas fa-tools"></i><h3>قطع غيار أصلية</h3><p>محركات، فرامل، إطارات، وإكسسوارات</p></div>
            </div>
        </div>
    </section>

    <section class="stats" style="background: var(--primary); color: var(--white); padding: 60px 0;">
        <div class="container stats-container" style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 30px;">
            <div class="stat-item"><span class="counter" data-target="120">0</span><p>عميل سعيد</p></div>
            <div class="stat-item"><span class="counter" data-target="200">0</span><p>منتج متوفر</p></div>
            <div class="stat-item"><span class="counter" data-target="15">0</span><p>سنوات خبرة</p></div>
            <div class="stat-item"><span class="counter" data-target="5">0</span><p>فروع</p></div>
        </div>
    </section>

    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">آراء العملاء</h2>
            <div class="testimonial-slider" id="testimonial-slider">
                <div class="testimonial-card active">
                    <p>"منتجات رائعة وسعر ممتاز، أنصح الجميع بـ <?= SITE_NAME ?>"</p>
                    <h4>أحمد علي</h4>
                </div>
                <div class="testimonial-card">
                    <p>"خدمة سريعة ومحترفة، شكراً لفريق <?= SITE_NAME ?>"</p>
                    <h4>سارة خالد</h4>
                </div>
                <div class="testimonial-card">
                    <p>"أفضل متجر قطع غيار في السودان، تعامل راقي"</p>
                    <h4>محمد عبدالله</h4>
                </div>
            </div>
            <div class="slider-controls" style="text-align:center; margin-top:20px;">
                <button class="slider-prev"><i class="fas fa-chevron-right"></i></button>
                <button class="slider-next"><i class="fas fa-chevron-left"></i></button>
            </div>
        </div>
    </section>

    <section class="brands">
        <div class="container">
            <h2 class="section-title">العلامات التجارية</h2>
            <div class="brands-grid">
                <div class="brand-item">تويوتا</div>
                <div class="brand-item">هوندا</div>
                <div class="brand-item">نيسان</div>
                <div class="brand-item">فورد</div>
                <div class="brand-item">بي إم دبليو</div>
                <div class="brand-item">مرسيدس</div>
            </div>
        </div>
    </section>

    <section class="latest-products">
        <div class="container">
            <h2 class="section-title">أحدث المنتجات</h2>
            <div class="product-grid" id="latest-product-grid"></div>
        </div>
    </section>

    <section class="special-offers" style="background: var(--light); padding: 70px 0;">
        <div class="container">
            <div class="offer-banner" style="background: linear-gradient(135deg, var(--primary), #2a2a2a); padding: 40px; border-radius: var(--border-radius); text-align: center; color: var(--white);">
                <h3 style="font-size: 2.5rem; color: var(--secondary);">خصم 20% على جميع الزيوت</h3>
                <p>لفترة محدودة، استخدم الكود: OIL20</p>
                <a href="products.php" class="btn btn-primary">تسوق الآن</a>
            </div>
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