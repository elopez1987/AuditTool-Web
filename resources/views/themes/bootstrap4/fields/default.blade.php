<div id="field_{{ $id }}" class="form-group">
    <label for="{{ $id }}"{!! Html::classes(['text-danger' => $hasErrors]) !!}>
        {{ $label }}
        @if ($required)
                <span class="badge ">*</span>
        @endif
    </label>
    {!! $input !!}
@foreach ($errors as $error)
    <div class="invalid-feedback font-12">{{ $error }}</div>
@endforeach
</div>
