@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar token
                </div>
                <form method="POST" action="{{url('edi-configurations/' . $ediConfig->id)}}">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('edi-configurations.index')}}">
                                    @include('buttons.back')
                                </a>
                                <a href="" data-toggle="modal" data-target="#modal-delete-{{$ediConfig->id}}">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/destroy1.png')}}" alt="" class="img-16">
                                        <span class="text-button"> Eliminar</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <hr class="mt-2">
                        @include('Configuration.EdiConfig.partials.formEdit')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

<!-- Modal de eliminación -->
<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-delete-{{$ediConfig->id}}">
    <form method="POST" action="{{url('edi-configurations/' . $ediConfig->id)}}">
        {{ method_field('DELETE') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Eliminar registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="font-12">¿Está seguro de eliminar la configuración EDI "{{$ediConfig->edi}}"?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

