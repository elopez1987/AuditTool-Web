@can('storageareas.edit')
    <a href="{{url('storageareas/' . $slug. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan


@can('storageareas.destroy')
    <a href="" data-target="#modal-delete-{{$slug}}" data-toggle="modal" data-toggle="tooltip"
       data-placement="top"
       title={{$statusid == "E" ? "Eliminar Registro" : "Restaurar Registro"}}
    >
        @if($statusid == 'E')
            <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">
        @else
            <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16">
        @endif
    </a>
    @include('Yards.StorageAreas.partials.delete')
@endcan


