@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar tolerancia a tipo de unidad
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="{{url('shipmenttruckstolerance/' . $shipmenttruck->id)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('shipmenttrucks.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Descripción</label>
                                            <input type="text" class="form-control" id="description" name="description" maxlength="15"
                                                   aria-describedby="description"  value="{{old('description')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="value01">Valor 01 </label>
                                            <input type="text" class="form-control" id="value01" name="value01" maxlength="15"
                                                   aria-describedby="value01" >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="value02">Valor 02</label>
                                            <input type="text" class="form-control" id="value02" name="value02" maxlength="15"
                                                   aria-describedby="value02" >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="value03">Valor 03 </label>
                                            <input type="text" class="form-control" id="value03" name="value03" maxlength="15"
                                                   aria-describedby="value03" >
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="value04">Valor 04 </label>
                                            <input type="text" class="form-control" id="value04" name="value04" maxlength="15"
                                                   aria-describedby="value04" >
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="value05">Valor 05 </label>
                                            <input type="text" class="form-control" id="value05" name="value05" maxlength="15"
                                                   aria-describedby="value05" >
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <hr class="mt-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-condensed table-bordered table-striped table-hover tablegeneral">
                                <thead>
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Valor 01</th>
                                        <th>Valor 02</th>
                                        <th>Valor 03</th>
                                        <th>Valor 04</th>
                                        <th>Valor 05</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $detail)
                                        <tr>
                                            <td>{{$detail->description}}</td>
                                            <td>{{$detail->value01}}</td>
                                            <td>{{$detail->value02}}</td>
                                            <td>{{$detail->value03}}</td>
                                            <td>{{$detail->value04}}</td>
                                            <td>{{$detail->value05}}</td>
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

