// ============================================================
// script.js - جميع وظائف الموقع (كار بارتس)
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ---------- شاشة التحميل ----------
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        setTimeout(() => { loadingScreen.classList.add('hidden'); }, 800);
    }

    // ---------- القائمة المتنقلة ----------
    const hamburger = document.getElementById('hamburger');
    const navList = document.querySelector('.nav-list');
    if (hamburger && navList) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            navList.classList.toggle('open');
        });
        navList.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navList.classList.remove('open');
            });
        });
    }

    // ---------- زر العودة للأعلى ----------
    const backToTop = document.getElementById('back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            backToTop.classList.toggle('show', window.scrollY > 300);
        });
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ---------- التمرير السلس ----------
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // ---------- سلايدر الشهادات ----------
    const slider = document.getElementById('testimonial-slider');
    if (slider) {
        const slides = slider.querySelectorAll('.testimonial-card');
        let currentSlide = 0;
        const prevBtn = document.querySelector('.slider-prev');
        const nextBtn = document.querySelector('.slider-next');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);

        let autoSlide = setInterval(nextSlide, 5000);
        slider.addEventListener('mouseenter', () => clearInterval(autoSlide));
        slider.addEventListener('mouseleave', () => {
            autoSlide = setInterval(nextSlide, 5000);
        });
    }

    // ---------- عدادات متحركة ----------
    const counters = document.querySelectorAll('.counter');
    if (counters.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-target'));
                    let current = 0;
                    const step = Math.ceil(target / 60);
                    const interval = setInterval(() => {
                        current += step;
                        if (current >= target) {
                            counter.textContent = target;
                            clearInterval(interval);
                        } else {
                            counter.textContent = current;
                        }
                    }, 25);
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(c => observer.observe(c));
    }

    // ---------- تحميل أحدث المنتجات ----------
    const latestGrid = document.getElementById('latest-product-grid');
    if (latestGrid) {
        fetch('products.php?action=latest&limit=4')
            .then(res => res.json())
            .then(data => {
                if (data && data.length) {
                    latestGrid.innerHTML = '';
                    data.forEach(p => {
                        const card = document.createElement('div');
                        card.className = 'product-card';
                        card.innerHTML = `
                            <img src="images/${p.image || 'default.jpg'}" alt="${p.name}" onerror="this.src='images/default.jpg'">
                            <div class="product-info">
                                <h3>${p.name}</h3>
                                <p class="price">${p.price} ج.س</p>
                            </div>
                        `;
                        latestGrid.appendChild(card);
                    });
                }
            })
            .catch(() => {});
    }

    // ---------- صفحة المنتجات (AJAX) ----------
    const productGrid = document.getElementById('product-grid');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const categoryFilter = document.getElementById('category-filter');
    const sortSelect = document.getElementById('sort-select');
    const pagination = document.getElementById('pagination');

    let currentPage = 1;
    const perPage = 6;
    let currentCategory = 'all';
    let currentSort = 'name';
    let currentOrder = 'ASC';
    let searchQuery = '';

    function loadCategories() {
        if (categoryFilter) {
            fetch('products.php?action=categories')
                .then(res => res.json())
                .then(data => {
                    if (data && data.length) {
                        data.forEach(cat => {
                            const option = document.createElement('option');
                            option.value = cat.slug;
                            option.textContent = cat.name;
                            categoryFilter.appendChild(option);
                        });
                    }
                })
                .catch(() => {});
        }
    }
    loadCategories();

    function loadProducts(page = 1, category = 'all', sort = 'name', order = 'ASC', search = '') {
        if (!productGrid) return;
        const offset = (page - 1) * perPage;
        let url = `products.php?action=products&limit=${perPage}&offset=${offset}&sort=${sort}&order=${order}`;
        if (category !== 'all') url += `&category=${category}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.products && data.products.length) {
                    renderProducts(data.products);
                    renderPagination(data.total, page);
                } else {
                    productGrid.innerHTML = '<p class="text-center" style="text-align:center; padding:40px;">لا توجد منتجات تطابق البحث</p>';
                    if (pagination) pagination.innerHTML = '';
                }
            })
            .catch(() => {
                productGrid.innerHTML = '<p class="text-center" style="text-align:center; padding:40px; color:red;">حدث خطأ في تحميل المنتجات</p>';
            });
    }

    function renderProducts(products) {
        if (!productGrid) return;
        productGrid.innerHTML = '';
        products.forEach(p => {
            const card = document.createElement('div');
            card.className = 'product-card';
            const discountBadge = p.discount_price && p.discount_price < p.price ? 
                `<span style="position:absolute; top:10px; left:10px; background:var(--secondary); color:#fff; padding:4px 12px; border-radius:20px; font-size:0.8rem;">خصم</span>` : '';
            card.innerHTML = `
                <div style="position:relative;">
                    <img src="images/${p.image || 'default.jpg'}" alt="${p.name}" onerror="this.src='images/default.jpg'">
                    ${discountBadge}
                </div>
                <div class="product-info">
                    <h3>${p.name}</h3>
                    <p style="font-size:0.9rem; color:var(--gray);">${p.category_name || ''}</p>
                    <p class="price">${p.discount_price || p.price} ج.س</p>
                    ${p.discount_price ? `<p style="text-decoration:line-through; color:#999; font-size:0.9rem;">${p.price} ج.س</p>` : ''}
                    <p class="rating">${'★'.repeat(Math.floor(p.rating_avg || 0))}${(p.rating_avg || 0) % 1 >= 0.5 ? '★' : ''} (${p.rating_avg || 0})</p>
                    <p class="stock">المخزون: ${p.stock}</p>
                    <div class="actions" style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;">
                        <button class="btn-details" data-id="${p.id}" style="flex:1; padding:8px; border:none; border-radius:30px; background:var(--primary); color:#fff; cursor:pointer;">تفاصيل</button>
                        <button class="btn-cart" data-id="${p.id}" style="flex:1; padding:8px; border:none; border-radius:30px; background:var(--secondary); color:#fff; cursor:pointer;">إضافة للسلة</button>
                    </div>
                </div>
            `;
            productGrid.appendChild(card);
        });

        document.querySelectorAll('.btn-details').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('تفاصيل المنتج رقم ' + this.dataset.id);
            });
        });
        document.querySelectorAll('.btn-cart').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('تم إضافة المنتج رقم ' + this.dataset.id + ' إلى السلة');
            });
        });
    }

    function renderPagination(total, current) {
        if (!pagination) return;
        const totalPages = Math.ceil(total / perPage);
        pagination.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            if (i === current) btn.classList.add('active');
            btn.addEventListener('click', function() {
                currentPage = i;
                loadProducts(currentPage, currentCategory, currentSort, currentOrder, searchQuery);
            });
            pagination.appendChild(btn);
        }
    }

    if (productGrid) {
        loadProducts(currentPage, currentCategory, currentSort, currentOrder);
    }

    // أحداث البحث والفلترة
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            searchQuery = searchInput ? searchInput.value.trim() : '';
            currentPage = 1;
            loadProducts(currentPage, currentCategory, currentSort, currentOrder, searchQuery);
        });
    }
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchQuery = this.value.trim();
                currentPage = 1;
                loadProducts(currentPage, currentCategory, currentSort, currentOrder, searchQuery);
            }
        });
    }
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            currentCategory = this.value;
            currentPage = 1;
            loadProducts(currentPage, currentCategory, currentSort, currentOrder, searchQuery);
        });
    }
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const val = this.value;
            if (val === 'price') { currentSort = 'price'; currentOrder = 'ASC'; }
            else if (val === 'price-desc') { currentSort = 'price'; currentOrder = 'DESC'; }
            else if (val === 'rating_avg') { currentSort = 'rating_avg'; currentOrder = 'DESC'; }
            else { currentSort = 'name'; currentOrder = 'ASC'; }
            currentPage = 1;
            loadProducts(currentPage, currentCategory, currentSort, currentOrder, searchQuery);
        });
    }

    // ---------- إشعارات ----------
    window.showNotification = function(message, type = 'info') {
        const notif = document.createElement('div');
        notif.className = `notification notification-${type}`;
        notif.textContent = message;
        notif.style.cssText = `
            position: fixed; bottom: 20px; right: 20px;
            background: var(--primary); color: var(--white);
            padding: 15px 25px; border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            z-index: 9999; font-weight: 600;
        `;
        document.body.appendChild(notif);
        setTimeout(() => {
            notif.style.opacity = '0';
            notif.style.transition = 'opacity 0.5s';
            setTimeout(() => notif.remove(), 500);
        }, 3000);
    };

    console.log('🚗 كار بارتس - تم تحميل الموقع بنجاح');
});