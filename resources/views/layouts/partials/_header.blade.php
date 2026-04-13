@php
    use App\Models\AuthorizedServer;
    try {
        $dbServers = AuthorizedServer::with(['availableDatabases' => function ($q) {
            $q->whereNull('deleted_at')->orderBy('db_name');
        }])
        ->where('is_active', 1)
        ->orderBy('server_name')
        ->get();
    } catch (\Exception $e) {
        $dbServers = collect();
    }
@endphp

<nav class="main-header !h-[3.75rem]" aria-label="Global">
    <div class="main-header-container ps-[0.725rem] pe-[1rem]">

        <div class="header-content-left">
            <div class="header-element md:px-[0.325rem] !items-center">
                <a aria-label="Hide Sidebar"
                    class="sidemenu-toggle animated-arrow hor-toggle horizontal-navtoggle inline-flex items-center"
                    href="javascript:void(0);"><span></span></a>
            </div>
        </div>

        <div class="header-content-right">

            {{-- ════ DATABASE CONNECTION SWITCHER TRIGGER ════ --}}
            <div class="header-element !items-center px-2 md:px-3">
                <button id="dbSwitcherBtn" type="button" onclick="openDbSwitcher()"
                    style="display:flex;align-items:center;gap:8px;background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.25);border-radius:10px;padding:6px 12px;cursor:pointer;transition:all 0.2s;max-width:280px;"
                    onmouseenter="this.style.background='rgba(99,102,241,0.14)'"
                    onmouseleave="this.style.background='rgba(99,102,241,0.08)'"
                >
                    <span id="dbStatusDot" style="width:8px;height:8px;border-radius:50%;background:#cbd5e1;flex-shrink:0;transition:background 0.3s;"></span>
                    <div style="display:flex;flex-direction:column;min-width:0;text-align:left;">
                        <span id="dbStatusServer" style="font-size:0.65rem;color:#94a3b8;line-height:1;display:none;"></span>
                        <span id="dbStatusLabel" style="font-size:0.8rem;font-weight:600;color:#6366f1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;line-height:1.3;">Pilih Koneksi</span>
                    </div>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-left:2px;"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
            </div>

            <!-- Dark / Light Mode -->
            <div class="header-element header-theme-mode hidden !items-center sm:block !py-[1rem] md:!px-[0.65rem] px-2">
                <a aria-label="anchor"
                    class="hs-dark-mode-active:hidden flex hs-dark-mode group flex-shrink-0 justify-center items-center gap-2 rounded-full font-medium transition-all text-xs dark:bg-bgdark dark:hover:bg-black/20 dark:text-white/50 dark:hover:text-white"
                    href="javascript:void(0);" data-hs-theme-click-value="dark">
                    <i class="bx bx-moon header-link-icon"></i>
                </a>
                <a aria-label="anchor"
                    class="hs-dark-mode-active:flex hidden hs-dark-mode group flex-shrink-0 justify-center items-center gap-2 rounded-full font-medium text-defaulttextcolor transition-all text-xs dark:bg-bodybg dark:bg-bgdark dark:hover:bg-black/20 dark:text-white/50 dark:hover:text-white"
                    href="javascript:void(0);" data-hs-theme-click-value="light">
                    <i class="bx bx-sun header-link-icon"></i>
                </a>
            </div>

            <!-- Profile -->
            <div class="header-element md:!px-[0.65rem] px-2 hs-dropdown !items-center ti-dropdown [--placement:bottom-left]">
                <button id="dropdown-profile" type="button"
                    class="hs-dropdown-toggle ti-dropdown-toggle !gap-2 !p-0 flex-shrink-0 sm:me-2 me-0 !rounded-full !shadow-none text-xs align-middle !border-0">
                    <img class="inline-block rounded-full" src="{{ asset(Auth::user()->photo ?? '') }}" width="32" height="32" alt="User">
                </button>
                <div class="md:block hidden dropdown-profile">
                    <p class="font-semibold mb-0 leading-none text-[#536485] text-[0.813rem]">{{ Auth::user()->name ?? '' }}</p>
                </div>
                <div class="hs-dropdown-menu ti-dropdown-menu !-mt-3 border-0 w-[11rem] !p-0 border-defaultborder hidden main-header-dropdown pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                    aria-labelledby="dropdown-profile">
                    <ul class="text-defaulttextcolor font-medium dark:text-white/50">
                        <li>
                            <a class="w-full ti-dropdown-item !text-[0.8125rem] !gap-x-0 !p-[0.65rem] !inline-flex" href="javascript:void(0);">
                                <i class="ti ti-user-circle text-[1.125rem] me-2 opacity-[0.7]"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="w-full ti-dropdown-item !text-[0.8125rem] !p-[0.65rem] !gap-x-0 !inline-flex"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                <i class="ti ti-logout text-[1.125rem] me-2 opacity-[0.7]"></i>Log Out
                            </a>
                            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display:none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════
     DB SWITCHER OVERLAY
═══════════════════════════════════════════ --}}
<div id="dbOverlay" onclick="closeDbSwitcher()"
    style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);backdrop-filter:blur(3px);z-index:9998;"></div>

{{-- ═══════════════════════════════════════════
     DB SWITCHER PANEL (slide dari kanan)
═══════════════════════════════════════════ --}}
<aside id="dbPanel"
    style="display:none;position:fixed;top:0;right:0;bottom:0;width:420px;max-width:95vw;background:#fff;z-index:9999;
           box-shadow:-20px 0 60px rgba(0,0,0,0.15);flex-direction:column;
           font-family:'Inter',system-ui,sans-serif;
           transform:translateX(100%);transition:transform 0.32s cubic-bezier(0.16,1,0.3,1);">

    {{-- ── Panel Header (selalu tampil) ── --}}
    <div id="panelHeader"
        style="padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:0.75rem;">
        {{-- Back button (hanya muncul di step 2) --}}
        <button id="btnBack" onclick="goToStep(1)"
            style="display:none;border:none;background:none;cursor:pointer;padding:4px;border-radius:8px;color:#64748b;flex-shrink:0;"
            onmouseenter="this.style.background='#f1f5f9'" onmouseleave="this.style.background='none'" title="Kembali ke daftar server">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div style="flex:1;min-width:0;">
            <div style="font-size:0.9375rem;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:0.5rem;">
                <span id="panelIconBg" style="width:28px;height:28px;background:linear-gradient(135deg,#6366f1,#22d3ee);border-radius:8px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </span>
                <span id="panelTitle">Pilih Server</span>
            </div>
            <div id="panelSubtitle" style="font-size:0.73rem;color:#94a3b8;margin-top:0.15rem;">Pilih server untuk melihat database yang tersedia</div>
        </div>
        <button onclick="closeDbSwitcher()"
            style="border:none;background:none;cursor:pointer;padding:6px;border-radius:8px;color:#94a3b8;flex-shrink:0;"
            onmouseenter="this.style.background='#f1f5f9'" onmouseleave="this.style.background='none'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    {{-- ── Koneksi Aktif (tampil di kedua step) ── --}}
    <div id="activeConnBar" style="display:none;margin:0.875rem 1.25rem 0;padding:0.75rem 1rem;background:rgba(52,211,153,0.08);border:1px solid rgba(52,211,153,0.3);border-radius:12px;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.5rem;min-width:0;">
                <span style="width:7px;height:7px;background:#34d399;border-radius:50%;flex-shrink:0;"></span>
                <div style="min-width:0;">
                    <div id="activeConnName" style="font-size:0.8125rem;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
                    <div id="activeConnMeta" style="font-size:0.7rem;color:#64748b;"></div>
                </div>
            </div>
            <button onclick="disconnectDb()"
                style="flex-shrink:0;border:1px solid rgba(244,63,94,0.3);background:none;color:#f43f5e;border-radius:8px;padding:3px 10px;font-size:0.72rem;cursor:pointer;font-family:inherit;font-weight:600;white-space:nowrap;"
                onmouseenter="this.style.background='rgba(244,63,94,0.08)'" onmouseleave="this.style.background='none'">
                Putuskan
            </button>
        </div>
    </div>

    {{-- ── Search Bar ── --}}
    <div style="padding:0.75rem 1.25rem 0;">
        <div style="position:relative;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input id="dbSearch" type="text" placeholder="Cari..."
                oninput="handleSearch(this.value)"
                style="width:100%;padding:7px 10px 7px 32px;border:1px solid #e2e8f0;border-radius:9px;font-size:0.8rem;color:#1e293b;outline:none;font-family:inherit;box-sizing:border-box;"
                onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'"
            >
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         STEP 1: DAFTAR SERVER
    ══════════════════════════════════════════ --}}
    <div id="step1" style="flex:1;overflow-y:auto;padding:0.75rem 1.25rem 1rem;">
        @if($dbServers->isEmpty())
            <div style="text-align:center;padding:3rem 1rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom:1rem;"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                <p style="color:#94a3b8;font-size:0.875rem;margin:0 0 0.5rem;">Belum ada server terdaftar</p>
                <a href="{{ route('authorized_server.index') }}" style="color:#6366f1;font-size:0.8125rem;font-weight:600;text-decoration:none;">+ Tambah Server</a>
            </div>
        @else
            @foreach($dbServers as $server)
            <div class="server-card" onclick="selectServer(this)"
                data-server-id="{{ $server->id }}"
                data-server-name="{{ $server->server_name }}"
                data-ip="{{ $server->ip_address }}"
                data-port="{{ $server->port ?? 3306 }}"
                data-username="{{ $server->username }}"
                data-password="{{ $server->password }}"
                data-db-count="{{ $server->availableDatabases->count() }}"
                style="display:flex;align-items:center;gap:0.875rem;padding:1rem 1.125rem;border:1px solid #f1f5f9;border-radius:14px;margin-bottom:0.625rem;cursor:pointer;transition:all 0.15s;background:#fff;">
                <div style="width:42px;height:42px;border-radius:12px;background:rgba(99,102,241,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.75" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $server->server_name }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;margin-top:2px;display:flex;align-items:center;gap:0.375rem;">
                        <span>{{ $server->ip_address }}:{{ $server->port ?? 3306 }}</span>
                        @if($server->availableDatabases->count() > 0)
                        <span style="width:3px;height:3px;background:#cbd5e1;border-radius:50%;"></span>
                        <span style="color:#6366f1;font-weight:500;">{{ $server->availableDatabases->count() }} database</span>
                        @else
                        <span style="width:3px;height:3px;background:#cbd5e1;border-radius:50%;"></span>
                        <span style="color:#f59e0b;font-weight:500;">Belum ada DB</span>
                        @endif
                    </div>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
            @endforeach
        @endif
    </div>

    {{-- ══════════════════════════════════════════
         STEP 2: DAFTAR DATABASE (per server terpilih)
    ══════════════════════════════════════════ --}}
    <div id="step2" style="display:none;flex:1;overflow-y:auto;padding:0.75rem 1.25rem 1rem;">

        {{-- Info server terpilih --}}
        <div id="selectedServerInfo"
            style="display:flex;align-items:center;gap:0.75rem;background:#f8fafc;border:1px solid #f1f5f9;border-radius:12px;padding:0.875rem 1rem;margin-bottom:0.875rem;">
            <div style="width:36px;height:36px;border-radius:10px;background:rgba(99,102,241,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <div>
                <div id="selServerName" style="font-size:0.8125rem;font-weight:600;color:#1e293b;"></div>
                <div id="selServerIP" style="font-size:0.72rem;color:#94a3b8;"></div>
            </div>
        </div>

        {{-- Daftar database injected via PHP --}}
        @foreach($dbServers as $server)
        <div class="db-group" data-server-id="{{ $server->id }}" style="display:none;">
            @forelse($server->availableDatabases as $db)
            <div class="db-card" onclick="selectDb(this)"
                data-server-id="{{ $server->id }}"
                data-db="{{ $db->db_name }}"
                data-server-name="{{ $server->server_name }}"
                data-ip="{{ $server->ip_address }}"
                data-port="{{ $server->port ?? 3306 }}"
                data-username="{{ $server->username }}"
                data-password="{{ $server->password }}"
                data-desc="{{ $db->description }}"
                data-search="{{ strtolower($db->db_name . ' ' . $db->description) }}"
                style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1rem;border:1px solid #f1f5f9;border-radius:12px;margin-bottom:0.5rem;cursor:pointer;transition:all 0.15s;background:#fff;">
                <div class="db-card-icon" style="width:38px;height:38px;border-radius:10px;background:rgba(99,102,241,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="db-card-name" style="font-size:0.8125rem;font-weight:600;color:#1e293b;">{{ $db->db_name }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;margin-top:1px;">{{ $db->description ?: 'Tidak ada deskripsi' }}</div>
                </div>
                <div class="db-card-status"></div>
            </div>
            @empty
            <div style="text-align:center;padding:2rem;color:#94a3b8;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:0.5rem;opacity:0.4;"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                <p style="font-size:0.8125rem;margin:0 0 0.5rem;">Belum ada database</p>
                <a href="{{ route('available_database.manage', $server->id) }}" style="color:#6366f1;font-size:0.8rem;font-weight:600;text-decoration:none;">+ Tambah Database</a>
            </div>
            @endforelse
        </div>
        @endforeach

        {{-- Empty search state --}}
        <div id="dbSearchEmpty" style="display:none;text-align:center;padding:2rem;color:#94a3b8;font-size:0.8125rem;">
            Tidak ada database yang cocok
        </div>

    </div>

    {{-- ── Footer status ── --}}
    <div style="padding:0.875rem 1.25rem;border-top:1px solid #f1f5f9;background:#fafafa;min-height:44px;display:flex;align-items:center;justify-content:center;">
        <div id="connectStatus" style="font-size:0.8rem;color:#64748b;text-align:center;"></div>
    </div>
</aside>

{{-- ─────────────────────────────────────────────── --}}
<style>
.server-card:hover {
    border-color: rgba(99,102,241,0.35) !important;
    background: rgba(99,102,241,0.02) !important;
    box-shadow: 0 4px 16px rgba(99,102,241,0.08);
    transform: translateX(2px);
}
.server-card.active-server {
    border-color: rgba(52,211,153,0.4) !important;
    background: rgba(52,211,153,0.04) !important;
}
.db-card:hover {
    border-color: rgba(99,102,241,0.35) !important;
    background: rgba(99,102,241,0.02) !important;
    transform: translateX(2px);
}
.db-card.active {
    border-color: rgba(52,211,153,0.5) !important;
    background: rgba(52,211,153,0.05) !important;
}
.db-card.active .db-card-icon { background: rgba(52,211,153,0.15) !important; }
.db-card.active .db-card-icon svg { stroke: #059669; }
.db-card.connecting { opacity: 0.6; cursor: wait; pointer-events: none; }
</style>

{{-- ─────────────────────────────────────────────── --}}
<script>
(function () {
    const CONN_KEY    = 'mon_active_conn';
    const SERVER_KEY  = 'mon_last_server'; // ingat server terakhir dipilih
    let activeConn    = null;
    let currentStep   = 1;
    let currentServer = null; // { id, name, ip, port, username, password }

    /* ── INIT ────────────────────────────────── */
    window.addEventListener('DOMContentLoaded', function () {
        try {
            const saved = sessionStorage.getItem(CONN_KEY);
            if (saved) {
                activeConn = JSON.parse(saved);
                applyHeaderBadge(activeConn);
                markActiveDb();
                window.dispatchEvent(new CustomEvent('dbConnected', { detail: activeConn }));
            }
        } catch(e) {}
    });

    /* ── BUKA PANEL ──────────────────────────── */
    window.openDbSwitcher = function () {
        document.getElementById('dbPanel').style.display   = 'flex';
        document.getElementById('dbOverlay').style.display = 'block';
        requestAnimationFrame(() => { document.getElementById('dbPanel').style.transform = 'translateX(0)'; });

        document.getElementById('dbSearch').value = '';

        // Jika ada koneksi aktif, langsung ke server-nya, else step 1
        const lastServerId = sessionStorage.getItem(SERVER_KEY);
        if (lastServerId) {
            const serverCard = document.querySelector(`.server-card[data-server-id="${lastServerId}"]`);
            if (serverCard) {
                goToStep(2, {
                    id:       serverCard.dataset.serverId,
                    name:     serverCard.dataset.serverName,
                    ip:       serverCard.dataset.ip,
                    port:     serverCard.dataset.port,
                    username: serverCard.dataset.username,
                    password: serverCard.dataset.password,
                }, false); // false = no animation scroll
            } else {
                goToStep(1);
            }
        } else {
            goToStep(1);
        }

        refreshActiveBar();
    };

    /* ── TUTUP PANEL ─────────────────────────── */
    window.closeDbSwitcher = function () {
        document.getElementById('dbPanel').style.transform = 'translateX(100%)';
        document.getElementById('dbOverlay').style.display = 'none';
        setTimeout(() => { document.getElementById('dbPanel').style.display = 'none'; }, 320);
        setStatus('');
    };

    /* ── NAVIGASI STEP ───────────────────────── */
    window.goToStep = function (step, server, animate) {
        currentStep = step;
        const s1 = document.getElementById('step1');
        const s2 = document.getElementById('step2');
        const back  = document.getElementById('btnBack');
        const title = document.getElementById('panelTitle');
        const sub   = document.getElementById('panelSubtitle');
        const search = document.getElementById('dbSearch');

        if (step === 1) {
            s1.style.display   = '';
            s2.style.display   = 'none';
            back.style.display = 'none';
            title.textContent  = 'Pilih Server';
            sub.textContent    = 'Pilih server untuk melihat database yang tersedia';
            search.placeholder = 'Cari server...';
            currentServer      = null;
            handleSearch('');
        } else {
            currentServer = server;
            sessionStorage.setItem(SERVER_KEY, server.id);

            s1.style.display   = 'none';
            s2.style.display   = '';
            back.style.display = '';
            title.textContent  = 'Pilih Database';
            sub.textContent    = server.name;
            search.placeholder = 'Cari database...';

            // Tampilkan group database server ini
            document.querySelectorAll('.db-group').forEach(g => g.style.display = 'none');
            const grp = document.querySelector(`.db-group[data-server-id="${server.id}"]`);
            if (grp) grp.style.display = '';

            // Info server
            document.getElementById('selServerName').textContent = server.name;
            document.getElementById('selServerIP').textContent   = server.ip + ':' + server.port;

            markActiveDb();
            handleSearch('');
        }
    };

    /* ── PILIH SERVER (step 1 → step 2) ─────── */
    window.selectServer = function (el) {
        goToStep(2, {
            id:       el.dataset.serverId,
            name:     el.dataset.serverName,
            ip:       el.dataset.ip,
            port:     el.dataset.port,
            username: el.dataset.username,
            password: el.dataset.password,
        });
    };

    /* ── PILIH DATABASE + TEST KONEKSI ──────── */
    window.selectDb = function (el) {
        if (el.classList.contains('connecting')) return;
        const dbName = el.dataset.db;
        el.classList.add('connecting');
        setStatus('⏳ Menguji koneksi ke ' + dbName + '...');
        setDbCardStatus(el, 'testing');

        const formData = new FormData();
        formData.append('server_id', el.dataset.serverId);
        formData.append('db_name',   dbName);
        formData.append('_token',    document.querySelector('meta[name=csrf-token]')?.content || '');

        fetch('/connections/test', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            el.classList.remove('connecting');
            if (data.status === 'success') {
                activeConn = {
                    server_id:   el.dataset.serverId,
                    server_name: el.dataset.serverName,
                    db_name:     dbName,
                    description: el.dataset.desc,
                    ip:          data.details.ip,
                    port:        data.details.port,
                    username:    data.details.username,
                    password:    data.details.password,
                };
                sessionStorage.setItem(CONN_KEY, JSON.stringify(activeConn));

                // Simpan token dari sessionStorage ke activeConn (sudah diset oleh PHP meta tag)
                const token = sessionStorage.getItem('api_token');
                if (token) activeConn._token = token;

                applyHeaderBadge(activeConn);
                markActiveDb();
                refreshActiveBar();
                setDbCardStatus(el, 'success');
                setStatus('✓ Terhubung ke ' + dbName, 'success');

                window.dispatchEvent(new CustomEvent('dbConnected', { detail: activeConn }));

                setTimeout(closeDbSwitcher, 900);
            } else {
                setDbCardStatus(el, 'error');
                setStatus('✕ ' + (data.message || 'Koneksi gagal'), 'error');
            }
        })
        .catch(() => {
            el.classList.remove('connecting');
            setDbCardStatus(el, 'error');
            setStatus('✕ Tidak dapat menghubungi server', 'error');
        });
    };

    /* ── DISCONNECT ──────────────────────────── */
    window.disconnectDb = function () {
        activeConn = null;
        sessionStorage.removeItem(CONN_KEY);
        sessionStorage.removeItem(SERVER_KEY);

        document.getElementById('dbStatusDot').style.background = '#cbd5e1';
        document.getElementById('dbStatusLabel').textContent    = 'Pilih Koneksi';
        document.getElementById('dbStatusServer').style.display = 'none';
        document.getElementById('activeConnBar').style.display  = 'none';

        markActiveDb();
        goToStep(1);
        setStatus('Koneksi diputus', 'muted');

        window.dispatchEvent(new CustomEvent('dbDisconnected'));
    };

    /* ── SEARCH ──────────────────────────────── */
    window.handleSearch = function (q) {
        const kw = q.toLowerCase().trim();
        if (currentStep === 1) {
            // Filter server cards
            document.querySelectorAll('.server-card').forEach(el => {
                const match = !kw ||
                    el.dataset.serverName.toLowerCase().includes(kw) ||
                    el.dataset.ip.includes(kw);
                el.style.display = match ? '' : 'none';
            });
        } else {
            // Filter db cards dalam group yang aktif
            let anyVisible = false;
            const grp = document.querySelector(`.db-group[data-server-id="${currentServer?.id}"]`);
            if (grp) {
                grp.querySelectorAll('.db-card').forEach(el => {
                    const match = !kw || el.dataset.search.includes(kw);
                    el.style.display = match ? '' : 'none';
                    if (match) anyVisible = true;
                });
            }
            const emptyEl = document.getElementById('dbSearchEmpty');
            if (emptyEl) emptyEl.style.display = (!anyVisible && kw) ? '' : 'none';
        }
    };

    /* ── HELPERS ─────────────────────────────── */
    function applyHeaderBadge(conn) {
        document.getElementById('dbStatusDot').style.background   = '#34d399';
        document.getElementById('dbStatusLabel').textContent       = conn.db_name;
        document.getElementById('dbStatusServer').textContent      = conn.server_name;
        document.getElementById('dbStatusServer').style.display    = '';
    }

    function refreshActiveBar() {
        const bar = document.getElementById('activeConnBar');
        if (!activeConn) { bar.style.display = 'none'; return; }
        bar.style.display = '';
        document.getElementById('activeConnName').textContent = activeConn.db_name;
        document.getElementById('activeConnMeta').textContent = activeConn.server_name + ' · ' + activeConn.ip + ':' + activeConn.port;
    }

    function markActiveDb() {
        document.querySelectorAll('.db-card').forEach(el => {
            el.classList.remove('active');
            const s = el.querySelector('.db-card-status');
            if (s) s.innerHTML = '';
        });
        if (!activeConn) return;
        document.querySelectorAll('.db-card').forEach(el => {
            if (el.dataset.serverId == activeConn.server_id && el.dataset.db === activeConn.db_name) {
                el.classList.add('active');
                const s = el.querySelector('.db-card-status');
                if (s) s.innerHTML = '<span style="font-size:0.68rem;font-weight:700;color:#059669;background:rgba(52,211,153,0.15);padding:2px 8px;border-radius:100px;">Aktif</span>';
            }
        });
        // Tandai server aktif juga
        document.querySelectorAll('.server-card').forEach(el => {
            el.classList.toggle('active-server', activeConn && el.dataset.serverId == activeConn.server_id);
        });
    }

    function setDbCardStatus(el, state) {
        const s = el.querySelector('.db-card-status');
        if (!s) return;
        if (state === 'testing') {
            s.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" style="animation:spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" opacity="0.2"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/></svg>';
        } else if (state === 'success') {
            s.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>';
        } else if (state === 'error') {
            s.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
        }
    }

    function setStatus(msg, type) {
        const el = document.getElementById('connectStatus');
        el.textContent = msg;
        el.style.color = type === 'error' ? '#f43f5e' : type === 'success' ? '#059669' : '#94a3b8';
    }

    /* ── CSS keyframes untuk spinner ── */
    if (!document.getElementById('dbSwitcherStyles')) {
        const style = document.createElement('style');
        style.id = 'dbSwitcherStyles';
        style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    }
})();
</script>
