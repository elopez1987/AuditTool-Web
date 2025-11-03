@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Errores de check list bloqueados
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('storagelifttrucks.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-1">
                    <div class="row">
                        <div class="col-lg-8">
                            <table class="table table-condensed table-bordered table-hover tablegeneral">
                                <thead>
                                <tr>
                                    <th>Centro de distribución</th>
                                    <th>Número de serie</th>
                                    <th>Usuario</th>
                                    <th>F.Creación</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($errorLogs as $error)
                                    <tr>
                                        <td>{{$error->center ? $error->center->name : ''}}</td>
                                        <td>{{$error->serialnumber}}</td>
                                        <td>{{$error->user ? $error->user->name : ''}}</td>
                                        <td>{{$error->created_at}}</td>
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
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection

