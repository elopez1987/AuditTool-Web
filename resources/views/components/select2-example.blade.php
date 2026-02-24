{{-- EJEMPLO DE USO DEL COMPONENTE SELECT2 --}}

{{-- Uso básico --}}
<x-select2
    name="centerid"
    label="Centros de distribución"
    :options="$centers"
    valueField="centerid"
    textField="name"
    :required="true"
/>

{{-- Con placeholder personalizado --}}
<x-select2
    name="status"
    label="Estado"
    :options="$statuses"
    placeholder="Seleccione un estado"
/>

{{-- Con clase de columna personalizada --}}
<x-select2
    name="category"
    label="Categoría"
    :options="$categories"
    colClass="col-12"
/>

{{-- Con valor seleccionado por defecto --}}
<x-select2
    name="type"
    label="Tipo"
    :options="$types"
    :selected="$selectedType"
/>

{{-- Con atributos adicionales (data attributes, etc) --}}
<x-select2
    name="warehouse"
    label="Almacén"
    :options="$warehouses"
    data-allow-clear="true"
    data-placeholder="Buscar almacén..."
/>

{{-- Con arrays simples en lugar de objetos --}}
<x-select2
    name="country"
    label="País"
    :options="[
        ['id' => 1, 'name' => 'México'],
        ['id' => 2, 'name' => 'USA'],
        ['id' => 3, 'name' => 'Colombia']
    ]"
/>

{{-- ANTES (Código original) --}}
{{--
<div class="col-lg-4 col-md-6 col-sm-12">
    <div class="mb-3">
        <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
        <select name="centerid" required class="form-control select2 @error('centerid') is-invalid @enderror">
            <option value="" selected>Seleccione una opción</option>
            @foreach($centers as $center)
                <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }} > {{$center->name}} </option>
            @endforeach
        </select>
        @error('centerid')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
--}}

{{-- DESPUÉS (Con el componente) --}}
{{--
<x-select2
    name="centerid"
    label="Centros de distribución"
    :options="$centers"
    valueField="centerid"
    textField="name"
    :required="true"
/>
--}}
