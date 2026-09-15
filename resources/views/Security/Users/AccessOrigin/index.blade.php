@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignación de origenes a usuario : <h5 class="text-trax">  {{$user->uuiduser}} </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securityaccess-origins/' . $user->uuiduser)}}">
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
                                        <label for="name">Nombre<span style="color: red">*</span></label>
                                        <input type="text" class="form-control" readonly id="name" name="name"
                                               value="{{$user->name}}"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="username">Nombre de usuario<span style="color: red">*</span></label>
                                        <input type="text" class="form-control" readonly id="username" name="username"
                                               value="{{$user->username}}"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email">Correo electronico<span style="color: red">*</span></label>
                                        <input type="text" class="form-control" readonly id="email" name="email"
                                               value="{{$user->email}}"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="originid">Origenes<span style="color: red">*</span></label>
                                        <select name="origins[]" id="origins[]" required class="duallistbox-with-filter" multiple="multiple" size="20">
                                            @foreach($origins as $origin)
                                                <option value="{{$origin->id}}"> {{$origin->description}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-input mt-4">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">Asignación de acceso</div>
                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            <table
                                                    class="table table-condensed table-bordered  table-hover tablegeneral">
                                                <thead>
                                                <tr>
                                                    <th width="10%">Folio</th>
                                                    <th>Origen</th>
                                                    <th>Opciones</th>
                                                </tr>
                                                </thead>
                                                <tbod>
                                                    @foreach($originsAssigned as $item)
                                                        <tr>
                                                            <td width="10%">{{$item->id}}</td>
                                                            <td>{{$item->storageunittype ? $item->storageunittype->description : ''}}</td>
                                                            <td width="10%">
                                                                <a href=""
                                                                   data-target="#modal-delete-{{$item->id}}"
                                                                   data-toggle="modal">
                                                                    <img src="{{asset('/ico/refuseunit.png')}}" alt=""
                                                                         class="img-16 ml-2">
                                                                </a>
                                                                @include('Security.Users.AccessOrigin.destroy')
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
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script type="text/javascript">
        $(".radio-switch input[type='radio']").bootstrapSwitch();
    </script>
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
@endsection
