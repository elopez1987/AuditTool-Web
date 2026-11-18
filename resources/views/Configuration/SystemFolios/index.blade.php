@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Folios de sistema
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('systemfolios.create')
                                    <a href="{{route('systemfolios.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reload')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Descripción </label>
                                        <input type="text" class="form-control" id="description" name="description"
                                               aria-describedby="description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-8 col-md-12">
                                <table id="table-folio"
                                       class="table table-bordered  table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">C.Distribución</th>
                                        <th class="bg-inverse text-white">Nombre</th>
                                        <th class="bg-inverse text-white">Folio</th>
                                        <th class="bg-inverse text-white">Auxiliar 01</th>
                                        <th class="bg-inverse text-white">Auxiliar 02</th>
{{--                                        <th class="bg-inverse text-white">Auxiliar 03</th>--}}
{{--                                        <th class="bg-inverse text-white">Auxiliar 04</th>--}}
{{--                                        <th class="bg-inverse text-white">Auxiliar 05</th>--}}
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
    @include('Configuration.SystemFolios.partials.table')
    <script>
        function reloadtable() {
            $('#table-folio').DataTable().ajax.reload();
        }
    </script>
@endsection
