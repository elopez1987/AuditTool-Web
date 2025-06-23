@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogos de inventario
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('inventorypartnumbers.create')
                                    <a href="{{route('inventorypartnumbers.create')}}">
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
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="prtnum">Numero de parte</label>
                                    <input type="text" class="form-control" id="prtnum" name="prtnum">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-partnumber" class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Sku</th>
                                    <th class="bg-inverse text-white">Descripción</th>
                                    <th class="bg-inverse text-white">Estandar</th>
                                    <th class="bg-inverse text-white">Tiempo de Vida</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Categoria 01</th>
                                    <th class="bg-inverse text-white">Categoria 02</th>
                                    <th class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('inventorypartnumbers.importpart')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span class="text-button"> Importar catalogo </span>
                            </button>
                        </a>
                        @include('Inventory.InventoryPartnumbers.uploadfile')
                    @endcan
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Inventory.InventoryPartnumbers.partials.table')
    <script>
        function reloadtable() {
            $('#table-partnumber').DataTable().ajax.reload();
        }
    </script>
@endsection
