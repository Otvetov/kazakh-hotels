@extends('layouts.app')

@section('title', __('messages.admin_edit_hotel') . ' - Kazakh Hotels')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-extrabold text-white mb-6">{{ __('messages.admin_edit_hotel') }}</h1>

    <form action="{{ route('admin.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data" class="otl-surface p-6">
        @csrf
        @method('PUT')
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_name_col') }}</label>
                <input type="text" name="name" required value="{{ old('name', $hotel->name) }}" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_city') }}</label>
                <input type="text" name="city" required value="{{ old('city', $hotel->city) }}" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_address') }}</label>
                <textarea name="address" required rows="3" class="field-input resize-none">{{ old('address', $hotel->address) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.description') }}</label>
                <textarea name="description" rows="5" class="field-input resize-none">{{ old('description', $hotel->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_rating') }}</label>
                <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', $hotel->rating) }}" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_cover') }}</label>
                @if($hotel->image)
                    <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-32 h-32 object-cover rounded-xl mb-3">
                @endif
                <input type="file" name="image" accept="image/*" class="field-input" style="color-scheme: dark;">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_add_gallery') }}</label>
                <input type="file" name="gallery_images[]" accept="image/*" multiple class="field-input" style="color-scheme: dark;">
                <p class="text-xs text-[#7e8488] mt-1.5">{{ __('messages.admin_multiple_files') }}</p>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-accent flex-1 py-3">{{ __('messages.save') }}</button>
                <a href="{{ route('admin.hotels.index') }}" class="btn-dark px-6 py-3">{{ __('messages.cancel') }}</a>
            </div>
        </div>
    </form>

    {{-- Управление галереей (отдельно, чтобы не вкладывать формы) --}}
    @if($hotel->images->count())
        <div class="otl-surface p-6 mt-6">
            <h2 class="text-lg font-bold text-white mb-4">{{ __('messages.admin_gallery') }} ({{ $hotel->images->count() }})</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($hotel->images as $img)
                    <div class="relative group">
                        <img src="{{ $img->image_url }}" alt="" class="w-full h-28 object-cover rounded-xl">
                        <form action="{{ route('admin.hotels.images.destroy', [$hotel, $img]) }}" method="POST"
                              onsubmit="return confirm('{{ __('messages.admin_delete_photo_confirm') }}');"
                              class="absolute top-1.5 right-1.5">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 bg-black/60 hover:bg-[#f04141] rounded-full text-white transition" aria-label="{{ __('messages.admin_delete') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
