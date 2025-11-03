@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    agregar detalle de incidencia
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storedetailcategoryincidence/' . $category->categoryincid)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagecategoryincidences.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-2">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="descriptiondetail">Descripción </label>
                                    <input type="text" class="form-control" required  id="descriptiondetail" name="descriptiondetail"  onKeyUp=this.value=this.value.toUpperCase()>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary01">Auxiliar 01
                                        @error("auxiliary01")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                    </label>
                                    <input type="text" readonly class="form-control @error('auxiliary01') is-invalid @enderror"
                                           id="auxiliary01" name="auxiliary01" maxlength="100"
                                           aria-describedby="auxiliary01" value="{{$category->auxiliary01}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary02">Auxiliar 02 </label>
                                    <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                                           aria-describedby="auxiliary02" value="{{old('auxiliary02')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary03">Auxiliar 03 </label>
                                    <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                                           aria-describedby="auxiliary03" value="{{old('auxiliary03')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary04">Auxiliar 04 </label>
                                    <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                                           aria-describedby="auxiliary04" value="{{old('auxiliary04')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary05">Auxiliar 05 </label>
                                    <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                                           aria-describedby="auxiliary05" value="{{old('auxiliary05')}}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr class="mt-3">
                    <div class="row">
                        <div class="col-lg-10">
                            <table class="table table-hover table-hover table-condensed tablegeneral">
                                <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th>Sub-categoria</th>
                                    <th>Tipo</th>
                                    <th>Auxiliar 02</th>
                                    <th>Auxiliar 03</th>
                                    <th>Auxiliar 04</th>
                                    <th>Auxiliar 05</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{$detail->category->description}}</td>
                                        <td>{{$detail->description}}</td>
                                        <td>{{$detail->auxiliary01}}</td>
                                        <td>{{$detail->auxiliary02}}</td>
                                        <td>{{$detail->auxiliary03}}</td>
                                        <td>{{$detail->auxiliary04}}</td>
                                        <td>{{$detail->auxiliary05}}</td>
                                        <td>
                                            @if($detail->statusid == 'E')
                                                <span class="label label-green font-table text-black">{{$detail->status->name}}</span>
                                            @endif

                                            @if($detail->statusid == 'D')
                                                <span class="label label-red font-table text-white">{{$detail->status->name}}</span>
                                            @endif

                                        </td>
                                        <td>
                                            <a href=""
                                               data-target="#modal-updatedetail-{{$detail->categoryincdetailid}}"
                                               data-toggle="modal">
                                                <img src="{{asset('/ico/adedit.png')}}" alt="" class="img-16 ml-2">
                                            </a>
                                            @include('Shipments.CategoryIncidences.updatedetail')
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

