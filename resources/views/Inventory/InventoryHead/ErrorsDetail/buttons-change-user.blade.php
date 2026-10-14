<a href="" data-target="#modal-change-user-{{ $id }}" data-toggle="modal" data-placement="top">
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">
        <img src="{{ asset('/ico/adduser.png') }}" alt="" class="img-16">
        <span style="font-size: 11px;"> Cambiar usuario</span>
    </button>
</a>
@include('Inventory.InventoryHead.ErrorsDetail.change-user')
