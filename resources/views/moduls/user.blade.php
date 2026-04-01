@extends('layouts.app')

@section('title', 'Administrator')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="block justify-between page-header md:flex">
        <div>
            <h3
                class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                Users</h3>
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
                Users
            </li>
        </ol>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-12">
        <div class="xxxl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="box-header justify-between">
                    <div class="box-title">
                        Bordered Tables
                    </div>
                    <div class="prism-toggle">
                        <button type="button" class="ti-btn !py-1 !px-2 ti-btn-primary !font-medium !text-[0.75rem]">Show
                            Code<i class="ri-code-line ms-2 inline-block align-middle"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap table-bordered min-w-full" id="users-table">
                            <thead>
                                <tr class="border-b border-defaultborder">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created At</th>
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
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('users.data') !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
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
{{-- @push('scripts') --}}
{{-- <script>
        // Data kuartal
        const quarterlyLabels = ['Kuartal 1', 'Kuartal 2', 'Kuartal 3', 'Kuartal 4'];

        // Inisialisasi Bar Chart
        const ctxQuarterly = document.getElementById('quarterlyChart').getContext('2d');
        const quarterlyChart = new Chart(ctxQuarterly, {
            type: 'bar', // Tipe grafik batang
            data: {
                labels: quarterlyLabels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: [
                            {{' $suratMasukPerKuartal'[1] ?? 0 }},
                            {{ $suratMasukPerKuartal[2] ?? 0 }},
                            {{ $suratMasukPerKuartal[3] ?? 0 }},
                            {{ $suratMasukPerKuartal[4] ?? 0 }},
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Surat Didisposisikan',
                        data: [
                            {{ $disposisiPerKuartal[1] ?? 0 }},
                            {{ $disposisiPerKuartal[2] ?? 0 }},
                            {{ $disposisiPerKuartal[3] ?? 0 }},
                            {{ $disposisiPerKuartal[4] ?? 0 }},
                        ],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Surat Keluar',
                        data: [
                            {{ $suratKeluarPerKuartal[1] ?? 0 }},
                            {{ $suratKeluarPerKuartal[2] ?? 0 }},
                            {{ $suratKeluarPerKuartal[3] ?? 0 }},
                            {{ $suratKeluarPerKuartal[4] ?? 0 }},
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Data untuk Pie Chart
        const statusLabels = ['Baru', 'Diproses', 'Disposisi', 'Selesai'];
        const statusData = [
            {{ $persentaseBaru }},
            {{ $persentaseDiproses }},
            {{ $persentaseDidisposisi }},
            {{ $persentaseSelesai }}
        ];

        // Inisialisasi Pie Chart
        const ctxStatusPie = document.getElementById('statusPieChart').getContext('2d');
        const statusPieChart = new Chart(ctxStatusPie, {
            type: 'pie', // Tipe grafik lingkaran
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

    </script> --}}
{{-- @endpush --}}
