<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-updatestatus-{{$statu->statusid}}">
    <form method="POST" action="{{url('updatestatus/' . $statu->statusid)}}">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">
                        Actualizar registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class=" col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="centerid">Centro de distribución</label>
                                <select name="centerid" class="form-control select2">
                                    <option value="" selected></option>
                                    @foreach($centers as $center)
                                        <option value="{{$center->centerid}}"
                                                @if($center->centerid == $statu->centerid)
                                                    selected="selected"
                                                @endif>
                                            {{$center->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="warehouseid">Bodega</label>
                                <select name="warehouseid" class="form-control select2">
                                    <option value="" selected></option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{$warehouse->warehouseid}}"
                                                @if($warehouse->warehouseid == $statu->warehouseid)
                                                    selected="selected"
                                                @endif>
                                            {{$warehouse->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="statusid">Estatus
                                    @error("statusid")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                </label>
                                <input type="text" class="form-control" readonly id="statusid" name="statusid"
                                       maxlength="100"
                                       aria-describedby="statusid" value="{{$statu->statusid}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="namestatus">Nombre del Estatus </label>
                                <input type="text" class="form-control" required id="namestatus" name="namestatus"
                                       maxlength="100"
                                       aria-describedby="namestatus" value="{{$statu->name}}"
                                       onKeyUp=this.value=this.value.toUpperCase();
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="comment">Comentario </label>
                                <input type="text" class="form-control" id="comment" name="comment"
                                       maxlength="100"
                                       aria-describedby="comment" value="{{$statu->comment}}"
                                       onKeyUp=this.value=this.value.toUpperCase();
                                >
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary01">Auxiliar 01 </label>
                                <input type="text" class="form-control" id="auxiliary01" name="auxiliary01"
                                       maxlength="100"
                                       aria-describedby="auxiliary01" value="{{$statu->auxiliary01}}"
                                       onKeyUp=this.value=this.value.toUpperCase();
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary02">Auxiliar 02 </label>
                                <input type="text" class="form-control" id="auxiliary02" name="auxiliary02"
                                       aria-describedby="auxiliary02" value="{{$statu->auxiliary02}}"
                                >
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary03">Auxiliar 03 </label>
                                <input type="text" class="form-control" id="auxiliary03" name="auxiliary03"
                                       aria-describedby="auxiliary03" value="{{$statu->auxiliary03}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary04">Auxiliar 04 </label>
                                <input type="text" class="form-control" id="auxiliary04" name="auxiliary04"
                                       aria-describedby="auxiliary04" value="{{$statu->auxiliary04}}"
                                >
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary05">Auxiliar 05 </label>
                                <input type="text" class="form-control" id="auxiliary05" name="auxiliary05"
                                       maxlength="100"
                                       aria-describedby="auxiliary05" value="{{$statu->auxiliary05}}"
                                >
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
    </form>
</div>
