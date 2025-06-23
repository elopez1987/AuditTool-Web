<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-kardexfolio">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                @include('Yards.KardexReceiptUnit.tabletrace')
            </div>
            <div class="modal-footer">
                <a href="{{url('tracereceiptunit/' . $orderdelivery->folionumber )}}" target="_blank">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                        <img src="{{asset('/ico/viewdetails.png')}}" alt="" class="img-16">
                        Ver Kardex
                    </button>
                </a>

                <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                        data-dismiss="modal">
                    <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">
                    Salir
                </button>
            </div>
        </div>
    </div>
</div>

