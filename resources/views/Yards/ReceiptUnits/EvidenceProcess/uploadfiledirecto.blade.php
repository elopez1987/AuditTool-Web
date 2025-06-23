<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-file-{{$item->statusid}}">
    <form method="POST" action="{{url('saveEvidenceProcessForCheckList')}}" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Subir evidencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="typeshipment">Tipo de embarque</label>
                                <input type="text" class="form-control"  readonly id="typeshipment" name="typeshipment"
                                       value="TDIRECTO"
                                >
                            </div>
                        </div>
                    </div>
                   <div class="row">
                       <div class="col-lg-4">
                           <div class="form-group">
                               <label for="folioid">Folio</label>
                               <input type="text" class="form-control"  readonly id="folioid" name="folioid"
                                      value="{{$receiptunit->id}}"
                               >
                           </div>
                       </div>
                       <div class="col-lg-4">
                           <div class="form-group">
                               <label for="statusid">Tipo de evidencia</label>
                               <input type="text" class="form-control"  readonly id="statusid" name="statusid"
                                      value="{{$item->statusid}}"
                               >
                           </div>
                       </div>
                       <div class="col-lg-4">
                           <div class="form-group">
                               <label for="name">Tipo de evidencia</label>
                               <input type="text" class="form-control"  readonly id="name" name="name"
                                      value="{{$item->name}}"
                               >
                           </div>
                       </div>
                   </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12"> Seleecione una evidencia</li>
                                <li class="list-group-item font-12">
                                    <input type='file' name="file" id="file" required>
                                </li>
                            </ul>
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
