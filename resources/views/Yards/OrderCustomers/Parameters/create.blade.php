@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Parametrizar cliente
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('addparameters/' . $model->id)}}" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('ordercustomers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre de cliente</label>
                                    <input type="text" class="form-control" readonly  id="name" name="name" value="{{$model->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th width="5%" class="bg-inverse text-white">Secuencia</th>
                                        <th class="bg-inverse text-white"></th>
                                        <th class="bg-inverse text-white">Parametro</th>
                                        <th class="bg-inverse text-white">Descripción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($parameters as $parameter)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            @if ($model->configurationflag != null)
                                                <td style="width: 70px;" class="switch">
                                                    <input
                                                        type="checkbox"
                                                        value="{{$parameter->statusid}}"
                                                        @foreach ($model->configurationflag as $check)
                                                            @if ($check == $parameter->statusid)
                                                                checked
                                                        @endif
                                                        @endforeach
                                                        name="parameter[]"
                                                        data-size="mini" data-on-color="success" data-off-color="danger"
                                                        data-on-text="<i class='ti ti-check'></i>"
                                                        data-off-text="<i class='ti ti-close'></i>"
                                                    >
                                                </td>
                                            @else
                                                <td class="switch">
                                                    <input type="checkbox" value="{{$parameter->statusid}}"
                                                           name="parameter[]"
                                                           data-size="mini" data-on-color="success" data-off-color="danger"
                                                           data-on-text="<i class='ti ti-check'></i>"
                                                           data-off-text="<i class='ti ti-close'></i>"
                                                    >

                                                </td>
                                            @endif
                                            <td>{{$parameter->statusid}}</td>
                                            <td>{{$parameter->name}}</td>
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
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
@endsection

