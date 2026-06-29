@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignación de proveedpr a usuarios : {{$user->name}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securityaccess-providers/' . $user->uuiduser)}}">
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
                        <hr class="mt-2">
                        <div class="card-input mt-5">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input type="text" class="form-control" readonly id="name" name="name"
                                               value="{{$user->name}}"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="username">Nombre de usuario</label>
                                        <input type="text" class="form-control" readonly id="username" name="username"
                                               value="{{$user->username}}"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email">Correo electronico</label>
                                        <input type="text" class="form-control" readonly id="email" name="email"
                                               value="{{$user->email}}"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="providerid">Proveedor <span style="color: red">*</span></label>
                                        <select name="providerid[]" id="providerid[]" required class="duallistbox-with-filter" multiple="multiple" size="20">
                                            @foreach($providers as $model)
                                                <option value="{{$model->id}}" style="font-size: 12px !important;text-transform: uppercase">
                                                    {{$model->providerid}} - {{$model->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-input mt-4">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-header">Asignación de acceso</div>
                                    <div class="card-body">
                                        <div class="col-lg-12 col-md-6 col-sm-12">
                                            <table
                                                class="table table-condensed table-bordered  table-hover tablegeneral">
                                                <thead>
                                                <tr>
                                                    <th width="10%">Folio</th>
                                                    <th>Proveedor</th>
                                                    <th>Nombre de proveedor</th>
                                                    <th>Opciones</th>
                                                </tr>
                                                </thead>
                                                <tbod>
                                                    @foreach($listProviders as $item)
                                                        <tr>
                                                            <td width="10%">{{$item->id}}</td>
                                                            <td>{{$item->provider ? $item->provider->providerid : ''}}</td>
                                                            <td>{{$item->provider ? $item->provider->name : ''}}</td>
                                                            <td width="10%">
                                                                <a href=""
                                                                   data-target="#modal-delete-{{$item->id}}"
                                                                   data-toggle="modal">
                                                                    <img src="{{asset('/ico/refuseunit.png')}}" alt=""
                                                                         class="img-16 ml-2">
                                                                </a>
                                                                @include('Security.Users.AccessProviders.destroy')
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbod>
                                            </table>
                                        </div>
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
@section('css')
{{--    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">--}}
<link rel="stylesheet" href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
    <script src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script>
        const dualListbox = $('.duallistbox-with-filter').bootstrapDualListbox({
            nonSelectedListLabel: 'No hay registros selecionados:',
            selectedListLabel: 'Seleccionado',
            preserveSelectionOnMove: 'Mover',
            moveOnSelect: false,
        });
        $(function () {
            $('.moveall i').removeClass().addClass('fas fa-angle-right');
            $('.removeall i').removeClass().addClass('fas fa-angle-left');
            $('.move i').removeClass().addClass('fas fa-angle-right');
            $('.remove i').removeClass().addClass('fas fa-angle-left');
        });

    </script>
{{--    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>--}}
{{--    <script type="text/javascript">--}}
{{--        $(".radio-switch input[type='radio']").bootstrapSwitch();--}}
{{--    </script>--}}
@endsection
