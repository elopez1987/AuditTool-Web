<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="name">Nombre
                        @error("name")
                        <span class="message-error">{{$message}}</span>
                        @enderror
                        <span style="color: red">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           required id="name" name="name"
                           aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                           value="{{$namesystem->name}}"
                    >
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="version">Versión
                        @error("version")
                        <span class="message-error">{{$message}}</span>
                        @enderror
                        <span style="color: red">*</span></label>
                    <input type="text" class="form-control @error('version') is-invalid @enderror"
                           required id="version" name="version"
                           aria-describedby="version" onKeyUp=this.value=this.value.toUpperCase();
                           value="{{$namesystem->version}}"
                    >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="auxiliary01">Auxiliar 01 </label>
                    <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                           aria-describedby="auxiliary01" value="{{$namesystem->auxiliary01}}">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="auxiliary02">Auxiliar 02 </label>
                    <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                           aria-describedby="auxiliary02" value="{{$namesystem->auxiliary02}}">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="auxiliary03">Auxiliar 03 </label>
                    <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                           aria-describedby="auxiliary03" value="{{$namesystem->auxiliary03}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="auxiliary04">Auxiliar 04 </label>
                    <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                           aria-describedby="auxiliary04" value="{{$namesystem->auxiliary04}}">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="auxiliary05">Auxiliar 05 </label>
                    <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                           aria-describedby="auxiliary04" value="{{$namesystem->auxiliary04}}">
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="auxiliary05">Mensaje de Notificación</label>
                    <input type='file' name="file" id="file" class="form-control">
                </div>
            </div>

        </div>
    </div>
</div>
