<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                Datos principales
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="name">Centro de distribución <span style="color: red">*</span></label>
                            <input type="text" class="form-control" required id="centerid" name="centerid" maxlength="15"
                                   value="{{old('centerid')}}"
                                   aria-describedby="centerid" onKeyUp=this.value=this.value.toUpperCase();>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="name">Descripción <span style="color: red">*</span></label>
                            <input type="text" class="form-control" required id="description" name="description" maxlength="50"
                                   aria-describedby="description" onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('description')}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="name">Nombre <span style="color: red">*</span></label>
                            <input type="text" class="form-control" required id="name" name="name" maxlength="50"
                                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase(); value="{{old('name')}}">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                Datos auxiliares
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="name">Auxiliar 01 </label>
                            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                                   aria-describedby="auxiliary01" value="{{old('auxiliary01')}}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="name">Auxiliar 02 </label>
                            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                                   aria-describedby="auxiliary02" value="{{old('auxiliary02')}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="name">Auxiliar 03 </label>
                            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                                   aria-describedby="auxiliary03" value="{{old('auxiliary03')}}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="name">Auxiliar 04 </label>
                            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                                   aria-describedby="auxiliary04" value="{{old('auxiliary04')}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



