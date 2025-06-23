<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="workday">Fecha de trabajo <span style="color: red">*</span></label>
            <input type="text" class="form-control workday" id="workday" name="workday"
                   value="{{$orderdelivery->workday}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Embarque <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                   value="{{$orderdelivery->shipmentid}}"
                   onKeyUp=this.value=this.value.toUpperCase()
                   pattern="[a-zA-Z0-9]+"
                   title="Solo se permiten números y letras (sin espacios ni caracteres especiales)"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="turnid">Turno <span style="color: red">*</span></label>
            <select required name="turnid" id="tunrid" class="form-control select2">
                @foreach($turns as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $orderdelivery->turnid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
            <select required name="carrierid" id="carrierid" class="form-control select2">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $orderdelivery->carrierid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="customerid">Cliente <span style="color: red">*</span></label>
            <select required name="customerid" id="customerid" class="form-control select2">
                @foreach($customers as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $orderdelivery->customerid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary03">Embarque complemento.</label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03"
                   value="{{$orderdelivery->auxiliary03}}"
                   onKeyUp=this.value=this.value.toUpperCase()
                   pattern="[a-zA-Z0-9]+"
                   title="Solo se permiten números y letras (sin espacios ni caracteres especiales)"
            >
        </div>
    </div>
</div>
<hr class="m-t-0">
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmenttypeid">Tipo de embarque <span style="color: red">*</span></label>
            <select required name="shipmenttypeid" id="shipmenttypeid" class="form-control select2">
                @foreach($shipmenttypes as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $orderdelivery->shipmenttypeid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="loadingdate">F. Cita de Carga <span style="color: red">*</span></label>
            <input type="text" class="form-control datefilter" id="loadingdate" name="loadingdate"
                   value="{{$orderdelivery->loadingdate}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="downloaddate">F. Cita de Descarga<span style="color: red">*</span></label>
            <input type="text" class="form-control datefilter" id="downloaddate" name="downloaddate"
                   value="{{$orderdelivery->downloaddate}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="totalpieces">Total de cajas</label>
            <input type="text" class="form-control numeric" id="totalpieces" name="totalpieces"
                   value="{{$orderdelivery->header->totalpieces}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="totalcontainers">Total de pallets</label>
            <input type="text" class="form-control numeric" id="totalcontainers" name="totalcontainers"
                   value="{{$orderdelivery->header->totalcontainers}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="totalweight">Peso total</label>
            <input type="text" class="form-control numeric" id="totalweight" name="totalweight"
                   value="{{$orderdelivery->header->totalweight}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="folionumber">Número de folio<span style="color: red">*</span></label>
            <input type="text" class="form-control numeric" id="folionumber" name="folionumber"
                   value="{{$orderdelivery->folionumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="confirmation">Número de confirmación<span style="color: red">*</span></label>
            <input type="text" class="form-control" id="confirmation" name="confirmation"
                   value="{{$orderdelivery->confirmation}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<hr class="m-t-0">
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary01">Auxiliar 01<span style="color: red">*</span></label>
            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01"
                   value="{{$orderdelivery->auxiliary01}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary02">Auxiliar 02<span style="color: red">*</span></label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02"
                   value="{{$orderdelivery->auxiliary02}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary04">Auxiliar 04<span style="color: red">*</span></label>
            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04"
                   value="{{$orderdelivery->auxiliary04}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
