<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="typename">Tipo de equipo</label>
            <input type="text" class="form-control" readonly value="{{$type->name}}" id="typename" name="typename">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <h5 class="card-title"> Montacarguistas </h5>
        <select multiple="multiple" size="15"  class="duallistbox-with-filter" name="username[]" required>
            @foreach($users as $user)
                <option value="{{$user->username}}" style="font-size: 13px !important;text-transform: uppercase"> {{$user->name}}</option>
            @endforeach
        </select>
    </div>
</div>

