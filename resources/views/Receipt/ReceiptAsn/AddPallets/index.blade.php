@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Ingreso de tarimas en recibo
                </div>
                <form method="POST" action="{{url('addpalletssave/' . $receipt->id)}}" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{url('schedulereceipt')}}">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                                                <span style="font-size: 11px;"> Regresar</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="card-input">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="shipmentid">Embarque <span
                                                        style="color: red">*</span></label>
                                                <input type="text" class="form-control" readonly id="shipmentid"
                                                       name="shipmentid"
                                                       value="{{$receipt->shipmentid}}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="partnumberid">Tipo de tarimas <span
                                                        style="color: red">*</span></label>
                                                <select name="partnumberid" required class="form-control select2">
                                                    <option value="" selected>Selecciona un valor</option>
                                                    @foreach($materials as $model)
                                                        <option value="{{$model->id}}" {{ old('partnumberid') == $model->id ? 'selected' : '' }} > {{$model->lngdsc}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="quantity">Cantidad de tarimas <span
                                                        style="color: red">*</span></label>
                                                <input type="text" class="form-control numeric" required id="quantity" maxlength="3"
                                                       name="quantity" value="{{old('quantity')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="damage">Daños <span style="color: red">*</span></label>
                                                <input type="text" class="form-control numeric" required id="damage" maxlength="3"
                                                       name="damage" value="{{old('damage')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <a href="">
                                    <button type="submit"
                                            class="btn waves-effect waves-light btn-outline-info btn-md btn-block">
                                        <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Guardar</span>
                                    </button>

                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Cantidad de tarimas</div>
                <div class="card-body">
                    <table class="table table-hover  table-condensed table-bordered tablegeneral">
                        <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Cantidad total</th>
                            <th>Cantidad dañana</th>
                            <th>Canidad en buen estado</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $item)
                            <tr>
                                <th>{{$item->description}}</th>
                                <th>{{$item->quantity}}</th>
                                <th>{{$item->damage}}</th>
                                <th>{{$item->quantity - $item->damage}}</th>
                                <th>
                                    @if($receipt->statusid != 'IG')
                                        <a href="" data-target="#modal-update-{{$item->id}}"
                                           data-toggle="modal">
                                            <img src="{{asset('/ico/typemovements.png')}}" alt=""
                                                 class="img-16 ml-2">
                                        </a>
                                        @include('Receipt.ReceiptAsn.AddPallets.updatepallets')
                                        <a href="" data-target="#modal-delete-{{$item->id}}"
                                           data-toggle="modal">
                                            <img src="{{asset('/ico/destroy2.png')}}" alt="" class="img-16 ml-2">
                                        </a>
                                        @include('Receipt.ReceiptAsn.AddPallets.deletepallets')
                                    @endif
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
