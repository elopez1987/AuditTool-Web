@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar permiso
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securitypermissions')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('securitypermissions.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securitypermissions.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-0">
                        @include('Security.Permissions.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')
    <script>
        $("#moduleid").change(event => {
            $.get(`../getFunctionForModule/${event.target.value}`, function (res) {
                $("#functionid").empty();
                res.forEach(element => {
                    $("#functionid").append(`<option value=${element.functionid}> ${element.description} </option>`);
                });
            });
        });
    </script>


@endsection

