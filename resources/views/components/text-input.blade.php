@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
    'colClass' => 'col-lg-2 col-md-6 col-sm-12',
    'value' => null,
    'uppercase' => false,
])

<div class="{{ $colClass }}">
    <div class="mb-3">
        <label for="{{ $name }}">
            {{ $label }}
            @if($required)
                <span style="color: red">*</span>
            @endif
            @error($name)
            <span class="message-error">{{$message}}</span>
            @enderror
        </label>
        <input 
            type="{{ $type }}" 
            class="form-control @error($name) is-invalid @enderror" 
            {{ $required ? 'required' : '' }}
            id="{{ $name }}" 
            name="{{ $name }}"
            aria-describedby="{{ $name }}" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($uppercase)
                onKeyUp="this.value=this.value.toUpperCase()"
            @endif
            {{ $attributes }}
        >
        @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{--<x-text-input--}}
{{--        name="email"--}}
{{--        label="Correo electrónico"--}}
{{--        type="email"--}}
{{--        required--}}
{{--        placeholder="ejemplo@correo.com"--}}
{{--        colClass="col-lg-6 col-md-12"--}}
{{--/>--}}

{{--<x-text-input--}}
{{--        name="name"--}}
{{--        label="Nombre completo"--}}
{{--        required--}}
{{--        uppercase--}}
{{--/>--}}