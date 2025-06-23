@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-10">
            <div class="card">
                <div class="card-header">
                    Registrar Salida de caja
                </div>
                <form method="POST" action="{{url('getcheckout')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <hr class="m-t-1">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="barcodebox">Código de barra</label>
                                            <input type="text" class="form-control numeric" id="barcodebox"
                                                   name="barcodebox"
                                                   onKeyUp=this.value=this.value.toUpperCase()
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    <button type="submit"
                                            class="btn waves-effect waves-light btn-outline-success btn-md">
                                        <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Registrar</span>
                                    </button>
                                </a>
                                <a href="{{route('receiptunits.index')}}">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-danger btn-md">
                                        <img src="{{asset('/ico/back.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Regresar</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection




