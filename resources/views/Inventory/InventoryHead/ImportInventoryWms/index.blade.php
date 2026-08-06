@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Importar Inventario Wms
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('import-inventory-wms-excel')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a>
                                    @include('buttons.save')
                                </a>
                                <a href="{{url('inventoryheads/' . $inventoryHead->inventory_uuid)}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Inventario </label>
                                    <input type="text" class="form-control" required id="inventoryid" name="inventoryid" value="{!! $inventoryHead->id !!}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre </label>
                                    <input type="text" class="form-control" required id="name" name="name" disabled
                                           value="{{$inventoryHead->name}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de la existencia
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Ubicación</th>
                                    <th>Número de parte</th>
                                    <th>Cantidad</th>
                                    <th>Cantidad de lpns</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

