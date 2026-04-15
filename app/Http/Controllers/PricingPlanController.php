<?php

namespace App\Http\Controllers;

use App\Http\Requests\PricingPlanRequest; // Ganti PricingPlan dengan nama model
use App\Models\PricingPlan; // Ganti PricingPlan dengan nama model
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PricingPlanController extends Controller
{
    public function index()
    {
        return view('pricing_plan.index'); // Ganti pricing_plan dengan nama model dalam huruf kecil
    }

    public function getData()
    {
        $data = PricingPlan::select('*'); // Mengambil semua kolom 
        //Model::select('isi_nama_kolom, yang, diingkan') jika mau costum
        return DataTables::of($data)
            ->addColumn('action', function ($item) {
                $editUrl = route('pricing_plan.edit', $item->id);
                $deleteUrl = route('pricing_plan.destroy', $item->id);

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
        return view('pricing_plan.create');
    }

    public function store(PricingPlanRequest $request)
    {
        $validated = $request->validated();
        $features = $validated['features'] ?? [];
        unset($validated['features']);

        $pricingPlan = PricingPlan::create($validated);

        foreach ($features as $index => $feature) {
            $pricingPlan->features()->create([
                'name' => $feature['name'],
                'is_highlighted' => isset($feature['is_highlighted']) ? $feature['is_highlighted'] : false,
                'order' => $index,
            ]);
        }

        Alert::success('Data PricingPlan Berhasil Disimpan');
        return redirect()->route('pricing_plan.index');
    }

    public function edit(string $id)
    {
        $pricing_plan = PricingPlan::with('features')->findOrFail($id);
        return view('pricing_plan.edit', ['pricing_plan' => $pricing_plan]);
    }

    public function update(PricingPlanRequest $request, PricingPlan $pricing_plan)
    {
        $validated = $request->validated();
        $features = $validated['features'] ?? [];
        unset($validated['features']);

        $pricing_plan->update($validated);

        // Simple sync: delete existing and recreate
        $pricing_plan->features()->delete();
        foreach ($features as $index => $feature) {
            $pricing_plan->features()->create([
                'name' => $feature['name'],
                'is_highlighted' => isset($feature['is_highlighted']) ? $feature['is_highlighted'] : false,
                'order' => $index,
            ]);
        }

        Alert::success('Data PricingPlan Berhasil Diperbarui');
        return redirect()->route('pricing_plan.index');
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
        $pricing_plan = PricingPlan::findOrFail($id);
        $pricing_plan->delete();
        Alert::success('Data PricingPlan Berhasil Dihapus');
        return redirect()->route('pricing_plan.index');
    }
}
