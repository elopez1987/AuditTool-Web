<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">{{ ui_label('centerid_orderdelivery_create','Centro de distribución') }}<span style="color: red">*</span></label>
            <select name="centerid" id="centerid" class="form-control select2 @error('centerid') is-invalid @enderror">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">{{ ui_label('warehouseid_orderdelivery_create','Bodega') }} <span style="color: red">*</span></label>
            <select name="warehouseid" id="warehouseid"
                    class="form-control select2 @error('warehouseid') is-invalid @enderror">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->warehouseid}}" {{ old('warehouseid') == $warehouse->warehouseid ? 'selected' : '' }}> {{$warehouse->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="workday">{{ ui_label('workday_orderdelivery_create','Fecha de trabajo') }}
                @error("workday")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('workday') is-invalid @enderror" required id="workday"
                   name="workday" value="{{old('workday')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="turnid">{{ ui_label('turnid_orderdelivery_create','Turno') }}<span style="color: red">*</span></label>
            <select name="turnid" class="form-control select2 @error('turnid') is-invalid @enderror">
                <option value="" selected>Seleccione una opción</option>
                @foreach($turns as $model)
                    <option value="{{$model->id}}" {{ old('turnid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">{{ ui_label('shipmentid_orderdelivery_create','Embarque') }}
                @error("shipmentid")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                   required id="shipmentid" name="shipmentid" maxlength="20"
                   onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('shipmentid')}}"
                   pattern="[a-zA-Z0-9]+"
                   title="Solo se permiten números y letras (sin espacios ni caracteres especiales)">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">{{ ui_label('carrierid_orderdelivery_create','Linea de transporte') }}<span style="color: red">*</span></label>
            <select name="carrierid" required class="form-control select2">
                <option value="" selected>Seleccione una opción</option>
                @foreach($carriers as $model)
                    <option value="{{$model->id}}" {{ old('carrierid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="customerid">{{ ui_label('customerid_orderdelivery_create','Cliente') }}<span style="color: red">*</span></label>
            <select name="customerid" id='customerid' required
                    class="form-control select2 @error('customerid') is-invalid @enderror">
                <option value="" selected>Seleccione una opción</option>
                @foreach($customers as $model)
                    <option value="{{$model->id}}" {{ old('customerid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    @if(ui_visible('auxiliary03_orderdelivery_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="auxiliary03">{{ ui_label('auxiliary03_orderdelivery_create','Embarque complemento') }}</label>
                <input type="text" class="form-control @error('auxiliary03') is-invalid @enderror"
                       id="auxiliary03" name="auxiliary03" maxlength="15"
                       onKeyUp=this.value=this.value.toUpperCase();
                       value="{{old('auxiliary03')}}" pattern="[a-zA-Z0-9]+"
                       title="Solo se permiten números y letras (sin espacios ni caracteres especiales)"
                       @if(ui_required('auxiliary03_orderdelivery_create')) required @endif
                >
            </div>
        </div>
    @endif
</div>
<hr class="m-t-0">
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmenttypeid">Tipo de embarque<span style="color: red">*</span></label>
            <select name="shipmenttypeid" id='shipmenttypeid' required
                    class="form-control select2 @error('shipmenttypeid') is-invalid @enderror">
                <option value="" selected>Seleccione una opción</option>
                @foreach($shipmenttypes as $model)
                    <option value="{{$model->id}}" {{ old('shipmenttypeid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="loadingdate">Fecha de cita de carga
                @error("loadingdate")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('loadingdate') is-invalid @enderror"
                   required id="loadingdate" name="loadingdate" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('loadingdate')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="downloaddate">Cita de descarga
                @error("downloaddate")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('downloaddate') is-invalid @enderror"
                   required id="downloaddate" name="downloaddate" value="{{old('downloaddate')}}">
        </div>
    </div>
</div>
<div class="row">
    @if(ui_visible('totalpieces_orderdelivery_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="totalpieces">{{ ui_label('totalpieces_orderdelivery_create','Total de cajas pick') }}
                    @if(ui_required('totalpieces_orderdelivery_create'))
                        <span style="color: red">*</span>
                    @endif
                    @error("totalpieces")
                    <span class="message-error">{{$message}}</span>
                    @enderror
                </label>
                <input type="text" class="form-control numeric @error('totalpieces') is-invalid @enderror"
                       id="totalpieces" name="totalpieces" maxlength="10" value="{{old('totalpieces')}}"
                       @if(ui_required('totalpieces_orderdelivery_create')) required @endif
                >
            </div>
        </div>
    @endif
    @if(ui_visible('totalcontainers_orderdelivery_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="totalcontainers">{{ ui_label('totalcontainers_orderdelivery_create','Total de pallets') }}
                    @if(ui_required('totalcontainers_orderdelivery_create'))
                        <span style="color: red">*</span>
                    @endif
                    @error("totalcontainers")
                    <span class="message-error">{{$message}}</span>
                    @enderror
                </label>
                <input type="text" class="form-control numeric @error('totalcontainers') is-invalid @enderror"
                       id="totalcontainers" name="totalcontainers" maxlength="10" value="{{old('totalcontainers')}}"
                       @if(ui_required('totalcontainers_orderdelivery_create')) required @endif>
            </div>
        </div>
    @endif
    @if(ui_visible('totalweight_orderdelivery_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="totalweight">{{ ui_label('totalweight_orderdelivery_create','Peso total') }}
                    @if(ui_required('totalweight_orderdelivery_create'))
                        <span style="color: red">*</span>
                    @endif
                    @error("totalweight")
                    <span class="message-error">{{$message}}</span>
                    @enderror
                </label>
                <input type="text" class="form-control @error('totalweight') is-invalid @enderror "
                       id="totalweight" name="totalweight" maxlength="20" value="{{old('totalweight')}}"
                       @if(ui_required('totalweight_orderdelivery_create')) required @endif>
            </div>
        </div>
    @endif
</div>
<div class="row">

    @if(ui_visible('folionumber_orderdelivery_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="folionumber">{{ ui_label('folionumber_orderdelivery_create','Número de folio') }}
                    @if(ui_required('folionumber_orderdelivery_create'))
                        <span style="color: red">*</span>
                    @endif
                    @error("folionumber")
                    <span class="message-error">{{$message}}</span>
                    @enderror
                </label>
                <input type="text" class="form-control numeric @error('folionumber') is-invalid @enderror"
                       id="folionumber" name="folionumber" maxlength="10" value="{{old('folionumber')}}"
                       @if(ui_required('folionumber_orderdelivery_create')) required @endif>
            </div>
        </div>
    @endif

    @if(ui_visible('confirmation_shipment_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="confirmation">
                    {{ ui_label('confirmation_shipment_create','Número de confirmación') }}
                    @if(ui_required('confirmation_shipment_create'))
                        <span style="color: red">*</span>
                    @endif
                </label>
                <input
                        type="{{ ui_type('confirmation_shipment_create', 'text') }}"
                        class="form-control"
                        id="confirmation"
                        name="confirmation"
                        aria-describedby="confirmation"
                        @if(ui_required('confirmation_shipment_create')) required @endif
                >
            </div>
        </div>
    @endif

</div>
<hr class="m-t-4 m-b-4 ">
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="auxiliary04">Comentario</label>
            <textarea class="form-control" rows="5" id="comment" name="comment"></textarea>
        </div>
    </div>
</div>
