<div class="row">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="shipmentid">Número de embarque <span
                                style="color: red">*</span></label>
                        <input type="text" class="form-control" readonly id="shipmentid"
                               name="shipmentid"
                               value="{{$transport->shipmentid}}"
                               onKeyUp=this.value=this.value.toUpperCase()
                        >
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 disabled">
                    <div class="form-group">
                        <label for="workday">Fecha de trabajo <span style="color: red">*</span></label>
                        <input type="text" class="form-control" readonly id="workday" name="workday"
                               value="{{$transport->workday}}"
                               onKeyUp=this.value=this.value.toUpperCase()
                        >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="loadingdate">Cita de carga <span style="color: red">*</span></label>
                        <input type="text" class="form-control" readonly id="loadingdate"
                               name="loadingdate"
                               value="{{$transport->loadingdate}}"
                               onKeyUp=this.value=this.value.toUpperCase()
                        >
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 readonly">
                    <div class="form-group">
                        <label for="downloaddate">Cita de entrega <span
                                style="color: red">*</span></label>
                        <input type="text" class="form-control" readonly id="downloaddate"
                               name="downloaddate"
                               value="{{$transport->downloaddate}}"
                               onKeyUp=this.value=this.value.toUpperCase()
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="card">
                <div class="card-header">
                    Datos de Operador
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="operatorname">Nombre de operador
                                    @error("operatorname")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('operatorname') is-invalid @enderror"
                                       required id="operatorname" name="operatorname"
                                       aria-describedby="operatorname" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport->operatorname}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="statusoperator">Estatus del operador
                                    @error("statusoperator")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                </label>
                                <input type="text" class="form-control @error('statusoperator') is-invalid @enderror"
                                       required id="statusoperator" name="statusoperator" readonly
                                       aria-describedby="statusoperator" onKeyUp=this.value=this.value.toUpperCase();
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone">Número de contacto
                                    @error("phone")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control numeric @error('phone') is-invalid @enderror"
                                       required id="phone" name="phone" minlength="10" maxlength="10"
                                       aria-describedby="phone" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport->phone}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="drivernumber">Número de licencia
                                    @error("drivernumber")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control  @error('drivernumber') is-invalid @enderror"
                                       required id="drivernumber" name="drivernumber" maxlength="30"
                                       aria-describedby="drivernumber" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport->drivernumber}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="carriername">Linea de transporte</label>
                                <input type="text" class="form-control" readonly id="carriername" name="carriername">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="rfccarrier">RFC de Línea de transporte</label>
                                <input type="text" class="form-control" readonly id="rfccarrier" name="rfccarrier">
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div id="check" class="custom-control custom-checkbox hide">
                                <input type="checkbox" class="custom-control-input" id="validateoperator"
                                       name="validateoperator">
                                <label class="custom-control-label" for="validateoperator">Operador validado en Recurso
                                    Confiable</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="card" >
                <div class="card-header">
                    Datos de Transporte
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="boxnumber">Placa de caja o remolque
                                    @error("boxnumber")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text"
                                       class="form-control LetrasNumeros @error('boxnumber') is-invalid @enderror"
                                       required id="boxnumber" name="boxnumber" maxlength="10"
                                       aria-describedby="boxnumber" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport->boxnumber}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary03">Eco. De Caja
                                    @error("auxiliary03")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('auxiliary03') is-invalid @enderror"
                                       required id="auxiliary03" name="auxiliary03" maxlength="9"
                                       aria-describedby="auxiliary03" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport->auxiliary03}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div id="tracto">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="trucknumber">Placa de tracto
                                        @error("trucknumber")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control  @error('trucknumber') is-invalid @enderror"
                                           required id="trucknumber" name="trucknumber" maxlength="10"
                                           aria-describedby="trucknumber" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{$transport->trucknumber}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary04">Eco. De tracto
                                        @error("auxiliary04")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('auxiliary04') is-invalid @enderror"
                                           required id="auxiliary04" name="auxiliary04" maxlength="9"
                                           aria-describedby="auxiliary04" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{$transport->auxiliary04}}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


