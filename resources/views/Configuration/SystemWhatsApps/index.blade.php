@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Configuración de WhatsApp
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('systemwhatsapps.create')
                                    <a href="{{route('systemwhatsapps.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a href="{{route('systemwhatsapps.index')}}">
                                    @include('buttons.reload')
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
                                    <label for="whatsappid">Clave de notificación </label>
                                    <input type="text" class="form-control" id="whatsappid" name="whatsappid"
                                           maxlength="100"
                                           aria-describedby="whatsappid" value="{{old('whatsappid')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Descripción </label>
                                    <input type="text" class="form-control" id="name" name="name" maxlength="100"
                                           aria-describedby="name" value="{{old('name')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-10 col-md-12" >
                            <table id="table-mail" class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Mensaje</th>
                                    <th>Descripción</th>
                                    <th>Auxiliar 01</th>
                                    <th>Auxiliar 02</th>
                                    <th>Auxiliar 03</th>
{{--                                    <th class="bg-inverse text-white">Auxiliar 04</th>--}}
{{--                                    <th class="bg-inverse text-white">Auxiliar 05</th>--}}
                                    <th>Estatus</th>
                                    <th>Opciones</th>
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

@section('script')
    @include('Configuration.SystemWhatsApps.partials.table')
@endsection
