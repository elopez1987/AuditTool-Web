<form id="formEditLabel">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $uiLabel->id }}">

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label for="module_name">{{ ui_label('module_input_name','Pantalla de referencia') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" required id="module_name" name="module_name" maxlength="100"
                       value="{{ $uiLabel->module_name }}" placeholder="Ej: module_name, 'Crear embarque'">
                <small class="form-text text-muted">Nombre de la pantalla donde se muestra</small>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label for="field_name_edit">{{ ui_label('field_name','Nombre del campo') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" required id="field_name_edit" name="field_name" maxlength="100"
                       value="{{ $uiLabel->field_name }}" placeholder="Ej: user_name, email_address">
                <small class="form-text text-muted">Nombre único del campo (snake_case recomendado)</small>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label for="label_text_edit">{{ui_label('label_text','Texto de la etiqueta')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" required id="label_text_edit" name="label_text" maxlength="255"
                       value="{{ $uiLabel->label_text }}" placeholder="Ej: Nombre de Usuario">
                <small class="form-text text-muted">Texto que se mostrará en la interfaz</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label for="type_edit">{{ui_label('type','Tipo de input')}}</label>
                <select name="type" id="type_edit" class="form-control">
                    <option value="text" {{ $uiLabel->type == 'text' ? 'selected' : '' }}>Text</option>
                    <option value="email" {{ $uiLabel->type == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="number" {{ $uiLabel->type == 'number' ? 'selected' : '' }}>Number</option>
                    <option value="date" {{ $uiLabel->type == 'date' ? 'selected' : '' }}>Date</option>
                    <option value="datetime-local" {{ $uiLabel->type == 'datetime-local' ? 'selected' : '' }}>DateTime</option>
                    <option value="tel" {{ $uiLabel->type == 'tel' ? 'selected' : '' }}>Teléfono</option>
                    <option value="url" {{ $uiLabel->type == 'url' ? 'selected' : '' }}>URL</option>
                    <option value="password" {{ $uiLabel->type == 'password' ? 'selected' : '' }}>Password</option>
                    <option value="file" {{ $uiLabel->type == 'file' ? 'selected' : '' }}>File</option>
                    <option value="checkbox" {{ $uiLabel->type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                    <option value="radio" {{ $uiLabel->type == 'radio' ? 'selected' : '' }}>Radio</option>
                    <option value="select" {{ $uiLabel->type == 'select' ? 'selected' : '' }}>Select</option>
                    <option value="textarea" {{ $uiLabel->type == 'textarea' ? 'selected' : '' }}>Textarea</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label for="visible_edit">{{ui_label('visibled_input','¿Visible?')}}</label>
                <select name="visibled"   id="visibled_edit" class="form-control select2" >
                    <option value="1" {{ $uiLabel->visibled ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !$uiLabel->visibled ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label for="required_edit">{{ui_label('required','¿Requerido?')}}</label>
                <select name="required" id="required_edit" class="form-control select2">
                    <option value="0" {{ !$uiLabel->required ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $uiLabel->required ? 'selected' : '' }}>Sí</option>
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>

<script>
$('#formEditLabel').on('submit', function(e) {
    e.preventDefault();
    
    let id = $(this).find('input[name="id"]').val();
    
    $.ajax({
        url: '/label-dictionary/' + id,
        type: 'PUT',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#labelModal').modal('hide');
                $('#table-label-dictionary').DataTable().ajax.reload();
                Swal.fire('Actualizado', response.message, 'success');
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
                Swal.fire('Error', xhr.responseJSON.message || 'No se pudo actualizar el registro', 'error');
            }
        }
    });
});
</script>
