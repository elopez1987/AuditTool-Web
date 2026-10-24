<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-updatedetail-{{$detail->categoryincdetailid}}">
    <form method="POST" action="{{url('updatedetail/' . $detail->categoryincdetailid)}}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">
                        Actualizar Detalle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="description">Tipo</label>
                                <input type="text" class="form-control @error('auxiliary01') is-invalid @enderror"
                                       id="description" name="description" maxlength="100"
                                       aria-describedby="description" value="{{$detail->description}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary01">Tipo</label>
                                <input type="text" class="form-control @error('auxiliary01') is-invalid @enderror"
                                       id="auxiliary01" name="auxiliary01" maxlength="100" readonly
                                       aria-describedby="auxiliary01" value="{{$detail->auxiliary01}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary02">Auxiliar 02 </label>
                                <input type="text" class="form-control" id="auxiliary02" name="auxiliary02"
                                       maxlength="100"
                                       aria-describedby="auxiliary02" value="{{$detail->auxiliary02}}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary03">Auxiliar 03 </label>
                                <input type="text" class="form-control" id="auxiliary03" name="auxiliary03"
                                       maxlength="100"
                                       aria-describedby="auxiliary03" value="{{$detail->auxiliary03}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary04">Auxiliar 04 </label>
                                <input type="text" class="form-control" id="auxiliary04" name="auxiliary04"
                                       maxlength="100"
                                       aria-describedby="auxiliary04" value="{{$detail->auxiliary04}}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary05">Auxiliar 05 </label>
                                <input type="text" class="form-control" id="auxiliary05" name="auxiliary05"
                                       maxlength="100"
                                       aria-describedby="auxiliary05" value="{{$detail->auxiliary05}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="statusid">Estatus </label>
                                <select name="statusid" class="form-control select2">
                                    @foreach($status as $statu)
                                        <option value="{{$statu->statusid}}"
                                                @if($statu->statusid == $detail->statusid)
                                                    selected="selected"
                                            @endif>
                                            {{$statu->name}}
                                        </option>
                                    @endforeach
                                </select>
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
