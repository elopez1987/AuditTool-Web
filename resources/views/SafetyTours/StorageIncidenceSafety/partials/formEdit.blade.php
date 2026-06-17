<div class="row">
    <x-text-input
            name="name"
            label="Nombre del área"
            required
            uppercase
            :value="$storage_incidence_safety->name"
    />

</div>
<div class="row">
    <x-text-input
            name="description"
            label="Descripción de la incidencia"
            required
            uppercase
            colClass="col-lg-6 col-md-12"
            :value="$storage_incidence_safety->description"
    />
</div>
<div class="row">
    <x-select2
            name="priority"
            label="Prioridad"
            :selected="$storage_incidence_safety->priority"
            valueField="id"
            textField="name"
            :options="[
                ['id' => 'LOW', 'name' => 'LOW'],
                ['id' => 'MEDIUM', 'name' => 'MEDIUM'],
                ['id' => 'HIGH', 'name' => 'HIGH']
            ]"
    />
    <x-text-input
            name="location"
            label="Ubicación de la incidencia"
            required
            uppercase
            :value="$storage_incidence_safety->location"
    />
</div>

<div class="row">
    <x-text-input
            name="auxiliary01"
            label="Auxiliar 01"
            uppercase
            :value="$storage_incidence_safety->auxiliary01"
    />
    <x-text-input
            name="auxiliary02"
            label="Auxiliar 02"
            uppercase
            :value="$storage_incidence_safety->auxiliary02"
    />
    <x-text-input
            name="auxiliary03"
            label="Auxiliar 03"
            uppercase
            :value="$storage_incidence_safety->auxiliary03"
    />
</div>

<div class="row">
    <x-select2
            name="statusid"
            label="Estatus"
            :options="$status"
            valueField="statusid"
            textField="name"
            :selected="$storage_incidence_safety->statusid"
    />
</div>