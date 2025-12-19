@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="card">
            <div class="card-header">
                Número de embarque: <span
                        style="font-size: 16px;font-weight: 700;text-align: left;margin-right: 100px;color: red"> {{ $orderDelivery->shipmentid}}</span>
                Cliente: <span
                        style="font-size: 13px;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->customer ? $orderDelivery->customer->name : ''}}</span>
                Estatus: <span
                        style="font-size: 13px;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->status ? $orderDelivery->status->name : ''}}</span>

                MODULO DE OPERACIONES
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{ url()->previous() }}">
                            @include('buttons.back')
                        </a>
                        @can('orderdeliveries.show')
                            <a href="{{url('orderdeliveries/' . $orderDelivery->id)}}" target="_blank">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16">
                                    <span style="font-size: 0.675rem "> Ver Kardex </span>
                                </button>
                            </a>
                        @endcan
                    </div>
                </div>
                <hr class="m-t-2">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Registro de tiempos operativos, en este apartado se deberan registrar los
                                            eventos
                                            logisticos
                                            cuando se realice una actividad.
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-6 col-sm-12">
                                                    <table class="table table-condensed  table-bordered table-hover ">
                                                        <thead>
                                                        <tr>
                                                            <th>Evento</th>
                                                            <th>Fecha y Hora</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td class="td-events">Inicio de Preparación</td>
                                                            <td width="30%" class="td-events">
                                                                @if ($orderDelivery->operation->startshipment == '')
                                                                    <a href=""
                                                                       data-target="#modal-addstartshipment-{{$orderDelivery->id}}"
                                                                       data-toggle="modal">
                                                                        <button
                                                                                class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                                            <img src="{{asset('/ico/priority.png')}}"
                                                                                 alt=""
                                                                                 class="img-16 ml-2 mr-2">
                                                                            <span
                                                                                    style="font-size: 0.675rem "> Iniciar</span>
                                                                        </button>
                                                                    </a>
                                                                    @include('Shipments.OrderMonitor.MonitorOperation.Events.addstartshipment')
                                                                @else
                                                                    <span
                                                                            style="font-size: 11px;">   {{$orderDelivery->operation->startshipment}} </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-events">Preparado en piso / Armado</td>
                                                            <td class="td-events">
                                                                @if ($orderDelivery->operation->startshipment != '')
                                                                    @if ($orderDelivery->operation->endchecker == '')
                                                                        <a href=""
                                                                           data-target="#modal-addendchecker-{{$orderDelivery->id}}"
                                                                           data-toggle="modal">
                                                                            <button
                                                                                    class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                                                <img src="{{asset('/ico/priority.png')}}"
                                                                                     alt="" class="img-16 ml-2 mr-2">
                                                                                <span
                                                                                        style="font-size: 0.675rem "> Iniciar</span>
                                                                            </button>
                                                                        </a>
                                                                        @include('Shipments.OrderMonitor.MonitorOperation.Events.addendchecker')
                                                                    @else
                                                                        <span style="font-size: 11px;">{{$orderDelivery->operation->endchecker}}
                                                                </span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-events">Inicio de Verificación</td>
                                                            <td class="td-events">
                                                             <span class="font-12">
                                                                   {{ $orderDelivery->check ? $orderDelivery->check->startdate : ''}}
                                                              </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-events">Fin de Verificación</td>
                                                            <td class="td-events">
                                                            <span style="font-size: 11px;">
                                                                {{ $orderDelivery->check ? $orderDelivery->check->finishdate : ''}}
                                                            </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-events">Por cargar</td>
                                                            <td class="td-events">
                                                                @if ($orderDelivery->operation->endchecker != '')
                                                                    @if ($orderDelivery->operation->forload == '')
                                                                        @can('forloadshipment')
                                                                            <a href=""
                                                                               data-target="#modal-forload-{{$orderDelivery->id}}"
                                                                               data-toggle="modal">
                                                                                <button
                                                                                        class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                                                    <img
                                                                                            src="{{asset('/ico/priority.png')}}"
                                                                                            alt=""
                                                                                            class="img-16 ml-2 mr-2">
                                                                                    <span style="font-size: 0.675rem "> Iniciar</span>
                                                                                </button>
                                                                            </a>
                                                                            @include('Shipments.OrderMonitor.MonitorOperation.Events.forload')
                                                                        @endcan
                                                                    @else
                                                                        <span
                                                                                style="font-size: 11px;">{{$orderDelivery->operation->forload}}</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-events">Inicio de carga</td>
                                                            <td class="td-events">
                                                                @if ($orderDelivery->operation->forload != '')
                                                                    @if ($orderDelivery->operation->startload == '')
                                                                        @can('startload')
                                                                            <a href=""
                                                                               data-target="#modal-addstartload-{{$orderDelivery->id}}"
                                                                               data-toggle="modal">
                                                                                <button
                                                                                        class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                                                    <img
                                                                                            src="{{asset('/ico/priority.png')}}"
                                                                                            alt=""
                                                                                            class="img-16 ml-2 mr-2">
                                                                                    <span style="font-size: 0.675rem "> Iniciar</span>
                                                                                </button>
                                                                            </a>
                                                                            @include('Shipments.OrderMonitor.MonitorOperation.Events.addstartload')
                                                                        @endcan
                                                                    @else
                                                                        <span
                                                                                style="font-size: 11px;">{{$orderDelivery->operation->startload}}</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-events">Fin de carga</td>
                                                            <td class="td-events">
                                                                @if ($orderDelivery->operation->startload != '')
                                                                    @if ($orderDelivery->operation->endload == '')
                                                                        @can('finishload')
                                                                            <a href=""
                                                                               data-target="#modal-addendload-{{$orderDelivery->id}}"
                                                                               data-toggle="modal">
                                                                                <button
                                                                                        class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                                                    <img
                                                                                            src="{{asset('/ico/priority.png')}}"
                                                                                            alt=""
                                                                                            class="img-16 ml-2 mr-2">
                                                                                    <span style="font-size: 0.675rem "> Iniciar</span>
                                                                                </button>
                                                                            </a>
                                                                            @include('Shipments.OrderMonitor.MonitorOperation.Events.addendload')
                                                                        @endcan
                                                                    @else
                                                                        <span
                                                                                style="font-size: 11px;">{{$orderDelivery->operation->endload}}</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="td-events">Enviado a Facturación</td>
                                                            <td class="td-events">
                                                                @if ($orderDelivery->operation->endload != '')
                                                                    @if ($orderDelivery->operation->senddocument == '')
                                                                        <a href=""
                                                                           data-target="#modal-addsenddocument-{{$orderDelivery->id}}"
                                                                           data-toggle="modal">
                                                                            <button
                                                                                    class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                                                <img src="{{asset('/ico/priority.png')}}"
                                                                                     alt="" class="img-16 ml-2 mr-2">
                                                                                <span
                                                                                        style="font-size: 0.675rem "> Iniciar</span>
                                                                            </button>
                                                                        </a>
                                                                        @include('Shipments.OrderMonitor.MonitorOperation.Events.addsenddocument')
                                                                    @else
                                                                        <span
                                                                                style="font-size: 11px;">{{$orderDelivery->operation->senddocument}}</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
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
                    <div class="col-lg-8">

                        <hr class="m-t-1">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#view01" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home font-12"></i></span> <span
                                            class="hidden-xs-down ml-3 mr-3">Datos del embarque</span>
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02" role="tab"><span
                                            class="hidden-sm-up"><i class="ti-comment font-12"></i></span> <span
                                            class="hidden-xs-down ml-3 mr-3">Comentario</span></a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-arrows-vertical font-12"></i></span>
                                    <span class="hidden-xs-down ml-3 mr-3">Control de tarimas</span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="view01" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                Datos generales del embarque
                                            </div>
                                            <div class="card-body">
                                                <form method="POST"
                                                      action="{{url('monitoroperation/' . $orderDelivery->id)}}"
                                                      enctype="multipart/form-data" id="form-edit-save-operation">
                                                    {{ method_field('PUT') }}
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <button type="submit"
                                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                                    id="submitBtnSaveOperation">
                                                                <img src="{{asset('/ico/save02.png')}}" alt=""
                                                                     class="img-16">
                                                                <span class="text-button"> Guardar Datos</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr class="m-t-1">
                                                    <div class="row mt-3">
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="supervisor">Supervisor en turno</label>
                                                                <input type="text" class="form-control" id="supervisor"
                                                                       name="supervisor" maxlength="100"
                                                                       aria-describedby="supervisor"
                                                                       value="{{$orderDelivery->operation ? $orderDelivery->operation->supervisor : ''}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="checker">Verificador de embarque </label>
                                                                <input type="text" class="form-control" id="checker"
                                                                       name="checker" maxlength="100"
                                                                       aria-describedby="checker"
                                                                       value="{{$orderDelivery->operation ? $orderDelivery->operation->checker : ''}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="forklift">Montacarguista de carga </label>
                                                                <input type="text" class="form-control" id="forklift"
                                                                       name="forklift" maxlength="100"
                                                                       aria-describedby="forklift"
                                                                       value="{{$orderDelivery->operation ? $orderDelivery->operation->forklift : ''}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="auxiliary02">% de Armado </label>
                                                                <input type="text"
                                                                       class="form-control  numeric"
                                                                       id="auxiliary02"
                                                                       name="auxiliary02" maxlength="100"
                                                                       aria-describedby="auxiliary02"
                                                                       value="{{$orderDelivery->operation ? $orderDelivery->operation->auxiliary02 : ''}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="auxiliary01">Prioridad de embarque </label>
                                                                <input type="text" class="form-control numeric"
                                                                       id="auxiliary01"
                                                                       name="auxiliary01" maxlength="100"
                                                                       aria-describedby="auxiliary01"
                                                                       value="{{$orderDelivery->operation ? $orderDelivery->operation->auxiliary01 : ''}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-10">
                                                            <hr class="hr-line">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="custom-control custom-checkbox mt-2 ">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input switch"
                                                                               value="1"
                                                                               name="fullpallet" id="check01"
                                                                               @if ($orderDelivery->operation ? $orderDelivery->operation->fullpallet : '' == 1)
                                                                                   checked
                                                                                @endif
                                                                        >
                                                                        <label class="custom-control-label"
                                                                               for="check01">
                                                                            Pallets terminados
                                                                            <span class="text-black-50"
                                                                                  style="font-size: .750rem !important;">
                                                                            (Marque esta casilla si el proceso de recolección de pallets ya finalizó ).
                                                                        </span>
                                                                        </label>
                                                                    </div>
                                                                    <hr class="mt-2">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               value="1"
                                                                               id="check2" name="fullpicking"
                                                                               @if ($orderDelivery->operation ? $orderDelivery->operation->fullpicking : '' == 1)

                                                                                   checked
                                                                                @endif>
                                                                        <label class="custom-control-label"
                                                                               for="check2">
                                                                            Restos terminados <span
                                                                                    class="text-black-50"
                                                                                    style="font-size: .750rem !important;">(Marque esta casilla si el proceso de piking ya finalizó. ) </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="view02" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                Comentarios del embarque
                                            </div>
                                            <div class="card-body">

                                                <form method="POST"
                                                      action="{{url('savecommentoperation/' . $orderDelivery->id)}}"
                                                      enctype="multipart/form-data" id="form-edit-save-comment">
                                                    {{ method_field('PUT') }}
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <button type="submit"
                                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                                    id="submitBtnSaveComment">
                                                                <img src="{{asset('/ico/save02.png')}}" alt=""
                                                                     class="img-16">
                                                                <span class="text-button"> Guardar comentarios</span>
                                                            </button>

                                                        </div>
                                                    </div>
                                                    <hr class="m-t-1">
                                                    <div class="row mt-3 mt-3">
                                                        <div class="col-lg-8 col-md-6 col-sm-12">
                                                            <label for="comment01" class="form-label">Comentario
                                                                01</label>
                                                            <textarea class="form-control" id="comment01"
                                                                      name="comment01"
                                                                      rows="2">{{$orderDelivery->operation ? $orderDelivery->operation->comment01 : ''}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-lg-8 col-md-6 col-sm-12">
                                                            <label for="comment02" class="form-label">Comentario
                                                                02</label>
                                                            <textarea class="form-control" id="comment02"
                                                                      name="comment02"
                                                                      rows="2">{{$orderDelivery->operation ? $orderDelivery->operation->comment02 : ''}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-lg-8 col-md-6 col-sm-12">
                                                            <label for="comment03" class="form-label">Comentario
                                                                03</label>
                                                            <textarea class="form-control" id="comment03"
                                                                      name="comment03"
                                                                      rows="2">{{$orderDelivery->operation ? $orderDelivery->operation->comment03 : ''}}</textarea>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="view03" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                Ingrese la cantidad de tarimas que se cargaran en el viaje, podra
                                                ingresar
                                                más de
                                                un tipo de tarima ingresando la cantidad en el apartado indicado.
                                            </div>
                                            <div class="card-body">

                                                <form method="POST"
                                                      action="{{url('savepalletstoshipment/' . $orderDelivery->id)}}"
                                                      enctype="multipart/form-data" id="form-edit-save-pallets">
                                                    {{ method_field('PUT') }}
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <button type="submit"
                                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                                    id="submitBtnSavePallet">
                                                                <img src="{{asset('/ico/save02.png')}}" alt=""
                                                                     class="img-16">
                                                                <span class="text-button"> Guardar tarimas</span>
                                                            </button>

                                                        </div>
                                                    </div>
                                                    <hr class="m-t-1">
                                                    <div class="row mt-3">
                                                        <div class="col-lg-12">
                                                            <table class="table table-bordered table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th>Sku</th>
                                                                    <th>Descripción</th>
                                                                    <th>Cantidad</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($materials as $material)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                   name="prtnum[]"
                                                                                   value="{{$material->id}}"
                                                                                   readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                   value="{{$material->lngdsc}}"
                                                                                   readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="quantity[]"
                                                                                   class="form-control numeric"
                                                                                   id="quantity">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-lg-12">
                                                            <table
                                                                    class="table table-bordered table-condensed tablegeneral">
                                                                <thead>
                                                                <tr>
                                                                    <th>Sku</th>
                                                                    <th>Descripción</th>
                                                                    <th>Cantidad</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($orderDelivery->pallets as $item)
                                                                    <tr>
                                                                        <td>{{$item->prtnum}}</td>
                                                                        <td>{{$item->partnumber->lngdsc}}</td>
                                                                        <td>{{$item->quantity}}</td>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    @include('layouts.Partials.Loading')

    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>

    <script>
        // Función para mostrar el loading
        function showLoading() {
            const overlay = document.getElementById('loading-overlay');
            overlay.style.display = 'flex';
            overlay.classList.add('show');
        }

        // Función para ocultar el loading (en caso de error)
        function hideLoading() {
            const overlay = document.getElementById('loading-overlay');
            overlay.style.display = 'none';
            overlay.classList.remove('show');
        }

        // Formulario de operaciones
        document.getElementById('form-edit-save-operation').addEventListener('submit', function (e) {
            const btn = document.getElementById('submitBtnSaveOperation');
            btn.disabled = true;
            showLoading();
        });

        // Formulario de comentarios
        document.getElementById('form-edit-save-comment').addEventListener('submit', function (e) {
            const btn = document.getElementById('submitBtnSaveComment');
            btn.disabled = true;
            showLoading();
        });

        // Formulario de tarimas
        document.getElementById('form-edit-save-pallets').addEventListener('submit', function (e) {
            const btn = document.getElementById('submitBtnSavePallet');
            btn.disabled = true;
            showLoading();
        });

        // Event listeners para modales
        const modalForms = [
            { formId: 'saveFormModalStartShipment', btnId: 'submitBtnStartShipment' },
            { formId: 'saveFormAddendchecker', btnId: 'submitBtnEndCheck' },
            { formId: 'saveFormForLoad', btnId: 'submitBtnForLoad' },
            { formId: 'saveFormAddLoad', btnId: 'submitBtnStartLoad' },
            { formId: 'saveFormEndLoad', btnId: 'submitBtnEndLoad' },
            { formId: 'saveFormSendDocument', btnId: 'submitBtnSendDocument' }
        ];

        modalForms.forEach(function(modal) {
            const form = document.getElementById(modal.formId);
            if (form) {
                form.addEventListener('submit', function() {
                    const btn = document.getElementById(modal.btnId);
                    if (btn) {
                        btn.disabled = true;
                    }
                    showLoading();
                });
            }
        });

    </script>
@endsection

