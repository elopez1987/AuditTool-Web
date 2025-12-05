<form id="formCreateLabel">
    @csrf

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label for="module_name">{{ ui_label('module_name','Nombre del modulo') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" required id="module_name" name="module_name" maxlength="100"
                       placeholder="Ej: module_name, Crear embarque">
                <small class="form-text text-muted">Nombre de la pantalla donde se usa el campo</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label for="field_name">{{ ui_label('field_name','Nombre del campo') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" required id="field_name" name="field_name" maxlength="100"
                       placeholder="Ej: user_name, email_address">
                <small class="form-text text-muted">Nombre único del campo (snake_case recomendado)</small>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label for="label_text">{{ui_label('label_text','Texto de la etiqueta')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" required id="label_text" name="label_text" maxlength="255"
                       placeholder="Ej: Nombre de Usuario">
                <small class="form-text text-muted">Texto que se mostrará en la interfaz</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label for="type">{{ui_label('type_input','Tipo de input')}}</label>
                <select name="type" id="type" class="form-control">
                    <option value="text" selected>Text</option>
                    <option value="email">Email</option>
                    <option value="number">Number</option>
                    <option value="date">Date</option>
                    <option value="datetime-local">DateTime</option>
                    <option value="tel">Teléfono</option>
                    <option value="url">URL</option>
                    <option value="password">Password</option>
                    <option value="file">File</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="radio">Radio</option>
                    <option value="select">Select</option>
                    <option value="textarea">Textarea</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label for="visible">{{ui_label('visibled_input','¿Visible?')}}</label>
                <select name="visibled" id="visibled" class="form-control select2">
                    <option value="1" selected>Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label for="required">{{ui_label('required_input','¿Requerido?')}}</label>
                <select name="required" id="required" class="form-control select2">
                    <option value="0" selected>No</option>
                    <option value="1">Sí</option>
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>

<script>
$('#formCreateLabel').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '{{ route('label-dictionary.store') }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                $('#labelModal').modal('hide');
                Swal.fire('Creado', response.message, 'success');
                reloadtable();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = '';
                $.each(errors, function(key, value) {
                    errorMsg += value[0] + '<br>';
                });
                Swal.fire('Error de validación', errorMsg, 'error');
            } else {
                Swal.fire('Error', xhr.responseJSON.message || 'No se pudo crear el registro', 'error');
            }
        }
    });
});
</script>
