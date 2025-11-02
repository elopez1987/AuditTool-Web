@can('canceltask')
    @if ($statusid == 'PD')
        <a href="javascript:void(0)" class="btn-delete-task" data-id="{{$id}}" data-toggle="tooltip" data-placement="top" title="Eliminar Registro">
            <img src="{{asset('/ico/addestroy1.png')}}" alt="" class="img-16 ml-2">
        </a>
    @endif
@endcan

