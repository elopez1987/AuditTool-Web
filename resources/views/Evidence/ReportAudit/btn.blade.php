{{--@can('inventoryhead.show')--}}
<a href="{{url('reportauditshipments/' . $lpnid)}}" data-toggle="tooltip" data-placement="top"
   title="Ver detalle de ID.Container">
    <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2">
</a>
{{--@endcan--}}


<a href="{{url('getDocumentPDFKardex/' . $lpnid)}}" data-toggle="tooltip" data-placement="top"
   title="Pepeleta">
    <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16 ml-2">
</a>

