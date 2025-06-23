@if($statusid == 'E')
    <span class="label label-green font-table text-black">{{$statusname}}</span>
@endif

@if($statusid == 'D')
<span class="label label-red font-table text-white">{{$statusname}}</span>
@endif

@if($statusid == 'A')
    <span class="label label-yellow font-table text-black">{{$statusname}}</span>
@endif
