@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    ajuste de datos de unidad.
                </div>
                <form method="POST" action="{{url('adjustmentunitstore')}}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Número de folio</label>
                                    <input type="text"  class="form-control" id="folionumber" name="folionumber" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carriername">Linea de transporte</label>
                                    <input type="text" readonly class="form-control" id="carriername"
                                           name="carriername">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="operatorname">Nombre de operador<span
                                            style="color: red">*</span></label>
                                    <input type="text"  class="form-control" id="operatorname"
                                           name="operatorname">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placas de Caja<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="boxnumber" name="boxnumber">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Eco. De caja</label>
                                    <input type="text" class="form-control" maxlength="19" id="econumber" name="econumber">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="trucknumber">Placas de tracto<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="trucknumber" name="trucknumber">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="foliotruck">Folio de tracto<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="foliotruck" name="foliotruck">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Concepto de entrada<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control" id="razonid" >
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="razonstatusid">Concepto de entrada nuevo <span style="color: red">*</span></label>
                                    <select name="razonstatusid" class="form-control select2">
                                        <option value="" selected>Seleccione un estatus</option>
                                        @foreach($concepts as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusidact">Estatus de Caja</label>
                                    <input type="text" readonly class="form-control" id="statusidact"
                                           name="statusidact">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus nuevo <span style="color: red">*</span></label>
                                    <select name="statusid" class="form-control select2">
                                        <option value="" selected>Seleccione un estatus</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusunit">Estatus de Caja</label>
                                    <input type="text" readonly class="form-control" id="statusunit"
                                           name="statusunit">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="unitstatus">Estatus nuevo </label>
                                    <select name="unitstatus" class="form-control select2">
                                        <option value="" selected>Seleccione un estatus</option>
                                        @foreach($unitstatus as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control" id="shipmentid"
                                           name="shipmentid">
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
                        $("#statusidact").attr("value", values[0].status);
                        $("#econumber").attr("value", values[0].econumber);
                        $("#folioid").attr("value", values[0].folio);
                        $("#carriername").attr("value", values[0].linea);
                        $("#foliotruck").attr("value", values[0].foliotruck);
                        $("#operatorname").attr("value", values[0].operatorname);
                        $("#razonid").attr("value", values[0].razonid);
                        $("#statusunit").attr("value", values[0].statusunit);
                        $("#shipmentid").attr("value", values[0].shipmentid);
                    }
                });
            }
        });
    </script>
@endsection
