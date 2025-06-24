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
{{--                    <div class="row mt-4">--}}
{{--                        @can('securityusers.destroy')--}}
{{--                            <div class="col-lg-12">--}}
{{--                                @if($user->statusid == 'E')--}}
{{--                                    <a href="" data-target="#modal-delete-{{$user->uuiduser}}" data-toggle="modal">--}}
{{--                                        <button type="button"--}}
{{--                                                class="btn waves-effect waves-light btn-outline-info btn-md">--}}
{{--                                            <img--}}
{{--                                                src="{{asset('/ico/delete_delete_deleteusers_delete_male_user_maleclient_2348.png')}}"--}}
{{--                                                alt="" class="img-16 ml-2">--}}
{{--                                            <span style="font-size: 0.675rem "> Eliminar Usuario</span>--}}
{{--                                        </button>--}}
{{--                                    </a>--}}
{{--                                @endif--}}
{{--                                @if($user->statusid == 'D')--}}
{{--                                    <a href="" data-target="#modal-restore-{{$user->uuiduser}}" data-toggle="modal">--}}
{{--                                        <button type="button"--}}
{{--                                                class="btn waves-effect waves-light btn-outline-info btn-md ml-3">--}}
{{--                                            <img--}}
{{--                                                src="{{asset('/ico/cloudrefresh_icon-icons.com_54403.png')}}"--}}
{{--                                                alt="" class="img-16 ml-2">--}}
{{--                                            <span style="font-size: 0.675rem "> Restaurar Usuario</span>--}}
{{--                                        </button>--}}
{{--                                    </a>--}}
{{--                                @endif--}}

{{--                            </div>--}}

{{--                            @include('Security.Users.delete')--}}
{{--                            @include('Security.Users.restore')--}}
{{--                        @endcan--}}
{{--                    </div>--}}


                    <div class="row mt-4">
                        @can('securityusers.destroy')
                            <div class="col-lg-12">

                                <a href="" data-target="#modal-delete-{{$user->id}}" data-toggle="modal">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img
                                            src="{{asset('/ico/delete_delete_deleteusers_delete_male_user_maleclient_2348.png')}}"
                                            alt="" class="img-16 ml-2">
                                        <span style="font-size: 0.675rem "> Eliminar Usuario</span>
                                    </button>
                                </a>

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

                            </div>

                            @include('Security.Users.delete')

                        @endcan
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
@endsection
