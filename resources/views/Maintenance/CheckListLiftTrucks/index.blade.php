@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   Check list realizados
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menumaintenance')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="serialnumber">Número de serie</label>
                                    <input type="text" class="form-control"  id="serialnumber" name="serialnumber">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="numbertruck">Número de equipo</label>
                                    <input type="text" class="form-control"  id="numbertruck" name="numbertruck">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                        <label for="workday">Fecha de creación</label>
                                        <input type="text" class="form-control workday"  id="workday" name="workday">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <button class="btn btn-outline-secondary mt-4" type="button" id="resetfilter"><i class="fa fa-search ml-2 mr-2"></i>Eliminar filtros</button>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-checklisttruck" class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Folio</th>
                                        <th class="bg-inverse text-white">Número de serie</th>
                                        <th class="bg-inverse text-white">Número de equipo</th>
                                        <th class="bg-inverse text-white">Tipo</th>
                                        <th class="bg-inverse text-white">Horometro</th>
                                        <th class="bg-inverse text-white">Montacarguista</th>
                                        <th class="bg-inverse text-white">Supervisor</th>
                                        <th class="bg-inverse text-white">Fecha de creación</th>
                                        <th class="bg-inverse text-white">Opciones</th>
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
    @include('Maintenance.CheckListLiftTrucks.partials.table')
    <script>
        function reloadtable() {
            $('#table-checklisttruck').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#workday,#numbertruck,#serialnumber").change(function () {
            let workdayselect = document.getElementById("workday").value;
            sessionStorage.setItem("workdayop", workdayselect);

            let serialnumberselect = document.getElementById("serialnumber").value;
            sessionStorage.setItem("serialnumber", serialnumberselect);

            let numbertruckselect = document.getElementById("numbertruck").value;
            sessionStorage.setItem("numbertruck", numbertruckselect);

            reloadtable();
        });
    </script>
    <script>
        $(document).ready(function () {
            document.getElementById("numbertruck").value = sessionStorage.getItem('numbertruck');
            document.getElementById("workday").value = sessionStorage.getItem('workdayop');
            document.getElementById("serialnumber").value = sessionStorage.getItem('serialnumber');
            reloadtable();
        });
    </script>

    <script>
        $("#resetfilter").click(function () {
            $("#search")[0].reset();
            reloadtable();
        });
    </script>

@endsection
