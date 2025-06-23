@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Conteos ciclicos por dron
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
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
                                    <label for="countid">Número de conteo</label>
                                    <input type="text" class="form-control" id="countid" name="countid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="rack">Rack</label>
                                    <input type="text" class="form-control" id="rack" name="rack">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workday">Fecha de conteo</label>
                                    <input type="text" class="form-control workday" id="workday" name="workday">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-countdron" class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Centro de distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Número de vuelo</th>
                                    <th class="bg-inverse text-white">Fecha de vuelo</th>
                                    <th class="bg-inverse text-white">Rack</th>
                                    <th class="bg-inverse text-white">Piloto</th>
                                    <th class="bg-inverse text-white">Detalle</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Counting.StorageCountDronHead.partials.table')
    <script>
        function reloadtable() {
            $('#table-countdron').DataTable().ajax.reload();
        }
    </script>
@endsection
