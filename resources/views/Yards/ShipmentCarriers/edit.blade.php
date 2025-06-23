@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Linea de Transporte
                </div>
                <form method="POST" action="{{url('shipmentcarriers/' . $shipmentcarrier->id)}}"
                      enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('shipmentcarriers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-4">
                        @include('Yards.ShipmentCarriers.partials.formEdit')
                        <div class="row mt-4">
                            <div class="col-lg-3 col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input name="imgqr" id="photo" type="file"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" style="height: 300px">
                                        <h4>Código QR de Acceso</h4>
                                        <img id="imgSalida" class="img-bordered img-responsive"
                                             style="width: 100%;height: 100%" src="" alt="" height="200px"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input name="photologo" id="photologo" type="file"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" style="height: 300px !important;">
                                        <h4>Logo de la linea de transporte.</h4>
                                        <img id="imgSalidaLogo" class="img-bordered img-responsive"
                                             style="width: 100%;height: 100%" src="" alt="" height="80%"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        Código QR Actual
                                    </div>
                                    <div class="card-body">
                                        @if($shipmentcarrier->auxiliary04 != null)
                                            <img src="{{asset('storage/' . $shipmentcarrier->auxiliary04)}}" alt=""
                                                 height="200">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        Logo Actual
                                    </div>
                                    <div class="card-body">
                                        @if($shipmentcarrier->auxiliary05 != null)
                                            <img src="{{asset('storage/' . $shipmentcarrier->auxiliary05)}}" alt=""
                                                 height="200">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#photo').change(function (e) {
                addImage(e);
            });

            function addImage(e) {
                let file = e.target.files[0],
                    imageType = /image.*/;
                if (!file.type.match(imageType))
                    return;
                let reader = new FileReader();
                reader.onload = fileOnload;
                reader.readAsDataURL(file);
            }

            function fileOnload(e) {
                let result = e.target.result;
                $('#imgSalida').attr("src", result);
            }
        });
    </script>
    <script>
        $(function () {
            $('#photologo').change(function (e) {
                addImage(e);
            });

            function addImage(e) {
                let file = e.target.files[0],
                    imageType = /image.*/;
                if (!file.type.match(imageType))
                    return;
                let reader = new FileReader();
                reader.onload = fileOnload;
                reader.readAsDataURL(file);
            }

            function fileOnload(e) {
                let result = e.target.result;
                $('#imgSalidaLogo').attr("src", result);
            }
        });
    </script>
@endsection

