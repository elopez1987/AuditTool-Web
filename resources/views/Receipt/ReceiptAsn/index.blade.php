@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Registro de descargas
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('receiptasn.create')
                                    <a href="{{route('receiptasn.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menureceipt')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Recibo</label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="oc">Orden de compra</label>
                                    <input type="text" class="form-control"  id="oc" name="oc">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Folio</label>
                                    <input type="text" class="form-control"  id="folionumber" name="folionumber">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus <span style="color: red">*</span></label>
                                    <select name="statusid" id="statusid"  class="form-control select2">
                                        <option value="" selected>Selecione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-receipts"
                                   class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
{{--                                    <th class="bg-inverse text-white">Turno</th>--}}
                                    <th class="bg-inverse text-white">Folio</th>
                                    <th class="bg-inverse text-white">Recibo</th>
                                    <th class="bg-inverse text-white">Factura</th>
                                    <th class="bg-inverse text-white">O. de Compra</th>
                                    <th class="bg-inverse text-white">Proveedor</th>
                                    <th class="bg-inverse text-white">Tipo de entrada</th>
                                    <th class="bg-inverse text-white">Origen</th>
                                    <th class="bg-inverse text-white">Linea</th>
                                    <th class="bg-inverse text-white">Creado por</th>
                                    <th class="bg-inverse text-white">Cita de descarga</th>
                                    <th class="bg-inverse text-white">Inicia Descarga</th>
                                    <th class="bg-inverse text-white">Termina Descarga</th>
                                    <th class="bg-inverse text-white">F.Creación</th>
                                    <th class="bg-inverse text-white">F.Ingreso en SAP</th>
                                    <th class="bg-inverse text-white">F.Cancelación</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('Receipt.ReceiptAsn.partials.table')
    <script>
        function reloadtable() {
            $('#table-receipts').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#statusid").change(function () {
            reloadtable();
        });
        $("#folionumber,#oc,#shipmentid").blur(function () {
            reloadtable();
        });
    </script>
@endsection
