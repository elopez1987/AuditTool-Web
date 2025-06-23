{{--@if ($code == '-1')--}}
    <a href="" data-target="#modal-reprocessizel-{{$id}}" data-toggle="modal" data-toggle="tooltip"
       data-placement="top" title="Reprocesar interface a izel">
        <img src="{{asset('/ico/reprocessizel.png')}}" alt="" class="img-16">
    </a>
    @include('Configuration.LogWebService.partials.reprocessizel')
{{--@endif--}}
