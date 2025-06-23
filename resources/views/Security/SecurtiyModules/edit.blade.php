@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Modulo
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securitymodules/' . $securitymodule->moduleid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securitymodules.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Security.SecurtiyModules.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    funciones del modulo
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-striped table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>Modulo</th>
                                    <th>Función</th>
                                    <th>Descripción</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($functions as $function)
                                    <tr>
                                        <td>{{$function->module->description}}</td>
                                        <td>{{$function->functionid}}</td>
                                        <td>{{$function->description}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

