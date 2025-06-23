@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar tarea
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagetask')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storagetask.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagetask.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-2">
                        @include('Yards.StorageTask.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        $("#zoneid").change(event => {
            $.get(`../getLocationForZone/${event.target.value}`, function (res) {
                $("#locationid").empty();
                res.forEach(element => {
                    $("#locationid").append(`<option value=${element.id}> ${element.name} </option>`);
                });
            });
        });
    </script>
    <script>
        $("#searchInvoice").click(function () {
            if ($("#folionumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el numero de Folio o Etiqueta!',
                })
            } else {
                $.getJSON('../getDataInvoiceForFolio/' + $("#folionumber").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                        })
                    } else {
                        $("#boxnumber").attr("value", values[0].boxnumber);
                        $("#containerseal").attr("value", values[0].containerseal);
                        $("#originzoneid").attr("value", values[0].zona);
                        $("#originlocationid").attr("value", values[0].ubicacion);
                        $("#operatorname").attr("value", values[0].operatorname);
                        $("#phone").attr("value", values[0].phone);
                        $("#econumber").attr("value", values[0].econumber);
                        $("#barcode").attr("value", values[0].barcode);
                        $("#linea").attr("value", values[0].linea);
                    }
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            function togglePatiero() {
                let type = document.getElementById("typeid").value;
                if (type === "1") {
                    $("#patiero").css("display", "none");
                } else {
                    $("#patiero").css("display", "block");
                }
            }
            togglePatiero();
            $('#typeid').on('change', function () {
                togglePatiero();
            });
        });
    </script>

@endsection
