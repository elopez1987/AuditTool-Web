<div class="row">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Nombre<span style="color: red">*</span></label>
                        <input type="text" class="form-control" required id="name" name="name"
                               value="{{$user->name}}"
                        >
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="email">Correo electronico</label>
                        <input type="email" class="form-control" readonly id="email" name="email"
                               value="{{$user->email}}"
                        >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" class="form-control" readonly id="username" name="username"
                               value="{{$user->username}}"
                        >
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="role_id">Perfil de usuario <span style="color: red">*</span></label>
                        <select name="role_id" class="form-control select2">
                            @foreach($roles as $rol)
                                <option value="{{$rol->name}}"
                                        @if($rol->id == $access->role_id)
                                            selected="selected"
                                    @endif>
                                    {{$rol->description}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="username">Estatus</label>
                        <input type="text" class="form-control" readonly id="username" name="username"
                               value="{{$user->status->name}}"
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
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auxiliary01">Auxiliary 01</label>
                        <input type="text" class="form-control numeric" id="auxiliary01" name="auxiliary01"
                               maxlength="10"
                               aria-describedby="auxiliary01" value="{{$user->auxiliary01}}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auxiliary02">Auxiliar 02 </label>
                        <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                               aria-describedby="auxiliary02" value="{{$user->auxiliary02}}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auxiliary03">Auxiliar 03 </label>
                        <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                               aria-describedby="auxiliary03" value="{{$user->auxiliary03}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auxiliary04">Auxiliar 04 </label>
                        <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                               aria-describedby="auxiliary04" value="{{$user->auxiliary04}}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auxiliary05">Auxiliar 05 </label>
                        <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                               aria-describedby="auxiliary05" value="{{$user->auxiliary05}}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auth0">Auth0 </label>
                        <input type="text" class="form-control" id="auth0" name="auth0" maxlength="100"
                               aria-describedby="auth0" value="{{$user->auth0}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


