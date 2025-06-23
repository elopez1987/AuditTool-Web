<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-update-{{$item->id}}">
    <form method="POST" action="{{url('updatepallets/' . $item->id)}}">
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
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partnumberidmod">Linea de transporte <span style="color: red">*</span></label>
                                <select required name="partnumberidmod" id="partnumberidmod" class="form-control select2">
                                    @foreach($materials as $model)
                                        <option value="{{$model->id}}"
                                            {{ $item->partnumberid == $model->id ? 'selected' : '' }} >
                                            {{$model->lngdsc}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('partnumberidmod')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="quantitymod">Cantidad de tarimas
                                    <span style="color: red">*</span>
                                    @error("quantitymod")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                </label>
                                <input type="text"
                                       class="form-control numeric @error('quantitymod') is-invalid @enderror"
                                       id="quantitymod"
                                       name="quantitymod"
                                       required
                                       maxlength="3"
                                       onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$item->quantity}}"
                                >
                                @error('quantitymod')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="damagemod">Cantidad de tarimas dañadas.
                                    <span style="color: red">*</span>
                                    @error("damagemod")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                </label>
                                <input type="text"
                                       class="form-control numeric @error('damagemod') is-invalid @enderror"
                                       id="damagemod"
                                       name="damagemod"
                                       required
                                       maxlength="3"
                                       onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$item->damage}}"
                                >
                                @error('damagemod')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
