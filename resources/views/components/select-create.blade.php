<div class="form-group">
    <label for="{{ $name }}">
        {{ $label }}
        @if($required)
            <span style="color: red">*</span>
        @endif
    </label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-control select2 {{ $errors->has($name) ? 'is-invalid' : '' }}"
        {{ $required ? 'required' : '' }}
    >
        <option value="" selected>{{ $placeholder }}</option>
        @foreach($options as $key => $option)
            <option
                value="{{ is_object($option) ? $option->id : $key }}"
                {{ old($name, $value) == (is_object($option) ? $option->id : $key) ? 'selected' : '' }}
            >
                {{ is_object($option) ? $option->name : $option }}
            </option>
        @endforeach
    </select>
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


{{--<x-select-create--}}
{{--    label="Línea de transporte"--}}
{{--    name="carrierid"--}}
{{--    :options="$carriers"--}}
{{--    value="{{ old('carrierid') }}"--}}
{{--    required="true"--}}
{{--    placeholder="Seleccione una opción"--}}
{{--/>--}}
