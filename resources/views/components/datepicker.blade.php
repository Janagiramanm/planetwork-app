
@props(['id', 'error'])

<input {{ $attributes }} type="date" class="form-control datetimepicker-input @error($error) is-invalid @enderror" id="{{ $id }}" data-toggle="datetimepicker" data-target="#{{ $id }}"
onchange="this.dispatchEvent(new InputEvent('input'))"
/>


