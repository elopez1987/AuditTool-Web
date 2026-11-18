@props(['action'])

<form method="POST" action="{{ $action }}" class="d-inline" data-maintenance-action>
    @csrf
    <button type="submit" {{ $attributes->class(['btn', 'btn-outline-danger']) }}>
        {{ $slot }}
    </button>
</form>

@once
    <script src="{{ asset('js/maintenance-actions.js') }}" defer></script>
@endonce
