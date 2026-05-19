<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashMo — Sistem Monitoring Penjualan</title>
    <meta name="description"
        content="Sistem monitoring penjualan real-time terintegrasi untuk tim salesman dan manajemen.">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5', // Indigo 600
                        'primary-light': '#818cf8', // Indigo 400
                        accent: '#22d3ee', // Cyan 400
                        darkbg: '#0B0F19', // Custom dark background
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Utilities for things Tailwind CDN doesn't cover natively out-of-the-box easily */
        body {
            background-color: #0B0F19;
            color: #f8fafc;
        }

        .glass-nav {
            background: rgba(11, 15, 25, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .text-gradient {
            background: linear-gradient(135deg, #818cf8, #22d3ee);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden selection:bg-primary/30 selection:text-white">

    <!-- NAVBAR -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#" class="flex items-center gap-3 group">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center transform group-hover:scale-105 transition-transform shadow-lg shadow-primary/20">
                            <img src="{{ asset('dist/assets/images/logo_dashmo.png') }}" class="w-6 h-6 object-contain"
                                alt="DashMo Logo">
                        </div>
                        <span class="font-bold text-xl tracking-tight text-white">DashMo</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-center space-x-8">
                        <a href="#fitur"
                            class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Fitur</a>
                        <a href="#statistik"
                            class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Statistik</a>
                        <a href="#pricing"
                            class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Harga</a>
                        <a href="{{ route('l5-swagger.default.api') }}" target="_blank"
                            class="text-slate-300 hover:text-white transition-colors text-sm font-medium">API Docs</a>
                        <a href="{{ route('login') }}"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-primary to-indigo-500 text-white font-medium text-sm hover-glow transform hover:-translate-y-0.5 transition-all shadow-lg shadow-primary/30">
                            Masuk Dashboard
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button (Optional implementation) -->
                <div class="md:hidden flex items-center">
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 rounded-lg bg-primary text-white font-medium text-sm">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Abstract Background -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-7xl pointer-events-none">
            <div
                class="absolute top-20 left-10 w-72 h-72 bg-primary/30 rounded-full mix-blend-screen filter blur-[100px] animate-blob">
            </div>
            <div
                class="absolute top-40 right-10 w-72 h-72 bg-accent/20 rounded-full mix-blend-screen filter blur-[100px] animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-1/2 w-72 h-72 bg-purple-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-blob animation-delay-4000">
            </div>
            <!-- Grid pattern -->
            <div class="absolute inset-0"
                style="background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left animate-fade-in-up">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full glass-card text-xs font-semibold text-primary-light mb-6 border-primary/20">
                        <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                        Sistem Monitoring Real-Time
                    </div>
                    <h1
                        class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-[1.1]">
                        Pantau Penjualan <br class="hidden lg:block">
                        <span class="text-gradient">Kapan Saja,</span> <br class="hidden lg:block">
                        Di Mana Saja
                    </h1>
                    <p class="text-lg text-slate-400 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Platform monitoring penjualan terintegrasi untuk tim salesman dan manajemen. Lihat data
                        real-time, riwayat transaksi, dan performa tim dalam satu dashboard premium.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}"
                            class="inline-flex justify-center items-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-primary to-indigo-500 text-white font-semibold hover-glow transform hover:-translate-y-1 transition-all shadow-xl shadow-primary/20">
                            Masuk Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                        <a href="{{ asset('downloads/dashmo-latest.apk') }}"
                            class="inline-flex justify-center items-center gap-2 px-8 py-4 rounded-xl glass-card text-white font-semibold hover:bg-white/10 transform hover:-translate-y-1 transition-all border border-accent/30 hover:border-accent shadow-lg shadow-accent/10">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Download APK
                        </a>
                    </div>
                    <p
                        class="mt-5 text-xs text-slate-500 max-w-md mx-auto lg:mx-0 text-center lg:text-left leading-relaxed">
                        <span class="text-accent/80 font-medium"><i class="ri-information-line"></i> Info
                            Instalasi:</span> Karena aplikasi ini bersifat internal (Enterprise), file APK belum
                        tersedia di PlayStore. Pastikan Anda mengaktifkan izin <strong>"Install dari Sumber Tidak
                            Dikenal"</strong> di pengaturan Android Anda.
                    </p>
                </div>

                <!-- Hero Mockup Visual -->
                <div class="hidden lg:block relative z-10 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div
                        class="glass-card p-6 rounded-3xl shadow-2xl shadow-black/50 border-white/10 relative transform rotate-y-12 perspective-1000 hover:rotate-0 transition-transform duration-700">
                        <!-- Mockup Header -->
                        <div class="flex items-center gap-2 mb-6 border-b border-white/5 pb-4">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="mx-auto text-xs font-medium text-slate-400">Dashboard Penjualan</div>
                        </div>
                        <!-- Mockup Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                                <p class="text-[10px] text-slate-400 mb-1 uppercase tracking-wider">Total Omzet</p>
                                <p class="text-xl font-bold text-emerald-400">Rp 1.2M</p>
                            </div>
                            <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                                <p class="text-[10px] text-slate-400 mb-1 uppercase tracking-wider">Tunai</p>
                                <p class="text-xl font-bold text-accent">Rp 850jt</p>
                            </div>
                            <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                                <p class="text-[10px] text-slate-400 mb-1 uppercase tracking-wider">Transaksi</p>
                                <p class="text-xl font-bold text-purple-400">3,492</p>
                            </div>
                        </div>
                        <!-- Mockup Chart -->
                        <div class="bg-white/5 rounded-xl p-5 border border-white/5 h-40 flex items-end gap-3">
                            <div class="w-full bg-gradient-to-t from-primary/80 to-primary/20 rounded-t-md hover:from-primary transition-colors"
                                style="height: 40%"></div>
                            <div class="w-full bg-gradient-to-t from-primary/80 to-primary/20 rounded-t-md hover:from-primary transition-colors"
                                style="height: 60%"></div>
                            <div class="w-full bg-gradient-to-t from-accent/80 to-accent/20 rounded-t-md hover:from-accent transition-colors"
                                style="height: 45%"></div>
                            <div class="w-full bg-gradient-to-t from-primary/80 to-primary/20 rounded-t-md hover:from-primary transition-colors"
                                style="height: 85%"></div>
                            <div class="w-full bg-gradient-to-t from-primary/80 to-primary/20 rounded-t-md hover:from-primary transition-colors"
                                style="height: 55%"></div>
                            <div class="w-full bg-gradient-to-t from-accent/80 to-accent/20 rounded-t-md hover:from-accent transition-colors"
                                style="height: 95%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <section id="statistik" class="py-20 border-y border-white/5 relative overflow-hidden bg-white/[0.02]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <p class="text-accent font-semibold tracking-wider text-sm uppercase mb-3">Statistik Performa</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Dipercaya Skala Enterprise</h2>
                <p class="text-slate-400">Data real-time yang memproses ribuan transaksi tanpa hambatan, memastikan
                    bisnis Anda tidak pernah kehilangan momen.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                <div
                    class="glass-card p-8 rounded-2xl text-center group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-16 h-16 mx-auto bg-primary/10 rounded-2xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition-transform duration-300">
                        📊</div>
                    <div class="text-4xl font-extrabold text-white mb-2 tracking-tight">10K+</div>
                    <div class="text-sm text-slate-400">Transaksi Harian</div>
                </div>
                <!-- Stat 2 -->
                <div
                    class="glass-card p-8 rounded-2xl text-center group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-16 h-16 mx-auto bg-accent/10 rounded-2xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition-transform duration-300">
                        ⚡</div>
                    <div class="text-4xl font-extrabold text-white mb-2 tracking-tight">&lt;1s</div>
                    <div class="text-sm text-slate-400">Waktu Sinkronisasi</div>
                </div>
                <!-- Stat 3 -->
                <div
                    class="glass-card p-8 rounded-2xl text-center group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-16 h-16 mx-auto bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition-transform duration-300">
                        👥</div>
                    <div class="text-4xl font-extrabold text-white mb-2 tracking-tight">99%</div>
                    <div class="text-sm text-slate-400">Uptime Server</div>
                </div>
                <!-- Stat 4 -->
                <div
                    class="glass-card p-8 rounded-2xl text-center group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-16 h-16 mx-auto bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition-transform duration-300">
                        🏢</div>
                    <div class="text-4xl font-extrabold text-white mb-2 tracking-tight">50+</div>
                    <div class="text-sm text-slate-400">Cabang Aktif</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="fitur" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <p class="text-accent font-semibold tracking-wider text-sm uppercase mb-3">Fitur Unggulan</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Kendali Penuh di Tangan Anda</h2>
                <p class="text-slate-400 text-lg">DashMo merangkum data kompleks menjadi visual yang mudah dipahami,
                    dirancang khusus untuk mobilitas tinggi.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="glass-card p-8 rounded-3xl hover:-translate-y-2 hover:border-primary/50 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-primary/20 to-transparent rounded-2xl flex items-center justify-center mb-6 border border-primary/20 group-hover:bg-primary/30 transition-colors">
                        <span class="text-2xl">📈</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Dashboard Harian</h3>
                    <p class="text-slate-400 leading-relaxed text-sm">Pantau total penjualan, retur, penerimaan piutang,
                        dan kas di tangan secara real-time dari satu layar yang rapi.</p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="glass-card p-8 rounded-3xl hover:-translate-y-2 hover:border-accent/50 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-accent/20 to-transparent rounded-2xl flex items-center justify-center mb-6 border border-accent/20 group-hover:bg-accent/30 transition-colors">
                        <span class="text-2xl">📋</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Riwayat Presisi</h3>
                    <p class="text-slate-400 leading-relaxed text-sm">Telusuri transaksi berdasarkan filter tanggal,
                        pelanggan, atau tipe pembayaran. Lacak setiap faktur tanpa terlewat.</p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="glass-card p-8 rounded-3xl hover:-translate-y-2 hover:border-purple-500/50 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500/20 to-transparent rounded-2xl flex items-center justify-center mb-6 border border-purple-500/20 group-hover:bg-purple-500/30 transition-colors">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Performa Salesman</h3>
                    <p class="text-slate-400 leading-relaxed text-sm">Bandingkan pencapaian omzet antar anggota tim.
                        Identifikasi salesman terbaik dan pacu produktivitas mereka.</p>
                </div>

                <!-- Feature 4 -->
                <div
                    class="glass-card p-8 rounded-3xl hover:-translate-y-2 hover:border-emerald-500/50 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-emerald-500/20 to-transparent rounded-2xl flex items-center justify-center mb-6 border border-emerald-500/20 group-hover:bg-emerald-500/30 transition-colors">
                        <span class="text-2xl">📦</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Monitoring Inventori</h3>
                    <p class="text-slate-400 leading-relaxed text-sm">Lacak pergerakan stok, terima barang, pindah
                        gudang, dan stok opname langsung dari perangkat mobile.</p>
                </div>

                <!-- Feature 5 -->
                <div
                    class="glass-card p-8 rounded-3xl hover:-translate-y-2 hover:border-yellow-500/50 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-yellow-500/20 to-transparent rounded-2xl flex items-center justify-center mb-6 border border-yellow-500/20 group-hover:bg-yellow-500/30 transition-colors">
                        <span class="text-2xl">🏆</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Top Produk Analisis</h3>
                    <p class="text-slate-400 leading-relaxed text-sm">Ketahui item apa yang paling laris setiap
                        bulannya. Optimalkan pengadaan barang berdasarkan data yang akurat.</p>
                </div>

                <!-- Feature 6 -->
                <div
                    class="glass-card p-8 rounded-3xl hover:-translate-y-2 hover:border-rose-500/50 transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-rose-500/20 to-transparent rounded-2xl flex items-center justify-center mb-6 border border-rose-500/20 group-hover:bg-rose-500/30 transition-colors">
                        <span class="text-2xl">🔒</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Keamanan Multi-Tenant</h3>
                    <p class="text-slate-400 leading-relaxed text-sm">Infrastruktur aman dengan integrasi Tailscale VPN
                        dan pemisahan database dinamis untuk setiap cabang ritel.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING SECTION -->
    <section id="pricing" class="py-24 relative overflow-hidden">
        <!-- Glow background for pricing -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/10 rounded-full mix-blend-screen filter blur-[120px] pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <p class="text-accent font-semibold tracking-wider text-sm uppercase mb-3">Paket Berlangganan</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Investasi Fleksibel untuk Bisnis</h2>
                <p class="text-slate-400">Pilih kapabilitas yang paling cocok dengan tahap pertumbuhan usaha Anda saat
                    ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch max-w-5xl mx-auto">
                @if(isset($pricing_plans) && count($pricing_plans) > 0)
                    @foreach($pricing_plans as $plan)
                        <div
                            class="glass-card rounded-[2rem] p-8 flex flex-col relative transition-all duration-300 hover:-translate-y-2 {{ $plan->is_featured ? 'border-primary shadow-2xl shadow-primary/20 lg:-mt-4 lg:mb-4 bg-slate-900/80' : 'hover:border-white/20' }}">

                            @if($plan->is_featured)
                                <div
                                    class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-primary to-accent text-white text-xs font-bold uppercase tracking-widest py-1.5 px-4 rounded-full shadow-lg">
                                    Paling Populer
                                </div>
                            @endif

                            <div class="mb-8">
                                <h3
                                    class="text-xl font-semibold mb-2 {{ $plan->is_featured ? 'text-primary-light' : 'text-white' }}">
                                    {{ $plan->name }}
                                </h3>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-extrabold text-white">
                                        <sup
                                            class="text-xl font-bold mr-1">Rp</sup>{{ is_numeric($plan->price) ? number_format($plan->price, 0, ',', '.') : $plan->price }}
                                    </span>
                                    <span class="text-sm text-slate-400 font-medium">{{ $plan->price_subtext }}</span>
                                </div>
                            </div>

                            <ul class="space-y-4 mb-8 flex-grow">
                                @foreach($plan->features as $feature)
                                    <li
                                        class="flex items-start gap-3 text-sm {{ $feature->is_highlighted ? 'text-white font-semibold' : 'text-slate-400' }}">
                                        <svg class="w-5 h-5 flex-shrink-0 {{ $feature->is_highlighted ? 'text-accent' : 'text-primary' }}"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>{{ $feature->name }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <a href="{{ route('my-subscription.checkout', $plan->id) }}"
                                class="w-full inline-block py-4 px-6 rounded-xl text-center font-semibold text-sm transition-all duration-300 {{ $plan->is_featured ? 'bg-primary text-white hover:bg-primary-light shadow-lg shadow-primary/30' : 'bg-white/10 text-white hover:bg-white/20' }}">
                                {{ Auth::check() ? ($plan->button_text ?? 'Berlangganan Sekarang') : 'Berlangganan Sekarang' }}
                            </a>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback if pricing_plans variable is not set (e.g. preview) -->
                    <div
                        class="glass-card rounded-[2rem] p-8 flex flex-col hover:-translate-y-2 transition-transform duration-300">
                        <h3 class="text-xl font-semibold mb-2 text-white">Starter</h3>
                        <div class="flex items-baseline gap-1 mb-8">
                            <span class="text-4xl font-extrabold text-white">Rp 150k</span>
                            <span class="text-sm text-slate-400">/ bulan</span>
                        </div>
                        <ul class="space-y-4 mb-8 flex-grow">
                            <li class="flex items-start gap-3 text-sm text-slate-400"><svg class="w-5 h-5 text-primary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> 1 Database Klien</li>
                            <li class="flex items-start gap-3 text-sm text-slate-400"><svg class="w-5 h-5 text-primary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> 5 User Salesman</li>
                            <li class="flex items-start gap-3 text-sm text-slate-400"><svg class="w-5 h-5 text-primary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> Basic Reporting</li>
                        </ul>
                        <a href="{{ route('login') }}"
                            class="w-full py-4 px-6 rounded-xl text-center font-semibold text-sm bg-white/10 text-white hover:bg-white/20 transition-colors">Pilih
                            Starter</a>
                    </div>

                    <div
                        class="glass-card rounded-[2rem] p-8 flex flex-col relative border-primary shadow-2xl shadow-primary/20 lg:-mt-4 lg:mb-4 bg-slate-900/80 hover:-translate-y-2 transition-transform duration-300">
                        <div
                            class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-primary to-accent text-white text-xs font-bold uppercase tracking-widest py-1.5 px-4 rounded-full shadow-lg">
                            Paling Populer</div>
                        <h3 class="text-xl font-semibold mb-2 text-primary-light">Pro</h3>
                        <div class="flex items-baseline gap-1 mb-8">
                            <span class="text-4xl font-extrabold text-white">Rp 350k</span>
                            <span class="text-sm text-slate-400">/ bulan</span>
                        </div>
                        <ul class="space-y-4 mb-8 flex-grow">
                            <li class="flex items-start gap-3 text-sm text-white font-semibold"><svg
                                    class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> Multi Database (Up to 5)</li>
                            <li class="flex items-start gap-3 text-sm text-white font-semibold"><svg
                                    class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> Unlimited User</li>
                            <li class="flex items-start gap-3 text-sm text-slate-400"><svg class="w-5 h-5 text-primary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> Advanced Analytics</li>
                        </ul>
                        <a href="{{ route('login') }}"
                            class="w-full py-4 px-6 rounded-xl text-center font-semibold text-sm bg-primary text-white hover:bg-primary-light shadow-lg shadow-primary/30 transition-all">Pilih
                            Pro</a>
                    </div>

                    <div
                        class="glass-card rounded-[2rem] p-8 flex flex-col hover:-translate-y-2 transition-transform duration-300">
                        <h3 class="text-xl font-semibold mb-2 text-white">Enterprise</h3>
                        <div class="flex items-baseline gap-1 mb-8">
                            <span class="text-4xl font-extrabold text-white">Custom</span>
                        </div>
                        <ul class="space-y-4 mb-8 flex-grow">
                            <li class="flex items-start gap-3 text-sm text-slate-400"><svg class="w-5 h-5 text-primary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> Unlimited Cabang</li>
                            <li class="flex items-start gap-3 text-sm text-slate-400"><svg class="w-5 h-5 text-primary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> Dedicated Tailscale Server</li>
                            <li class="flex items-start gap-3 text-sm text-slate-400"><svg class="w-5 h-5 text-primary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> 24/7 Priority Support</li>
                        </ul>
                        <a href="#"
                            class="w-full py-4 px-6 rounded-xl text-center font-semibold text-sm bg-white/10 text-white hover:bg-white/20 transition-colors">Hubungi
                            Sales</a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-white/5 bg-[#070a12] pt-16 pb-8 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center items-center gap-3 mb-6">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-lg">
                    <img src="{{ asset('dist/assets/images/logo_dashmo.png') }}" class="w-4 h-4 object-contain"
                        alt="DashMo Logo">
                </div>
                <span class="font-bold text-xl tracking-tight text-white">DashMo</span>
            </div>
            <p class="text-sm text-slate-500 mb-8 max-w-md mx-auto">
                Solusi manajemen dan monitoring penjualan multi-tenant terdepan untuk modernisasi operasi ritel Anda.
            </p>
            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} DashMo System. Hak Cipta Dilindungi.
                </p>
                <div class="flex gap-6 text-xs font-medium text-slate-400">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6285380988988?text=Halo%20tim%20DashMo,%20saya%20tertarik%20untuk%20berlangganan."
        target="_blank"
        class="fixed bottom-6 right-6 md:bottom-8 md:right-8 z-[100] group flex items-center justify-center w-14 h-14 bg-[#25D366] text-white rounded-full shadow-lg shadow-[#25D366]/40 hover:scale-110 hover:shadow-[#25D366]/60 transition-all duration-300 animate-bounce hover:animate-none">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M12.031 0C5.385 0 0 5.388 0 12.034c0 2.124.553 4.195 1.605 6.012L.15 24l6.111-1.603a11.967 11.967 0 0 0 5.77 1.485h.004c6.645 0 12.03-5.388 12.03-12.034 0-6.646-5.385-12.034-12.034-12.034zm0 21.844c-1.802 0-3.567-.484-5.114-1.401l-.367-.217-3.8.997 1.015-3.705-.238-.379a9.986 9.986 0 0 1-1.528-5.342c0-5.526 4.498-10.024 10.027-10.024 5.529 0 10.028 4.498 10.028 10.024 0 5.526-4.499 10.024-10.028 10.024zm5.503-7.518c-.301-.151-1.785-.881-2.062-.981-.277-.1-.479-.151-.68.151-.202.302-.781.981-.958 1.182-.176.202-.353.227-.654.076-.301-.151-1.275-.471-2.428-1.502-.897-.803-1.503-1.794-1.68-2.096-.176-.302-.019-.465.132-.616.136-.136.302-.353.453-.529.151-.176.202-.302.302-.504.1-.202.05-.379-.025-.529-.076-.151-.68-1.642-.932-2.247-.245-.589-.494-.509-.68-.518-.176-.008-.378-.008-.579-.008s-.529.076-.806.379c-.277.302-1.058 1.034-1.058 2.52s1.083 2.923 1.234 3.125c.151.202 2.13 3.25 5.158 4.557.72.311 1.281.497 1.722.636.723.228 1.381.196 1.902.119.584-.087 1.785-.73 2.036-1.436.252-.706.252-1.31.176-1.436-.076-.126-.277-.202-.579-.353z" />
        </svg>
        <span
            class="absolute right-16 bg-white text-slate-800 text-sm font-semibold px-4 py-2 rounded-xl shadow-lg border border-slate-100 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap hidden md:block">
            Chat dengan Sales
        </span>
    </a>

    <!-- INTERACTIVE SCRIPTS -->
    <script>
        // Smooth reveal animation on scroll
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.glass-card').forEach((el) => {
            el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
            observer.observe(el);
        });

        // Navbar blur intensity on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-lg', 'shadow-black/20');
                nav.style.background = 'rgba(11, 15, 25, 0.9)';
            } else {
                nav.classList.remove('shadow-lg', 'shadow-black/20');
                nav.style.background = 'rgba(11, 15, 25, 0.7)';
            }
        });
    </script>
</body>

</html>