@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                Errores de inventario fiscal - {{$inventoryhead->name}}
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a >
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{url('inventoryheads/' . $inventoryhead->inventory_uuid)}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control"  id="name" name="name" value="{{old('name')}}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card">
                                            <div class="card-header">
                                                ERRORES DE CONTEO
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered table-condensed" id="inventoryDetailTableErrors"
                                                       width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Pasillo</th>
                                                        <th>Cedula</th>
                                                        <th>Ubicación</th>
                                                        <th>Sku 01</th>
                                                        <th>Catidad 01</th>
                                                        <th>Sku 02</th>
                                                        <th>Catidad 02</th>
                                                        <th>Tipo de conteo</th>
                                                        <th>Ajustar</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="card-footer"></div>
                                        </div>

                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                Concentrado de errores
                                            </div>
                                            <div class="card-body">
                                               <table class="table table-hover table-bordered table-condensed tablegeneral" >
                                                   <thead>
                                                    <tr>
                                                        <th>Cédula</th>
                                                        <th>Cantidad de errores</th>
                                                        <th>Imprimir</th>
                                                    </tr>
                                                   </thead>
                                                   <tbody>
                                                    @foreach($cedulas as $cedula)
                                                        <tr>
                                                            <td>{{$cedula->cardname}}</td>
                                                            <td>{{$cedula->totalerrores}}</td>
                                                            <td>
                                                                <a href="{{url('printCardErrors/' . $cedula->inventory_card_id )}}" class="ml-2 mr-2 ">
                                                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">
                                                                        <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-2 mr-2">
                                                                        <span> Imprimir</span>
                                                                    </button>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                   </tbody>
                                               </table>
                                            </div>
                                            <div class="card-footer">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@include('layouts.Partials.Errors')
@endsection

@section('script')
    @include('Inventory.InventoryHead.partials.table-locations-errors')
    <script>
        function reloadtable() {
            $('#inventoryDetailTableErrors').DataTable().ajax.reload();
        }
    </script>
    @include('layouts.Partials.DataTable')
@endsection
