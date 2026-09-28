@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Parametros
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('systemparameters.create')
                                <a href="{{route('systemparameters.create')}}">
                                  @include('buttons.add')
                                </a>
                                @endcan
                                <a href="">
                                  @include('buttons.search')
                                </a>
                                <a href="{{route('systemparameters.index')}}">
                                  @include('buttons.reload')
                                </a>
                                <a href="{{route('home')}}">
                                  @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="parameterid">Parametro</label>
                                    <input type="text" class="form-control"  id="parameterid" name="parameterid"
                                           aria-describedby="parameterid" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('parameterid')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control"  id="name" name="name"
                                           aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('name')}}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <table id="table-parameters" class="table table-bordered table-condensed table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th>Parametro</th>
                                        <th>Nombre</th>
                                        <th>Auxiliar 01</th>
                                        <th>Auxiliar 02</th>
                                        <th>Auxiliar 03</th>
                                        <th>Opciones</th>
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
    <script>
        let oTable = initDataTable("#table-parameters", {
            serverSide: true,
            ajax: {
                url: '{{ route('getDataParameters') }}',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.parameterid = $('input[name=parameterid]').val();
                }
            },
            columns: [
                {data : 'parameterid'},
                {data : 'name'},
                {data : 'auxiliary01'},
                {data : 'auxiliary02'},
                {data : 'auxiliary03'},
                { data: 'btn', orderable: false, searchable: false },
            ],
            columnDefs: [
                {"width": "300px", "targets": 0},
                {"width": "450px", "targets": 1},
                {"width": "150px", "targets": 2},
                {"width": "220px", "targets": 3},
                {"width": "220px", "targets": 4},
                {"width": "220px", "targets": 5},
            ],
        });
        $('#search').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    </script>
@endsection
