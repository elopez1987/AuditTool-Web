@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Registro de unidad
                </div>
                <form method="POST" action="{{url('receiptunitticket')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Folio de unidad
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control numeric @error('folionumber') is-invalid @enderror"
                                           required id="folionumber" name="folionumber" maxlength="20"
                                           onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('folionumber')}}"
                                    >
                                    @error('folionumber')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="weightin">Peso de entrada
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control numeric @error('weightin') is-invalid @enderror"
                                           required id="weightin" name="weightin" maxlength="20"
                                           onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('weightin')}}"
                                    >
                                    @error('weightin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row button-group">
                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-4">
                                <a href="">
                                    <button type="submit"
                                            class="btn waves-effect waves-light btn-outline-success btn-block btn-md">
                                        <img src="{{asset('/pics/adaceptar.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Guardar</span>
                                    </button>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-4">
                                <a href="{{route('receiptunitticket.index')}}">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-danger btn-block btn-md">
                                        <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Cancelar</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>

@endsection

