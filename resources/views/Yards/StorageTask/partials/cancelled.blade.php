@can('canceltask')
    @if ($statusid == 'PD')
        <a href="" data-target="#modal-cancelledtask-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="Cancelar Tarea">
            <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">
        </a>
        @include('Yards.StorageTask.cancelledtask')
    @endif
@endcan

