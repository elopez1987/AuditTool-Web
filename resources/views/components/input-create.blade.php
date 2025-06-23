<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <input
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes }}  {{-- Permite añadir atributos extra como required, readonly --}}
    >
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


{{--<x-input-create--}}
{{--    type="email"--}}
{{--    id="email"--}}
{{--    name="email"--}}
{{--    label="Correo electrónico"--}}
{{--    placeholder="Ingresa tu email"--}}
{{--    value="{{ old('email') }}"--}}
{{--    required--}}
{{--/>--}}
