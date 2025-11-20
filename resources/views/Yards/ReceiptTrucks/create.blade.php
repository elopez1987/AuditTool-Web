@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    crear registro
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('receipttrucks')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('receipttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        @include('Yards.ReceiptTrucks.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')

@endsection


@section('script')
    <script type="module">
        import { initOperatorLookup } from "/js/selects/operator.js";

        initOperatorLookup(
            "searchOperator",
            "barcodeoperator",
            {
                operatorname: "name",
                drivernumber: "drivernumber",
                phone: "phone"
            }
        );
    </script>


    <script>
        $("#searchTruck").click(function () {
            if ($("#barcodetruck").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el numero de tracto!',
                })
            } else {
                $.getJSON('../getDataTruck/' + $("#barcodetruck").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros de tracto!',
                        })
                    } else {
                        $("#trucknumber").attr("value", values[0].boxnumber);
                        $.getJSON('../getCarrierForBox/' + $("#barcodetruck").val(), function (res) {
                            $("#carrierid").empty();
                            res.forEach(element => {
                                $("#carrierid").append(`<option value=${element.id}> ${element.name} </option>`);
                            });
                        });
                        $.getJSON('../getTypeUnitForBox/' + $("#barcodetruck").val(), function (res) {
                            $("#unittypeid").empty();
                            res.forEach(element => {
                                $("#unittypeid").append(`<option value=${element.id}> ${element.description} </option>`);
                            });
                        });
                    }
                });
            }
        });
    </script>
@endsection

