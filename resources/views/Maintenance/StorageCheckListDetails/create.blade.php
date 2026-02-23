@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar detalle de check list
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storedetailslifttruck/' . $checkList->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagechecklistlifttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Maintenance.StorageCheckListDetails.partials.formCreate')
                    </form>
                    <div class="row">
                        <div class="col-8">
                            <table class="table table-condensed  table-bordered tablegeneral">
                                <thead>
                                <tr>
                                    <th>Punto de revisión</th>
                                    <th>Nivel critico</th>
                                    <th>Tipo de revisión</th>
                                    <th>Auxiliar 02</th>
                                    <th>Auxiliar 03</th>
                                    <th>Auxiliar 04</th>
                                    <th>Auxiliar 05</th>
                                    <th>Estatus</th>
                                    <th>Cambiar estatus</th>
                                    <th>Modificar registro</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{$detail->description}}</td>
                                        <td>
                                            @if($detail->typeid == 'TP-01')
                                                <img src="{{asset('ico/mtnormal.png')}}" alt="" class="img-16">
                                            @else
                                                <img src="{{asset('ico/mtcritico.png')}}" alt="" class="img-16">
                                            @endif
                                            {{$detail->type ? $detail->type->name : ''}}
                                        </td>
                                        <td>{{$detail->auxiliary01}}</td>
                                        <td>{{$detail->auxiliary02}}</td>
                                        <td>{{$detail->auxiliary03}}</td>
                                        <td>{{$detail->auxiliary04}}</td>
                                        <td>{{$detail->auxiliary05}}</td>
                                        <td>{{$detail->status ? $detail->status->name : ''}}</td>
                                        <td>
                                            <a href="" data-target="#modal-updatestatus-{{$detail->id}}"
                                               data-toggle="modal">
                                                <img src="{{asset('/ico/typemovements.png')}}" alt=""
                                                     class="img-16 ml-2">
                                            </a>
                                            @include('Maintenance.StorageCheckListDetails.updatestatus')
                                        </td>
                                        <td>
                                            <a href="" data-target="#modal-updatelist-{{$detail->id}}"
                                               data-toggle="modal">
                                                <img src="{{asset('/ico/adedit.png')}}" alt="" class="img-16 ml-2">
                                            </a>
                                            @include('Maintenance.StorageCheckListDetails.updatelist')


                                            @can('deletedetailchecklistlifttrucks')
                                                <a href="" data-target="#modal-delete-{{$detail->id}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/destroy2.png')}}" alt=""
                                                         class="img-16 ml-2">
                                                </a>
                                                @include('Maintenance.StorageCheckListDetails.deletedetail')
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
    @include('layouts.Partials.DataTable')
@endsection

