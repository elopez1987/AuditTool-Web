<div class="row">
    <x-select2
            name="centerid"
            label="Centros de distribución"
            :options="$centers"
            valueField="centerid"
            textField="name"
            :required="true"
    />
    <x-text-input
            name="name"
            label="Nombre del área"
            required
            uppercase
    />
</div>

<div class="row">
    <x-text-input
            name="auxiliary01"
            label="Auxiliar 01"
            uppercase
    />
    <x-text-input
            name="auxiliary01"
            label="Auxiliar 01"
            uppercase
    />
    <x-text-input
            name="auxiliary01"
            label="Auxiliar 01"
            uppercase
    />
</div>