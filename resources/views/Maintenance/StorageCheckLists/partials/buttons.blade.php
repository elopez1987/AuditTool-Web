@can('storagechecklistlifttrucks.edit')
    <a href="{{url('storagechecklistlifttrucks/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('addchecklistdetailslifttruck')
    <a href="{{url('addchecklistdetailslifttruck/' . $id)}}" data-toggle="tooltip" data-placement="top"
       title="Detalle del Check List">
        <img src="{{asset('/ico/checklistdetails.png')}}" alt="" class="img-16 ml-1 mr-2">
    </a>
@endcan

@can('addchecklistdetailslifttruck')
    <a href="{{url('storage-check-listtruck-evidences/' . $id)}}" data-toggle="tooltip" data-placement="top"
       title="Check list de fotos">
        <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-1 mr-2">
    </a>
@endcan

@can('storagechecklistlifttrucks.destroy')
    <a href="" data-target="#modal-delete-{{$id}}"
       data-toggle="modal">
        <img src="{{asset('/ico/destroy2.png')}}" alt=""
             class="img-16 ml-2 mr-2">
    </a>
    @include('Maintenance.StorageCheckLists.delete')
@endcan
