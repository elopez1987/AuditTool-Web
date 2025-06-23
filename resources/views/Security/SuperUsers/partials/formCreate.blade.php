<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="userid">Usuario <span style="color: red">*</span></label>
            <select name="userid" required class="form-control select2">
                @foreach($users as $user)
                    <option value="{{$user->id}}"> {{$user->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
