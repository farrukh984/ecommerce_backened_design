<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Authentication') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth-premium.css') }}?v={{ time() }}">
    
    <!-- GSAP + ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <!-- Theme JS -->
    <script src="{{ asset('js/theme.js') }}"></script>
    
    @yield('styles')
</head>
<body class="auth-body">

    <!-- ═══ PREMIUM LOADER ═══ -->
    <div id="auth-loader">
        <div class="loader-orb loader-orb-1"></div>
        <div class="loader-orb loader-orb-2"></div>
        <div class="loader-content">
            <div class="loader-icon">
                <div class="loader-icon-ring"></div>
                <div class="loader-icon-ring"></div>
                <div class="loader-icon-gem">
                    <i class="fa-solid fa-gem"></i>
                </div>
            </div>
            <div class="loader-brand">{{ config('app.name') }}</div>
            <div class="loader-progress">
                <div class="loader-progress-bar"></div>
            </div>
            <div class="loader-status">Loading Experience</div>
        </div>
    </div>

    <!-- ═══ ANIMATED MESH BACKGROUND ═══ -->
    <div class="auth-bg">
        <div class="auth-bg-overlay"></div>
        <div class="auth-particles" id="particles-container"></div>
    </div>

    <!-- ═══ THEME TOGGLE ═══ -->
    <div class="auth-theme-toggle">
        <button class="theme-btn theme-toggle" title="Toggle Theme">
            <i class="fa-solid fa-moon theme-toggle-icon"></i>
        </button>
    </div>

    <!-- ═══ MAIN — Split Screen ═══ -->
    <main class="auth-main">
        <div class="auth-wrapper {{ request()->routeIs('register') ? 'auth-reversed' : '' }}" id="auth-wrapper">
            <!-- LEFT: Form Panel -->
            <div class="auth-panel-left">
                @yield('content')
            </div>

            <!-- RIGHT: Showcase Image Panel -->
            <div class="auth-panel-right" id="showcase-panel">
                <div class="auth-showcase-img">
                    <img src="@yield('showcase-image', asset('images/auth/watch-showcase.png'))" 
                         alt="@yield('showcase-alt', 'Premium Watches')" 
                         id="showcase-img">
                </div>
                <div class="auth-showcase-overlay"></div>
                <div class="auth-showcase-content" id="showcase-content">
                    <div class="auth-showcase-badge gs-reveal-right">
                        <i class="fa-solid fa-gem"></i>
                        @hasSection('showcase-badge')
                            @yield('showcase-badge')
                        @else
                            PREMIUM COLLECTION
                        @endif
                    </div>
                    <h2 class="auth-showcase-title gs-reveal-right">
                        @hasSection('showcase-title')
                            @yield('showcase-title')
                        @else
                            Discover <span>Timeless</span> Elegance
                        @endif
                    </h2>
                    <p class="auth-showcase-desc gs-reveal-right">
                        @hasSection('showcase-desc')
                            @yield('showcase-desc')
                        @else
                            Explore our curated collection of luxury timepieces from world-renowned brands.
                        @endif
                    </p>
                    <div class="auth-showcase-stats gs-reveal-right">
                        @hasSection('showcase-stats')
                            @yield('showcase-stats')
                        @else
                            <div class="showcase-stat">
                                <div class="showcase-stat-value">500<span>+</span></div>
                                <div class="showcase-stat-label">Watches</div>
                            </div>
                            <div class="showcase-stat">
                                <div class="showcase-stat-value">50<span>+</span></div>
                                <div class="showcase-stat-label">Brands</div>
                            </div>
                            <div class="showcase-stat">
                                <div class="showcase-stat-value">24<span>/7</span></div>
                                <div class="showcase-stat-label">Support</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ═══ GLOBAL SCRIPTS ═══ -->
    <script>
        // ═══ PREMIUM LOADER ═══
        window.addEventListener('load', function() {
            const loader = document.getElementById('auth-loader');
            if (loader) {
                setTimeout(() => {
                    loader.classList.add('hide');
                    setTimeout(() => loader.remove(), 600);
                    // Trigger page entrance animations after loader
                    initPageAnimations();
                }, 1000);
            }
        });

        // Show loader on form submit
        document.addEventListener('submit', function(e) {
            if (e.defaultPrevented) return;
            let loader = document.getElementById('auth-loader');
            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'auth-loader';
                loader.innerHTML = `
                    <div class="loader-orb loader-orb-1"></div>
                    <div class="loader-orb loader-orb-2"></div>
                    <div class="loader-content">
                        <div class="loader-icon">
                            <div class="loader-icon-ring"></div>
                            <div class="loader-icon-ring"></div>
                            <div class="loader-icon-gem"><i class="fa-solid fa-gem"></i></div>
                        </div>
                        <div class="loader-brand">Processing</div>
                        <div class="loader-progress"><div class="loader-progress-bar"></div></div>
                        <div class="loader-status">Please Wait</div>
                    </div>`;
                document.body.appendChild(loader);
            } else {
                loader.classList.remove('hide');
            }
        });

        // ═══ FLOATING PARTICLES ═══
        (function() {
            const container = document.getElementById('particles-container');
            if (!container) return;
            const count = window.innerWidth < 576 ? 8 : 18;
            const colors = [
                'rgba(6, 182, 212, 0.3)',
                'rgba(14, 165, 233, 0.25)',
                'rgba(20, 184, 166, 0.2)',
                'rgba(99, 102, 241, 0.15)'
            ];
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                p.classList.add('particle');
                const size = Math.random() * 8 + 4;
                const color = colors[Math.floor(Math.random() * colors.length)];
                p.style.width = size + 'px';
                p.style.height = size + 'px';
                p.style.left = Math.random() * 100 + '%';
                p.style.bottom = -(Math.random() * 20) + '%';
                p.style.animationDuration = (Math.random() * 10 + 8) + 's';
                p.style.animationDelay = (Math.random() * 8) + 's';
                p.style.opacity = Math.random() * 0.4 + 0.15;
                p.style.background = 'radial-gradient(circle, ' + color + ' 0%, transparent 70%)';
                container.appendChild(p);
            }
        })();

        // ═══ PAGE ENTRANCE ANIMATIONS (GSAP) ═══
        function initPageAnimations() {
            const authWrapper = document.getElementById('auth-wrapper');
            const isReversed = authWrapper ? authWrapper.classList.contains('auth-reversed') : false;
            const playFlipIn = sessionStorage.getItem('playFlipIn') === 'true';

            // 1. FLIP TRANSITION
            if (authWrapper && playFlipIn) {
                sessionStorage.removeItem('playFlipIn');
                gsap.fromTo(authWrapper,
                    { rotationY: isReversed ? 90 : -90, scale: 0.9, opacity: 0 },
                    { rotationY: 0, scale: 1, opacity: 1, duration: 0.8, ease: "power3.out", clearProps: "all" }
                );
            }

            // Card entrance — cinematic slide up + fade
            const card = document.querySelector('.auth-card');
            if (card && !playFlipIn) {
                gsap.to(card, {
                    opacity: 1,
                    y: 0,
                    duration: 0.9,
                    ease: "power4.out",
                    delay: 0.1
                });
            } else if (card) {
               // Ensure visible if flipped
               gsap.set(card, { opacity: 1, y: 0 });
            }

            // Intercept flip toggles across pages
            document.querySelectorAll('.flip-trigger').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (e.defaultPrevented || window.innerWidth <= 860) return; 
                    e.preventDefault();
                    const targetUrl = this.href;
                    sessionStorage.setItem('playFlipIn', 'true');
                    gsap.to(authWrapper, {
                        rotationY: isReversed ? 90 : -90,
                        scale: 0.9,
                        opacity: 0,
                        duration: 0.6,
                        ease: "power2.in",
                        onComplete: () => { window.location.href = targetUrl; }
                    });
                });
            });

            // Form elements — staggered reveal
            gsap.fromTo(".auth-card .gs-reveal",
                { opacity: 0, y: 25 },
                { 
                    opacity: 1, y: 0, 
                    stagger: 0.07, 
                    duration: 0.7, 
                    ease: "power3.out", 
                    delay: 0.3,
                    clearProps: "all"
                }
            );

            // Brand icon — scale + rotate entrance
            gsap.fromTo(".auth-brand-icon",
                { opacity: 0, scale: 0.3, rotation: -15 },
                { 
                    opacity: 1, scale: 1, rotation: 0, 
                    duration: 0.8, 
                    ease: "back.out(1.7)", 
                    delay: 0.2,
                    clearProps: "all"
                }
            );

            // Brand name — typed reveal feel
            gsap.fromTo(".auth-brand-name",
                { opacity: 0, x: -20 },
                { 
                    opacity: 1, x: 0, 
                    duration: 0.6, 
                    ease: "power2.out", 
                    delay: 0.4,
                    clearProps: "all"
                }
            );

            // Showcase panel — right side animations
            const showcasePanel = document.getElementById('showcase-panel');
            if (showcasePanel && window.innerWidth > 860) {
                // Image parallax entrance
                gsap.fromTo("#showcase-img",
                    { scale: 1.2, opacity: 0 },
                    { 
                        scale: 1, opacity: 1, 
                        duration: 1.5, 
                        ease: "power2.out", 
                        delay: 0.2 
                    }
                );

                // Showcase content — slide from right
                gsap.fromTo(".auth-showcase-content .gs-reveal-right",
                    { opacity: 0, x: 50 },
                    { 
                        opacity: 1, x: 0, 
                        stagger: 0.12, 
                        duration: 0.8, 
                        ease: "power3.out", 
                        delay: 0.6,
                        clearProps: "all"
                    }
                );

                // Stats counter animation
                document.querySelectorAll('.showcase-stat-value').forEach(el => {
                    const text = el.textContent;
                    const num = parseInt(text);
                    if (!isNaN(num)) {
                        const suffix = text.replace(num.toString(), '');
                        gsap.from({ val: 0 }, {
                            val: num,
                            duration: 2,
                            ease: "power2.out",
                            delay: 1.2,
                            onUpdate: function() {
                                el.innerHTML = Math.round(this.targets()[0].val) + '<span>' + suffix + '</span>';
                            }
                        });
                    }
                });
            }

            // Input focus micro-animations
            document.querySelectorAll('.input-wrapper input').forEach(inp => {
                inp.addEventListener('focus', () => {
                    gsap.to(inp.closest('.input-wrapper'), { 
                        scale: 1.02, 
                        duration: 0.3, 
                        ease: "power2.out" 
                    });
                    gsap.to(inp.closest('.input-wrapper .icon'), { 
                        scale: 1.2, 
                        duration: 0.3, 
                        ease: "back.out(1.7)" 
                    });
                });
                inp.addEventListener('blur', () => {
                    gsap.to(inp.closest('.input-wrapper'), { 
                        scale: 1, 
                        duration: 0.2 
                    });
                    gsap.to(inp.closest('.input-wrapper .icon'), { 
                        scale: 1, 
                        duration: 0.2 
                    });
                });
            });

            // Button hover parallax
            const submitBtn = document.querySelector('.btn-submit');
            if (submitBtn) {
                submitBtn.addEventListener('mouseenter', () => {
                    gsap.to(submitBtn, { 
                        boxShadow: "0 16px 40px -8px rgba(6, 182, 212, 0.4)", 
                        duration: 0.3 
                    });
                });
                submitBtn.addEventListener('mouseleave', () => {
                    gsap.to(submitBtn, { 
                        boxShadow: "0 8px 20px -4px rgba(6, 182, 212, 0.25)", 
                        duration: 0.3 
                    });
                });
            }
        }

        // Fallback — if load already fired
        if (document.readyState === 'complete') {
            const loader = document.getElementById('auth-loader');
            if (loader && !loader.classList.contains('hide')) {
                setTimeout(() => {
                    loader.classList.add('hide');
                    setTimeout(() => loader.remove(), 600);
                    initPageAnimations();
                }, 800);
            }
        }

        // Safety fallback — guarantee animations fire within 3s max
        let _animInit = false;
        const _origInit = initPageAnimations;
        initPageAnimations = function() {
            if (_animInit) return;
            _animInit = true;
            _origInit();
        };
        setTimeout(() => {
            if (!_animInit) {
                const loader = document.getElementById('auth-loader');
                if (loader) { loader.classList.add('hide'); setTimeout(() => loader.remove(), 600); }
                initPageAnimations();
            }
        }, 3000);
    </script>

    @yield('scripts')
</body>
</html>
