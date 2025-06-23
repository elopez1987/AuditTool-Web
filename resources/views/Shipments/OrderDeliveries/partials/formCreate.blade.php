<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid" class="form-control select2 @error('centerid') is-invalid @enderror">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
            <select name="warehouseid" id="warehouseid" class="form-control select2 @error('warehouseid') is-invalid @enderror">
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
            <label for="workday">Fecha de trabajo
                @error("workday")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('workday') is-invalid @enderror" required id="workday" name="workday" value="{{old('workday')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="turnid">Turno<span style="color: red">*</span></label>
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
            <label for="shipmentid">Embarque
                @error("shipmentid")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                   required id="shipmentid" name="shipmentid" maxlength="20" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('shipmentid')}}"
                   pattern="[a-zA-Z0-9]+" title="Solo se permiten números y letras (sin espacios ni caracteres especiales)">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
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
            <label for="customerid">Cliente<span style="color: red">*</span></label>
            <select name="customerid" id='customerid' required class="form-control select2 @error('customerid') is-invalid @enderror">
                <option value="" selected>Seleccione una opción</option>
                @foreach($customers as $model)
                    <option value="{{$model->id}}" {{ old('customerid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary03">Embarque complemento </label>
            <input type="text" class="form-control @error('auxiliary03') is-invalid @enderror"
                   id="auxiliary03" name="auxiliary03" maxlength="15"
                     onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('auxiliary03')}}"  pattern="[a-zA-Z0-9]+"
                   title="Solo se permiten números y letras (sin espacios ni caracteres especiales)"
            >
        </div>
    </div>
</div>
<hr class="m-t-0">
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmenttypeid">Tipo de embarque<span style="color: red">*</span></label>
            <select name="shipmenttypeid" id='shipmenttypeid' required class="form-control select2 @error('shipmenttypeid') is-invalid @enderror">
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
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="totalpieces">Total de cajas</label>
            <input type="text" class="form-control numeric @error('totalpieces') is-invalid @enderror"
                    id="totalpieces" name="totalpieces" maxlength="10" value="{{old('totalpieces')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="totalcontainers">Total de pallets
                @error("totalcontainers")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control numeric @error('totalcontainers') is-invalid @enderror"
                    id="totalcontainers" name="totalcontainers" maxlength="10" value="{{old('totalcontainers')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="totalweight">Peso total
                @error("totalweight")
                <span class="message-error">{{$message}}</span>
                @enderror
               </label>
            <input type="text" class="form-control @error('totalweight') is-invalid @enderror "
                    id="totalweight" name="totalweight" maxlength="20" value="{{old('totalweight')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="folionumber">Número de folio
                @error("folionumber")
                <span class="message-error">{{$message}}</span>
                @enderror
               </label>
            <input type="text" class="form-control numeric @error('folionumber') is-invalid @enderror"
                    id="folionumber" name="folionumber" maxlength="10"
                   value="{{old('folionumber')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="confirmation">Número de confirmación
                @error("confirmation")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('confirmation') is-invalid @enderror"
                    id="confirmation" name="confirmation" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('confirmation')}}"
            >
        </div>
    </div>
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
