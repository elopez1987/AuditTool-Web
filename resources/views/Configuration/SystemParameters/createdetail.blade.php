@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar detalle del parametro
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('savedetailparameter/' . $systemparameter->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @can('savedetailparameter')
                                    <a href="">
                                        @include('buttons.save')
                                    </a>
                                @endcan
                                <a href="{{route('systemparameters.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Detalle</label>
                                        <textarea id="nameparameter" name="nameparameter" readonly class="form-control"
                                                  cols="150" rows="10">
                                        {{$systemparameter->name}}
                                    </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value01">Valor 01 </label>
                                        <input type="text" class="form-control" id="value01" name="value01"
                                               aria-describedby="value01" value="{{old('value01')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value02">Valor 02 </label>
                                        <input type="text" class="form-control" id="value02" name="value02"
                                               aria-describedby="value02" value="{{old('value02')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value03">Valor 03 </label>
                                        <input type="text" class="form-control" id="value03" name="value03"
                                               aria-describedby="value03" value="{{old('value03')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value04">Valor 04 </label>
                                        <input type="text" class="form-control" id="value04" name="value04"
                                               aria-describedby="value04" value="{{old('value04')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value05">Valor 05 </label>
                                        <input type="text" class="form-control" id="value05" name="value05"
                                               aria-describedby="value05" value="{{old('value05')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value06">Valor 06 </label>
                                        <input type="text" class="form-control" id="value06" name="value06" maxlength="100"
                                               aria-describedby="value06" value="{{old('value06')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value07">Valor 07 </label>
                                        <input type="text" class="form-control" id="value07" name="value07" maxlength="100"
                                               aria-describedby="value07" value="{{old('value07')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value08">Valor 08 </label>
                                        <input type="text" class="form-control" id="value08" name="value08" maxlength="100"
                                               aria-describedby="value08" value="{{old('value08')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value09">Valor 09 </label>
                                        <input type="text" class="form-control" id="value09" name="value09" maxlength="100"
                                               aria-describedby="value09" value="{{old('value09')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value10">Valor 10 </label>
                                        <input type="text" class="form-control" id="value10" name="value10" maxlength="100"
                                               aria-describedby="value10" value="{{old('value10')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value11">Valor 11 </label>
                                        <input type="text" class="form-control" id="value11" name="value11" maxlength="100"
                                               aria-describedby="value11" value="{{old('value11')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value12">Valor 12 </label>
                                        <input type="text" class="form-control" id="value12" name="value12" maxlength="100"
                                               aria-describedby="value12" value="{{old('value12')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value13">Valor 13 </label>
                                        <input type="text" class="form-control" id="value13" name="value13" maxlength="100"
                                               aria-describedby="value13" value="{{old('value13')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value14">Valor 14 </label>
                                        <input type="text" class="form-control" id="value14" name="value14" maxlength="100"
                                               aria-describedby="value14" value="{{old('value14')}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="value15">Valor 15 </label>
                                        <input type="text" class="form-control" id="value15" name="value15" maxlength="100"
                                               aria-describedby="value15" value="{{old('value15')}}">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <table class="table table-condensed table-bordered tablegeneral">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Secuencia</th>
                                        <th class="bg-inverse text-white">Valor 01</th>
                                        <th class="bg-inverse text-white">Valor 02</th>
                                        <th class="bg-inverse text-white">Valor 03</th>
                                        <th class="bg-inverse text-white">Valor 04</th>
                                        <th class="bg-inverse text-white">Valor 05</th>
                                        <th class="bg-inverse text-white">Valor 06</th>
                                        <th class="bg-inverse text-white">Valor 07</th>
                                        <th class="bg-inverse text-white">Valor 08</th>
                                        <th class="bg-inverse text-white">Valor 09</th>
                                        <th class="bg-inverse text-white">Valor 10</th>
                                        <th class="bg-inverse text-white">Valor 11</th>
                                        <th class="bg-inverse text-white">Valor 12</th>
                                        <th class="bg-inverse text-white">Valor 13</th>
                                        <th class="bg-inverse text-white">Valor 14</th>
                                        <th class="bg-inverse text-white">Valor 15</th>
                                        <th class="bg-inverse text-white"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($details as $item)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->value01}}</td>
                                            <td>{{$item->value02}}</td>
                                            <td>{{$item->value03}}</td>
                                            <td>{{$item->value04}}</td>
                                            <td>{{$item->value05}}</td>
                                            <td>{{$item->value06}}</td>
                                            <td>{{$item->value07}}</td>
                                            <td>{{$item->value08}}</td>
                                            <td>{{$item->value09}}</td>
                                            <td>{{$item->value10}}</td>
                                            <td>{{$item->value11}}</td>
                                            <td>{{$item->value12}}</td>
                                            <td>{{$item->value13}}</td>
                                            <td>{{$item->value14}}</td>
                                            <td>{{$item->value15}}</td>
                                            <td>
                                                @can('deletedetailparameter')
                                                    <a href="" data-target="#modal-delete-{{$item->id}}"
                                                       data-toggle="modal">
                                                        <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16">
                                                    </a>
                                                    @include('Configuration.SystemParameters.deletedetail')
                                                @endcan
                                                <a href="" data-target="#modal-edit-{{$item->id}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/adedit.png')}}" alt="" class="img-16 ml-2 mr-2" >
                                                </a>
                                                @include('Configuration.SystemParameters.editdetail')
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
    </div>
@endsection

@section('css')

@endsection

@section('script')
@include('layouts.Partials.DataTable')
@endsection

