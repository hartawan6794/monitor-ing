<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor-ing — Sistem Monitoring Penjualan</title>
    <meta name="description" content="Sistem monitoring penjualan real-time terintegrasi untuk tim salesman dan manajemen.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #22d3ee;
            --purple: #a855f7;
            --green: #34d399;
            --bg: #080b1a;
            --surface: rgba(255,255,255,0.05);
            --border: rgba(255,255,255,0.1);
            --text: #f1f5f9;
            --text-muted: #94a3b8;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            background: rgba(8,11,26,0.7);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            transition: background 0.3s;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            text-decoration: none;
        }
        .nav-brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-brand-icon svg { width: 20px; height: 20px; fill: #fff; }
        .nav-brand-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.01em;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
        }
        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 0.875rem;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }
        .nav-links a:hover { color: var(--text); background: var(--surface); }
        .btn-nav-login {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff !important;
            padding: 0.5rem 1.25rem !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 12px rgba(99,102,241,0.35);
            transition: transform 0.15s, box-shadow 0.2s !important;
        }
        .btn-nav-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99,102,241,0.5) !important;
            background: var(--primary) !important;
        }

        /* ── HERO ── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 7rem 2rem 4rem;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(99,102,241,0.18), transparent),
                        radial-gradient(ellipse 50% 40% at 90% 80%, rgba(34,211,238,0.12), transparent),
                        radial-gradient(ellipse 40% 50% at 10% 60%, rgba(168,85,247,0.1), transparent);
        }
        /* grid dots */
        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 36px 36px;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        @media (max-width: 768px) {
            .hero-content { grid-template-columns: 1fr; text-align: center; }
            .hero-visual { display: none; }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.3);
            border-radius: 100px;
            padding: 0.3rem 0.875rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #a5b4fc;
            letter-spacing: 0.03em;
            margin-bottom: 1.5rem;
        }
        .hero-badge::before { content: ''; width: 7px; height: 7px; background: var(--accent); border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }

        .hero h1 {
            font-size: clamp(2.25rem, 5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 1.25rem;
        }
        .hero h1 .highlight {
            background: linear-gradient(135deg, var(--primary-light, #818cf8), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero p {
            font-size: 1.0625rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 2.25rem;
            max-width: 480px;
        }
        .hero-cta {
            display: flex;
            gap: 0.875rem;
            flex-wrap: wrap;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9375rem;
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
            transition: transform 0.15s, box-shadow 0.2s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(99,102,241,0.55); }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: var(--surface);
            color: var(--text);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.9375rem;
            border: 1px solid var(--border);
            transition: background 0.2s, border-color 0.2s;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); }

        /* Hero visual: dashboard mockup */
        .hero-visual {
            position: relative;
        }
        .dashboard-mock {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 1.5rem;
            backdrop-filter: blur(8px);
            box-shadow: 0 32px 64px rgba(0,0,0,0.4);
        }
        .mock-header {
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .mock-dot { width: 10px; height: 10px; border-radius: 50%; }
        .mock-dot:nth-child(1) { background: #f87171; }
        .mock-dot:nth-child(2) { background: #fbbf24; }
        .mock-dot:nth-child(3) { background: #34d399; }
        .mock-title { margin-left: auto; font-size: 0.75rem; color: var(--text-muted); }

        .mock-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .mock-stat {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 0.875rem;
            border: 1px solid rgba(255,255,255,0.07);
        }
        .mock-stat-label { font-size: 0.6875rem; color: var(--text-muted); margin-bottom: 0.25rem; }
        .mock-stat-value { font-size: 1rem; font-weight: 700; color: var(--text); }
        .mock-stat-value.green { color: #34d399; }
        .mock-stat-value.blue  { color: #60a5fa; }
        .mock-stat-value.purple{ color: #c084fc; }

        .mock-chart {
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid rgba(255,255,255,0.07);
            height: 120px;
            display: flex;
            align-items: flex-end;
            gap: 6px;
        }
        .bar {
            flex: 1;
            border-radius: 4px 4px 0 0;
            background: linear-gradient(180deg, rgba(99,102,241,0.8), rgba(99,102,241,0.3));
            transition: height 0.3s;
        }
        .bar.accent { background: linear-gradient(180deg, rgba(34,211,238,0.7), rgba(34,211,238,0.2)); }

        /* ── STATS SECTION ── */
        .stats-section {
            padding: 5rem 2rem;
            background: rgba(255,255,255,0.02);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-badge {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: var(--accent);
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }
        .section-title {
            font-size: clamp(1.75rem, 3vw, 2.25rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
        }
        .section-desc {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 560px;
            line-height: 1.7;
        }
        .section-header { margin-bottom: 3rem; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.75rem;
            text-align: center;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99,102,241,0.4);
            box-shadow: 0 16px 40px rgba(99,102,241,0.15);
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.375rem;
        }
        .stat-icon.indigo { background: rgba(99,102,241,0.15); }
        .stat-icon.cyan   { background: rgba(34,211,238,0.15); }
        .stat-icon.purple { background: rgba(168,85,247,0.15); }
        .stat-icon.green  { background: rgba(52,211,153,0.15); }
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }
        .stat-desc { font-size: 0.8125rem; color: var(--text-muted); }

        /* ── FEATURES SECTION ── */
        .features-section { padding: 5rem 2rem; }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .feature-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .feature-card:hover { transform: translateY(-4px); border-color: rgba(99,102,241,0.3); box-shadow: 0 20px 48px rgba(0,0,0,0.3); }
        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.5rem;
        }
        .feature-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.625rem; }
        .feature-desc { font-size: 0.875rem; color: var(--text-muted); line-height: 1.65; }

        /* ── FOOTER ── */
        footer {
            padding: 3rem 2rem;
            border-top: 1px solid var(--border);
            text-align: center;
        }
        .footer-brand { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 1rem; }
        .footer-text { font-size: 0.8125rem; color: var(--text-muted); }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
        <a href="#" class="nav-brand">
            <div class="nav-brand-icon">
                <svg viewBox="0 0 24 24"><path d="M3 3h18v4H3V3zm0 6h8v4H3V9zm0 6h8v4H3v-4zm10-6h8v10h-8V9z"/></svg>
            </div>
            <span class="nav-brand-name">Monitor-ing</span>
        </a>
        <ul class="nav-links">
            <li><a href="#fitur">Fitur</a></li>
            <li><a href="#statistik">Statistik</a></li>
            <li><a href="{{ route('login') }}" class="btn-nav-login">Masuk</a></li>
        </ul>
    </nav>

    <!-- HERO -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">✦ Sistem Monitoring Real-Time</div>
                <h1>
                    Pantau Penjualan<br>
                    <span class="highlight">Kapan Saja,</span><br>
                    Di Mana Saja
                </h1>
                <p>Platform monitoring penjualan terintegrasi untuk tim salesman dan manajemen. Lihat data real-time, riwayat transaksi, dan performa per salesman dalam satu dashboard.</p>
                <div class="hero-cta">
                    <a href="{{ route('login') }}" class="btn-primary">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Masuk Sekarang
                    </a>
                    <a href="#fitur" class="btn-secondary">
                        Lihat Fitur
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </div>

            <!-- Dashboard Mockup -->
            <div class="hero-visual">
                <div class="dashboard-mock">
                    <div class="mock-header">
                        <div class="mock-dot"></div>
                        <div class="mock-dot"></div>
                        <div class="mock-dot"></div>
                        <span class="mock-title">Dashboard Penjualan — Hari Ini</span>
                    </div>
                    <div class="mock-stats">
                        <div class="mock-stat">
                            <div class="mock-stat-label">Total Penjualan</div>
                            <div class="mock-stat-value green">Rp 1,2M</div>
                        </div>
                        <div class="mock-stat">
                            <div class="mock-stat-label">Penjualan Tunai</div>
                            <div class="mock-stat-value blue">Rp 850rb</div>
                        </div>
                        <div class="mock-stat">
                            <div class="mock-stat-label">Kas di Tangan</div>
                            <div class="mock-stat-value purple">Rp 2,4M</div>
                        </div>
                    </div>
                    <div class="mock-chart">
                        <div class="bar" style="height:45%"></div>
                        <div class="bar" style="height:70%"></div>
                        <div class="bar accent" style="height:55%"></div>
                        <div class="bar" style="height:85%"></div>
                        <div class="bar" style="height:60%"></div>
                        <div class="bar accent" style="height:90%"></div>
                        <div class="bar" style="height:75%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="stats-section" id="statistik">
        <div class="container">
            <div class="section-header" style="text-align:center;">
                <div class="section-badge">Statistik</div>
                <h2 class="section-title">Dipercaya Tim Penjualan</h2>
                <p class="section-desc" style="margin:0 auto;">Data real-time yang membantu pengambilan keputusan lebih cepat dan akurat.</p>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon indigo">📊</div>
                    <div class="stat-number" style="color:#818cf8;">500+</div>
                    <div class="stat-desc">Transaksi Dipantau per Hari</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon cyan">⚡</div>
                    <div class="stat-number" style="color:#22d3ee;">Real-Time</div>
                    <div class="stat-desc">Data Update Langsung</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">👥</div>
                    <div class="stat-number" style="color:#c084fc;">20+</div>
                    <div class="stat-desc">Salesman Termonitoring</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">✅</div>
                    <div class="stat-number" style="color:#34d399;">99%</div>
                    <div class="stat-desc">Akurasi Data</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features-section" id="fitur">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Fitur Unggulan</div>
                <h2 class="section-title">Semua yang Anda Butuhkan</h2>
                <p class="section-desc">Dari dashboard ringkasan hingga riwayat transaksi detail — Monitor-ing menyediakan semua informasi dalam genggaman.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(99,102,241,0.15);">📈</div>
                    <h3 class="feature-title">Dashboard Penjualan Harian</h3>
                    <p class="feature-desc">Pantau total penjualan, return, penjualan tunai, penerimaan piutang, dan biaya operasional hari ini dalam satu layar.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(34,211,238,0.15);">📋</div>
                    <h3 class="feature-title">Riwayat Penjualan</h3>
                    <p class="feature-desc">Telusuri transaksi berdasarkan tanggal, customer, salesman, atau tipe pembayaran dengan filter yang fleksibel.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(168,85,247,0.15);">👤</div>
                    <h3 class="feature-title">Performa Salesman</h3>
                    <p class="feature-desc">Bandingkan performa setiap salesman berdasarkan omzet harian, mingguan, hingga tahunan dengan ranking otomatis.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(52,211,153,0.15);">📦</div>
                    <h3 class="feature-title">Monitoring Stok</h3>
                    <p class="feature-desc">Lacak pergerakan stok, laporan penyesuaian, dan peringatan stok menipis secara real-time.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(251,191,36,0.15);">🏆</div>
                    <h3 class="feature-title">Top Produk &amp; Salesman</h3>
                    <p class="feature-desc">Lihat produk terlaris dan salesman terbaik secara real-time untuk pengambilan keputusan yang lebih cepat.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(248,113,113,0.15);">🔒</div>
                    <h3 class="feature-title">Aman &amp; Multi-Database</h3>
                    <p class="feature-desc">Autentikasi Sanctum, koneksi database dinamis per cabang, serta enkripsi data untuk keamanan penuh.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-brand">
            <div class="nav-brand-icon" style="width:32px;height:32px;border-radius:9px;">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;" fill="#fff"><path d="M3 3h18v4H3V3zm0 6h8v4H3V9zm0 6h8v4H3v-4zm10-6h8v10h-8V9z"/></svg>
            </div>
            <span style="font-weight:700;font-size:1rem;">Monitor-ing</span>
        </div>
        <p class="footer-text">&copy; {{ date('Y') }} Monitor-ing System. All rights reserved.</p>
    </footer>

    <script>
        // Animate bars in mockup on load
        document.querySelectorAll('.bar').forEach((bar, i) => {
            const h = bar.style.height;
            bar.style.height = '5%';
            setTimeout(() => { bar.style.height = h; bar.style.transition = 'height 0.6s cubic-bezier(0.16,1,0.3,1)'; }, 300 + i * 80);
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            nav.style.background = window.scrollY > 50
                ? 'rgba(8,11,26,0.95)' : 'rgba(8,11,26,0.7)';
        });
    </script>
</body>
</html>
