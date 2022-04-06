@props(['placeholder' => 'Select Options', 'id'])

<div wire:ignore>
    <select {{ $attributes }} id="{{ $id }}" multiple="multiple" data-placeholder="{{ $placeholder }}" >
        {{ $slot }}
    </select>
</div>

@once
@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
@endonce


@push('scripts')
<!-- Select2 -->
<script src="{{ asset('backend/select2/js/select2.full.min.js') }}"></script>

<script>
    $(function() {
        $('#{{ $id }}').select2({
            theme: 'bootstrap4',
        })
    })
</script>
@endpush
