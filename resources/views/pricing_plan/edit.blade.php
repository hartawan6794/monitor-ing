@extends('layouts.app')

@section('title', 'Ubah PricingPlan')

@section('content')
    <div class="block justify-between page-header md:flex">
        <div>
            <h3 class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                Ubah PricingPlan</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-[0.813rem] ps-[0.5rem]">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary truncate" href="{{ route('home') }}">
                    Dashboards
                    <i class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] ps-[0.5rem]">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary truncate" href="{{ route('pricing_plan.index') }}">
                    PricingPlans
                    <i class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] text-defaulttextcolor font-semibold hover:text-primary dark:text-[#8c9097] dark:text-white/50" aria-current="page">
                Edit
            </li>
        </ol>
    </div>

    <!-- Form Ubah PricingPlan -->
    <div class="box">
        <div class="box-header justify-between">
            <div class="box-title">
                Ubah Data PricingPlan
            </div>
        </div>
        <div class="box-body">
            <form action="{{ route('pricing_plan.update', $pricing_plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Memanggil untuk menghasilkan input berdasarkan kolom -->
            <div class="mb-4">
                <label for="form-name" class="form-label !text-[.875rem] text-black">Nama Paket</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="form-name" name="name" value="{{ old('name', $pricing_plan->name) }}" placeholder="Contoh: Basic, Premium, etc.">
                @error('name')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-6 mb-4">
                    <label for="form-price" class="form-label !text-[.875rem] text-black">Harga</label>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="form-price" name="price" value="{{ old('price', $pricing_plan->price) }}" placeholder="Contoh: $19, Free, etc.">
                    @error('price')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="col-span-6 mb-4">
                    <label for="form-price_subtext" class="form-label !text-[.875rem] text-black">Subteks Harga</label>
                    <input type="text" class="form-control @error('price_subtext') is-invalid @enderror" id="form-price_subtext" name="price_subtext" value="{{ old('price_subtext', $pricing_plan->price_subtext) }}" placeholder="Contoh: /per month">
                    @error('price_subtext')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-6 mb-4">
                    <label for="form-button_text" class="form-label !text-[.875rem] text-black">Teks Tombol</label>
                    <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="form-button_text" name="button_text" value="{{ old('button_text', $pricing_plan->button_text) }}" placeholder="Contoh: Get Started">
                    @error('button_text')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="col-span-6 mb-4">
                    <label for="form-button_link" class="form-label !text-[.875rem] text-black">Link Tombol</label>
                    <input type="text" class="form-control @error('button_link') is-invalid @enderror" id="form-button_link" name="button_link" value="{{ old('button_link', $pricing_plan->button_link) }}" placeholder="Contoh: # / https://...">
                    @error('button_link')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-4 mb-4">
                    <label for="form-badge_text" class="form-label !text-[.875rem] text-black">Teks Badge</label>
                    <input type="text" class="form-control @error('badge_text') is-invalid @enderror" id="form-badge_text" name="badge_text" value="{{ old('badge_text', $pricing_plan->badge_text) }}" placeholder="Contoh: Most Popular">
                    @error('badge_text')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="col-span-4 mb-4">
                    <label for="form-order" class="form-label !text-[.875rem] text-black">Urutan</label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="form-order" name="order" value="{{ old('order', $pricing_plan->order) }}">
                    @error('order')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="col-span-4 mb-4 flex items-center pt-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="form-is_featured" {{ old('is_featured', $pricing_plan->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label !text-[.875rem] text-black" for="form-is_featured">
                            Tandai Sebagai Terpopuler/Utama?
                        </label>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-[1rem]">Fitur Paket</h4>
                    <button type="button" class="ti-btn ti-btn-sm ti-btn-primary" id="add-feature">
                        <i class="ri-add-line"></i> Tambah Fitur
                    </button>
                </div>
                
                <div id="features-container">
                    @php 
                        $currentFeatures = old('features', $pricing_plan->features);
                    @endphp
                    @foreach($currentFeatures as $index => $feature)
                        @php $feature = (object)$feature; @endphp
                        <div class="feature-row grid grid-cols-12 gap-x-4 mb-3 items-center">
                            <div class="col-span-8">
                                <input type="text" name="features[{{ $index }}][name]" class="form-control" value="{{ $feature->name ?? '' }}" placeholder="Nama Fitur">
                            </div>
                            <div class="col-span-3">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="features[{{ $index }}][is_highlighted]" value="1" {{ (isset($feature->is_highlighted) && $feature->is_highlighted) ? 'checked' : '' }}>
                                    <label class="form-check-label">Sorot?</label>
                                </div>
                            </div>
                            <div class="col-span-1 text-end">
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-danger remove-feature"><i class="ri-delete-bin-line"></i></button>
                            </div>
                        </div>
                    @endforeach

                    @if(count($currentFeatures) == 0)
                        <div class="feature-row grid grid-cols-12 gap-x-4 mb-3 items-center">
                            <div class="col-span-8">
                                <input type="text" name="features[0][name]" class="form-control" placeholder="Nama Fitur">
                            </div>
                            <div class="col-span-3">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="features[0][is_highlighted]" value="1">
                                    <label class="form-check-label">Sorot?</label>
                                </div>
                            </div>
                            <div class="col-span-1 text-end">
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-danger remove-feature"><i class="ri-delete-bin-line"></i></button>
                            </div>
                        </div>
                    @endif
                </div>
                @error('features')
                    <div class="text-danger mt-2"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <button class="ti-btn ti-btn-primary-full mt-4" type="submit">Update Paket</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @php 
            $initialCount = count($currentFeatures);
            if ($initialCount == 0) $initialCount = 1;
        @endphp
        let featureIndex = {{ $initialCount }};
        const container = document.getElementById('features-container');
        const addButton = document.getElementById('add-feature');

        addButton.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'feature-row grid grid-cols-12 gap-x-4 mb-3 items-center';
            row.innerHTML = `
                <div class="col-span-8">
                    <input type="text" name="features[${featureIndex}][name]" class="form-control" placeholder="Nama Fitur">
                </div>
                <div class="col-span-3">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="features[${featureIndex}][is_highlighted]" value="1">
                        <label class="form-check-label">Sorot?</label>
                    </div>
                </div>
                <div class="col-span-1 text-end">
                    <button type="button" class="ti-btn ti-btn-sm ti-btn-danger remove-feature"><i class="ri-delete-bin-line"></i></button>
                </div>
            `;
            container.appendChild(row);
            featureIndex++;
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-feature')) {
                const rows = container.querySelectorAll('.feature-row');
                if (rows.length > 1) {
                    e.target.closest('.feature-row').remove();
                } else {
                    alert('Minimal harus ada satu fitur.');
                }
            }
        });
    });
</script>
@endpush
            </form>
        </div>
    </div>
@endsection
