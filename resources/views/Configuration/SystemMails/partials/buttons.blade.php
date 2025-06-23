@can('systemmails.edit')
    <a href="{{url('systemmails/' . $mailid . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.search-table')
    </a>
@endcan
@can('systemmails.adddetailmail')
    <a href="{{url('systemmails/' . $mailid . '/createMailDetail')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar contactos">
        <img src="{{asset('/ico/systemmails.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan

