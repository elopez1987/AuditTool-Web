@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Tablas del sistema
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="tableid">Tabla <span style="color: red">*</span></label>
                                    <input type="text" class="form-control"  name="tableid" aria-describedby="tableid">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-10">
                            <table id="table-trucks" class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Tabla</th>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Nota</th>
                                    <th class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('systemtables.importtables')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span style="font-size: 11px;"> Armar Tablas </span>
                            </button>
                        </a>
                        @include('Configuration.SystemTables.uploadfile')
                    @endcan
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Configuration.SystemTables.partials.table')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js">
    </script>
    <script>
        function reloadtable() {
            $('input[type="text"]').val('');
            $('#table-trucks').DataTable().ajax.reload();
        }
    </script>
@endsection
