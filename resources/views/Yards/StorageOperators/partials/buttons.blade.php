@can('storageoperators.edit')
    <a href="{{url('storageoperators/' . $id. '/edit')}}" >
        @include('buttons.edit')
    </a>
@endcan
@can('storageoperators.printcard')
    <a href="{{url('printlabeloperator/' . $id)}}">
        <img src="{{asset('/ico/systemprinter.png')}}" alt=""
             class="img-16 ml-2">
    </a>
@endcan
@can('storageoperators.destroy')
    <a href="" data-target="#modal-destroyoperator-{{$id}}" data-toggle="modal" >
        <img src="{{asset('/ico/delete_delete_deleteusers_delete_male_user_maleclient_2348.png')}}" alt=""
             class="img-16 ml-2">
    </a>
    @include('Yards.StorageOperators.destroy')
@endcan
