@if($totalstock != $tconteo01)
    <span class="label label-danger font-12">{{number_format(round($tconteo01,3),3)}}</span>
@else
    <span class="label label-success font-12">{{number_format(round($tconteo01,3),3)}}</span>
@endif
