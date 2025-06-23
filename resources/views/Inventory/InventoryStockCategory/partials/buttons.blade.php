@can('inventorystockcategories.edit')
    <a href="{{url('inventorystockcategories/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

