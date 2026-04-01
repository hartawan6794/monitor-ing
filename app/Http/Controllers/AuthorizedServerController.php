<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizedServerRequest; // Ganti AuthorizedServer dengan nama model
use App\Models\AuthorizedServer; // Ganti AuthorizedServer dengan nama model
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AuthorizedServerController extends Controller
{
    public function index()
    {
        return view('authorized_server.index'); // Ganti authorized_server dengan nama model dalam huruf kecil
    }

    public function getData()
    {
        $data = AuthorizedServer::select('*'); // Mengambil semua kolom 
        //Model::select('isi_nama_kolom, yang, diingkan') jika mau costum
        return DataTables::of($data)
            ->addColumn('action', function ($item) {
                $editUrl = route('authorized_server.edit', $item->id);
                $deleteUrl = route('authorized_server.destroy', $item->id);

                return '
                <a href="' . $editUrl . '" class="ti-btn ti-btn-sm ti-btn-info !rounded-full"><i class="ri-edit-line"></i></a>
                <form action="' . $deleteUrl . '" method="POST" style="display:inline;" id="delete-form-' . $item->id . '">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="button" class="ti-btn ti-btn-sm ti-btn-danger !rounded-full" onclick="confirmDelete(' . $item->id . ')">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </form>
            
                <script>
                    function confirmDelete(itemId) {
                        Swal.fire({
                            title: "Yakin ingin menghapus?",
                            text: "Jika iya maka data tidak dapat dikembalikan!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, hapus!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById("delete-form-" + itemId).submit();
                            }
                        });
                    }
                </script>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at ? $item->created_at->format('Y-m-d') : '-';
            })
            ->editColumn('updated_at', function ($item) {
                return $item->updated_at ? $item->updated_at->format('Y-m-d') : '-';
            })
            ->make(true);
    }

    public function create()
    {
        return view('authorized_server.create');
    }

    public function store(AuthorizedServerRequest $request)
    {
        $validated = $request->validated();
        AuthorizedServer::create($validated);
        Alert::success('Data AuthorizedServer Berhasil Disimpan');
        return redirect()->route('authorized_server.index');
    }

    public function edit(string $id)
    {
        $authorized_server = AuthorizedServer::findOrFail($id);
        return view('authorized_server.edit', ['authorized_server' => $authorized_server]);
    }

    public function update(AuthorizedServerRequest $request, AuthorizedServer $authorized_server)
    {
        $validated = $request->validated();
        $authorized_server->update($validated);
        Alert::success('Data AuthorizedServer Berhasil Diperbarui');
        return redirect()->route('authorized_server.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $authorized_server = AuthorizedServer::findOrFail($id);
        $authorized_server->delete();
        Alert::success('Data AuthorizedServer Berhasil Dihapus');
        return redirect()->route('authorized_server.index');
    }
}
