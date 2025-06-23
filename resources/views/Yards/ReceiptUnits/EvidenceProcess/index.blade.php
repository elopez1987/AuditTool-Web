@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Subir Evidencia de Proceso
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('receiptunits.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-input">
                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Folio</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase() readonly
                                                   value="{{$receiptunit->id}}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Código de barra</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase() readonly
                                                   value="{{$receiptunit->barcode}}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Embarque / Recibo</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase() readonly
                                                   value="{{$receiptunit->shipmentid}}"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Linea de transporte</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase() readonly
                                                   value="{{$receiptunit->carrier ? $receiptunit->carrier->name : ''}}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Nombre de Operador</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase() readonly
                                                   value="{{$receiptunit->operatorname}}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Placa de caja</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase() readonly
                                                   value="{{$receiptunit->boxnumber}}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Concepto de entrada</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase() readonly
                                                   value="{{$receiptunit->event->name}}"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-input">
                        @php
                            $exists = in_array('APP-02', $parameterCenter);
                        @endphp
                        @if ($exists)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            @if($folionumberType->auxiliary01 == '')
                                                <form method="POST" action="{{url('saveTypeShipmentEvidence')}}"
                                                      enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <input type="text" value="{{$receiptunit->id}}"
                                                                       class="hide" name="folioid">
                                                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-4">
                                                                    <div class="form-group">
                                                                        <label for="typeshipment">Seleccione el proceso:
                                                                            <span
                                                                                style="color: red">*</span></label>
                                                                        <select name="typeshipment" id="typeshipment"
                                                                                class="form-control select2">
                                                                            <option value="TDIRECTO">Directa
                                                                            </option>
                                                                            <option value="TIROS">Por Tiros
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-6 col-sm-12 hide"
                                                                     id="ntirosdiv">
                                                                    <div class="form-group">
                                                                        <label for="ntiros">Cantidad de Tiros
                                                                            @error("ntiros")
                                                                            <span
                                                                                class="message-error">{{$message}}</span>
                                                                            @enderror
                                                                            <span
                                                                                style="color: red">*</span></label>
                                                                        <input type="text"
                                                                               class="form-control @error('ntiros') is-invalid @enderror"
                                                                               id="ntiros"
                                                                               name="ntiros" maxlength="10"
                                                                               aria-describedby="ntiros"
                                                                               onKeyUp=this.value=this.value.toUpperCase();
                                                                               value="{{old('ntiros')}}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row button-group mt-3">
                                                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-4">
                                                                    <a href="">
                                                                        <button type="submit"
                                                                                class="btn waves-effect waves-light btn-outline-success btn-block btn-md">
                                                                            <img src="{{asset('/pics/adaceptar.png')}}"
                                                                                 alt="" class="img-16">
                                                                            <span
                                                                                style="font-size: 11px;"> Guardar</span>
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                            @if($folionumberType->auxiliary01 != '')
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <table
                                                            class="table table-condensed table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Concepto</th>
                                                                <th>Foto</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if($folionumberType->auxiliary01 == 'TIROS')
                                                                @foreach($listCheckList as $item)
                                                                    <tr>
                                                                        <td>{{$item->id }}</td>
                                                                        <td>{{$item->name}}</td>
                                                                        <td>
                                                                            <a href=""
                                                                               data-target="#modal-file-{{$item->id}}"
                                                                               data-toggle="modal">
                                                                                <img
                                                                                    src="{{asset('/ico/camera02.png')}}"
                                                                                    alt=""
                                                                                    class="img-16 ml-2">
                                                                            </a>
                                                                            @include('Yards.ReceiptUnits.EvidenceProcess.uploadfile')
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            @if($folionumberType->auxiliary01 == 'TDIRECTO')
                                                                @foreach($listCheckList as $item)
                                                                    <tr>
                                                                        <td>{{$item->statusid }}</td>
                                                                        <td>{{$item->name}}</td>
                                                                        <td>
                                                                            <a href=""
                                                                               data-target="#modal-file-{{$item->statusid}}"
                                                                               data-toggle="modal">
                                                                                <img
                                                                                    src="{{asset('/ico/camera02.png')}}"
                                                                                    alt=""
                                                                                    class="img-16 ml-2">
                                                                            </a>
                                                                            @include('Yards.ReceiptUnits.EvidenceProcess.uploadfiledirecto')
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card-input">
                                        <div class="col-lg-6">
                                            <h4 class="card-title" style="font-size: 12px;">Evidencia de proceso</h4>
                                            <div class="card-foto" style="border-radius: 10px">
                                                <form action="{{ url('saveEvidenceProcessDirectWeb/'. $receiptunit->id .'/upload') }}"
                                                    class="dropzone" id="myAwesomeDropzone">
                                                    {{ csrf_field() }}
                                                    <div class="dz-message" data-dz-message>
                                                        <span style="font-size: 12px;color: #4db6ac;font-weight: 500">
                                                            Toca para abrir la camara o el selector de imagenes. <br><br>
                                                            <i class="fa fa-camera fa-4x"></i>
                                                        </span>
                                                    </div>
                                                    <div class="fallback">
                                                        <input name="file" type="file" multiple/>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/dropzone/dist/min/dropzone.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/dropzone/dist/dropzone.js')}}"></script>
    <script>
        Dropzone.options.myAwesomeDropzone = {
            paramName: "file",
            maxFiles: 10,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFilesize: 8,
        };
    </script>

    <script>
        $("#typeshipment").change(event => {
            let typeshipment = document.getElementById("typeshipment").value;
            if (typeshipment === 'TIROS') {
                $("#ntirosdiv").show();
            }
            if (typeshipment === 'TDIRECTO') {
                $("#ntirosdiv").hide();
            }
        });
    </script>

@endsection
