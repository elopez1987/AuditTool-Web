
    <a href="{{url('authorizetransportentry/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Realizar Check List">
        <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16">
    </a>

@if ($statusid != 'OU')
    @can('receiptunits.edit')
        <a href="{{url('receiptunits/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro">
            @include('buttons.edit')
        </a>
    @endcan
@endif

@can('registerout')
    <a href="" data-target="#modal-updatestatus-{{$id}}"
       data-toggle="modal">
        <img src="{{asset('/ico/refuseunit.png')}}" alt=""
             class="img-16 ml-2">
    </a>
    @include('Yards.AuthorizeTransportEntry.rechazar')
@endcan
