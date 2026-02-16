@can('webservicelog.show')
    <a href="{{url('webservicelog/' . $id )}}" data-toggle="tooltip" data-placement="top"
       title="Detalle del Registro">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan