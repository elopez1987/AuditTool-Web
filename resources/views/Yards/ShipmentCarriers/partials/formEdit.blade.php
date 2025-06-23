<div class="card-input">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Linea de transporte</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="100"
                               aria-describedby="name" value="{{$shipmentcarrier->name}}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="carrierid">Vendor</label>
                        <input type="text" class="form-control" id="carrierid" name="carrierid" maxlength="100"
                               aria-describedby="carrierid" value="{{$shipmentcarrier->carrierid}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auxiliary01">Auxiliar 01 </label>
                        <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                               aria-describedby="auxiliary01" value="{{$shipmentcarrier->auxiliary01}}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auxiliary02">Auxiliar 02 </label>
                        <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                               aria-describedby="auxiliary02" value="{{$shipmentcarrier->auxiliary02}}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="auxiliary03">Auxiliar 03 </label>
                        <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                               aria-describedby="auxiliary03" value="{{$shipmentcarrier->auxiliary03}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="statusid">Estatus <span style="color: red">*</span></label>
                        <select name="statusid" class="form-control select2">
                            @foreach($status as $statu)
                                <option value="{{$statu->statusid}}"
                                        @if($statu->statusid == $shipmentcarrier->statusid)
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
    </div>
</div>

