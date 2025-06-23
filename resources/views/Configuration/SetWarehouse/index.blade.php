@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Fijar bodega
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('setwarehousestore/' . $user->username)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <a>
                                    <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/adagregar1.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Fijar Bodega</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="username">Nombre de usuario:</label>
                                    <input type="text" class="form-control" readonly value="{{$user->username}}"
                                           id="username" name="username">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre:</label>
                                    <input type="text" class="form-control" readonly value="{{$user->name}}" id="name"
                                           name="name">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <table class="table table-condensed table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Acceso</th>
                                        <th>Centro de distribución</th>
                                        <th>Bodega</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($accesos as $acceso)
                                        <tr>
                                            <td class="radio-switch">
                                                <input type="radio" value="{{$acceso->id}}"
                                                       name="defaultlevel" id="defaultlevel" data-size="mini"
                                                       data-on-color="success" data-on-text="Seleccionado"
                                                       data-off-text="Fijar"
                                                       @if ($acceso->defaultlevel == 'S')
                                                           checked
                                                    @endif
                                                >
                                            </td>
                                            <td>{{$acceso->center ? $acceso->center->name : ''}}</td>
                                            <td>{{$acceso->warehouse ? $acceso->warehouse->name : ''}}</td>
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
    <script type="text/javascript">
        $(".radio-switch input[type='radio']").bootstrapSwitch();
    </script>
@endsection
