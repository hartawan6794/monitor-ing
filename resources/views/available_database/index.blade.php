@extends('layouts.app')

@section('title', 'Daftar AvailableDatabase')

@section('content')
    <div class="block justify-between page-header md:flex">
        <div>
            <h3
                class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                Databases</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-[0.813rem] ps-[0.5rem]">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary truncate"
                    href="{{ route('home') }}">
                    Dashboards
                    <i
                        class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] text-defaulttextcolor font-semibold hover:text-primary dark:text-[#8c9097] dark:text-white/50 "
                aria-current="page">
                Databases
            </li>
        </ol>
    </div>

    <div class="grid grid-cols-12 gap-x-12">
        <div class="xxxl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="box-header justify-between">
                    <div class="box-title">
                        Daftar untuk menambahkan server ke database yang dibutuhkan
                    </div>
                    <!-- <div class="prism-toggle">
                            <a href="{{ route('available_database.create')}}"
                                class="ti-btn !py-1 !px-2 ti-btn-primary !font-medium !text-[0.75rem]">Tambah
                                Database ke server<i class="fas fa-plus"></i></a>
                        </div> -->
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap table-bordered min-w-full" id="available_database-table">
                            <thead>
                                <tr class="border-b border-defaultborder">
                                    <th>Id</th>
                                    <th>Server Name</th>
                                    <th>Ip Address</th>
                                    <th>Daftar Database</th>
                                    <th>Aksi</th>
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
        $(function () {
            $('#available_database-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('available_database.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'server_name', name: 'server_name' }, // Sesuaikan dengan field di DB Server Anda
                    { data: 'ip_address', name: 'ip_address' },
                    {
                        data: 'database_list',
                        name: 'database_list',
                        orderable: false, // Matikan fitur sorting untuk kolom HTML ini
                        searchable: false // Matikan fitur search untuk kolom HTML ini
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false, // Matikan fitur sorting untuk kolom HTML ini
                        searchable: false // Matikan fitur search untuk kolom HTML ini
                    }
                ]
            });
        });
    </script>
@endpush