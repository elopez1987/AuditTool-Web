@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de check list
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{route('checklistlifttrucks.index')}}">
                                @include('buttons.cancel')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="serialnumber">Número de serie</label>
                                <input type="text" class="form-control" id="serialnumber" readonly name="serialnumber"
                                       value="{{$checkList->serialnumber}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="serialnumber">Supervisor</label>
                                <input type="text" class="form-control" id="serialnumber" readonly name="serialnumber"
                                       value="{{$checkList->supervisor}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="serialnumber">Supervisor</label>
                                <input type="text" class="form-control" id="serialnumber" readonly name="serialnumber"
                                       value="{{$checkList->horometro}}"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">

                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                class="ti-home"></i></span> <span class="hidden-xs-down">Tabla de detalle</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                class="ti-user"></i></span> <span class="hidden-xs-down">Evidencia de check list principal</span></a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="view01" role="tabpanel">
                                    <div class="p-20">
                                        <table class="table table-condensed table-bordered table-striped tablegeneral">
                                            <thead>
                                            <tr>
                                                <th>Descripción</th>
                                                <th>Respuesta</th>
                                                <th>Evidencia</th>
                                                <th>Firma de responsable</th>
                                                <th>Responsable de liberación</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($errorsCheck as $error)
                                                <tr>
                                                    <td>{{$error->checklistdetail ? $error->checklistdetail->description : ''}}</td>
                                                    <td>{{$error->responsecheck}}</td>
                                                    <td>
                                                        @if(!empty($error->evidencepath))
                                                            <div class="el-card-item">
                                                                <div class="el-card-avatar el-overlay-1">
                                                                    <div class="el-overlay">
                                                                        <ul class="list-style-none el-info">
                                                                            <li class="el-item bg-grey">
                                                                                <a class="btn default btn-outline image-popup-vertical-fit el-link "
                                                                                   href="{{asset('storage/' . $error->evidencepath)}}"
                                                                                   target="_blank">
                                                                                    Ver
                                                                                    <i class="icon-magnifier"></i>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            Sin Evidencia
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($error->evidencekey))
                                                            <div class="el-card-item">
                                                                <div class="el-card-avatar el-overlay-1">
                                                                    <div class="el-overlay">
                                                                        <ul class="list-style-none el-info">
                                                                            <li class="el-item bg-grey">
                                                                                <a class="btn default btn-outline image-popup-vertical-fit el-link "
                                                                                   href="{{asset('storage/' . $error->evidencekey)}}"
                                                                                   target="_blank">
                                                                                    Ver
                                                                                    <i class="icon-magnifier"></i>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            Sin Evidencia
                                                        @endif
                                                    </td>
                                                    <td>{{$error->user ? $error->user->name : ''}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane  p-20" id="view02" role="tabpanel">
                                    <div class="p-20">
                                        @if (!empty($evidences))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row el-element-overlay">
                                                                @foreach($evidences as $photo)
                                                                    @if ($photo->auxiliary01 == 'EVI')
                                                                        <div class="col-lg-2 col-md-6">
                                                                            <div class="card">
                                                                                <div class="el-card-item">
                                                                                    <div class="el-card-avatar el-overlay-1">
                                                                                        <img src="{{asset('storage/' . $photo->path)}}" alt="user" style="max-width: 200px;max-height: 200px"/>
                                                                                        <div class="el-overlay">
                                                                                            <ul class="list-style-none el-info">
                                                                                                <li class="el-item">
                                                                                                    <a href="{{url('downloadphoto/' . $photo->id)}}"
                                                                                                       data-togggle="tooltip"
                                                                                                       title="Descargar"
                                                                                                       class="btn default btn-outline el-link">
                                                                                                        <i class="mdi mdi-download"></i>
                                                                                                    </a>
                                                                                                </li>
                                                                                                @can('destroyphotoshipment')
                                                                                                    <li class="el-item">
                                                                                                        <a href="{{url('destroyphoto/' . $photo->id)}}"
                                                                                                           class="btn default btn-outline el-link">
                                                                                                            <i class="mdi mdi-delete"></i>
                                                                                                        </a>
                                                                                                    </li>
                                                                                                @endcan
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="el-card-content">
                                                                                        <h4 class="m-b-0 font-10 font-weight-light"> {{$photo->user->name}} </h4>
                                                                                        <span class="text-muted">{{$photo->created_at}}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
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
    {{--    @include('Maintenance.CheckListLiftTrucks.partials.table')--}}
    {{--    <script>--}}
    {{--        function reloadtable() {--}}
    {{--            $('#table-checklisttruck').DataTable().ajax.reload();--}}
    {{--        }--}}
    {{--    </script>--}}
@endsection
