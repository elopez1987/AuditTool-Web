@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Parametrizar Linea de Transporte
                </div>
                <form method="POST" action="{{url('addparameterscarrier/' . $model->id)}}"
                      enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="card-body">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-12">
                                    <a href="">
                                        @include('buttons.save')
                                    </a>
                                    <a href="{{route('shipmentcarriers.index')}}">
                                        @include('buttons.back')
                                    </a>
                                </div>
                            </div>
                            <hr class="mt-4">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Linea de transporte</label>
                                        <input type="text" class="form-control" readonly value="{{$model->name}}"
                                               id="name" name="name">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <table class="table  table-bordered table-condensed tablegeneral">
                                        <thead>
                                        <tr>
                                            <th style="width: 10% !important;">Secuencia</th>
                                            <th></th>
                                            <th>Parametro</th>
                                            <th>Descripción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($parameters as $parameter)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td class="switch">
                                                    <input
                                                            type="checkbox"
                                                            value="{{$parameter->statusid}}"
                                                            @if(!empty($model->configurationflag))
                                                                @foreach ($model->configurationflag as $check)
                                                                    @if ($check == $parameter->statusid)
                                                                        checked
                                                            @endif
                                                            @endforeach
                                                            @endif
                                                            name="parameter[]"
                                                            data-size="mini" data-on-color="success"
                                                            data-off-color="danger"
                                                            data-on-text="<i class='ti ti-check'></i>"
                                                            data-off-text="<i class='ti ti-close'></i>"
                                                    >
                                                </td>
                                                <td>{{$parameter->statusid}}</td>
                                                <td>{{$parameter->name}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
@endsection

