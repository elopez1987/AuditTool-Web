@extends('layouts.app')

@section('content')
    <form method="POST" action="{{url('createSafetyTour/' . $storageSafetyTourType->typeuuid)}}">
        {{csrf_field()}}
        {{ method_field('PUT') }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Armar recorrido de seguridad
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" readonly
                                           value="{{$storageSafetyTourType->name}}"
                                    >
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Armar recorrido de seguridad
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <h4 class="card-title"> Tags </h4>
                                <select multiple="multiple" size="15" class="duallistbox-with-filter" name="tagid[]"
                                        required>
                                    @foreach($storageTags as $tag)
                                        <option value="{{$tag->id}}"> {{$tag->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    Detalle del recorrido de seguridad.
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-condensed table-striped tablegeneral"
                           style="width: 100%;">
                        <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Ubicación</th>
                            <th>Eliminar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($details as $detail)
                            <tr>
                                <td>{{$detail->tag ? $detail->tag->tagid : ''}}</td>
                                <td>{{$detail->tag ? $detail->tag->name : ''}}</td>
                                <td>
                                    @if(empty($detail->deleted_at))
                                        <a href="" data-target="#modal-destroydetail-{{$detail->id}}"
                                           data-toggle="modal">
                                            <img src="{{asset('/ico/destroy2.png')}}" alt=""
                                                 class="img-16 ml-2">
                                        </a>
                                        @include('SafetyTours.StorageSafetyTourType.destroydetail')
                                    @endif
                                    @if(!empty($detail->deleted_at))
                                        <a href="" data-target="#modal-restoredetail-{{$detail->id}}"
                                           data-toggle="modal">
                                            <img src="{{asset('/ico/adreload.png')}}" alt=""
                                                 class="img-16 ml-2">
                                        </a>
                                        @include('SafetyTours.StorageSafetyTourType.restoredetail')
                                    @endif
                                </td>
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
    <link rel="stylesheet"
          href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endsection


@section('script')
    <script
        src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
    @include('SafetyTours.StorageSafetyTourType.partials.table')
    @include('layouts.Partials.DataTable')
    <script>
        $('.duallistbox-with-filter').bootstrapDualListbox({
            nonSelectedListLabel: 'No hay registros selecionados:',
            selectedListLabel: 'Seleccionado',
            preserveSelectionOnMove: 'Mover',
            moveOnSelect: false,
        });
        $(function () {
            $('.moveall i').removeClass().addClass('fas fa-angle-right');
            $('.removeall i').removeClass().addClass('fas fa-angle-left');
            $('.move i').removeClass().addClass('fas fa-angle-right');
            $('.remove i').removeClass().addClass('fas fa-angle-left');
        });
    </script>

@endsection
