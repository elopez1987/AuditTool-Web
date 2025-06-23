@can('addpriority')
    @if ($downloadpriority == null)
        <a href="" data-target="#modal-addpriority-{{$id}}" data-toggle="modal" data-toggle="tooltip"
           data-placement="top" title="Marcar como prioridad">
            <img src="{{asset('/ico/priority02.png')}}" alt="" class="img-16 text-center">
        </a>
        @include('Receipt.ScheduleReceipt.options.addpriority')
    @else
        <label class="label label-danger font-10">Prioridad</label>
    @endif
@endcan
