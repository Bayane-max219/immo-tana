/**
 * Immo Tana - main.js
 * AJAX filter, burger menu, animations, counter, contact forms
 */
(function () {
    'use strict';

    /* ------------------------------------------------------------------
       1. HEADER scroll effect
       ------------------------------------------------------------------ */
    const header = document.getElementById('site-header');
    function onScroll() {
        if (window.scrollY > 50) header.classList.add('scrolled');
        else header.classList.remove('scrolled');
        toggleBackToTop();
        highlightNav();
    }
    window.addEventListener('scroll', onScroll, { passive: true });

    /* ------------------------------------------------------------------
       2. BURGER MENU
       ------------------------------------------------------------------ */
    const burger  = document.getElementById('burger-menu');
    const nav     = document.getElementById('site-nav');
    const overlay = document.getElementById('nav-overlay');

    function closeMenu() {
        burger && burger.classList.remove('active');
        nav    && nav.classList.remove('open');
        overlay && overlay.classList.remove('active');
        burger && burger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }
    if (burger) {
        burger.addEventListener('click', function () {
            const open = nav.classList.toggle('open');
            burger.classList.toggle('active', open);
            overlay.classList.toggle('active', open);
            burger.setAttribute('aria-expanded', String(open));
            document.body.style.overflow = open ? 'hidden' : '';
        });
    }
    overlay && overlay.addEventListener('click', closeMenu);
    document.querySelectorAll('.nav-list a').forEach(a => a.addEventListener('click', closeMenu));

    /* ------------------------------------------------------------------
       3. SMOOTH SCROLL
       ------------------------------------------------------------------ */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const id = this.getAttribute('href');
            if (id === '#') return;
            const target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            const offset = (header ? header.offsetHeight : 72) + 8;
            const top = target.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top, behavior: 'smooth' });
        });
    });

    /* ------------------------------------------------------------------
       4. ACTIVE NAV LINK
       ------------------------------------------------------------------ */
    const sections = document.querySelectorAll('section[id]');
    function highlightNav() {
        const scrollPos = window.scrollY + (header ? header.offsetHeight : 72) + 40;
        sections.forEach(function (sec) {
            const top  = sec.offsetTop;
            const bot  = top + sec.offsetHeight;
            const link = document.querySelector('.nav-list a[href="#' + sec.id + '"]');
            if (link) link.classList.toggle('active', scrollPos >= top && scrollPos < bot);
        });
    }

    /* ------------------------------------------------------------------
       5. BACK TO TOP
       ------------------------------------------------------------------ */
    const backToTop = document.getElementById('back-to-top');
    function toggleBackToTop() {
        if (!backToTop) return;
        backToTop.classList.toggle('visible', window.scrollY > 400);
    }
    backToTop && backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    /* ------------------------------------------------------------------
       6. COUNTER ANIMATION (stats hero)
       ------------------------------------------------------------------ */
    function animateCounter(el) {
        const target = parseInt(el.dataset.count, 10);
        const duration = 1800;
        const step = target / (duration / 16);
        let current = 0;
        const timer = setInterval(function () {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = Math.floor(current);
        }, 16);
    }
    const counters = document.querySelectorAll('.stat-number[data-count]');
    if (counters.length && 'IntersectionObserver' in window) {
        const counterObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(c => counterObs.observe(c));
    }

    /* ------------------------------------------------------------------
       7. AJAX FILTER
       ------------------------------------------------------------------ */
    const searchBtn  = document.getElementById('btn-search');
    const resetBtn   = document.getElementById('btn-reset');
    const biensGrid  = document.getElementById('biens-grid');
    const loading    = document.getElementById('biens-loading');
    const noResults  = document.getElementById('no-results');
    const countNum   = document.getElementById('count-num');

    function getFilterValues() {
        return {
            type_bien: document.getElementById('q-type')    ? document.getElementById('q-type').value    : '',
            quartier:  document.getElementById('q-quartier') ? document.getElementById('q-quartier').value : '',
            statut:    document.getElementById('q-statut')   ? document.getElementById('q-statut').value   : '',
            prix_max:  document.getElementById('q-prix')     ? document.getElementById('q-prix').value     : '',
        };
    }

    function renderBienCard(bien) {
        const statutMap = {
            vente:    '<span class="statut-badge badge-vente">À vendre</span>',
            location: '<span class="statut-badge badge-location">À louer</span>',
            vendu:    '<span class="statut-badge badge-vendu">Vendu</span>',
            loue:     '<span class="statut-badge badge-loue">Loué</span>',
        };
        const badge   = statutMap[bien.statut] || '';
        const image   = bien.image || 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&q=80';
        const prix    = bien.prix  ? Number(bien.prix).toLocaleString('fr-FR') + ' Ar' + (bien.statut === 'location' ? '/mois' : '') : 'Prix sur demande';
        const type    = bien.type    && bien.type.length    ? '<span class="bien-type"><i class="fas fa-home"></i> ' + escHtml(bien.type[0]) + '</span>'    : '';
        const qtier   = bien.quartier && bien.quartier.length ? '<span class="bien-location"><i class="fas fa-map-marker-alt"></i> ' + escHtml(bien.quartier[0]) + '</span>' : '';
        const surface = bien.surface ? '<span><i class="fas fa-ruler-combined"></i> ' + escHtml(bien.surface) + ' m²</span>' : '';
        const pieces  = bien.pieces  ? '<span><i class="fas fa-door-open"></i> '       + escHtml(bien.pieces)  + ' pièces</span>' : '';
        const bains   = bien.bains   ? '<span><i class="fas fa-bath"></i> '            + escHtml(bien.bains)   + ' bain(s)</span>' : '';
        const waMsg   = encodeURIComponent('Bonjour Immo Tana, je suis intéressé(e) par : ' + bien.title);

        return `<article class="bien-card anim-fade">
            <div class="bien-card-image">
                <img src="${escHtml(image)}" alt="${escHtml(bien.title)}" loading="lazy">
                ${badge}
                <div class="bien-card-overlay">
                    <a href="${escHtml(bien.permalink)}" class="btn-voir"><i class="fas fa-eye"></i> Voir le bien</a>
                </div>
            </div>
            <div class="bien-card-body">
                <div class="bien-meta-top">${type}${qtier}</div>
                <h3 class="bien-title"><a href="${escHtml(bien.permalink)}">${escHtml(bien.title)}</a></h3>
                <div class="bien-features">${surface}${pieces}${bains}</div>
                <div class="bien-card-footer">
                    <div class="bien-prix">${prix}</div>
                    <div class="bien-actions">
                        <a href="${escHtml(bien.permalink)}" class="btn-detail">Détails</a>
                        <a href="https://wa.me/261348349886?text=${waMsg}" target="_blank" rel="noopener" class="btn-wa"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </article>`;
    }

    function doFilter() {
        if (!biensGrid || typeof immoAjax === 'undefined') return;

        const values = getFilterValues();
        const data   = new FormData();
        data.append('action', 'immo_filter');
        data.append('nonce',  immoAjax.nonce);
        Object.entries(values).forEach(([k, v]) => data.append(k, v));

        biensGrid.style.opacity = '0.4';
        biensGrid.style.pointerEvents = 'none';
        if (loading)   loading.style.display = 'block';
        if (noResults) noResults.style.display = 'none';

        fetch(immoAjax.ajaxurl, { method: 'POST', body: data })
            .then(r => r.json())
            .then(function (res) {
                biensGrid.style.opacity = '1';
                biensGrid.style.pointerEvents = '';
                if (loading) loading.style.display = 'none';

                if (res.success && res.data.biens.length > 0) {
                    biensGrid.innerHTML = res.data.biens.map(renderBienCard).join('');
                    if (countNum) countNum.textContent = res.data.count;
                    // Animate new cards
                    setTimeout(() => {
                        biensGrid.querySelectorAll('.anim-fade').forEach(el => el.classList.add('visible'));
                    }, 50);
                } else {
                    biensGrid.innerHTML = '';
                    if (countNum) countNum.textContent = '0';
                    if (noResults) noResults.style.display = 'block';
                }
            })
            .catch(function () {
                biensGrid.style.opacity = '1';
                biensGrid.style.pointerEvents = '';
                if (loading) loading.style.display = 'none';
            });
    }

    searchBtn && searchBtn.addEventListener('click', doFilter);
    resetBtn  && resetBtn.addEventListener('click', function () {
        ['q-type', 'q-quartier', 'q-statut', 'q-prix'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        doFilter();
    });

    // Recherche en temps réel sur les selects
    ['q-type', 'q-quartier', 'q-statut', 'q-prix'].forEach(id => {
        const el = document.getElementById(id);
        el && el.addEventListener('change', doFilter);
    });

    /* ------------------------------------------------------------------
       8. VUE GRILLE / LISTE
       ------------------------------------------------------------------ */
    const viewGrid = document.getElementById('view-grid');
    const viewList = document.getElementById('view-list');
    viewGrid && viewGrid.addEventListener('click', function () {
        biensGrid && biensGrid.classList.remove('list-view');
        viewGrid.classList.add('active');
        viewList && viewList.classList.remove('active');
    });
    viewList && viewList.addEventListener('click', function () {
        biensGrid && biensGrid.classList.add('list-view');
        viewList.classList.add('active');
        viewGrid && viewGrid.classList.remove('active');
    });

    /* ------------------------------------------------------------------
       9. CONTACT FORM (footer)
       ------------------------------------------------------------------ */
    const contactForm = document.getElementById('immo-contact-form');
    const alertBox    = document.getElementById('form-alert');
    const submitBtn   = document.getElementById('form-submit');

    contactForm && contactForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const name  = contactForm.querySelector('[name="name"]').value.trim();
        const email = contactForm.querySelector('[name="email"]').value.trim();
        const phone = contactForm.querySelector('[name="phone"]').value.trim();
        const msg   = contactForm.querySelector('[name="message"]').value.trim();

        if (!name || !email || !phone || !msg) { showAlert(alertBox, 'Veuillez remplir tous les champs.', 'error'); return; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showAlert(alertBox, 'Email invalide.', 'error'); return; }

        setLoading(submitBtn, true, 'fas fa-spinner fa-spin', 'Envoi…');
        hideAlert(alertBox);

        const data = new FormData(contactForm);
        data.append('action', 'immo_contact');
        data.append('nonce',  immoAjax.nonce);

        fetch(immoAjax.ajaxurl, { method: 'POST', body: data })
            .then(r => r.json())
            .then(function (res) {
                if (res.success) {
                    showAlert(alertBox, res.data.message || 'Message envoyé ! Nous vous contactons sous 2h.', 'success');
                    contactForm.reset();
                } else {
                    showAlert(alertBox, res.data.message || 'Erreur. Réessayez.', 'error');
                }
            })
            .catch(() => showAlert(alertBox, 'Erreur réseau.', 'error'))
            .finally(() => setLoading(submitBtn, false, 'fas fa-paper-plane', 'Envoyer le message'));
    });

    /* ------------------------------------------------------------------
       10. SINGLE CONTACT FORM
       ------------------------------------------------------------------ */
    const singleForm  = document.getElementById('single-contact-form');
    const singleAlert = document.getElementById('single-form-alert');
    const singleBtn   = document.getElementById('single-submit');

    singleForm && singleForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const name  = singleForm.querySelector('[name="name"]').value.trim();
        const email = singleForm.querySelector('[name="email"]').value.trim();
        const phone = singleForm.querySelector('[name="phone"]').value.trim();
        const msg   = singleForm.querySelector('[name="message"]').value.trim();

        if (!name || !email || !phone || !msg) { showAlert(singleAlert, 'Champs obligatoires manquants.', 'error'); return; }

        setLoading(singleBtn, true, 'fas fa-spinner fa-spin', 'Envoi…');
        hideAlert(singleAlert);

        const data = new FormData(singleForm);
        data.append('action', 'immo_contact');
        data.append('nonce',  immoAjax.nonce);

        fetch(immoAjax.ajaxurl, { method: 'POST', body: data })
            .then(r => r.json())
            .then(function (res) {
                if (res.success) {
                    showAlert(singleAlert, 'Demande envoyée ! Réponse sous 2h.', 'success');
                    singleForm.reset();
                } else {
                    showAlert(singleAlert, 'Erreur. Réessayez.', 'error');
                }
            })
            .catch(() => showAlert(singleAlert, 'Erreur réseau.', 'error'))
            .finally(() => setLoading(singleBtn, false, 'fas fa-paper-plane', 'Envoyer ma demande'));
    });

    /* ------------------------------------------------------------------
       11. INTERSECTION OBSERVER ANIMATIONS
       ------------------------------------------------------------------ */
    if ('IntersectionObserver' in window) {
        const animEls = document.querySelectorAll('.bien-card, .service-card, .contact-info-item, .apropos-list li, .feature-item');
        animEls.forEach((el, i) => {
            el.classList.add('anim-fade');
            el.style.transitionDelay = (i % 4) * 0.08 + 's';
        });
        const obs = new IntersectionObserver(function (entries) {
            entries.forEach(e => {
                if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
            });
        }, { threshold: 0.12 });
        animEls.forEach(el => obs.observe(el));
    }

    /* ------------------------------------------------------------------
       HELPERS
       ------------------------------------------------------------------ */
    function showAlert(box, msg, type) {
        if (!box) return;
        box.textContent = msg;
        box.className = 'form-alert ' + type;
    }
    function hideAlert(box) {
        if (!box) return;
        box.textContent = '';
        box.className = 'form-alert';
    }
    function setLoading(btn, loading, iconClass, text) {
        if (!btn) return;
        btn.disabled = loading;
        const icon = btn.querySelector('i');
        const span = btn.querySelector('span');
        if (icon) icon.className = iconClass;
        if (span) span.textContent = text;
    }
    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

})();
