@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Esquemas del sistema
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('systemschemas.create')}}">
                                    @include('buttons.add')
                                </a>
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reload')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="schemaid">Esquema</label>
                                        <input type="text" class="form-control" id="schemaid" name="schemaid"
                                               maxlength="100"
                                               aria-describedby="schemaid" value="{{old('schemaid')}}"
                                               onKeyUp=this.value=this.value.toUpperCase()
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Nombre </label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               maxlength="100"
                                               aria-describedby="name" value="{{old('name')}}"
                                               onKeyUp=this.value=this.value.toUpperCase()
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-6">
                                <table id="table-schema" class="table table-bordered table-striped table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Nombre</th>
                                        <th class="bg-inverse text-white">Descripción</th>
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

@endsection

@section('script')
    @include('Configuration.SystemSchemas.partials.table')
    <script>
        function reloadtable() {
            $('#table-schema').DataTable().ajax.reload();
        }
    </script>
@endsection
