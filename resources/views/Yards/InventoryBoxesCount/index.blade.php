@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Ejecución de inventario de cajas
                </div>
                <form method="POST" action="{{url('inventoryboxcount')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Código de barra</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="barcode" name="barcode"
                                               aria-describedby="basic-addon1" value="{{ old('barcode') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-info btn-xs " style="font-size: 11px"
                                                    id="searchbarcode"
                                                    type="button">Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Placa de caja</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="boxnumber" name="boxnumber"
                                               aria-describedby="basic-addon1" value="{{ old('boxnumber') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-info btn-xs" style="font-size: 11px"
                                                    id="searchboxnumber"
                                                    type="button">Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label for="folionumber">Número de folio</label>
                                    <input type="text" class="form-control" readonly id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label for="boxnumber2">Placa de caja</label>
                                    <input type="text" class="form-control" readonly id="boxnumber2" name="boxnumber2"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carriername">Linea de transporte</label>
                                    <input type="text" class="form-control" readonly id="carriername" name="carriername"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Economico de caja</label>
                                    <input type="text" class="form-control" readonly id="econumber" name="econumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="originlocationid">Ubiciación actual</label>
                                    <input type="text" class="form-control" readonly id="originlocationid"
                                           name="originlocationid"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                        </div>
                        <hr class="mt-3 mb-3">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="zoneid">Nueva Zona<span style="color: red">*</span></label>
                                    <select name="zoneid" id='zoneid' required class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($zones as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="locationid">Nueva Ubicación<span style="color: red">*</span></label>
                                    <select name="locationid" id='locationid' required class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($locations as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td width="15%" class="radio-switch">
                                            <input type="radio" value="1"
                                                   name="unitstatusid" id="truckid" data-size="mini"
                                                   data-on-color="success" data-on-text="Seleccionado"
                                                   data-off-text="Vacio" required>
                                        </td>
                                        <td><span style="font-size: 13px;color: black;font-weight: 600">Vacio </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="15%" class="radio-switch">
                                            <input type="radio" value="2"
                                                   name="unitstatusid" id="truckid" data-size="mini"
                                                   data-on-color="success" data-on-text="Seleccionado"
                                                   data-off-text="Cargado" required>
                                        </td>
                                        <td><span style="font-size: 13px;color: black;font-weight: 600">Cargado</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-xs-4 col-sm-4">
                                <a href="">
                                    <button type="submit"
                                            class="btn waves-effect waves-light btn-outline-info btn-block btn-md">
                                        <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Guardar</span>
                                    </button>

                                </a>
                            </div>
                            <div class="col-xs-4 col-sm-4 mt-3">
                                <a href="{{route('home')}}">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md btn-block">
                                        <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16 ml-1">
                                        <span style="font-size: 11px;"> Cancelar</span>
                                    </button>
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
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection

@section('script')

    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>


    <script type="text/javascript">
        $(".radio-switch input[type='radio']").bootstrapSwitch();
    </script>

    <script>
        $("#searchbarcode").click(function () {

            if ($("#barcode").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    text: 'Ingrese el numero de Folio o Etiqueta!',
                    width: '300px',
                })
            } else {
                $.getJSON('./getDataInvoice/' + $("#barcode").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                            width: '300px',
                        })
                    } else {
                        $("#boxnumber2").attr("value", values[0].boxnumber);
                        $("#folionumber").attr("value", values[0].folio);
                        $("#carriername").attr("value", values[0].linea);
                        $("#originlocationid").attr("value", values[0].ubicacion);
                        $("#econumber").attr("value", values[0].econumber);
                    }
                });
            }

        });
    </script>

    <script>
        $("#searchboxnumber").click(function () {
            if ($("#boxnumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    text: 'Ingrese el numero de placa!',
                    width: '300px',
                })
            } else {
                $.getJSON('./getDataInvoiceForBox/' + $("#boxnumber").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                            width: '300px',
                        })
                    } else {
                        $("#boxnumber2").attr("value", values[0].boxnumber);
                        $("#folionumber").attr("value", values[0].folio);
                        $("#carriername").attr("value", values[0].linea);
                        $("#originlocationid").attr("value", values[0].ubicacion);
                        $("#barcode").attr("value", values[0].barcode);
                        $("#econumber").attr("value", values[0].econumber);
                    }
                });
            }

        });
    </script>


    <script>
        $("#zoneid").change(event => {
            $.get(`./getLocationForZoneUN/${event.target.value}`, function (res) {
                $("#locationid").empty();
                res.forEach(element => {
                    $("#locationid").append(`<option value=${element.id}> ${element.name} </option>`);
                });
            });
        });
    </script>
@endsection
