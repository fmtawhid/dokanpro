<!doctype html>
<html lang="en">
@php
    $siteTitle = $general_settings?->site_title ?? 'DokanPro - Advanced ERP for Retail & Store Management';
    $siteDescription = $general_settings?->meta_description ?? 'DokanPro is an advanced ERP for retail and store management with sales, inventory, accounting, HR, subscriptions, and analytics.';
    $siteKeywords = $general_settings?->meta_keywords ?? 'DokanPro, ERP, Retail ERP, POS, Inventory, Accounting, HR, Analytics, Shop Management';
    $logoImage = $general_settings?->logo?->file ?? asset('assets/images/logo.png');
    $favicon = $general_settings?->favicon?->file ?? asset('/logo/small_logo.png');
    $pageUrl = url()->current();
    $dummyImage = asset('assets/images/logo.png');
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $siteTitle,
        'url' => url('/'),
        'logo' => $logoImage,
        'sameAs' => [
            'https://facebook.com',
            'https://twitter.com',
            'https://linkedin.com'
        ]
    ];
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteTitle }}</title>
    <link rel="icon" href="{{ $favicon }}" />
    <meta name="description" content="{{ $siteDescription }}">
    <meta name="keywords" content="{{ $siteKeywords }}">
    <meta name="robots" content="index, follow">
    <meta name="author" content="{{ $general_settings?->copyright_text ?? 'DokanPro' }}">
    <meta property="og:title" content="{{ $siteTitle }}">
    <meta property="og:description" content="{{ $siteDescription }}">
    <meta property="og:image" content="{{ $logoImage }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:site_name" content="{{ $siteTitle }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $siteTitle }}">
    <meta name="twitter:description" content="{{ $siteDescription }}">
    <meta name="twitter:image" content="{{ $logoImage }}">
    <meta name="twitter:site" content="@DokanPro">
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;800;900&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.6); }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes rotate-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes bounce-in {
            0% { opacity: 0; transform: scale(0.3); }
            50% { opacity: 1; }
            100% { transform: scale(1); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        @keyframes wobble {
            0%, 100% { transform: translateX(0); }
            15% { transform: translateX(-5px); }
            30% { transform: translateX(5px); }
            45% { transform: translateX(-5px); }
            60% { transform: translateX(5px); }
        }

        .animate-fade-in-down { animation: fadeInDown 0.8s ease-out; }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out; }
        .animate-slide-in-left { animation: slideInLeft 0.8s ease-out; }
        .animate-slide-in-right { animation: slideInRight 0.8s ease-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .animate-scale-in { animation: scaleIn 0.6s ease-out; }
        .animate-rotate-slow { animation: rotate-slow 8s linear infinite; }
        .animate-bounce-in { animation: bounce-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .animate-wobble { animation: wobble 0.8s ease-in-out; }

        /* ===== GRADIENTS ===== */
        .gradient-primary {
            background: linear-gradient(135deg, #5E17EB 0%, #A24DFF 100%);
        }

        .gradient-secondary {
            background: linear-gradient(135deg, #FF3131 0%, #FF6B6B 100%);
        }

        .gradient-tertiary {
            background: linear-gradient(135deg, #8A5BFF 0%, #FF3131 100%);
        }

        .gradient-quaternary {
            background: linear-gradient(135deg, #5E17EB 0%, #FF3131 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #5E17EB 0%, #FF3131 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== CARD EFFECTS ===== */
        .card-glow {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .card-glow:hover::before {
            left: 100%;
        }

        .card-glow:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        /* ===== BUTTON EFFECTS ===== */
        .btn-magic {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-magic::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-magic:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-magic:hover {
            transform: translateY(-3px);
        }

        /* ===== FEATURE CARDS ===== */
        .feature-card {
            position: relative;
            padding: 2rem;
            border-radius: 16px;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
        }

        .feature-card:hover {
            border-color: transparent;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }

        .feature-card .icon {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover .icon {
            transform: scale(1.2) rotate(10deg);
        }

        /* ===== STATS COUNTER ===== */
        .stat-box {
            text-align: center;
            padding: 2rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== PRICING CARDS ===== */
        .pricing-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .pricing-card.featured {
            transform: scale(1.05);
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .pricing-card .badge {
            position: absolute;
            top: -15px;
            right: 30px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* ===== TESTIMONIAL CARDS ===== */
        .testimonial-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            border-left: 4px solid;
        }

        .testimonial-card.blue { border-left-color: #667eea; }
        .testimonial-card.pink { border-left-color: #f5576c; }
        .testimonial-card.cyan { border-left-color: #00f2fe; }

        .stars {
            color: #fbbf24;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-top: 1rem;
            display: inline-block;
            border: 2px solid;
        }

        /* ===== STAGGER ANIMATION ===== */
        .stagger-item {
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .stagger-item:nth-child(1) { animation-delay: 0.1s; }
        .stagger-item:nth-child(2) { animation-delay: 0.2s; }
        .stagger-item:nth-child(3) { animation-delay: 0.3s; }
        .stagger-item:nth-child(4) { animation-delay: 0.4s; }
        .stagger-item:nth-child(5) { animation-delay: 0.5s; }
        .stagger-item:nth-child(6) { animation-delay: 0.6s; }
        .stagger-item:nth-child(7) { animation-delay: 0.7s; }
        .stagger-item:nth-child(8) { animation-delay: 0.8s; }
        .stagger-item:nth-child(9) { animation-delay: 0.9s; }
        .stagger-item:nth-child(10) { animation-delay: 1s; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .animate-slide-in-left,
            .animate-slide-in-right {
                animation: fadeInUp 0.8s ease-out !important;
            }

            .pricing-card.featured {
                transform: scale(1);
            }
        }

        /* ===== SPECIAL EFFECTS ===== */
        .blob {
            position: absolute;
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation: wobble 4s ease-in-out infinite;
        }

        .image-hover-zoom {
            overflow: hidden;
            border-radius: 12px;
        }

        .image-hover-zoom img {
            transition: transform 0.4s ease;
        }

        .image-hover-zoom:hover img {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

    <!-- ===== NAVIGATION ===== -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md shadow-lg">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center gap-3 animate-fade-in-down">
                    <img src="{{ $logoImage }}" alt="{{ $siteTitle }}" class="h-12 object-contain">
                </div>
                
                <div class="hidden md:flex gap-6 items-center text-sm font-medium">
                    <a href="#features" class="text-gray-600 hover:text-[#5E17EB] transition">Features</a>
                    <a href="#pricing" class="text-gray-600 hover:text-[#5E17EB] transition">Pricing</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-[#5E17EB] transition">Testimonials</a>
                </div>

                <div class="flex gap-3 animate-fade-in-down">
                    <a href="{{ route('signin.index') }}" class="px-4 py-2 text-gray-700 font-medium hover:text-[#5E17EB] transition hidden sm:block">Sign In</a>
                    <a href="{{ route('signup.index') }}" class="px-6 py-2 gradient-primary text-white rounded-lg font-semibold btn-magic shadow-lg hover:shadow-xl">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== HERO SECTION ===== -->
    <section class="relative overflow-hidden py-20 sm:py-32 bg-white">
        <!-- Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-[#5E17EB] rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-[#FF3131] rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-[#A24DFF] rounded-full mix-blend-multiply filter blur-3xl opacity-18 animate-float" style="animation-delay: 4s;"></div>
        </div>

        <div class="relative mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="animate-slide-in-left">
                    <div class="inline-block mb-6 px-4 py-2 bg-[#F3E8FF] rounded-full">
                        <span class="text-[#5E17EB] font-semibold text-sm flex items-center gap-2">
                            <i class="fas fa-star text-[#FF3131]"></i> Top Rated ERP Solution
                        </span>
                    </div>

                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-gray-900 mb-6 leading-tight">
                        Manage Your 
                        <span class="gradient-text">Retail Business</span>
                        Effortlessly
                    </h1>

                    <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                        DokanPro is the complete ERP solution for modern retail businesses. Manage sales, inventory, HR, accounting, and more from a single powerful platform.
                    </p>

                    <div class="grid grid-cols-3 gap-4 mb-8 py-6 border-y border-[#E9D7FF]">
                        <div>
                            <div class="text-2xl font-black gradient-text">8+</div>
                            <p class="text-sm text-gray-600">Modules</p>
                        </div>
                        <div>
                            <div class="text-2xl font-black gradient-text">1000+</div>
                            <p class="text-sm text-gray-600">Users</p>
                        </div>
                        <div>
                            <div class="text-2xl font-black gradient-text">99.9%</div>
                            <p class="text-sm text-gray-600">Uptime</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('signup.index') }}" class="px-8 py-4 gradient-primary text-white rounded-xl font-bold btn-magic text-center shadow-xl hover:shadow-2xl text-lg">
                            <i class="fas fa-rocket mr-2"></i>Get Started Free
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 border-2 border-[#5E17EB] text-[#5E17EB] rounded-xl font-bold hover:bg-[#5E17EB] hover:text-white transition text-center text-lg">
                            <i class="fas fa-play-circle mr-2"></i>View Demo
                        </a>
                    </div>

                    <div class="flex gap-6 mt-8 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>No credit card required</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>14 day free trial</span>
                        </div>
                    </div>
                </div>

                <!-- Right Visual -->
                <div class="hidden lg:block animate-slide-in-right">
                    <div class="relative">
                        <!-- Glowing Background -->
                        <div class="absolute -inset-4 gradient-primary opacity-20 rounded-3xl blur-3xl"></div>
                        
                        <!-- Main Card -->
                        <div class="relative bg-gradient-to-br from-white to-[#FBF6FF] rounded-3xl p-8 shadow-2xl border border-white/20 overflow-hidden">
                            <div class="mb-6 overflow-hidden rounded-3xl border border-[#E9D7FF]">
                                <img src="{{ $dummyImage }}" alt="DokanPro preview" class="w-full h-56 object-cover">
                            </div>
                            <!-- Dashboard Preview -->
                            <div class="space-y-6">
                                <!-- Header -->
                                <div class="flex justify-between items-center pb-4 border-b border-[#E9D7FF]">
                                    <div>
                                        <div class="h-3 bg-[#E9D7FF] rounded-full w-32 animate-pulse"></div>
                                        <div class="h-2 bg-[#F3E8FF] rounded-full w-48 mt-2 animate-pulse"></div>
                                    </div>
                                    <div class="w-10 h-10 bg-gradient-primary rounded-full animate-pulse"></div>
                                </div>

                                <!-- Charts -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-3">
                                        <div class="flex items-end gap-2 h-24">
                                            <div class="flex-1 bg-[#CDB3FF] rounded-t-lg h-16 animate-bounce-in" style="animation-delay: 0.2s;"></div>
                                            <div class="flex-1 bg-[#A24DFF] rounded-t-lg h-20 animate-bounce-in" style="animation-delay: 0.3s;"></div>
                                            <div class="flex-1 bg-[#5E17EB] rounded-t-lg h-24 animate-bounce-in" style="animation-delay: 0.4s;"></div>
                                            <div class="flex-1 bg-[#A24DFF] rounded-t-lg h-16 animate-bounce-in" style="animation-delay: 0.5s;"></div>
                                        </div>
                                        <p class="text-xs text-gray-600 font-semibold">Sales Trend</p>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-center h-24">
                                            <div class="w-20 h-20 rounded-full border-8 border-green-200 border-t-green-500 animate-rotate-slow"></div>
                                        </div>
                                        <p class="text-xs text-gray-600 font-semibold">Inventory</p>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="grid grid-cols-3 gap-3 pt-4">
                                    <div class="bg-[#F3E8FF] rounded-lg p-3">
                                        <div class="text-2xl font-black text-[#5E17EB]">$48K</div>
                                        <p class="text-xs text-gray-600 mt-1">Revenue</p>
                                    </div>
                                    <div class="bg-[#FFE8E8] rounded-lg p-3">
                                        <div class="text-2xl font-black text-[#FF3131]">234</div>
                                        <p class="text-xs text-gray-600 mt-1">Orders</p>
                                    </div>
                                    <div class="bg-[#E6D6FF] rounded-lg p-3">
                                        <div class="text-2xl font-black text-[#A24DFF]">89%</div>
                                        <p class="text-xs text-gray-600 mt-1">Growth</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES SECTION ===== -->
    <section id="features" class="py-20 sm:py-32 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#F3E8FF] rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>

        <div class="relative mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in-down">
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4">Powerful Features</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Everything you need to run your retail business efficiently</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="feature-card stagger-item">
                    <div class="icon gradient-primary text-white">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Point of Sale</h3>
                    <p class="text-gray-600">Fast, intuitive POS system for quick checkouts and accurate invoicing.</p>
                    <div class="mt-4 text-[#5E17EB] font-semibold flex items-center gap-2 hover:gap-3 transition">
                        Learn more <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card stagger-item">
                    <div class="icon gradient-secondary text-white">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Inventory Management</h3>
                    <p class="text-gray-600">Real-time stock tracking with barcode scanning and automatic alerts.</p>
                    <div class="mt-4 text-[#5E17EB] font-semibold flex items-center gap-2 hover:gap-3 transition">
                        Learn more <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card stagger-item">
                    <div class="icon gradient-tertiary text-white">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics & Reports</h3>
                    <p class="text-gray-600">Deep insights into sales, inventory, and financial performance.</p>
                    <div class="mt-4 text-[#5E17EB] font-semibold flex items-center gap-2 hover:gap-3 transition">
                        Learn more <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card stagger-item">
                    <div class="icon gradient-quaternary text-white">
                        <i class="fas fa-people-group"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">HR & Payroll</h3>
                    <p class="text-gray-600">Complete employee management and automated payroll processing.</p>
                    <div class="mt-4 text-[#5E17EB] font-semibold flex items-center gap-2 hover:gap-3 transition">
                        Learn more <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card stagger-item">
                    <div class="icon gradient-primary text-white">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Payment Gateway</h3>
                    <p class="text-gray-600">Secure payments with multiple payment method integration.</p>
                    <div class="mt-4 text-[#5E17EB] font-semibold flex items-center gap-2 hover:gap-3 transition">
                        Learn more <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card stagger-item">
                    <div class="icon gradient-secondary text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Customer Management</h3>
                    <p class="text-gray-600">Build customer profiles and track purchase history easily.</p>
                    <div class="mt-4 text-[#5E17EB] font-semibold flex items-center gap-2 hover:gap-3 transition">
                        Learn more <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </div>

                <!-- Feature 7 -->
                <div class="feature-card stagger-item">
                    <div class="icon gradient-tertiary text-white">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Supplier Management</h3>
                    <p class="text-gray-600">Streamline procurement with supplier tracking and orders.</p>
                    <div class="mt-4 text-[#5E17EB] font-semibold flex items-center gap-2 hover:gap-3 transition">
                        Learn more <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </div>

                <!-- Feature 8 -->
                <div class="feature-card stagger-item">
                    <div class="icon gradient-quaternary text-white">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Security & Backups</h3>
                    <p class="text-gray-600">Enterprise-grade security with automated daily backups.</p>
                    <div class="mt-4 text-[#5E17EB] font-semibold flex items-center gap-2 hover:gap-3 transition">
                        Learn more <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MODULES SHOWCASE ===== -->
    <section class="py-20 sm:py-32 bg-gradient-to-r from-[#5E17EB] via-[#9A37FF] to-[#FF3131] text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full filter blur-2xl"></div>
            <div class="absolute bottom-0 right-0 w-40 h-40 bg-white rounded-full filter blur-2xl"></div>
        </div>

        <div class="relative mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in-down">
                <h2 class="text-4xl sm:text-5xl font-black mb-4">Core Modules</h2>
                <p class="text-lg opacity-90 max-w-2xl mx-auto">Complete suite of integrated modules for comprehensive business management</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Module 1 -->
                <div class="card-glow stagger-item bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white">
                    <div class="text-4xl mb-4">🛒</div>
                    <h3 class="text-xl font-bold mb-2">Sales & POS</h3>
                    <p class="text-sm opacity-90">Fast checkout, invoices, and sales tracking</p>
                </div>

                <!-- Module 2 -->
                <div class="card-glow stagger-item bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white">
                    <div class="text-4xl mb-4">📦</div>
                    <h3 class="text-xl font-bold mb-2">Inventory</h3>
                    <p class="text-sm opacity-90">Stock management and barcode scanning</p>
                </div>

                <!-- Module 3 -->
                <div class="card-glow stagger-item bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white">
                    <div class="text-4xl mb-4">🛍️</div>
                    <h3 class="text-xl font-bold mb-2">Purchase</h3>
                    <p class="text-sm opacity-90">Purchase orders and supplier management</p>
                </div>

                <!-- Module 4 -->
                <div class="card-glow stagger-item bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white">
                    <div class="text-4xl mb-4">💰</div>
                    <h3 class="text-xl font-bold mb-2">Accounting</h3>
                    <p class="text-sm opacity-90">Ledger, payments, and financial reports</p>
                </div>

                <!-- Module 5 -->
                <div class="card-glow stagger-item bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white">
                    <div class="text-4xl mb-4">👥</div>
                    <h3 class="text-xl font-bold mb-2">CRM</h3>
                    <p class="text-sm opacity-90">Customer profiles and relationship management</p>
                </div>

                <!-- Module 6 -->
                <div class="card-glow stagger-item bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white">
                    <div class="text-4xl mb-4">👨‍💼</div>
                    <h3 class="text-xl font-bold mb-2">HR & Payroll</h3>
                    <p class="text-sm opacity-90">Employee management and payroll processing</p>
                </div>

                <!-- Module 7 -->
                <div class="card-glow stagger-item bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-bold mb-2">Analytics</h3>
                    <p class="text-sm opacity-90">Detailed reports and business insights</p>
                </div>

                <!-- Module 8 -->
                <div class="card-glow stagger-item bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white">
                    <div class="text-4xl mb-4">🔗</div>
                    <h3 class="text-xl font-bold mb-2">Integration</h3>
                    <p class="text-sm opacity-90">API and third-party integrations</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRICING SECTION ===== -->
    <section id="pricing" class="py-20 sm:py-32 bg-white">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in-down">
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4">Simple Pricing</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Flexible plans for businesses of all sizes</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Starter Plan -->
                <div class="pricing-card stagger-item bg-white rounded-2xl border border-[#E9D7FF] overflow-hidden shadow-lg">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                        <p class="text-gray-600 mb-6">Perfect for small shops</p>
                        <div class="mb-6">
                            <span class="text-4xl font-black text-gray-900">$29</span>
                            <span class="text-gray-600">/month</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Up to 1 outlet
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                5 users
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Basic analytics
                            </li>
                            <li class="flex items-center gap-3 text-gray-400">
                                <i class="fas fa-times-circle"></i>
                                API access
                            </li>
                        </ul>
                        <button class="w-full py-3 border-2 border-[#5E17EB] text-[#5E17EB] rounded-xl font-bold btn-magic hover:bg-[#5E17EB] hover:text-white">Get Started</button>
                    </div>
                </div>

                <!-- Professional Plan -->
                <div class="pricing-card featured stagger-item bg-white rounded-2xl border-2 border-[#5E17EB] overflow-hidden shadow-2xl">
                    <div class="badge">Most Popular</div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Professional</h3>
                        <p class="text-gray-600 mb-6">For growing businesses</p>
                        <div class="mb-6">
                            <span class="text-4xl font-black gradient-text">$79</span>
                            <span class="text-gray-600">/month</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Up to 5 outlets
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                50 users
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Advanced analytics
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                API access
                            </li>
                        </ul>
                        <button class="w-full py-3 gradient-primary text-white rounded-xl font-bold btn-magic hover:shadow-lg">Start Free Trial</button>
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="pricing-card stagger-item bg-white rounded-2xl border border-[#E9D7FF] overflow-hidden shadow-lg">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                        <p class="text-gray-600 mb-6">For large organizations</p>
                        <div class="mb-6">
                            <span class="text-4xl font-black text-gray-900">Custom</span>
                            <span class="text-gray-600 text-base">contact us</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Unlimited outlets
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Unlimited users
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Custom analytics
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                24/7 support
                            </li>
                        </ul>
                        <button class="w-full py-3 border-2 border-[#5E17EB] text-[#5E17EB] rounded-xl font-bold btn-magic hover:bg-[#5E17EB] hover:text-white">Contact Sales</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TESTIMONIALS SECTION ===== -->
    <section id="testimonials" class="py-20 sm:py-32 bg-white">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in-down">
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4">What Our Customers Say</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Trusted by 1000+ retailers worldwide</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card blue stagger-item">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 mb-4 leading-relaxed">"DokanPro transformed how we manage our store. The POS system is incredibly fast and the inventory tracking is spot-on. Highly recommended!"</p>
                    <div class="flex items-center gap-4">
                        <div class="avatar bg-blue-200">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="Avatar">
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Fatema Khan</p>
                            <p class="text-sm text-gray-600">Store Owner, Dhaka</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card pink stagger-item">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 mb-4 leading-relaxed">"Best investment we made for our retail chain. The analytics dashboard gives us real-time insights into our business performance."</p>
                    <div class="flex items-center gap-4">
                        <div class="avatar bg-pink-200">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka" alt="Avatar">
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Karim Ahmed</p>
                            <p class="text-sm text-gray-600">Manager, Chittagong</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card cyan stagger-item">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 mb-4 leading-relaxed">"The customer support team is amazing. They helped us integrate everything within days. Our sales have increased 40% since implementation!"</p>
                    <div class="flex items-center gap-4">
                        <div class="avatar bg-cyan-200">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Luna" alt="Avatar">
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Sarah Islam</p>
                            <p class="text-sm text-gray-600">Founder, Sylhet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== WHY CHOOSE US ===== -->
    <section class="py-20 sm:py-32 bg-gradient-to-r from-white via-[#F3E8FF] to-[#FFE8E8]">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Image -->
                <div class="animate-slide-in-left">
                    <div class="relative">
                        <div class="absolute -inset-8 gradient-primary opacity-15 rounded-3xl blur-3xl"></div>
                        <div class="relative bg-white rounded-3xl p-8 shadow-2xl border border-[#E9D7FF] image-hover-zoom">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=500&fit=crop" alt="Team" class="rounded-2xl w-full">
                        </div>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="animate-slide-in-right">
                    <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-6">Why Choose DokanPro?</h2>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl gradient-primary text-white">
                                    <i class="fas fa-lightning-bolt"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Lightning Fast</h3>
                                <p class="text-gray-600">Optimized performance with zero downtime</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl gradient-secondary text-white">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Enterprise Security</h3>
                                <p class="text-gray-600">Bank-level encryption and data protection</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl gradient-tertiary text-white">
                                    <i class="fas fa-headset"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">24/7 Support</h3>
                                <p class="text-gray-600">Expert support available round the clock</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl gradient-quaternary text-white">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Mobile & Desktop</h3>
                                <p class="text-gray-600">Seamless experience on all devices</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl gradient-primary text-white">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Real-Time Sync</h3>
                                <p class="text-gray-600">Instant updates across all locations</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl gradient-secondary text-white">
                                    <i class="fas fa-puzzle-piece"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Easy Integration</h3>
                                <p class="text-gray-600">Connect with your existing tools instantly</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CALL TO ACTION ===== -->
    <section class="relative overflow-hidden py-20 sm:py-32">
        <div class="absolute inset-0 gradient-primary opacity-90"></div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-scale-in">
            <h2 class="text-4xl sm:text-5xl font-black text-white mb-6">Ready to Transform Your Business?</h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">Join 1000+ retailers who are already managing their business smarter with DokanPro</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('signup.index') }}" class="px-10 py-4 bg-white text-[#5E17EB] rounded-xl font-bold btn-magic shadow-xl hover:shadow-2xl text-lg">
                    <i class="fas fa-rocket mr-2"></i>Start Free Trial
                </a>
                <a href="{{ route('dashboard') }}" class="px-10 py-4 border-2 border-white text-white rounded-xl font-bold btn-magic hover:bg-white hover:text-[#5E17EB] text-lg transition">
                    <i class="fas fa-play-circle mr-2"></i>Watch Demo
                </a>
            </div>

            <p class="mt-8 text-white/80">✓ No credit card required  ✓ 14-day free trial  ✓ Cancel anytime</p>
        </div>
    </section>

    <!-- ===== FAQ SECTION ===== -->
    <section class="py-20 sm:py-32 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in-down">
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-600">Find answers to common questions</p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <details class="group border border-[#E9D7FF] rounded-xl p-6 cursor-pointer stagger-item hover:border-[#5E17EB] transition">
                    <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                        Is my data secure with DokanPro?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">Yes! We use enterprise-grade encryption (256-bit AES) and comply with international security standards including ISO 27001. All data is encrypted both in transit and at rest.</p>
                </details>

                <!-- FAQ 2 -->
                <details class="group border border-[#E9D7FF] rounded-xl p-6 cursor-pointer stagger-item hover:border-[#5E17EB] transition">
                    <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                        Can I migrate from my current ERP?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">Absolutely! Our migration team can help you transfer your data from any existing system. Most migrations are completed within 2-4 weeks with zero downtime.</p>
                </details>

                <!-- FAQ 3 -->
                <details class="group border border-[#E9D7FF] rounded-xl p-6 cursor-pointer stagger-item hover:border-[#5E17EB] transition">
                    <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                        What's your uptime guarantee?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">We guarantee 99.9% uptime with SLA backup. Our servers are distributed across multiple data centers ensuring maximum availability.</p>
                </details>

                <!-- FAQ 4 -->
                <details class="group border border-[#E9D7FF] rounded-xl p-6 cursor-pointer stagger-item hover:border-[#5E17EB] transition">
                    <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                        Do you offer training and onboarding?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">Yes! All plans include comprehensive onboarding and training. We provide video tutorials, documentation, and live training sessions for your team.</p>
                </details>

                <!-- FAQ 5 -->
                <details class="group border border-[#E9D7FF] rounded-xl p-6 cursor-pointer stagger-item hover:border-[#5E17EB] transition">
                    <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                        Can I customize the system for my needs?
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">Definitely! DokanPro is highly customizable. You can configure workflows, fields, reports, and more. For advanced customization, our development team can assist.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-gray-900 text-gray-300 py-16">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 gradient-primary rounded-lg flex items-center justify-center">
                            <span class="text-white font-black">D</span>
                        </div>
                        <span class="text-white font-black text-lg">DokanPro</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">The complete ERP solution for modern retail businesses worldwide.</p>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="text-white font-bold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-[#5E17EB] transition">Features</a></li>
                        <li><a href="#pricing" class="hover:text-[#5E17EB] transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-[#5E17EB] transition">Security</a></li>
                        <li><a href="#" class="hover:text-[#5E17EB] transition">Updates</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-white font-bold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-[#5E17EB] transition">About Us</a></li>
                        <li><a href="#" class="hover:text-[#5E17EB] transition">Blog</a></li>
                        <li><a href="#" class="hover:text-[#5E17EB] transition">Careers</a></li>
                        <li><a href="#" class="hover:text-[#5E17EB] transition">Contact</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-white font-bold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-[#5E17EB] transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-[#5E17EB] transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-[#5E17EB] transition">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Social & Copyright -->
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-400">&copy; 2024 DokanPro. All rights reserved.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-[#5E17EB] transition"><i class="fab fa-facebook-f text-lg"></i></a>
                    <a href="#" class="hover:text-[#5E17EB] transition"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" class="hover:text-[#5E17EB] transition"><i class="fab fa-linkedin-in text-lg"></i></a>
                    <a href="#" class="hover:text-[#5E17EB] transition"><i class="fab fa-instagram text-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>