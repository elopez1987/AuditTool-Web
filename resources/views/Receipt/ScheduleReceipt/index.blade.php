@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Horario de descarga
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-10">
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
                                <hr class="mt-2">
                                <div class="card-input">
                                    <div class="row">
                                        <x-select2
                                                name="warehouseid"
                                                label="Bodega."
                                                :options="$warehouses"
                                                valueField="warehouseid"
                                                textField="name"
                                                placeholder="Seleccione una bodega"
                                        />
                                        <x-text-input
                                                name="shipmentid"
                                                label="Número de recibo."
                                                type="text"
                                                placeholder="Número de recibo o contenedor"
                                                colClass="col-lg-2 col-md-4 col-md-12"
                                        />
                                        <x-select2
                                                name="statusid"
                                                label="Estatus del recibo."
                                                :options="$status"
                                                valueField="statusid"
                                                textField="name"
                                                placeholder="Seleccione una estatus"
                                        />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="card-input">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <table id="tablereceipt" class="table table-condensed table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Bodega</th>
                                <th>Recibo</th>
                                <th>Cortina</th>
                                <th>Estatus del recibo</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer">
                        @can('receiptasn.clearschedulle')
                            <a href="" data-target="#modal-clearschedulle" data-toggle="modal">
                                <button type="submit"
                                        class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/refuseunit.png')}}" alt=""
                                         class="img-16 ml-1">
                                    <span class="text-button"> Limpiar horario</span>
                                </button>
                            </a>
                            @include('Receipt.ScheduleReceipt.clearschedulle')
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')

@endsection

@section('script')

    @include('Receipt.ScheduleReceipt.partials.table')
    <script>
        function reloadtable() {
            $('#tablereceipt').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#providerid,#statusid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>

@endsection
