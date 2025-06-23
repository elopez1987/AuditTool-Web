<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-edit-{{$item->id}}">
    <form method="POST" action="{{url('editdetailparameter/' . $item->id)}}"
          enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Modificar
                        registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">ID</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$item->id}}"
                                       readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 01</label>
                                <input type="text" class="form-control" id="value01" name="value01"
                                       value="{{$item->value01}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 02</label>
                                <input type="text" class="form-control" id="value02" name="value02"
                                       value="{{$item->value02}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 03</label>
                                <input type="text" class="form-control" id="value03" name="value03"
                                       value="{{$item->value03}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 04</label>
                                <input type="text" class="form-control" id="value04" name="value04"
                                       value="{{$item->value04}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 05</label>
                                <input type="text" class="form-control" id="value05" name="value05"
                                       value="{{$item->value05}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 06</label>
                                <input type="text" class="form-control" id="value06" name="value06"
                                       value="{{$item->value06}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 07</label>
                                <input type="text" class="form-control" id="value07" name="value07"
                                       value="{{$item->value07}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 08</label>
                                <input type="text" class="form-control" id="value08" name="value08"
                                       value="{{$item->value08}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 09</label>
                                <input type="text" class="form-control" id="value09" name="value09"
                                       value="{{$item->value09}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 10</label>
                                <input type="text" class="form-control" id="value10" name="value10"
                                       value="{{$item->value10}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 11</label>
                                <input type="text" class="form-control" id="value11" name="value11"
                                       value="{{$item->value11}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 12</label>
                                <input type="text" class="form-control" id="value12" name="value12"
                                       value="{{$item->value12}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 13</label>
                                <input type="text" class="form-control" id="value13" name="value13"
                                       value="{{$item->value13}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 14</label>
                                <input type="text" class="form-control" id="value14" name="value14"
                                       value="{{$item->value14}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Valor 15</label>
                                <input type="text" class="form-control" id="value15" name="value15"
                                       value="{{$item->value15}}">
                            </div>
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm "
                                data-dismiss="modal">
                            <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                            Cancelar
                        </button>
                        <button type="submit" class="btn waves-effect waves-light btn-outline-secondary btn-sm ">
                            <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
