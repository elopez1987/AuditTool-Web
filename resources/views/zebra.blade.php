<!DOCTYPE html>
<html>
<head>
    <title>Previsualizar Etiqueta Zebra</title>
    <style>
        textarea { width: 100%; height: 250px; font-family: monospace; }
        img { border: 1px solid #aaa; margin-top: 20px; }
    </style>
</head>
<body>
<h2>Editor de Etiquetas ZPL - YardTrax</h2>

@if(session('error')) <p style="color:red">{{ session('error') }}</p> @endif
@if(session('success')) <p style="color:green">{{ session('success') }}</p> @endif

<form method="POST" action="/etiqueta/preview">
    @csrf
    <textarea name="zpl">{{ old('zpl', session('zpl', $zpl ?? '')) }}</textarea>
    <br><br>
    <button type="submit">🖼️ Previsualizar Etiqueta</button>
</form>

@if(session('image'))
    <h3>Vista Previa:</h3>
    <img src="{{ session('image') }}" alt="ZPL Preview" />
    <form method="POST" action="/etiqueta/imprimir">
        @csrf
        <input type="hidden" name="zpl" value="{{ session('zpl') }}">
        <button type="submit">🖨️ Imprimir en Zebra</button>
    </form>
@endif
</body>
</html>
