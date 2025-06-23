@if ($deliverdate != null)
    <i class="fa fa-truck text-success font-12"></i>
@endif
@if ($vendor == '196802' || $vendor == '269004')
    <span class="label label-rounded label-warning" style="font-size: 0.675rem !important;;color: black">
        Back Hauling
    </span>
@endif
