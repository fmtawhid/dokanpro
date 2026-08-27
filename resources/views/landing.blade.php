<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DokanPro – Complete ERP for Modern Retail</title>
    <meta name="description" content="DokanPro is an advanced ERP for retail and store management with sales, inventory, accounting, HR, subscriptions, and analytics." />
    <meta name="keywords" content="DokanPro, ERP, Retail ERP, POS, Inventory, Accounting, HR, Analytics, Shop Management" />
    <meta name="robots" content="index, follow" />
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🛒</text></svg>" />

    <!-- Tailwind + Fonts -->
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <style>
        /* ── base ── */
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background: #fafcff;
            color: #0b1120;
        }

        /* ── animations ── */
        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(32px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            0% {
                opacity: 0;
                transform: scale(0.92);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-12px);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        .animate-fade-up {
            opacity: 0;
            animation: fadeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-fade-in {
            opacity: 0;
            animation: fadeIn 0.8s ease forwards;
        }

        .animate-scale-in {
            opacity: 0;
            animation: scaleIn 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-float {
            animation: float 5s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 6s ease-in-out infinite 1.5s;
        }

        .stagger-children>* {
            opacity: 0;
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .stagger-children>*:nth-child(1) {
            animation-delay: 0.05s;
        }
        .stagger-children>*:nth-child(2) {
            animation-delay: 0.10s;
        }
        .stagger-children>*:nth-child(3) {
            animation-delay: 0.15s;
        }
        .stagger-children>*:nth-child(4) {
            animation-delay: 0.20s;
        }
        .stagger-children>*:nth-child(5) {
            animation-delay: 0.25s;
        }
        .stagger-children>*:nth-child(6) {
            animation-delay: 0.30s;
        }
        .stagger-children>*:nth-child(7) {
            animation-delay: 0.35s;
        }
        .stagger-children>*:nth-child(8) {
            animation-delay: 0.40s;
        }
        .stagger-children>*:nth-child(9) {
            animation-delay: 0.45s;
        }
        .stagger-children>*:nth-child(10) {
            animation-delay: 0.50s;
        }

        /* ── cards ── */
        .card-hover {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px -12px rgba(79, 70, 229, 0.20), 0 8px 24px -8px rgba(0, 0, 0, 0.06);
        }

        .card-glow {
            transition: box-shadow 0.4s ease, transform 0.3s ease;
        }

        .card-glow:hover {
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.15), 0 20px 40px -12px rgba(79, 70, 229, 0.25);
            transform: translateY(-4px);
        }

        /* ── gradient text ── */
        .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #a855f7 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .gradient-text-2 {
            background: linear-gradient(135deg, #0b1120 0%, #4f46e5 80%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* ── buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 24px -6px rgba(79, 70, 229, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 16px 40px -8px rgba(79, 70, 229, 0.45);
        }

        .btn-outline {
            border: 2px solid rgba(79, 70, 229, 0.25);
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            border-color: #4f46e5;
            background: rgba(79, 70, 229, 0.06);
            transform: translateY(-2px);
        }

        /* ── misc ── */
        .glass {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .badge-pill {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.10), rgba(124, 58, 237, 0.08));
            border: 1px solid rgba(79, 70, 229, 0.12);
        }

        .section-divider {
            background: linear-gradient(90deg, transparent, rgba(79, 70, 229, 0.08), transparent);
            height: 1px;
        }

        /* scroll reveal (simple) */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* pricing featured */
        .pricing-featured {
            border: 2px solid #4f46e5;
            box-shadow: 0 20px 48px -16px rgba(79, 70, 229, 0.25);
            position: relative;
        }

        .pricing-featured::before {
            content: 'Most Popular';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 1.2rem;
            border-radius: 999px;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        @media (max-width: 640px) {
            .pricing-featured::before {
                font-size: 0.65rem;
                padding: 0.2rem 0.9rem;
                top: -10px;
            }
        }

        /* faq details */
        details summary::-webkit-details-marker {
            display: none;
        }

        details summary {
            list-style: none;
            cursor: pointer;
        }

        .faq-item {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .faq-item:hover {
            border-color: #4f46e5;
        }

        .faq-item[open] {
            border-color: #4f46e5;
            box-shadow: 0 4px 16px -8px rgba(79, 70, 229, 0.12);
        }

        /* footer link hover */
        .footer-link {
            transition: color 0.2s ease, transform 0.2s ease;
        }
        .footer-link:hover {
            color: #a78bfa;
            transform: translateX(4px);
        }

        /* scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #4f46e5, #7c3aed);
            border-radius: 999px;
        }
    </style>
</head>

<body>

    <!-- ════════════════════════════ NAV ════════════════════════════ -->
    <nav class="fixed top-0 left-0 w-full z-50 glass border-b border-gray-200/30 transition-shadow duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:shadow-indigo-500/40 transition">
                        <span class="text-white font-black text-lg tracking-tight">D</span>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-gray-900">Dokan<span class="gradient-text">Pro</span></span>
                </a>

                <!-- Desktop nav -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#modules" class="text-gray-600 hover:text-indigo-600 transition-colors">Modules</a>
                    <a href="#pricing" class="text-gray-600 hover:text-indigo-600 transition-colors">Pricing</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-indigo-600 transition-colors">Testimonials</a>
                    <a href="#faq" class="text-gray-600 hover:text-indigo-600 transition-colors">FAQ</a>
                </div>

                <!-- CTA -->
                <div class="flex items-center gap-3">
                    <a href="#" class="hidden sm:inline-block text-sm font-semibold text-gray-700 hover:text-indigo-600 transition">Sign In</a>
                    <a href="#" class="btn-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl tracking-wide">
                        <i class="fas fa-rocket mr-1.5"></i>Start Free
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ════════════════════════════ HERO ════════════════════════════ -->
    <section class="relative pt-32 pb-24 md:pt-44 md:pb-32 overflow-hidden bg-gradient-to-br from-white via-indigo-50/40 to-white">
        <!-- BG blobs -->
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-indigo-200/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-purple-200/25 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-100/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2.5 badge-pill rounded-full px-5 py-2 text-sm font-semibold text-indigo-700 mb-6 animate-fade-up">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                </span>
                Trusted by 1,200+ retailers worldwide
            </div>

            <!-- Headline -->
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-black leading-[1.08] tracking-tight text-gray-900 animate-fade-up" style="animation-delay:0.1s;">
                Complete ERP for
                <span class="gradient-text">Modern Retail</span>
            </h1>

            <p class="mt-6 max-w-2xl mx-auto text-lg sm:text-xl text-gray-600 leading-relaxed animate-fade-up" style="animation-delay:0.2s;">
                Manage sales, inventory, HR, accounting, and analytics from one powerful platform. Automate operations and grow faster.
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 max-w-lg mx-auto mt-10 py-6 px-4 rounded-2xl bg-white/60 backdrop-blur-sm border border-gray-200/50 animate-fade-up" style="animation-delay:0.3s;">
                <div>
                    <div class="text-2xl sm:text-3xl font-black gradient-text">8+</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-0.5">Core Modules</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-black gradient-text">99.9%</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-0.5">Uptime SLA</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-black gradient-text">3 Days</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-0.5">Setup Time</div>
                </div>
            </div>

            <!-- CTA -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10 animate-fade-up" style="animation-delay:0.4s;">
                <a href="#" class="btn-primary text-white font-bold px-8 py-4 rounded-xl text-base shadow-xl flex items-center justify-center gap-2">
                    <i class="fas fa-rocket"></i> Start 14-Day Free Trial
                </a>
                <a href="#" class="btn-outline text-indigo-700 font-bold px-8 py-4 rounded-xl text-base flex items-center justify-center gap-2">
                    <i class="fas fa-play-circle"></i> Watch Demo
                </a>
            </div>

            <!-- Trust -->
            <div class="flex flex-wrap gap-6 justify-center mt-8 text-sm text-gray-500 animate-fade-up" style="animation-delay:0.5s;">
                <span class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> No credit card</span>
                <span class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Cancel anytime</span>
                <span class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> 24/7 support</span>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════ FEATURES ════════════════════════════ -->
    <section id="features" class="py-20 md:py-28 bg-white relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/40 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block text-sm font-semibold text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full mb-4">Features</span>
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">Everything you need to <span class="gradient-text">succeed</span></h2>
                <p class="mt-4 text-lg text-gray-500">Powerful tools designed to streamline every aspect of your retail business</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 stagger-children">
                <!-- 1 -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-500/20 mb-4">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Point of Sale</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Fast, intuitive POS for quick checkouts, accurate invoicing, and seamless billing.</p>
                </div>
                <!-- 2 -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white text-xl shadow-lg shadow-emerald-500/20 mb-4">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Inventory Management</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Real-time stock tracking with barcode scanning, low-stock alerts, and auto-reorder.</p>
                </div>
                <!-- 3 -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white text-xl shadow-lg shadow-amber-500/20 mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Analytics & Reports</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Deep insights into sales, inventory, and financial performance with custom dashboards.</p>
                </div>
                <!-- 4 -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center text-white text-xl shadow-lg shadow-rose-500/20 mb-4">
                        <i class="fas fa-people-group"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">HR & Payroll</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Complete employee management, attendance tracking, and automated payroll processing.</p>
                </div>
                <!-- 5 -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-xl shadow-lg shadow-violet-500/20 mb-4">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Payment Gateway</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Secure payments with multiple method integration including cards, mobile, and cash.</p>
                </div>
                <!-- 6 -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-white text-xl shadow-lg shadow-cyan-500/20 mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Customer Management</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Build detailed customer profiles, track purchase history, and loyalty programs.</p>
                </div>
                <!-- 7 -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-500/20 mb-4">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Supplier Management</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Streamline procurement with supplier tracking, purchase orders, and delivery schedules.</p>
                </div>
                <!-- 8 -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white text-xl shadow-lg shadow-gray-700/20 mb-4">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Security & Backups</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Enterprise-grade encryption with automated daily backups and disaster recovery.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════ MODULES ════════════════════════════ -->
    <section id="modules" class="py-20 md:py-28 bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-300 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block text-sm font-semibold bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full border border-white/10 mb-4">Core Modules</span>
                <h2 class="text-4xl sm:text-5xl font-black tracking-tight">Who USE <span class="text-indigo-300">Dokan Pro </span></h2>
                <p class="mt-4 text-lg text-indigo-200/80">Integrated modules that work together to give you full control</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 stagger-children">
                @foreach ($shopCategories as $category)
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 card-glow hover:bg-white/10 transition">
                        <div class="text-3xl mb-3"><i class="fas fa-store"></i></div>
                        <h3 class="text-lg font-bold mb-1.5">{{ $category->name }}</h3>
                        <p class="text-sm text-indigo-200/70">{{ $category->description ?: 'Manage sales, inventory, and daily operations with ease.' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

        <!-- ════════════════════════════ WHY CHOOSE US ════════════════════════════ -->
    <section class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1 stagger-children">
                    <span class="inline-block text-sm font-semibold text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full mb-4">Why DokanPro</span>
                    <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">Built for <span class="gradient-text">growth</span></h2>
                    <p class="mt-4 text-lg text-gray-500 leading-relaxed">We combine powerful features with simplicity so you can focus on what matters — your business.</p>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($subscriptionFeatures as $feature)
                            <div class="flex gap-3 items-start">
                                <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 text-sm flex-shrink-0"><i class="fas fa-check"></i></div>
                                <div><h4 class="font-bold text-gray-900 text-sm">{{ $feature->name }}</h4><p class="text-xs text-gray-500">{{ $feature->description ?: 'Included in our active subscription plans.' }}</p></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="order-1 lg:order-2">
                    <div class="relative">
                        <div class="absolute -inset-6 bg-gradient-to-r from-indigo-100/40 to-purple-100/40 rounded-3xl blur-2xl"></div>
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-100">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=700&h=500&fit=crop&crop=center" alt="Team collaboration" class="w-full h-auto object-cover" loading="lazy" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════ PRICING ════════════════════════════ -->
    <section id="pricing" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block text-sm font-semibold text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full mb-4">Pricing</span>
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">Simple, <span class="gradient-text">transparent</span> pricing</h2>
                <p class="mt-4 text-lg text-gray-500">Choose the plan that fits your business size and needs</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto stagger-children">
                @foreach ($subscriptions as $subscription)
                    @php($isFeatured = $loop->index === (int) floor(($subscriptions->count() - 1) / 2))
                    <div class="bg-white rounded-2xl {{ $isFeatured ? 'pricing-featured' : 'border border-gray-200' }} p-8 card-hover">
                        <h3 class="text-xl font-bold text-gray-900">{{ $subscription->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $subscription->description ?: 'A flexible plan for your retail business.' }}</p>
                        <div class="mt-6">
                            <span class="text-4xl font-black {{ $isFeatured ? 'gradient-text' : 'text-gray-900' }}">{{ numberFormat($subscription->price) }}</span>
                            <span class="text-gray-400 text-sm">/{{ $subscription->recurring_type->value }}</span>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-emerald-600"><i class="fas fa-gift mr-1"></i>First 7 days free</p>
                        <ul class="mt-6 space-y-3 text-sm">
                            <li class="flex items-center gap-3 text-gray-600"><i class="fas fa-check-circle text-green-500 w-4"></i> Up to {{ $subscription->shop_limit }} outlet(s)</li>
                            <li class="flex items-center gap-3 text-gray-600"><i class="fas fa-check-circle text-green-500 w-4"></i> Up to {{ $subscription->product_limit }} products</li>
                            @foreach ($subscription->features->take(2) as $feature)
                                <li class="flex items-center gap-3 text-gray-600"><i class="fas fa-check-circle text-green-500 w-4"></i> {{ $feature->name }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('signup.index') }}" class="mt-8 block w-full text-center font-bold {{ $isFeatured ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg shadow-indigo-500/25' : 'text-indigo-600 border-2 border-indigo-200 hover:bg-indigo-50' }} rounded-xl py-3 transition">Start 7-Day Free Trial</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ════════════════════════════ INDUSTRY USE CASES ════════════════════════════ -->
    <section class="py-20 md:py-28 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block text-sm font-semibold text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full mb-4">Use Cases</span>
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">Built for <span class="gradient-text">every retail</span> vertical</h2>
                <p class="mt-4 text-lg text-gray-500">Tailored solutions for different business types</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 stagger-children">
                <!-- Fashion -->
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover">
                    <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-6xl opacity-80">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900">Fashion &amp; Apparel</h3>
                        <p class="text-sm text-gray-500 mt-2 leading-relaxed">Multi-size tracking, seasonal inventory, trend analytics, and collection management.</p>
                        <ul class="mt-4 space-y-1.5 text-sm text-gray-600">
                            <li class="flex items-center gap-2"><i class="fas fa-check text-indigo-500 w-4"></i> Size &amp; color variants</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-indigo-500 w-4"></i> Collection management</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-indigo-500 w-4"></i> Seasonal forecasting</li>
                        </ul>
                    </div>
                </div>

                <!-- Grocery -->
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover">
                    <div class="h-48 bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-6xl opacity-80">
                        <i class="fas fa-apple-alt"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900">Grocery &amp; Food</h3>
                        <p class="text-sm text-gray-500 mt-2 leading-relaxed">Expiry tracking, bulk purchasing, supplier integration, and batch management.</p>
                        <ul class="mt-4 space-y-1.5 text-sm text-gray-600">
                            <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500 w-4"></i> Expiry date alerts</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500 w-4"></i> Supplier management</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500 w-4"></i> Batch tracking</li>
                        </ul>
                    </div>
                </div>

                <!-- Electronics -->
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover">
                    <div class="h-48 bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white text-6xl opacity-80">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900">Electronics &amp; Tech</h3>
                        <p class="text-sm text-gray-500 mt-2 leading-relaxed">Warranty tracking, IMEI management, tech specs, and vendor management.</p>
                        <ul class="mt-4 space-y-1.5 text-sm text-gray-600">
                            <li class="flex items-center gap-2"><i class="fas fa-check text-amber-500 w-4"></i> Warranty tracking</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-amber-500 w-4"></i> IMEI / Serial logging</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check text-amber-500 w-4"></i> Vendor management</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════════ TESTIMONIALS ════════════════════════════ -->
    <section id="testimonials" class="py-20 md:py-28 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block text-sm font-semibold text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full mb-4">Testimonials</span>
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">What our <span class="gradient-text">customers</span> say</h2>
                <p class="mt-4 text-lg text-gray-500">Trusted by 1,200+ retailers worldwide</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 stagger-children">
                <!-- 1 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm card-hover">
                    <div class="flex text-amber-400 text-sm gap-0.5 mb-4"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="text-gray-700 leading-relaxed">"DokanPro transformed how we manage our store. The POS is incredibly fast and inventory tracking is spot-on. Highly recommended!"</p>
                    <div class="flex items-center gap-4 mt-6">
                        <div class="w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">FK</div>
                        <div><p class="font-bold text-gray-900 text-sm">Fatema Khan</p><p class="text-xs text-gray-500">Store Owner, Dhaka</p></div>
                    </div>
                </div>

                <!-- 2 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm card-hover">
                    <div class="flex text-amber-400 text-sm gap-0.5 mb-4"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="text-gray-700 leading-relaxed">"Best investment for our retail chain. The analytics dashboard gives real-time insights into our business performance."</p>
                    <div class="flex items-center gap-4 mt-6">
                        <div class="w-11 h-11 rounded-full bg-rose-100 flex items-center justify-center text-rose-700 font-bold text-sm">KA</div>
                        <div><p class="font-bold text-gray-900 text-sm">Karim Ahmed</p><p class="text-xs text-gray-500">Manager, Chittagong</p></div>
                    </div>
                </div>

                <!-- 3 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm card-hover">
                    <div class="flex text-amber-400 text-sm gap-0.5 mb-4"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="text-gray-700 leading-relaxed">"The support team is amazing. They helped us integrate within days. Our sales have increased 40% since implementation!"</p>
                    <div class="flex items-center gap-4 mt-6">
                        <div class="w-11 h-11 rounded-full bg-sky-100 flex items-center justify-center text-sky-700 font-bold text-sm">SI</div>
                        <div><p class="font-bold text-gray-900 text-sm">Sarah Islam</p><p class="text-xs text-gray-500">Founder, Sylhet</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- ════════════════════════════ CTA BANNER ════════════════════════════ -->
    <section class="py-20 md:py-28 relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-purple-300 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-scale-in">
                <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tight">Ready to transform your <span class="text-indigo-200">business</span>?</h2>
                <p class="mt-4 text-xl text-indigo-100/80 max-w-2xl mx-auto">Join 1,200+ retailers already managing smarter with DokanPro</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10">
                    <a href="#" class="bg-white text-indigo-700 font-bold px-10 py-4 rounded-xl shadow-xl hover:shadow-2xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-rocket"></i> Start Free Trial
                    </a>
                    <a href="#" class="border-2 border-white/30 text-white font-bold px-10 py-4 rounded-xl hover:bg-white/10 transition flex items-center justify-center gap-2">
                        <i class="fas fa-play-circle"></i> Watch Demo
                    </a>
                </div>
                <p class="mt-6 text-sm text-indigo-200/70 flex flex-wrap gap-4 justify-center">
                    <span><i class="fas fa-check-circle text-green-300 mr-1.5"></i> No credit card</span>
                    <span><i class="fas fa-check-circle text-green-300 mr-1.5"></i> 14-day free trial</span>
                    <span><i class="fas fa-check-circle text-green-300 mr-1.5"></i> Cancel anytime</span>
                </p>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════ FAQ ════════════════════════════ -->
    <section id="faq" class="py-20 md:py-28 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block text-sm font-semibold text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full mb-4">FAQ</span>
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight">Frequently Asked <span class="gradient-text">Questions</span></h2>
            </div>

            <div class="space-y-3 stagger-children">
                <!-- 1 -->
                <details class="faq-item bg-white border border-gray-200 rounded-xl p-5 group">
                    <summary class="flex items-center justify-between gap-4 text-gray-900 font-bold text-base">
                        Is my data secure with DokanPro?
                        <i class="fas fa-chevron-down text-indigo-500 text-sm group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">Yes. We use enterprise-grade 256-bit AES encryption and comply with ISO 27001 standards. All data is encrypted in transit and at rest.</p>
                </details>

                <!-- 2 -->
                <details class="faq-item bg-white border border-gray-200 rounded-xl p-5 group">
                    <summary class="flex items-center justify-between gap-4 text-gray-900 font-bold text-base">
                        Can I migrate from my current ERP?
                        <i class="fas fa-chevron-down text-indigo-500 text-sm group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">Absolutely. Our migration team helps you transfer data from any existing system. Most migrations are completed within 2–4 weeks with zero downtime.</p>
                </details>

                <!-- 3 -->
                <details class="faq-item bg-white border border-gray-200 rounded-xl p-5 group">
                    <summary class="flex items-center justify-between gap-4 text-gray-900 font-bold text-base">
                        What uptime guarantee do you offer?
                        <i class="fas fa-chevron-down text-indigo-500 text-sm group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">We guarantee 99.9% uptime with SLA backup. Our servers are distributed across multiple data centers for maximum availability.</p>
                </details>

                <!-- 4 -->
                <details class="faq-item bg-white border border-gray-200 rounded-xl p-5 group">
                    <summary class="flex items-center justify-between gap-4 text-gray-900 font-bold text-base">
                        Do you offer training &amp; onboarding?
                        <i class="fas fa-chevron-down text-indigo-500 text-sm group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">Yes. All plans include comprehensive onboarding, video tutorials, documentation, and live training sessions for your team.</p>
                </details>

                <!-- 5 -->
                <details class="faq-item bg-white border border-gray-200 rounded-xl p-5 group">
                    <summary class="flex items-center justify-between gap-4 text-gray-900 font-bold text-base">
                        Can I customize the system?
                        <i class="fas fa-chevron-down text-indigo-500 text-sm group-open:rotate-180 transition-transform duration-300"></i>
                    </summary>
                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">Definitely. You can configure workflows, fields, reports, and more. For advanced customization, our development team is ready to assist.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- ════════════════════════════ FOOTER ════════════════════════════ -->
    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 pb-12 border-b border-gray-800">
                <!-- brand -->
                <div class="col-span-2 sm:col-span-3 lg:col-span-1">
                    <a href="#" class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white font-black text-lg">D</span>
                        </div>
                        <span class="text-white font-extrabold text-xl tracking-tight">Dokan<span class="text-indigo-400">Pro</span></span>
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed max-w-xs">Complete ERP solution for modern retail businesses.</p>
                    <div class="flex gap-3 mt-5">
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-indigo-600 transition flex items-center justify-center text-gray-400 hover:text-white"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-indigo-600 transition flex items-center justify-center text-gray-400 hover:text-white"><i class="fab fa-twitter text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-indigo-600 transition flex items-center justify-center text-gray-400 hover:text-white"><i class="fab fa-linkedin-in text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-indigo-600 transition flex items-center justify-center text-gray-400 hover:text-white"><i class="fab fa-instagram text-sm"></i></a>
                    </div>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Product</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#features" class="footer-link">Features</a></li>
                        <li><a href="#pricing" class="footer-link">Pricing</a></li>
                        <li><a href="#" class="footer-link">Security</a></li>
                        <li><a href="#" class="footer-link">Updates</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Resources</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="footer-link">Documentation</a></li>
                        <li><a href="#" class="footer-link">Blog</a></li>
                        <li><a href="#" class="footer-link">API Docs</a></li>
                        <li><a href="#" class="footer-link">Support</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Company</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="footer-link">About</a></li>
                        <li><a href="#" class="footer-link">Careers</a></li>
                        <li><a href="#" class="footer-link">Contact</a></li>
                        <li><a href="#" class="footer-link">Status</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Legal</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="footer-link">Privacy</a></li>
                        <li><a href="#" class="footer-link">Terms</a></li>
                        <li><a href="#" class="footer-link">Cookie</a></li>
                        <li><a href="#" class="footer-link">GDPR</a></li>
                    </ul>
                </div>
            </div>

            <!-- bottom -->
            <div class="pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <span>&copy; 2026 DokanPro. All rights reserved.</span>
                <span class="flex items-center gap-1.5">Made with <i class="fas fa-heart text-rose-400 text-xs"></i> for retailers</span>
            </div>
        </div>
    </footer>

    <!-- ════════════════════════════ SCROLL REVEAL (simple) ════════════════════════════ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reveals = document.querySelectorAll('.reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

            reveals.forEach(el => observer.observe(el));

            // navbar shadow on scroll
            const nav = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                nav.classList.toggle('shadow-md', window.scrollY > 20);
            });
        });
    </script>

</body>
</html>