@if($statustrans == 'IN' || $statustrans == 'QI' || $statustrans == 'ES')
    <span class="label label-red font-table">{{$statustransport}}</span>
@elseif($statustrans == 'RS')
    <span class="label label-orange font-table">{{$statustransport}}</span>
@elseif($statustrans == 'EP' || $statustrans == 'EN' || $statustrans == 'TR' || $statustrans == 'FI')
    <span class="label label-green font-table text-black">{{$statustransport}}</span>
@elseif($statustrans == 'OU')
    <span class="label label-blue font-table">{{$statustransport}}</span>
@endif
