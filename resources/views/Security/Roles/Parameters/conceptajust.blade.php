@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Parametrizar Centro de distribución
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('saveconceptajust/' . $role->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securityroles.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Perfil de usuario<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" readonly id="name" name="name"
                                           value="{{$role->description}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="categoryincid">Categoria de ajuste<span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="categoryincid" name="categoryincid"
                                           value="{{old('categoryincid')}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="categoryincdetailid">Concepto de ajuste<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="categoryincdetailid"
                                           name="categoryincdetailid"
                                           value="{{old('categoryincdetailid')}}"
                                    >
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-condensed table-bordered table-hover tablegeneral">
                                    <thead>
                                    <tr>
                                        <th>Centro de distribución</th>
                                        <th>Categoria de ajuste</th>
                                        <th>Concepto de ajuste</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($details as $item)
                                        <tr>
                                            <td>{{$item->centerid}}</td>
                                            <td>{{$item->categoryincid}}</td>
                                            <td>{{$item->categoryincdetailid}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="" data-target="#modal-delete-{{$item->id}}"
                                                       data-toggle="modal">
                                                        <img src="{{asset('/ico/addestroy.png')}}" alt=""
                                                             class="img-16">
                                                    </a>
                                                    @include('Security.Roles.Parameters.destroy')
                                                </div>
                                            </td>
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
    </div>

@endsection

@section('css')

@endsection


@section('script')
    @include('layouts.Partials.DataTable')
@endsection
