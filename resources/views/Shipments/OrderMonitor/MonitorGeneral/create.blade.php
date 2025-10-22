@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Programar embarque
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('ordermonitor')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('ordermonitor.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <br>
                        <hr class="m-t-0">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque
                                        @error("shipmentid")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                                           required id="shipmentid" name="shipmentid" maxlength="20" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('shipmentid')}}"
                                           pattern="[a-zA-Z0-9]+" title="Solo se permiten números y letras (sin espacios ni caracteres especiales)">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')

@endsection

@section('script')

@endsection
