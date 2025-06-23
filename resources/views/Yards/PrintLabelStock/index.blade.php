@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Generación de etiquetas para cajas
                </div>
                <form method="POST" action="{{url('printlabel')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('menuyard')}}" style="margin-left: 10px">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-4">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centros de distribución <span
                                            style="color: red">*</span></label>
                                    <select name="centerid" id="centerid" class="form-control select2">
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}"> {{$center->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="quantity">Cantidad de etiquetas</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="typeid">Tipo de etiqueta<span style="color: red">*</span></label>
                                <select name="typeid" id="typeid" class="form-control select2">
                                    <option value="CAJA"> CAJA</option>
                                    <option value="TRACTO"> TRACTO</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="printerid">Impresora<span style="color: red">*</span></label>
                                    <select name="printerid" id="printerid" class="form-control select2">
                                        @foreach($printers as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('script')

@endsection
