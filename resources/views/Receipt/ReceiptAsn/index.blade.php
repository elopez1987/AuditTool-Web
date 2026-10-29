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
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <x-select2
                                    name="warehouseid"
                                    label="Centros de distribución"
                                    :options="$warehouses"
                                    valueField="warehouseid"
                                    textField="name"
                            />
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">{{ ui_label('receiptasn_shipmentid_index','Recibo') }} </label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="oc"> {{ ui_label('receiptasn_oc_index','Orden de compra') }}  </label>
                                    <input type="text" class="form-control"  id="oc" name="oc">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">{{ ui_label('receiptasn_folionumber_index','Número de folio') }}</label>
                                    <input type="text" class="form-control"  id="folionumber" name="folionumber">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">{{ ui_label('receiptasn_statusid_index','Estatus') }} </label>
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
                                   class="table table-bordered  table-condensed table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">{{ ui_label('receiptasn_cdistribucion_index','C.Distribución') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('receiptasn_warehouse_index','Bodega') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('receiptasn_shipmentid_index','Recibo') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('receiptasn_provider_index','Ruta') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('receiptasn_delivery_index','Operador') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('receiptasn_statusid_index','Estatus') }}</th>
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
        $("#statusid,#warehouseid").change(function () {
            reloadtable();
        });
        $("#folionumber,#oc,#shipmentid").blur(function () {
            reloadtable();
        });
    </script>
@endsection
