@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignacion de empleados a tipos de equipos.
                </div>
                <div class="card-body">
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-6 col-xs-12">
                                <table id="table-typelifts"
                                       class="table table-bordered table-condensed table-hover tablegeneral">
                                    <thead>
                                    <tr>
                                        <th>Tipo de equipo</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($types as $truck)
                                        <tr>
                                            <td>{{$truck->name}}</td>
                                            <td>
                                                @can('storageassignlifttrucks.edit')
                                                    <a href="{{url('storageassignlifttrucks/' . $truck->statusid . '/edit')}}" class="btn waves-effect waves-light btn-outline-info btn-md">
                                                        <i class="fa fa-truck ml-2 mr-2"></i> <spam class="text-capitalize">Asignar</spam>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
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
    @include('layouts.Partials.DataTable')
    <script>
        function reloadtable() {
            $('#table-folio').DataTable().ajax.reload();
        }
    </script>
@endsection
