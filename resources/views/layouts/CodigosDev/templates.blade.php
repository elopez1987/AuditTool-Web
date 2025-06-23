<?php

//Conocer la version de laravel
{{--Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})--}}

{{----Formulario para crear -----}}
<form method="POST" action="{{url('systemwarehouses')}}" enctype="multipart/form-data">
    @csrf

</form>
{{----Formulario para editar ------}}
<form method="POST" action="{{url('systemcenterdistributions/' . $systemcenterdistribution->centerid)}}"
      enctype="multipart/form-data">
    {{ method_field('PUT') }}
    @csrf

</form>
{{--Formulario para eliminar--}}
<form method="POST" action="{{url('receiptasn/' . $receiptasn->id)}}" enctype="multipart/form-data">
    {{ method_field('DELETE') }}
    @csrf

</form>

{{--Select para crear--}}
<div class="col-lg-3 col-md-6 col-sm-12">
    <div class="form-group">
        <label for="carrierid">Linea de transporte
            <span style="color: red">*</span>
        </label>
        <select name="carrierid" class="form-control select2 @error('carrierid') is-invalid @enderror" required>
            <option value="" selected>Seleccione una opción</option>
            @foreach($carriers as $model)
                <option
                    value="{{$model->id}}" {{ old('carrierid') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
            @endforeach
        </select>
        @error('carrierid')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{--Input para crear--}}
<div class="col-lg-3 col-md-6 col-sm-12">
    <div class="form-group">
        <label for="oc">Orden de compra
            <span style="color: red">*</span>
            @error("oc")
            <span class="message-error">{{$message}}</span>
            @enderror
        </label>
        <input type="text"
               class="form-control @error('oc') is-invalid @enderror"
               id="oc"
               name="oc"
               required
               onKeyUp=this.value=this.value.toUpperCase();
               value="{{old('oc')}}"
        >
        @error('oc')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{--Select para editar--}}
<div class="col-lg-2 col-md-6 col-sm-12">
    <div class="form-group">
        <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
        <select required name="carrierid" id="carrierid" class="form-control select2">
            @foreach($carriers as $model)
                <option value="{{$model->id}}"
                    {{ $orderdelivery->carrierid == $model->id ? 'selected' : '' }} >
                    {{$model->name}}
                </option>
            @endforeach
        </select>
        @error('carrierid')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
