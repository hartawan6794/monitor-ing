<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuthorizedServer;
use App\Models\AvailableDatabase;
use App\Models\PricingPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;

class SetupWizardController extends Controller
{
    public function index(Request $request)
    {
        $pricingPlans = PricingPlan::orderBy('order')->get();

        // Jika ada ?user_id, load data user untuk pre-fill Step 1
        $prefillUser = null;
        if ($request->filled('user_id')) {
            $prefillUser = User::with([
                'subscriptions' => fn ($q) => $q->where('status', 'active')->latest()->limit(1),
                'subscriptions.pricingPlan',
            ])->find($request->user_id);
        }

        return view('setup.wizard', compact('pricingPlans', 'prefillUser'));
    }

    /**
     * Admin page: hanya muat statistik aggregate (ringan).
     * Data tabel dimuat terpisah via DataTables AJAX (server-side).
     */
    public function registeredUsersPage()
    {
        // Hanya ambil aggregate — tidak load semua kolom user
        $totalUsers      = User::count();
        $subscribedUsers = User::whereHas('subscriptions', fn ($q) => $q->where('status', 'active'))->count();
        $pendingUsers    = User::where('provisioning_status', 'pending')->count();
        $noSubUsers      = User::whereDoesntHave('subscriptions', fn ($q) => $q->where('status', 'active'))->count();

        return view('setup.registered_users', compact('totalUsers', 'subscribedUsers', 'pendingUsers', 'noSubUsers'));
    }

    /**
     * Server-side DataTables endpoint untuk tabel User Terdaftar.
     * Mendukung search, sort, filter status, dan pagination otomatis.
     */
    public function getRegisteredUsersData(Request $request)
    {
        $query = User::with([
            'subscriptions' => fn ($q) => $q->where('status', 'active')->latest()->limit(1),
            'subscriptions.pricingPlan',
            'availableDatabases',
        ]);

        // Filter by provisioning_status jika ada
        if ($request->filled('prov_status') && $request->prov_status !== 'all') {
            $query->where('provisioning_status', $request->prov_status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('avatar', function ($user) {
                $colors = ['#6366f1','#10b981','#f59e0b','#ef4444','#22d3ee','#a855f7','#ec4899'];
                $color  = $colors[$user->id % count($colors)];
                $init   = strtoupper(substr($user->name, 0, 1));
                return "<div style='width:36px;height:36px;border-radius:50%;background:{$color};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.875rem;flex-shrink:0;'>{$init}</div>";
            })
            ->addColumn('user_info', function ($user) {
                $name     = e($user->name);
                $username = e($user->username ?? '—');
                return "<div class='font-semibold text-defaulttextcolor dark:text-white'>{$name}</div>
                        <div class='text-xs text-muted'>{$username}</div>";
            })
            ->addColumn('status_badge', function ($user) {
                $pStatus  = $user->provisioning_status;
                $sub      = $user->subscriptions->first();
                $hasSub   = (bool) $sub;
                $isActive = $hasSub && $sub->expires_at && \Carbon\Carbon::parse($sub->expires_at)->gte(now());

                if ($pStatus === 'provisioned' && $isActive)
                    return '<span class="badge-sub badge-active"><i class="ri-shield-check-line"></i> Aktif & Terhubung</span>';
                if ($pStatus === 'pending')
                    return '<span class="badge-sub badge-pending" style="animation:blink-badge 1.5s ease-in-out infinite;"><i class="ri-loader-3-line"></i> Menunggu Koneksi DB</span>';
                if ($hasSub && !$isActive)
                    return '<span class="badge-sub badge-expired"><i class="ri-time-line"></i> Kedaluwarsa</span>';
                return '<span class="badge-sub badge-nosub"><i class="ri-close-circle-line"></i> Belum Berlangganan</span>';
            })
            ->addColumn('plan_name', function ($user) {
                $sub = $user->subscriptions->first();
                return $sub?->pricingPlan?->name
                    ? "<span style='font-weight:600;'>{$sub->pricingPlan->name}</span>"
                    : '<span class="text-muted text-xs">—</span>';
            })
            ->addColumn('expiry', function ($user) {
                $sub = $user->subscriptions->first();
                if (!$sub?->expires_at) return '<span class="text-muted text-xs">—</span>';
                $date     = \Carbon\Carbon::parse($sub->expires_at);
                $isActive = $date->gte(now());
                $days     = (int) now()->diffInDays($date, false);
                $cls      = $isActive ? '' : 'color:#dc2626;';
                $remaining = $isActive ? "<div class='text-xs text-muted'>Sisa {$days} hari</div>" : '';
                return "<div style='font-size:.85rem;font-weight:500;{$cls}'>{$date->format('d M Y')}</div>{$remaining}";
            })
            ->addColumn('db_list', function ($user) {
                if ($user->availableDatabases->isEmpty())
                    return '<span class="text-muted text-xs">Belum Ada</span>';
                $html = '<div class="flex flex-wrap gap-1">';
                foreach ($user->availableDatabases->take(2) as $db)
                    $html .= '<span class="badge-db">' . e($db->db_name) . '</span>';
                $extra = $user->availableDatabases->count() - 2;
                if ($extra > 0) $html .= "<span class='text-xs text-muted'>+{$extra}</span>";
                $html .= '</div>';
                return $html;
            })
            ->addColumn('actions', function ($user) {
                $pStatus    = $user->provisioning_status;
                $setupUrl   = route('setup.wizard', ['user_id' => $user->id]);
                $dbUrl      = route('available_database.index');
                $statusUrl  = route('registered.users.provisioning', $user->id);

                // Tombol aksi utama
                if ($pStatus === 'pending') {
                    $mainBtn = "<a href='{$setupUrl}' class='ti-btn ti-btn-sm ti-btn-primary-full !rounded-lg !py-1 !px-2 !text-xs' title='Setup DB'><i class='ri-rocket-line mr-1'></i>Setup</a>";
                } elseif ($pStatus === 'provisioned') {
                    $mainBtn = "<a href='{$dbUrl}' class='ti-btn ti-btn-sm ti-btn-info !rounded-lg !py-1 !px-2 !text-xs' title='Kelola DB'><i class='ri-database-2-line mr-1'></i>Kelola DB</a>";
                } else {
                    $mainBtn = "<span class='text-muted' style='font-size:.8rem;'>—</span>";
                }

                // Status options
                $statuses = \App\Models\User::PROVISIONING_STATUSES;
                $options  = '';
                foreach ($statuses as $key => $meta) {
                    $checked = $key === $pStatus ? '<i class="ri-check-line ms-auto" style="color:#6366f1;"></i>' : '';
                    $bg      = $key === $pStatus ? 'rgba(99,102,241,.08)' : 'transparent';
                    $options .= "<button type='button' class='prov-status-option w-full text-left flex items-center gap-2'
                        data-user-id='{$user->id}' data-status='{$key}' data-url='{$statusUrl}'
                        style='padding:7px 10px;border-radius:8px;border:none;background:{$bg};cursor:pointer;width:100%;font-size:.82rem;font-weight:600;color:{$meta['color']};transition:background .15s;'>
                        <i class='{$meta['icon']}'></i> {$meta['label']} {$checked}
                    </button>";
                }

                return "
                <div class='flex items-center justify-center gap-2'>
                    {$mainBtn}
                    <div class='prov-dd-wrap' style='position:relative;display:inline-block;'>
                        <button type='button' class='prov-status-btn'
                            data-user-id='{$user->id}'
                            title='Ubah status provisioning'
                            style='width:28px;height:28px;border-radius:8px;border:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:center;cursor:pointer;'>
                            <i class='ri-settings-3-line' style='font-size:.85rem;color:#6366f1;'></i>
                        </button>
                        <div class='prov-dropdown' data-user-id='{$user->id}'
                            style='display:none;position:absolute;right:0;top:calc(100% + 4px);z-index:999;background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.12);padding:6px;min-width:195px;'>
                            <div style='font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;padding:4px 8px 6px;'>Ubah Status</div>
                            {$options}
                        </div>
                    </div>
                </div>";
            })
            ->rawColumns(['avatar','user_info','status_badge','plan_name','expiry','db_list','actions'])
            ->make(true);
    }

    /**
     * Update provisioning_status user — dipanggil via AJAX dari halaman User Terdaftar.
     */
    public function updateProvisioningStatus(Request $request, $userId)
    {
        $request->validate([
            'status' => ['required', 'in:unregistered,pending,provisioned'],
        ]);

        $user = User::findOrFail($userId);
        $oldStatus = $user->provisioning_status;
        $user->setProvisioningStatus($request->status);

        return response()->json([
            'status'  => 'success',
            'message' => "Status {$user->name} diubah dari <b>{$oldStatus}</b> → <b>{$request->status}</b>.",
            'new_status' => $request->status,
            'label'      => \App\Models\User::PROVISIONING_STATUSES[$request->status]['label'],
            'color'      => \App\Models\User::PROVISIONING_STATUSES[$request->status]['color'],
            'icon'       => \App\Models\User::PROVISIONING_STATUSES[$request->status]['icon'],
        ]);
    }

    /**
     * Return all registered users with their active subscription info.
     * Used by the manage.blade.php to populate the user dropdown.
     */
    public function getRegisteredUsers()
    {
        $users = User::with([
            'subscriptions' => fn ($q) => $q->where('status', 'active')->latest()->limit(1),
            'subscriptions.pricingPlan',
        ])->orderBy('name')->get()->map(function ($user) {
            $activeSub = $user->subscriptions->first();
            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'username'     => $user->username ?? $user->email,
                'email'        => $user->email,
                'has_sub'      => (bool) $activeSub,
                'plan_id'      => $activeSub?->pricing_plan_id,
                'plan_name'    => $activeSub?->pricingPlan?->name ?? null,
                'expires_at'   => $activeSub?->expires_at,
                'sub_status'   => $activeSub?->status,
            ];
        });

        return response()->json(['status' => 'success', 'data' => $users]);
    }

    /**
     * Return active subscription detail for a specific user.
     * Called via AJAX when admin selects a user in manage.blade.php.
     */
    public function getUserSubscription($userId)
    {
        $user = User::with([
            'subscriptions' => fn ($q) => $q->where('status', 'active')->latest()->limit(1),
            'subscriptions.pricingPlan',
        ])->findOrFail($userId);

        $sub = $user->subscriptions->first();

        return response()->json([
            'status'       => 'success',
            'has_sub'      => (bool) $sub,
            'plan_id'      => $sub?->pricing_plan_id,
            'plan_name'    => $sub?->pricingPlan?->name,
            'expires_at'   => $sub?->expires_at,
            'sub_status'   => $sub?->status,
        ]);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'message' => 'User berhasil dibuat!'
        ]);
    }

    public function storeServer(Request $request)
    {
        $validated = $request->validate([
            'server_name' => 'required|string|max:255',
            'ip_address' => 'required|string|max:45',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'port' => 'nullable|integer',
        ]);

        $server = AuthorizedServer::create([
            'server_name' => $validated['server_name'],
            'ip_address' => $validated['ip_address'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'port' => $validated['port'] ?? 3306,
            'is_active' => 1,
        ]);

        // Attempt to fetch databases immediately
        try {
            Config::set('database.connections.mysql_temp.driver', 'mysql');
            Config::set('database.connections.mysql_temp.host', $server->ip_address);
            Config::set('database.connections.mysql_temp.port', $server->port);
            Config::set('database.connections.mysql_temp.username', $server->username);
            Config::set('database.connections.mysql_temp.password', $server->password);
            Config::set('database.connections.mysql_temp.database', 'information_schema');
            Config::set('database.connections.mysql_temp.charset', 'utf8');
            Config::set('database.connections.mysql_temp.collation', 'utf8_general_ci');

            DB::purge('mysql_temp');
            $databases = DB::connection('mysql_temp')->select('SHOW DATABASES');

            $excludedDbs = ['information_schema', 'mysql', 'performance_schema', 'sys', 'phpmyadmin'];
            $dbList = [];

            foreach ($databases as $db) {
                $dbName = $db->Database ?? $db->{'Database'};
                if (!in_array($dbName, $excludedDbs)) {
                    $dbList[] = $dbName;
                }
            }

            return response()->json([
                'status' => 'success',
                'server_id' => $server->id,
                'databases' => $dbList,
                'message' => 'Server berhasil ditambahkan dan koneksi sukses!'
            ]);

        } catch (\Exception $e) {
            // Rollback server creation if connection fails
            $server->delete();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terkoneksi ke server. Pastikan IP dan Kredensial benar. Detail: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeDatabase(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'server_id' => 'required|exists:authorized_servers,id',
            'db_name' => 'required|string|max:255',
            'package_type' => 'required|string',
            'description' => 'nullable|string',
            'expired_at' => 'nullable|date',
        ]);

        // Check if DB already assigned to this server
        $existingDb = AvailableDatabase::where('server_id', $validated['server_id'])
            ->where('db_name', $validated['db_name'])
            ->first();

        if ($existingDb) {
             return response()->json([
                'status' => 'error',
                'message' => 'Database ini sudah terdaftar di sistem!'
            ], 422);
        }

        $packageType = 'basic';
        if ($validated['package_type']) {
            $plan = PricingPlan::find($validated['package_type']);
            if ($plan) {
                $packageType = strtolower($plan->name);
            }
        }
        
        $validatedDb = $validated;
        $validatedDb['package_type'] = $packageType;
        
        AvailableDatabase::create($validatedDb);

        // Update or Create Subscription for the user
        if (!empty($validated['expired_at'])) {
            $subscription = Subscription::where('user_id', $validated['user_id'])->where('status', 'active')->first();
            
            if ($subscription) {
                $subscription->update([
                    'pricing_plan_id' => $validated['package_type'],
                    'expires_at' => $validated['expired_at'],
                ]);
            } else {
                Subscription::create([
                    'user_id' => $validated['user_id'],
                    'pricing_plan_id' => $validated['package_type'],
                    'starts_at' => now()->toDateString(),
                    'expires_at' => $validated['expired_at'],
                    'status' => 'active',
                    'notes' => 'Otomatis dibuat dari Setup Wizard',
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Setup Klien Selesai! Database berhasil dikaitkan ke user.'
        ]);
    }
}
