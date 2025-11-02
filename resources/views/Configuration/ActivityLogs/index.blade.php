@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Registro de eventos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Centros de distribución <span
                                            style="color: red">*</span></label>
                                    <select name="description" id="description" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($descriptions as $model)
                                            <option value="{{$model->description}}"> {{$model->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="model">Modelo <span style="color: red">*</span></label>
                                    <select name="model" id="model" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($models as $model)
                                            <option value="{{$model->subject_type}}"> {{$model->subject_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="subject_id">Registro<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="subject_id" name="subject_id"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                                {{--                                {!! Field::text('subject_id',['label' => 'Registro']) !!}--}}
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="user">Usuario <span style="color: red">*</span></label>
                                    <select name="user" id="user" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($users as $model)
                                            <option value="{{$model->id}}"> {{$model->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{--                            <div class="col-lg-2 col-md-6 col-sm-12">--}}
                            {{--                                {!! Field::text('id',['label' => 'Folio']) !!}--}}
                            {{--                            </div>--}}
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="date_in">Fecha de Inicio</label>
                                    <input type="text" class="form-control mydatepicker" id="date_in" name="date_in"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="date_out">Fecha Final</label>
                                    <input type="text" class="form-control mydatepicker" id="date_out" name="date_out"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-logs" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Folio</th>
                                    <th class="bg-inverse text-white">Description</th>
                                    <th class="bg-inverse text-white">Modelo</th>
                                    <th class="bg-inverse text-white">Modelo Id</th>
                                    <th class="bg-inverse text-white">Usuario</th>
                                    <th class="bg-inverse text-white">F.Creación</th>
                                    <th class="bg-inverse text-white">F.Modificación</th>
                                    <th width="5%" class="bg-inverse text-white">Opciones</th>
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


@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        jQuery('.mydatepicker').datepicker(
            {
                autoclose: true,
                orientation: 'bottom',
                format: 'yyyy-mm-dd',
            }
        );
    </script>
    @include('Configuration.ActivityLogs.partials.table')
    <script>
        function reloadtable() {
            $('#table-logs').DataTable().ajax.reload();
        }
    </script>
@endsection
