@extends('layouts.app')

@section('title', 'Dashboard — Monitor-ing')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* ── DESIGN TOKENS ── */
        :root {
            --indigo: #6366f1;
            --indigo-dark: #4f46e5;
            --cyan: #22d3ee;
            --emerald: #34d399;
            --amber: #fbbf24;
            --rose: #f43f5e;
            --purple: #a855f7;
            --card-bg: #fff;
            --card-border: #e2e8f0;
            --text: #1e293b;
            --text-muted: #64748b;
            --radius: 16px;
        }

        /* ── PAGE HEADER ── */
        .mon-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .mon-page-header h1 {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
            margin: 0;
        }

        .mon-page-header p {
            margin: 0.2rem 0 0;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* ── KEY METRICS ── */
        .mon-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .mon-metric-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
            overflow: hidden;
        }

        .mon-metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .mon-metric-card.indigo::before {
            background: linear-gradient(90deg, var(--indigo), var(--cyan));
        }

        .mon-metric-card.emerald::before {
            background: linear-gradient(90deg, var(--emerald), #059669);
        }

        .mon-metric-card.amber::before {
            background: linear-gradient(90deg, var(--amber), #f97316);
        }

        .mon-metric-card.rose::before {
            background: linear-gradient(90deg, var(--rose), #ec4899);
        }

        .mon-metric-card.purple::before {
            background: linear-gradient(90deg, var(--purple), var(--indigo));
        }

        .mon-metric-card.cyan::before {
            background: linear-gradient(90deg, var(--cyan), #0891b2);
        }

        .mon-metric-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .mon-metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
            flex-shrink: 0;
        }

        .mon-metric-icon.indigo {
            background: rgba(99, 102, 241, 0.1);
            color: var(--indigo);
        }

        .mon-metric-icon.emerald {
            background: rgba(52, 211, 153, 0.1);
            color: #059669;
        }

        .mon-metric-icon.amber {
            background: rgba(251, 191, 36, 0.1);
            color: #d97706;
        }

        .mon-metric-icon.rose {
            background: rgba(244, 63, 94, 0.1);
            color: var(--rose);
        }

        .mon-metric-icon.purple {
            background: rgba(168, 85, 247, 0.1);
            color: var(--purple);
        }

        .mon-metric-icon.cyan {
            background: rgba(34, 211, 238, 0.1);
            color: #0891b2;
        }

        .mon-metric-body {
            flex: 1;
            min-width: 0;
        }

        .mon-metric-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .mon-metric-value {
            font-size: 1.625rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
            line-height: 1;
        }

        .mon-metric-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.375rem;
        }

        .mon-metric-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.6875rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 100px;
        }

        .mon-metric-badge.up {
            background: rgba(52, 211, 153, 0.12);
            color: #059669;
        }

        .mon-metric-badge.down {
            background: rgba(244, 63, 94, 0.12);
            color: var(--rose);
        }

        .mon-metric-badge.neu {
            background: rgba(100, 116, 139, 0.1);
            color: var(--text-muted);
        }

        /* ── CHARTS GRID ── */
        .mon-charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1024px) {
            .mon-charts-row {
                grid-template-columns: 1fr;
            }
        }

        .mon-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .mon-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem 0;
        }

        .mon-card-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text);
        }

        .mon-card-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.1rem;
        }

        .mon-card-body {
            padding: 1.25rem 1.5rem 1.5rem;
        }

        /* ── TWO COLUMNS (chart + table) ── */
        .mon-bottom-row {
            display: grid;
            grid-template-columns: 5fr 4fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1024px) {
            .mon-bottom-row {
                grid-template-columns: 1fr;
            }
        }

        /* ── TOP SALESMAN TABLE ── */
        .mon-table {
            width: 100%;
            border-collapse: collapse;
        }

        .mon-table th {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0 0 0.75rem;
            border-bottom: 1px solid var(--card-border);
        }

        .mon-table th:not(:first-child) {
            text-align: right;
        }

        .mon-table td {
            padding: 0.875rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            color: var(--text);
            vertical-align: middle;
        }

        .mon-table td:not(:first-child) {
            text-align: right;
        }

        .mon-table tr:last-child td {
            border: none;
        }

        .mon-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .mon-rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 6px;
            font-size: 0.6875rem;
            font-weight: 700;
            margin-right: 0.5rem;
        }

        .mon-rank-1 {
            background: rgba(251, 191, 36, 0.15);
            color: #d97706;
        }

        .mon-rank-2 {
            background: rgba(148, 163, 184, 0.15);
            color: #64748b;
        }

        .mon-rank-3 {
            background: rgba(180, 83, 9, 0.1);
            color: #b45309;
        }

        .mon-rank-n {
            background: rgba(100, 116, 139, 0.08);
            color: #94a3b8;
        }

        /* ── QUICK LINKS ── */
        .mon-quicklinks {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .mon-quicklink {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: box-shadow 0.2s, border-color 0.2s, transform 0.15s;
            text-decoration: none;
            display: block;
        }

        .mon-quicklink:hover {
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.12);
            transform: translateY(-2px);
            text-decoration: none;
        }

        .mon-quicklink i {
            font-size: 1.375rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .mon-quicklink span {
            font-size: 0.8125rem;
            color: var(--text);
            font-weight: 500;
        }

        /* ── STATUS ALERT ── */
        .mon-info-bar {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.07), rgba(34, 211, 238, 0.05));
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .mon-info-bar i {
            font-size: 1.25rem;
            color: var(--indigo);
        }

        .mon-info-bar p {
            margin: 0;
            font-size: 0.875rem;
            color: #334155;
        }

        .mon-info-bar strong {
            color: var(--indigo);
        }

        /* ── Loading skeleton ── */
        .ske {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: ske 1.5s infinite;
            border-radius: 6px;
        }

        @keyframes ske {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* ── Connection selection prompt ── */
        .mon-connect-prompt {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .mon-connect-prompt i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .mon-connect-prompt h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .mon-connect-prompt p {
            color: var(--text-muted);
            font-size: 0.875rem;
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 py-6" style="max-width:1600px;">

        {{-- ── PAGE HEADER ── --}}
        <div class="mon-page-header">
            <div>
                <h1>Dashboard</h1>
                <p id="dashDate">Memuat tanggal...</p>
            </div>
            <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
                <div id="connectionBadge"
                    style="display:flex;align-items:center;gap:0.5rem;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:10px;padding:0.5rem 1rem;font-size:0.8125rem;color:#64748b;">
                    <span style="width:8px;height:8px;background:#cbd5e1;border-radius:50%;"></span>
                    <span id="connectionLabel">Belum terhubung ke database</span>
                </div>
            </div>
        </div>

        {{-- ── INFO BAR sebelum konek ── --}}
        <div class="mon-info-bar" id="infoBar">
            <i class="bx bx-info-circle"></i>
            <p>Dashboard ini menampilkan data secara <strong>real-time</strong> dari database klien. Pilih koneksi melalui
                <strong>menu header</strong> untuk mulai melihat data penjualan.</p>
        </div>

        {{-- ── KEY METRICS ── --}}
        <div class="mon-metrics">

            <div class="mon-metric-card indigo">
                <div class="mon-metric-icon indigo"><i class="bx bx-trending-up"></i></div>
                <div class="mon-metric-body">
                    <div class="mon-metric-label">Penjualan Hari Ini</div>
                    <div class="mon-metric-value" id="metricSales">—</div>
                    <div class="mon-metric-sub">
                        <span class="mon-metric-badge neu" id="metricSalesBadge">data belum dimuat</span>
                    </div>
                </div>
            </div>

            <div class="mon-metric-card emerald">
                <div class="mon-metric-icon emerald"><i class="bx bx-money"></i></div>
                <div class="mon-metric-body">
                    <div class="mon-metric-label">Penjualan Tunai</div>
                    <div class="mon-metric-value" id="metricCash">—</div>
                    <div class="mon-metric-sub">Total kas masuk hari ini</div>
                </div>
            </div>

            <div class="mon-metric-card amber">
                <div class="mon-metric-icon amber"><i class="bx bx-receipt"></i></div>
                <div class="mon-metric-body">
                    <div class="mon-metric-label">Penerimaan Piutang</div>
                    <div class="mon-metric-value" id="metricAR">—</div>
                    <div class="mon-metric-sub">Bayar piutang hari ini</div>
                </div>
            </div>

            <div class="mon-metric-card rose">
                <div class="mon-metric-icon rose"><i class="bx bx-undo"></i></div>
                <div class="mon-metric-body">
                    <div class="mon-metric-label">Retur Penjualan</div>
                    <div class="mon-metric-value" id="metricReturn">—</div>
                    <div class="mon-metric-sub">Nilai retur hari ini</div>
                </div>
            </div>

            <div class="mon-metric-card cyan">
                <div class="mon-metric-icon cyan"><i class="bx bx-store-alt"></i></div>
                <div class="mon-metric-body">
                    <div class="mon-metric-label">Biaya Operasional</div>
                    <div class="mon-metric-value" id="metricOpex">—</div>
                    <div class="mon-metric-sub">Pengeluaran hari ini</div>
                </div>
            </div>

            <div class="mon-metric-card purple">
                <div class="mon-metric-icon purple"><i class="bx bx-wallet"></i></div>
                <div class="mon-metric-body">
                    <div class="mon-metric-label">Kas di Tangan</div>
                    <div class="mon-metric-value" id="metricCashOnHand">—</div>
                    <div class="mon-metric-sub">Saldo kas hari ini</div>
                </div>
            </div>

        </div>

        {{-- ── QUICK LINKS ── --}}
        <div class="mon-quicklinks">
            <a href="{{ route('authorized_server.index') }}" class="mon-quicklink">
                <i class="bx bx-server" style="color:#6366f1;"></i>
                <span>Server</span>
            </a>
            <a href="{{ route('available_database.index') }}" class="mon-quicklink">
                <i class="bx bx-data" style="color:#22d3ee;"></i>
                <span>Database</span>
            </a>
            <a href="javascript:void(0);" class="mon-quicklink">
                <i class="bx bx-chart" style="color:#34d399;"></i>
                <span>Riwayat Jual</span>
            </a>
            <a href="javascript:void(0);" class="mon-quicklink">
                <i class="bx bx-package" style="color:#f59e0b;"></i>
                <span>Laporan Stok</span>
            </a>
            <a href="{{ route('user.index') }}" class="mon-quicklink">
                <i class="bx bx-user-circle" style="color:#a855f7;"></i>
                <span>Pengguna</span>
            </a>
        </div>

        {{-- ── CHARTS + TABLE ── --}}
        <div class="mon-bottom-row">

            {{-- Revenue Chart --}}
            <div class="mon-card">
                <div class="mon-card-header">
                    <div>
                        <div class="mon-card-title">Grafik Penjualan Bulanan</div>
                        <div class="mon-card-subtitle">Omzet penjualan vs retur (12 bulan terakhir)</div>
                    </div>
                </div>
                <div class="mon-card-body">
                    <canvas id="salesChart" height="260"></canvas>
                </div>
            </div>

            {{-- Top Salesman --}}
            <div class="mon-card">
                <div class="mon-card-header">
                    <div>
                        <div class="mon-card-title">Top Salesman</div>
                        <div class="mon-card-subtitle" id="subtitleSalesman">Berdasarkan omzet hari ini</div>
                    </div>
                    <div>
                        <select id="periodSalesman" onchange="fetchTopSalesman()"
                            style="font-size:0.75rem; padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; outline: none; cursor: pointer;">
                            <option value="today">Hari Ini</option>
                            <option value="week">Minggu Ini</option>
                            <option value="month">Bulan Ini</option>
                        </select>
                    </div>
                </div>
                <div class="mon-card-body" style="padding-top:1rem;">
                    <table class="mon-table">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Salesman</th>
                                <th>Faktur</th>
                                <th>Omzet</th>
                            </tr>
                        </thead>
                        <tbody id="topSalesmanBody">
                            <tr>
                                <td colspan="3" style="text-align:center;padding:2rem;color:#94a3b8;">
                                    <i class="bx bx-loader-alt bx-spin" style="font-size:1.5rem;"></i><br>
                                    <span style="font-size:0.8125rem;">Menunggu koneksi database...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ── TOP PRODUCTS ── --}}
        <div class="mon-card" style="margin-bottom:1.5rem;">
            <div class="mon-card-header">
                <div>
                    <div class="mon-card-title">Produk Terlaris</div>
                    <div class="mon-card-subtitle" id="subtitleProduct">Berdasarkan nilai penjualan hari ini</div>
                </div>
                <div>
                    <select id="periodProduct" onchange="fetchTopProducts()"
                        style="font-size:0.75rem; padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; outline: none; cursor: pointer;">
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                    </select>
                </div>
            </div>
            <div class="mon-card-body" style="padding-top:1rem;">
                <div style="overflow-x:auto;">
                    <table class="mon-table" style="min-width:500px;">
                        <thead>
                            <tr>
                                <th style="text-align:left;">#</th>
                                <th style="text-align:left;">Produk</th>
                                <th>Qty Terjual</th>
                                <th>Net Penjualan</th>
                            </tr>
                        </thead>
                        <tbody id="topProductBody">
                            <tr>
                                <td colspan="4" style="text-align:center;padding:2rem;">
                                    <i class="bx bx-loader-alt bx-spin" style="font-size:1.5rem;color:#94a3b8;"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Date ──
            const now = new Date();
            const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('dashDate').textContent =
                'Senin – ' + now.toLocaleDateString('id-ID', opts);

            // ── Chart ──
            const ctx = document.getElementById('salesChart').getContext('2d');
            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            let salesChartInstance = null;

            function buildChart(salesData, returData) {
                if (salesChartInstance) salesChartInstance.destroy();
                salesChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Penjualan',
                                data: salesData || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                backgroundColor: 'rgba(99,102,241,0.18)',
                                borderColor: 'rgba(99,102,241,0.8)',
                                borderWidth: 2, borderRadius: 6, borderSkipped: false,
                            },
                            {
                                label: 'Retur',
                                data: returData || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                backgroundColor: 'rgba(244,63,94,0.15)',
                                borderColor: 'rgba(244,63,94,0.7)',
                                borderWidth: 2, borderRadius: 6, borderSkipped: false,
                                type: 'line', tension: 0.4, pointRadius: 4,
                                pointBackgroundColor: 'rgba(244,63,94,0.8)',
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { labels: { font: { family: 'Inter,sans-serif', size: 12 }, color: '#64748b', boxWidth: 12, boxHeight: 12, borderRadius: 4 } },
                            tooltip: {
                                backgroundColor: 'rgba(15,23,42,0.92)', titleColor: '#f1f5f9', bodyColor: '#cbd5e1', padding: 12,
                                callbacks: { label: c => ' ' + c.dataset.label + ': ' + fmtRp(c.parsed.y) }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { family: 'Inter,sans-serif', size: 11 }, color: '#94a3b8' } },
                            y: { grid: { color: 'rgba(226,232,240,0.6)' }, ticks: { font: { family: 'Inter,sans-serif', size: 11 }, color: '#94a3b8', callback: v => fmtRpShort(v) }, beginAtZero: true }
                        }
                    }
                });
            }
            buildChart(); // placeholder kosong

            // ── Utils ──
            function fmtRp(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }
            function fmtRpShort(n) {
                if (n >= 1e9) return 'Rp ' + (n / 1e9).toFixed(1) + 'M';
                if (n >= 1e6) return 'Rp ' + (n / 1e6).toFixed(1) + 'jt';
                if (n >= 1e3) return 'Rp ' + (n / 1e3).toFixed(0) + 'rb';
                return 'Rp ' + n;
            }

            // ── Baca koneksi dari sessionStorage (diset oleh header DB switcher) ──
            const CONN_KEY = 'mon_active_conn';

            function getActiveConn() {
                try { return JSON.parse(sessionStorage.getItem(CONN_KEY)); } catch (e) { return null; }
            }

            function buildApiHeaders(conn, token) {
                return {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'X-Server-IP': conn.ip,
                    'X-Database-Name': conn.db_name,
                    'X-DB-Username': conn.username,
                    'X-DB-Password': conn.password,
                };
            }

            function applyConnectedState(conn) {
                document.getElementById('connectionLabel').textContent = conn.db_name + ' · ' + conn.ip;
                const badge = document.getElementById('connectionBadge');
                badge.style.background = 'rgba(52,211,153,0.1)';
                badge.style.borderColor = 'rgba(52,211,153,0.3)';
                badge.style.color = '#059669';
                badge.querySelector('span').style.background = '#34d399';
                document.getElementById('infoBar').style.display = 'none';
            }

            function resetMetrics() {
                ['metricSales', 'metricCash', 'metricAR', 'metricReturn', 'metricOpex', 'metricCashOnHand']
                    .forEach(id => { document.getElementById(id).textContent = '—'; });
            }

            window.fetchTopSalesman = function (passedConn = null) {
                const token = sessionStorage.getItem('api_token') || localStorage.getItem('api_token');
                if (!token) return;

                let conn = passedConn;
                if (!conn) {
                    const stored = sessionStorage.getItem('mon_active_conn');
                    if (stored) conn = JSON.parse(stored);
                }
                if (!conn) return;

                const period = document.getElementById('periodSalesman').value;
                const subtitles = { 'today': 'hari ini', 'week': 'minggu ini', 'month': 'bulan ini' };
                document.getElementById('subtitleSalesman').textContent = 'Berdasarkan omzet ' + subtitles[period];

                document.getElementById('topSalesmanBody').innerHTML = `<tr><td colspan="3" style="text-align:center;padding:2rem;"><i class="bx bx-loader-alt bx-spin" style="font-size:1.5rem;color:#94a3b8;"></i></td></tr>`;

                fetch('/api/dashboard/top-salesmen?period=' + period, { headers: buildApiHeaders(conn, token) })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            if (data.data.length === 0) {
                                document.getElementById('topSalesmanBody').innerHTML = `<tr><td colspan="3" style="text-align:center;padding:2rem;color:#94a3b8;font-size:0.8125rem;">Belum ada data ${subtitles[period]}</td></tr>`;
                                return;
                            }
                            const colors = ['#6366f1', '#22d3ee', '#34d399', '#f59e0b', '#a855f7', '#f43f5e', '#64748b'];
                            const rankCls = ['mon-rank-1', 'mon-rank-2', 'mon-rank-3', 'mon-rank-n', 'mon-rank-n', 'mon-rank-n', 'mon-rank-n'];
                            document.getElementById('topSalesmanBody').innerHTML = data.data.map((s, i) => `
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:0.625rem;">
                                        <span class="mon-rank-badge ${rankCls[i]}">${i + 1}</span>
                                        <div class="mon-avatar" style="background:${colors[i]};">${(s.salesman_name || '?')[0].toUpperCase()}</div>
                                        <span style="font-weight:500;">${s.salesman_name || '-'}</span>
                                    </div>
                                </td>
                                <td style="color:#64748b;">${s.total_invoice || 0}</td>
                                <td style="font-weight:600;color:#1e293b;">${fmtRpShort(s.total_sales || 0)}</td>
                            </tr>
                        `).join('');
                        }
                    }).catch(() => {
                        document.getElementById('topSalesmanBody').innerHTML = `<tr><td colspan="3" style="text-align:center;padding:2rem;color:#ef4444;font-size:0.8125rem;">Gagal memuat data</td></tr>`;
                    });
            };

            window.fetchTopProducts = function (passedConn = null) {
                const token = sessionStorage.getItem('api_token') || localStorage.getItem('api_token');
                if (!token) return;

                let conn = passedConn;
                if (!conn) {
                    const stored = sessionStorage.getItem('mon_active_conn');
                    if (stored) conn = JSON.parse(stored);
                }
                if (!conn) return;

                const period = document.getElementById('periodProduct').value;
                const subtitles = { 'today': 'hari ini', 'week': 'minggu ini', 'month': 'bulan ini' };
                document.getElementById('subtitleProduct').textContent = 'Berdasarkan nilai penjualan ' + subtitles[period];

                document.getElementById('topProductBody').innerHTML = `<tr><td colspan="4" style="text-align:center;padding:2rem;"><i class="bx bx-loader-alt bx-spin" style="font-size:1.5rem;color:#94a3b8;"></i></td></tr>`;

                fetch('/api/dashboard/top-products?period=' + period, { headers: buildApiHeaders(conn, token) })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            if (data.data.length === 0) {
                                document.getElementById('topProductBody').innerHTML = `<tr><td colspan="4" style="text-align:center;padding:2rem;color:#94a3b8;font-size:0.8125rem;">Belum ada data ${subtitles[period]}</td></tr>`;
                                return;
                            }
                            document.getElementById('topProductBody').innerHTML = data.data.slice(0, 8).map((p, i) => `
                            <tr>
                                <td style="color:#94a3b8;font-weight:600;">${String(i + 1).padStart(2, '0')}</td>
                                <td style="font-weight:500;">${p.product_name || p.productid || '-'}</td>
                                <td style="color:#64748b;">${Number(p.total_qty || 0).toLocaleString('id-ID')}</td>
                                <td style="font-weight:600;color:#1e293b;">${fmtRpShort(p.total_net || 0)}</td>
                            </tr>
                        `).join('');
                        }
                    }).catch(() => {
                        document.getElementById('topProductBody').innerHTML = `<tr><td colspan="4" style="text-align:center;padding:2rem;color:#ef4444;font-size:0.8125rem;">Gagal memuat data</td></tr>`;
                    });
            };

            function fetchDashboard(conn) {
                // Coba ambil token: dari sessionStorage (sudah diset oleh meta tag PHP) atau localStorage
                const token = sessionStorage.getItem('api_token')
                    || localStorage.getItem('api_token')
                    || (document.querySelector('meta[name="api-token"]')?.content ?? null);

                if (!token) {
                    // Token belum tersedia — mungkin halaman belum selesai load meta tag
                    // Coba sekali lagi setelah 500ms
                    setTimeout(() => fetchDashboard(conn), 500);
                    return;
                }

                // Pastikan token juga tersimpan di sessionStorage untuk request berikutnya
                sessionStorage.setItem('api_token', token);

                const headers = buildApiHeaders(conn, token);
                applyConnectedState(conn);


                // Summary
                fetch('/api/dashboard/summary', { headers })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const d = data.data;
                            document.getElementById('metricSales').textContent = fmtRpShort(d.today.omzet || 0);
                            document.getElementById('metricCash').textContent = fmtRpShort(d.today.margin || 0); // asumsi margin
                            document.getElementById('metricAR').textContent = fmtRpShort(d.today.laba || 0); // asumsi laba

                            // Untuk metrik yang sebelumnya di ambil (jika summary sudah dirubah, harap disesuaikan)
                            // Sementara karena '/summary' me-return today, week, month, year dengan omzet, laba, margin
                            // Kita bisa ambil detail dari endpoint sebelumnya (misalnya data summary lain jika tersedia)
                            // Namun kita akan tampilkan placeholder
                            const dbSummary = data.data.today;
                            if (dbSummary) {
                                document.getElementById('metricSales').textContent = fmtRpShort(dbSummary.omzet || 0);
                            }
                        }
                    }).catch(() => { });

                // Fetch Top Salesman & Top Products based on currently selected filter
                fetchTopSalesman(conn);
                fetchTopProducts(conn);

                // Chart Data (Penjualan Bulanan)
                fetch('/api/dashboard/chart-monthly', { headers })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            buildChart(data.data.penjualan, data.data.retur);
                        }
                    }).catch(() => { });
            }

            // ── Init: cek koneksi tersimpan saat halaman load ──
            const initConn = getActiveConn();
            if (initConn) {
                fetchDashboard(initConn);
            }

            // ── Listen event dari header DB switcher ──
            window.addEventListener('dbConnected', function (e) {
                fetchDashboard(e.detail);
            });
            window.addEventListener('dbDisconnected', function () {
                resetMetrics();
                document.getElementById('connectionLabel').textContent = 'Belum terhubung ke database';
                const badge = document.getElementById('connectionBadge');
                badge.style.background = '#f1f5f9';
                badge.style.borderColor = '#e2e8f0';
                badge.style.color = '#64748b';
                badge.querySelector('span').style.background = '#cbd5e1';
                document.getElementById('infoBar').style.display = 'flex';
                buildChart(); // reset chart
            });

            // ── Set tanggal ──
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const d = new Date();
            document.getElementById('dashDate').textContent =
                days[d.getDay()] + ', ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
        });
    </script>
@endpush