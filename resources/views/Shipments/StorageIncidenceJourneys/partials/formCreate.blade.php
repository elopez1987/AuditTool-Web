<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="delivery">Factura <span style="color: red">*</span></label>
                            <select name="delivery" id="delivery" required
                                    class="form-control select2 delivery"></select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="partnumberid">Número de parte o IDH</label>
                            <input type="text" class="form-control" required id="partnumberid" name="partnumberid"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="quantity">Cantidad</label>
                            <input type="text" class="form-control numeric" required id="quantity" name="quantity"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="downloaddate">Fecha de trabajo</label>
                            <input type="text" class="form-control workday" required id="downloaddate"
                                   name="downloaddate"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="unitmeasure">Unidad de medida <span style="color: red">*</span></label>
                            <select name="unitmeasure" id="unitmeasure" required class="form-control select2">
                                <option value="PZ"> PIEZAS</option>
                                <option value="CS" selected> CAJAS</option>
                                <option value="PL"> PALLETS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="razoncode">Motivo <span style="color: red">*</span></label>
                            <select name="razoncode" id="razoncode" required class="form-control select2">
                                <option value="" selected>Seleecione un opción</option>
                                @foreach($razoncodes as $model)
                                    <option value="{{$model->categoryincdetailid}}"> {{$model->description}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="hide" id="codigo">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partnumberiderror">Número de parte</label>
                                <input type="text" class="form-control" id="partnumberiderror" name="partnumberiderror"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="comment">Comentario</label>
                            <textarea class="form-control" id="comment" name="comment" rows="5"> </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <button type="button"
                                class="btn waves-effect waves-light btn-outline-info btn-block btn-md"
                                id="saveIncidence">
                            <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                            <span style="font-size: 11px;"> Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

