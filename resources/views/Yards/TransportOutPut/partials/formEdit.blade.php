<div class="row">
    <div class="col-lg-6 col-sm-12">
        <div style="border:2px solid #0f6674;border-radius: 5px;padding: 5px">
            <table class="table table-condensed  table-striped">
                <tbody>
                <tr>
                    <td>FOLIO :</td>
                    <td>{{$receiptunit->id}}</td>
                </tr>
                <tr>
                    <td>LINEA :</td>
                    <td>{{$receiptunit->carrier->name}}</td>
                </tr>
                <tr>
                    <td>PLACA :</td>
                    <td>{{$receiptunit->boxnumber}}</td>
                </tr>
                <tr>
                    <td>OPERADOR :</td>
                    <td>{{$receiptunit->operatorname}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr class="m-t-1">
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="barcodetruck">Ingrese el número de código de barra del tracto camión<span
                    style="color: red">*</span></label>
            <input type="text" class="form-control" required id="barcodetruck" name="barcodetruck"
                   value="{{$receiptunit->auxiliary01}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
@if($receiptunit->razonstatusid == 2)
    <div class="row mb-4 mt-3">
        <div class="col-lg-4 col-sm-12">
            <div class="card p-2" style="border: 1px dotted #0f6674;border-radius: 4px">
                  <span class="font-12 font-italic">
                    <i class="fa fa-1x fa-exclamation-triangle text-danger mr-2"></i>
                    Los campos de Embarque y Sellos solo se mostrarán si la unidad entró a cargar.
                  </span>
            </div>
        </div>
    </div>
    <div class="row hide">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="shipmentid">Ingrese el número de embarque de salida
                    el remolque, en caso de ir vacio colocar 0. <span style="color: red">*</span></label>
                <input type="text" class="form-control" required id="shipmentid" name="shipmentid"
                       value="0"
                       onKeyUp=this.value=this.value.toUpperCase()
                >
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="containerseal">Ingrese el valor de los sellos del remolque,
                    en caso de ir vacio colocar el valor de 0, si el remolque cuenta con más de un sello separarlo por
                    una /.
                    <span style="color: red">*</span></label>
                <input type="text" class="form-control" required id="containerseal"
                       name="containerseal" value="0"
                       onKeyUp=this.value=this.value.toUpperCase()
                >
            </div>
        </div>
    </div>
@else
    <div class="row ">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="shipmentid">Ingrese el número de embarque de salida.</label>
                <input type="text" class="form-control" required id="shipmentid" name="shipmentid"
                       onKeyUp=this.value=this.value.toUpperCase()
                >
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="containerseal">Ingrese el valor de los sellos del remolque.</label>
                <input type="text" class="form-control" required id="containerseal" name="containerseal"
                       onKeyUp=this.value=this.value.toUpperCase()
                >
            </div>
        </div>
    </div>
@endif

@if($checklist == 'on')
    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped table-condensed table-bordered">
                <thead>
                <tr>
                    <th class="hide"></th>
                    <th>Descripción</th>
                    <th style="width: 15%">SI</th>
                    <th style="width: 15%">NO</th>
                    <th style="width: 15%">NO APLICA</th>
                </tr>
                </thead>
                <tbody>
                @foreach($checklistdetails as $item)
                    <tr>
                        <td class="hide"><input type="text" value="{{$item->checklistid}}" id="checklistid[]" name="checklistid[]" class="hide"></td>
                        <td class="hide"><input type="text" value="{{$item->checklitsdetailid}}" id="checklitsdetailid[]" name="checklitsdetailid[]" class="hide"></td>
                        <td>{{$item->description}}</td>
                        <td><input type="radio" id="response[]" required value="SI" name="response[]-{{$item->checklitsdetailid}}"></td>
                        <td><input type="radio" id="response[]" required value="NO" name="response[]-{{$item->checklitsdetailid}}"></td>
                        <td><input type="radio" id="response[]" required value="NA" name="response[]-{{$item->checklitsdetailid}}"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
