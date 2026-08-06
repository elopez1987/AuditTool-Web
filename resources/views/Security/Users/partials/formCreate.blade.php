<div class="row">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
                        <select name="centerid" required class="form-control select2">
                            @foreach($centers as $center)
                                <option value="{{$center->centerid}}"> {{$center->name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                        <select name="warehouseid" required class="form-control select2">
                            @foreach($warehouses as $warehouse)
                                <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Nombre completo
                            @error('name')
                            <span class="message-error">{{$message}}</span>
                            @enderror
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" required id="name"
                               name="name"
                               aria-describedby="name" value="{{old('name')}}"
                               onKeyUp=this.value=this.value.toUpperCase()
                        >
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="email">Correo electronico
                            @error('email')
                            <span class="message-error">{{$message}}</span>
                            @enderror
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror " required
                               id="email" name="email"
                               aria-describedby="email" value="{{old('email')}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="username">
                            Usuario
                            @error('username')
                            <span class="message-error">{{$message}}</span>
                            @enderror
                        </label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" required
                               id="username" name="username" maxlength="12"
                               aria-describedby="username" value="{{old('username')}}">
                    </div>

                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="password">Contraseña
                            @error('password')
                            <span class="message-error">{{$message}}</span>
                            @enderror
                        </label>
                        <input type="text" class="form-control @error('password') is-invalid @enderror" required
                               id="password" name="password" maxlength="30"
                               aria-describedby="password" value="{{old('password')}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="role_id">Perfil de usuario <span style="color: red">*</span></label>
                        <select name="role_id" required class="form-control select2">
                            <option value="">Seleccione una opción</option>
                            @foreach($roles as $rol)
                                <option value="{{$rol->name}}"> {{$rol->description}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Número de contacto </label>
                        <input type="text" class="form-control numeric" id="auxiliary01" name="auxiliary01" maxlength="10"
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
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Auxiliar 03 </label>
                        <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                               aria-describedby="auxiliary03" value="{{old('auxiliary03')}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Auxiliar 04 </label>
                        <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                               aria-describedby="auxiliary04" value="{{old('auxiliary04')}}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Auxiliar 05 </label>
                        <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                               aria-describedby="auxiliary05" value="{{old('auxiliary05')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


