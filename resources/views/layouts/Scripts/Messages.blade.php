<script type="text/javascript">
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        // "positionClass": "toast-top-center",
        // "positionClass": "toast-bottom-full-width",
        "positionClass": "toast-top-full-width",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

@if(session()->has('success'))
    <script>toastr.success("{{ session('success') }}")</script>
@elseif(session()->has('info'))
    <script>toastr.info("{{ session('info') }}")</script>
@elseif(session()->has('warning'))
    <script>toastr.warning("{{ session('warning') }}")</script>
@elseif(session()->has('error'))
    <script>toastr.error("{{ session('error') }}")</script>
@endif
