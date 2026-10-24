@can('storagecategoryincidences.edit')
    <a href="{{url('storagecategoryincidences/' . $categoryincid. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('createdetailcategoryincidence')
    <a href="{{url('storagecategoryincidences/' . $categoryincid . '/adddetail')}}" data-toggle="tooltip" data-placement="top"
       title="Detalle del Check List">
        <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan

