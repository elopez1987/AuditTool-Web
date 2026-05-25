@can('inventorylocations.edit')
    <a href="javascript:void(0);" onclick="openEditLocationModal({{ $id }})" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan



@can('inventorylocations.destroy')
    <a href="javascript:void(0);"
       onclick="openDestroyLocationModal({{ $id }}, '{{ empty($deleted_at) ? 'delete' : 'restore' }}', this)"
       data-toggle="tooltip"
       data-placement="top"
       title="{{ empty($deleted_at) ? 'Eliminar Registro' : 'Restaurar Registro' }}">
        <img src="{{ asset(empty($deleted_at) ? '/ico/addestroy1.png' : '/ico/typemovements.png') }}"
             alt="" class="img-16 ml-2">
    </a>
@endcan