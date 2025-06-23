@php use Carbon\Carbon; @endphp

@if(Carbon::now() >= $outputdate)
    <span class="label label-danger font-table text-white">{{$outputdate}}</span>
@elseif(Carbon::now() <= Carbon::parse($outputdate)->subMinute(1) || Carbon::now() <= Carbon::parse($outputdate)->subMinutes(60))
    <span class="label label-yellow font-table text-black">{{$outputdate}}</span>
@elseif(Carbon::now() <= Carbon::parse($outputdate)->subMinute(61))
    <span class="label label-yellow font-table text-black">{{$outputdate}}</span>
@endif

