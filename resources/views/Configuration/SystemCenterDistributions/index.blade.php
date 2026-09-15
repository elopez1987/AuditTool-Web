@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{ ui_label('distribution_center', 'Centro de distribución') }}
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('systemcenterdistributions.create')
                                    <a href="{{route('systemcenterdistributions.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a href="{{route('systemcenterdistributions.index')}}">
                                    @include('buttons.reload')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            @if(ui_visible('name_center'))
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">
                                            {{ ui_label('name_center','Nombre del centro') }}
                                            @if(ui_required('name_center'))
                                                <span style="color: red">*</span>
                                            @endif
                                        </label>
                                        <input
                                            type="{{ ui_type('name_center', 'text') }}"
                                            class="form-control"
                                            id="name"
                                            name="name"
                                            aria-describedby="name"
                                            @if(ui_required('name_center')) required @endif
                                        >
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-center" class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">{{ ui_label('centerid', 'C.Distribución') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('name', 'Nombre') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('description', 'Descripción') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('yard_capacity', 'Capacidad de Patio') }}</th>
{{--                                    <th class="bg-inverse text-white">{{ ui_label('wms_url', 'Url Web Service WMS') }}</th>--}}
{{--                                    <th class="bg-inverse text-white">{{ ui_label('wms_user', 'Usuario Web Service WMS') }}</th>--}}
{{--                                    <th class="bg-inverse text-white">{{ ui_label('wms_password', 'Contraseña Web Service WMS') }}</th>--}}
                                    <th class="bg-inverse text-white">{{ ui_label('status', 'Estatus') }}</th>
                                    <th class="bg-inverse text-white">{{ ui_label('options', 'Opciones') }}</th>
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
    @include('Configuration.SystemCenterDistributions.partials.table')
@endsection
