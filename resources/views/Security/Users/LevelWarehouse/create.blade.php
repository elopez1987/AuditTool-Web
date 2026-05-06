@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignación de accesos a bodegas.
                </div>
                <div class="card-body">
                    <form method="POST" action="{!! url('storeLevelWarehouse/' . $user->uuiduser) !!}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securityusers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card-input">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="nameuser">Nombre <span style="color: red">*</span></label>
                                                <input type="text" class="form-control" readonly id="nameuser"
                                                       name="nameuser"
                                                       value="{{$user->name}}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="username">Nombre de usuario <span
                                                        style="color: red">*</span></label>
                                                <input type="text" class="form-control" readonly id="username"
                                                       name="username"
                                                       value="{{$user->username}}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-input">
                                    @foreach($centers as $center)
                                        <div class="row">
                                            <div class="col-md-10">
                                                <table class="table table-hover table-bordered table-condensed tablegeneral">
                                                    <thead>
                                                    <tr>
                                                        <th class="bg-inverse text-white"></th>
                                                        <th class="bg-inverse text-white">C.Distribución</th>
                                                        <th class="bg-inverse text-white">Bodega</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($warehouses as $warehouse)
                                                        @if ($center->centerid == $warehouse->centerid)
                                                            <tr>
                                                                <td class="switch">
                                                                    <input
                                                                        type="checkbox"
                                                                        value="{{$warehouse->warehouseid}}"
                                                                        @if(!empty($access))
                                                                            @foreach ($access as $acce)
                                                                                @if ($acce->warehouseid == $warehouse->warehouseid)
                                                                                    checked
                                                                        @endif
                                                                        @endforeach
                                                                        @endif
                                                                        name="warehouse[]"
                                                                        data-size="mini" data-on-color="success"
                                                                        data-off-color="danger"
                                                                        data-on-text="<i class='ti ti-check'></i>"
                                                                        data-off-text="<i class='ti ti-close'></i>"
                                                                    >
                                                                </td>
                                                                <td >{{$warehouse->centerdistribution->name}}</td>
                                                                <td>{{$warehouse->name}}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection


@section('script')
    @include('layouts.Partials.DataTable')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
@endsection

