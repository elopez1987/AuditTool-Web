@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Agregar datos al recibo
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('addstockstore/' . $receiptasn->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                @if ($receiptasn->statusid != 'IG')
                                    <a href="">
                                        @include('buttons.save')
                                    </a>
                                @endif
                                <a href="javascript:history.back()">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="d-flex flex-row comment-row m-t-0">
                                        <div class="comment-text w-100">
                                            <h6 class="font-weight-light">Embarque:</h6>
                                            <span
                                                class="m-b-15 d-block"> {{$receiptasn->shipmentid}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="d-flex flex-row comment-row m-t-0">
                                        <div class="comment-text w-100">
                                            <h6 class="font-weight-light">Factura:</h6>
                                            <span
                                                class="m-b-15 d-block"> {{$receiptasn->billoflading}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="d-flex flex-row comment-row m-t-0">
                                        <div class="comment-text w-100">
                                            <h6 class="font-weight-light">Orden de compra:</h6>
                                            <span
                                                class="m-b-15 d-block"> {{$receiptasn->oc}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="d-flex flex-row comment-row m-t-0">
                                        {!! DNS2D::getBarcodeHTML($receiptasn->shipmentid, 'QRCODE',4,4) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-input">
                            @if ($receiptasn->statusid != 'IG')
                                @include('Receipt.ReceiptAsn.partials.addDetailStock')
                            @endif
                        </div>
                    </form>
                </div>
{{--                <div class="card-footer">--}}
{{--                    @if($receiptasn->statusid != 'IG')--}}
{{--                        <a href="" data-target="#modal-uploaddata-{{$receiptasn->id}}" data-toggle="modal"--}}
{{--                           data-placement="top">--}}
{{--                            <button type="button"--}}
{{--                                    class="btn waves-effect waves-light btn-outline-info btn-md"--}}
{{--                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">--}}
{{--                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">--}}
{{--                                <span class="text-button"> Importar datos </span>--}}
{{--                            </button>--}}
{{--                        </a>--}}
{{--                        @include('Receipt.ReceiptAsn.uploaddata')--}}
{{--                    @endif--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Material ingresado
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed tablegeneral ">
                                <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>F.Producción</th>
                                    <th>Lote</th>
                                    <th>Cantidad</th>
                                    <th>Rotativa</th>
                                    <th>Definitiva</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stocks as $stock)
                                    <tr>
                                        <td>{{$stock->partnumber->prtnum}}</td>
                                        <td>{{$stock->partnumber->lngdsc}}</td>
                                        <td>{{$stock->productiondate}}</td>
                                        <td>{{$stock->expirecode}}</td>
                                        <td>{{$stock->quantity}}</td>
                                        <td>{{$stock->rotary}}</td>
                                        <td>{{$stock->definitive}}</td>
                                        <td>
                                            <a href="" data-target="#modal-updatequantity-{{$stock->id}}"
                                               data-toggle="modal" data-placement="top">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-outline-info btn-sm">
                                                    <img src="{{asset('/ico/adedit.png')}}" alt=""
                                                         class="img-16">
                                                </button>
                                            </a>
                                            @include('Receipt.ReceiptAsn.editquantity')
                                            <a href="" data-target="#modal-deletestock-{{$stock->id}}"
                                               data-toggle="modal" data-placement="top">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-outline-info btn-sm">
                                                    <img src="{{asset('/ico/closemodal.png')}}" alt=""
                                                         class="img-16">
                                                </button>
                                            </a>
                                            @include('Receipt.ReceiptAsn.deletestock')
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>

    <script>
        $('.date').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});
    </script>

    <script type="module">
        document.addEventListener("DOMContentLoaded", () => {

            const prtnum = document.getElementById("prtnum");
            const lngdsc = document.getElementById("lngdsc");

            prtnum.addEventListener("blur", async () => {

                const code = prtnum.value.trim();

                if (code === "") {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Ingrese el número de parte",
                    });
                    return;
                }

                try {
                    const url = `../../inventory-partnumbers/${encodeURIComponent(code)}/search`;

                    const response = await fetch(url);
                    const data = await response.json();

                    if (!data?.Data) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "No se encuentran registros del Sku!",
                        });

                        prtnum.value = "";
                        lngdsc.value = "";
                        return;
                    }

                    // Llenar campos
                    lngdsc.value = data.Data.lngdsc ?? "";

                } catch (error) {
                    console.error("Error en la petición:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Ocurrió un error al consultar el SKU.",
                    });
                }
            });

        });
    </script>


@endsection
