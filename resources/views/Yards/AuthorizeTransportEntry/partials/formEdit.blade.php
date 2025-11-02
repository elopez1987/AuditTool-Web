<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="folionumber">Folio de patio<span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="folionumber" name="folionumber"
                   value="{{$receiptunit->id}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="operatorname">Nombre de operador<span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="operatorname" name="operatorname"
                   value="{{$receiptunit->operatorname}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="boxnumber">Placa de caja<span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="boxnumber" name="boxnumber"
                   value="{{$receiptunit->boxnumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="trucknumber">Placa de tracto<span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="trucknumber" name="trucknumber"
                   value="{{$receiptunit->trucknumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="containerseal">Sellos<span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="containerseal" name="containerseal"
                   value="{{$receiptunit->containerseal}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <table class="table table-condensed table-bordered tablegeneral">
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
                    <td class="hide"><input type="text" value="{{$item->checklistid}}" id="checklistid[]"
                                            name="checklistid[]" class="hide"></td>
                    <td class="hide"><input type="text" value="{{$item->checklitsdetailid}}" id="checklitsdetailid[]"
                                            name="checklitsdetailid[]" class="hide"></td>
                    <td>{{$item->description}}</td>
                    <td class="switch" style="width: 70px;">
                        <input type="radio" id="response[]" required value="SI" name="response[]-{{$item->checklitsdetailid}}"
                               data-size="mini" data-on-color="success" data-off-color="danger"
                               data-on-text="<i class='ti ti-check'></i>"
                               data-off-text="<i class='ti ti-close'></i>"
                        >
                    </td>
                    <td class="switch" style="width: 70px;">
                        <input type="radio" id="response[]" required value="NO" name="response[]-{{$item->checklitsdetailid}}"
                               data-size="mini" data-on-color="success" data-off-color="danger"
                               data-on-text="<i class='ti ti-check'></i>"
                               data-off-text="<i class='ti ti-close'></i>"
                        >
                    </td>
                    <td class="switch" style="width: 70px;">
                        <input type="radio" id="response[]" required value="NA" name="response[]-{{$item->checklitsdetailid}}"
                               data-size="mini" data-on-color="success" data-off-color="danger"
                               data-on-text="<i class='ti ti-check'></i>"
                               data-off-text="<i class='ti ti-close'></i>"
                        >
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="">Comentario</label>
            <textarea class="form-control" rows="5" name="comment"
                      placeholder="Ingrese un comentario corto..."></textarea>
        </div>
    </div>
</div>
