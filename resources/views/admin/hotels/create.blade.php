@extends('layouts.app')

@section('title', __('messages.admin_add_hotel') . ' - Kazakh Hotels')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-extrabold text-white mb-6">{{ __('messages.admin_add_hotel') }}</h1>

    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data" class="otl-surface p-6">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_name_col') }}</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_city') }}</label>
                <input type="text" name="city" required value="{{ old('city') }}" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_address') }}</label>
                <textarea name="address" required rows="3" class="field-input resize-none">{{ old('address') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.description') }}</label>
                <textarea name="description" rows="5" class="field-input resize-none">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_rating') }}</label>
                <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating') }}" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_cover') }}</label>
                <input type="file" name="image" accept="image/*" class="field-input" style="color-scheme: dark;">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_gallery_photos') }}</label>
                <input type="file" name="gallery_images[]" accept="image/*" multiple class="field-input" style="color-scheme: dark;">
                <p class="text-xs text-[#7e8488] mt-1.5">{{ __('messages.admin_multiple_files') }}</p>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-accent flex-1 py-3">{{ __('messages.admin_create_hotel') }}</button>
                <a href="{{ route('admin.hotels.index') }}" class="btn-dark px-6 py-3">{{ __('messages.cancel') }}</a>
            </div>
        </div>
    </form>
</div>
@endsection
