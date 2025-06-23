@if ($time <= 2)
    <span class="label label-success font-12">{{$stays}}</span>
@elseif ($time > 2)
    <span class="label label-danger font-12">{{$stays}}</span>
@endif

