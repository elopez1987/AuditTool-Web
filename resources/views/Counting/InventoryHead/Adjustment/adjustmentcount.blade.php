@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{url('adjustmentinventory/' . $inventory->id)}}">
                        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                            <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                            <span style="font-size: 11px;"> Regresar</span>
                        </button>
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storeajustmentcount')}}">
                        @csrf

                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    <button type="submit" class="btn waves-effect waves-light btn-outline-info  btn-md">
                                        <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Guardar</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="inventoryid">Número de inventario:</label>
                                    <input type="text" class="form-control"  readonly value="{{$inventory->id}}" id="inventoryid" name="inventoryid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Descripción de inventario:</label>
                                    <input type="text" class="form-control"  readonly value="{{$inventory->description}}" id="description" name="description">
                                </div>
                            </div>
                        </div>
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col-lg-8">
                                <table class="table table-condensed table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Pasillo</th>
                                        <th>Cédula</th>
                                        <th>Ubicación</th>
                                        <th>Sku 01</th>
                                        <th>Cantidad 01</th>
                                        <th>Sku 02</th>
                                        <th>Cantidad 02</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($locations as $location)
                                        <tr>
                                            <td>{{$location->areaid}}</td>
                                            <td>{{$location->aisleid}}</td>
                                            <td>
                                                <input type="text" class="form-control" readonly name="cardid[]"
                                                       value="{{$location->cardid}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly name="locationid[]"
                                                       value="{{$location->locationid}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="partnumber01[]"
                                                       value="{{$location->partnumber01}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="quantity01[]"
                                                       value="{{$location->quantity01}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="partnumber02[]"
                                                       value="{{$location->partnumber02}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="quantity02[]"
                                                       value="{{$location->quantity02}}">
                                            </td>
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

@section('script')

@endsection

