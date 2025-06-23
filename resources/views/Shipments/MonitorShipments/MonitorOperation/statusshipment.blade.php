@if($statusshipment == 'PC')
    <span class="label label-blue font-table">{{$status}}</span>
@elseif($statusshipment == 'DO' || $statusshipment == 'IF' || $statusshipment == 'EJ' || $statusshipment == 'DT')
    <span class="label label-green font-table text-black">{{$status}}</span>
@elseif($statusshipment == 'IP')
    <span class="label label-yellow font-table text-black">{{$status}}</span>
@else
    {{$status}}
@endif

