@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Cajas autorizadas para salir del patio
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="GET" id="search" role="form">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="">
                                            @include('buttons.search')
                                        </a>
                                        <a>
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Refrescar la pagina"
                                                    onclick="reloadtable();">
                                                <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                                <span style="font-size: 11px;"> Refrescar</span>
                                            </button>
                                        </a>
                                        <a href="{{route('home')}}">
                                            @include('buttons.cancel')
                                        </a>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Embarque / Recibo</label>
                                            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                                   onKeyUp=this.value=this.value.toUpperCase()
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="folionumber">Número de folio</label>
                                            <input type="text" class="form-control" id="folionumber" name="folionumber"
                                                   onKeyUp=this.value=this.value.toUpperCase()
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="barcode">Código de barra</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode"
                                                   onKeyUp=this.value=this.value.toUpperCase()
                                            >
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-authorized" class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Folio</th>
                                    <th class="bg-inverse text-white">Linea de transporte</th>
                                    <th class="bg-inverse text-white">Placas de caja</th>
                                    <th class="bg-inverse text-white">Eco. de caja</th>
                                    <th class="bg-inverse text-white">Nombre del operador</th>
                                    <th class="bg-inverse text-white">Celular</th>
                                    <th class="bg-inverse text-white">C.Barra</th>
                                    <th class="bg-inverse text-white">Embarque/Recibo</th>
                                    <th class="bg-inverse text-white">F.Entrada</th>
                                    <th class="bg-inverse text-white">Concepto de entrada</th>
                                    <th class="bg-inverse text-white">Salida Autorizada</th>
                                    <th class="bg-inverse text-white">Tiempo Transcurrido</th>
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

@section('css')

@endsection


@section('script')
    @include('Yards.ReceiptUnits.OutPuts.table')
    <script>
        function reloadtable() {
            $('#table-authorized').DataTable().ajax.reload();
        }
    </script>
@endsection
