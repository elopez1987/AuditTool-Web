@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar lista de contactos
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storeMailDetail/' . $systemmail->mailid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @can('systemmails.savedetailmail')
                                    <a href="">
                                        @include('buttons.save')
                                    </a>
                                @endcan
                                <a href="{{route('systemmails.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="namemail">Correo <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" readonly id="namemail" name="namemail" maxlength="15"
                                           aria-describedby="namemail" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{$systemmail->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centros de distribución <span
                                            style="color: red">*</span></label>
                                    <select name="centerid" required class="form-control select2">
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}"> {{$center->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                                    <select name="warehouseid" required class="form-control select2">
                                        @foreach($warehouses as $warehouse)
                                            <option
                                                value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre de Contacto <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="name" name="name" maxlength="15"
                                           aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('name')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="email">Correo <span style="color: red">*</span></label>
                                    <input type="email" class="form-control" required id="email" name="email" maxlength="100"
                                           aria-describedby="email"
                                           value="{{old('email')}}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-10">
                            <table class="table table-condensed table-bordered">
                                <thead>
                                <tr>
                                    <th width="20%" class="bg-inverse text-white">C. Distribución</th>
                                    <th width="20%" class="bg-inverse text-white">Bodega</th>
                                    <th width="35" class="bg-inverse text-white">Nombre de Contacto</th>
                                    <th width="20" class="bg-inverse text-white">Correo</th>
                                    <th width="5%" class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{$detail->center->name}}</td>
                                        <td>{{$detail->warehouse->name}}</td>
                                        <td>{{$detail->name}}</td>
                                        <td>{{$detail->email}}</td>
                                        <td>
                                            @can('systemmails.deletedetailmail')
                                                <a href="" data-target="#modal-delete-{{$detail->id}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16">
                                                </a>
                                                @include('Configuration.SystemMails.partials.deleteMailContact')
                                            @endcan
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
@endsection

@section('script')
    @include('SelectDynamics.SelectWarehouseForCenterForIndex')
@endsection

