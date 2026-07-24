<?php require_once 'session.php'; redirectIfNotLoggedIn(); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>من نحن - <?= SITE_NAME ?></title>
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
                    <li><a href="products.php">المنتجات</a></li>
                    <li><a href="about.php" class="active">من نحن</a></li>
                    <li><a href="contact.php">تواصل معنا</a></li>
                    <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a></li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <span></span><span></span><span></span>
                </div>
            </nav>
        </div>
    </header>

    <section class="about-page">
        <div class="container">
            <h1 class="page-title" style="font-size: 2.5rem; text-align: center; margin-bottom: 40px;">من نحن</h1>
            <div class="about-grid">
                <div class="about-text">
                    <h2>نبذة عن <?= SITE_NAME ?></h2>
                    <p>تأسست <?= SITE_NAME ?> عام 2010 بهدف توفير أفضل زيوت وقطع غيار السيارات الأصلية للمستهلكين في السودان. نقدم منتجات من أشهر الماركات العالمية بأسعار تنافسية بالجنيه السوداني مع ضمان شامل.</p>
                    <h3>رؤيتنا</h3>
                    <p>أن نكون الوجهة الأولى لعشاق السيارات في السودان والشرق الأوسط.</p>
                    <h3>رسالتنا</h3>
                    <p>تقديم أفضل المنتجات مع خدمة عملاء استثنائية وتجربة تسوق مريحة.</p>
                    <h3>أهدافنا</h3>
                    <ul>
                        <li>توسيع نطاق منتجاتنا لتشمل جميع احتياجات السيارات.</li>
                        <li>بناء شراكات قوية مع كبرى الشركات المصنعة.</li>
                        <li>تقديم دعم فني متميز لعملائنا.</li>
                    </ul>
                </div>
                <div class="about-image">
                    <img src="images/team-about.jpg" alt="فريق <?= SITE_NAME ?>" style="width:100%; border-radius:12px;">
                </div>
            </div>

            <h2 class="section-title">فريق العمل</h2>
            <div class="team-grid">
                <div class="team-card">
                    <img src="images/team-member1.jpg" alt="أحمد">
                    <h4>أحمد المدير العام</h4>
                    <p>15 سنة خبرة في مجال السيارات</p>
                </div>
                <div class="team-card">
                    <img src="images/team-member2.jpg" alt="سارة">
                    <h4>سارة مديرة التسويق</h4>
                    <p>10 سنوات خبرة</p>
                </div>
                <div class="team-card">
                    <img src="images/team-member3.jpg" alt="خالد">
                    <h4>خالد مدير المبيعات</h4>
                    <p>12 سنة خبرة في قطع الغيار</p>
                </div>
            </div>

            <h2 class="section-title">الإنجازات</h2>
            <div class="achievements" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; margin: 30px 0;">
                <div class="achievement-item" style="background: var(--primary); color: var(--white); padding: 20px 40px; border-radius: var(--border-radius); min-width: 150px; text-align: center;">
                    <span style="font-size: 2.5rem; font-weight: 800; color: var(--secondary); display: block;">+5000</span>
                    <p>عميل سعيد</p>
                </div>
                <div class="achievement-item" style="background: var(--primary); color: var(--white); padding: 20px 40px; border-radius: var(--border-radius); min-width: 150px; text-align: center;">
                    <span style="font-size: 2.5rem; font-weight: 800; color: var(--secondary); display: block;">+300</span>
                    <p>منتج متنوع</p>
                </div>
                <div class="achievement-item" style="background: var(--primary); color: var(--white); padding: 20px 40px; border-radius: var(--border-radius); min-width: 150px; text-align: center;">
                    <span style="font-size: 2.5rem; font-weight: 800; color: var(--secondary); display: block;">+15</span>
                    <p>سنوات خبرة</p>
                </div>
            </div>

            <h2 class="section-title">تاريخنا</h2>
            <div class="timeline">
                <div class="timeline-item"><span>2010</span><p>تأسيس الشركة</p></div>
                <div class="timeline-item"><span>2013</span><p>افتتاح أول فرع في ود مدني</p></div>
                <div class="timeline-item"><span>2018</span><p>إطلاق المتجر الإلكتروني</p></div>
                <div class="timeline-item"><span>2024</span><p>توسعنا إلى 5 فروع</p></div>
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