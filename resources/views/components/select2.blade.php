@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'placeholder' => 'Seleccione una opción',
    'colClass' => 'col-lg-2 col-md-6 col-sm-12',
    'valueField' => 'id',
    'textField' => 'name',
    'selected' => null,
])

<div class="{{ $colClass }}">
    <div class="mb-3">
        <label for="{{ $name }}">
            {{ $label }}
            @if($required)
                <span style="color: red">*</span>
            @endif
        </label>
        <select 
            name="{{ $name }}" 
            id="{{ $name }}"
            {{ $required ? 'required' : '' }} 
            class="form-control select2 @error($name) is-invalid @enderror"
            {{ $attributes }}
        >
            <option value="" selected>{{ $placeholder }}</option>
            @foreach($options as $option)
                @php
                    $value = is_array($option) ? $option[$valueField] : $option->$valueField;
                    $text = is_array($option) ? $option[$textField] : $option->$textField;
                    $isSelected = old($name, $selected) == $value;
                @endphp
                <option value="{{ $value }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        </select>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
