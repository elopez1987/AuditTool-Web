<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-updatestatus-{{$detail->id}}">
   <form method="POST" action="{{url('updatestatuscategory/' . $detail->id)}}" enctype="multipart/form-data">
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
                       <div class="col-md-6 col-sm-12">
                           <div class="form-group">
                               <label for="descriptionedit">Nombre </label>
                               <input type="text" class="form-control" required id="descriptionedit"
                                      name="descriptionedit"
                                      maxlength="100"
                                      aria-describedby="namestatus" value="{{$detail->description}}"
                                      onKeyUp=this.value=this.value.toUpperCase();
                               >
                           </div>
                       </div>
                       <div class=" col-md-6 col-sm-12">
                           <div class="form-group">
                               <label for="statusedit">Estatus<span style="color: red">*</span></label>
                               <select name="statusedit"  id="statusedit" class="form-control select2">
{{--                                   <option value="" selected></option>--}}
                                   @foreach($status as $model)
                                       <option value="{{$model->statusid}}"
                                               @if($model->statusid == $detail->statusid)
                                                   selected="selected"
                                               @endif>
                                           {{$model->name}}
                                       </option>
                                   @endforeach
                               </select>
                           </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-md-6 col-sm-12">
                           <div class="form-group">
                               <label for="auxiliary01">Auxiliar 01 </label>
                               <input type="text" class="form-control" id="auxiliary01"
                                      name="auxiliary01"
                                      maxlength="100" value="{{$detail->auxiliary01}}"
                               >
                           </div>
                       </div>
                       <div class="col-md-6 col-sm-12">
                           <div class="form-group">
                               <label for="auxiliary02">Auxiliar 02 </label>
                               <input type="text" class="form-control" id="auxiliary02"
                                      name="auxiliary02"
                                      maxlength="100" value="{{$detail->auxiliary02}}"
                               >
                           </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-md-6 col-sm-12">
                           <div class="form-group">
                               <label for="auxiliary03">Auxiliar 03 </label>
                               <input type="text" class="form-control" id="auxiliary03"
                                      name="auxiliary03"
                                      maxlength="100" value="{{$detail->auxiliary03}}"
                               >
                           </div>
                       </div>
                       <div class="col-md-6 col-sm-12">
                           <div class="form-group">
                               <label for="auxiliary04">Auxiliar 04 </label>
                               <input type="text" class="form-control" id="auxiliary04"
                                      name="auxiliary04"
                                      maxlength="100" value="{{$detail->auxiliary04}}"
                               >
                           </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-md-12 col-sm-12">
                           <div class="form-group">
                               <label for="auxiliary05">Auxiliar 05 </label>
                               <input type="text" class="form-control" id="auxiliary05"
                                      name="auxiliary05"
                                      maxlength="100" value="{{$detail->auxiliary05}}"
                               >
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
       </div>
   </form>
</div>

