@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Embarque
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('monitorpods')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('monitorpods.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('monitorpods.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="shipmentid">Número de embarque</label>
                                <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                                       id="shipmentid" name="shipmentid" maxlength="20" required
                                       onKeyUp=this.value=this.value.toUpperCase()
                                       aria-describedby="shipmentid" value="{{old('shipmentid')}}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @include('layouts.Partials.Errors')
        </div>
    </div>
@endsection

@section('script')

@endsection

