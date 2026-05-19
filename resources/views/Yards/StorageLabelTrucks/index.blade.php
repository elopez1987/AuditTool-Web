@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Etiquetas de caja
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagelabeltrucks.create')
                                    <a href="{{route('storagelabeltrucks.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                @include('buttons.reloadajax')
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="label">C.Barra</label>
                                    <input type="text" class="form-control" id="label" name="label"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placas</label>
                                    <input type="text" class="form-control" id="boxnumber" name="boxnumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="typeid">Tipo de registro<span style="color: red">*</span></label>
                                    <select name="typeid" id="typeid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        <option value="BX">CAJA</option>
                                        <option value="TR">TRACTO</option>
                                    </select>
                                </div>
                            </div>
                            <x-select2
                                    name="carrierid"
                                    label="Lineas de transporte"
                                    :options="$carriers"
                                    valueField="id"
                                    textField="name"
                                    :required="false"
                            />
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <table id="table-label" class="table table-bordered  table-condensed">
                                                <thead>
                                                <tr>
                                                    <th class="bg-inverse text-white">C.Distribución</th>
                                                    <th class="bg-inverse text-white">C.Barra</th>
                                                    <th class="bg-inverse text-white">Linea</th>
                                                    <th class="bg-inverse text-white">Placas</th>
                                                    <th class="bg-inverse text-white">Economico</th>
                                                    <th class="bg-inverse text-white">Tipo de Registro</th>
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
                </div>
                <div class="card-footer">
                    @can('storagelocations.importlocation')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16 mr-2">
                                <span> Importar Etiquetas </span>
                            </button>
                        </a>
                        @include('Yards.StorageLabelTrucks.uploadfile')
                    @endcan
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    @include('Yards.StorageLabelTrucks.partials.table')
    <script>
        function reloadtable() {
            $('#table-label').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#carrierid ,#typeid").change(function () {
            reloadtable();
        });
    </script>

@endsection
