/**
 * =====================================================
 * Bhagwan Vishwakarma Mandir — Main JavaScript
 * =====================================================
 */

document.addEventListener('DOMContentLoaded', function() {

    // =====================================================
    // 1. NAVBAR SCROLL EFFECT
    // =====================================================
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // =====================================================
    // 2. BACK TO TOP BUTTON
    // =====================================================
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // =====================================================
    // 3. ANNOUNCEMENT BAR CLOSE
    // =====================================================
    const closeAnnouncement = document.getElementById('closeAnnouncement');
    const announcementBar = document.getElementById('announcementBar');
    if (closeAnnouncement && announcementBar) {
        closeAnnouncement.addEventListener('click', function() {
            announcementBar.style.transition = 'all 0.3s ease';
            announcementBar.style.maxHeight = '0';
            announcementBar.style.padding = '0';
            announcementBar.style.overflow = 'hidden';
            setTimeout(() => announcementBar.remove(), 300);
            sessionStorage.setItem('announcementClosed', 'true');
        });

        // Hide if already closed this session
        if (sessionStorage.getItem('announcementClosed') === 'true') {
            announcementBar.remove();
        }
    }

    // =====================================================
    // 4. SCROLL ANIMATIONS (IntersectionObserver)
    // =====================================================
    const animatedElements = document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right');
    if (animatedElements.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        animatedElements.forEach(function(el) {
            observer.observe(el);
        });
    } else {
        // Fallback: make all visible immediately
        animatedElements.forEach(function(el) {
            el.classList.add('visible');
        });
    }

    // =====================================================
    // 5. COUNTDOWN TIMERS
    // =====================================================
    const countdowns = document.querySelectorAll('[data-target-date]');
    countdowns.forEach(function(countdown) {
        const targetDate = new Date(countdown.dataset.targetDate).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const diff = targetDate - now;

            if (diff <= 0) {
                countdown.innerHTML = '<p style="color: var(--gold-light); font-size: 1.25rem;">🎉 Event has started!</p>';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            const daysEl = countdown.querySelector('#countdown-days') || countdown.querySelectorAll('.countdown-number')[0];
            const hoursEl = countdown.querySelector('#countdown-hours') || countdown.querySelectorAll('.countdown-number')[1];
            const minutesEl = countdown.querySelector('#countdown-minutes') || countdown.querySelectorAll('.countdown-number')[2];
            const secondsEl = countdown.querySelector('#countdown-seconds') || countdown.querySelectorAll('.countdown-number')[3];

            if (daysEl) daysEl.textContent = days;
            if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
            if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
            if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });

    // =====================================================
    // 6. GLIGHTBOX INITIALIZATION
    // =====================================================
    if (typeof GLightbox !== 'undefined') {
        GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });
    }

    // =====================================================
    // 7. CSRF TOKEN HELPER
    // =====================================================
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // =====================================================
    // 8. NEWSLETTER FORM (AJAX)
    // =====================================================
    const newsletterForms = document.querySelectorAll('#homeNewsletterForm, #footerNewsletterForm');
    newsletterForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[name="email"]').value;
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('email', email);
            formData.append('csrf_token', getCsrfToken());

            fetch(BASE_URL + '/api/newsletter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;

                const msgEl = document.getElementById('newsletterMessage');
                if (msgEl) {
                    msgEl.style.display = 'block';
                    msgEl.className = 'mt-3 alert alert-' + (data.success ? 'success' : 'warning');
                    msgEl.textContent = data.message;
                    setTimeout(() => { msgEl.style.display = 'none'; }, 5000);
                }

                if (data.success) {
                    form.reset();
                    showToast('success', data.message);
                }
            })
            .catch(function() {
                btn.innerHTML = originalText;
                btn.disabled = false;
                showToast('danger', 'Something went wrong. Please try again.');
            });
        });
    });

    // =====================================================
    // 9. CONTACT FORM (AJAX)
    // =====================================================
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            btn.disabled = true;

            const formData = new FormData(this);
            formData.append('csrf_token', getCsrfToken());

            fetch(BASE_URL + '/api/contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;

                if (data.success) {
                    showToast('success', data.message);
                    contactForm.reset();
                } else {
                    showToast('danger', data.message || 'Error sending message.');
                }
            })
            .catch(function() {
                btn.innerHTML = originalText;
                btn.disabled = false;
                showToast('danger', 'Something went wrong. Please try again.');
            });
        });
    }

    // =====================================================
    // 10. BOOKING MULTI-STEP FORM
    // =====================================================
    const bookingSteps = document.querySelectorAll('.step-content');
    const stepIndicators = document.querySelectorAll('.booking-step');
    const stepConnectors = document.querySelectorAll('.booking-step-connector');
    let currentStep = 0;

    window.nextStep = function(step) {
        // Validate current step before proceeding
        if (!validateStep(currentStep)) return;

        if (bookingSteps[currentStep]) bookingSteps[currentStep].classList.remove('active');
        if (stepIndicators[currentStep]) stepIndicators[currentStep].classList.remove('active');
        if (stepIndicators[currentStep]) stepIndicators[currentStep].classList.add('completed');
        if (stepConnectors[currentStep]) stepConnectors[currentStep].classList.add('active');

        currentStep = step;

        if (bookingSteps[currentStep]) bookingSteps[currentStep].classList.add('active');
        if (stepIndicators[currentStep]) stepIndicators[currentStep].classList.add('active');

        // If last step, populate review
        if (currentStep === bookingSteps.length - 1) {
            populateBookingReview();
        }
    };

    window.prevStep = function(step) {
        if (bookingSteps[currentStep]) bookingSteps[currentStep].classList.remove('active');
        if (stepIndicators[currentStep]) stepIndicators[currentStep].classList.remove('active');
        if (stepConnectors[step]) stepConnectors[step].classList.remove('active');
        if (stepIndicators[step]) stepIndicators[step].classList.remove('completed');

        currentStep = step;

        if (bookingSteps[currentStep]) bookingSteps[currentStep].classList.add('active');
        if (stepIndicators[currentStep]) stepIndicators[currentStep].classList.add('active');
    };

    function validateStep(step) {
        const stepEl = bookingSteps[step];
        if (!stepEl) return true;

        const requiredFields = stepEl.querySelectorAll('[required]');
        let valid = true;

        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Check radio buttons
        const radioGroups = stepEl.querySelectorAll('.puja-radio-group');
        radioGroups.forEach(function(group) {
            const checked = group.querySelector('input[type="radio"]:checked');
            if (!checked) {
                valid = false;
                showToast('warning', 'Please select a puja.');
            }
        });

        return valid;
    }

    function populateBookingReview() {
        const form = document.getElementById('bookingForm');
        if (!form) return;

        const reviewEl = document.getElementById('bookingReview');
        if (!reviewEl) return;

        const pujaRadio = form.querySelector('input[name="puja_id"]:checked');
        const pujaName = pujaRadio ? pujaRadio.closest('.puja-select-card').querySelector('.puja-name').textContent : '-';
        const pujaPrice = pujaRadio ? pujaRadio.closest('.puja-select-card').querySelector('.puja-price-text').textContent : '-';

        reviewEl.innerHTML = `
            <table class="table">
                <tr><td><strong>Puja</strong></td><td>${pujaName}</td></tr>
                <tr><td><strong>Date</strong></td><td>${form.querySelector('[name="puja_date"]')?.value || '-'}</td></tr>
                <tr><td><strong>Name</strong></td><td>${form.querySelector('[name="devotee_name"]')?.value || '-'}</td></tr>
                <tr><td><strong>Phone</strong></td><td>${form.querySelector('[name="devotee_phone"]')?.value || '-'}</td></tr>
                <tr><td><strong>Email</strong></td><td>${form.querySelector('[name="devotee_email"]')?.value || '-'}</td></tr>
                <tr><td><strong>Persons</strong></td><td>${form.querySelector('[name="num_persons"]')?.value || '1'}</td></tr>
                <tr><td><strong>Amount</strong></td><td>${pujaPrice}</td></tr>
            </table>
        `;
    }

    // =====================================================
    // 11. PUJA / GALLERY CATEGORY FILTER
    // =====================================================
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            const target = this.dataset.target || '.filter-item';

            // Update active button
            this.closest('.filter-buttons').querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Filter items
            document.querySelectorAll(target).forEach(function(item) {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = '';
                    item.style.animation = 'fadeIn 0.3s ease';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // =====================================================
    // 12. DONATION AMOUNT PRESETS
    // =====================================================
    const amountBtns = document.querySelectorAll('.amount-btn');
    const customAmountInput = document.querySelector('#donationAmount, input[name="amount"]');

    amountBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            amountBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            if (customAmountInput) {
                customAmountInput.value = this.dataset.amount;
            }
        });
    });

    // =====================================================
    // 13. SMOOTH SCROLL FOR ANCHOR LINKS
    // =====================================================
    document.querySelectorAll('a[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // =====================================================
    // 14. TOAST NOTIFICATION HELPER
    // =====================================================
    window.showToast = function(type, message) {
        // Create toast container if not exists
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        const icons = {
            success: 'fa-check-circle',
            danger: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        const toastHtml = `
            <div class="toast align-items-center text-bg-${type} border-0" role="alert" data-bs-autohide="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${icons[type] || 'fa-info-circle'} me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', toastHtml);
        const toastEl = container.lastElementChild;
        const toast = new bootstrap.Toast(toastEl);
        toast.show();

        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    };

    // =====================================================
    // 15. BASE URL FOR AJAX
    // =====================================================
    if (!window.BASE_URL) {
        const baseUrlMeta = document.querySelector('link[rel="canonical"]');
        window.BASE_URL = baseUrlMeta ? new URL(baseUrlMeta.href).origin + '/BVM' : '/BVM';
    }

    // =====================================================
    // 16. FORM INPUT ANIMATIONS
    // =====================================================
    document.querySelectorAll('.bvm-form .form-control').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.closest('.mb-3, .form-group')?.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            this.closest('.mb-3, .form-group')?.classList.remove('focused');
        });
    });

    // =====================================================
    // 17. LAZY LOAD ENHANCEMENT
    // =====================================================
    if ('loading' in HTMLImageElement.prototype) {
        // Browser supports native lazy loading
        document.querySelectorAll('img[loading="lazy"]').forEach(function(img) {
            if (img.dataset.src) {
                img.src = img.dataset.src;
            }
        });
    } else {
        // Fallback for older browsers
        const lazyObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    }
                    lazyObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(function(img) {
            lazyObserver.observe(img);
        });
    }

    console.log('🙏 Bhagwan Vishwakarma Mandir — Website Loaded Successfully');
});
