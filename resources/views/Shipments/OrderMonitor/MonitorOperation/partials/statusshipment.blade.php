<span class="label
@if ($statusid == 'PR')
    label-info text-white
@elseif ($statusid == 'AP')
    label-megna text-black
@elseif ($statusid == 'EJ')
    label-purple
@elseif ($statusid == 'FO')
    label-success
@elseif ($statusid == 'IP')
    label-warning
@else
    label-inverse text-white
@endif
    " style="font-size: 0.675rem !important;;text-transform: capitalize !important;">
        {{$statusname}}
 </span>
