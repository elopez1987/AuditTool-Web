
@can('storagekeyincidencesopetations.edit')
    <a href="{{url('storagekeyincidencesopetations/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Ver detalles">
        <img src="{{asset('/ico/viewdetails.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
