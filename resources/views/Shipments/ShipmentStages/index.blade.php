@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modulo de asignación de cortina a embarque.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                {!! Field::select('turnid',$turns,['class' => 'select2']) !!}
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                {!! Field::text('shipmentid',["onKeyUp"=>"this.value=this.value.toUpperCase();"]) !!}
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-stages" class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Fecha</th>
                                    <th class="bg-inverse text-white">Turno</th>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Cliente</th>
                                    <th class="bg-inverse text-white">Precarga</th>
                                    <th class="bg-inverse text-white">Cortina 01</th>
                                    <th class="bg-inverse text-white">Cortina 02</th>
                                    <th class="bg-inverse text-white">Cortina 03</th>
                                    <th class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    Control de patios.
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Shipments.ShipmentStages.partials.table')
    <script>
        function reloadtable() {
            $('#table-stages').DataTable().ajax.reload();
        }
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery(".numeric").on('input', function (evt) {
                jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
            });
        });
    </script>
@endsection
