@if($totalstock != $tconteo02)
    <span class="label label-danger font-12">{{number_format(round($tconteo02,3),3)}}</span>
@else
    <span class="label label-success font-12">{{number_format(round($tconteo02,3),3)}}</span>
@endif
