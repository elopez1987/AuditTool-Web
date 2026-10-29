@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Check list de analista
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('addchecklistAR/' . $receipt->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('receiptasn.index')}}">
                                @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="checklistname">Tipo</label>
                                    <input type="text" class="form-control" readonly  id="checklistname" name="checklistname"
                                           value="{{$checklist->name}}"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque </label>
                                    <input type="text" class="form-control" readonly  id="shipmentid" name="shipmentid"
                                           value="{{$receipt->shipmentid}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="billoflading">Factura </label>
                                    <input type="text" class="form-control" readonly  id="billoflading" name="billoflading"
                                           value="{{$receipt->billoflading}}"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <table class="table table-bordered table-condensed table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th width="7%">Secuencia</th>
                                        <th>Descripción</th>
                                        <th>Estatus</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($details as $item)
                                        <tr>
                                            <td class="hide"><input type="text" value="{{$item->checklistid}}" name="checklistid"></td>
                                            <td class="hide"><input type="text" value="{{$item->auxiliary01}}" name="typeid"></td>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->description}}</td>
                                            <td class="switch border-top-0">
                                                <input type="checkbox" id="checklistdetailid[]"
                                                       name="checklistdetailid[]"
                                                       value="{{$item->id}}" data-size="mini"
                                                       data-on-color="success" data-on-text="SI" data-off-text="NO"
                                                       @foreach ($actives as $active)
                                                           @if ($active->checklistdetailid == $item->id)
                                                               checked
                                                    @endif
                                                    @endforeach
                                                >
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
        var quill = new Quill('#comment', {
            theme: 'snow'
        });
    </script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
@endsection
