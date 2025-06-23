@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    Datos generales Inventario
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('storagescheduleinverse.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid"
                                           onKeyUp=this.value=this.value.toUpperCase() readonly
                                           value="{{$storagescheduleinverse->order->shipmentid}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="customerid">Cliente</label>
                                    <input type="text" class="form-control"  id="customerid" name="customerid"
                                           onKeyUp=this.value=this.value.toUpperCase() readonly
                                           value="{{$storagescheduleinverse->order->customer->name}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carrierid">Linea de transporte</label>
                                    <input type="text" class="form-control"  id="carrierid" name="carrierid"
                                           onKeyUp=this.value=this.value.toUpperCase() readonly
                                           value="{{$storagescheduleinverse->order->carrier->name}}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Modificar Cita
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{url('storagescheduleinverseinventory/' . $storagescheduleinverse->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="card-input">
                            <table class="table table-bordered table-condensed table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>F.Solicitud Zare</th>
                                    <th>Zare</th>
                                    <th>F. Recepción NE</th>
                                    <th>Comentario</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $item)
                                    <tr>
                                        <th width="10%">
                                            <input type="text" class="form-control" readonly value="{{$item->delivery}}"
                                                   name="delivery[]">
                                        </th>
                                        <th>
                                            {{$item->requestzare}}
                                        </th>
                                        <th>
                                            {{$item->zare}}
                                        </th>
                                        <th>
                                            {{$item->requestne}}
                                        </th>
                                        <th>
                                            <input type="text" name="note[]" class="form-control"
                                                   value="{{$item->note}}">
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script
        src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script>
        $('#requestadjust,#replyadjust').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
    </script>
@endsection


