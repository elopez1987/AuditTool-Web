@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogos de Mantenimiento.
                </div>
                <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <a href="{{route('menushipment')}}" class=" m-1">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                    <hr class="mt-4">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#view01" role="tab"><span class="hidden-sm-up"><i class="ti-align-center"></i></span> <span class="hidden-xs-down">Marcas de equipos.</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#view02" role="tab"><span class="hidden-sm-up"><i class="ti-align-center"></i></span> <span class="hidden-xs-down">Modelos de equipos.</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#view03" role="tab"><span class="hidden-sm-up"><i class="ti-align-center"></i></span> <span class="hidden-xs-down">Tipo de equipos</span></a> </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="view01" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Agregar nueva marca.
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" action="{{url('savebrand')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <a >
                                                            @include('buttons.save')
                                                        </a>
                                                    </div>
                                                </div>
                                                <hr class="mb-2">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="schemaidmarca">Esquema</span>
                                                            </label>
                                                            <input type="text" class="form-control" id="schemaidmarca" name="schemaidmarca" maxlength="10"
                                                                   aria-describedby="schemaidmarca" value="MCM" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="namemarca">Nombre</label>
                                                            <input type="text" class="form-control" id="namemarca" name="namemarca" maxlength="100"
                                                                   aria-describedby="namemarca" value="{{old('namemarca')}}"
                                                                   onKeyUp=this.value=this.value.toUpperCase()
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Tabla de marcas de equipo
                                        </div>
                                        <div class="card-body">
                                            <table id="table-marcas" class="table table-condensed table-bordered  table-hover">
                                                <thead>
                                                    <tr>
                                                        <th># ID</th>
                                                        <th>Descripción</th>
                                                        <th>Fecha de creación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($marcas as $marca )
                                                            <tr>
                                                                <td>{{$marca->statusid}}</td>
                                                                <td>{{$marca->name}}</td>
                                                                <td>{{$marca->created_at}}</td>
                                                            </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane  p-20" id="view02" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Agregar nuevo modelo.
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" action="{{url('savemodel')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <a >
                                                            @include('buttons.save')
                                                        </a>
                                                    </div>
                                                </div>
                                                <hr class="mb-2">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="schemamodel">Esquema</span>
                                                            </label>
                                                            <input type="text" class="form-control" id="schemamodel" name="schemamodel" maxlength="10"
                                                                   aria-describedby="schemamodel" value="MDM" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="namemodel">Nombre</label>
                                                            <input type="text" class="form-control" id="namemodel" name="namemodel" maxlength="100"
                                                                   aria-describedby="namemodel" value="{{old('namemodel')}}"
                                                                   onKeyUp=this.value=this.value.toUpperCase()
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Tabla de modelos de equipo
                                        </div>
                                        <div class="card-body">
                                            <table id="table-modelos" class="table table-condensed table-bordered table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th># ID</th>
                                                    <th>Descripción</th>
                                                    <th>Fecha de creación</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($modelos as $model )
                                                    <tr>
                                                        <td>{{$model->statusid}}</td>
                                                        <td>{{$model->name}}</td>
                                                        <td>{{$model->created_at}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="view03" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Agregar tipos de equipos.
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" action="{{url('savetype')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <a >
                                                            @include('buttons.save')
                                                        </a>
                                                    </div>
                                                </div>
                                                <hr class="mb-2">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="schemamodel">Esquema</label>
                                                            <input type="text" class="form-control" id="schemamodel" name="schemamodel" maxlength="10"
                                                                   aria-describedby="schemamodel" value="TPM" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="nametype">Nombre</label>
                                                            <input type="text" class="form-control" id="nametype" name="nametype" maxlength="100"
                                                                   aria-describedby="nametype" value="{{old('nametype')}}"
                                                                   onKeyUp=this.value=this.value.toUpperCase()
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Tabla de modelos de equipo
                                        </div>
                                        <div class="card-body">
                                            <table id="table-modelos" class="table table-condensed table-bordered table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th># ID</th>
                                                    <th>Descripción</th>
                                                    <th>Fecha de creación</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($types as $model )
                                                        <tr>
                                                            <td>{{$model->statusid}}</td>
                                                            <td>{{$model->name}}</td>
                                                            <td>{{$model->created_at}}</td>
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
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var oTable =   $("#table-marcas").DataTable({
            "paging"      : true,
            "searching"  : true,
            "ordering"    : true,
            "serverSide" :false,
            'colReorder': true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],

            buttons: [
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem !important;'> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Excel</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                }
            ],
        });
    </script>
    <script>
        var oTable =   $("#table-modelos").DataTable({
            "paging"      : true,
            "searching"  : true,
            "ordering"    : true,
            "serverSide" :false,
            'colReorder': true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],

            buttons: [
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem !important;'> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Excel</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                }
            ],
        });
    </script>



@endsection
