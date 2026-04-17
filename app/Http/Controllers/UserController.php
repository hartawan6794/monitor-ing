<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('user.index');
    }

    public function getData()
    {
        $users = User::withCount('availableDatabases')->select(['id', 'name', 'username', 'email', 'created_at']);
        return DataTables::of($users)
            ->addColumn('total_databases', function ($user) {
                if ($user->available_databases_count > 0) {
                    return '<span class="badge bg-primary/10 text-primary">' . $user->available_databases_count . ' Databases</span>';
                }
                return '<span class="badge bg-light text-muted">0 Databases</span>';
            })
            ->addColumn('action', function ($user) {
                $editUrl = route('user.edit', $user->id);  // Route untuk edit
                $deleteUrl = route('user.destroy', $user->id);  // Route untuk hapus

                return '
                <div class="flex gap-2">
                    <a href="' . $editUrl . '" class="ti-btn ti-btn-sm ti-btn-info !rounded-full"><i class="ri-edit-line"></i></a>
                    <form action="' . $deleteUrl . '" method="POST" style="display:inline;" id="delete-form-' . $user->id . '">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" class="ti-btn ti-btn-sm ti-btn-danger !rounded-full" onclick="confirmDelete(' . $user->id . ')">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                </div>
            
                <script>
                    function confirmDelete(userId) {
                        Swal.fire({
                            title: "Yakin ingin menghapus?",
                            text: "Jika iya maka data tidak dapat di kembalikan!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, hapus!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById("delete-form-" + userId).submit();
                            }
                        });
                    }
                </script>';
            
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('Y-m-d');
            })
            ->rawColumns(['total_databases', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        // Simpan data user
        User::create($validated);
        Alert::success('Data User Berhasil Disimpan');

        // Redirect dengan Toast Success
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */ public function update(UserRequest $request, User $user)
    {
        // Validasi sudah dilakukan oleh UserRequest

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        Alert::success('Data User Berhasil Dirubah');

        // Redirect dengan Toast Success
        return redirect()->route('user.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Alert::success('Data User Berhasil Dihapus');
        return redirect()->route('user.index');
    }
}
