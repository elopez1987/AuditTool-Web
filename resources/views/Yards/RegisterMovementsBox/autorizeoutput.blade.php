@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    Autoriza la salida de la unidad del patio.
                </div>
                <form method="POST" action="{{url('registermovementbox')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-input">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="folionumber">Número de Folio</label>
                                                <input type="text"
                                                       class="form-control @error('folionumber') is-invalid @enderror"
                                                       id="folionumber" required
                                                       name="folionumber" maxlength="20"
                                                       value="{{ old('folionumber') }}">
                                                @error('folionumber')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="barcode">Código de barra</label>
                                                <div class="input-group">
                                                    <input type="number" readonly class="form-control numeric"
                                                           id="barcode"
                                                           name="barcode"
                                                           value="{{ old('barcode') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6  col-xs-12">
                                            <div class="form-group">
                                                <label for="linea">Línea de transporte</label>
                                                <input type="text" class="form-control" readonly id="linea"
                                                       name="linea"
                                                       value="{{ old('linea') }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6  col-xs-12">
                                            <div class="form-group">
                                                <label for="econumber">Eco. De caja</label>
                                                <input type="text" class="form-control" readonly id="econumber"
                                                       name="econumber"
                                                       value="{{ old('econumber') }}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-4 col-md-6  col-xs-12">
                                            <div class="form-group">
                                                <label for="boxnumber">Placa De caja</label>
                                                <input type="text" class="form-control" readonly id="boxnumber"
                                                       name="boxnumber"
                                                       value="{{ old('boxnumber') }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6  col-xs-12">
                                            <div class="form-group">
                                                <label for="shipmentid">Embarque</label>
                                                <input type="text" class="form-control" readonly id="shipmentid"
                                                       name="shipmentid"
                                                       value="{{ old('shipmentid') }}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $exists = !empty($parameter) && in_array('SWH-24', $parameter);
                                @endphp
                                @if ($exists)
                                    <div class="card-input">
                                        <div id="location">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6  col-xs-12 mt-2">
                                                        <div class="form-group">
                                                            <label for="zoneid">Zonas<span
                                                                    style="color: red">*</span></label>
                                                            <select name="zoneid" id='zoneid'
                                                                    class="form-control select2">
                                                                <option value="" selected>Seleccione una opción</option>
                                                                @foreach($zones as $model)
                                                                    <option
                                                                        value="{{$model->id}}" {{ old('zoneid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6  col-xs-12 mt-2">
                                                        <div class="form-group">
                                                            <label for="locationid">Ubicación<span
                                                                    style="color: red">*</span></label>
                                                            <select name="locationid" id='locationid'
                                                                    class="form-control select2">
                                                                <option value="" selected>Seleccione una opción</option>
                                                                @foreach($locations as $model)
                                                                    <option
                                                                        value="{{$model->id}}" {{ old('locationid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6  col-xs-12 mt-2">
                                                        <div class="form-group">
                                                            <label for="unitstatusid">Estatus de la unidad<span
                                                                    style="color: red">*</span></label>
                                                            <select name="unitstatusid" id="unitstatusid"
                                                                    class="form-control select2">
                                                                <option value="" selected>Seleccione una opción</option>
                                                                @foreach($unitstatus as $model)
                                                                    <option
                                                                        value="{{$model->id}}" {{ old('unitstatusid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @php
                            $exists = !empty($parameter) &&  in_array('SWH-40', $parameter);
                        @endphp
                        @if ($exists)
                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <div class="card-input">
                                        <table
                                            class="table table-condensed table-hover table-striped table-bordered">
                                            <thead>
                                            <th>Descripción</th>
                                            <th>SI</th>
                                            <th>NO</th>
                                            </thead>
                                            <thead>
                                            @foreach($checkAS as $item)
                                                <tr>
                                                    <td class="hide"><input type="text"
                                                                            value="{{$item->checklistid}}"
                                                                            id="checklistid[]" name="checklistid[]"
                                                                            class="hide"></td>
                                                    <td class="hide"><input type="text" value="{{$item->id}}"
                                                                            id="checklitsdetailid[]"
                                                                            name="checklitsdetailid[]" class="hide">
                                                    </td>
                                                    <td>{{$item->description}}</td>
                                                    <td><input type="radio" id="response[]" @if($exists) required
                                                               @endif value="1" name="response[]-{{$item->id}}">
                                                    </td>
                                                    <td><input type="radio" id="response[]" @if($exists) required
                                                               @endif value="0" name="response[]-{{$item->id}}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Comentario</label>
                                    <textarea class="form-control" rows="10" name="comment"
                                              placeholder="Ingrese un comentario corto..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-1 col-md-4 col-sm-12 col-xs-12">
                                <input type="submit" value="Autorizar Salida" name="btn" id="2"
                                       class="btn btn-block btn-block btn-outline-success">
                            </div>
                            <div class="col-lg-1 col-md-4 col-sm-12 col-xs-12">
                                <a href="{{url('menuyard')}}">
                                    <input type="button" value="Cancelar"
                                           class="btn btn-block btn-block btn-outline-danger">
                                </a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    {{--    <script>--}}
    {{--        var quill = new Quill('#comment', {--}}
    {{--            theme: 'snow'--}}
    {{--        });--}}
    {{--    </script>--}}
    <script>
        $("#zoneid").change(event => {
            $.get(`./getLocationForZone/${event.target.value}`, function (res) {
                $("#locationid").empty();
                res.forEach(element => {
                    $("#locationid").append(`<option value=${element.id}> ${element.name} </option>`);
                });
            });
        });
    </script>
    <script>
        $("#folionumber").blur(function () {
            if ($("#folionumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el código de barra!',
                })
            } else {
                $.getJSON('./getDataInvoiceForFolio/' + $("#folionumber").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                        })
                    } else {
                        $("#boxnumber").attr("value", values[0].boxnumber);
                        $("#folionumber").attr("value", values[0].folio);
                        $("#shipmentid").attr("value", values[0].shipmentid);
                        $("#econumber").attr("value", values[0].econumber);
                        $("#linea").attr("value", values[0].linea);
                        $("#barcode").attr("value", values[0].barcode);
                    }
                });
            }
        });
    </script>
@endsection
