@extends('layouts.app')

@section('title', 'Daftar PricingPlan')

@section('content')
    <div class="block justify-between page-header md:flex">
        <div>
            <h3 class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                PricingPlans</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-[0.813rem] ps-[0.5rem]">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary truncate" href="{{ route('home') }}">
                    Dashboards
                    <i class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] text-defaulttextcolor font-semibold hover:text-primary dark:text-[#8c9097] dark:text-white/50 " aria-current="page">
                PricingPlans
            </li>
        </ol>
    </div>

    <div class="grid grid-cols-12 gap-x-12">
        <div class="xxxl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="box-header justify-between">
                    <div class="box-title">
                        Daftar PricingPlan
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ route('pricing_plan.create')}}" class="ti-btn !py-1 !px-2 ti-btn-primary !font-medium !text-[0.75rem]">Tambah PricingPlan<i class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap table-bordered min-w-full" id="pricing_plan-table">
                            <thead>
                                <tr class="border-b border-defaultborder">
                                    <th>Id</th>
<th>Name</th>
<th>Price</th>
<th>Price Subtext</th>
<th>Button Text</th>
<th>Button Link</th>
<th>Is Featured</th>
<th>Badge Text</th>
<th>Order</th>
 <!-- Placeholder untuk header kolom -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#pricing_plan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('pricing_plan.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'price', name: 'price' },
{ data: 'price_subtext', name: 'price_subtext' },
{ data: 'button_text', name: 'button_text' },
{ data: 'button_link', name: 'button_link' },
{ data: 'is_featured', name: 'is_featured' },
{ data: 'badge_text', name: 'badge_text' },
{ data: 'order', name: 'order' },
 
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
