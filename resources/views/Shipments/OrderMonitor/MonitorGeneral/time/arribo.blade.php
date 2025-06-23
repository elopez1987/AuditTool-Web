<div class="d-flex flex-row">
    <div class="align-self-center ">
        @if ($arriboreal != '')
            @if ($arribopro > $arriboreal)
                <i class="fa fa-chevron-circle-up text-success font-14"></i>
            @else
                <i class="fa fa-chevron-circle-down text-danger font-14"></i>
            @endif
        @else
            <i class="fa fa-ban text-warning font-14"></i>
        @endif
    </div>
    <div class="m-l-10 align-self-center">
        @if ($arriboreal != '')
            <h6>
                {{\Carbon\Carbon::parse($arribopro)->diffInMinutes($arriboreal)}} min
            </h6>
        @endif
        <span class="text-dark ">
           Prog:{{$arribopro}} <br>
           Real:{{$arriboreal}}
        </span>
    </div>
</div>
