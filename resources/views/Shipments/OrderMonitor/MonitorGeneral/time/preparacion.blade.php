<div class="d-flex flex-row">
    <div class="align-self-center ">
        @if ($inicioreal != '')
            @if ($iniciopro > $inicioreal)
                <i class="fa fa-chevron-circle-up text-success font-14"></i>
            @else
                <i class="fa fa-chevron-circle-down text-danger font-14"></i>
            @endif
        @else
            <i class="fa fa-ban text-warning font-14"></i>
        @endif
    </div>
    <div class="m-l-10 align-self-center">
        @if ($inicioreal != '')
            <h6>
                {{\Carbon\Carbon::parse($iniciopro)->diffInMinutes($inicioreal)}} min
            </h6>
        @endif
        <span class="text-dark ">
           Prog:{{$inicioreal}} <br>
           Real:{{$iniciopro}}
        </span>
    </div>
</div>
