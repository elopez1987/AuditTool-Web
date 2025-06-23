@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Parametrizar Usuario
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securityusersparameterstore/' . $user->uuiduser)}}">
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
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Usuario </label>
                                    <input type="text" class="form-control" id="name" name="name" maxlength="100"
                                           aria-describedby="auxiliary05" value="{{$user->name}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <table class="table table-striped table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th width="10%">Secuencia</th>
                                        <th width="10%">Activar/Desactivar</th>
                                        <th>Parametro</th>
                                        <th>Descripción</th>
                                        <th width="20%">Comentario</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($parameters as $parameter)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td style="width: 100px;" class="switch">
                                                <input
                                                    type="checkbox"
                                                    value="{{$parameter->statusid}}"
                                                    @if(!empty($user->configurationflag))
                                                        @foreach ($user->configurationflag as $check)
                                                            @if ($check == $parameter->statusid)
                                                                checked
                                                             @endif
                                                        @endforeach
                                                    @endif
                                                    name="parameter[]"
                                                    data-size="mini" data-on-color="success" data-off-color="danger"
                                                    data-on-text="<i class='ti ti-check'></i>"
                                                    data-off-text="<i class='ti ti-close'></i>"
                                                >
                                            </td>
                                            <td>{{$parameter->statusid}}</td>
                                            <td>{{$parameter->name}}</td>
                                            <td>{{$parameter->comment}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
@endsection
