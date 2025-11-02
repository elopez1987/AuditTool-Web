@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Logs de inicio de sesión
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menusecurity')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">

                    </form>
                    <div class="row">
                        <div class="col-lg-10 col-md-12 col-sm-12">
                            <table width="100%" id="table-activities"
                                   class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">id</th>
                                    <th class="bg-inverse text-white">user_id</th>
                                    <th class="bg-inverse text-white">activity_type</th>
                                    <th class="bg-inverse text-white">details</th>
                                    <th class="bg-inverse text-white">severity</th>
                                    <th class="bg-inverse text-white">reviewed</th>
                                    <th class="bg-inverse text-white">reviewed_at</th>
                                    <th class="bg-inverse text-white">reviewed_by</th>
                                    <th class="bg-inverse text-white">created_at</th>
                                    <th width="5%" class="bg-inverse text-white">Opciones</th>
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
    @include('Security.LogSuspicious.partials.table')
    <script>
        function reloadtable() {
            $('#table-activities').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#roleid,#statusid").change(function () {
            reloadtable();
        });
    </script>
@endsection
