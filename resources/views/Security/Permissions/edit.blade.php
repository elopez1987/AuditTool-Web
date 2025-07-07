@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificación de permisos
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securitypermissions/' . $securitypermission->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf

                        <div class="card-input">
                            <div class="row">
                                <div class="col-12">
                                    <a href="">
                                        @include('buttons.save')
                                    </a>
                                    <a href="{{route('securitypermissions.index')}}">
                                        @include('buttons.back')
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Security.Permissions.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

