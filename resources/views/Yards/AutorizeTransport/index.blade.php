@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-md-12">
            <div class="card">
                <div class="card-header">
                    Autorizar reutilización de unidad
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-input">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="folionumber" class="form-check-label"><b>Folio de patios</b></label>
                                            <input type="text" class="form-control"  id="folionumber" name="folionumber"
                                            value="{{ old('folionumber') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="barcode">Numero de Etiqueta</label>
                                            <div class="input-group">
                                                <input type="number" readonly class="form-control numeric" id="barcode"
                                                       name="barcode"
                                                       aria-describedby="basic-addon1" value="{{ old('barcode') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="operatorname">Nombre de operador</label>
                                            <input type="text" class="form-control" readonly id="operatorname"
                                                   name="operatorname"
                                                   value="{{ old('operatorname') }}"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="boxnumber">Placa de caja </label>
                                            <input type="text" class="form-control" readonly id="boxnumber"
                                                   name="boxnumber"
                                                   value="{{ old('boxnumber') }}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="linea">Linea de transporte</label>
                                            <input type="text" class="form-control" readonly id="linea" name="linea"
                                                   value="{{ old('linea') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="razonid">Concepto de entrada</label>
                                            <input type="text" class="form-control" readonly id="razonid" name="razonid"
                                                   value="{{ old('razonid') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-input">
                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="centerid">Centros de distribución <span
                                                    style="color: red">*</span></label>
                                            <select name="centerid" required id="centerid" class="form-control select2">
                                                <option value="" selected>Seleccione una opción</option>
                                                @foreach($centers as $center)
                                                    <option value="{{$center->centerid}}"> {{$center->name}} </option>
                                                @endforeach
                                            </select>
                                            @error('centerid')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                                            <select name="warehouseid" required id="warehouseid"
                                                    class="form-control select2">
                                                <option value="" selected>Seleccione una opción</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option
                                                        value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                                                @endforeach
                                            </select>
                                            @error('warehouseid')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="centerid">Concepto de Registro <span style="color: red">*</span></label>
                                            <select name="razonstatusid" required id="razonstatusid"
                                                    class="form-control select2">
                                                <option value="" selected>Seleccione una opción</option>
                                                @foreach($rason as $model)
                                                    <option value="{{$model->id}}"> {{$model->name}} </option>
                                                @endforeach
                                            </select>
                                            @error('razonstatusid')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <a>
                                <button class="btn waves-effect waves-light btn-outline-info btn-md" id="saveFolio">
                                    <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                    <span class="text-button"> Guardar</span>
                                </button>
                            </a>
                            <a href="{{route('home')}}">
                                <button type="button"
                                        class="btn waves-effect waves-light btn-outline-info btn-md ml-3"
                                        data-toggle="tooltip" data-placement="top"
                                        title="Regresar a la pagina principal">
                                    <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">
                                    <span class="text-button"> Cancelar</span>
                                </button>
                            </a>
                        </div>
                    </div>
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
        $("#folionumber").blur(function () {
            if ($("#folionumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el numero de Folio o Etiqueta!',
                })
            } else {
                $.getJSON('./receipt-units/' + $("#folionumber").val() + '/search', function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                        })
                    } else {
                        $("#folio").val(values.Data.id);
                        $("#operatorname").val(values.Data.operatorname);
                        $("#boxnumber").val(values.Data.boxnumber);
                        $("#barcode").val(values.Data.barcode);
                        $("#linea").val(values.Data.carriername);
                        $("#razonid").val(values.Data.razonname);
                    }
                });
            }
        });
    </script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#saveFolio').click(function (e) {
                e.preventDefault();

                const centerid = $("#centerid").val();
                const folionumber = $("#folionumber").val();
                const warehouseid = $("#warehouseid").val();
                const barcode = $("#barcode").val();
                const razonstatusid = $("#razonstatusid").val();

                if (!centerid || !warehouseid || !folionumber || !razonstatusid) {
                    Swal.fire({
                        type: 'warning',
                        title: 'Campos obligatorios',
                        text: 'Por favor, complete todos los campos antes de enviar el formulario.',
                        width: 350,
                    });
                    return;
                }

                $.ajax({
                    data: {
                        centerid: centerid,
                        warehouseid: warehouseid,
                        barcode: barcode,
                        folionumber: folionumber,
                        razonstatusid: razonstatusid,
                    },
                    url: '{!! route('autoizetransportstore') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        Swal.fire({
                            type: 'success',
                            title: `Folio creado : ${data.id}`,
                            text: `Fecha de creación : ${data.entrydate}`,
                            width: 350,
                        });
                        $('#barcode').val('');
                        $('#folio').val('');
                        $('#operatorname').val('');
                        $('#boxnumber').val('');
                        $('#carriername').val('');
                        $('#folionumber').val('');
                        $('#razonid').val('');
                        $('#linea').val('');
                    },
                    error: function (xhr) {
                        let message = 'Ocurrió un error al procesar la solicitud.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            message = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: message,
                            width: 350,
                        });
                    },
                });
            });
        });
    </script>
@endsection
