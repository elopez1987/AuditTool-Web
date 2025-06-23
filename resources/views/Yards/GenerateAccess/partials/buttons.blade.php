@can('generateaccesswarehouse.edit')
    <a href="{{url('generateaccesswarehouse/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('generateaccessformat')
    @if ($statusid != 'BK')
        <a href="{{url('generateaccessformat/' . $id)}}" data-toggle="tooltip" data-placement="top"
           title="Imprimir PDF">
            <img src="{{asset('/ico/invoice.png')}}" alt="" class="img-16 ml-2">
        </a>
    @endif
@endcan


@can('generateaccesswarehouse.destroy')
    <a href="" data-target="#modal-destroy-{{$id}}" data-toggle="modal" >
        <img src="{{asset('/ico/delete_delete_deleteusers_delete_male_user_maleclient_2348.png')}}" alt=""
             class="img-16 ml-2">
    </a>
    @include('Yards.GenerateAccess.destroy')
@endcan
