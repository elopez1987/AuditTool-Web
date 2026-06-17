<div class="row">
    <x-text-input
            name="name"
            label="Nombre del área"
            required
            uppercase
            :value="$responsible_area_security->name"
    />
</div>

<div class="row">
    <x-text-input
            name="auxiliary01"
            label="Auxiliar 01"
            uppercase
            :value="$responsible_area_security->auxiliary01"
    />
    <x-text-input
            name="auxiliary02"
            label="Auxiliar 02"
            uppercase
            :value="$responsible_area_security->auxiliary02"
    />
    <x-text-input
            name="auxiliary03"
            label="Auxiliar 03"
            uppercase
            :value="$responsible_area_security->auxiliary03"
    />
</div>

<div class="row">
    <x-select2
            name="statusid"
            label="Estatus"
            :options="$status"
            valueField="statusid"
            textField="name"
            :selected="$responsible_area_security->statusid"
    />
</div>