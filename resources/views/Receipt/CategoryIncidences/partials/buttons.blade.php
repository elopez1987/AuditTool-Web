@can('categories-incidents.edit')
    <a href="{{url('categories-incidents/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

{{--@can('createdetailcategoryincidence')--}}
{{--    <a href="{{url('categories-incidents/' . $id . '/adddetail')}}" data-toggle="tooltip" data-placement="top"--}}
{{--       title="Detalle del Check List">--}}
{{--        <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-1">--}}
{{--    </a>--}}
{{--@endcan--}}

