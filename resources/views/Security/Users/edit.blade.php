@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificacion de usuario : {{$user->name}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securityusers/' . $user->uuiduser)}}">
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
                        @include('Security.Users.partials.formEdit')
                    </form>
                    <hr>
                    <div class="row mt-4">

                        <div class="col-lg-12">
                            @if(!empty($user->email))
                                <a href="" data-target="#modal-resetpassword-{{$user->id}}" data-toggle="modal">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img
                                                src="{{asset('/ico/restoreaccess.png')}}"
                                                alt="" class="img-16 ml-2">
                                        <span style="font-size: 0.675rem "> Reiniciar Contraseña</span>
                                    </button>
                                </a>
                                @include('Security.Users.resetpassword')
                            @endif

                            @can('securityusers.destroy')
                                <a href="" data-target="#modal-delete-{{$user->id}}" data-toggle="modal">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img
                                                src="{{asset('/ico/delete_delete_deleteusers_delete_male_user_maleclient_2348.png')}}"
                                                alt="" class="img-16 ml-2">
                                        <span style="font-size: 0.675rem "> Eliminar Usuario</span>
                                    </button>
                                </a>
                                @include('Security.Users.delete')

                                <a href="" data-target="#modal-restore-{{$user->id}}" data-toggle="modal">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md ml-3">
                                        <img
                                                src="{{asset('/ico/cloudrefresh_icon-icons.com_54403.png')}}"
                                                alt="" class="img-16 ml-2">
                                        <span style="font-size: 0.675rem "> Restaurar Usuario</span>
                                    </button>
                                </a>
                                @include('Security.Users.restore')

                                @if(empty($user->auth0))
                                    <a href="" data-target="#modal-okta-{{$user->id}}" data-toggle="modal">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md ml-3">
                                            <img
                                                    src="{{asset('/ico/save02.png')}}"
                                                    alt="" class="img-16 ml-2">
                                            <span style="font-size: 0.675rem "> Enviar a Okta</span>
                                        </button>
                                    </a>
                                    @include('Security.Users.okta')
                                @endif

                                @if(!empty($user->auth0))
                                    <a href="" data-target="#modal-deleteokta-{{$user->id}}" data-toggle="modal">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md ml-3">
                                            <img
                                                    src="{{asset('/ico/addestroy1.png')}}"
                                                    alt="" class="img-16 ml-2">
                                            <span style="font-size: 0.675rem "> Eliminar de Okta</span>
                                        </button>
                                    </a>
                                    @include('Security.Users.oktadelete')
                                @endif

                            @endcan
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Actualización de contraseña.
                </div>
                <div class="card-body">
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-12">
                                <form method="POST" action="{{url('updateprofile/' . $user->uuiduser)}}">
                                    {{ method_field('PUT') }}
                                    @csrf
                                    <div class="row">
                                        <div class="col-2">
                                            <a href="">
                                                <button type="submit"
                                                        class="btn waves-effect waves-light btn-outline-danger btn-md btn-block">
                                                    <span style="font-size: 0.675rem "> Actualizar contraseña</span>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="mt-2">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="password">Nueva contraseña</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control  " id="password"
                                                           name="password"
                                                           minlength="10"
                                                           maxlength="12"
                                                           aria-describedby="password"
                                                    >
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                                id="togglePassword">
                                                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirmar contraseña </label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control"
                                                           id="password_confirmation"
                                                           name="password_confirmation" maxlength="12" minlength="10"
                                                           aria-describedby="password_confirmation"
                                                    >
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                                id="togglePasswordConfirmation">
                                                            <i class="fas fa-eye"
                                                               id="togglePasswordConfirmationIcon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script type="text/javascript">
        $(".radio-switch input[type='radio']").bootstrapSwitch();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Función para crear toggle de contraseña
            function createPasswordToggle(inputId, buttonId, iconId) {
                const toggleButton = document.getElementById(buttonId);
                const passwordInput = document.getElementById(inputId);
                const toggleIcon = document.getElementById(iconId);

                if (toggleButton && passwordInput && toggleIcon) {
                    toggleButton.addEventListener('click', function () {
                        const isPassword = passwordInput.getAttribute('type') === 'password';

                        // Cambiar tipo de input
                        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                        // Cambiar ícono
                        toggleIcon.classList.toggle('fa-eye', !isPassword);
                        toggleIcon.classList.toggle('fa-eye-slash', isPassword);
                    });
                }
            }

            // Aplicar a ambos campos
            createPasswordToggle('password', 'togglePassword', 'togglePasswordIcon');
            createPasswordToggle('password_confirmation', 'togglePasswordConfirmation', 'togglePasswordConfirmationIcon');
        });
    </script>

@endsection
