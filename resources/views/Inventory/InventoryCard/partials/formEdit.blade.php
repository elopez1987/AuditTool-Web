
<div class="row">
    <x-select2
            name="inventory_area_id"
            label="Area"
            :options="$areas"
            :selected="$inventorycard->inventory_area_id"
            required
    />
    <x-select2
            name="inventory_aisle_id"
            label="Pasillo"
            :options="$aisles"
            :selected="$inventorycard->inventory_aisle_id"
            required
    />
</div>
<div class="row">
    <x-text-input
            name="name"
            label="Cédula"
            value="{{$inventorycard->name}}"
            required
            uppercase
    />
</div>
<div class="row">
    <x-text-input
            name="auxiliary01"
            label="Auxiliar 01"
            value="{{$inventorycard->auxiliary01}}"
            uppercase
    />
    <x-text-input
            name="auxiliary02"
            label="Auxiliar 02"
            value="{{$inventorycard->auxiliary02}}"
            uppercase
    />
    <x-text-input
            name="auxiliary03"
            label="Auxiliar 03"
            value="{{$inventorycard->auxiliary03}}"
            uppercase
    />
</div>
<div class="row">
    <x-select2
            name="statusid"
            label="Estatus del registro"
            value-field="statusid"
            :options="$status"
            :selected="$inventorycard->statusid"
            required
    />
</div>