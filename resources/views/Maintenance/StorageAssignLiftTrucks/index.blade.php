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
                                       class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Tipo de equipo</th>
                                        <th class="bg-inverse text-white">Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($types as $truck)
                                            <tr>
                                                <td>{{$truck->name}}</td>
                                                <td>
                                                    @can('storageassignlifttrucks.edit')
                                                        <a href="{{url('storageassignlifttrucks/' . $truck->statusid . '/edit')}}" data-toggle="tooltip" data-placement="top"
                                                           title="Modificar Registro">
                                                            <img src="{{asset('/ico/addreceipt.png')}}" alt="" class="img-16 ml-1">
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
    <script>
        function reloadtable() {
            $('#table-folio').DataTable().ajax.reload();
        }
    </script>
@endsection
