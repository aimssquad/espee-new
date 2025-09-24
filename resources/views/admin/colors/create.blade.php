@extends('layouts.admin')

@section('title', 'Create Color')
@section('page-title', 'Create Color')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Create Color</h2>
        <a href="{{ route('admin.colors.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Colors
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.colors.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="hex_code" class="form-label">Hex Code *</label>
                    <div class="input-group">
                        <span class="input-group-text">#</span>
                        <input type="text" class="form-control @error('hex_code') is-invalid @enderror"
                               id="hex_code" name="hex_code" value="{{ old('hex_code') }}"
                               placeholder="000000" maxlength="6" required>
                        <div class="input-group-text">
                            <input type="color" id="color_picker" class="form-control-color"
                                   style="width: 30px; height: 30px; border: none;">
                        </div>
                    </div>
                    @error('hex_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Enter a 6-digit hex code (e.g., FF0000 for red)</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Color
                    </button>
                    <a href="{{ route('admin.colors.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hexInput = document.getElementById('hex_code');
    const colorPicker = document.getElementById('color_picker');

    // Update color picker when hex input changes
    hexInput.addEventListener('input', function() {
        if (this.value.length === 6) {
            colorPicker.value = '#' + this.value;
        }
    });

    // Update hex input when color picker changes
    colorPicker.addEventListener('input', function() {
        hexInput.value = this.value.substring(1);
    });
});
</script>
@endpush
@endsection
