@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Salida de caja por ajuste de Inventario.
                </div>
                <form method="POST" action="{{url('registeroutputunit')}}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-info">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h2 class="text-info font-12"><i class="fa fa-check-circle"></i> Aviso</h2>
                                    "¡Hola! Queremos informarles que hemos realizado algunos cambios en el formulario <b>Salida De Unidad Por Ajuste</b>
                                    para hacerlo aún más fácil de usar. Los cambios realizados son : Cambio de <b>Código de Caja por Número de Folio </b> como valor de búsqueda.
                                    ¡Esperamos que les guste la nueva versión!"
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Número de folio<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required  id="folionumber" name="folionumber">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carriername">Linea de transporte<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control"  id="carriername" name="carriername">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus de Caja<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control"  id="statusid" name="statusid">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placas de Caja<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control"  id="boxnumber" name="boxnumber">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Eco. de caja<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control"  id="econumber" name="econumber">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="trucknumber">Placas de tracto<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control"  id="trucknumber" name="trucknumber">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcodetruck">Código de barra de tracto<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control"  id="barcodetruck" name="barcodetruck">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcodetruck">Comentario del ajuste<span style="color: red">*</span></label>
                                   <textarea id="comment" name="comment" required class="form-control" cols="100" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('home')}}" style="margin-left: 10px">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        $("#folionumber").blur(function () {
            if ($("#folionumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el numero de Etiqueta!',
                    width: '300px'
                })
            } else {
                $.getJSON('./getDataInvoiceForFolio/' + $("#folionumber").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                            width: '300px'
                        })
                    } else {
                        $("#boxnumber").attr("value", values[0].boxnumber);
                        $("#trucknumber").attr("value", values[0].trucknumber);
                        $("#statusid").attr("value", values[0].status);
                        $("#econumber").attr("value", values[0].econumber);
                        $("#folioid").attr("value", values[0].folio);
                        $("#carriername").attr("value", values[0].linea);
                        $("#barcodetruck").attr("value", values[0].barcodetruck);
                    }
                });
            }
        });
    </script>
@endsection
